<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// HTML field formatting CONFIGs for admin settings

$admin_ui_menus['dev']['radio'] = array(

                                           'error_reporting' => array(
                                                                     'PHP Error Reporting' => array(
                                                                                                   'Off' => 0,
                                                                                                   'On' => -1
                                                                                                   )
                                                                     ),
                                                                     
                                                                     
                                           );

// END of $admin_ui_menus['dev']['radio']
                                           

//var_dump($admin_ui_menus);

?>
	
	<div class='bitcoin align_center' style='margin-bottom: 20px;'>(advanced configuration, handle with care)</div>
	
	
	<p class='bitcoin bitcoin_dotted' style='display: <?=( $beta_v6_admin_pages == 'on' ? 'block' : 'none' )?>;'>
	
	These sections / category pages will be INCREMENTALLY populated with the corrisponding admin configuration options, over a period of time AFTER the initial v6.00.1 release (v6.00.1 will only test the back-end / under-the-hood stability of THE ON / OFF MODES OF THE BETA v6 Admin Interface). <br /><br />You may need to turn off the BETA v6 Admin Interface to edit any UNFINISHED SECTIONS by hand in the config files (config.php in the app install folder, and any plug-conf.php files in the plugins folder).
	
	</p>
	

				
				
	<p> Coming Soon&trade; </p>
				
	<p class='bitcoin'> Editing these settings is <i>currently only available manually (UNLESS you turn on the BETA v6 Admin Interface)</i>, by updating the file config.php (in this app's main directory: <?=$base_dir?>) with a text editor.</p>
				
	
		    