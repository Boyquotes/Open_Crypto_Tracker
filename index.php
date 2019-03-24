<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

require("templates/default/header.php");

?>


		<ul class='tabs'>
			<li class='tabli'><a href='#values'>Your Coin Values</a></li>
			<li class='tabli'><a href='#links'>External Resource Links</a></li>
			<li class='tabli'><a href='#calculators'>Mining and Interest Calculators</a></li>
			<li class='tabli'><a href='#tools'>Crypto Tools</a></li>
			<li class='tabli'><a href='#amounts'>Update Coin Amounts</a></li>
			<li class='tabli'><a href='#settings'>Program Settings</a></li>
			<li class='tabli'><a style='color:red;' href='#help'>Help</a></li>
		</ul>
		<div id='values' class='tabdiv'>
			<h3 style='display: inline;'>Coin Portfolio Value</h3> (<?=$last_trade_cache?> minute cache)
			<?php
			if ( sizeof($alert_percent) > 1 ) {
				
				if ( $alert_percent[3] == 'visual_only' ) {
				$visual_audio_alerts = 'Visual';
				}
				elseif ( $alert_percent[3] == 'visual_audio' ) {
				$visual_audio_alerts = 'Visual / Audio';
				}
				
			?>
			 &nbsp; &nbsp; <span style='color: <?=( stristr($alert_percent[1], '-') == false ? 'green' : '#ea6b1c' )?>; font-weight: bold;'><?=$visual_audio_alerts?> alerts on (<?=ucfirst($marketcap_site)?> / <?=$alert_percent[1]?>% / <?=$alert_percent[2]?>)</span>
			<?php
			}
			?> &nbsp; &nbsp; <a href='javascript:location.reload(true);' style='font-weight: bold;' title='Refreshing data too frequently may cause API request refusals, especially if request caching settings are too low. It is recommended to use this refresh feature sparingly with lower or disabled cache settings. The current real-time exchange data re-cache setting in config.php is set to <?=$last_trade_cache?> minute(s). A setting of 1 or higher assists in avoiding IP blacklisting by exchanges.'>Refresh Data</a> &nbsp; <select name='select_auto_refresh' id='select_auto_refresh' onchange='auto_reload(this.value);'>
				<option value=''> Manually </option>
				<option value='300' <?=( $_COOKIE['coin_reload'] == '300' ? 'selected' : '' )?>> Every 5 Minutes </option>
				<option value='600' <?=( $_COOKIE['coin_reload'] == '600' ? 'selected' : '' )?>> Every 10 Minutes </option>
				<option value='900' <?=( $_COOKIE['coin_reload'] == '900' ? 'selected' : '' )?>> Every 15 Minutes </option>
				<option value='1800' <?=( $_COOKIE['coin_reload'] == '1800' ? 'selected' : '' )?>> Every 30 Minutes </option>
			</select> &nbsp; <span id='reload_countdown' style='color: red;'></span>
			<p><?php require("sections/coin.values.php"); ?></p>
		</div>
		<div id='links' class='tabdiv'>
			<h3>External Resource Links</h3>
			<?php require("sections/external-resource-links.php"); ?>
		</div>
		<div id='calculators' class='tabdiv'>
			<h3>Mining and Interest Calculators</h3>
			<?php require("sections/calculators.php"); ?>
		</div>
		<div id='tools' class='tabdiv'>
			<h3>Crypto Tools</h3>
			<?php require("sections/tools.php"); ?>
		</div>
		<div id='amounts' class='tabdiv'>
			<h3>Update Coin Amounts</h3>
			<p><?php require("sections/form.php"); ?></p>
		</div>
		<div id='settings' class='tabdiv'>
			<h3>Program Settings</h3>
			<?php require("sections/settings.php"); ?>
		</div>
		<div id='help' class='tabdiv'>
			<h3 style='color: red;'>Help</h3>
			<?php require("sections/help.php"); ?>
		</div>


<?php
require("templates/default/footer.php");
?>

