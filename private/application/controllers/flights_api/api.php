<?php
@session_start();

/**
*
* Flights Central API
*
**/

require_once (APPPATH . 'controllers/scaffolding/flightsAPIAuth.php');
require_once (APPPATH . 'controllers/scaffolding/flightsSOAP.php');
require_once (APPPATH . 'controllers/scaffolding/flightsSearchRequest.php');
require_once (APPPATH . 'controllers/scaffolding/flightsAPISearchResponseHandler.php');

class api extends MY_Controller
{

/**
*
* FLIGHTS ONEWAY CONTROLLER
*
**/

	function oneway_search(){
        /**
        * data from Search form
        */
        $data = $this->input->post(null, true);

        /**
        * Object initialisation
        */
        $flightsAPIAuthObj = new FlightsAPIAuth;
        $flightsSearchRequestObj = new FlightsSearchRequest;
        $flightsSOAPObj = new FlightsSOAP;
        $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;

        /**
        * Set Request Authentication Data array
        * UserName
        * Password
        */
        $flightsAPIAuthObj->setUserId("redytrip");
        $flightsAPIAuthObj->setPassword("redytrip@12");
        $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

        /**
        * Set Request Params array
        */
        $flightsSearchRequestObj->setOrigin($data['data']['from']);
        $flightsSearchRequestObj->setDestination($data['data']['to']);
        $flightsSearchRequestObj->setDepartureDate(date("Y-m-d", strtotime($data['data']['oneway_date'])));
        $flightsSearchRequestObj->setReturnDate("2014-01-01");//default value for oneway search
        $flightsSearchRequestObj->setType("OneWay");
        if (isset($data['data']['travel_class']) && $data['data']['travel_class'] != "" ){
            $flightsSearchRequestObj->setCabinClass($data['data']['travel_class']);
        } else{
            $flightsSearchRequestObj->setCabinClass("All");
        }
        if ($data['data']['airline_preference'] != "" || $data['data']['airline_preference'] != null ){
            $trunc_airline_code = explode("-", $data['data']['airline_preference']);
            $flightsSearchRequestObj->setPreferredCarrier($trunc_airline_code[1]);
        } else{
            $flightsSearchRequestObj->setPreferredCarrier("");
        }
        $flightsSearchRequestObj->setAdultCount($data['data']['adult_count']);
        $flightsSearchRequestObj->setChildCount($data['data']['youth_count']);
        $flightsSearchRequestObj->setInfantCount($data['data']['kids_count']);
        $flightsSearchRequestObj->setSeniorCount("0");
        $flightsSearchRequestObj->setPromotionalPlanType("Normal");
        $flightsSearchRequestObj->setIsDirectFlight("");
        $requestArrKeys = array("Origin", "Destination", "DepartureDate", "ReturnDate", "Type", "CabinClass", "PreferredCarrier", "AdultCount", "ChildCount", "InfantCount", "SeniorCount", "PromotionalPlanType", "IsDirectFlight");
        $temp = array_combine($requestArrKeys, array_values((array)$flightsSearchRequestObj));
        $requestArr['Search']['request'] = $temp;

        /**
        * Set SOAP Request params and make SOAP Request
        * Url
        * Header
        * Client
        */
        $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
        $flightsSOAPObj->setSOAPClient();
        $flightsSOAPObj->setSOAPHeader($authDataArr);

        $result = $flightsSOAPObj->makeSOAPCall("Search", $requestArr);

        if( $result->SearchResult->Status->Description == "Fail" || empty($result->SearchResult->Result) ){
            echo json_encode(false);
            die;
        }

        /**
        * Parse Response
        */
        $isDomestic = $result->SearchResult->IsDomestic;
        $sessionId = $result->SearchResult->SessionId;
        $isOneWay = $data['data']['flight_type'];
        $flightsAPISearchResponseHandlerObj->checkIsDomestic($isDomestic);//check if flight search is domestic
        $flightsAPISearchResponseHandlerObj->checkIsOneWay($isOneWay, $sessionId);//check if flight search is one way, to handle multiway search as well

        $wsResult = (array)$result->SearchResult->Result->WSResult;
        $indResults = $flightsAPISearchResponseHandlerObj->getIndividualFlightResults($wsResult);
        echo json_encode($indResults);//Send response back to AJAX caller.
    }

