<?php

class SimPay
{
    protected $auth = array();
    protected $api = '1';
    
    protected $response = array();
    protected $call = array();
    
    public function __construct($key = '', $secret = '', $api = false)
    {
        if(isset($api) and !empty($api)) {
            $this->api = $api;
        } 
        
        $this->auth = array(
            "auth" => array(
                "key" => $key,
                "secret" => $secret, 
            )
        );
    }
    
    public function url($value, $params = array())
    {
        $data = json_encode(array('params'=>array_merge($this->auth, $params)));
        $this->call = $this->request($data, "https://simpay.pl/api/".$this->api."/".$value);
        return $this->call;
    }
    
    public function getStatus($params) 
    {
        $this->response = $this->url('status', $params);
        return $this->response;
    }
    
    public function check() 
    {
        if(isset($this->response) and is_array($this->response)) {
            if(isset($this->response['respond']['status']) and $this->response['respond']['status']=='OK') {
                return true;                
            } else if(isset($this->response['error']) and is_array($this->response['error'])) {
                return false;
            } 
        } else {
            throw new Exception('Brak informacji na temat ostatniego zapytania');
        }
    }
    
    public function error() 
    {
        if(isset($this->response['error']) and is_array($this->response['error'])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function showError() 
    {
        if(isset($this->response['error']) and is_array($this->response['error'])) {
            return $this->response['error'];
        } else {
            throw new Exception('Brak bledu do pokazania');
        }        
    }
    
    
    // v2 //
    // status ostatnich sms
    
    // info o sms wyslanych przez uzytkownika
    
    private function request($data, $url)
    {       
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // developer only
        $call = curl_exec($curl);
        $response = json_decode($call, true);
        $error = curl_errno($curl);
        curl_close($curl);
        
        if ($error > 0) {
            throw new RuntimeException('CURL ERROR Code:'.$error);
        }
        return $response;
    }
    
    public function response() {
        return $this->response;
    }
}

function pre($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

define('API_KEY',       '11111111');
define('API_SECRET',    '22222222223333333333111111111100');

try {

    $api = new SimPay(API_KEY, API_SECRET, 3);
    $api->getStatus(array(
        'service_id'    => '21',       // identyfikator usługi premium sms
        'number'        => '7355',     // numer na który wysłano sms
        'code'          => '35FDEA',  // kod wprowadzony przez klienta
    //  'test'          => '1',         // sprawia ze sprawdzane są poprzez api sms wygenerowane testowo
    //  'show_used'     => '1'          // sprawia ze 1 kod mozna wykorzystac bezterminowo: 
                                        // pamiętaj! wazność kodów w bazie SimPay wynosi 12 mc, po tym terminie kod zostaje deaktywowany automatycznie   
    ));
  
   if($api->check()) {
       echo 'Pozytywka';
   } else if($api->error()) {
       // Widok tylko w wersji deweloperskiej - do wyłączenia po zakończeniu testów
       echo 'Wystapil blad:<br/>';
       pre($api->showError());
   } else {
       print_r($api->showStatus());
   }
    
} 

catch(Exception $e) {
    echo 'Error: ' .$e->getMessage();
}