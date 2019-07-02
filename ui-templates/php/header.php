<?php

// Start measuring page load time
$load_time = microtime();
$load_time = explode(' ', $load_time);
$load_time = $load_time[1] + $load_time[0];
$start = $load_time;

$runtime_mode = 'ui';

$tablesort_theme = 'default';

require("config.php");

?><!DOCTYPE html>
<html>

<!-- /*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */ -->

<head>

	<title>DFD Cryptocoin Values - Open source / free private cryptocurrency investment portfolio tracker, with email / text / Alexa alerts, charts, mining calculators, and other crypto tools</title>
    
	<meta charset="UTF-8">
	<meta name="robots" content="noindex,nofollow"> <!-- Keeps this URL private (search engines won't add this URL to their search indexes) -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<link rel="stylesheet" href="ui-templates/css/bootstrap/bootstrap.min.css" type="text/css" />

	<link rel="stylesheet" href="ui-templates/css/style.css" type="text/css" />

	<link rel="stylesheet" href="ui-templates/css/modaal.css" type="text/css" />


	<style>

	@import "ui-templates/css/tablesorter/theme.<?=$tablesort_theme?>.css";
	
	.tablesorter-<?=$tablesort_theme?> .header, .tablesorter-<?=$tablesort_theme?> .tablesorter-header {
    white-space: nowrap;
	}
	
	</style>


	<script type="text/javascript" src="app-lib/js/jquery/jquery-3.4.1.min.js"></script>

	<script type="text/javascript" src="app-lib/js/jquery/bootstrap.min.js"></script>

	<script type="text/javascript" src="app-lib/js/jquery/jquery.tablesorter.min.js"></script>

	<script type="text/javascript" src="app-lib/js/jquery/jquery.tablesorter.widgets.min.js"></script>

	<script type="text/javascript" src="app-lib/js/jquery/jquery.balloon.min.js"></script>

	<script type="text/javascript" src="app-lib/js/functions.js"></script>

	<script type="text/javascript" src="app-lib/js/modaal.js"></script>

	<?php
	if ( $charts_page == 'on' ) {
	?>
	<script type="text/javascript" src="app-lib/js/zingchart.min.js"></script>
	<?php
	}
	?>

	<script type="text/javascript" src="app-lib/js/init.js"></script>


	<script>
	
	var sorted_by_col = <?=$sorted_by_col?>;
	var sorted_by_asc_desc = <?=$sorted_by_asc_desc?>;
	var tablesort_theme = '<?=$tablesort_theme?>';
	
	var btc_usd_value = '<?=number_format( get_btc_usd('binance')['last_trade'], 2, '.', '' )?>';
	
	</script>


	<link rel="shortcut icon" href="ui-templates/media/images/favicon.png">
	<link rel="icon" href="ui-templates/media/images/favicon.png">

</head>
<body>
    
    <audio preload="metadata" id="audio_alert">
      <source src="ui-templates/media/audio/Smoke-Alarm-SoundBible-1551222038.mp3">
      <source src="ui-templates/media/audio/Smoke-Alarm-SoundBible-1551222038.ogg">
    </audio>

    <div align='center' style='width: 100%; min-width: 1200px; margin: auto;'>
    <h2>DFD Cryptocoin Values - Cryptocurrency Portfolio Tracker</h2>
            <div align='left' style=' margin: 0px; min-width: 1200px; display: inline;'>
        <!- header END -->


