<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 

$reset_result = array();


// If we are not activating an existing reset session, run checks before rendering anything...
if ( !$_GET['pass_reset_activate'] ) {
	
	if ( validate_email($app_config['comms']['to_email']) != 'valid'  ) {
	$reset_result['error'][] = "Admin's 'To' Email has NOT been set in the admin configuration, therefore the password CANNOT be reset. Alternatively, you can delete the file '/cache/secured/admin_login_XXXXXXXXXXXXX.dat' in the app directory. This will prompt you to create a new admin login, the next time you use the app.";
	$no_password_reset = 1;
	}
	
	if ( sizeof($admin_login) != 2 ) {
	$reset_result['error'][] = "No admin account exists to reset.";
	$no_password_reset = 1;
	}
	
}
elseif ( $password_reset_denied ) {
$reset_result['error'][] = "Password reset key does not exist.";
$no_password_reset = 1;
}




// Submitted reset request
if ( $_POST['submit_reset'] ) {


	// Run more checks...
	
	if ( trim($_POST['captcha_code']) == '' || trim($_POST['captcha_code']) != '' && strtolower($_POST['captcha_code']) != strtolower($_SESSION['captcha_code']) )	{
	$reset_result['error'][] = "Captcha image code was not correct.";
	$captcha_field_color = '#ff4747';
	}
	
	
	if ( trim($_POST['reset_username']) == '' )	{
	$reset_result['error'][] = "Please fill in the username field.";
	$username_field_color = '#ff4747';
	}
	
	

	// Checks cleared, send email ////////
	if ( sizeof($reset_result['error']) < 1 && trim($_POST['reset_username']) == $admin_login[0] ) {

	$pass_reset_activate = random_hash(32);
	
	$message = "

Please confirm your request to reset the admin password for username '".$admin_login[0]."', in your DFD Cryptocoin Values application.

To complete resetting your admin password, please visit this link below:
". $base_url . "password-reset.php?pass_reset_activate=".$pass_reset_activate."

This link expires in 1 day.

If you did NOT request this password reset (originating from ip address ".$_SERVER['REMOTE_ADDR']."), you can ignore this message, and the account WILL NOT BE RESET.

";
	
  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
   $send_params = array(
          					'email' => array(
          											'subject' => 'DFD Cryptocoin Values - Admin Password Reset',
     													'message' => $message
          											)
          					);
          	
   // Send notifications
   @queue_notifications($send_params);
          	
	store_file_contents($base_dir . '/cache/secured/activation/password_reset_' . random_hash(16) . '.dat', $pass_reset_activate); // Store password reset activation code, to confirm via clicked email link later

	
	}



	// Fake success message, even if the username was not found (so 3rd parties cannot hunt for a valid username)
	if ( sizeof($reset_result['error']) < 1 ) {
	$reset_result['success'][] = "If the username exists, a message has been sent to the registered admin email address for resetting the admin password. Please check your inbox (or spam folder, and mark as 'not spam') in a few minutes.";
	}


}


require("templates/interface/php/header.php");

?>

								
<div style="text-align: center;">

<h3 class='bitcoin'>Reset Admin Account</h3>


	<div style='font-weight: bold;' id='login_alert'>
<?php
	foreach ( $reset_result['error'] as $error ) {
	echo "<br clear='all' /><div class='red_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #ff4747;'> $error </div>";
	}
	
	foreach ( $reset_result['success'] as $success ) {
	echo "<br clear='all' /><div class='green_bright' style='display: inline-block;  font-weight: bold; padding: 15px; margin: 15px; font-size: 21px; border: 4px dotted #10d602;'> $success </div>";
	}
	
	if ( sizeof($reset_result['success']) > 0 ) {
	echo "<p> <a href='".$base_url."'>Return To The Portfolio Main Page</a> </p>";
	}
?>
	</div>


<?php

if ( !$_POST['submit_reset'] && !$no_password_reset || sizeof($reset_result['error']) > 0 && !$no_password_reset ) {
?>

				<form action='' method ='post'>
				
    <div style="display: inline-block; text-align: right; width: 400px;">

				<p><b>Username:</b> <input type='text' name='reset_username' value='<?=$_POST['reset_username']?>' style='<?=( $username_field_color ? 'background: ' . $username_field_color : '' )?>' /></p>
				
    </div>

  	 
  	 
  	 <br clear='all' />
  
  
  	 <div class='align_center' style='display: inline-block;'>
  	 
  	 <p><img id='captcha_image' src='templates/interface/media/images/captcha.php' alt='' class='image_border' />
  	 <br />
  	 <a href='javascript: return false;' onclick='refreshImage("captcha_image", "templates/interface/media/images/captcha.php");' class='bitcoin' style='font-weight: bold;'>Get A Different Image</a>
  	 </p>
  	 
  	 </div>
  
  	 
  	 <br clear='all' />


  	 <div style="display: inline-block; text-align: right; width: 400px;">
  
  	 <p><b>Enter Image Text:</b> <input type='text' name='captcha_code' id='captcha_code' value='' style='<?=( $captcha_field_color ? 'background: ' . $captcha_field_color : '' )?>' /></p>
	
	<p class='align_left' style='font-size: 19px; font-weight: bold; color: #ff4747;' id='captcha_alert'></p>
  
  	 </div>
  	 
  
  	 <br clear='all' />
				  
				<p style='padding: 20px;'><input type='submit' value='Reset Admin Account' /></p>
				
				<input type='hidden' name='submit_reset' value='1' />
				
				</form>
	
<?php
}
?>
</div>			


<?php
require("templates/interface/php/footer.php");
?>