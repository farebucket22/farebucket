<?php

class HotelsAPIResponseParser{

    public function getCityId($result, $cityName){
        
        $response = array("Error"=>0, "ErrorMsg"=>"", "CityId"=>0);
        $cityId = 0;
        if ($result['DestinationCityList'] != null) {
            $xml = new SimpleXMLElement($result['DestinationCityList']);
            for ($i = 0; $i < count($xml->City); $i++) {
                $apiStr = strtolower((string)$xml->City[$i]->CityName);
                $searchStr = strtolower($cityName);
                
                if ( is_numeric(strrpos($apiStr, $searchStr)) ) {
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
}