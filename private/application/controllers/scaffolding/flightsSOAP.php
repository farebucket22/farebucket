<?php

class FlightsSOAP{
	private $header;
	private $url;
	private $client;

	public function setSOAPUrl($url){
		$this->url = $url;
	}

	public function getSOAPUrl(){
		return $this->url;
	}

	public function setSOAPClient(){
		$this->client = new SoapClient($this->url);
	}

	public function getSOAPClient(){
		return $this->client;
	}

	public function setSOAPHeader($authData){
		$this->header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $authData, false);
		$this->client->__setSoapHeaders(array($this->header));

	}

	public function makeSOAPCall($apiMethod, $request){
		try{
			return $this->client->__soapCall($apiMethod, $request);
		} catch(SoapFault $fault){
			// echo "<pre>";
			// print_r($fault->getMessage());die;
			return json_encode(false);
		}
	}

	public function getLastRequest(){
		return $this->client->__getLastRequestHeaders();
	}

}
?>