    /**
*
* FLIGHTS RETURN CONTROLLER
*
**/

    function return_search(){
        /**
        * data from Search form
        */
        $data = $this->input->post(null, true);

        /**
        * Object initialisation
        */
        $flightsAPIAuthObj = new FlightsAPIAuth;
        $flightsSearchRequestObj = new FlightsSearchRequest;
        $flightsSOAPObj = new FlightsSOAP;
        $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;

        /**
        * Set Request Authentication Data array
        * UserName
        * Password
        */
        $flightsAPIAuthObj->setUserId("redytrip");
        $flightsAPIAuthObj->setPassword("redytrip@12");
        $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

        /**
        * Set Request Params array
        */
        $flightsSearchRequestObj->setOrigin($data['data']['from']);
        $flightsSearchRequestObj->setDestination($data['data']['to']);
        $flightsSearchRequestObj->setDepartureDate(date("Y-m-d", strtotime($data['data']['to_date'])));
        $flightsSearchRequestObj->setReturnDate(date("Y-m-d", strtotime($data['data']['from_date'])));//default value for oneway search
        $flightsSearchRequestObj->setType("Return");
        if (isset($data['data']['travel_class']) && $data['data']['travel_class'] != "" ){
            $flightsSearchRequestObj->setCabinClass($data['data']['travel_class']);
        } else{
            $flightsSearchRequestObj->setCabinClass("All");
        }
        if ($data['data']['airline_preference'] != "" || $data['data']['airline_preference'] != null ){
            $trunc_airline_code = explode("-", $data['data']['airline_preference']);
            $flightsSearchRequestObj->setPreferredCarrier($trunc_airline_code[1]);
        } else{
            $flightsSearchRequestObj->setPreferredCarrier("");
        }
        $flightsSearchRequestObj->setAdultCount($data['data']['adult_count']);
        $flightsSearchRequestObj->setChildCount($data['data']['youth_count']);
        $flightsSearchRequestObj->setInfantCount($data['data']['kids_count']);
        $flightsSearchRequestObj->setSeniorCount("0");
        $flightsSearchRequestObj->setPromotionalPlanType("Normal");
        $flightsSearchRequestObj->setIsDirectFlight("");
        $requestArrKeys = array("Origin", "Destination", "DepartureDate", "ReturnDate", "Type", "CabinClass", "PreferredCarrier", "AdultCount", "ChildCount", "InfantCount", "SeniorCount", "PromotionalPlanType", "IsDirectFlight");
        $temp = array_combine($requestArrKeys, array_values((array)$flightsSearchRequestObj));
        $requestArr['Search']['request'] = $temp;

        /**
        * Set SOAP Request params and make SOAP Request
        * Url
        * Header
        * Client
        */
        $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
        $flightsSOAPObj->setSOAPClient();
        $flightsSOAPObj->setSOAPHeader($authDataArr);

        $result = $flightsSOAPObj->makeSOAPCall("Search", $requestArr);

        // echo "<pre>";
        // print_r($result);die;

        if( $result->SearchResult->Status->Description == "Fail" || empty($result->SearchResult->Result) ){
            echo json_encode(false);
            die;
        }

        /**
        * Parse Response
        */
        $isDomestic = $result->SearchResult->IsDomestic;
        $sessionId = $result->SearchResult->SessionId;
        
        $isReturn = $data['data']['flight_type'];
        $flightsAPISearchResponseHandlerObj->checkIsDomestic($isDomestic);//check if flight search is domestic
        $flightsAPISearchResponseHandlerObj->checkIsReturn($isOneWay, $sessionId);//check if flight search is one way, to handle multiway search as well

        $wsResult = (array)$result->SearchResult->Result->WSResult;
        $indResults = $flightsAPISearchResponseHandlerObj->getIndividualFlightResults($wsResult);
        echo json_encode($indResults);//Send response back to AJAX caller.
    }
}
?>