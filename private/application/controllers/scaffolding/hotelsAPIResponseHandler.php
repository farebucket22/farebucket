<?php

class HotelsAPIResponseHandler{

    public function citySearchResponse($result, $cityName){
    	$response = array("Error"=>0, "ErrorMsg"=>"", "CityId"=>0);
    	$cityId = 0;
        if ($result['DestinationCityList'] != null) {
            $xml = new SimpleXMLElement($result['DestinationCityList']);
            for ($i = 0; $i < count($xml->City); $i++) {
                if ((string)$xml->City[$i]->CityName == $cityName) {
                    $cityId = (string)$xml->City[$i]->CityId;
                }
            }
        } else {
            $response["Error"] = 1;
            $response["ErrorMsg"] = 'Sorry, An error occoured. Please try again.';
        }

        if ($cityId == 0) {
            $response["Error"] = 2;
            $response["ErrorMsg"] = 'Sorry, there are no hotels available for this destination.';
        } else {
            $response["CityId"] = $cityId;
        }

        return $response;
	}

	public function hotelSearchResponse($result){
		
	}
}

?>