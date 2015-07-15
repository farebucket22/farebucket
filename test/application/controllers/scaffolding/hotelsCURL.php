<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HotelsCURL extends CI_Controller {

    public function makeRequest($url, $header, $requestData){
    	$curlObj = curl_init($url);

        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $requestData);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
        
        return curl_exec($curlObj);
        curl_close($curlObj);
    }
}