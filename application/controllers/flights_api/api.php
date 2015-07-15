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
        $utf_from = $data['data']['utf_from'];
        $utf_to = $data['data']['utf_to'];
		
		if((isset($_SESSION['return_utf_from'])) && $data['data']['utf_from'] == 0 ) {
            $data['data']['utf_from'] = $_SESSION['return_utf_from'];
            $utf_from = $data['data']['utf_from'];
        }
        if((isset($_SESSION['return_utf_to'])) && $data['data']['utf_to'] == 0 ) {
            $data['data']['utf_to'] = $_SESSION['return_utf_to'];
            $utf_to = $data['data']['utf_to'];
        }

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
        $flightsAPIAuthObj->setUserId("PNYR196");
        $flightsAPIAuthObj->setPassword("travel/090");
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
        $flightsSOAPObj->setSOAPUrl("http://airapi.travelboutiqueonline.com/tboapi_v7/service.asmx?wsdl");
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
        $_SESSION['search_sessionid'] = $sessionId;
        $isOneWay = $data['data']['flight_type'];
        $isDomestic = $flightsAPISearchResponseHandlerObj->checkIsDomestic($isDomestic);//check if flight search is domestic
        $flightsAPISearchResponseHandlerObj->checkIsOneWay($isOneWay, $sessionId);//check if flight search is one way, to handle multiway search as well

        $wsResult = (array)$result->SearchResult->Result->WSResult;
        $indResults = $flightsAPISearchResponseHandlerObj->getIndividualFlightResults($wsResult, $utf_from, $utf_to);
        $ResultArrays['wsResult'] = $wsResult;
        $ResultArrays['indResults'] = $indResults;
		$ResultArrays['isDomestic'] = $isDomestic;
        echo json_encode($ResultArrays);//Send response back to AJAX caller.
    }

    function getFareRule(){

        $data = $this->input->post(null,true);
        $res = $data['wsres'];

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
        $flightsAPIAuthObj->setUserId("PNYR196");
        $flightsAPIAuthObj->setPassword("travel/090");
        $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

        $fareRule["GetFareRule"]["fareRuleRequest"]["Result"] = $res;
        $fareRule["GetFareRule"]["fareRuleRequest"]["SessionId"] = $_SESSION['search_sessionid'];
        /**
        * Set SOAP Request params and make SOAP Request
        * Url
        * Header
        * Client
        */
        $flightsSOAPObj->setSOAPUrl("http://airapi.travelboutiqueonline.com/tboapi_v7/service.asmx?wsdl");
        $flightsSOAPObj->setSOAPClient();
        $flightsSOAPObj->setSOAPHeader($authDataArr);


        $rule = $flightsSOAPObj->makeSOAPCall("GetFareRule",$fareRule);
        if((is_array($rule->GetFareRuleResult->FareRules->WSFareRule))){
            $fareDetails = $rule->GetFareRuleResult->FareRules->WSFareRule[0];
            $fareDetails = $fareDetails->FareRuleDetail;
        }
        else{
            $fareDetails = $rule->GetFareRuleResult->FareRules->WSFareRule;
            $fareDetails = $fareDetails->FareRuleDetail;
        }

        echo json_encode($fareDetails);

    }
    function getFareRuleReturn(){

        $data = $this->input->post(null,true);
        $res = $data['wsres'];

        $session_outbound = $_SESSION['outbound_id'];
        $session_inbound = $_SESSION['inbound_id'];

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
        $flightsAPIAuthObj->setUserId("PNYR196");
        $flightsAPIAuthObj->setPassword("travel/090");
        $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

        $fareRule["GetFareRule"]["fareRuleRequest"]["Result"] = $res;
        $fareRule["GetFareRule"]["fareRuleRequest"]["SessionId"] = $session_outbound;
        /**
        * Set SOAP Request params and make SOAP Request
        * Url
        * Header
        * Client
        */
        $flightsSOAPObj->setSOAPUrl("http://airapi.travelboutiqueonline.com/tboapi_v7/service.asmx?wsdl");
        $flightsSOAPObj->setSOAPClient();
        $flightsSOAPObj->setSOAPHeader($authDataArr);

        $rule = $flightsSOAPObj->makeSOAPCall("GetFareRule",$fareRule);

        if(empty($rule->GetFareRuleResult->FareRules->WSFareRule)){
            $fareRule["GetFareRule"]["fareRuleRequest"]["Result"] = $res;
            $fareRule["GetFareRule"]["fareRuleRequest"]["SessionId"] = $session_inbound;

            $rule = $flightsSOAPObj->makeSOAPCall("GetFareRule",$fareRule);
        }
        
        if((is_array($rule->GetFareRuleResult->FareRules->WSFareRule))){
            $fareDetails = $rule->GetFareRuleResult->FareRules->WSFareRule[0];
			if(isset($fareDetails->FareRuleDetail)){
				$fareDetails = $fareDetails->FareRuleDetail;
			}
			else{
				$fareDetails = "No flight fare rules available.";
			}
        }
        else{
            $fareDetails = $rule->GetFareRuleResult->FareRules->WSFareRule;
            if(isset($fareDetails->FareRuleDetail)){
				$fareDetails = $fareDetails->FareRuleDetail;
			}
			else{
				$fareDetails = "No flight fare rules available.";
			}
        }          
        echo json_encode($fareDetails);
    }
}
?>