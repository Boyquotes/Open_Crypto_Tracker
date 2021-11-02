<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Scheduled maintenance (run every ~3 hours if NOT cron runtime, OR if runtime is cron every ~1 hours)
//////////////////////////////////////////////////////////////////
if ( $runtime_mode != 'cron' && $ct_cache->update_cache($base_dir . '/cache/events/scheduled-maintenance.dat', (60 * 3) ) == true 
|| $runtime_mode == 'cron' && $ct_cache->update_cache($base_dir . '/cache/events/scheduled-maintenance.dat', (60 * 1) ) == true  ) {
//////////////////////////////////////////////////////////////////



	////////////////////////////////////////////////////////////
	// Maintenance to run only if cron is setup and running
	////////////////////////////////////////////////////////////
	if ( $runtime_mode == 'cron' ) {
	
		// Chart backups...run before any price checks to avoid any potential file lock issues
		if ( $ct_conf['gen']['asset_charts_toggle'] == 'on' && $ct_conf['power']['charts_backup_freq'] > 0 ) {
		$ct_cache->backup_archive('charts-data', $base_dir . '/cache/charts/', $ct_conf['power']['charts_backup_freq']); // No $backup_arch_pass extra param here (waste of time / energy to encrypt charts data backups)
		}
   
	}
	////////////////////////////////////////////////////////////
	// END cron-only maintenance routines
	////////////////////////////////////////////////////////////
	

// Upgrade check
require($base_dir . '/app-lib/php/other/upgrade-check.php');


// Update cached vars...

// Current default primary currency stored to flat file (for checking if we need to reconfigure things for a changed value here)
$ct_cache->save_file($base_dir . '/cache/vars/default_btc_prim_currency_pairing.dat', $default_btc_prim_currency_pairing);
	

// Current app version stored to flat file (for the bash auto-install/upgrade script to easily determine the currently-installed version)
$ct_cache->save_file($base_dir . '/cache/vars/app_version.dat', $app_version);


// Determine / store portfolio cache size
$ct_cache->save_file($base_dir . '/cache/vars/cache_size.dat', $ct_gen->conv_bytes( $ct_gen->dir_size($base_dir . '/cache/') , 3) );


// Cache files cleanup...

// Delete ANY old zip archive backups scheduled to be purged
$ct_cache->delete_old_files($base_dir . '/cache/secured/backups', $ct_conf['power']['backup_arch_del_old'], 'zip');


// Stale cache files cleanup...

$ct_cache->delete_old_files($base_dir . '/cache/events/throttling', 1, 'dat'); // Delete throttling event tracking cache files older than 1 day

$ct_cache->delete_old_files($base_dir . '/cache/events/lite_chart_rebuilds', 3, 'dat'); // Delete lite chart rebuild event tracking cache files older than 3 days

$ct_cache->delete_old_files($base_dir . '/cache/secured/activation', 1, 'dat'); // Delete activation cache files older than 1 day

$ct_cache->delete_old_files($base_dir . '/cache/secured/external_data', 1, 'dat'); // Delete external API cache files older than 1 day

$ct_cache->delete_old_files($base_dir . '/internal-api', 1, 'dat'); // Delete internal API cache files older than 1 day


// Secondary logs cleanup
$logs_cache_cleanup = array(
									$base_dir . '/cache/logs/debug/external_data',
									$base_dir . '/cache/logs/error/external_data',
									);
									
$ct_cache->delete_old_files($logs_cache_cleanup, $ct_conf['power']['logs_purge'], 'dat'); // Delete LOGS API cache files older than $ct_conf['power']['logs_purge'] day(s)


// Update the maintenance event tracking
$ct_cache->save_file($base_dir . '/cache/events/scheduled-maintenance.dat', $ct_gen->time_date_format(false, 'pretty_date_time') );


}
//////////////////////////////////////////////////////////////////
// END SCHEDULED MAINTENANCE
//////////////////////////////////////////////////////////////////

 
 ?>