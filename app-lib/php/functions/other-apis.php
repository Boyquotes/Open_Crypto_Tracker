<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// !!!!!!! MAKE SURE API'S TLD HAS SUPPORT ADDED IN $app_config['top_level_domain_map'] @ config.php !!!!!!!
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


//////////////////////////////////////////////////////////


function grin_api($request) {
 
global $app_config;
 		
$json_string = 'https://api.grinmint.com/v1/networkStats';

$jsondata = @api_data('url', $json_string, $app_config['chainstats_cache_time']);
    
$data = json_decode($jsondata, true);
    
return $data[$request];
  
}


//////////////////////////////////////////////////////////


function monero_api($request) {
 
global $app_config;
 		
 	$json_string = 'https://moneroblocks.info/api/get_stats';
 	$jsondata = @api_data('url', $json_string, $app_config['chainstats_cache_time']);
  	
  	$data = json_decode($jsondata, true);
    
		if ( !$data ) {
		return;
		}
		else {
		
		return $data[$request];
		  
		}
  
}


//////////////////////////////////////////////////////////


function bitcoin_api($request) {
 
global $app_config;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'https://blockchain.info/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'https://blockchain.info/q/getdifficulty';
		  
		}
		
    $data = @api_data('url', $string, $app_config['chainstats_cache_time']);
    
  return (float)$data;
  
}


//////////////////////////////////////////////////////////


function dogecoin_api($request) {
 
global $app_config;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'https://dogechain.info/chain/Dogecoin/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'https://dogechain.info/chain/Dogecoin/q/getdifficulty';
		  
		}
		
    $data = @api_data('url', $string, $app_config['chainstats_cache_time']);
    
  return (float)$data;
  
}


//////////////////////////////////////////////////////////


function litecoin_api($request) {
 
global $app_config;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'http://explorer.litecoin.net/chain/Litecoin/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'http://explorer.litecoin.net/chain/Litecoin/q/getdifficulty';
		  
		}
		
    $data = @api_data('url', $string, $app_config['chainstats_cache_time']);
    
  return (float)$data;
  
  
}


//////////////////////////////////////////////////////////


function decred_api($type, $request) {
 
global $app_config, $runtime_mode;

	if ( $runtime_mode != 'ui' ) {
	return false;  // We only use the block reward config file call for UI data, can skip the API request if not running the UI.
	}
 	else {
 		
 		if ( $type == 'block' ) {
 		$json_string = 'https://explorer.dcrdata.org/api/block/best/verbose';
 		}
 		elseif ( $type == 'subsidy' ) {
 		$json_string = 'https://explorer.dcrdata.org/api/block/best/subsidy';
 		}
 		
 		$jsondata = @api_data('url', $json_string, $app_config['chainstats_cache_time']);
  		
  		$data = json_decode($jsondata, true);
   	 
		return $data[$request];
			  
			
	}
  
}


//////////////////////////////////////////////////////////

// https://core.telegram.org/bots/api#making-requests
function telegram_user_data() {
	
global $app_config;

// Don't cache data, we are storing it as a specific (secured) cache var instead
$get_telegram_chatroom_data = @api_data('url', 'https://api.telegram.org/bot'.$app_config['telegram_bot_token'].'/getUpdates', 0);
		
$telegram_chatroom = json_decode($get_telegram_chatroom_data, true);

$telegram_chatroom = $telegram_chatroom['result']; 

	foreach( $telegram_chatroom as $chat_key => $chat_unused ) {
	
		// Overwrites any earlier value while looping, so we have the latest data
		if ( $telegram_chatroom[$chat_key]['message']['chat']['username'] == trim($app_config['telegram_your_username']) ) {
		$telegram_user_data = $telegram_chatroom[$chat_key];
		}
	
	}

return $telegram_user_data;

}


//////////////////////////////////////////////////////////


