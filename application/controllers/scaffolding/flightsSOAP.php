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
		$this->client = new SoapClient($this->url, array('trace' => 1, "cache_wsdl" => WSDL_CACHE_DISK));
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
			return json_encode($fault);
		}
	}

	public function getLastRequest(){
		return $this->client->__getLastRequest();
	}

	public function getLastResponse(){
		return $this->client->__getLastResponse();
	}

}
?>