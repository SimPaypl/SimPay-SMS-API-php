<?php

    require_once '../src/SimPay.class.php';
    
function pre($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

    define('API_KEY', '11111111');
    define('API_SECRET', '22222222223333333333111111111100');

try {
    $api = new SimPay(API_KEY, API_SECRET);
    $api->getStatus(array(
        'service_id'    => '21',        // identyfikator usl�ugi premium sms
        'number'        => '7355',      // numer na ktory wyslano sms
        'code'          => '35FDEA',    // kod wprowadzony przez klienta
    ));

    if ($api->check()) {
        echo 'Wprowadzono poprawny kod';
    } elseif ($api->error()) {
           // Widok tylko w wersji deweloperskiej - do wylaczenia po zakonczeniu testow
           echo 'Wystapil blad:<br/>';
           pre($api->showError());
    } else {
        print_r($api->showStatus());
    }
} catch (Exception $e) {
    echo 'Error: ' .$e->getMessage();
}
