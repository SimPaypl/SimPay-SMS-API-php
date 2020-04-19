## :warning: Zalecamy używanie [nowej biblioteki](https://github.com/SimPaypl/SimPay-API-php)

## SimPay_SMS_API_Client
Official SimPay.pl PHP API Client 

## Requirements
* PHP 5.3+

## Installation

The SimPay_PHP_SMS_Client can be installed using [Composer](https://packagist.org/packages/simpaypl/sms_client).

### Composer

#### Automatic install
```composer require simpaypl/sms_client```

#### Manual install
Inside of `composer.json` specify the following:

``` json
{
  "require": {
    "simpaypl/sms_client": "dev-master"
  }
}
```

``` php
<?php
// load Composer
require 'vendor/autoload.php';

define('API_KEY', 	'XXXXXXXX');
define('API_SECRET', 	'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');

try {
	
	$api = new Simpay(API_KEY, API_SECRET );

    	$api->getStatus(array(
			'service_id' 	=>	'2',							
			'number'		=>	'7355',
			'code'			=>	'5DB554',						
	));

	if( $api -> check() ){
		echo 'Kod poprawny';

		echo 'Numer telefonu z ktorego sms zostal wyslany ' . $api -> getSMSNumberFrom();

		echo 'Wartosc SMSa ' . $api -> getSMSValue( '7355' );
	}
	else{
		echo 'Kod nie poprawny';
	}


	
} catch(Exception $e) {
	echo	'Error:	'	.$e->getMessage();
}
```
	
# Kontakt
W razie jakicholwiek pytań w implementacji , problemów, próśb o dodanie funkcjonalności zachęcamy do kontaktu poprzez:

<kontakt@simpay.pl>
