<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Sanitize any user inputs VERY EARLY (for security / compatibility)
foreach ( $_GET as $scan_get_key => $unused ) {
$_GET[$scan_get_key] = $ct['gen']->sanitize_requests('get', $scan_get_key, $_GET[$scan_get_key]);
}
foreach ( $_POST as $scan_post_key => $unused ) {
$_POST[$scan_post_key] = $ct['gen']->sanitize_requests('post', $scan_post_key, $_POST[$scan_post_key]);
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If user is logging out (run immediately after setting PRIMARY vars, for quick runtime)
if ( $_GET['logout'] == 1 && $ct['gen']->pass_sec_check($_GET['admin_hashed_nonce'], 'logout') ) {
	
// Try to avoid edge-case bug where sessions don't delete, using our hardened function logic
$ct['gen']->hardy_sess_clear(); 

// Delete admin login cookie
unset($_COOKIE['admin_auth_' . $ct['gen']->id()]);
$ct['gen']->store_cookie('admin_auth_' . $ct['gen']->id(), '', time()-3600); // Delete

header("Location: index.php");
exit;

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// CSRF attack protection for downloads EXCEPT backup downloads (which are secured by requiring the nonce)
if ( $ct['runtime_mode'] == 'download' && !isset($_GET['backup']) && $_GET['token'] != $ct['gen']->nonce_digest('download') ) {
$ct['gen']->log('security_error', 'aborted, security token mis-match/stale from ' . $_SERVER['REMOTE_ADDR'] . ', for request: ' . $_SERVER['REQUEST_URI'] . ' (try reloading the app)');
$ct['cache']->error_log();
echo "Aborted, security token mis-match/stale, try reloading the app.";
exit;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Toggle ADMIN SECURITY LEVEL DEFAULTS (#MUST# BE SET BEFORE load-config-by-security-level.php)
// (EXCEPT IF 'opt_admin_sec' from authenticated admin is verified [that MUST be in config-init.php])

// If not updating, and cached var already exists
if ( file_exists($ct['base_dir'] . '/cache/vars/admin_area_sec_level.dat') ) {
$admin_area_sec_level = trim( file_get_contents($ct['base_dir'] . '/cache/vars/admin_area_sec_level.dat') );

     // Backwards compatibility (upgrades from < v6.00.27)
     if ( $admin_area_sec_level == 'enhanced' ) {
     $admin_area_sec_level = 'medium';
     }
     
}
// Else, default to high admin security
else {
$admin_area_sec_level = 'high';
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/admin_area_sec_level.dat', $admin_area_sec_level);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Toggle 2FA DEFAULTS (#MUST# BE SET IMMEADIATELY AFTER ADMIN SECURITY LEVEL)
// (EXCEPT IF 'opt_admin_2fa' from authenticated admin is verified [that MUST be in config-init.php])

// If not updating, and cached var already exists
if ( file_exists($ct['base_dir'] . '/cache/vars/admin_area_2fa.dat') ) {
$admin_area_2fa = trim( file_get_contents($ct['base_dir'] . '/cache/vars/admin_area_2fa.dat') );
}
// Else, default to 2FA disabled
else {
$admin_area_2fa = 'off';
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/admin_area_2fa.dat', $admin_area_2fa);
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// IF NOT FAST RUNTIMES
if ( !$is_fast_runtime ) {
     
     
     // Secured cache files
     $secured_cache_files = $ct['gen']->sort_files($ct['base_dir'] . '/cache/secured', 'dat', 'desc');
     
     // WE LOAD ct_conf WAY EARLIER, SO IT'S NOT INCLUDED HERE
     
     foreach( $secured_cache_files as $secured_file ) {
     	
     	
     	// MIGRATE PEPPER VAR TO NEW SECRET VAR (FOR V6.00.26 BACKWARDS COMPATIBILITY)
     	if ( preg_match("/pepper_var_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( $migrate_to_auth_secret ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
     		$migrate_to_auth_secret = trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) );
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file); // DELETE BECAUSE WE ARE MIGRATING TO A NEW VAR NAME
     		}
     	
     	
     	}
     	
     	
     	// Secret var (for google authenticator etc)
     	if ( preg_match("/secret_var_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file (OR ARE MIGRATING), delete any stale ones
     		if ( $auth_secret || $migrate_to_auth_secret ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
     		$auth_secret = trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) );
     		}
     	
     	
     	}
     	
     	
     	// MASTER webhook secret key (for secure webhook communications)
     	elseif ( preg_match("/webhook_master_key_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( $webhook_master_key ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
               $webhook_master_key = trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) );
     		}
     	
     	
     	}
     	
     	
     	// PER-SERVICE webhook secret keys (for secure webhook communications)
     	elseif ( preg_match("/_webhook_key_/i", $secured_file) ) {
     		
          $webhook_plug = preg_replace("/_webhook_key_(.*)/i", "", $secured_file);
          
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( isset($int_webhooks[$webhook_plug]) ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
     	     $int_webhooks[$webhook_plug] = trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) );
     		}
     	
     	
     	unset($webhook_plug); 
     	
     	}
     	
     	
     	// Internal API key (for secure API communications with other apps)
     	elseif ( preg_match("/int_api_key_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( $int_api_key ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
     		$int_api_key = trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) );
     		}
     	
     	
     	}
     	
     	
     	// Stored admin login user / hashed password (for admin login authentication)
     	elseif ( preg_match("/admin_login_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( is_array($stored_admin_login) ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
     		$active_admin_login_path = $ct['base_dir'] . '/cache/secured/' . $secured_file; // To easily delete, if we are resetting the login
     		$stored_admin_login = explode("||", trim( file_get_contents($active_admin_login_path) ) );
     		}
     	
     	
     	}
     	
     
     }
     
     
     //////////////////////////////////////////////////////////////////////////////////////////////////////////
     
     
     // If no secret var
     if ( !$auth_secret || $migrate_to_auth_secret ) {
     
     $secure_128bit_hash = $ct['gen']->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
     
     
          if ( $migrate_to_auth_secret ) {
          $secure_256bit_hash = $migrate_to_auth_secret;
          }
          else {
          $secure_256bit_hash = $ct['gen']->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
          }
     	
     	
     	// Halt the process if an issue is detected safely creating a random hash
     	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
     		
     	$ct['gen']->log(
     				'security_error',
     				'Cryptographically secure pseudo-random bytes could not be generated for secret var (in secured cache storage), secret var creation aborted to preserve security'
     				);
     	
     	}
     	else {
     	$ct['cache']->save_file($ct['base_dir'] . '/cache/secured/secret_var_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
     	$auth_secret = $secure_256bit_hash;
     	}
     
     
     }
     
     
}
// END IF NOT FAST RUNTIMES

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>