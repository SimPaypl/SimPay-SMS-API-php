<?php

class SimPay
{
    protected $auth = array();
    
    protected $response = array();
    protected $call = array();

    protected $smsValue = Array(
        '7055' => '0.50',
        '7136' => '1',
        '7255' => '2',
        '7355' => '3',
        '7436' => '4',
        '7536' => '5',
        '7636' => '6',
        '7736' => '7',
        '7836' => '8',
        '7936' => '9',
        '91055' => '10',
        '91155' => '11',
        '91455' => '14',
        '91664' => '16',
        '91955' => '19',
        '92055' => '20',
        '92555' => '25'
    );
    
    public function __construct($key = '', $secret = '' ){
        
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
        $this->call = $this->request($data, "https://simpay.pl/api/".$value);
        return $this->call;
    }
    
    public function getStatus($params) 
    {
        $this->response = $this->url('status', $params);
        return $this->response;
    }

    public function getServices() 
    {
        $this->response = $this->url( 'get_services' );

        return $this->response;
    }

    public function getServicesDB() 
    {
        $this->response = $this->url( 'get_services_db' );
        
        return $this->response;
    }

    public function getTransactionsSMS() 
    {
        $this->response = $this->url( 'transactions_sms' );
        
        return $this->response;
    }

    public function getTransactionsDB() 
    {
        $this->response = $this->url( 'transactions_db' );
        
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

    public function getSMSNumberFrom(){
        if(isset($this->response) and is_array($this->response)) {
            if(isset($this->response['respond']['from']) ) {
                return $this->response['respond']['from'];                
            } else if(isset($this->response['error']) and is_array($this->response['error'])) {
                return '';
            } 
        } else {
            throw new Exception('Brak informacji na temat ostatniego zapytania');
        }
    }

    public function getSMSNumberValue( $number ){
        if( !isset( $this -> smsValue[ $number ] ) ){
            return 0;
        }

        return $this -> smsValue[ $number ];
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
    
    private function request($data, $url){       
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

    public function getResponse(){
        return $this->response;
    }
}