function etherscan_api($block_info) {
 
global $base_dir, $app_config;


	if ( $app_config['etherscanio_api_key'] == '' ) {
	return false;
	}


  $json_string = 'https://api.etherscan.io/api?module=proxy&action=eth_blockNumber&apikey=' . $app_config['etherscanio_api_key'];
  $jsondata = @api_data('url', $json_string, $app_config['chainstats_cache_time']);
    
  $data = json_decode($jsondata, true);
  
  $block_number = $data['result'];
    
    	if ( !$block_number ) {
    	return;
    	}
    	else {
    		
    		// Non-dynamic cache file name, because filename would change every recache and create cache bloat
    		if ( update_cache_file('cache/secured/apis/eth-stats.dat', $app_config['chainstats_cache_time'] ) == true ) {
			
  			$json_string = 'https://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag='.$block_number.'&boolean=true&apikey=' . $app_config['etherscanio_api_key'];
  			$jsondata = @api_data('url', $json_string, 0); // ZERO TO NOT CACHE DATA (WOULD CREATE CACHE BLOAT)
    		
    		store_file_contents($base_dir . '/cache/secured/apis/eth-stats.dat', $jsondata);
    		
    		$data = json_decode($jsondata, true);
    		
    		return $data['result'][$block_info];
    		
    		}
    		else {
    			
    		$cached_data = trim( file_get_contents('cache/secured/apis/eth-stats.dat') );
    		
    		$data = json_decode($cached_data, true);
    		
    		return $data['result'][$block_info];

    		}
  
    	}
  
}


//////////////////////////////////////////////////////////


function coingecko_api($force_primary_currency=null) {
	
global $app_config;

$result = array();

// Don't overwrite global
$coingecko_primary_currency = ( $force_primary_currency != null ? strtolower($force_primary_currency) : strtolower($app_config['btc_primary_currency_pairing']) );

$jsondata = @api_data('url', 'https://api.coingecko.com/api/v3/coins/markets?per_page='.$app_config['marketcap_ranks_max'].'&page=1&vs_currency='.$coingecko_primary_currency.'&price_change_percentage=1h,24h,7d,14d,30d,200d,1y', $app_config['marketcap_cache_time']);
	   
$data = json_decode($jsondata, true);

   if ( is_array($data) || is_object($data) ) {
  		
  	 	foreach ($data as $key => $value) {
     	  	
        	if ( $data[$key]['symbol'] != '' ) {
        	$result[strtolower($data[$key]['symbol'])] = $data[$key];
     	  	}
    
  	  	}
  	  
  	}
		  
		  
return $result;
  
}


//////////////////////////////////////////////////////////


function coinmarketcap_api() {
	
global $app_config, $coinmarketcap_currencies, $cap_data_force_usd, $cmc_notes;

$result = array();


	if ( trim($app_config['coinmarketcapcom_api_key']) == null ) { 
	app_logging('config_error', '"coinmarketcapcom_api_key" (free API key) is not configured in config.php', false, false, true);
	return false;
	}
	

	// Don't overwrite global
	$coinmarketcap_primary_currency = strtoupper($app_config['btc_primary_currency_pairing']);
	
		
		if ( in_array($coinmarketcap_primary_currency, $coinmarketcap_currencies) ) {
		$convert = $coinmarketcap_primary_currency;
		$cap_data_force_usd = null;
		}
		// Default to USD, if currency is not supported
		else {
		$cmc_notes = 'Coinmarketcap.com does not support '.$coinmarketcap_primary_currency.' stats,<br />showing USD stats instead.';
		$convert = 'USD';
		$cap_data_force_usd = 1;
		}
		
	
	$headers = [
  'Accepts: application/json',
  'X-CMC_PRO_API_KEY: ' . $app_config['coinmarketcapcom_api_key']
	];

	$cmc_params = array(
	  							'start' => '1',
	 							'limit' => $app_config['marketcap_ranks_max'],
	  							'convert' => $convert
								);

	$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
		
	$qs = http_build_query($cmc_params); // query string encode the parameters
	
	$request = "{$url}?{$qs}"; // create the request URL

	$jsondata = @api_data('url', $request, $app_config['api_timeout'], null, null, null, $headers);
	
	$data = json_decode($jsondata, true);
        
   $data = $data['data'];
        
	

    if ( is_array($data) || is_object($data) ) {
  		
  	   	foreach ($data as $key => $value) {
     	  	
        		if ( $data[$key]['symbol'] != '' ) {
        		$result[strtolower($data[$key]['symbol'])] = $data[$key];
     	  		}
    	 
  	   	}
     
    return $result;
 	 }

		  
}


//////////////////////////////////////////////////////////


?>