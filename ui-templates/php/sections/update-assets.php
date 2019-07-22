<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='update_assets_wrapper'>
	
				
	
				<h4 style='display: inline;'>Update Assets</h4>
				
				
				<span id='reload_countdown2' class='red countdown_notice'></span>
				
				
	<p style='margin-top: 10px;'><a style='font-weight: bold;' class='show red' id='disclaimer' href='#show_disclaimer' title='Click to show disclaimer.' onclick='return false;'>Disclaimer!</a></p>
	    
	    
	    
		<div style='display: none;' class='show_disclaimer' align='left'>
			
	     
						<p class='red' style='font-weight: bold;'>
						
						Assets in the default list DO NOT indicate ANY endorsement of these assets (AND removal indicates NO anti-endorsement). These crypto-assets are merely either interesting, historically popular, or (at time off addition) good ROI for cryptocurrency mining hardware. They are only used as <i>examples for demoing feasibility of features</i> in this application, <a href='README.txt' target='_blank'>before you install it on your own PHP-enabled web server and change the list to your favorite assets</a>. 
						
						<br /><br />Always do your due diligence investigating whether you are engaging in trading within acceptable risk levels for your net worth, and consider consulting a professional if you are unaware of what risks are present.
						
						</p>
	
						<p class='red' style='font-weight: bold;'>
						
						<i><u>Semi-simplified version of above important disclaimer / advisory</u>:</i> 
						
						<br /><br /><i>NEVER</i> invest more than you can afford to lose, <i>NEVER</i> buy an asset because of somebody's opinion of it, <i>ALWAYS <u>fully research</u></i> your planned investment beforehand (fundamentals are just as important as chart TA), <i>ALWAYS</i> diversify / balance your portfolio for you <i>(and yours)</i> safety / sanity, <i>ALWAYS <u>buy low</u> AND <u>sell high</u></i> (NOT the other way around), <i><u>ALWAYS AVOID</u></i> <a href='https://twitter.com/hashtag/pumpndump?src=hash' target='_blank'>#pumpndump</a> / <a href='https://twitter.com/hashtag/fomo?src=hash' target='_blank'>#fomo</a> / <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> trading, <i>AND</i> hang on tight until you can't stand fully holding anymore / want to or must make a position exit (percentage) official. 
						
						<br /><br />Best of luck, be careful out there in this cryptoland frontier <i>full of garbage coins, scam coins, and greedy <u>glorified</u> (and NOT so glorified) crooks</i> and their silver tongues! :-o
						
						</p>
	
		
		</div>
			
	
	
	
	<div>
	<a style='font-weight: bold;' href='README.txt' target='_blank'>Editing The Coin List, or Enabling Email / Text / Alexa Exchange Price Alerts</a>
	</div>
	
			
	<div align='left' style='margin-top: 30px; margin-bottom: 15px;'>
	
		
		<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
		<button class='force_button_style' onclick='
		document.coin_amounts.submit();
		'>Save Updated Assets</button>
	
		<form style='display: inline;' name='csv_import' id='csv_import' enctype="multipart/form-data" action="<?=start_page($_GET['start_page'])?>" method="post">
		
	    <input type="hidden" name="csv_check" value="1" />
	    
	    <span id='file_upload'><input style='margin-left: 75px;' name="csv_file" type="file" /></span>
	    
	    <input type="button" onclick='validateForm("csv_import", "csv_file");' value="Import Portfolio From CSV File" />
	    
		</form>
		
		<button style='margin-left: 75px;' class='force_button_style' onclick='
		set_target_action("coin_amounts", "_blank", "download.php");
		document.coin_amounts.submit();
		set_target_action("coin_amounts", "_self", "<?=start_page($_GET['start_page'])?>");
		'>Export Portfolio To CSV File</button>
		
	</div>
	
		
		
	<div style='display: inline-block; border: 2px dotted black; padding: 7px; margin-left: 0px; margin-top: 15px; margin-bottom: 15px;'>
	
		<div align='center' style='font-weight: bold;'>Watch Only</div>
	
		<div style='margin-left: 6px;'><input type='checkbox' onclick='selectAll(this, "coin_amounts");' /> Select / Unselect All <i><u>Unheld</u> Assets</i>	</div>
		
	
	</div>
	
	
	<br clear='all' />	
	
	 <?php
	 if ( $csv_import_fail != NULL ) {
	 ?>
	<br />	
	 <div class='red red_dotted' style='font-weight: bold;'><?=$csv_import_fail?></div>
	<br />	
	<br />	
	 <?php
	 }
	 if ( $csv_import_succeed != NULL ) {
	 ?>
	<br />	
	 <div class='green green_dotted' style='font-weight: bold;'><?=$csv_import_succeed?></div>
	<br />	
	<br />	
	 <?php
	 }
	 ?>
	
	
	
	<form id='coin_amounts' name='coin_amounts' action='<?=start_page($_GET['start_page'])?>' method='post'>
	
	
	<?php
	
	if (is_array($coins_list) || is_object($coins_list)) {

	    
	    $zebra_stripe = 'long_list_odd';
	    foreach ( $coins_list as $coin_array_key => $coin_array_value ) {
		
		 $rand_id = rand(10000000,100000000);
	    
	    $field_var_pairing = strtolower($coin_array_key) . '_pairing';
	    $field_var_market = strtolower($coin_array_key) . '_market';
	    $field_var_amount = strtolower($coin_array_key) . '_amount';
	    $field_var_paid = strtolower($coin_array_key) . '_paid';
	    $field_var_leverage = strtolower($coin_array_key) . '_leverage';
	    $field_var_margintype = strtolower($coin_array_key) . '_margintype';
	    $field_var_watchonly = strtolower($coin_array_key) . '_watchonly';
	    $field_var_restore = strtolower($coin_array_key) . '_restore';
	    
	    
	        if ( $_POST['submit_check'] == 1 ) {
	        $coin_pairing_id = $_POST[$field_var_pairing];
	        $coin_market_id = $_POST[$field_var_market];
	        $coin_amount_value = remove_number_format($_POST[$field_var_amount]);
	        $coin_paid_value = remove_number_format($_POST[$field_var_paid]);
	        $coin_leverage_value = $_POST[$field_var_leverage];
	        $coin_margintype_value = $_POST[$field_var_margintype];
	        }
	        elseif ( $run_csv_import == 1 ) {
	        	
	        
	        		foreach( $csv_file_array as $key => $value ) {
	        		
	        			if ( strtoupper($coin_array_key) == strtoupper($key) ) {
	        		 	$coin_pairing_id = $value[6];
	        			$coin_market_id = $value[5];
	        		 	$coin_amount_value = remove_number_format($value[1]);
	       		 	$coin_paid_value = remove_number_format($value[2]);
	       		 	$coin_leverage_value = $value[3];
	        			$coin_margintype_value = strtolower($value[4]);
	       		 	}
	        	
	        		}
	        		
	        
	        }
	        
	
	    
	    	  // Cookies
	        if ( !$run_csv_import && $_COOKIE['coin_pairings'] ) {
	        
	        $all_coin_pairings_cookie_array = explode("#", $_COOKIE['coin_pairings']);
	        
		if (is_array($all_coin_pairings_cookie_array) || is_object($all_coin_pairings_cookie_array)) {
		    
		    foreach ( $all_coin_pairings_cookie_array as $coin_pairings ) {
		        
		    $single_coin_pairings_cookie_array = explode("-", $coin_pairings);
		    
		    $coin_symbol = strtoupper(preg_replace("/_pairing/i", "", $single_coin_pairings_cookie_array[0]));  
		    
		        if ( $coin_symbol == strtoupper($coin_array_key) ) {
		        $coin_pairing_id = $single_coin_pairings_cookie_array[1];
		        }
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	        
	        
	        if ( !$run_csv_import && $_COOKIE['coin_markets'] ) {
	        
	        $all_coin_markets_cookie_array = explode("#", $_COOKIE['coin_markets']);
	        
		if (is_array($all_coin_markets_cookie_array) || is_object($all_coin_markets_cookie_array)) {
		    
		    foreach ( $all_coin_markets_cookie_array as $coin_markets ) {
		        
		    $single_coin_markets_cookie_array = explode("-", $coin_markets);
		    
		    $coin_symbol = strtoupper(preg_replace("/_market/i", "", $single_coin_markets_cookie_array[0]));  
		    
		        if ( $coin_symbol == strtoupper($coin_array_key) ) {
		        $coin_market_id = $single_coin_markets_cookie_array[1];
		        }
		    
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_amounts'] ) {
	        
	        $all_coin_amounts_cookie_array = explode("#", $_COOKIE['coin_amounts']);
	        
		if (is_array($all_coin_amounts_cookie_array) || is_object($all_coin_amounts_cookie_array)) {
		    
		    foreach ( $all_coin_amounts_cookie_array as $coin_amounts ) {
		        
		    $single_coin_amounts_cookie_array = explode("-", $coin_amounts);
		    
		    $coin_symbol = strtoupper(preg_replace("/_amount/i", "", $single_coin_amounts_cookie_array[0]));  
		    
		    		// We don't need remove_number_format() for cookie data, because it was already done creating the cookies
					if ( $coin_symbol == strtoupper($coin_array_key) ) {
					$coin_amount_value = floattostr($single_coin_amounts_cookie_array[1]);
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_paid'] ) {
	        
	        $all_coin_paid_cookie_array = explode("#", $_COOKIE['coin_paid']);
	        
		if (is_array($all_coin_paid_cookie_array) || is_object($all_coin_paid_cookie_array)) {
		    
		    foreach ( $all_coin_paid_cookie_array as $coin_paid ) {
		        
		    $single_coin_paid_cookie_array = explode("-", $coin_paid);
		    
		    $coin_symbol = strtoupper(preg_replace("/_paid/i", "", $single_coin_paid_cookie_array[0]));  
		    		
		    		// We don't need remove_number_format() for cookie data, because it was already done creating the cookies
					if ( $coin_symbol == strtoupper($coin_array_key) ) {
					$coin_paid_value = floattostr($single_coin_paid_cookie_array[1]);
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_leverage'] ) {
	        
	        $all_coin_leverage_cookie_array = explode("#", $_COOKIE['coin_leverage']);
	        
		if (is_array($all_coin_leverage_cookie_array) || is_object($all_coin_leverage_cookie_array)) {
		    
		    foreach ( $all_coin_leverage_cookie_array as $coin_leverage ) {
		        
		    $single_coin_leverage_cookie_array = explode("-", $coin_leverage);
		    
		    $coin_symbol = strtoupper(preg_replace("/_leverage/i", "", $single_coin_leverage_cookie_array[0]));  
		    
					if ( $coin_symbol == strtoupper($coin_array_key) ) {
					$coin_leverage_value = $single_coin_leverage_cookie_array[1];
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_margintype'] ) {
	        
	        $all_coin_margintype_cookie_array = explode("#", $_COOKIE['coin_margintype']);
	        
		if (is_array($all_coin_margintype_cookie_array) || is_object($all_coin_margintype_cookie_array)) {
		    
		    foreach ( $all_coin_margintype_cookie_array as $coin_margintype ) {
		        
		    $single_coin_margintype_cookie_array = explode("-", $coin_margintype);
		    
		    $coin_symbol = strtoupper(preg_replace("/_margintype/i", "", $single_coin_margintype_cookie_array[0]));  
		    
					if ( $coin_symbol == strtoupper($coin_array_key) ) {
					$coin_margintype_value = $single_coin_margintype_cookie_array[1];
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	        
	        
	    $selected_pairing = ( $coin_pairing_id ? $coin_pairing_id : 'btc' );
	    
	    
	    	if ( strtoupper($coin_array_key) == 'USD' ) {
	    	$coin_amount_decimals = 2;
	    	}
	    	else {
	    	$coin_amount_decimals = 8;
	    	}
	    
	  	 $coin_amount_value = pretty_numbers($coin_amount_value, $coin_amount_decimals, TRUE); // TRUE = Show even if low value is off the map, just for UX purposes tracking token price only, etc
	    
	  	 $coin_paid_value = pretty_numbers($coin_paid_value, 8); // Show low value only with 8 decimals minimum
	  	 
	    	
	    ?>
	    
	    <div class='<?=$zebra_stripe?> long_list_taller' style='white-space: nowrap;'> 
	       
	       
	       <input type='checkbox' value='<?=strtolower($coin_array_key)?>' id='<?=$field_var_watchonly?>' onchange='watch_toggle(this);' <?=( remove_number_format($coin_amount_value) > 0 && remove_number_format($coin_amount_value) <= '0.000000001' ? 'checked' : '' )?> /> &nbsp;
				    
				    
			<b class='blue'><?=$coin_array_value['coin_name']?> (<?=strtoupper($coin_array_key)?>)</b> /  
	       
	       
				    <select onchange='
				    
				    $("#<?=$field_var_market?>_lists").children().hide(); 
				    $("#" + this.value + "<?=$coin_array_key?>_pairs").show(); 
				    
				    $("#<?=$field_var_market?>").val( $("#" + this.value + "<?=$coin_array_key?>_pairs option:selected").val() );
				    
				    ' id='<?=$field_var_pairing?>' name='<?=$field_var_pairing?>'>
					<?php
					foreach ( $coin_array_value['market_pairing'] as $pairing_key => $pairing_id ) {
					 $loop = $loop + 1;
					 
						if ( $coin_array_key == 'BTC' ) {
						?>
						<option value='btc' selected> USD </option>
						<?php
						}
						else{
					?>
					<option value='<?=$pairing_key?>' <?=( isset($_POST[$field_var_pairing]) && ($_POST[$field_var_pairing]) == $pairing_key || isset($coin_pairing_id) && ($coin_pairing_id) == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper(preg_replace("/_/i", " ", $pairing_key))?> </option>
					<?php
							}
					
									foreach ( $coin_array_value['market_pairing'][$pairing_key] as $market_key => $market_id ) {
							$loop2 = $loop2 + 1;
							
								$html_market_list[$pairing_key] .= "\n<option value='".$loop2."'" . ( isset($_POST[$field_var_market]) && ($_POST[$field_var_market]) == $loop2 || isset($coin_market_id) && ($coin_market_id) == $loop2 ? ' selected ' : '' ) . ">" . ucwords(preg_replace("/_/i", " ", $market_key)) . " </option>\n";
								
									}
								$loop2 = NULL;
							
							
					}
					$loop = NULL;
					?>
				    </select> 
				    
				    
				     @ <input type='hidden' id='<?=$field_var_market?>' name='<?=$field_var_market?>' value='<?php
				     
				     if ( $_POST[$field_var_market] ) {
				     echo $_POST[$field_var_market];
				     }
				     elseif ( $coin_market_id ) {
				     echo $coin_market_id;
				     }
				     else {
						echo '1';
				     }
				     
				     ?>'>
				     
				     
				     <span id='<?=$field_var_market?>_lists' style='display: inline;'>
				    <?php
				    
				    foreach ( $html_market_list as $key => $value ) {
				    ?>
				    
				    <select onchange ='
				    
				    $("#<?=$field_var_market?>").val( this.value );
				    
				    ' id='<?=$key.$coin_array_key?>_pairs' style='display: <?=( $selected_pairing == $key ? 'inline' : 'none' )?>;'><?=$html_market_list[$key]?></select>
				    
				    <?php
				    }
				    $html_market_list = NULL;
				    ?>
				    
				    </span> &nbsp;  &nbsp; 
				    
				    
			
	     			 <b>Amount:</b> <input type='text' size='11' id='<?=$field_var_amount?>' name='<?=$field_var_amount?>' value='<?=$coin_amount_value?>' onkeyup='
	     
	     $("#<?=strtolower($coin_array_key)?>_restore").val( $("#<?=strtolower($coin_array_key)?>_amount").val() );
	     
	     ' onblur='
	     
	     $("#<?=strtolower($coin_array_key)?>_restore").val( $("#<?=strtolower($coin_array_key)?>_amount").val() );
	     
	     ' <?=( remove_number_format($coin_amount_value) > 0 && remove_number_format($coin_amount_value) <= '0.000000001' ? 'readonly' : '' )?> /> <span class='blue'><?=strtoupper($coin_array_key)?></span>  &nbsp;  &nbsp; 
			    
			
	     <b>Paid (per-token):</b> $<input type='text' size='10' id='<?=$field_var_paid?>' name='<?=$field_var_paid?>' value='<?=$coin_paid_value?>' />   &nbsp;  &nbsp; 
	     
	     
	     <b>Margin Leverage:</b> 
	     
	     <select name='<?=$field_var_leverage?>' id='<?=$field_var_leverage?>' onchange='
	     alert(" Leverage trading in crypto assets is \n EXTREMELY RISKY. NEVER put more \n than ~5% of your crypto investments \n in leveraged trades EVER, OR YOU \n ###COULD LOSE EVERYTHING###. ");
	     '>
	     <option value='0' <?=( $coin_leverage_value == 0 ? 'selected' : '' )?>> None </option>
	     <option value='2' <?=( $coin_leverage_value == 2 ? 'selected' : '' )?>> 2x </option>
	     <option value='3' <?=( $coin_leverage_value == 3 ? 'selected' : '' )?>> 3x </option>
	     <option value='4' <?=( $coin_leverage_value == 4 ? 'selected' : '' )?>> 4x </option>
	     <option value='5' <?=( $coin_leverage_value == 5 ? 'selected' : '' )?>> 5x </option>
	     <option value='10' <?=( $coin_leverage_value == 10 ? 'selected' : '' )?>> 10x </option>
	     <option value='25' <?=( $coin_leverage_value == 25 ? 'selected' : '' )?>> 25x </option>
	     <option value='50' <?=( $coin_leverage_value == 50 ? 'selected' : '' )?>> 50x </option>
	     <option value='100' <?=( $coin_leverage_value == 100 ? 'selected' : '' )?>> 100x </option>
	     </select> 
	     
	     
	     <select name='<?=$field_var_margintype?>' id='<?=$field_var_margintype?>'>
	     <option value='long' <?=( $coin_margintype_value == 'long' ? 'selected' : '' )?>> Long </option>
	     <option value='short' <?=( $coin_margintype_value == 'short' ? 'selected' : '' )?>> Short </option>
	     </select> 
	     
	     
		<img id='leverage_trading_notes_<?=$rand_id?>' src='ui-templates/media/images/info.png' alt='' width='30' border='0' style='position: relative; left: -5px;' /> 
	 <script>
	
			var leverage_trading_notes = '<h5 align="center" class="yellow" style="position: relative; white-space: nowrap;">Tracking Long / Short Margin Leverage Trades</h5>'
			
			
			+'<p class="coin_info extra_margins red" style="white-space: normal; max-width: 600px; font-size: 13px;"><b>*Leverage trading is <u>EXTREMELY RISKY</u> (and even more so in crypto markets). Never put more than ~5% of your total investment worth into leverage trades, or you will <u>RISK LOSING EVERYTHING</u>!</b></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">Set the "Amount" of tokens to match your margin leverage deposit (example: buying 1 BTC @ 5x leverage would be 0.2 BTC in the "Amount" field in this app). You\'ll also need to fill in the "Paid (per-token)" field with the average price paid in USD per-token. Finally, set the "Margin Leverage" fields to match your leverage and whether you are long or short. When you are done, click "Save Updated Assets".</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">To see your margin leverage stats after updating your portfolio, go to the bottom of the Portfolio page, where you\'ll find a stats section. Hovering over the "I" icon next to those summary stats will display additional stats per-asset. There is also an "I" icon in the far right table column (USD Subtotal) per-asset, which you can hover over for margin leverage stats too.</p>'
			
			+'<p class="coin_info"><span class="yellow"> </span></p>';
		
		
			$('#leverage_trading_notes_<?=$rand_id?>').balloon({
			html: true,
			position: "left",
			contents: leverage_trading_notes,
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "1px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.95",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
	     
	     
	     <input type='hidden' id='<?=$field_var_restore?>' name='<?=$field_var_restore?>' value='<?=( remove_number_format($coin_amount_value) > 0 && remove_number_format($coin_amount_value) <= '0.000000001' ? '' : $coin_amount_value )?>' />
				
				
	    </div>
	    
	    
	    <?php
	    
		 	if ( $zebra_stripe == 'long_list_odd' ) {
		 	$zebra_stripe = 'long_list_even';
		 	}
		 	else {
		 	$zebra_stripe = 'long_list_odd';
		 	}
	    
	    $coin_symbol = NULL;
	    
	    $coin_pairing_id = NULL;
	    $coin_market_id = NULL;
	    $coin_amount_value = NULL;
 		 $coin_paid_value = NULL;
	    
	    }
	    
	    
	}
	?>
	
	<div class='long_list_end'> &nbsp; </div>
	
	
	<input type='hidden' id='submit_check' name='submit_check' value='1' />
	
	<input type='hidden' id='theme_selected' name='theme_selected' value='<?=$theme_selected?>' />
	
	<input type='hidden' id='sort_by' name='sort_by' value='<?=($sorted_by_col)?>|<?=($sorted_by_asc_desc)?>' />
	
	<input type='hidden' id='use_cookies' name='use_cookies' value='<?php echo ( $_COOKIE['coin_amounts'] != '' ? '1' : ''); ?>' />
	
	<input type='hidden' id='use_notes' name='use_notes' value='<?php echo ( $_COOKIE['notes_reminders'] != '' ? '1' : ''); ?>' />
	
	<input type='hidden' id='use_alert_percent' name='use_alert_percent' value='<?=( $_POST['use_alert_percent'] != '' ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] )?>' />
	
	<input type='hidden' id='show_charts' name='show_charts' value='<?=( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] )?>' />
			
	<p><input type='submit' value='Save Updated Assets' /></p>
	
	</form>
	
	
			    
			    
</div> <!-- force_1200px_wrapper END -->



