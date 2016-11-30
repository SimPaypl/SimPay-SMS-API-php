# API_PHP
Official SimPay.pl PHP API Client 

Sample code


	<?php
	define('API_KEY', 'XXXXXXXX');
	define('API_SECRET', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
	define(‘API_VERSION’, 1);

	require_once(‘SimPay.class.php’);

	try {
	
	$api = new SimPay(API_KEY, API_SECRET, API_VERSION);
        $api->getStatus(array(
				'service_id' 	=>	'2',							
				'number'	=>	'7355',
				'code'		=>	'5DB554',						
	));
	
	} catch(Exception $e) {
		echo	'Error:	'	.$e->getMessage();
	}
	
