<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


$htaccess_protection_check = file_get_contents($base_dir . '/.htaccess');

// Htaccess password-protection

// FAILSAFE, FOR ANY EXISTING CRON JOB TO BAIL US OUT IF USER DELETES CACHE DIRECTORY WHERE AN ACTIVE LINKED PASSWORD FILE IS 
// (CAUSING INTERFACE TO CRASH WITH ERROR 500)
if ( preg_match("/Require valid-user/i", $htaccess_protection_check) && !is_readable($base_dir . '/cache/secured/.app_htpasswd') ) {
// Default htaccess root file, WITH NO PASSWORD PROTECTION
$restore_default_htaccess = $ct_cache->save_file($base_dir . '/.htaccess', $ct_cache->htaccess_dir_defaults() ); 
}

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>