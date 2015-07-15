<?php
require_once (APPPATH . 'controllers/scaffolding/getPassengerData.php');
require_once (APPPATH . 'controllers/scaffolding/getBookRequest.php');
require_once (APPPATH . 'controllers/scaffolding/flightsAPIAuth.php');
require_once (APPPATH . 'controllers/scaffolding/flightsSOAP.php');
require_once (APPPATH . 'controllers/scaffolding/flightsSearchRequest.php');
require_once (APPPATH . 'controllers/scaffolding/flightsAPISearchResponseHandler.php');
require_once (APPPATH . 'controllers/scaffolding/getPassengerDetails.php');
require_once (APPPATH . 'controllers/scaffolding/getTicketDetails.php');
require_once (APPPATH . 'controllers/scaffolding/getTicketBookingDetails.php');
require_once (APPPATH . 'controllers/scaffolding/getAddBookingDetails.php');
require_once (APPPATH . 'controllers/scaffolding/getTicketInformation.php');
require_once (APPPATH . 'controllers/scaffolding/getTravellerData.php');
require_once (APPPATH . 'controllers/scaffolding/getReturnBookRequest.php');
require_once (APPPATH . 'controllers/scaffolding/getInboundBookRequest.php');
require_once (APPPATH . 'controllers/scaffolding/getReturnTicketDetails.php');
require_once (APPPATH . 'controllers/scaffolding/getReturnTicketBookingDetails.php');
require_once (APPPATH . 'controllers/scaffolding/getReturnTicketInfo.php');


@session_start();
// flights controller
class Flights extends MY_Controller
{
    function search_flights_return()
    {
        $data = $this->input->post(null, true);
        require_once (APPPATH . 'lib/nusoap.php');

        $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
        $headerpara = array();
        $headerpara["UserName"] = 'redytrip';
        $headerpara["Password"] = 'redytrip@12';
        $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array(
            $client_header
        ));
        $flights_search = array();
        $flights_search["Search"]["request"]["Origin"] = $data['data']['from'];
        $flights_search["Search"]["request"]["Destination"] = $data['data']['to'];
        $flights_search["Search"]["request"]["DepartureDate"] = date("Y-m-d", strtotime($data['data']['to_date']));;
        $flights_search["Search"]["request"]["ReturnDate"] = date("Y-m-d", strtotime($data['data']['from_date']));;
        $flights_search["Search"]["request"]["Type"] = "Return";
        if ( isset($data['data']['travel_class']) && !empty($data['data']['travel_class']) ) $flights_search["Search"]["request"]["CabinClass"] = "" . $data['data']['travel_class']; /*default*/
        else $flights_search["Search"]["request"]["CabinClass"] = "All";
        if ($data['data']['airline_preference'] != "") {
            $trunc_airline_code = explode("-", $data['data']['airline_preference']);
            $flights_search["Search"]["request"]["PreferredCarrier"] = "" . $trunc_airline_code[1];
        }
        else $flights_search["Search"]["request"]["PreferredCarrier"] = "";
        $flights_search["Search"]["request"]["AdultCount"] = $data['data']['adult_count'];
        $flights_search["Search"]["request"]["ChildCount"] = $data['data']['youth_count'];
        $flights_search["Search"]["request"]["InfantCount"] = $data['data']['kids_count'];
        $flights_search["Search"]["request"]["SeniorCount"] = "0";
        $flights_search["Search"]["request"]["PromotionalPlanType"] = "Normal";
        $flights_search["Search"]["request"]["IsDirectFlight"] = "";
        $header = array();

        try{
            $header['se'] = (array)$client->__call('Search', $flights_search);
        }catch(SoapFault $fault){
            echo json_encode('false');
            die;
        }

        if( $header['se']['SearchResult']->Status->Description == "Fail" || empty($header['se']['SearchResult']->Result) ){
            $message="false";
            echo json_encode($message);
            die;
        }

        if (!empty($header['se']['SearchResult']->IsDomestic)) {
            $_SESSION['IsDomestic'] = $header['se']['SearchResult']->IsDomestic;
        }
        else {
            $_SESSION['IsDomestic'] = 0;
        }

        // print_r($header['se']);die;

        $sess_id = explode(",", $header['se']['SearchResult']->SessionId);
        $_SESSION['outbound_id'] = $sess_id[0];
        $_SESSION['inbound_id'] = $sess_id[1];

        // $this->session->set_userdata(array(
        //   'outbound_id' => $sess_id[0],
        //   'inbound_id' => $sess_id[1]
        // ));

        $i = 0;
        $airline_list = array();
        $highest = 0;
        $lowest = 10000000;
        $max_stops = 0;
        $max_duration = 0;
        $min_duration = 9999;
        $details = array();
        $flight = array();
        $temp = array();
        $tempor1 = array();
        $travel = array();
        $fare_breakdown = array();
        $flight_info = array();
        $travel_connecting = array();
        $multi_fare_breakdown = array();
        $connect = array();
        $destination = new StdClass;
        $source = new StdClass;
        foreach($header['se']['SearchResult']->Result->WSResult as $res) {
            $details[$i] = new StdClass;
            $flight[$i] = new StdClass;
            $temp[$i] = new StdClass;
            $travel[$i] = new StdClass;
            $fare_breakdown[$i] = new StdClass;
            $flight_info[$i] = new StdClass;
            $travel_connecting[$i] = new StdClass;
            $multi_fare_breakdown[$i] = new StdClass;
            if (!is_array($res->Segment->WSSegment)) {
                $atime = explode("T", $res->Segment->WSSegment->ArrTime);
                $dtime = explode("T", $res->Segment->WSSegment->DepTIme);
                $date_d = new DateTime($dtime[0] . '' . $dtime[1]);
                $date_a = new DateTime($atime[0] . '' . $atime[1]);
                $diff = $date_d->diff($date_a);
                $d = $diff->d . 'd';
                $hr = $diff->h . 'h';
                $min = $diff->i . 'm';
                $depature = explode(":", $dtime[1]);
                $arrival = explode(":", $atime[1]);
                $details[$i]->rest = $res;
                array_push($airline_list, $res->Segment->WSSegment->Airline->AirlineName);
                $details[$i]->rest = $res;
                $flight[$i]->type = ($res->IsLcc) ? 1 : 0;
                $temp[$i]->airline = $res->Segment->WSSegment->Airline->AirlineName;
                $temp[$i]->airline_code = $res->Segment->WSSegment->Airline->AirlineCode;

                // the <space> between time and origin object is mission critical.

                $temp[$i]->travel = $res->Origin . '-' . $res->Destination;
                $travel[$i]->origin = $res->Segment->WSSegment->Origin->CityName;
                $travel[$i]->destination = $res->Segment->WSSegment->Destination->CityName;
                $hrs = intval($depature[0]);
                $mins = intval($depature[1]);
                $temp[$i]->from_hidden = ($hrs * 60) + $mins;
                $temp[$i]->from = $depature[0] . ':' . $depature[1];
                $depFilter = ($hrs * 60) + $mins;
                if ($depFilter > $max_duration) {
                    $max_duration = $depFilter;
                }

                if ($depFilter < $min_duration) {
                    $min_duration = $depFilter;
                }

                $temp[$i]->to = $arrival[0] . ':' . $arrival[1];
                if ($diff->d == 0) $temp[$i]->duration = $hr . ' ' . $min;
                else $temp[$i]->duration = $d . ' ' . $hr . ' ' . $min;
                $fare_obj = $res->Fare;
                $total_fare = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $temp[$i]->fare = $total_fare;
                if ($temp[$i]->fare > $highest) {
                    $highest = $temp[$i]->fare;
                }

                if ($temp[$i]->fare < $lowest) {
                    $lowest = $temp[$i]->fare;
                }

                $temp[$i]->stops = 0;
                $fare_breakdown[$i]->taxes = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $fare_breakdown[$i]->base_fare = $fare_obj->BaseFare;
                $fare_breakdown[$i]->tot_fare = $fare_breakdown[$i]->taxes + $fare_breakdown[$i]->base_fare;
                if ($res->NonRefundable == 1) $fare_breakdown[$i]->ticket_type = "NonRefundable";
                else $fare_breakdown[$i]->ticket_type = "Refundable";
                $temp[$i]->ticket_type = $res->NonRefundable;
                $flight_info[$i]->dep_date = date("D, jS M Y", strtotime($dtime[0]));
                $flight_info[$i]->arr_date = date("D, jS M Y", strtotime($atime[0]));
                $source = $res->Segment->WSSegment->Origin;
                if (isset($source->Terminal)) {
                    $flight_info[$i]->source_details = $source->AirportName . ', ' . $source->CityName;
                }
                else {
                    $flight_info[$i]->source_details = $source->AirportName . ', ' . $source->CityName;
                }

                $destination = $res->Segment->WSSegment->Destination;
                if (isset($destination->Terminal)) $flight_info[$i]->destination_details = $destination->AirportName . ', ' . $destination->CityName;
                else $flight_info[$i]->destination_details = $destination->AirportName . ', ' . $destination->CityName;
                $flight_info[$i]->name_of_airline = $res->Segment->WSSegment->Airline->AirlineName;
            }
            else {
                $details[$i]->rest = $res;
                $flight[$i]->type = ($res->IsLcc) ? 1 : 0;
                array_push($airline_list, $res->Segment->WSSegment[0]->Airline->AirlineName);
                $airline_name = $res->Segment->WSSegment[0]->Airline->AirlineName;
                $depart = explode("T", $res->Segment->WSSegment[0]->DepTIme);
                $d = explode(":", $depart[1]);
                $arrive = explode("T", $res->Segment->WSSegment[count($res->Segment->WSSegment) - 1]->ArrTime);
                $a = explode(":", $arrive[1]);
                $date_d = new DateTime($depart[0] . ' ' . $depart[1]);
                $date_a = new DateTime($arrive[0] . ' ' . $arrive[1]);
                $diff = $date_d->diff($date_a);
                $day = $diff->d . 'd';
                $hr = $diff->h . 'h';
                $min = $diff->i . 'm';
                $count = count($res->Segment->WSSegment) - 1;
                $fare_obj = $res->Fare;
                $total_fare = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $temp[$i]->airline = $airline_name;
                $temp[$i]->airline_code = $res->Segment->WSSegment[0]->Airline->AirlineCode;
                $temp[$i]->travel = $res->Segment->WSSegment[0]->Origin->CityCode . '-' . $res->Segment->WSSegment[count($res->Segment->WSSegment) - 1]->Destination->CityCode;
                $travel_connecting[$i]->origin = $res->Segment->WSSegment[0]->Origin->CityName;
                $travel_connecting[$i]->destination = $res->Segment->WSSegment[count($res->Segment->WSSegment) - 1]->Destination->CityName;
                $hrsCon = intval($d[0]);
                $minsCon = intval($d[1]);
                $temp[$i]->from_hidden = ($hrsCon * 60) + $minsCon;
                $temp[$i]->from = $d[0] . ':' . $d[1];
                $depFilter = ($hrsCon * 60) + $minsCon;
                if ($depFilter > $max_duration) {
                    $max_duration = $depFilter;
                }

                if ($depFilter < $min_duration) {
                    $min_duration = $depFilter;
                }

                $temp[$i]->to = $a[0] . ':' . $a[1];
                if ($diff->d == 0) $temp[$i]->duration = $hr . ' ' . $min;
                else $temp[$i]->duration = $day . ' ' . $hr . ' ' . $min;
                $temp[$i]->fare = $total_fare;
                $temp[$i]->stops = count($res->Segment->WSSegment) - 1;
                if ($max_stops < $temp[$i]->stops) {
                    $max_stops = $temp[$i]->stops;
                }

                $j = 0;

                // $connect[$i] = new StdClass;

                $flag_con = 0;

                // $temp_layover[$i][] = new StdClass;

                $connect[$i] = array();
                $temp[$i]->multi = array();
                $travel_connecting[$i]->connecting = array();
                if ($res->NonRefundable == 1) $multi_fare_breakdown[$i]->ticket_type = "NonRefundable";
                else $multi_fare_breakdown[$i]->ticket_type = "Refundable";
                $temp[$i]->ticket_type = $res->NonRefundable;
                $multi_fare_breakdown[$i]->taxes = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $multi_fare_breakdown[$i]->base_fare = $fare_obj->BaseFare;
                $multi_fare_breakdown[$i]->tot_fare = $multi_fare_breakdown[$i]->taxes + $multi_fare_breakdown[$i]->base_fare;
                if ($temp[$i]->fare > $highest) {
                    $highest = $temp[$i]->fare;
                }

                if ($temp[$i]->fare < $lowest) {
                    $lowest = $temp[$i]->fare;
                }

                $flag = 0;
                foreach($res->Segment->WSSegment as $connectingFlight) {
                    $connect[$i][$j] = new StdClass;
                    $temp[$i]->multi[$j] = new StdClass;
                    $travel_connecting[$i]->connecting[$j] = new StdClass;
                    $atime = explode("T", $connectingFlight->ArrTime);
                    $dtime = explode("T", $connectingFlight->DepTIme);
                    $date_d = new DateTime($dtime[0] . '' . $dtime[1]);
                    $date_a = new DateTime($atime[0] . '' . $atime[1]);
                    if ($flag == 1) {
                        $lay_d = new DateTime($dtime[0] . '' . $dtime[1]);
                        $diff_lay = $lay_d->diff($lay_a);
                        $d = $diff_lay->d . 'd';
                        $hr = $diff_lay->h . 'h';
                        $min = $diff_lay->i . 'm';
                        if ($diff_lay->d == 0) $temp_layover[$i][] = $hr . ' ' . $min;
                        else $temp_layover[$i][] = $d . ' ' . $hr . ' ' . $min;
                        $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                        $flag_con++;
                    }
                    else {
                        $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                        $flag++;
                    }

                    $diff = $date_d->diff($date_a);
                    $d = $diff->d . 'd';
                    $hr = $diff->h . 'h';
                    $min = $diff->i . 'm';
                    $depature = explode(":", $dtime[1]);
                    $arrival = explode(":", $atime[1]);
                    $airlinen = $connectingFlight->Airline->AirlineName;
                    $temp[$i]->multi[$j]->airline = $connectingFlight->Airline->AirlineName;
                    $temp[$i]->multi[$j]->from = $depature[0] . ':' . $depature[1];
                    $travel_connecting[$i]->connecting[$j]->origin = $connectingFlight->Origin->CityName;
                    $travel_connecting[$i]->connecting[$j]->destination = $connectingFlight->Destination->CityName;
                    $temp[$i]->multi[$j]->to = $arrival[0] . ':' . $arrival[1];
                    if ($diff->d == 0) {
                        $temp[$i]->multi[$j]->duration = $hr . ' ' . $min;
                    }
                    else {
                        $temp[$i]->multi[$j]->duration = $d . ' ' . $hr . ' ' . $min;
                    }

                    $temp[$i]->multi[$j]->fare = $total_fare;
                    $temp[$i]->multi[$j]->stops = count($connectingFlight);
                    $source = $connectingFlight->Origin;
                    if (isset($source->Terminal)) {
                        $connect[$i][$j]->source_details = $source->AirportName . ', ' . $source->CityName;
                    }
                    else {
                        $connect[$i][$j]->source_details = $source->AirportName . ', ' . $source->CityName;
                    }

                    $destination = $connectingFlight->Destination;
                    if (isset($destination->Terminal)) $connect[$i][$j]->destination_details = $destination->AirportName . ', ' . $destination->CityName;
                    else $connect[$i][$j]->destination_details = $destination->AirportName . ', ' . $destination->CityName;
                    $connect[$i][$j]->name_of_airline = $connectingFlight->Airline->AirlineName;
                    $connect[$i][$j]->dep_date = date("D, jS M Y", strtotime($dtime[0]));
                    $connect[$i][$j]->arr_date = date("D, jS M Y", strtotime($atime[0]));
                    $j++;
                }
            }

            $i++;
        }

        $tempor = array_unique($airline_list);
        foreach($tempor as $tem) {
            if ($tem != '' || $tem != null) {
                $tempor1[] = $tem;
            }
        }
        sort($tempor1);

        if (!isset($temp_layover)) $temp_layover = null;
        if (!isset($temp)) {
            echo json_encode("false");
        }

        echo json_encode(array(
            'layover' => $temp_layover,
            'results' => $temp,
            'fare_max' => $highest,
            'fare_min' => $lowest,
            'airline_list' => $tempor1,
            'max_stops' => $max_stops,
            'details' => $details,
            'travel' => $travel,
            'fare_breakdown' => $fare_breakdown,
            'flight_info' => $flight_info,
            'flight' => $flight,
            'multi_fare_breakdown' => $multi_fare_breakdown,
            'multi_flight_info' => $connect,
            'travel_connecting' => $travel_connecting,
            'max_duration' => $max_duration,
            'min_duration' => $min_duration
        ));
    }

    function oneway_parameters()
    {   
        //$_SESSION['currentSearchUrl'] = $_SERVER['PATH_INFO'] . "?" . $_SERVER['QUERY_STRING'];
        $_SESSION['onewayGetStrings'] = $_GET;
        $_SESSION['currentUrlFlight'] = current_full_url();
        $_GET['total_count'] = $_GET['adult_count']+$_GET['youth_count']+$_GET['kids_count'];
        $this->load->view("common/header.php");
        $this->load->view('flights_view/display_flights');
        $this->load->view("common/footer.php");
    }

    function return_parameters()
    {
        //$_SESSION['currentSearchUrl'] = $_SERVER['PATH_INFO'] . "?" . $_SERVER['QUERY_STRING'];
        $_SESSION['currentUrlFlight'] = current_full_url();
        $_GET['total_count'] = $_GET['adult_count']+$_GET['youth_count']+$_GET['kids_count'];
        $this->load->view("common/header.php");
        $this->load->view('flights_view/display_return_flights');
        $this->load->view("common/footer.php");
    }

    function test_multi()
    {
        $chk_edit = $this->input->get('isedit');
        $is_mod = $this->input->get('ismod');
        if ($chk_edit != 'true') {
            if ((isset($_SESSION['cnt_val']) && $_SESSION['cnt_val'] > 1) && $is_mod != 'true') {
                $j = $_SESSION['cnt_val'] - 1;
                $total_flights = $_SESSION['details']['flights'];
                redirect('api/flights/set_multi_url?city_from=' . $_SESSION['details']['from'][$j] . '&city_to=' . $_SESSION['details']['to'][$j] . '&adult_count=' . $_SESSION['details']['adult_count'] . '&youth_count=' . $_SESSION['details']['youth_count'] . '&kids_count=' . $_SESSION['details']['kids_count'] . '&multi_date=' . $_SESSION['details']['dates'][$j] . '&flight_num=' . $_SESSION['cnt_val'] . '&airline_preference_3=' . $_SESSION['airline_preference'] . '&class_of_travel_3=' . $_SESSION['class_travel']);
            } else {
                $total_flights = $this->input->get('count_flights') + 1;
                $count_num[0] = "one";
                $count_num[1] = "two";
                $count_num[2] = "three";
                $count_num[3] = "four";
                for ($i = 0; $i < $total_flights; $i++) {
                    $from[$i] = $this->input->get('city_from_' . $count_num[$i]);
                    $to[$i] = $this->input->get('city_to_' . $count_num[$i]);
                    $dates[$i] = $this->input->get('multi_date_' . $count_num[$i]);
                }

                $adult_count = $this->input->get('adult_count');
                $kid_count = $this->input->get('kids_count');
                $youth_count = $this->input->get('youth_count');
                $total_count = $this->input->get('total_count');
                $city_count = 1;
                if( isset($_GET['class_of_travel_3']) ){
                    $_SESSION['class_travel'] = $_GET['class_of_travel_3'];
                    $_SESSION['airline_preference'] = $_GET['airline_preference_3'];
                }else{
                    $_SESSION['class_travel'] = 'All';
                    $_SESSION['airline_preference'] = '';
                }
                $_SESSION['cnt_val'] = $city_count;
                $_SESSION['details'] = array(
                    'from' => $from,
                    'to' => $to,
                    'dates' => $dates,
                    'flights' => $total_flights,
                    'adult_count' => $adult_count,
                    'kids_count' => $kid_count,
                    'youth_count' => $youth_count,
                    'total_count' => $total_count
                );

                redirect('api/flights/set_multi_url?city_from=' . $_SESSION['details']['from'][0] . '&city_to=' . $_SESSION['details']['to'][0] . '&adult_count=' . $_SESSION['details']['adult_count'] . '&youth_count=' . $_SESSION['details']['youth_count'] . '&kids_count=' . $_SESSION['details']['kids_count'] . '&multi_date=' . $_SESSION['details']['dates'][0] . '&flight_num=' . $_SESSION['cnt_val'] . '&airline_preference_3=' . $_SESSION['airline_preference'] . '&class_of_travel_3=' . $_SESSION['class_travel']);
            } 
        } else { 
            if( isset($_SESSION['currentUrlBus']) && (isset($_SESSION['ovMode'])) && ($_SESSION['ovMode']->mode == 'bus') ){
                redirect($_SESSION['currentUrlBus']);
            }
            else if( isset($_SESSION['currentUrlCabs']) && (isset($_SESSION['ovMode'])) && ($_SESSION['ovMode']->mode == 'cab') ) {
                redirect($_SESSION['currentUrlCabs']);
            }
            else{
                $i = $_SESSION['cnt_val'] - 1;
                redirect('api/flights/set_multi_url?city_from=' . $_SESSION['details']['from'][$i] . '&city_to=' . $_SESSION['details']['to'][$i] . '&adult_count=' . $_SESSION['details']['adult_count'] . '&youth_count=' . $_SESSION['details']['youth_count'] . '&kids_count=' . $_SESSION['details']['kids_count'] . '&multi_date=' . $_SESSION['details']['dates'][$i] . '&flight_num=' . $_SESSION['cnt_val'] . '&airline_preference_3=' . $_SESSION['airline_preference'] . '&class_of_travel_3=' . $_SESSION['class_travel']);
            }
        }
    }

    function set_multi_url()
    {
        $_GET['total_count'] = $_GET['adult_count']+$_GET['youth_count']+$_GET['kids_count'];
        $_SESSION['details'] = array(
            'from' => $_SESSION['details']['from'],
            'to' => $_SESSION['details']['to'],
            'dates' => $_SESSION['details']['dates'],
            'flights' => $_SESSION['details']['flights'],
            'adult_count' => $_GET['adult_count'],
            'kids_count' => $_GET['kids_count'],
            'youth_count' => $_GET['youth_count'],
            'total_count' => $_GET['total_count']
        );
        $_SESSION['calling_controller_name'] = "flights";
        $_SESSION['currentUrlFlight'] = current_full_url();
        //$_SESSION['currentSearchUrl'] = $_SERVER['PATH_INFO'] . "?" . $_SERVER['QUERY_STRING'];
        $this->load->view("common/header.php");
        $this->load->view('flights_view/display_flights_check');
        $this->load->view("common/footer.php");
    }

    function sample()
    {
        // $_SESSION['calling_controller_name'] = "flights";
        // $_SESSION['currentUrlFlight'] = current_full_url();
        $overview_data = array();
        $overview_data['data'] = new stdClass();
        $temp_layover = array();
        $data = $this->input->post(null, true);
        $temp_arr = json_decode(stripcslashes($data['booking_details']));
        if (is_array($temp_arr->rest->Segment->WSSegment)) {
            $flag = 0;
            foreach($temp_arr->rest->Segment->WSSegment as $connectingFlight) {
                $atime = explode("T", $connectingFlight->ArrTime);
                $dtime = explode("T", $connectingFlight->DepTIme);
                $date_d = new DateTime($dtime[0] . '' . $dtime[1]);
                $date_a = new DateTime($atime[0] . '' . $atime[1]);
                if ($flag == 1) {
                    $lay_d = new DateTime($dtime[0] . '' . $dtime[1]);
                    $diff_lay = $lay_d->diff($lay_a);
                    $d = $diff_lay->d . 'd';
                    $hr = $diff_lay->h . 'h';
                    $min = $diff_lay->i . 'm';
                    if ($diff_lay->d == 0) $temp_layover[] = $hr . ' ' . $min;
                    else $temp_layover[] = $d . ' ' . $hr . ' ' . $min;
                    $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                }
                else {
                    $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                    $flag++;
                }
            }
        }

        $overview_data['data']->mode = 'flight';
        $overview_data['data']->WSSegment = $temp_arr->rest->Segment->WSSegment;
        $overview_data['data']->from_field = $data['from_field'];
        $overview_data['data']->to_field = $data['to_field'];
        $overview_data['data']->org = $temp_arr->rest->Origin;
        $overview_data['data']->dest = $temp_arr->rest->Destination;
        $overview_data['data']->flight_duration_field = $data['flight_duration_field'];
        $overview_data['data']->travel_date = $data['travel_date'];
        $overview_data['data']->total_fare_field = $data['total_fare_field'];
        $overview_data['data']->layover = $temp_layover;
        $data['ov'] = $overview_data['data'];
        $cnt_val = $_SESSION['cnt_val'];
        if ($cnt_val < $_SESSION['details']['flights']) {
            $cnt_val = $cnt_val + 1;
            $_SESSION['cnt_val'] = $cnt_val;
            $_SESSION['flight_data'][$cnt_val - 2] = $data;
            redirect('api/flights/test_multi');
        }
        else {
            $_SESSION['flight_data'][$cnt_val - 1] = $data;
            redirect('api/flights/traveller_multi_details');
        }
    }

    function multi_parameters()
    {
        $_SESSION['currentUrlFlight'] = current_full_url();
        if ($this->session->userdata('cnt_val') > 1) {
            $this->load->view("common/header.php");
            $this->load->view('flights_view/display_multi_flights');
            $this->load->view("common/footer.php");
        }
        else {
            $city_count = 1;

            // $this->native_session->set('cnt_val', $city_count);

            $this->session->set_userdata(array(
                'cnt_val' => $city_count
            ));
            $this->load->view("common/header.php");
            $this->load->view('flights_view/display_multi_flights');
            $this->load->view("common/footer.php");
        }
    }

    function search_flights_oneway()
    {
        $data = $this->input->post(null, true);
        require_once (APPPATH . 'lib/nusoap.php');
        require_once (APPPATH . 'lib/rss_php.php');
        $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
        $headerpara = array();
        $headerpara["UserName"] = 'redytrip';
        $headerpara["Password"] = 'redytrip@12';
        $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array(
            $client_header
        )); /*T00:00:00*/
        $flights_search = array();
        $flights_search["Search"]["request"]["Origin"] = $data['data']['from'];
        $flights_search["Search"]["request"]["Destination"] = $data['data']['to'];
        $flights_search["Search"]["request"]["DepartureDate"] = date("Y-m-d", strtotime($data['data']['oneway_date']));
        $flights_search["Search"]["request"]["ReturnDate"] = "2014-01-01"; /*default date*/
        $flights_search["Search"]["request"]["Type"] = "OneWay";
        if (isset($data['data']['travel_class']) && $data['data']['travel_class'] != "" ) $flights_search["Search"]["request"]["CabinClass"] = "" . $data['data']['travel_class']; /*default*/
        else $flights_search["Search"]["request"]["CabinClass"] = "All";
        if ($data['data']['airline_preference'] != "" || $data['data']['airline_preference'] != null ) {
            $trunc_airline_code = explode("-", $data['data']['airline_preference']);
            $flights_search["Search"]["request"]["PreferredCarrier"] = "" . $trunc_airline_code[1];
        }
        else $flights_search["Search"]["request"]["PreferredCarrier"] = "";
        $flights_search["Search"]["request"]["AdultCount"] = $data['data']['adult_count'];
        $flights_search["Search"]["request"]["ChildCount"] = $data['data']['youth_count'];
        $flights_search["Search"]["request"]["InfantCount"] = $data['data']['kids_count'];
        $flights_search["Search"]["request"]["SeniorCount"] = "0";
        $flights_search["Search"]["request"]["PromotionalPlanType"] = "Normal";
        $flights_search["Search"]["request"]["IsDirectFlight"] = "";

        $header = array();
        // echo '<h2>Request</h2>';
        // echo '<pre>' . htmlspecialchars($client->, ENT_QUOTES) . '</pre>';die;

        try{
            $header['se'] = (array)$client->__call('Search', $flights_search);
        }catch(SoapFault $fault){
            echo json_encode('false');
            die;
        }

        if( $header['se']['SearchResult']->Status->Description == "Fail" || empty($header['se']['SearchResult']->Result) ){
            $message="false";
            echo json_encode($message);
            die;
        }

        if (!empty($header['se']['SearchResult']->IsDomestic)) {
            $_SESSION['IsDomestic'] = $header['se']['SearchResult']->IsDomestic;
        }
        else {
            $_SESSION['IsDomestic'] = 0;
        }

        if (isset($data['data']['flight_type'])) {
            if ($data['data']['flight_type'] != 'OneWay') {
                if (isset($_SESSION['cnt_val'])) $_SESSION['sess_id'][$_SESSION['cnt_val'] - 1] = $header['se']['SearchResult']->SessionId;
            }
            else {
                $_SESSION['cnt_val'] = 1;
                $_SESSION['sess_id'][0] = $header['se']['SearchResult']->SessionId;
            }
        }

        $i = 0;
        $airline_list = array();
        $highest = 0;
        $lowest = 10000000;
        $max_stops = 0;
        $max_duration = 0;
        $min_duration = 9999;
        $depFilter = 0;
        $details = array();
        $flight = array();
        $temp = array();
        $travel = array();
        $tempor1 = array();
        $fare_breakdown = array();
        $flight_info = array();
        $travel_connecting = array();
        $multi_fare_breakdown = array();
        $connect = array();
        foreach($header['se']['SearchResult']->Result->WSResult as $res) {
            $details[$i] = new StdClass;
            $flight[$i] = new StdClass;
            $temp[$i] = new StdClass;
            $travel[$i] = new StdClass;
            $fare_breakdown[$i] = new StdClass;
            $flight_info[$i] = new StdClass;
            $travel_connecting[$i] = new StdClass;
            $multi_fare_breakdown[$i] = new StdClass;
            if (!is_array($res->Segment->WSSegment)) {
                $atime = explode("T", $res->Segment->WSSegment->ArrTime);
                $dtime = explode("T", $res->Segment->WSSegment->DepTIme);
                $date_d = new DateTime($dtime[0] . '' . $dtime[1]);
                $date_a = new DateTime($atime[0] . '' . $atime[1]);
                $diff = $date_d->diff($date_a);
                $d = $diff->d . 'd';
                $hr = $diff->h . 'h';
                $min = $diff->i . 'm';
                $depature = explode(":", $dtime[1]);
                $arrival = explode(":", $atime[1]);
                array_push($airline_list, $res->Segment->WSSegment->Airline->AirlineName);
                $details[$i]->rest = $res;
                $flight[$i]->type = ($res->IsLcc) ? 1 : 0;
                $temp[$i]->airline = $res->Segment->WSSegment->Airline->AirlineName;
                $temp[$i]->airline_code = $res->Segment->WSSegment->Airline->AirlineCode;
                $hrs = intval($depature[0]);
                $mins = intval($depature[1]);
                $temp[$i]->from_hidden = ($hrs * 60) + $mins;
                $temp[$i]->from = $depature[0] . ':' . $depature[1];
                $depFilter = ($hrs * 60) + $mins;
                if ($depFilter > $max_duration) {
                    $max_duration = $depFilter;
                }

                if ($depFilter < $min_duration) {
                    $min_duration = $depFilter;
                }

                $travel[$i]->origin = $res->Segment->WSSegment->Origin->CityName;
                $travel[$i]->destination = $res->Segment->WSSegment->Destination->CityName;
                $temp[$i]->to = $arrival[0] . ':' . $arrival[1];
                if ($diff->d == 0) $temp[$i]->duration = $hr . ' ' . $min;
                else $temp[$i]->duration = $d . ' ' . $hr . ' ' . $min;
                $fare_obj = $res->Fare;
                $total_fare = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $temp[$i]->fare = $total_fare;
                if ($temp[$i]->fare > $highest) {
                    $highest = $temp[$i]->fare;
                }

                if ($temp[$i]->fare < $lowest) {
                    $lowest = $temp[$i]->fare;
                }

                $temp[$i]->stops = 0;
                $fare_breakdown[$i]->taxes = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $fare_breakdown[$i]->base_fare = $fare_obj->BaseFare;
                $fare_breakdown[$i]->tot_fare = $fare_breakdown[$i]->taxes + $fare_breakdown[$i]->base_fare;
                if ($res->NonRefundable == 1) $fare_breakdown[$i]->ticket_type = "NonRefundable";
                else $fare_breakdown[$i]->ticket_type = "Refundable";
                $temp[$i]->ticket_type = $res->NonRefundable;
                $flight_info[$i]->dep_date = date("D, jS M Y", strtotime($dtime[0]));
                $flight_info[$i]->arr_date = date("D, jS M Y", strtotime($atime[0]));
                $source = $res->Segment->WSSegment->Origin;
                if (isset($source->Terminal)) $flight_info[$i]->source_details = $source->AirportName . ', ' . $source->CityName;
                else $flight_info[$i]->source_details = $source->AirportName . ', ' . $source->CityName;
                $destination = $res->Segment->WSSegment->Destination;
                if (isset($destination->Terminal)) $flight_info[$i]->destination_details = $destination->AirportName . ', ' . $destination->CityName;
                else $flight_info[$i]->destination_details = $destination->AirportName . ', ' . $destination->CityName;
                $flight_info[$i]->name_of_airline = $res->Segment->WSSegment->Airline->AirlineName;
            }
            else {

                // array_push($airline_list, $connectingFlight->Airline->AirlineName);

                $details[$i]->rest = $res;
                $flight[$i]->type = ($res->IsLcc) ? 1 : 0;
                $airline_name = $res->Segment->WSSegment[0]->Airline->AirlineName;
                $depart = explode("T", $res->Segment->WSSegment[0]->DepTIme);
                $d = explode(":", $depart[1]);
                $arrive = explode("T", $res->Segment->WSSegment[count($res->Segment->WSSegment) - 1]->ArrTime);
                $a = explode(":", $arrive[1]);
                $journey = $res->Segment->WSSegment[0]->Origin->CityCode . '-' . $res->Segment->WSSegment[count($res->Segment->WSSegment) - 1]->Destination->CityCode;
                $date_d = new DateTime($depart[0] . ' ' . $depart[1]);
                $date_a = new DateTime($arrive[0] . ' ' . $arrive[1]);
                $diff = $date_d->diff($date_a);
                $day = $diff->d . 'd';
                $hr = $diff->h . 'h';
                $min = $diff->i . 'm';
                $count = count($res->Segment->WSSegment) - 1;
                $fare_obj = $res->Fare;
                $total_fare = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $temp[$i]->airline = $airline_name;
                $temp[$i]->airline_code = $res->Segment->WSSegment[0]->Airline->AirlineCode;
                $hrsCon = intval($d[0]);
                $minsCon = intval($d[1]);
                $temp[$i]->from_hidden = ($hrsCon * 60) + $minsCon;
                $temp[$i]->from = $d[0] . ':' . $d[1];
                $depFilter = ($hrsCon * 60) + $minsCon;
                array_push($airline_list, $res->Segment->WSSegment[0]->Airline->AirlineName);
                if ($depFilter > $max_duration) {
                    $max_duration = $depFilter;
                }

                if ($depFilter < $min_duration) {
                    $min_duration = $depFilter;
                }

                $travel_connecting[$i]->origin = $res->Segment->WSSegment[0]->Origin->CityName;
                $travel_connecting[$i]->destination = $res->Segment->WSSegment[count($res->Segment->WSSegment) - 1]->Destination->CityName;
                $temp[$i]->to = $a[0] . ':' . $a[1];
                if ($diff->d == 0) $temp[$i]->duration = $hr . ' ' . $min;
                else $temp[$i]->duration = $day . ' ' . $hr . ' ' . $min;
                $temp[$i]->fare = $total_fare;
                if ($temp[$i]->fare > $highest) {
                    $highest = $temp[$i]->fare;
                }

                if ($temp[$i]->fare < $lowest) {
                    $lowest = $temp[$i]->fare;
                }

                $temp[$i]->stops = count($res->Segment->WSSegment) - 1;
                if ($max_stops < $temp[$i]->stops) {
                    $max_stops = $temp[$i]->stops;
                }

                $j = 0;

                // $temp_layover[$i] = new StdClass;

                $connect[$i] = new StdClass;
                $connect[$i] = array();
                $temp[$i]->multi = array();
                $travel_connecting[$i]->connecting = array();
                if ($res->NonRefundable == 1) $multi_fare_breakdown[$i]->ticket_type = "NonRefundable";
                else $multi_fare_breakdown[$i]->ticket_type = "Refundable";
                $temp[$i]->ticket_type = $res->NonRefundable;
                $multi_fare_breakdown[$i]->taxes = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $multi_fare_breakdown[$i]->base_fare = $fare_obj->BaseFare;
                $multi_fare_breakdown[$i]->tot_fare = $multi_fare_breakdown[$i]->taxes + $multi_fare_breakdown[$i]->base_fare;
                $flag = 0;
                foreach($res->Segment->WSSegment as $connectingFlight) {
                    $connect[$i][$j] = new StdClass;
                    $temp[$i]->multi[$j] = new StdClass;
                    $travel_connecting[$i]->connecting[$j] = new StdClass;
                    $atime = explode("T", $connectingFlight->ArrTime);
                    $dtime = explode("T", $connectingFlight->DepTIme);
                    $date_d = new DateTime($dtime[0] . '' . $dtime[1]);
                    $date_a = new DateTime($atime[0] . '' . $atime[1]);
                    if ($flag == 1) {
                        $lay_d = new DateTime($dtime[0] . '' . $dtime[1]);
                        $diff_lay = $lay_d->diff($lay_a);
                        $d = $diff_lay->d . 'd';
                        $hr = $diff_lay->h . 'h';
                        $min = $diff_lay->i . 'm';
                        if ($diff_lay->d == 0) $temp_layover[$i][] = $hr . ' ' . $min;
                        else $temp_layover[$i][] = $d . ' ' . $hr . ' ' . $min;
                        $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                    }
                    else {
                        $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                        $flag++;
                    }

                    $diff = $date_d->diff($date_a);
                    $d = $diff->d . 'd';
                    $hr = $diff->h . 'h';
                    $min = $diff->i . 'm';
                    $depature = explode(":", $dtime[1]);
                    $arrival = explode(":", $atime[1]);

                    // $airlinen = $connectingFlight->Airline->AirlineName;

                    $temp[$i]->multi[$j]->airline = $connectingFlight->Airline->AirlineName;
                    $temp[$i]->multi[$j]->airline = $connectingFlight->Airline->AirlineCode;
                    $temp[$i]->multi[$j]->from = $depature[0] . ':' . $depature[1];
                    $travel_connecting[$i]->connecting[$j]->origin = $connectingFlight->Origin->CityCode;
                    $travel_connecting[$i]->connecting[$j]->destination = $connectingFlight->Destination->CityCode;
                    $temp[$i]->multi[$j]->to = $arrival[0] . ':' . $arrival[1];
                    if ($diff->d == 0) $temp[$i]->multi[$j]->duration = $hr . ' ' . $min;
                    else $temp[$i]->multi[$j]->duration = $d . ' ' . $hr . ' ' . $min;
                    $temp[$i]->multi[$j]->fare = $total_fare;
                    $temp[$i]->multi[$j]->stops = count($connectingFlight);
                    $source = $connectingFlight->Origin;
                    if (isset($source->Terminal)) $connect[$i][$j]->source_details = $source->AirportName . ', ' . $source->CityName;
                    else $connect[$i][$j]->source_details = $source->AirportName . ', ' . $source->CityName;
                    $destination = $connectingFlight->Destination;
                    if (isset($destination->Terminal)) $connect[$i][$j]->destination_details = $destination->AirportName . ', ' . $destination->CityName;
                    else $connect[$i][$j]->destination_details = $destination->AirportName . ', ' . $destination->CityName;
                    $connect[$i][$j]->name_of_airline = $connectingFlight->Airline->AirlineName;
                    $connect[$i][$j]->dep_date = date("D, jS M Y", strtotime($dtime[0]));
                    $connect[$i][$j]->arr_date = date("D, jS M Y", strtotime($atime[0]));
                    $j++;
                }
            }

            $i++;
        }

        $tempor = array_unique($airline_list);
        foreach($tempor as $tem) {
            if ($tem != '' || $tem != null) {
                $tempor1[] = $tem;
            }
        }
        sort($tempor1);

        if (isset($_SESSION['cnt_val'])) $_SESSION['sess_id'][$_SESSION['cnt_val'] - 1] = $header['se']['SearchResult']->SessionId;
        if (!isset($temp_layover)) $temp_layover = null;
        if (!isset($temp)) {
            echo json_encode("false");
        }

        echo json_encode(array(
            'layover' => $temp_layover,
            'results' => $temp,
            'fare_max' => $highest,
            'fare_min' => $lowest,
            'airline_list' => $tempor1,
            'max_stops' => $max_stops,
            'fare_breakdown' => $fare_breakdown,
            'flight_info' => $flight_info,
            'multi_fare_breakdown' => $multi_fare_breakdown,
            'multi_flight_info' => $connect,
            'travel_connecting' => $travel_connecting,
            'travel' => $travel,
            'details' => $details,
            'flight' => $flight,
            'max_duration' => $max_duration,
            'min_duration' => $min_duration,
            'sess_id' => $_SESSION['sess_id'][$_SESSION['cnt_val'] - 1]
        ));

        // $this->load->view('flights_view/display_flights',array('details' => $data, 'results' => $header['se']['SearchResult']));

    }

    function edit_fl()
    {
        $_SESSION['cnt_val'] = intval($this->input->get('ind'));
        for ($i = count($_SESSION['flight_data']); $i >= intval($this->input->get('ind')); $i--) {
            $_SESSION['ovMode'] = ($_SESSION['flight_data'][$i-1]['ov']);
            unset($_SESSION['flight_data'][$i - 1]);
        }
        redirect('api/flights/test_multi?isedit=true');
    }

    function traveller_details()
    {
        $_SESSION['calling_controller_name'] = "flights";
        //$_SESSION['currentTravellerUrl'] = current_full_url();
        $this->load->model('flight_model');
        if ($this->input->post(null, true)) {
            $data = $this->input->post(null, true);
            $_SESSION['tooltip_str']['from'] = $data['from_field_str'];
            $_SESSION['tooltip_str']['to'] = $data['to_field_str'];
            unset($data['from_field_str']);
            unset($data['to_field_str']);
            if( $_POST['flight_type'] == 'true' ){
                $data['flight_type'] = 1;
            }else{
                $data['flight_type'] = 0;
            }
            $_SESSION['onewayFlightTravellerData'] = $data;
            $segment = json_decode(stripcslashes($data['booking_details']));
            if (is_array($segment)) {
                $flag = 0;
                foreach($segment as $connectingFlight) {
                    $atime = explode("T", $connectingFlight->ArrTime);
                    $dtime = explode("T", $connectingFlight->DepTIme);
                    $date_d = new DateTime($dtime[0] . '' . $dtime[1]);
                    $date_a = new DateTime($atime[0] . '' . $atime[1]);
                    if ($flag == 1) {
                        $lay_d = new DateTime($dtime[0] . '' . $dtime[1]);
                        $diff_lay = $lay_d->diff($lay_a);
                        $d = $diff_lay->d . 'd';
                        $hr = $diff_lay->h . 'h';
                        $min = $diff_lay->i . 'm';
                        if ($diff_lay->d == 0) $temp_layover[] = $hr . ' ' . $min;
                        else $temp_layover[] = $d . ' ' . $hr . ' ' . $min;
                        $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                    }
                    else {
                        $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                        $flag++;
                    }
                }
                if( isset($temp_layover) ){
                    $data['layover'] = $temp_layover;
                }
            }
        } else {
            $data = $_SESSION['onewayFlightTravellerData'];
        }
        $this->load->model('admin/convenience_model');
        $data['flight_type'] = $data['flight_type'];

        if ($data['flight_type'] == 0) {
            $this->load->view("common/header.php");
            $convenience = $this->convenience_model->get_convenience_charge();
            $data['convenience_charge'] = $convenience->convenience_charge;
            $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
            $this->load->view('flights/traveller_book_page', array(
                'data' => $data
            ));
            $this->load->view("common/footer.php");
        }
        else {
            $data = $this->get_fare_quote($data);
            $convenience = $this->convenience_model->get_convenience_charge();
            $data['convenience_charge'] = $convenience->convenience_charge;
            $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
            $this->load->view("common/header.php");
            $this->load->view('flights/traveller_book_page', array(
                'data' => $data
            ));
            $this->load->view("common/footer.php");
        }
    }

    function return_traveller_details()
    {
        $_SESSION['calling_controller_name'] = "flights";
        //$_SESSION['currentUrlFlight'] = current_full_url();
        // $this->session->set_userdata($callingViewDetails);

        $data = $this->input->post(null, true);
        $_SESSION['tooltip_str']['from'] = $data['from_field_str'];
        $_SESSION['tooltip_str']['to'] = $data['to_field_str'];
        unset($data['from_field_str']);
        unset($data['to_field_str']);
        $this->load->model('flight_model');
        if ($data) {
            $row_id = $this->flight_model->add_user_return_details($data);
            $temp_arr = json_decode(stripcslashes($data['outbound_booking_details']));
            if (is_array($temp_arr->rest->Segment->WSSegment)) {
                $flag = 0;
                foreach($temp_arr->rest->Segment->WSSegment as $connectingFlight) {
                    $atime = explode("T", $connectingFlight->ArrTime);
                    $dtime = explode("T", $connectingFlight->DepTIme);
                    $date_d = new DateTime($dtime[0] . '' . $dtime[1]);
                    $date_a = new DateTime($atime[0] . '' . $atime[1]);
                    if ($flag == 1) {
                        $lay_d = new DateTime($dtime[0] . '' . $dtime[1]);
                        $diff_lay = $lay_d->diff($lay_a);
                        $d = $diff_lay->d . 'd';
                        $hr = $diff_lay->h . 'h';
                        $min = $diff_lay->i . 'm';
                        if ($diff_lay->d == 0) $temp_layover[] = $hr . ' ' . $min;
                        else $temp_layover[] = $d . ' ' . $hr . ' ' . $min;
                        $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                    }
                    else {
                        $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                        $flag++;
                    }
                }

                $data['outbound_layover'] = $temp_layover;
            }

            $temp_arr = json_decode(stripcslashes($data['inbound_booking_details']));
            if (is_array($temp_arr->rest->Segment->WSSegment)) {
                $flag = 0;
                foreach($temp_arr->rest->Segment->WSSegment as $connectingFlight) {
                    $atime = explode("T", $connectingFlight->ArrTime);
                    $dtime = explode("T", $connectingFlight->DepTIme);
                    $date_d = new DateTime($dtime[0] . '' . $dtime[1]);
                    $date_a = new DateTime($atime[0] . '' . $atime[1]);
                    if ($flag == 1) {
                        $lay_d = new DateTime($dtime[0] . '' . $dtime[1]);
                        $diff_lay = $lay_d->diff($lay_a);
                        $d = $diff_lay->d . 'd';
                        $hr = $diff_lay->h . 'h';
                        $min = $diff_lay->i . 'm';
                        if ($diff_lay->d == 0) $temp_layover_in[] = $hr . ' ' . $min;
                        else $temp_layover_in[] = $d . ' ' . $hr . ' ' . $min;
                        $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                    }
                    else {
                        $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                        $flag++;
                    }
                }

                $data['inbound_layover'] = $temp_layover_in;
            }

            $_SESSION['return_traveller_id'] = $row_id;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $hash_val = '';
            for ($i = 0; $i < 10; $i++) {
                $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
            }

            $hash_val = md5($hash_val);
            $data['hash_val'] = $hash_val;
        }
        else {
            $row_id = $_SESSION['return_traveller_id'];

            // $row_id = $this->session->userdata('return_traveller_id');

            $retVal = $this->flight_model->get_return_user_info($row_id);
            $data['outbound_booking_details'] = $retVal[0]->outbound_booking_details;
            $data['outbound_flight_type'] = $retVal[0]->outbound_flight_type;
            $data['outbound_from_field'] = $retVal[0]->outbound_from_field;
            $data['outbound_to_field'] = $retVal[0]->outbound_to_field;
            $data['outbound_flight_duration_field'] = $retVal[0]->outbound_flight_duration_field;
            $data['outbound_total_fare_field'] = $retVal[0]->outbound_total_fare_field;
            $data['inbound_to_date'] = $retVal[0]->inbound_to_date;
            $data['outbound_airline_name_field'] = $retVal[0]->outbound_airline_name_field;
            $data['outbound_source'] = $retVal[0]->outbound_source;
            $data['outbound_destination'] = $retVal[0]->outbound_destination;
            $data['inbound_booking_details'] = $retVal[0]->inbound_booking_details;
            $data['inbound_flight_type'] = $retVal[0]->inbound_flight_type;
            $data['inbound_from_field'] = $retVal[0]->inbound_from_field;
            $data['inbound_to_field'] = $retVal[0]->inbound_to_field;
            $data['inbound_flight_duration_field'] = $retVal[0]->inbound_flight_duration_field;
            $data['inbound_total_fare_field'] = $retVal[0]->inbound_total_fare_field;
            $data['inbound_from_date'] = $retVal[0]->inbound_from_date;
            $data['inbound_airline_name_field'] = $retVal[0]->inbound_airline_name_field;
            $data['inbound_source'] = $retVal[0]->inbound_source;
            $data['inbound_destination'] = $retVal[0]->inbound_destination;
            $data['adult_count_field'] = $retVal[0]->adult_count_field;
            $data['youth_count_field'] = $retVal[0]->youth_count_field;
            $data['kids_count_field'] = $retVal[0]->kids_count_field;
            $data['total_count_field'] = $retVal[0]->total_count_field;
        }

        $data['outbound_booking_details'] = json_decode(stripslashes($data['outbound_booking_details']));
        $data['inbound_booking_details'] = json_decode(stripslashes($data['inbound_booking_details']));
        $data['outbound_flight_type'] = json_decode(stripslashes($data['outbound_flight_type']));
        $data['inbound_flight_type'] = json_decode(stripslashes($data['inbound_flight_type']));

        $temp_arr = $data['outbound_booking_details'];
        if (is_array($temp_arr->rest->Segment->WSSegment)) {
            $flag = 0;
            foreach($temp_arr->rest->Segment->WSSegment as $connectingFlight) {
                $atime = explode("T", $connectingFlight->ArrTime);
                $dtime = explode("T", $connectingFlight->DepTIme);
                $date_d = new DateTime($dtime[0] . '' . $dtime[1]);
                $date_a = new DateTime($atime[0] . '' . $atime[1]);
                if ($flag == 1) {
                    $lay_d = new DateTime($dtime[0] . '' . $dtime[1]);
                    $diff_lay = $lay_d->diff($lay_a);
                    $d = $diff_lay->d . 'd';
                    $hr = $diff_lay->h . 'h';
                    $min = $diff_lay->i . 'm';
                    if ($diff_lay->d == 0) $temp_layover[] = $hr . ' ' . $min;
                    else $temp_layover[] = $d . ' ' . $hr . ' ' . $min;
                    $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                }
                else {
                    $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                    $flag++;
                }
            }

            $data['outbound_layover'] = $temp_layover;
        }

        $temp_arr = $data['inbound_booking_details'];
        if (is_array($temp_arr->rest->Segment->WSSegment)) {
            $flag = 0;
            foreach($temp_arr->rest->Segment->WSSegment as $connectingFlight) {
                $atime = explode("T", $connectingFlight->ArrTime);
                $dtime = explode("T", $connectingFlight->DepTIme);
                $date_d = new DateTime($dtime[0] . '' . $dtime[1]);
                $date_a = new DateTime($atime[0] . '' . $atime[1]);
                if ($flag == 1) {
                    $lay_d = new DateTime($dtime[0] . '' . $dtime[1]);
                    $diff_lay = $lay_d->diff($lay_a);
                    $d = $diff_lay->d . 'd';
                    $hr = $diff_lay->h . 'h';
                    $min = $diff_lay->i . 'm';
                    if ($diff_lay->d == 0) $temp_layover_in[] = $hr . ' ' . $min;
                    else $temp_layover_in[] = $d . ' ' . $hr . ' ' . $min;
                    $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                }
                else {
                    $lay_a = new DateTime($atime[0] . '' . $atime[1]);
                    $flag++;
                }
            }

            $data['inbound_layover'] = $temp_layover_in;
        }

        if ($data['outbound_flight_type']->type) {
            $data['booking_details'] = $data['outbound_booking_details'];
            $data['type_flight'] = "outbound";
            $data['outbound_total_fare_field'] = $this->get_fare_quote_return($data);
        }

        if ($data['inbound_flight_type']->type) {
            $data['booking_details'] = $data['inbound_booking_details'];
            $data['type_flight'] = "inbound";
            $data['inbound_total_fare_field'] = $this->get_fare_quote_return($data);
        }
        $this->load->model('admin/convenience_model');
        $convenience = $this->convenience_model->get_convenience_charge();
        $data['convenience_charge'] = $convenience->convenience_charge;
        $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
        $this->load->view("common/header.php");
        $this->load->view('flights/traveller_return_book_page', array(
            'data' => $data
        ));
        $this->load->view("common/footer.php");
    }

    function traveller_multi_details()
    {
        //$_SESSION['currentUrlFlight'] = current_full_url();
        //print_r($_SESSION['currentUrlFlight']);die;
        $this->load->model('admin/convenience_model');
        $convenience = $this->convenience_model->get_convenience_charge();
        $data['convenience_charge'] = $convenience->convenience_charge;
        $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
        $this->load->view("common/header.php");
        $this->load->view('flights/traveller_multi_book_page',$data);
        $this->load->view("common/footer.php");
    }

    function get_fare_quote($data)
    {
        $data['booking_details'] = json_decode(stripslashes($data['booking_details']), TRUE);
        $data['fare'] = json_decode(stripslashes($data['fare']), TRUE);
        $data['fare_breakdown'] = json_decode(stripslashes($data['fare_breakdown']), TRUE);
        $data['fare_rule'] = json_decode(stripslashes($data['fare_rule']), TRUE);
        require_once (APPPATH . 'lib/nusoap.php');

        $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
        $headerpara = array();
        $headerpara["UserName"] = 'redytrip';
        $headerpara["Password"] = 'redytrip@12';
        $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array(
            $client_header
        ));
        $fare = array();

        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Origin"]  = $data['origin'];
        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Destination"] = $data['destination'];
        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["TripIndicator"] = $data['TripIndicator'];

        $i = 0;
        if( count($data['fare_breakdown']) > 1 ){
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"][$i] = $data['fare_breakdown']['WSPTCFare'];
        }else{
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"] = $data['fare_breakdown']['WSPTCFare'];
        }
     
        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']['Fare'] = $data['fare'];

        if( count($data['booking_details']) > 1){
            foreach( $data['booking_details'] as $bd ){
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i] = $bd;
                $i++;
            }
        }else{
            foreach( $data['booking_details'] as $bd ){
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"] = $bd;
            }
        }
        $durArr = explode(' ', $data['flight_duration_field']);
        $hArr = explode('h', $durArr[0]);
        $mArr = explode('m', $durArr[1]);
        $duration = $hArr[0].':'.$mArr[0];
        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["ObDuration"] = $duration;
        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Source"] = $data['Source'];

        if( count($data['fare_rule']) > 1){
            foreach( $data['fare_rule'] as $fr ){
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"][$i] = $fr;
                $i++;
            }
        }else{
            foreach( $data['fare_rule'] as $fr ){
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"] = $fr;
            }
        }

        if( $data['non_refundable'] == 'true' ){
            $data['non_refundable'] = 1;
        }else{
            $data['non_refundable'] = 0;
        }

        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["IsLcc"] = $data['flight_type'];
        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["IbSegCount"] = $data['IbSegCount'];
        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["ObSegCount"] = $data['ObSegCount'];
        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["PromotionalPlanType"] = $data['PromotionalPlanType'];
        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["NonRefundable"] = $data['non_refundable'];
        $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["SegmentKey"] = $data['segment_key'];
        if (isset($data['session_id'])) {
            $_SESSION['sess_id'] = $data['session_id'];
        }

        $fare["GetFareQuote"]["fareQuoteRequest"]["SessionId"] = $_SESSION['sess_id'][$_SESSION['cnt_val'] - 1];
        $fare_arr = $fare['GetFareQuote']['fareQuoteRequest']['Result']['WSResult']['Fare'];
        $total_base_fare = $fare_arr['BaseFare'];
        $total_tax_fare = $fare_arr['AdditionalTxnFee'] + $fare_arr['AirTransFee'] + $fare_arr['Tax'] + $fare_arr['OtherCharges'] + $fare_arr['ServiceTax'];
        $total_fare_before = $total_base_fare + $total_tax_fare;   

        $header = array();
        $header['se'] = (array)$client->__call('GetFareQuote', $fare);

        $fare_obj = $header['se']['GetFareQuoteResult']->Result->Fare;
        $total_base_fare_after = $fare_obj->BaseFare;
        $total_tax_fare_after = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
        $total_fare_after = $total_base_fare_after + $total_tax_fare_after;

        if( $total_fare_before == $total_fare_after ){
            $_SESSION['hasFareChanged'] = 0;
            $_SESSION['new_total_fare'] = $total_fare_before;
        }else{
            $_SESSION['hasFareChanged'] = 1;
            $_SESSION['new_total_fare'] = $total_fare_after;
            $_SESSION['old_total_fare'] = $total_fare_before;
            $_SESSION['new_base_fare'] = $total_base_fare_after;
            $_SESSION['new_tax'] = $total_tax_fare_after;
        }
        $data['total_fare_field'] = $header['se']['GetFareQuoteResult']->Result->Fare->OfferedFare;
        $data['booking_details']->rest = $header['se']['GetFareQuoteResult']->Result;

        return $data;
    }

    function get_fare_quote_return($data)
    {
        for ($i = 0; $i < 2; $i++) {
            $passenger_array = count($data['booking_details']->rest->FareBreakdown->WSPTCFare);
            /*lcc one way*/
            require_once (APPPATH . 'lib/nusoap.php');

            $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
            $headerpara = array();
            $headerpara["UserName"] = 'redytrip';
            $headerpara["Password"] = 'redytrip@12';
            $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
            $client = new SoapClient($wsdl);
            $client->__setSoapHeaders(array(
                $client_header
            ));
            $fare = array();
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["TripIndicator"] = $data['booking_details']->rest->TripIndicator;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["BaseFare"] = $data['booking_details']->rest->Fare->BaseFare;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["Tax"] = $data['booking_details']->rest->Fare->Tax;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["ServiceTax"] = $data['booking_details']->rest->Fare->ServiceTax;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["AdditionalTxnFee"] = $data['booking_details']->rest->Fare->AdditionalTxnFee;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["AgentCommission"] = $data['booking_details']->rest->Fare->AgentCommission;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["TdsOnCommission"] = $data['booking_details']->rest->Fare->TdsOnCommission;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["IncentiveEarned"] = $data['booking_details']->rest->Fare->IncentiveEarned;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["TdsOnIncentive"] = $data['booking_details']->rest->Fare->TdsOnIncentive;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["PLBEarned"] = $data['booking_details']->rest->Fare->PLBEarned;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["TdsOnPLB"] = $data['booking_details']->rest->Fare->TdsOnPLB;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["PublishedPrice"] = $data['booking_details']->rest->Fare->PublishedPrice;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["AirTransFee"] = $data['booking_details']->rest->Fare->AirTransFee;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["Currency"] = $data['booking_details']->rest->Fare->Currency;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["Discount"] = $data['booking_details']->rest->Fare->Discount;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["PriceId"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[0]->PriceId;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["ChargeType"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[0]->ChargeType;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["Amount"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[0]->Amount;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["PriceId"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[1]->PriceId;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["ChargeType"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[1]->ChargeType;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["Amount"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[1]->Amount;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["OtherCharges"] = $data['booking_details']->rest->Fare->OtherCharges;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["FuelSurcharge"] = $data['booking_details']->rest->Fare->FuelSurcharge;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["TransactionFee"] = $data['booking_details']->rest->Fare->TransactionFee;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["ReverseHandlingCharge"] = $data['booking_details']->rest->Fare->ReverseHandlingCharge;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["OfferedFare"] = $data['booking_details']->rest->Fare->OfferedFare;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["AgentServiceCharge"] = $data['booking_details']->rest->Fare->AgentServiceCharge;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Fare"]["AgentConvienceCharges"] = $data['booking_details']->rest->Fare->AgentConvienceCharges;
            if (!is_array($data['booking_details']->rest->FareBreakdown->WSPTCFare)) {
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"]["PassengerType"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->PassengerType;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"]["PassengerCount"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->PassengerCount;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"]["BaseFare"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->BaseFare;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"]["Tax"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->Tax;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"]["AirlineTransFee"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->AirlineTransFee;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"]["AdditionalTxnFee"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"]["FuelSurcharge"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->FuelSurcharge;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"]["AgentServiceCharge"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->AgentServiceCharge;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"]["AgentConvienceCharges"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges;
            }

            $i = 0;
            if (is_array($data['booking_details']->rest->FareBreakdown->WSPTCFare)) {
                foreach($data['booking_details']->rest->FareBreakdown->WSPTCFare as $pass) {
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"][$i]["PassengerType"] = $pass->PassengerType;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"][$i]["PassengerCount"] = $pass->PassengerCount;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"][$i]["BaseFare"] = $pass->BaseFare;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"][$i]["Tax"] = $pass->Tax;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"][$i]["AirlineTransFee"] = $pass->AirlineTransFee;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"][$i]["AdditionalTxnFee"] = $pass->AdditionalTxnFee;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"][$i]["FuelSurcharge"] = $pass->FuelSurcharge;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"][$i]["AgentServiceCharge"] = $pass->AgentServiceCharge;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareBreakdown"]["WSPTCFare"][$i]["AgentConvienceCharges"] = $pass->AgentConvienceCharges;
                }
            }

            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Origin"] = $data['booking_details']->rest->Origin;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Destination"] = $data['booking_details']->rest->Destination;
            $i = 0;
            if (is_array($data['booking_details']->rest->Segment->WSSegment)) {
                foreach($data['booking_details']->rest->Segment->WSSegment as $seg) {
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["SegmentIndicator"] = $seg->SegmentIndicator;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Airline"]["AirlineCode"] = $seg->Airline->AirlineCode;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Airline"]["AirlineName"] = $seg->Airline->AirlineName;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["FlightNumber"] = $seg->FlightNumber;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["FareClass"] = $seg->FareClass;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Origin"]["AirportCode"] = $seg->Origin->AirportCode;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Origin"]["AirportName"] = $seg->Origin->AirportName;
                    if (isset($seg->Origin->Terminal)) $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Origin"]["Terminal"] = $seg->Origin->Terminal;
                    else $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Origin"]["Terminal"] = '';
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Origin"]["CityCode"] = $seg->Origin->CityCode;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Origin"]["CityName"] = $seg->Origin->CityName;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Origin"]["CountryCode"] = $seg->Origin->CountryCode;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Origin"]["CountryName"] = $seg->Origin->CountryName;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Destination"]["AirportCode"] = $seg->Destination->AirportCode;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Destination"]["AirportName"] = $seg->Destination->AirportName;
                    if (isset($seg->Destination->Terminal)) $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Destination"]["Terminal"] = $seg->Destination->Terminal;
                    else $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Destination"]["Terminal"] = '';
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Destination"]["CityCode"] = $seg->Destination->CityCode;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Destination"]["CityName"] = $seg->Destination->CityName;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Destination"]["CountryCode"] = $seg->Destination->CountryCode;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Destination"]["CountryName"] = $seg->Destination->CountryName;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["DepTIme"] = $seg->DepTIme;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["ArrTime"] = $seg->ArrTime;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["ETicketEligible"] = $seg->ETicketEligible;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Duration"] = $seg->Duration;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Stop"] = $seg->Stop;
                    if (isset($seg->Craft)) $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Craft"] = $seg->Craft;
                    else $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Craft"] = '';
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"][$i]["Status"] = $seg->Status;
                    $i++;
                }
            }

            if (!is_array($data['booking_details']->rest->Segment->WSSegment)) {
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["SegmentIndicator"] = $data['booking_details']->rest->Segment->WSSegment->SegmentIndicator;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Airline"]["AirlineCode"] = $data['booking_details']->rest->Segment->WSSegment->Airline->AirlineCode;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Airline"]["AirlineName"] = $data['booking_details']->rest->Segment->WSSegment->Airline->AirlineName;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["FlightNumber"] = $data['booking_details']->rest->Segment->WSSegment->FlightNumber;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["FareClass"] = $data['booking_details']->rest->Segment->WSSegment->FareClass;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Origin"]["AirportCode"] = $data['booking_details']->rest->Segment->WSSegment->Origin->AirportCode;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Origin"]["AirportName"] = $data['booking_details']->rest->Segment->WSSegment->Origin->AirportName;
                if (isset($data['booking_details']->rest->Segment->WSSegment->Origin->Terminal)) $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Origin"]["Terminal"] = $data['booking_details']->rest->Segment->WSSegment->Origin->Terminal;
                else $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Origin"]["Terminal"] = '';
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Origin"]["CityCode"] = $data['booking_details']->rest->Segment->WSSegment->Origin->CityCode;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Origin"]["CityName"] = $data['booking_details']->rest->Segment->WSSegment->Origin->CityName;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Origin"]["CountryCode"] = $data['booking_details']->rest->Segment->WSSegment->Origin->CountryCode;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Origin"]["CountryName"] = $data['booking_details']->rest->Segment->WSSegment->Origin->CountryName;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Destination"]["AirportCode"] = $data['booking_details']->rest->Segment->WSSegment->Destination->AirportCode;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Destination"]["AirportName"] = $data['booking_details']->rest->Segment->WSSegment->Destination->AirportName;
                if (isset($data['booking_details']->rest->Segment->WSSegment->Destination->Terminal)) $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Destination"]["Terminal"] = $data['booking_details']->rest->Segment->WSSegment->Destination->Terminal;
                else $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Destination"]["Terminal"] = '';
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Destination"]["CityCode"] = $data['booking_details']->rest->Segment->WSSegment->Destination->CityCode;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Destination"]["CityName"] = $data['booking_details']->rest->Segment->WSSegment->Destination->CityName;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Destination"]["CountryCode"] = $data['booking_details']->rest->Segment->WSSegment->Destination->CountryCode;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Destination"]["CountryName"] = $data['booking_details']->rest->Segment->WSSegment->Destination->CountryName;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["DepTIme"] = $data['booking_details']->rest->Segment->WSSegment->DepTIme;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["ArrTime"] = $data['booking_details']->rest->Segment->WSSegment->ArrTime;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["ETicketEligible"] = $data['booking_details']->rest->Segment->WSSegment->ETicketEligible;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Duration"] = $data['booking_details']->rest->Segment->WSSegment->Duration;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Stop"] = $data['booking_details']->rest->Segment->WSSegment->Stop;
                if (isset($data['booking_details']->rest->Segment->WSSegment->Craft)) $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Craft"] = $data['booking_details']->rest->Segment->WSSegment->Craft;
                else $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Craft"] = '';
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Segment"]["WSSegment"]["Status"] = $data['booking_details']->rest->Segment->WSSegment->Status;
            }

            // $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]["IbDuration"] = $data['booking_details']->rest->IbDuration;

            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["ObDuration"] = $data['booking_details']->rest->ObDuration;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["Source"] = $data['booking_details']->rest->Source;
            if (!is_array($data['booking_details']->rest->FareRule->WSFareRule)) {
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"]["Origin"] = $data['booking_details']->rest->FareRule->WSFareRule->Origin;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"]["Destination"] = $data['booking_details']->rest->FareRule->WSFareRule->Destination;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"]["Airline"] = $data['booking_details']->rest->FareRule->WSFareRule->Airline;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"]["FareBasisCode"] = $data['booking_details']->rest->FareRule->WSFareRule->FareBasisCode;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"]["DepartureDate"] = $data['booking_details']->rest->FareRule->WSFareRule->DepartureDate;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"]["ReturnDate"] = $data['booking_details']->rest->FareRule->WSFareRule->ReturnDate;
                $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"]["Source"] = $data['booking_details']->rest->FareRule->WSFareRule->Source;
            }

            $i = 0;
            if (is_array($data['booking_details']->rest->FareRule->WSFareRule)) {
                foreach($data['booking_details']->rest->FareRule->WSFareRule as $fare_rule) {
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"][$i]["Origin"] = $fare_rule->Origin;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"][$i]["Destination"] = $fare_rule->Destination;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"][$i]["Airline"] = $fare_rule->Airline;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"][$i]["FareBasisCode"] = $fare_rule->FareBasisCode;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"][$i]["DepartureDate"] = $fare_rule->DepartureDate;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"][$i]["ReturnDate"] = $fare_rule->ReturnDate;
                    $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["FareRule"]["WSFareRule"][$i]["Source"] = $fare_rule->Source;
                    $i++;
                }
            }

            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["IsLcc"] = $data['booking_details']->rest->IsLcc;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["IbSegCount"] = $data['booking_details']->rest->IbSegCount;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["ObSegCount"] = $data['booking_details']->rest->ObSegCount;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["PromotionalPlanType"] = $data['booking_details']->rest->PromotionalPlanType;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["NonRefundable"] = $data['booking_details']->rest->NonRefundable;
            $fare["GetFareQuote"]["fareQuoteRequest"]["Result"]['WSResult']["SegmentKey"] = $data['booking_details']->rest->SegmentKey;
            if ($data['type_flight'] == "outbound") {
                $fare["GetFareQuote"]["fareQuoteRequest"]["SessionId"] = $_SESSION['outbound_id'];
                $fare_arr_out_before = $fare['GetFareQuote']['fareQuoteRequest']['Result']['WSResult']['Fare'];
                $total_base_fare_out_before = $fare_arr_out_before['BaseFare'];
                $total_tax_fare_out_before = $fare_arr_out_before['AdditionalTxnFee'] + $fare_arr_out_before['AirTransFee'] + $fare_arr_out_before['Tax'] + $fare_arr_out_before['OtherCharges'] + $fare_arr_out['ServiceTax'];
                $total_fare_before_out = $total_base_fare_out_before + $total_tax_fare_out_before;
            }
            else {
                $fare["GetFareQuote"]["fareQuoteRequest"]["SessionId"] = $_SESSION['inbound_id'];
                $fare_arr_in_before = $fare['GetFareQuote']['fareQuoteRequest']['Result']['WSResult']['Fare'];
                $total_base_fare_in_before = $fare_arr_in_before['BaseFare'];
                $total_tax_fare_in_before = $fare_arr_in_before['AdditionalTxnFee'] + $fare_arr_in_before['AirTransFee'] + $fare_arr_in_before['Tax'] + $fare_arr_in_before['OtherCharges'] + $fare_arr_in_before['ServiceTax'];
                $total_fare_before_in = $total_base_fare_in_before + $total_tax_fare_in_before;
            }
            $header = array();
            $header['se'] = (array)$client->__call('GetFareQuote', $fare);
            $fare_obj = $header['se']['GetFareQuoteResult']->Result->Fare;
            //$data['total_fare_field'] = $header['se']['GetFareQuoteResult']->Result->Fare->OfferedFare;
            if ($data['type_flight'] == "outbound") {  
                $total_base_fare_after_out = $fare_obj->BaseFare;
                $total_tax_fare_after_out = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $total_fare_after_out = $total_base_fare_after_out + $total_tax_fare_after_out;

                if( $total_fare_before_out == $total_fare_after_out ){
                    $_SESSION['hasFareChangedOut'] = 0;
                    $_SESSION['new_total_fare_out'] = $total_fare_before_out;
                    $_SESSION['old_total_fare_out'] = $total_fare_before_out;
                }else{
                    $_SESSION['hasFareChangedOut'] = 1;
                    $_SESSION['new_total_fare_out'] = $total_fare_after_out;
                    $_SESSION['old_total_fare_out'] = $total_fare_before_out;
                    $_SESSION['new_base_fare_out'] = $total_base_fare_after_out;
                    $_SESSION['new_tax_out'] = $total_tax_fare_after_out;
                }
            }
            else {
                $total_base_fare_after_in = $fare_obj->BaseFare;
                $total_tax_fare_after_in = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $total_fare_after_in = $total_base_fare_after_in + $total_tax_fare_after_in;

                if( $total_fare_before_in == $total_fare_after_in ){
                    $_SESSION['hasFareChangedIn'] = 0;
                    $_SESSION['new_total_fare_in'] = $total_fare_before_in;
                    $_SESSION['old_total_fare_in'] = $total_fare_before_in;
                }else{
                    $_SESSION['hasFareChangedIn'] = 1;
                    $_SESSION['new_total_fare_in'] = $total_fare_after_in;
                    $_SESSION['old_total_fare_in'] = $total_fare_before_in;
                    $_SESSION['new_base_fare_in'] = $total_base_fare_after_in;
                    $_SESSION['new_tax_in'] = $total_tax_fare_after_in;
                }
            }    
            return $data['total_fare_field'];
        }
    }

    function get_fare_rule($data)
    {
        // print_r( XmlConvert.ToDateTime($data['booking_details']->rest->FareRule->WSFareRule->ReturnDate));die;
        // $count = $data['adult_count_field'] + $data['kids_count_field'] + $data['youth_count_field'];

        $passenger_array = count($data['booking_details']->rest->FareBreakdown->WSPTCFare);
        /*lcc one way*/
        require_once (APPPATH . 'lib/nusoap.php');

        $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
        $headerpara = array();
        $headerpara["UserName"] = 'redytrip';
        $headerpara["Password"] = 'redytrip@12';
        $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array(
            $client_header
        ));
        $fare = array();
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["TripIndicator"] = $data['booking_details']->rest->TripIndicator;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["BaseFare"] = $data['booking_details']->rest->Fare->BaseFare;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["Tax"] = $data['booking_details']->rest->Fare->Tax;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["ServiceTax"] = $data['booking_details']->rest->Fare->ServiceTax;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["AdditionalTxnFee"] = $data['booking_details']->rest->Fare->AdditionalTxnFee;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["AgentCommission"] = $data['booking_details']->rest->Fare->AgentCommission;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["TdsOnCommission"] = $data['booking_details']->rest->Fare->TdsOnCommission;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["IncentiveEarned"] = $data['booking_details']->rest->Fare->IncentiveEarned;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["TdsOnIncentive"] = $data['booking_details']->rest->Fare->TdsOnIncentive;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["PLBEarned"] = $data['booking_details']->rest->Fare->PLBEarned;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["TdsOnPLB"] = $data['booking_details']->rest->Fare->TdsOnPLB;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["PublishedPrice"] = $data['booking_details']->rest->Fare->PublishedPrice;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["AirTransFee"] = $data['booking_details']->rest->Fare->AirTransFee;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["Currency"] = $data['booking_details']->rest->Fare->Currency;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["Discount"] = $data['booking_details']->rest->Fare->Discount;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["PriceId"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[0]->PriceId;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["ChargeType"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[0]->ChargeType;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["Amount"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[0]->Amount;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["PriceId"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[1]->PriceId;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["ChargeType"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[1]->ChargeType;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["ChargeBU"]["ChargeBreakUp"][0]["Amount"] = $data['booking_details']->rest->Fare->ChargeBU->ChargeBreakUp[1]->Amount;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["OtherCharges"] = $data['booking_details']->rest->Fare->OtherCharges;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["FuelSurcharge"] = $data['booking_details']->rest->Fare->FuelSurcharge;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["TransactionFee"] = $data['booking_details']->rest->Fare->TransactionFee;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["ReverseHandlingCharge"] = $data['booking_details']->rest->Fare->ReverseHandlingCharge;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["OfferedFare"] = $data['booking_details']->rest->Fare->OfferedFare;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["AgentServiceCharge"] = $data['booking_details']->rest->Fare->AgentServiceCharge;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Fare"]["AgentConvienceCharges"] = $data['booking_details']->rest->Fare->AgentConvienceCharges;
        if (!is_array($data['booking_details']->rest->FareBreakdown->WSPTCFare)) {
            $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"]["PassengerType"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->PassengerType;
            $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"]["PassengerCount"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->PassengerCount;
            $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"]["BaseFare"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->BaseFare;
            $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"]["Tax"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->Tax;
            $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"]["AirlineTransFee"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->AirlineTransFee;
            $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"]["AdditionalTxnFee"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee;
            $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"]["FuelSurcharge"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->FuelSurcharge;
            $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"]["AgentServiceCharge"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->AgentServiceCharge;
            $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"]["AgentConvienceCharges"] = $data['booking_details']->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges;
        }

        $i = 0;
        if (is_array($data['booking_details']->rest->FareBreakdown->WSPTCFare)) {
            foreach($data['booking_details']->rest->FareBreakdown->WSPTCFare as $pass) {
                $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"][$i]["PassengerType"] = $pass->PassengerType;
                $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"][$i]["PassengerCount"] = $pass->PassengerCount;
                $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"][$i]["BaseFare"] = $pass->BaseFare;
                $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"][$i]["Tax"] = $pass->Tax;
                $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"][$i]["AirlineTransFee"] = $pass->AirlineTransFee;
                $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"][$i]["AdditionalTxnFee"] = $pass->AdditionalTxnFee;
                $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"][$i]["FuelSurcharge"] = $pass->FuelSurcharge;
                $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"][$i]["AgentServiceCharge"] = $pass->AgentServiceCharge;
                $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareBreakdown"]["WSPTCFare"][$i]["AgentConvienceCharges"] = $pass->AgentConvienceCharges;
            }
        }

        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Origin"] = $data['booking_details']->rest->Origin;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Destination"] = $data['booking_details']->rest->Destination;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["SegmentIndicator"] = $data['booking_details']->rest->Segment->WSSegment->SegmentIndicator;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Airline"]["AirlineCode"] = $data['booking_details']->rest->Segment->WSSegment->Airline->AirlineCode;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Airline"]["AirlineName"] = $data['booking_details']->rest->Segment->WSSegment->Airline->AirlineName;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["FlightNumber"] = $data['booking_details']->rest->Segment->WSSegment->FlightNumber;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["FareClass"] = $data['booking_details']->rest->Segment->WSSegment->FareClass;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Origin"]["AirportCode"] = $data['booking_details']->rest->Segment->WSSegment->Origin->AirportCode;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Origin"]["AirportName"] = $data['booking_details']->rest->Segment->WSSegment->Origin->AirportName;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Origin"]["Terminal"] = $data['booking_details']->rest->Segment->WSSegment->Origin->Terminal;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Origin"]["CityCode"] = $data['booking_details']->rest->Segment->WSSegment->Origin->CityCode;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Origin"]["CityName"] = $data['booking_details']->rest->Segment->WSSegment->Origin->CityName;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Origin"]["CountryCode"] = $data['booking_details']->rest->Segment->WSSegment->Origin->CountryCode;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Origin"]["CountryName"] = $data['booking_details']->rest->Segment->WSSegment->Origin->CountryName;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Destination"]["AirportCode"] = $data['booking_details']->rest->Segment->WSSegment->Destination->AirportCode;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Destination"]["AirportName"] = $data['booking_details']->rest->Segment->WSSegment->Destination->AirportName;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Destination"]["Terminal"] = $data['booking_details']->rest->Segment->WSSegment->Destination->Terminal;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Destination"]["CityCode"] = $data['booking_details']->rest->Segment->WSSegment->Destination->CityCode;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Destination"]["CityName"] = $data['booking_details']->rest->Segment->WSSegment->Destination->CityName;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Destination"]["CountryCode"] = $data['booking_details']->rest->Segment->WSSegment->Destination->CountryCode;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Destination"]["CountryName"] = $data['booking_details']->rest->Segment->WSSegment->Destination->CountryName;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["DepTIme"] = $data['booking_details']->rest->Segment->WSSegment->DepTIme;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["ArrTime"] = $data['booking_details']->rest->Segment->WSSegment->ArrTime;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["ETicketEligible"] = $data['booking_details']->rest->Segment->WSSegment->ETicketEligible;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Duration"] = $data['booking_details']->rest->Segment->WSSegment->Duration;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Stop"] = $data['booking_details']->rest->Segment->WSSegment->Stop;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Craft"] = $data['booking_details']->rest->Segment->WSSegment->Craft;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Segment"]["WSSegment"]["Status"] = $data['booking_details']->rest->Segment->WSSegment->Status;

        // $fare["GetFareRule"]["fareRuleRequest"]["Result"]["IbDuration"] = $data['booking_details']->rest->IbDuration;

        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["ObDuration"] = $data['booking_details']->rest->ObDuration;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["Source"] = $data['booking_details']->rest->Source;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareRule"]["WSFareRule"]["Origin"] = $data['booking_details']->rest->FareRule->WSFareRule->Origin;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareRule"]["WSFareRule"]["Destination"] = $data['booking_details']->rest->FareRule->WSFareRule->Destination;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareRule"]["WSFareRule"]["Airline"] = $data['booking_details']->rest->FareRule->WSFareRule->Airline;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareRule"]["WSFareRule"]["FareBasisCode"] = $data['booking_details']->rest->FareRule->WSFareRule->FareBasisCode;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareRule"]["WSFareRule"]["DepartureDate"] = $data['booking_details']->rest->FareRule->WSFareRule->DepartureDate;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareRule"]["WSFareRule"]["ReturnDate"] = $data['booking_details']->rest->FareRule->WSFareRule->ReturnDate;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["FareRule"]["WSFareRule"]["Source"] = $data['booking_details']->rest->FareRule->WSFareRule->Source;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["IsLcc"] = $data['booking_details']->rest->IsLcc;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["IbSegCount"] = $data['booking_details']->rest->IbSegCount;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["ObSegCount"] = $data['booking_details']->rest->ObSegCount;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["PromotionalPlanType"] = $data['booking_details']->rest->PromotionalPlanType;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["NonRefundable"] = $data['booking_details']->rest->NonRefundable;
        $fare["GetFareRule"]["fareRuleRequest"]["Result"]["SegmentKey"] = $data['booking_details']->rest->SegmentKey;
        $fare["GetFareRule"]["fareRuleRequest"]["SessionId"] = $this->session->userdata('sess_id');
        $header = array();
        $header['se'] = (array)$client->__call('GetFareRule', $fare);
    }

    function book()
    {
        $data = $_SESSION['onewayFlightTravellerData'];
        $travellerData = $_POST;
        $data['booking_details'] = json_decode(stripslashes($data['booking_details']), TRUE);
        $data['fare'] = json_decode(stripslashes($data['fare']), TRUE);
        $data['fare_breakdown'] = json_decode(stripslashes($data['fare_breakdown']), TRUE);
        $data['fare_rule'] = json_decode(stripslashes($data['fare_rule']), TRUE);

        $flightsAPIAuthObj = new FlightsAPIAuth;
        $flightsSearchRequestObj = new FlightsSearchRequest;
        $flightsSOAPObj = new FlightsSOAP;
        $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;

        $flightsAPIAuthObj->setUserId("redytrip");
        $flightsAPIAuthObj->setPassword("redytrip@12");
        $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

        $getPassengerDataObj = new GetPassengerData;
        $getBookRequestObj = new GetBookRequest;

        $passengerData = $getPassengerDataObj->setPassengerData($data,$travellerData);
      
        if ($data['flight_type'] == 0) {
            $book = $getBookRequestObj->setBookRequest($data,$passengerData);

            $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
            $flightsSOAPObj->setSOAPClient();
            $flightsSOAPObj->setSOAPHeader($authDataArr);

            $result = $flightsSOAPObj->makeSOAPCall("Book", $book);

            //generate farebucket booking id (FBFL123456) 
            $fbBooking = 'FBFL';
            $this->load->model('flight_model');
            $returnId = $this->flight_model->getLastFbBookingId();
            if(strlen($returnId) === 0){
                $randomNum = 1;
            }
            else{
                $splitReturnID = explode("FBFL",$returnId);
                $randomNum = $splitReturnID[1] + 1;
            } 
            
            $randomNum = sprintf("%06d",$randomNum);
            $fbBookingId = $fbBooking . $randomNum;
            $data['fbBookingId'] = $fbBookingId;
            //generate farebucket booking id end

            if ($result->BookResult->Status->Description == "Successful" || $result->BookResult->Status->Description == "Fare is not available at the time of booking") {
                $data['status'] = $result->BookResult->Status->Description;
                $data['pnr'] = $result->BookResult->PNR;
                $data['booking_id'] = $result->BookResult->BookingId;
                $this->load->view('common/header.php');
                $this->load->view('flights/book_intermediate_page', array(
                    'data' => $data,
                    'passengerData' => $passengerData
                ));
                $this->load->view('common/footer.php');
            }
            
            else {
                redirect('api/flights/booking_failed');     
            }
        }

        if ($data['flight_type'] == 1) {

            $fbBooking = 'FBFL';
            $this->load->model('flight_model');
            $returnId = $this->flight_model->getLastFbBookingId();
            if(strlen($returnId) === 0){
                $randomNum = 1;
            }
            else{
                $splitReturnID = explode("FBFL",$returnId);
                $randomNum = $splitReturnID[1] + 1;
            } 
            
            $randomNum = sprintf("%06d",$randomNum);
            $fbBookingId = $fbBooking . $randomNum;
            $data['fbBookingId'] = $fbBookingId;

            $data['status'] = "Pending";
            $data['pnr'] = "0";
            $data['booking_id'] = "0";
            $this->load->view('common/header.php');
            $this->load->view('flights/book_intermediate_page', array(
                'data' => $data,
                'passengerData' => $passengerData
            ));
            $this->load->view('common/footer.php');
        }
    }

    function book_return()
    {
        //$_SESSION['currentUrlFlight'] = current_full_url();
        $data = $this->input->post(null, true);

        $data['details'] = json_decode(stripslashes($data['details']));
        $booking_flag = 0;

        //farebucket booking id
        $fbBooking = 'FBFL';
        $this->load->model('flight_model');
        $returnId = $this->flight_model->getLastFbBookingId();
        if(strlen($returnId) === 0){
            $randomNum = 1;
        }
        else{
            $splitReturnID = explode("FBFL",$returnId);
            $randomNum = $splitReturnID[1] + 1;
        } 
        
        $randomNum = sprintf("%06d",$randomNum);
        $fbBookingId = $fbBooking . $randomNum;
        $data['fbBookingId'] = $fbBookingId;
        //farebucket booking id

        $getTravellerDataObj = new GetTravellerData;

        for ($l = 0; $l < 2; $l++) {
            if ($l == 0) {
                $data = $getTravellerDataObj->setTravellerData($data);

                if ($data['details']->outbound_flight_type->type == 0) {
                    
                    /**
                    * Object initialisation
                    */
                    $flightsAPIAuthObj = new FlightsAPIAuth;
                    $flightsSearchRequestObj = new FlightsSearchRequest;
                    $flightsSOAPObj = new FlightsSOAP;
                    $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;
                    $getReturnBookRequestObj = new GetReturnBookRequest;

                    /**
                    * Set Request Authentication Data array
                    * UserName
                    * Password
                    */
                    $flightsAPIAuthObj->setUserId("redytrip");
                    $flightsAPIAuthObj->setPassword("redytrip@12");
                    $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

                    $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
                    $flightsSOAPObj->setSOAPClient();
                    $flightsSOAPObj->setSOAPHeader($authDataArr);

                    $book = $getReturnBookRequestObj->setReturnBookRequest($data);
                    $result = $flightsSOAPObj->makeSOAPCall("Book", $book);

                    if ($result->BookResult->Status->Description == "Successful" || $result->BookResult->Status->Description == "Fare is not available at the time of booking") {
                        $out_data['outbound_status'] = $result->BookResult->Status->Description;
                        $out_data['outbound_pnr'] = $result->BookResult->PNR;
                        $out_data['outbound_booking_id'] = $result->BookResult->BookingId;
                    }
                    else {
                        $booking_flag++;
                        redirect('api/flights/booking_failed');
                    }
                }

                if ($data['details']->outbound_flight_type->type == 1) {
                    $out_data['outbound_status'] = "Pending";
                    $out_data['outbound_pnr'] = "0";
                    $out_data['outbound_booking_id'] = "0";
                }
            }

            /*----inbound---*/
            if ($l == 1) {
                
                $data = $getTravellerDataObj->setTravellerData($data);

                if ($data['details']->inbound_flight_type->type == 0) {

                    /**
                    * Object initialisation
                    */
                    $flightsAPIAuthObj = new FlightsAPIAuth;
                    $flightsSearchRequestObj = new FlightsSearchRequest;
                    $flightsSOAPObj = new FlightsSOAP;
                    $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;
                    $getInboundBookRequestObj = new GetInboundBookRequest;

                    /**
                    * Set Request Authentication Data array
                    * UserName
                    * Password
                    */
                    $flightsAPIAuthObj->setUserId("redytrip");
                    $flightsAPIAuthObj->setPassword("redytrip@12");
                    $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

                    $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
                    $flightsSOAPObj->setSOAPClient();
                    $flightsSOAPObj->setSOAPHeader($authDataArr);

                    $book = $getInboundBookRequestObj->setInboundBookRequest($data);
                    $result = $flightsSOAPObj->makeSOAPCall("Book", $book);

                    if ($result->BookResult->Status->Description == "Successful" || $result->BookResult->Status->Description == "Fare is not available at the time of booking") {
                        $data['inbound_status'] = $result->BookResult->Status->Description;
                        $data['inbound_pnr'] = $result->BookResult->PNR;
                        $data['inbound_booking_id'] = $result->BookResult->BookingId;
                    }
                    else {
                        $this->load->view('common/header.php');
                        $this->load->view('flights/failure_page.php');
                        $this->load->view('common/footer.php');
                    }
                }

                if ($data['details']->inbound_flight_type->type == 1) {
                    $data['inbound_status'] = "Pending";
                    $data['inbound_pnr'] = "0";
                    $data['inbound_booking_id'] = "0";
                }
            }
        }

        if ($booking_flag > 0) {
            redirect('api/flights/booking_failed');
        }

        $this->load->view('common/header.php');
        $this->load->view('flights/book_return_intermediate_page', array(
            'data' => $data,
            'data1' => $out_data
        ));
        $this->load->view('common/footer.php');
    }

    function booking_failed()
    {
        $this->load->view('common/header.php');
        $this->load->view('flights/failure_page.php');
        $this->load->view('common/footer.php');
    }

    function book_assorted(){
        $data = $this->input->get(null, true);
        $m = $this->input->get('index');
        $data['details'][$m] = $_SESSION['flight_data'][$m];
        $data['details'][$m]['booking_details'] = json_decode($data['details'][$m]['booking_details']);
        $data['details'][$m]['flight_type'] = json_decode($data['details'][$m]['flight_type']);
        for ($i = 0; $i < $_SESSION['details']['total_count']; $i++) {

            //lead traveller
            if( $i == 0 ){
                $data['title_lead'] = $data['Title' . $i . ''];
                $data['first_name_lead'] = $data['fname' . $i . ''];
                $data['last_name_lead'] = $data['lname' . $i . ''];
                $data['lead_adult_email_id'] = $data['email_id'];
                $data['lead_adult_mobile_no'] = $data['mobile'];

            }else{
                //others
                $user_age_raw = explode('-', $data['age' . $i . '']);
                $traveller_type = $user_age_raw[1];

                if( $traveller_type == 'adult' ){

                    $data['title_a'][] = $data['Title' . $i . ''];
                    $data['first_name_a'][] = $data['fname' . $i . ''];
                    $data['last_name_a'][] = $data['lname' . $i . ''];

                }else if( $traveller_type == 'child' ){

                    $data['title_k'][] = $data['Title' . $i . ''];
                    $data['first_name_k'][] = $data['fname' . $i . ''];
                    $data['last_name_k'][] = $data['lname' . $i . ''];
                    $data['dob_k'][] = $data['dob' . $i . ''];

                }else if( $traveller_type == 'infant' ){

                    $data['title_i'][] = $data['Title' . $i . ''];
                    $data['first_name_i'][] = $data['fname' . $i . ''];
                    $data['last_name_i'][] = $data['lname' . $i . ''];
                    $data['dob_i'][] = $data['dob' . $i . ''];

                }else{

                    print_r('Error, Please try again.');die;

                }
            }
        }

        if( isset($data['title_a']) ){
            $data['adult_title_csv'] = implode(",", $data['title_a']);
            $data['adult_first_name_csv'] = implode(",", $data['first_name_a']);
            $data['adult_last_name_csv'] = implode(",", $data['last_name_a']);
        }
        if( isset($data['title_k']) ){
            $data['kid_title_csv'] = implode(",", $data['title_k']);
            $data['kid_first_name_csv'] = implode(",", $data['first_name_k']);
            $data['kid_last_name_csv'] = implode(",", $data['last_name_k']);
            $data['kid_dob_csv'] = implode(",", $data['dob_k']);
        }
        if( isset($data['title_i']) ){
            $data['infant_title_csv'] = implode(",", $data['title_i']);
            $data['infant_first_name_csv'] = implode(",", $data['first_name_i']);
            $data['infant_last_name_csv'] = implode(",", $data['last_name_i']);
            $data['infant_dob_csv'] = implode(",", $data['dob_i']);
        }

        $booking_flag = 0;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $hash_val = '';
        for ($i = 0; $i < 10; $i++) {
            $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
        }
        $hash_val = md5($hash_val);
        $data['details'][$m] = (Object)$data['details'][$m];

            if ($data['details'][$m]->flight_type->type == 0) {
                require_once (APPPATH . 'lib/nusoap.php');

                $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
                $headerpara = array();
                $headerpara["UserName"] = 'redytrip';
                $headerpara["Password"] = 'redytrip@12';
                $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
                $client = new SoapClient($wsdl);
                $client->__setSoapHeaders(array(
                    $client_header
                ));
                $book = array();
                $book["Book"]["bookRequest"]["Remarks"] = "FlightBook";
                $book["Book"]["bookRequest"]["InstantTicket"] = True;
                $book["Book"]["bookRequest"]["Fare"]["BaseFare"] = $data['details'][$m]->booking_details->rest->Fare->BaseFare;
                $book["Book"]["bookRequest"]["Fare"]["Tax"] = $data['details'][$m]->booking_details->rest->Fare->Tax;
                $book["Book"]["bookRequest"]["Fare"]["ServiceTax"] = $data['details'][$m]->booking_details->rest->Fare->ServiceTax;
                $book["Book"]["bookRequest"]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->booking_details->rest->Fare->AdditionalTxnFee;
                $book["Book"]["bookRequest"]["Fare"]["AgentCommission"] = $data['details'][$m]->booking_details->rest->Fare->AgentCommission;
                $book["Book"]["bookRequest"]["Fare"]["TdsOnCommission"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnCommission;
                $book["Book"]["bookRequest"]["Fare"]["IncentiveEarned"] = $data['details'][$m]->booking_details->rest->Fare->IncentiveEarned;
                $book["Book"]["bookRequest"]["Fare"]["TdsOnIncentive"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnIncentive;
                $book["Book"]["bookRequest"]["Fare"]["PLBEarned"] = $data['details'][$m]->booking_details->rest->Fare->PLBEarned;
                $book["Book"]["bookRequest"]["Fare"]["TdsOnPLB"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnPLB;
                $book["Book"]["bookRequest"]["Fare"]["PublishedPrice"] = $data['details'][$m]->booking_details->rest->Fare->PublishedPrice;
                $book["Book"]["bookRequest"]["Fare"]["AirTransFee"] = $data['details'][$m]->booking_details->rest->Fare->AirTransFee;
                $book["Book"]["bookRequest"]["Fare"]["Currency"] = $data['details'][$m]->booking_details->rest->Fare->Currency;
                $book["Book"]["bookRequest"]["Fare"]["Discount"] = $data['details'][$m]->booking_details->rest->Fare->Discount;
                $book["Book"]["bookRequest"]["Fare"]["OtherCharges"] = $data['details'][$m]->booking_details->rest->Fare->OtherCharges;
                $book["Book"]["bookRequest"]["Fare"]["FuelSurcharge"] = $data['details'][$m]->booking_details->rest->Fare->FuelSurcharge;
                $book["Book"]["bookRequest"]["Fare"]["TransactionFee"] = $data['details'][$m]->booking_details->rest->Fare->TransactionFee;
                $book["Book"]["bookRequest"]["Fare"]["ReverseHandlingCharge"] = $data['details'][$m]->booking_details->rest->Fare->ReverseHandlingCharge;
                $book["Book"]["bookRequest"]["Fare"]["OfferedFare"] = $data['details'][$m]->booking_details->rest->Fare->OfferedFare;
                $book["Book"]["bookRequest"]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->booking_details->rest->Fare->AgentServiceCharge;
                $book["Book"]["bookRequest"]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->booking_details->rest->Fare->AgentConvienceCharges;
                /*---lead traveller---*/
                $i = 0;
                if ($data['details'][$m]->adult_count_field >= 1) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_lead'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_lead'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_lead'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_a'][$j]));
                    if (is_array($data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare)) {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->BaseFare / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->Tax / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->AdditionalTxnFee / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->FuelSurcharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentServiceCharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentConvienceCharges / $data['details'][$m]->adult_count_field;
                    }
                    else {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->BaseFare / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->Tax / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->FuelSurcharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->AgentServiceCharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges / $data['details'][$m]->adult_count_field;
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details'][$m]->booking_details->rest->Fare->ServiceTax / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details'][$m]->booking_details->rest->Fare->AgentCommission / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnCommission / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details'][$m]->booking_details->rest->Fare->IncentiveEarned / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnIncentive / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details'][$m]->booking_details->rest->Fare->PLBEarned / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnPLB / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details'][$m]->booking_details->rest->Fare->PublishedPrice / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details'][$m]->booking_details->rest->Fare->AirTransFee / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details'][$m]->booking_details->rest->Fare->Discount / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details'][$m]->booking_details->rest->Fare->OtherCharges / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details'][$m]->booking_details->rest->Fare->TransactionFee / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details'][$m]->booking_details->rest->Fare->ReverseHandlingCharge / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details'][$m]->booking_details->rest->Fare->OfferedFare / $data['details'][$m]->adult_count_field;
                    if ($data['title_lead'] == 'Mr' || $data['title_lead'] == 'Master') {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                    }
                    else {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                    }

                    if( $_SESSION['IsDomestic'] == 0 ){
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = $data['pass_number'];
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = $data['pass_expiry'];
                    }else{
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                    }
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['mobile'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = $data['addressPickup'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['email_id'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                }

                /*------*/
                $i = 1;
                while ($i < $data['details'][$m]->adult_count_field) {
                    $j = $i - 1;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_a'][$j];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_a'][$j];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_a'][$j];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_a'][$j]));

                    // print_r($data['details'][$m]->FareBreakdown->WSPTCFare->BaseFare/$data['details'][$m]->adult_count_field);die;

                    if (is_array($data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare)) {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->BaseFare / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->Tax / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->AdditionalTxnFee / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->FuelSurcharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentServiceCharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentConvienceCharges / $data['details'][$m]->adult_count_field;
                    }
                    else {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->BaseFare / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->Tax / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->FuelSurcharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->AgentServiceCharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges / $data['details'][$m]->adult_count_field;
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details'][$m]->booking_details->rest->Fare->ServiceTax / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details'][$m]->booking_details->rest->Fare->AgentCommission / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnCommission / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details'][$m]->booking_details->rest->Fare->IncentiveEarned / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnIncentive / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details'][$m]->booking_details->rest->Fare->PLBEarned / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnPLB / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details'][$m]->booking_details->rest->Fare->PublishedPrice / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details'][$m]->booking_details->rest->Fare->AirTransFee / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details'][$m]->booking_details->rest->Fare->Discount / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details'][$m]->booking_details->rest->Fare->OtherCharges / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details'][$m]->booking_details->rest->Fare->TransactionFee / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details'][$m]->booking_details->rest->Fare->ReverseHandlingCharge / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details'][$m]->booking_details->rest->Fare->OfferedFare / $data['details'][$m]->adult_count_field;
                    if ($data['title_a'][$j] == 'Mr' || $data['title_a'][$j] == 'Master') {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                    }
                    else {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                    $i++;
                }

                /*....*/
                $i = 0;
                while ($i < $data['details'][$m]->youth_count_field) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_k'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_k'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_k'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_k'][$j]));
                    if (count($data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare) > 1 && $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Child") {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->BaseFare / $data['details'][$m]->youth_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->Tax / $data['details'][$m]->youth_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->AdditionalTxnFee / $data['details'][$m]->youth_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->FuelSurcharge / $data['details'][$m]->youth_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->AgentServiceCharge / $data['details'][$m]->youth_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->AgentConvienceCharges / $data['details'][$m]->youth_count_field;
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details'][$m]->booking_details->rest->Fare->ServiceTax / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details'][$m]->booking_details->rest->Fare->AgentCommission / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnCommission / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details'][$m]->booking_details->rest->Fare->IncentiveEarned / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnIncentive / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details'][$m]->booking_details->rest->Fare->PLBEarned / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnPLB / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details'][$m]->booking_details->rest->Fare->PublishedPrice / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details'][$m]->booking_details->rest->Fare->AirTransFee / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details'][$m]->booking_details->rest->Fare->Discount / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details'][$m]->booking_details->rest->Fare->OtherCharges / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details'][$m]->booking_details->rest->Fare->TransactionFee / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details'][$m]->booking_details->rest->Fare->ReverseHandlingCharge / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details'][$m]->booking_details->rest->Fare->OfferedFare / $data['details'][$m]->youth_count_field;
                    if ($data['title_k'][$i] == 'Mr' || $data['title_k'][$i] == 'Master') {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                    }
                    else {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                    $i++;
                }

                /*--*/
                $i = 0;
                while ($i < $data['details'][$m]->kids_count_field) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_i'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_i'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_i'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_i'][$j]));
                    if (count($data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare) > 2 && $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[2]->PassengerType == "Infant") {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[2]->BaseFare / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[2]->Tax / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[2]->AdditionalTxnFee / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[2]->FuelSurcharge / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[2]->AgentServiceCharge / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[2]->AgentConvienceCharges / $data['details'][$m]->kids_count_field;
                    }

                    if (count($data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare) == 2 && $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Infant") {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->BaseFare / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->Tax / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->AdditionalTxnFee / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->FuelSurcharge / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->AgentServiceCharge / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->booking_details->rest->FareBreakdown->WSPTCFare[1]->AgentConvienceCharges / $data['details'][$m]->kids_count_field;
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details'][$m]->booking_details->rest->Fare->ServiceTax / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details'][$m]->booking_details->rest->Fare->AgentCommission / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnCommission / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details'][$m]->booking_details->rest->Fare->IncentiveEarned / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnIncentive / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details'][$m]->booking_details->rest->Fare->PLBEarned / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details'][$m]->booking_details->rest->Fare->TdsOnPLB / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details'][$m]->booking_details->rest->Fare->PublishedPrice / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details'][$m]->booking_details->rest->Fare->AirTransFee / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details'][$m]->booking_details->rest->Fare->Discount / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details'][$m]->booking_details->rest->Fare->OtherCharges / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details'][$m]->booking_details->rest->Fare->TransactionFee / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details'][$m]->booking_details->rest->Fare->ReverseHandlingCharge / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details'][$m]->booking_details->rest->Fare->OfferedFare / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                    $i++;
                }

                /*....*/
                $book["Book"]["bookRequest"]["Origin"] = $data['details'][$m]->booking_details->rest->Origin;
                $book["Book"]["bookRequest"]["Destination"] = $data['details'][$m]->booking_details->rest->Destination;
                /*....*/
                if (!is_array($data['details'][$m]->booking_details->rest->Segment->WSSegment)) {
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["SegmentIndicator"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->SegmentIndicator;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Airline->AirlineCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Airline->AirlineName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["FlightNumber"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->FlightNumber;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["FareClass"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->FareClass;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->AirportCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->AirportName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["Terminal"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->Terminal;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->CityCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->CityName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->CountryCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->CountryName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->AirportCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->AirportName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["Terminal"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->Terminal;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->CityCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->CityName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->CountryCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->CountryName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["DepTIme"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->DepTIme;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["ArrTime"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->ArrTime;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["ETicketEligible"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->ArrTime;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Duration"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Duration;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Stop"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Stop;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Craft"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Craft;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Status"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Status;
                }
                else {
                    for ($t = 0; $t < count($data['details'][$m]->booking_details->rest->Segment->WSSegment); $t++) {
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["SegmentIndicator"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->SegmentIndicator;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Airline"]["AirlineCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Airline->AirlineCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Airline"]["AirlineName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Airline->AirlineName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["FlightNumber"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->FlightNumber;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["FareClass"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->FareClass;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["AirportCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Origin->AirportCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["AirportName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Origin->AirportName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["Terminal"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Origin->Terminal;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CityCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Origin->CityCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CityName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Origin->CityName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CountryCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Origin->CountryCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CountryName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Origin->CountryName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["AirportCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Destination->AirportCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["AirportName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Destination->AirportName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["Terminal"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Destination->Terminal;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CityCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Destination->CityCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CityName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Destination->CityName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CountryCode"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Destination->CountryCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CountryName"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Destination->CountryName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["DepTIme"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->DepTIme;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["ArrTime"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->ArrTime;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["ETicketEligible"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->ArrTime;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Duration"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Duration;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Stop"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Stop;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Craft"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Craft;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Status"] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$t]->Status;
                    }
                }

                $book["Book"]["bookRequest"]["FareType"] = "PUB";
                /*----352a001f-5214-4067-a67f-b9be1e2f92fc*/
                if (!is_array($data['details'][$m]->booking_details->rest->FareRule->WSFareRule)) {
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Origin"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule->Origin;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Destination"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule->Destination;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Airline"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule->Airline;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["FareRestriction"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule->FareRestriction;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["FareBasisCode"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule->FareBasisCode;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["DepartureDate"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule->DepartureDate;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["ReturnDate"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule->ReturnDate;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Source"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule->Source;
                    $book["Book"]["bookRequest"]["Source"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule->Source;
                }
                else {
                    for ($t = 0; $t < count($data['details'][$m]->booking_details->rest->FareRule->WSFareRule); $t++) {
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Origin"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule[$t]->Origin;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Destination"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule[$t]->Destination;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Airline"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule[$t]->Airline;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["FareRestriction"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule[$t]->FareRestriction;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["FareBasisCode"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule[$t]->FareBasisCode;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["DepartureDate"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule[$t]->DepartureDate;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["ReturnDate"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule[$t]->ReturnDate;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Source"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule[$t]->Source;
                        $book["Book"]["bookRequest"]["Source"] = $data['details'][$m]->booking_details->rest->FareRule->WSFareRule[$t]->Source;
                    }
                }

                $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentInformationId"] = 0;
                $book["Book"]["bookRequest"]["PaymentInformation"]["InvoiceNumber"] = 0;
                $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentId"] = 0;
                $book["Book"]["bookRequest"]["PaymentInformation"]["Amount"] = 14024;
                $book["Book"]["bookRequest"]["PaymentInformation"]["IPAddress"] = "";
                $book["Book"]["bookRequest"]["PaymentInformation"]["TrackId"] = 0;
                $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentGateway"] = "APICustomer";
                $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentModeType"] = "Deposited";
                $book["Book"]["bookRequest"]["SessionId"] = $_SESSION['sess_id'][$m];
                $book["Book"]["bookRequest"]["PromotionalPlanType"] = "Normal";

                $header = array();
                $header['se'] = (array)$client->__call('Book', $book);

                $this->load->model('flight_model');
                if ($header['se']['BookResult']->Status->Description == "Successful" || $header['se']['BookResult']->Status->Description == "Fare is not available at the time of booking") {
                    $data[$m]['status'] = $header['se']['BookResult']->Status->Description;
                    $data[$m]['pnr'] = $header['se']['BookResult']->PNR;
                    $data[$m]['booking_id'] = $header['se']['BookResult']->BookingId;

                    // save to database after matching variables

                    $setData['is_multi_way'] = '1';
                    if (isset($_SESSION['cnt_val'])) {
                        $setData['num_of_city'] = $_SESSION['cnt_val'];
                    }
                    else {
                        $setData['num_of_city'] = '';
                    }

                    $setData['total_fare'] = $data['details'][$m]->total_fare_field;
                    $setData['pnr'] = $data[$m]['pnr'];
                    if (is_array($data['details'][$m]->booking_details->rest->Segment->WSSegment)) {
                        $max = count($data['details'][$m]->booking_details->rest->Segment->WSSegment);
                        $setData['source'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[0]->Origin->CityName;
                        $setData['destination'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$max - 1]->Destination->CityName;
                        $setData['src_airport_name'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[0]->Origin->AirportName;
                        $setData['dest_airport_name'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$max - 1]->Destination->AirportName;
                    }
                    else {
                        $setData['source'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->CityName;
                        $setData['destination'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->CityName;
                        $setData['src_airport_name'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->AirportName;
                        $setData['dest_airport_name'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->AirportName;
                    }

                    $setData['airline_name'] = $data['details'][$m]->airline_name_field;
                    $setData['date'] = $data['details'][$m]->travel_date;
                    $setData['arrival_time'] = $data['details'][$m]->to_field;
                    $setData['departure_time'] = $data['details'][$m]->from_field;
                    $setData['flight_duration'] = $data['details'][$m]->flight_duration_field;
                    $setData['status'] = $data[$m]['status'];
                    $setData['lead_traveller_title'] = $data['title_lead'];
                    $setData['lead_traveller_first_name'] = $data['first_name_lead'];
                    $setData['lead_traveller_last_name'] = $data['last_name_lead'];
                    $setData['lead_traveller_email'] = $data['email_id'];
                    $setData['lead_traveller_mobile'] = $data['mobile'];
                    $setData['adult_travellers_titles'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_title_csv'] : '';
                    $setData['adult_travellers_first_names'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_first_name_csv'] : '';
                    $setData['adult_travellers_last_names'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_last_name_csv'] : '';
                    $setData['child_travellers_titles'] = ($data['details'][$m]->youth_count_field > 1) ? $data['kid_title_csv'] : '';
                    $setData['child_travellers_first_names'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_first_name_csv'] : '';
                    $setData['child_travellers_last_names'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_last_name_csv'] : '';
                    $setData['child_travellers_dobs'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_dob_csv'] : '';
                    $setData['infant_travellers_titles'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_title_csv'] : '';
                    $setData['infant_travellers_first_names'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_first_name_csv'] : '';
                    $setData['infant_travellers_last_names'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_last_name_csv'] : '';
                    $setData['infant_travellers_dobs'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_dob_csv'] : '';
                    if ($_SESSION['user_details']) $setData['user_id'] = $_SESSION['user_details'][0]->user_id;
                    else $setData['user_id'] = $this->session->userdata('guest_id');
                    $setData['num_of_adults'] = $data['details'][$m]->adult_count_field;
                    $setData['num_of_children'] = $data['details'][$m]->youth_count_field;
                    $setData['num_of_infants'] = $data['details'][$m]->kids_count_field;
                    $setData['booking_id'] = $data[$m]['booking_id'];
                    $setData['hash_val'] = $hash_val;
                    $setData['session_id'] = $_SESSION['sess_id'][$m];
                    $setData['extra_info'] = json_encode(($data['details'][$m]));
                    $retId[] = $this->flight_model->postTicket($setData);
                }
            } else if ($data['details'][$m]->flight_type->type == 1) {
                $data[$m]['status'] = "Pending";
                $data[$m]['pnr'] = "0";
                $data[$m]['booking_id'] = "0";
                $setData['is_multi_way'] = '1';
                if (isset($_SESSION['cnt_val'])) {
                    $setData['num_of_city'] = $_SESSION['cnt_val'];
                }
                else {
                    $setData['num_of_city'] = '';
                }

                $setData['total_fare'] = $data['details'][$m]->total_fare_field;
                $setData['pnr'] = $data[$m]['pnr'];
                if (is_array($data['details'][$m]->booking_details->rest->Segment->WSSegment)) {
                    $len = count($data['details'][$m]->booking_details->rest->Segment->WSSegment);
                    $setData['source'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[0]->Origin->CityName;
                    $setData['destination'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$len - 1]->Destination->CityName;
                    $setData['src_airport_name'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[0]->Origin->AirportName;
                    $setData['dest_airport_name'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment[$len - 1]->Destination->AirportName;
                }
                else {
                    $setData['source'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->CityName;
                    $setData['destination'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->CityName;
                    $setData['src_airport_name'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Origin->AirportName;
                    $setData['dest_airport_name'] = $data['details'][$m]->booking_details->rest->Segment->WSSegment->Destination->AirportName;
                }

                $setData['airline_name'] = $data['details'][$m]->airline_name_field;
                $setData['date'] = $data['details'][$m]->travel_date;
                $setData['arrival_time'] = $data['details'][$m]->to_field;
                $setData['departure_time'] = $data['details'][$m]->from_field;
                $setData['flight_duration'] = $data['details'][$m]->flight_duration_field;
                $setData['status'] = $data[$m]['status'];
                $setData['lead_traveller_title'] = $data['title_lead'];
                $setData['lead_traveller_first_name'] = $data['first_name_lead'];
                $setData['lead_traveller_last_name'] = $data['last_name_lead'];
                $setData['lead_traveller_email'] = $data['email_id'];
                $setData['lead_traveller_mobile'] = $data['mobile'];
                $setData['adult_travellers_titles'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_title_csv'] : '';
                $setData['adult_travellers_first_names'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_first_name_csv'] : '';
                $setData['adult_travellers_last_names'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_last_name_csv'] : '';
                $setData['child_travellers_titles'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_title_csv'] : '';
                $setData['child_travellers_first_names'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_first_name_csv'] : '';
                $setData['child_travellers_last_names'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_last_name_csv'] : '';
                $setData['child_travellers_dobs'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_dob_csv'] : '';
                $setData['infant_travellers_titles'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_title_csv'] : '';
                $setData['infant_travellers_first_names'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_first_name_csv'] : '';
                $setData['infant_travellers_last_names'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_last_name_csv'] : '';
                $setData['infant_travellers_dobs'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_dob_csv'] : '';
                if ($_SESSION['user_details']) $setData['user_id'] = $_SESSION['user_details'][0]->user_id;
                else $setData['user_id'] = $_SESSION['guest_id'];
                $setData['num_of_adults'] = $data['details'][$m]->adult_count_field;
                $setData['num_of_children'] = $data['details'][$m]->youth_count_field;
                $setData['num_of_infants'] = $data['details'][$m]->kids_count_field;
                $setData['booking_id'] = $data[$m]['booking_id'];
                $setData['hash_val'] = $hash_val;
                $setData['session_id'] = $_SESSION['sess_id'][$m];
                $setData['extra_info'] = json_encode(($data['details'][$m]));
                $this->load->model('flight_model');
                $retId[] = $this->flight_model->postTicket($setData);
            }
        if ($booking_flag > 0) {
            redirect('api/flights/booking_failed');
        }
        $_SESSION['retId'] = $retId;
        redirect('common/store_block_id?retId=true&call_func=flight');
    }

    function book_multi()
    {
        $retId = array();
        $booking_flag = 0;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $hash_val = '';
        for ($i = 0; $i < 10; $i++) {
            $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
        }

        $hash_val = md5($hash_val);
        $i = 0;
        $data['details'][$i] = new StdClass;
        $data = $this->input->post(null, true);
        $data['details'] = $_SESSION['details'];

        foreach($_SESSION['flight_data'] as $fl => $fl_val) {
            $data['details'][$fl] = json_decode($fl_val['booking_details']);
            $data['details'][$fl]->airline_name_field = $fl_val['airline_name_field'];
            $data['details'][$fl]->from_field = $fl_val['from_field'];
            $data['details'][$fl]->to_field = $fl_val['to_field'];
            $data['details'][$fl]->flight_duration_field = $fl_val['flight_duration_field'];
            $data['details'][$fl]->total_fare_field = $fl_val['total_fare_field'];
            $data['details'][$fl]->adult_count_field = $fl_val['adult_count_field'];
            $data['details'][$fl]->youth_count_field = $fl_val['youth_count_field'];
            $data['details'][$fl]->kids_count_field = $fl_val['kids_count_field'];
            $data['details'][$fl]->total_count_field = $fl_val['total_count_field'];
            $data['details'][$fl]->travel_date = $fl_val['travel_date'];
            $data['details'][$fl]->flight_type = json_decode($fl_val['flight_type']);
            $data['details'][$fl]->session_id = $_SESSION['sess_id'][$fl];
        }

        for ($m = 0; $m < count($_SESSION['flight_data']); $m++) {
            if ($data['details'][$m]->adult_count_field > 0) {
                $i = 1;
                $data['lead_adult_title'] = $data['title_lead'];
                $data['lead_adult_first_name'] = $data['first_name_lead'];
                $data['lead_adult_last_name'] = $data['last_name_lead'];
                $data['lead_adult_email_id'] = $data['email_id_lead'];
                $data['lead_adult_mobile_no'] = $data['mobile_no_lead'];
            }

            if (isset($data['details'][$m]->adult_count_field) && $data['details'][$m]->adult_count_field > 1) {
                $data['adult_title_csv'] = "";
                $data['adult_first_name_csv'] = "";
                $data['adult_last_name_csv'] = "";
                for ($i = 1; $i < $data['details'][$m]->adult_count_field; $i++) {
                    $j = $i - 1;
                    if ($data['adult_title_csv'] == "") {
                        $data['adult_title_csv'] = $data['title_a'][$j];
                        $data['adult_first_name_csv'] = $data['first_name_a'][$j];
                        $data['adult_last_name_csv'] = $data['last_name_a'][$j];
                    }
                    else {
                        $data['adult_title_csv'] = $data['adult_title_csv'] . ',' . $data['title_a'][$j];
                        $data['adult_first_name_csv'] = $data['adult_first_name_csv'] . ',' . $data['first_name_a'][$j];
                        $data['adult_last_name_csv'] = $data['adult_last_name_csv'] . ',' . $data['last_name_a'][$j];
                    }
                }
            }

            if (isset($data['details'][$m]->youth_count_field) && $data['details'][$m]->youth_count_field > 0) {
                $data['kid_title_csv'] = "";
                $data['kid_first_name_csv'] = "";
                $data['kid_last_name_csv'] = "";
                $data['kid_dob_csv'] = "";
                for ($i = 0; $i < $data['details'][$m]->youth_count_field; $i++) {
                    if ($data['kid_title_csv'] == "") {
                        $data['kid_title_csv'] = $data['title_k'][$i];
                        $data['kid_first_name_csv'] = $data['first_name_k'][$i];
                        $data['kid_last_name_csv'] = $data['last_name_k'][$i];
                        $data['kid_dob_csv'] = $data['dob_k'][$i];
                    }
                    else {
                        $data['kid_title_csv'] = $data['kid_title_csv'] . ',' . $data['title_k'][$i];
                        $data['kid_first_name_csv'] = $data['kid_first_name_csv'] . ',' . $data['first_name_k'][$i];
                        $data['kid_last_name_csv'] = $data['kid_last_name_csv'] . ',' . $data['last_name_k'][$i];
                        $data['kid_dob_csv'] = $data['kid_dob_csv'] . ',' . $data['dob_k'][$i];
                    }
                }
            }

            if (isset($data['details'][$m]->kids_count_field) && $data['details'][$m]->kids_count_field > 0) {
                $data['infant_title_csv'] = "";
                $data['infant_first_name_csv'] = "";
                $data['infant_last_name_csv'] = "";
                $data['infant_dob_csv'] = "";
                for ($i = 0; $i < $data['details'][$m]->kids_count_field; $i++) {
                    if ($data['infant_title_csv'] == "") {
                        $data['infant_title_csv'] = $data['title_i'][$i];
                        $data['infant_first_name_csv'] = $data['first_name_i'][$i];
                        $data['infant_last_name_csv'] = $data['last_name_i'][$i];
                        $data['infant_dob_csv'] = $data['dob_i'][$i];
                    }
                    else {
                        $data['infant_title_csv'] = $data['infant_title_csv'] . ',' . $data['title_i'][$i];
                        $data['infant_first_name_csv'] = $data['infant_first_name_csv'] . ',' . $data['first_name_i'][$i];
                        $data['infant_last_name_csv'] = $data['infant_last_name_csv'] . ',' . $data['last_name_i'][$i];
                        $data['infant_dob_csv'] = $data['infant_dob_csv'] . ',' . $data['dob_i'][$i];
                    }
                }
            }

            if ($data['details'][$m]->flight_type->type == 0) {
                require_once (APPPATH . 'lib/nusoap.php');

                $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
                $headerpara = array();
                $headerpara["UserName"] = 'redytrip';
                $headerpara["Password"] = 'redytrip@12';
                $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
                $client = new SoapClient($wsdl);
                $client->__setSoapHeaders(array(
                    $client_header
                ));
                $book = array();
                $book["Book"]["bookRequest"]["Remarks"] = "FlightBook";
                $book["Book"]["bookRequest"]["InstantTicket"] = True;
                $book["Book"]["bookRequest"]["Fare"]["BaseFare"] = $data['details'][$m]->rest->Fare->BaseFare;
                $book["Book"]["bookRequest"]["Fare"]["Tax"] = $data['details'][$m]->rest->Fare->Tax;
                $book["Book"]["bookRequest"]["Fare"]["ServiceTax"] = $data['details'][$m]->rest->Fare->ServiceTax;
                $book["Book"]["bookRequest"]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->rest->Fare->AdditionalTxnFee;
                $book["Book"]["bookRequest"]["Fare"]["AgentCommission"] = $data['details'][$m]->rest->Fare->AgentCommission;
                $book["Book"]["bookRequest"]["Fare"]["TdsOnCommission"] = $data['details'][$m]->rest->Fare->TdsOnCommission;
                $book["Book"]["bookRequest"]["Fare"]["IncentiveEarned"] = $data['details'][$m]->rest->Fare->IncentiveEarned;
                $book["Book"]["bookRequest"]["Fare"]["TdsOnIncentive"] = $data['details'][$m]->rest->Fare->TdsOnIncentive;
                $book["Book"]["bookRequest"]["Fare"]["PLBEarned"] = $data['details'][$m]->rest->Fare->PLBEarned;
                $book["Book"]["bookRequest"]["Fare"]["TdsOnPLB"] = $data['details'][$m]->rest->Fare->TdsOnPLB;
                $book["Book"]["bookRequest"]["Fare"]["PublishedPrice"] = $data['details'][$m]->rest->Fare->PublishedPrice;
                $book["Book"]["bookRequest"]["Fare"]["AirTransFee"] = $data['details'][$m]->rest->Fare->AirTransFee;
                $book["Book"]["bookRequest"]["Fare"]["Currency"] = $data['details'][$m]->rest->Fare->Currency;
                $book["Book"]["bookRequest"]["Fare"]["Discount"] = $data['details'][$m]->rest->Fare->Discount;
                $book["Book"]["bookRequest"]["Fare"]["OtherCharges"] = $data['details'][$m]->rest->Fare->OtherCharges;
                $book["Book"]["bookRequest"]["Fare"]["FuelSurcharge"] = $data['details'][$m]->rest->Fare->FuelSurcharge;
                $book["Book"]["bookRequest"]["Fare"]["TransactionFee"] = $data['details'][$m]->rest->Fare->TransactionFee;
                $book["Book"]["bookRequest"]["Fare"]["ReverseHandlingCharge"] = $data['details'][$m]->rest->Fare->ReverseHandlingCharge;
                $book["Book"]["bookRequest"]["Fare"]["OfferedFare"] = $data['details'][$m]->rest->Fare->OfferedFare;
                $book["Book"]["bookRequest"]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->rest->Fare->AgentServiceCharge;
                $book["Book"]["bookRequest"]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->rest->Fare->AgentConvienceCharges;
                /*---lead traveller---*/
                $i = 0;
                if ($data['details'][$m]->adult_count_field >= 1) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_lead'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_lead'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_lead'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1-1-1990'));
                    if (is_array($data['details'][$m]->rest->FareBreakdown->WSPTCFare)) {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->BaseFare / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->Tax / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->AdditionalTxnFee / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->FuelSurcharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->AgentServiceCharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->AgentConvienceCharges / $data['details'][$m]->adult_count_field;
                    }
                    else {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->BaseFare / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->Tax / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->FuelSurcharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->AgentServiceCharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges / $data['details'][$m]->adult_count_field;
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details'][$m]->rest->Fare->ServiceTax / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details'][$m]->rest->Fare->AgentCommission / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details'][$m]->rest->Fare->TdsOnCommission / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details'][$m]->rest->Fare->IncentiveEarned / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details'][$m]->rest->Fare->TdsOnIncentive / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details'][$m]->rest->Fare->PLBEarned / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details'][$m]->rest->Fare->TdsOnPLB / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details'][$m]->rest->Fare->PublishedPrice / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details'][$m]->rest->Fare->AirTransFee / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details'][$m]->rest->Fare->Discount / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details'][$m]->rest->Fare->OtherCharges / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details'][$m]->rest->Fare->TransactionFee / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details'][$m]->rest->Fare->ReverseHandlingCharge / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details'][$m]->rest->Fare->OfferedFare / $data['details'][$m]->adult_count_field;
                    if ($data['title_lead'] == 'Mr' || $data['title_lead'] == 'Master') {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                    }
                    else {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                }

                /*------*/
                $i = 1;
                while ($i < $data['details'][$m]->adult_count_field) {
                    $j = $i - 1;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_a'][$j];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_a'][$j];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_a'][$j];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1-1-1990'));

                    // print_r($data['details'][$m]->FareBreakdown->WSPTCFare->BaseFare/$data['details'][$m]->adult_count_field);die;

                    if (is_array($data['details'][$m]->rest->FareBreakdown->WSPTCFare)) {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->BaseFare / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->Tax / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->AdditionalTxnFee / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->FuelSurcharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->AgentServiceCharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[0]->AgentConvienceCharges / $data['details'][$m]->adult_count_field;
                    }
                    else {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->BaseFare / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->Tax / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->FuelSurcharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->AgentServiceCharge / $data['details'][$m]->adult_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges / $data['details'][$m]->adult_count_field;
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details'][$m]->rest->Fare->ServiceTax / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details'][$m]->rest->Fare->AgentCommission / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details'][$m]->rest->Fare->TdsOnCommission / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details'][$m]->rest->Fare->IncentiveEarned / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details'][$m]->rest->Fare->TdsOnIncentive / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details'][$m]->rest->Fare->PLBEarned / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details'][$m]->rest->Fare->TdsOnPLB / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details'][$m]->rest->Fare->PublishedPrice / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details'][$m]->rest->Fare->AirTransFee / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details'][$m]->rest->Fare->Discount / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details'][$m]->rest->Fare->OtherCharges / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details'][$m]->rest->Fare->TransactionFee / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details'][$m]->rest->Fare->ReverseHandlingCharge / $data['details'][$m]->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details'][$m]->rest->Fare->OfferedFare / $data['details'][$m]->adult_count_field;
                    if ($data['title_a'][$j] == 'Mr' || $data['title_a'][$j] == 'Master') {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                    }
                    else {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                    $i++;
                }

                /*....*/
                $i = 0;
                while ($i < $data['details'][$m]->youth_count_field) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_k'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_k'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_k'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1-1-1990'));
                    if (count($data['details'][$m]->rest->FareBreakdown->WSPTCFare) > 1 && $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Child") {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->BaseFare / $data['details'][$m]->youth_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->Tax / $data['details'][$m]->youth_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->AdditionalTxnFee / $data['details'][$m]->youth_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->FuelSurcharge / $data['details'][$m]->youth_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->AgentServiceCharge / $data['details'][$m]->youth_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->AgentConvienceCharges / $data['details'][$m]->youth_count_field;
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details'][$m]->rest->Fare->ServiceTax / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details'][$m]->rest->Fare->AgentCommission / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details'][$m]->rest->Fare->TdsOnCommission / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details'][$m]->rest->Fare->IncentiveEarned / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details'][$m]->rest->Fare->TdsOnIncentive / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details'][$m]->rest->Fare->PLBEarned / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details'][$m]->rest->Fare->TdsOnPLB / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details'][$m]->rest->Fare->PublishedPrice / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details'][$m]->rest->Fare->AirTransFee / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details'][$m]->rest->Fare->Discount / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details'][$m]->rest->Fare->OtherCharges / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details'][$m]->rest->Fare->TransactionFee / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details'][$m]->rest->Fare->ReverseHandlingCharge / $data['details'][$m]->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details'][$m]->rest->Fare->OfferedFare / $data['details'][$m]->youth_count_field;
                    if ($data['title_k'][$i] == 'Mr' || $data['title_k'][$i] == 'Master') {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                    }
                    else {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                    $i++;
                }

                /*--*/
                $i = 0;
                while ($i < $data['details'][$m]->kids_count_field) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_i'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_i'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_i'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1-1-1990'));
                    if (count($data['details'][$m]->rest->FareBreakdown->WSPTCFare) > 2 && $data['details'][$m]->rest->FareBreakdown->WSPTCFare[2]->PassengerType == "Infant") {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[2]->BaseFare / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[2]->Tax / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[2]->AdditionalTxnFee / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[2]->FuelSurcharge / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[2]->AgentServiceCharge / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[2]->AgentConvienceCharges / $data['details'][$m]->kids_count_field;
                    }

                    if (count($data['details'][$m]->rest->FareBreakdown->WSPTCFare) == 2 && $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Infant") {
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->BaseFare / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->Tax / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->AdditionalTxnFee / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->FuelSurcharge / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->AgentServiceCharge / $data['details'][$m]->kids_count_field;
                        $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details'][$m]->rest->FareBreakdown->WSPTCFare[1]->AgentConvienceCharges / $data['details'][$m]->kids_count_field;
                    }

                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details'][$m]->rest->Fare->ServiceTax / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details'][$m]->rest->Fare->AgentCommission / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details'][$m]->rest->Fare->TdsOnCommission / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details'][$m]->rest->Fare->IncentiveEarned / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details'][$m]->rest->Fare->TdsOnIncentive / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details'][$m]->rest->Fare->PLBEarned / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details'][$m]->rest->Fare->TdsOnPLB / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details'][$m]->rest->Fare->PublishedPrice / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details'][$m]->rest->Fare->AirTransFee / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details'][$m]->rest->Fare->Discount / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details'][$m]->rest->Fare->OtherCharges / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details'][$m]->rest->Fare->TransactionFee / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details']->rest->Fare->ReverseHandlingCharge / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details'][$m]->rest->Fare->OfferedFare / $data['details'][$m]->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                    $i++;
                }

                /*....*/
                $book["Book"]["bookRequest"]["Origin"] = $data['details'][$m]->rest->Origin;
                $book["Book"]["bookRequest"]["Destination"] = $data['details'][$m]->rest->Destination;
                /*....*/
                if (!is_array($data['details'][$m]->rest->Segment->WSSegment)) {
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["SegmentIndicator"] = $data['details'][$m]->rest->Segment->WSSegment->SegmentIndicator;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineCode"] = $data['details'][$m]->rest->Segment->WSSegment->Airline->AirlineCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineName"] = $data['details'][$m]->rest->Segment->WSSegment->Airline->AirlineName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["FlightNumber"] = $data['details'][$m]->rest->Segment->WSSegment->FlightNumber;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["FareClass"] = $data['details'][$m]->rest->Segment->WSSegment->FareClass;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportCode"] = $data['details'][$m]->rest->Segment->WSSegment->Origin->AirportCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportName"] = $data['details'][$m]->rest->Segment->WSSegment->Origin->AirportName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["Terminal"] = $data['details'][$m]->rest->Segment->WSSegment->Origin->Terminal;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityCode"] = $data['details'][$m]->rest->Segment->WSSegment->Origin->CityCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityName"] = $data['details'][$m]->rest->Segment->WSSegment->Origin->CityName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryCode"] = $data['details'][$m]->rest->Segment->WSSegment->Origin->CountryCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryName"] = $data['details'][$m]->rest->Segment->WSSegment->Origin->CountryName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportCode"] = $data['details'][$m]->rest->Segment->WSSegment->Destination->AirportCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportName"] = $data['details'][$m]->rest->Segment->WSSegment->Destination->AirportName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["Terminal"] = $data['details'][$m]->rest->Segment->WSSegment->Destination->Terminal;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityCode"] = $data['details'][$m]->rest->Segment->WSSegment->Destination->CityCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityName"] = $data['details'][$m]->rest->Segment->WSSegment->Destination->CityName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryCode"] = $data['details'][$m]->rest->Segment->WSSegment->Destination->CountryCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryName"] = $data['details'][$m]->rest->Segment->WSSegment->Destination->CountryName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["DepTIme"] = $data['details'][$m]->rest->Segment->WSSegment->DepTIme;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["ArrTime"] = $data['details'][$m]->rest->Segment->WSSegment->ArrTime;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["ETicketEligible"] = $data['details'][$m]->rest->Segment->WSSegment->ArrTime;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Duration"] = $data['details'][$m]->rest->Segment->WSSegment->Duration;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Stop"] = $data['details'][$m]->rest->Segment->WSSegment->Stop;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Craft"] = $data['details'][$m]->rest->Segment->WSSegment->Craft;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Status"] = $data['details'][$m]->rest->Segment->WSSegment->Status;
                }
                else {
                    for ($t = 0; $t < count($data['details'][$m]->rest->Segment->WSSegment); $t++) {
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["SegmentIndicator"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->SegmentIndicator;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Airline"]["AirlineCode"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Airline->AirlineCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Airline"]["AirlineName"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Airline->AirlineName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["FlightNumber"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->FlightNumber;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["FareClass"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->FareClass;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["AirportCode"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Origin->AirportCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["AirportName"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Origin->AirportName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["Terminal"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Origin->Terminal;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CityCode"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Origin->CityCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CityName"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Origin->CityName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CountryCode"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Origin->CountryCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CountryName"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Origin->CountryName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["AirportCode"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Destination->AirportCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["AirportName"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Destination->AirportName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["Terminal"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Destination->Terminal;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CityCode"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Destination->CityCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CityName"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Destination->CityName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CountryCode"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Destination->CountryCode;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CountryName"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Destination->CountryName;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["DepTIme"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->DepTIme;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["ArrTime"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->ArrTime;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["ETicketEligible"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->ArrTime;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Duration"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Duration;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Stop"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Stop;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Craft"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Craft;
                        $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Status"] = $data['details'][$m]->rest->Segment->WSSegment[$t]->Status;
                    }
                }

                $book["Book"]["bookRequest"]["FareType"] = "PUB";
                /*----352a001f-5214-4067-a67f-b9be1e2f92fc*/
                if (!is_array($data['details'][$m]->rest->FareRule->WSFareRule)) {
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Origin"] = $data['details'][$m]->rest->FareRule->WSFareRule->Origin;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Destination"] = $data['details'][$m]->rest->FareRule->WSFareRule->Destination;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Airline"] = $data['details'][$m]->rest->FareRule->WSFareRule->Airline;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["FareRestriction"] = $data['details'][$m]->rest->FareRule->WSFareRule->FareRestriction;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["FareBasisCode"] = $data['details'][$m]->rest->FareRule->WSFareRule->FareBasisCode;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["DepartureDate"] = $data['details'][$m]->rest->FareRule->WSFareRule->DepartureDate;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["ReturnDate"] = $data['details'][$m]->rest->FareRule->WSFareRule->ReturnDate;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Source"] = $data['details'][$m]->rest->FareRule->WSFareRule->Source;
                    $book["Book"]["bookRequest"]["Source"] = $data['details'][$m]->rest->FareRule->WSFareRule->Source;
                }
                else {
                    for ($t = 0; $t < count($data['details'][$m]->rest->FareRule->WSFareRule); $t++) {
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Origin"] = $data['details'][$m]->rest->FareRule->WSFareRule[$t]->Origin;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Destination"] = $data['details'][$m]->rest->FareRule->WSFareRule[$t]->Destination;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Airline"] = $data['details'][$m]->rest->FareRule->WSFareRule[$t]->Airline;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["FareRestriction"] = $data['details'][$m]->rest->FareRule->WSFareRule[$t]->FareRestriction;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["FareBasisCode"] = $data['details'][$m]->rest->FareRule->WSFareRule[$t]->FareBasisCode;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["DepartureDate"] = $data['details'][$m]->rest->FareRule->WSFareRule[$t]->DepartureDate;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["ReturnDate"] = $data['details'][$m]->rest->FareRule->WSFareRule[$t]->ReturnDate;
                        $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Source"] = $data['details'][$m]->rest->FareRule->WSFareRule[$t]->Source;
                        $book["Book"]["bookRequest"]["Source"] = $data['details'][$m]->rest->FareRule->WSFareRule[$t]->Source;
                    }
                }

                $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentInformationId"] = 0;
                $book["Book"]["bookRequest"]["PaymentInformation"]["InvoiceNumber"] = 0;
                $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentId"] = 0;
                $book["Book"]["bookRequest"]["PaymentInformation"]["Amount"] = 14024;
                $book["Book"]["bookRequest"]["PaymentInformation"]["IPAddress"] = "";
                $book["Book"]["bookRequest"]["PaymentInformation"]["TrackId"] = 0;
                $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentGateway"] = "APICustomer";
                $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentModeType"] = "Deposited";
                $book["Book"]["bookRequest"]["SessionId"] = $_SESSION['sess_id'][$m];
                $book["Book"]["bookRequest"]["PromotionalPlanType"] = "Normal";
                $header = array();
                $header['se'] = (array)$client->__call('Book', $book);
                $this->load->model('flight_model');
                if ($header['se']['BookResult']->Status->Description == "Successful" || $header['se']['BookResult']->Status->Description == "Fare is not available at the time of booking") {
                    $data[$m]['status'] = $header['se']['BookResult']->Status->Description;
                    $data[$m]['pnr'] = $header['se']['BookResult']->PNR;
                    $data[$m]['booking_id'] = $header['se']['BookResult']->BookingId;

                    // save to database after matching variables

                    $setData['is_multi_way'] = '1';
                    if (isset($_SESSION['cnt_val'])) {
                        $setData['num_of_city'] = $_SESSION['cnt_val'];
                    }
                    else {
                        $setData['num_of_city'] = '';
                    }

                    $setData['total_fare'] = $data['details'][$m]->total_fare_field;
                    $setData['pnr'] = $data[$m]['pnr'];
                    if (is_array($data['details'][$m]->rest->Segment->WSSegment)) {
                        $max = count($data['details'][$m]->rest->Segment->WSSegment);
                        $setData['source'] = $data['details'][$m]->rest->Segment->WSSegment[0]->Origin->CityName;
                        $setData['destination'] = $data['details'][$m]->rest->Segment->WSSegment[$max - 1]->Destination->CityName;
                        $setData['src_airport_name'] = $data['details'][$m]->rest->Segment->WSSegment[0]->Origin->AirportName;
                        $setData['dest_airport_name'] = $data['details'][$m]->rest->Segment->WSSegment[$max - 1]->Destination->AirportName;
                    }
                    else {
                        $setData['source'] = $data['details'][$m]->rest->Segment->WSSegment->Origin->CityName;
                        $setData['destination'] = $data['details'][$m]->rest->Segment->WSSegment->Destination->CityName;
                        $setData['src_airport_name'] = $data['details'][$m]->rest->Segment->WSSegment->Origin->AirportName;
                        $setData['dest_airport_name'] = $data['details'][$m]->rest->Segment->WSSegment->Destination->AirportName;
                    }

                    $setData['airline_name'] = $data['details'][$m]->airline_name_field;
                    $setData['date'] = $data['details'][$m]->travel_date;
                    $setData['arrival_time'] = $data['details'][$m]->to_field;
                    $setData['departure_time'] = $data['details'][$m]->from_field;
                    $setData['flight_duration'] = $data['details'][$m]->flight_duration_field;
                    $setData['status'] = $data[$m]['status'];
                    $setData['lead_traveller_title'] = $data['lead_adult_title'];
                    $setData['lead_traveller_first_name'] = $data['lead_adult_first_name'];
                    $setData['lead_traveller_last_name'] = $data['lead_adult_last_name'];
                    $setData['lead_traveller_email'] = $data['lead_adult_email_id'];
                    $setData['lead_traveller_mobile'] = $data['lead_adult_mobile_no'];
                    $setData['adult_travellers_titles'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_title_csv'] : '';
                    $setData['adult_travellers_first_names'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_first_name_csv'] : '';
                    $setData['adult_travellers_last_names'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_last_name_csv'] : '';
                    $setData['child_travellers_titles'] = ($data['details'][$m]->youth_count_field > 1) ? $data['kid_title_csv'] : '';
                    $setData['child_travellers_first_names'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_first_name_csv'] : '';
                    $setData['child_travellers_last_names'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_last_name_csv'] : '';
                    $setData['child_travellers_dobs'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_dob_csv'] : '';
                    $setData['infant_travellers_titles'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_title_csv'] : '';
                    $setData['infant_travellers_first_names'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_first_name_csv'] : '';
                    $setData['infant_travellers_last_names'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_last_name_csv'] : '';
                    $setData['infant_travellers_dobs'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_dob_csv'] : '';
                    if ($_SESSION['user_details']) $setData['user_id'] = $_SESSION['user_details'][0]->user_id;
                    else $setData['user_id'] = $this->session->userdata('guest_id');
                    $setData['num_of_adults'] = $data['details'][$m]->adult_count_field;
                    $setData['num_of_children'] = $data['details'][$m]->youth_count_field;
                    $setData['num_of_infants'] = $data['details'][$m]->kids_count_field;
                    $setData['booking_id'] = $data[$m]['booking_id'];
                    $setData['hash_val'] = $hash_val;
                    $setData['session_id'] = $data['details'][$m]->session_id;
                    $setData['extra_info'] = json_encode(($data['details'][$m]));
                    $retId[$m] = $this->flight_model->postTicket($setData);
                }
            }
            else {
                $booking_flag++;
            }

            if ($data['details'][$m]->flight_type->type == 1) {
                $data[$m]['status'] = "Pending";
                $data[$m]['pnr'] = "0";
                $data[$m]['booking_id'] = "0";
                $setData['is_multi_way'] = '1';
                if (isset($_SESSION['cnt_val'])) {
                    $setData['num_of_city'] = $_SESSION['cnt_val'];
                }
                else {
                    $setData['num_of_city'] = '';
                }

                $setData['total_fare'] = $data['details'][$m]->total_fare_field;
                $setData['pnr'] = $data[$m]['pnr'];
                if (is_array($data['details'][$m]->rest->Segment->WSSegment)) {
                    $len = count($data['details'][$m]->rest->Segment->WSSegment);
                    $setData['source'] = $data['details'][$m]->rest->Segment->WSSegment[0]->Origin->CityName;
                    $setData['destination'] = $data['details'][$m]->rest->Segment->WSSegment[$len - 1]->Destination->CityName;
                    $setData['src_airport_name'] = $data['details'][$m]->rest->Segment->WSSegment[0]->Origin->AirportName;
                    $setData['dest_airport_name'] = $data['details'][$m]->rest->Segment->WSSegment[$len - 1]->Destination->AirportName;
                }
                else {
                    $setData['source'] = $data['details'][$m]->rest->Segment->WSSegment->Origin->CityName;
                    $setData['destination'] = $data['details'][$m]->rest->Segment->WSSegment->Destination->CityName;
                    $setData['src_airport_name'] = $data['details'][$m]->rest->Segment->WSSegment->Origin->AirportName;
                    $setData['dest_airport_name'] = $data['details'][$m]->rest->Segment->WSSegment->Destination->AirportName;
                }

                $setData['airline_name'] = $data['details'][$m]->airline_name_field;
                $setData['date'] = $data['details'][$m]->travel_date;
                $setData['arrival_time'] = $data['details'][$m]->to_field;
                $setData['departure_time'] = $data['details'][$m]->from_field;
                $setData['flight_duration'] = $data['details'][$m]->flight_duration_field;
                $setData['status'] = $data[$m]['status'];
                $setData['lead_traveller_title'] = $data['lead_adult_title'];
                $setData['lead_traveller_first_name'] = $data['lead_adult_first_name'];
                $setData['lead_traveller_last_name'] = $data['lead_adult_last_name'];
                $setData['lead_traveller_email'] = $data['lead_adult_email_id'];
                $setData['lead_traveller_mobile'] = $data['lead_adult_mobile_no'];
                $setData['adult_travellers_titles'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_title_csv'] : '';
                $setData['adult_travellers_first_names'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_first_name_csv'] : '';
                $setData['adult_travellers_last_names'] = ($data['details'][$m]->adult_count_field > 1) ? $data['adult_last_name_csv'] : '';
                $setData['child_travellers_titles'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_title_csv'] : '';
                $setData['child_travellers_first_names'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_first_name_csv'] : '';
                $setData['child_travellers_last_names'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_last_name_csv'] : '';
                $setData['child_travellers_dobs'] = ($data['details'][$m]->youth_count_field > 0) ? $data['kid_dob_csv'] : '';
                $setData['infant_travellers_titles'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_title_csv'] : '';
                $setData['infant_travellers_first_names'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_first_name_csv'] : '';
                $setData['infant_travellers_last_names'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_last_name_csv'] : '';
                $setData['infant_travellers_dobs'] = ($data['details'][$m]->kids_count_field > 0) ? $data['infant_dob_csv'] : '';
                if ($_SESSION['user_details']) $setData['user_id'] = $_SESSION['user_details'][0]->user_id;
                else $setData['user_id'] = $_SESSION['guest_id'];
                $setData['num_of_adults'] = $data['details'][$m]->adult_count_field;
                $setData['num_of_children'] = $data['details'][$m]->youth_count_field;
                $setData['num_of_infants'] = $data['details'][$m]->kids_count_field;
                $setData['booking_id'] = $data[$m]['booking_id'];
                $setData['hash_val'] = $hash_val;
                $setData['session_id'] = $data['details'][$m]->session_id;
                $setData['extra_info'] = json_encode(($data['details'][$m]));
                $this->load->model('flight_model');
                $retId[$m] = $this->flight_model->postTicket($setData);
            }
        }
        if ($booking_flag > 0) {
            redirect('api/flights/booking_failed');
        }
        $_SESSION['retId'] = $retId;
        $amount = 0;
        foreach($_SESSION['flight_data'] as $fd){
            $amount += floatval(str_replace( ',', '', $fd['total_fare_field']));
        }
        $_SESSION['multiway_postdata']['total_fare_multi'] = $amount;
        $_SESSION['multiway_postdata']['key'] = 'C0Dr8m';
        redirect('flights/payment_gateway');
    }

    function cancel_ticket_admin()
    {
        $ticketid = $this->input->get('ticket_id');
        $data['ticket_id'] = $ticketid;
        $this->load->model('flight_model');
        $result = $this->flight_model->cancelTicketById($ticketid);
        $sub_val = json_decode($result[0]->extra_info);
        $booking_id = $result[0]->booking_id;
        $source = $sub_val->rest->Source;
        require_once (APPPATH . 'lib/nusoap.php');

        $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
        $headerpara = array();
        $headerpara["UserName"] = 'redytrip';
        $headerpara["Password"] = 'redytrip@12';
        $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array(
            $client_header
        ));
        $cancel = array();
        $cancel['CancelItinerary']["cancelRequest"]["BookingId"] = $booking_id;
        $cancel['CancelItinerary']["cancelRequest"]["Source"] = $source;
        $header = array();
        $header['se'] = (array)$client->__call('CancelItinerary', $cancel);
        if ($header['se']['CancelItineraryResult']->Status->Description == "booking cancelled sucessfully") {
            $val = $this->flight_model->updateTicketStatus($ticketid);
        }
        redirect('admin/flight');
    }

    function guest_cancel_ticket()
    {
        $ticketid = $this->input->get('ticket_id');
        $data['ticket_id'] = $ticketid;
        $this->load->model('flight_model');
        $result = $this->flight_model->cancelTicketById($ticketid);
        $sub_val = json_decode(stripslashes($result[0]->extra_info));
        $booking_id = $result[0]->booking_id;
        $source = $sub_val->rest->Source;
        require_once (APPPATH . 'lib/nusoap.php');

        $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
        $headerpara = array();
        $headerpara["UserName"] = 'redytrip';
        $headerpara["Password"] = 'redytrip@12';
        $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array(
            $client_header
        ));
        print_r($booking_id);
        print_r($source);
        $cancel = array();
        $cancel['CancelItinerary']["cancelRequest"]["BookingId"] = $booking_id . "";
        $cancel['CancelItinerary']["cancelRequest"]["Source"] = $source;
        $header = array();
        $header['se'] = (array)$client->__call('CancelItinerary', $cancel);
        print_r($cancel);
        print_r($header);
        die;
        if ($header['se']['CancelItineraryResult']->Status->Description == "booking cancelled sucessfully") {
            $val = $this->flight_model->updateTicketStatus($ticketid);
        }
        $this->ticket_details($ticketid);
    }

    function cancel_ticket()
    {
        $ticketid = $this->input->post('ticket_id');
        $data['ticket_id'] = $ticketid;
        $this->load->model('flight_model');
        $result = $this->flight_model->cancelTicketById($ticketid);
        $sub_val = json_decode(stripslashes($result[0]->extra_info));
        $booking_id = $result[0]->booking_id;
        $source = $sub_val->rest->Source;
        require_once (APPPATH . 'lib/nusoap.php');

        $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
        $headerpara = array();
        $headerpara["UserName"] = 'redytrip';
        $headerpara["Password"] = 'redytrip@12';
        $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array(
            $client_header
        ));
        $cancel = array();
        $cancel['CancelItinerary']["cancelRequest"]["BookingId"] = $booking_id;
        $cancel['CancelItinerary']["cancelRequest"]["Source"] = $source;
        $header = array();
        $header['se'] = (array)$client->__call('CancelItinerary', $cancel);
        if ($header['se']['CancelItineraryResult']->Status->Description == "booking cancelled sucessfully") {
            $val = $this->flight_model->updateTicketStatus($ticketid);
        }
        echo json_encode($header['se']['CancelItineraryResult']->Status);
    }

    function ticket_assorted(){
        $data = $this->input->get(null, true);
        $c=0;
        $ticketid = array();
        foreach( $data as $d_key => $d_val ){
            if( $d_key == 'flight_number'.$c ){
                $ticketid[] = $d_val;
                $c++;
            }
        }

        $this->load->model('flight_model');
        for ($f = 0; $f < count($ticketid); $f++) {
            $details = $this->flight_model->getticketDetails($ticketid[$f]);

            // print_r($details);die;

            if ($details[0]->num_of_adults > 1) {
                $a_t = explode(",", $details[0]->adult_travellers_titles);
                $a_f = explode(",", $details[0]->adult_travellers_first_names);
                $a_l = explode(",", $details[0]->adult_travellers_last_names);
            }

            if ($details[0]->num_of_children > 0) {
                $c_t = explode(",", $details[0]->child_travellers_titles);
                $c_f = explode(",", $details[0]->child_travellers_first_names);
                $c_l = explode(",", $details[0]->child_travellers_last_names);
                $c_d = explode(",", $details[0]->child_travellers_dobs);
            }

            if ($details[0]->num_of_infants > 0) {
                $i_t = explode(",", $details[0]->infant_travellers_titles);
                $i_f = explode(",", $details[0]->infant_travellers_first_names);
                $i_l = explode(",", $details[0]->infant_travellers_last_names);
                $i_d = explode(",", $details[0]->infant_travellers_dobs);
            }

            $data = json_decode($details[0]->extra_info);
            require_once (APPPATH . 'lib/nusoap.php');

            $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
            $headerpara = array();
            $headerpara["UserName"] = 'redytrip';
            $headerpara["Password"] = 'redytrip@12';
            $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
            $client = new SoapClient($wsdl);
            $client->__setSoapHeaders(array(
                $client_header
            ));
            $ticket = array();
            $ticket["Ticket"]['wsTicketRequest']['BookingID'] = $details[0]->booking_id . "";
            $ticket["Ticket"]["wsTicketRequest"]["Origin"] = $data->booking_details->rest->Origin;
            $ticket["Ticket"]["wsTicketRequest"]["Destination"] = $data->booking_details->rest->Destination;
            if (!is_array($data->booking_details->rest->Segment->WSSegment)) {
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["SegmentIndicator"] = $data->booking_details->rest->Segment->WSSegment->SegmentIndicator;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineCode"] = $data->booking_details->rest->Segment->WSSegment->Airline->AirlineCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineName"] = $data->booking_details->rest->Segment->WSSegment->Airline->AirlineName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["FlightNumber"] = $data->booking_details->rest->Segment->WSSegment->FlightNumber;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["FareClass"] = $data->booking_details->rest->Segment->WSSegment->FareClass;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportCode"] = $data->booking_details->rest->Segment->WSSegment->Origin->AirportCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportName"] = $data->booking_details->rest->Segment->WSSegment->Origin->AirportName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["Terminal"] = $data->booking_details->rest->Segment->WSSegment->Origin->Terminal;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityCode"] = $data->booking_details->rest->Segment->WSSegment->Origin->CityCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityName"] = $data->booking_details->rest->Segment->WSSegment->Origin->CityName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryCode"] = $data->booking_details->rest->Segment->WSSegment->Origin->CountryCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryName"] = $data->booking_details->rest->Segment->WSSegment->Origin->CountryName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportCode"] = $data->booking_details->rest->Segment->WSSegment->Destination->AirportCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportName"] = $data->booking_details->rest->Segment->WSSegment->Destination->AirportName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["Terminal"] = $data->booking_details->rest->Segment->WSSegment->Destination->Terminal;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityCode"] = $data->booking_details->rest->Segment->WSSegment->Destination->CityCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityName"] = $data->booking_details->rest->Segment->WSSegment->Destination->CityName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryCode"] = $data->booking_details->rest->Segment->WSSegment->Destination->CountryCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryName"] = $data->booking_details->rest->Segment->WSSegment->Destination->CountryName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["DepTIme"] = $data->booking_details->rest->Segment->WSSegment->DepTIme;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["ArrTime"] = $data->booking_details->rest->Segment->WSSegment->ArrTime;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["ETicketEligible"] = true;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Duration"] = $data->booking_details->rest->Segment->WSSegment->Duration;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Stop"] = $data->booking_details->rest->Segment->WSSegment->Stop;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Craft"] = $data->booking_details->rest->Segment->WSSegment->Craft;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Status"] = $data->booking_details->rest->Segment->WSSegment->Status;
                $ticket['Ticket']['wsTicketRequest']["Segment"]["WSSegment"][0]["OperatingCarrier"] = "";
            }
            else {
                for ($i = 0; $i < count($data->booking_details->rest->Segment->WSSegment); $i++) {
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["SegmentIndicator"] = $data->booking_details->rest->Segment->WSSegment[$i]->SegmentIndicator;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Airline"]["AirlineCode"] = $data->booking_details->rest->Segment->WSSegment[$i]->Airline->AirlineCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Airline"]["AirlineName"] = $data->booking_details->rest->Segment->WSSegment[$i]->Airline->AirlineName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["FlightNumber"] = $data->booking_details->rest->Segment->WSSegment[$i]->FlightNumber;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["FareClass"] = $data->booking_details->rest->Segment->WSSegment[$i]->FareClass;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["AirportCode"] = $data->booking_details->rest->Segment->WSSegment[$i]->Origin->AirportCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["AirportName"] = $data->booking_details->rest->Segment->WSSegment[$i]->Origin->AirportName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["Terminal"] = $data->booking_details->rest->Segment->WSSegment[$i]->Origin->Terminal;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CityCode"] = $data->booking_details->rest->Segment->WSSegment[$i]->Origin->CityCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CityName"] = $data->booking_details->rest->Segment->WSSegment[$i]->Origin->CityName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CountryCode"] = $data->booking_details->rest->Segment->WSSegment[$i]->Origin->CountryCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CountryName"] = $data->booking_details->rest->Segment->WSSegment[$i]->Origin->CountryName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["AirportCode"] = $data->booking_details->rest->Segment->WSSegment[$i]->Destination->AirportCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["AirportName"] = $data->booking_details->rest->Segment->WSSegment[$i]->Destination->AirportName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["Terminal"] = $data->booking_details->rest->Segment->WSSegment[$i]->Destination->Terminal;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CityCode"] = $data->booking_details->rest->Segment->WSSegment[$i]->Destination->CityCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CityName"] = $data->booking_details->rest->Segment->WSSegment[$i]->Destination->CityName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CountryCode"] = $data->booking_details->rest->Segment->WSSegment[$i]->Destination->CountryCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CountryName"] = $data->booking_details->rest->Segment->WSSegment[$i]->Destination->CountryName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["DepTIme"] = $data->booking_details->rest->Segment->WSSegment[$i]->DepTIme;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["ArrTime"] = $data->booking_details->rest->Segment->WSSegment[$i]->ArrTime;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["ETicketEligible"] = true;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Duration"] = $data->booking_details->rest->Segment->WSSegment[$i]->Duration;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Stop"] = $data->booking_details->rest->Segment->WSSegment[$i]->Stop;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Craft"] = $data->booking_details->rest->Segment->WSSegment[$i]->Craft;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Status"] = $data->booking_details->rest->Segment->WSSegment[$i]->Status;
                    $ticket['Ticket']['wsTicketRequest']["Segment"]["WSSegment"][$i]["OperatingCarrier"] = "";
                }
            }

            /*----*/
            $ticket['Ticket']['wsTicketRequest']["FareType"] = "PUB";
            if (!is_array($data->booking_details->rest->FareRule->WSFareRule)) {
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Origin"] = $data->booking_details->rest->FareRule->WSFareRule->Origin;

                // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Destinaton"] = $data['details']->rest->FareRule->WSFareRule->Destinaton;

                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Airline"] = $data->booking_details->rest->FareRule->WSFareRule->Airline;

                // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareRestriction"] = $data['details']->rest->FareRule->WSFareRule->FareRestriction;

                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareBasisCode"] = $data->booking_details->rest->FareRule->WSFareRule->FareBasisCode;

                // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareRuleDetail"] =$data['details']->rest->FareRule->WSFareRule->FareRuleDetail;

                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["DepartureDate"] = $data->booking_details->rest->FareRule->WSFareRule->DepartureDate;
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["ReturnDate"] = $data->booking_details->rest->FareRule->WSFareRule->ReturnDate;
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Source"] = $data->booking_details->rest->FareRule->WSFareRule->Source;
            }
            else {
                for ($i = 0; $i < count($data->booking_details->rest->FareRule->WSFareRule); $i++) {
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["Origin"] = $data->booking_details->rest->FareRule->WSFareRule[$i]->Origin;

                    // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Destinaton"] = $data['details']->rest->FareRule->WSFareRule->Destinaton;

                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["Airline"] = $data->booking_details->rest->FareRule->WSFareRule[$i]->Airline;

                    // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareRestriction"] = $data['details']->rest->FareRule->WSFareRule->FareRestriction;

                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["FareBasisCode"] = $data->booking_details->rest->FareRule->WSFareRule[$i]->FareBasisCode;

                    // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareRuleDetail"] =$data['details']->rest->FareRule->WSFareRule->FareRuleDetail;

                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["DepartureDate"] = $data->booking_details->rest->FareRule->WSFareRule[$i]->DepartureDate;
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["ReturnDate"] = $data->booking_details->rest->FareRule->WSFareRule[$i]->ReturnDate;
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["Source"] = $data->booking_details->rest->FareRule->WSFareRule[$i]->Source;
                }
            }

            /*---*/

            // print_r($data->booking_details->rest->Fare->Tax);die;

            $ticket['Ticket']['wsTicketRequest']["Fare"]["BaseFare"] = $data->booking_details->rest->Fare->BaseFare;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["Tax"] = $data->booking_details->rest->Fare->Tax;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["ServiceTax"] = $data->booking_details->rest->Fare->ServiceTax;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AdditionalTxnFee"] = $data->booking_details->rest->Fare->AdditionalTxnFee;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AgentCommission"] = $data->booking_details->rest->Fare->AgentCommission;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TdsOnCommission"] = $data->booking_details->rest->Fare->TdsOnCommission;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["IncentiveEarned"] = $data->booking_details->rest->Fare->IncentiveEarned;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TdsOnIncentive"] = $data->booking_details->rest->Fare->TdsOnIncentive;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["PLBEarned"] = $data->booking_details->rest->Fare->PLBEarned;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TdsOnPLB"] = $data->booking_details->rest->Fare->TdsOnPLB;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["PublishedPrice"] = $data->booking_details->rest->Fare->PublishedPrice;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AirTransFee"] = $data->booking_details->rest->Fare->AirTransFee;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["Currency"] = $data->booking_details->rest->Fare->Currency;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["Discount"] = $data->booking_details->rest->Fare->Discount;
            if (!is_array($data->booking_details->rest->Fare->ChargeBU->ChargeBreakUp)) {
                $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"]["PriceId"] = $data->booking_details->rest->Fare->ChargeBU->ChargeBreakUp->PriceId;
                $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"]["ChargeType"] = $data->booking_details->rest->Fare->ChargeBU->ChargeBreakUp->ChargeType;
                $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"]["Amount"] = $data->booking_details->rest->Fare->ChargeBU->ChargeBreakUp->Amount;
            }
            else {
                for ($i = 0; $i < count($data->booking_details->rest->Fare->ChargeBU->ChargeBreakUp); $i++) {
                    $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"][$i]["PriceId"] = $data->booking_details->rest->Fare->ChargeBU->ChargeBreakUp[$i]->PriceId;
                    $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"][$i]["ChargeType"] = $data->booking_details->rest->Fare->ChargeBU->ChargeBreakUp[$i]->ChargeType;
                    $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"][$i]["Amount"] = $data->booking_details->rest->Fare->ChargeBU->ChargeBreakUp[$i]->Amount;
                }
            }

            $ticket['Ticket']['wsTicketRequest']["Fare"]["OtherCharges"] = $data->booking_details->rest->Fare->OtherCharges;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["FuelSurcharge"] = $data->booking_details->rest->Fare->FuelSurcharge;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TransactionFee"] = $data->booking_details->rest->Fare->TransactionFee;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["ReverseHandlingCharge"] = $data->booking_details->rest->Fare->ReverseHandlingCharge;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["OfferedFare"] = $data->booking_details->rest->Fare->OfferedFare;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AgentServiceCharge"] = $data->booking_details->rest->Fare->AgentServiceCharge;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AgentConvienceCharges"] = $data->booking_details->rest->Fare->AgentConvienceCharges;
            /*---*/
            $i = 0;
            if ($details[0]->num_of_adults >= 1) {
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $details[0]->lead_traveller_title;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $details[0]->lead_traveller_first_name;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $details[0]->lead_traveller_last_name;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_a'][$j]));
                if (is_array($data->booking_details->rest->FareBreakdown->WSPTCFare)) {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->booking_details->rest->Fare->ServiceTax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentConvienceCharges / $details[0]->num_of_adults;
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->booking_details->rest->Fare->ServiceTax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges / $details[0]->num_of_adults;
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->booking_details->rest->Fare->AgentCommission / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->booking_details->rest->Fare->TdsOnCommission / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->booking_details->rest->Fare->IncentiveEarned / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->booking_details->rest->Fare->TdsOnIncentive / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->booking_details->rest->Fare->PLBEarned / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->booking_details->rest->Fare->TdsOnPLB / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->booking_details->rest->Fare->PublishedPrice / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->booking_details->rest->Fare->AirTransFee / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->booking_details->rest->Fare->Discount / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->booking_details->rest->Fare->OtherCharges / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->booking_details->rest->Fare->TransactionFee / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->booking_details->rest->Fare->ReverseHandlingCharge / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->booking_details->rest->Fare->OfferedFare / $details[0]->num_of_adults;
                if ($details[0]->lead_traveller_title == 'Mr' || $details[0]->lead_traveller_title == 'Master') {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
            }

            /*---*/
            $i = 1;
            while ($i < $details[0]->num_of_adults) {
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $a_t[$i - 1];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $a_f[$i - 1];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $a_l[$i - 1];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_a'][$j]));
                if (is_array($data->booking_details->rest->FareBreakdown->WSPTCFare)) {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentConvienceCharges / $details[0]->num_of_adults;
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->booking_details->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges / $details[0]->num_of_adults;
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->booking_details->rest->Fare->Discount / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->booking_details->rest->Fare->OtherCharges / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->booking_details->rest->Fare->ServiceTax / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->booking_details->rest->Fare->AgentCommission / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->booking_details->rest->Fare->TdsOnCommission / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->booking_details->rest->Fare->IncentiveEarned / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->booking_details->rest->Fare->TdsOnIncentive / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->booking_details->rest->Fare->PLBEarned / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->booking_details->rest->Fare->TdsOnPLB / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->booking_details->rest->Fare->PublishedPrice / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->booking_details->rest->Fare->AirTransFee / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->booking_details->rest->Fare->TransactionFee / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->booking_details->rest->Fare->ReverseHandlingCharge / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->booking_details->rest->Fare->OfferedFare / $details[0]->num_of_adults;
                if ($a_t[$i - 1] == 'Mr' || $a_t[$i - 1] == 'Master') {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                $i++;
            }

            /*--*/
            $i = 0;
            while ($i < $details[0]->num_of_children) {
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $c_t[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $c_f[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $c_l[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_a'][$j]));
                if (count($data->booking_details->rest->FareBreakdown->WSPTCFare) == 1 && $data->booking_details->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Child") {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[1]->BaseFare / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[1]->Tax / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[1]->AdditionalTxnFee / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[1]->FuelSurcharge / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[1]->AgentServiceCharge / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[1]->AgentConvienceCharges / $details[0]->num_of_children;
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->booking_details->rest->Fare->ServiceTax;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->booking_details->rest->Fare->AgentCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->booking_details->rest->Fare->TdsOnCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->booking_details->rest->Fare->IncentiveEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->booking_details->rest->Fare->TdsOnIncentive;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->booking_details->rest->Fare->PLBEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->booking_details->rest->Fare->TdsOnPLB;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->booking_details->rest->Fare->PublishedPrice;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->booking_details->rest->Fare->AirTransFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->booking_details->rest->Fare->Discount;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->booking_details->rest->Fare->OtherCharges;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->booking_details->rest->Fare->TransactionFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->booking_details->rest->Fare->ReverseHandlingCharge;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->booking_details->rest->Fare->OfferedFare;
                if ($c_t[$i] == 'Mr' || $c_t[$i] == 'Master') {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                $i++;
            }

            /*--*/
            $i = 0;
            while ($i < $details[0]->num_of_infants) {
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $i_t[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $i_f[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $i_l[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_a'][$j]));
                if (count($data->booking_details->rest->FareBreakdown->WSPTCFare) == 2 && $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->PassengerType == "Infant") {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->BaseFare / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->Tax / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->AdditionalTxnFee / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->FuelSurcharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->AgentServiceCharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->AgentConvienceCharges / $details[0]->num_of_infants;
                }

                if (count($data->booking_details->rest->FareBreakdown->WSPTCFare) == 1 && $data->booking_details->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Infant") {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->BaseFare / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->Tax / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->AdditionalTxnFee / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->FuelSurcharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->AgentServiceCharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->booking_details->rest->FareBreakdown->WSPTCFare[2]->AgentConvienceCharges / $details[0]->num_of_infants;
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->booking_details->rest->Fare->ServiceTax;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->booking_details->rest->Fare->AgentCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->booking_details->rest->Fare->TdsOnCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->booking_details->rest->Fare->IncentiveEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->booking_details->rest->Fare->TdsOnIncentive;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->booking_details->rest->Fare->PLBEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->booking_details->rest->Fare->TdsOnPLB;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->booking_details->rest->Fare->PublishedPrice;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->booking_details->rest->Fare->AirTransFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->booking_details->rest->Fare->Discount;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->booking_details->rest->Fare->OtherCharges;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->booking_details->rest->Fare->TransactionFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->booking_details->rest->Fare->ReverseHandlingCharge;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->booking_details->rest->Fare->OfferedFare;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                $i++;
            }

            /*--*/
            $ticket["Ticket"]["wsTicketRequest"]["Remarks"] = "FlightTicket";
            $ticket["Ticket"]["wsTicketRequest"]["InstantTicket"] = TRUE;
            /*--*/
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentInformationId"] = 0;
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["InvoiceNumber"] = 0;
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentId"] = "0";
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["Amount"] = $data->booking_details->rest->Fare->OfferedFare;
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["IPAddress"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["TrackId"] = 0;
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentGateway"] = "APICustomer";
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentModeType"] = "Deposited";
            /*---*/
            $ticket["Ticket"]["wsTicketRequest"]["Source"] = $data->booking_details->rest->Source;
            $retSess = $this->flight_model->getSessionId($ticketid[$i]);
            $ticket["Ticket"]["wsTicketRequest"]["SessionId"] = $retSess[0]->session_id;
            $ticket["Ticket"]["wsTicketRequest"]["IsOneWayBooking"] = TRUE;
            $ticket["Ticket"]["wsTicketRequest"]["CorporateCode"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["TourCode"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["Endorsement"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["PromotionalPlanType"] = "Normal";
            $header = array();
            $header['se'] = (array)$client->__call('Ticket', $ticket);


            // if ($header['se']['TicketResult']->Status->Description == "Sucessfull")
            // {
            //   $this->load->model('flight_model');
            //   $ret['pnr'] = $header['se']['TicketResult']->PNR;
            //   $ret['booking_id'] =  $header['se']['TicketResult']->BookingId;
            //   $ret['status'] = "Successful";
            //   $this->flight_model->updateLccTicketStatus($ticketid[$f],$ret);
            // }

            if ($header['se']['TicketResult']->PNR != "") {
                $this->load->model('flight_model');
                $ret['pnr'] = $header['se']['TicketResult']->PNR;
                $ret['booking_id'] = $header['se']['TicketResult']->BookingId;
                $ret['status'] = "Successful";
                $this->flight_model->updateLccTicketStatus($ticketid[$f], $ret);
                /*---get booking details*/

                // print_r($ret['booking_id']);die;

                require_once (APPPATH . 'lib/nusoap.php');

                $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
                $headerpara = array();
                $headerpara["UserName"] = 'redytrip';
                $headerpara["Password"] = 'redytrip@12';
                $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
                $client = new SoapClient($wsdl);
                $client->__setSoapHeaders(array(
                    $client_header
                ));
                $ticket_details = array();
                $ticket_details['GetBooking']['bookingRequest']['BookingId'] = intval($ret['booking_id']);
                $ticket_details['GetBooking']['bookingRequest']['Pnr'] = "" . $ret['pnr'];
                if (!is_array($data->booking_details->rest->FareRule->WSFareRule)) $ticket_details['GetBooking']['bookingRequest']['Source'] = "" . $data->booking_details->rest->FareRule->WSFareRule->Source;
                else $ticket_details['GetBooking']['bookingRequest']['Source'] = "" . $data->booking_details->rest->FareRule->WSFareRule[0]->Source;

                // $ticket_details['GetBooking']['bookingRequest']['LastName'] = "".$details[0]->lead_traveller_last_name;

                $ticket_details['GetBooking']['bookingRequest']['LastName'] = "";
                $ticket_details['GetBooking']['bookingRequest']['TicketId'] = 0;
                $header = array();
                $header['se'] = (array)$client->__call('GetBooking', $ticket_details);

                // print_r($ticket_details);
                // print_r($header['se']);die;

                $info['Source'] = $data->booking_details->rest->Source;
                $info['BookingId'] = $header['se']['GetBookingResult']->BookingId;
                $info['PNR'] = $header['se']['GetBookingResult']->PNR;
                $info['Source'] = $data->booking_details->rest->Source;
                $fare_obj = $header['se']['GetBookingResult']->Fare;
                $info['TotalFare'] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $info['FareBreakdown'] = json_encode($header['se']['GetBookingResult']->Fare);
                if (is_array($header['se']['GetBookingResult']->Passenger->WSPassenger)) {
                    $info['LeadTitle'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->Title;
                    $info['LeadFirstName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->FirstName;
                    $info['LeadLastName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->LastName;
                    $info['LeadGender'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->Gender;
                    $info['LeadEmail'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->Email;
                    $iter = 0;
                    foreach($header['se']['GetBookingResult']->Passenger->WSPassenger as $ws) {
                        $passengers['Title'][$iter] = $ws->Title;
                        $passengers['FirstName'][$iter] = $ws->FirstName;
                        $passengers['LastName'][$iter] = $ws->LastName;
                        $passengers['Gender'][$iter] = $ws->Gender;
                        $passengers['Type'][$iter] = $ws->Type;
                        $fare_obj = $ws->Fare;
                        $passengers['IndividualFare'][$iter] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                        $iter++;
                    }

                    $info['Title'] = implode(',', $passengers['Title']);
                    $info['FirstName'] = implode(',', $passengers['FirstName']);
                    $info['LastName'] = implode(',', $passengers['LastName']);
                    $info['Gender'] = implode(',', $passengers['Gender']);
                    $info['Type'] = implode(',', $passengers['Type']);
                    $info['IndividualFare'] = implode(',', $passengers['IndividualFare']);
                }
                else {
                    $info['LeadTitle'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Title;
                    $info['LeadFirstName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->FirstName;
                    $info['LeadLastName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->LastName;
                    $info['LeadGender'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Gender;
                    $info['LeadEmail'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Email;
                    $info['Title'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Title;
                    $info['FirstName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->FirstName;
                    $info['LastName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->LastName;
                    $info['Gender'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Gender;
                    $info['Type'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Type;
                    $fare_obj = $header['se']['GetBookingResult']->Passenger->WSPassenger->Fare;
                    $info['IndividualFare'] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                }

                $info['Origin'] = $header['se']['GetBookingResult']->Origin;
                $info['Destination'] = $header['se']['GetBookingResult']->Destination;
                if (is_array($header['se']['GetBookingResult']->Segment->WSSegment)) {
                    $r = 0;
                    foreach($header['se']['GetBookingResult']->Segment->WSSegment as $wss) {
                        $segment['ConnectingJourney'][$r] = $wss->Origin->CityCode;
                        $segment['ConnectingJourney'][$r + 1] = $wss->Destination->CityCode;
                        $segment['ConnectingDepTime'][$r] = $wss->DepTIme;
                        $segment['ConnectingArrTime'][$r] = $wss->ArrTime;
                        $segment['ConnectingAirlineName'] = $wss->Airline->AirlineName;
                        $segment['ConnectingAirlineCode'] = $wss->Airline->AirlineCode;
                        $segment['ConnectingCityName'][$r] = $wss->Origin->CityName;
                        $segment['ConnectingCityName'][$r + 1] = $wss->Destination->CityName;
                        $segment['ConnectingCityCode'][$r] = $wss->Origin->CityCode;
                        $segment['ConnectingCityCode'][$r + 1] = $wss->Destination->CityCode;
                        $r++;
                    }

                    $info['ConnectingJourney'] = implode(',', $segment['ConnectingJourney']);
                    $info['ConnectingDepTime'] = implode(',', $segment['ConnectingDepTime']);
                    $info['ConnectingArrTime'] = implode(',', $segment['ConnectingArrTime']);
                    $info['ConnectingAirlineName'] = $segment['ConnectingAirlineName'];
                    $info['ConnectingAirlineCode'] = $segment['ConnectingAirlineCode'];
                    $info['ConnectingCityName'] = implode(',', $segment['ConnectingCityName']);
                    $info['ConnectingCityCode'] = implode(',', $segment['ConnectingCityCode']);
                }
                else {
                    $info['ConnectingDepTime'] = $header['se']['GetBookingResult']->Segment->WSSegment->DepTIme;
                    $info['ConnectingArrTime'] = $header['se']['GetBookingResult']->Segment->WSSegment->ArrTime;
                    $info['ConnectingAirlineName'] = $header['se']['GetBookingResult']->Segment->WSSegment->Airline->AirlineName;
                    $info['ConnectingAirlineCode'] = $header['se']['GetBookingResult']->Segment->WSSegment->Airline->AirlineCode;
                    $info['OriginCityCode'] = $header['se']['GetBookingResult']->Segment->WSSegment->Origin->CityCode;
                    $info['OriginCityName'] = $header['se']['GetBookingResult']->Segment->WSSegment->Origin->CityName;
                    $info['DestinationCityCode'] = $header['se']['GetBookingResult']->Segment->WSSegment->Destination->CityCode;
                    $info['DestinationCityName'] = $header['se']['GetBookingResult']->Segment->WSSegment->Destination->CityName;
                }

                if (is_array($header['se']['GetBookingResult']->Ticket->WSTicket)) {
                    $t = 0;
                    foreach($header['se']['GetBookingResult']->Ticket->WSTicket as $wst) {
                        $tickets['TicketId'][$t] = $wst->TicketId;
                        $tickets['TicketNumber'][$t] = trim($wst->TicketNumber, ' ');
                        $tickets['IssueDate'][$t] = $wst->IssueDate;
                        $t++;
                    }

                    $info['TicketId'] = implode(',', $tickets['TicketId']);
                    $info['TicketNumber'] = implode(',', $tickets['TicketNumber']);
                    $info['IssueDate'] = implode(',', $tickets['IssueDate']);
                }
                else {
                    $info['TicketId'] = $header['se']['GetBookingResult']->Ticket->WSTicket->TicketId;
                    $info['TicketNumber'] = trim($header['se']['GetBookingResult']->Ticket->WSTicket->TicketNumber, ' ');
                    $info['IssueDate'] = $header['se']['GetBookingResult']->Ticket->WSTicket->IssueDate;
                }

                $info['PayuId'] = $_SESSION['payu_id'];
                $this->load->model('flight_model');
                $ticket_booking_id = $this->flight_model->updatePayuId($info['PayuId'], $header['se']['GetBookingResult']->BookingId);
                $ticket_booking_id[$f] = $this->flight_model->setTicketDetails($info);
            }
        }

        $this->ticket_details_assorted( $ticket_booking_id );
    }

    /*-----oneway------*/
    function ticket()
    {
        $ticketid = $_SESSION['ticket_id'];
        $this->load->model('flight_model');
        $details = $this->flight_model->getticketDetails($ticketid);

        $getPassengerDetailsObj = new GetPassengerDetails;
        $getTicketDetailsObj = new GetTicketDetails;
        $getTicketBookingDetailsObj = new GetTicketBookingDetails;
        $getTicketInformationObj = new GetTicketInformation;

        $fb_booking_id = $details[0]->fb_bookingId;
        $data = json_decode($details[0]->extra_info, TRUE);

        $passengerDetails = $getPassengerDetailsObj->setPassengerDetails($details);
        $ticket = $getTicketDetailsObj->setTicketDetails($data,$details);

        $flightsAPIAuthObj = new FlightsAPIAuth;
        $flightsSearchRequestObj = new FlightsSearchRequest;
        $flightsSOAPObj = new FlightsSOAP;
        $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;

        $flightsAPIAuthObj->setUserId("redytrip");
        $flightsAPIAuthObj->setPassword("redytrip@12");
        $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

        $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
        $flightsSOAPObj->setSOAPClient();
        $flightsSOAPObj->setSOAPHeader($authDataArr);

        $result = $flightsSOAPObj->makeSOAPCall("Ticket", $ticket);

        $dataRes = $this->addBookingDetails($result);
        
        if ($result->TicketResult->PNR != "") {
            $this->load->model('flight_model');
            $ret['pnr'] = $result->TicketResult->PNR;
            $ret['booking_id'] = $result->TicketResult->BookingId;
            $ret['status'] = "Successful";
            $this->flight_model->updateLccTicketStatus($ticketid, $ret);

            $ticket_details = $getTicketBookingDetailsObj->setTicketBookingDetails($ret,$data);          

            $flightsAPIAuthObj->setUserId("redytrip");
            $flightsAPIAuthObj->setPassword("redytrip@12");
            $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

            $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
            $flightsSOAPObj->setSOAPClient();
            $flightsSOAPObj->setSOAPHeader($authDataArr);

            $bookingResult = $flightsSOAPObj->makeSOAPCall("GetBooking", $ticket_details);

            // echo "<pre>";
            // print_r($bookingResult);die;

            $info = $getTicketInformationObj->setTicketInformation($data, $bookingResult);

            $info['fbBooking_id'] = $fb_booking_id;
            $info['PayuId'] = $_SESSION['payu_id'];
            $this->load->model('flight_model');
            $ticket_booking_id = $this->flight_model->updatePayuId($info['PayuId'], $bookingResult->GetBookingResult->BookingId);
            $ticket_booking_id = $this->flight_model->setTicketDetails($info);
        }

        $this->ticket_details($details[0]->fb_bookingId);
        
    }

    public function addBookingDetails($ticket){
        /**
        * Object initialisation
        */
        $flightsAPIAuthObj = new FlightsAPIAuth;
        $flightsSearchRequestObj = new FlightsSearchRequest;
        $flightsSOAPObj = new FlightsSOAP;
        $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;

        $getAddBookingDetailsObj = new GetAddBookingDetails;

        /**
        * Set Request Authentication Data array
        * UserName
        * Password
        */
        $flightsAPIAuthObj->setUserId("redytrip");
        $flightsAPIAuthObj->setPassword("redytrip@12");
        $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

        $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
        $flightsSOAPObj->setSOAPClient();
        $flightsSOAPObj->setSOAPHeader($authDataArr);

        $add_booking_details = $getAddBookingDetailsObj->setAddBookingDetails($ticket);

        $result = $flightsSOAPObj->makeSOAPCall("AddBookingDetail", $add_booking_details);

    }

    function ticket_multiway()
    {
        /*one way*/
        $this->load->model('flight_model');
        if ($_SESSION['retId']) {
            $ticketid = $_SESSION['retId'];
        }
        else {
            $this->load->view('common/header.php');
            $this->load->view('flights/failure_page.php');
            $this->load->view('common/footer.php');
        }

        for ($f = 0; $f < count($ticketid); $f++) {
            $details = $this->flight_model->getticketDetails($ticketid[$f]);

            if ($details[0]->num_of_adults > 1) {
                $a_t = explode(",", $details[0]->adult_travellers_titles);
                $a_f = explode(",", $details[0]->adult_travellers_first_names);
                $a_l = explode(",", $details[0]->adult_travellers_last_names);
            }

            if ($details[0]->num_of_children > 0) {
                $c_t = explode(",", $details[0]->child_travellers_titles);
                $c_f = explode(",", $details[0]->child_travellers_first_names);
                $c_l = explode(",", $details[0]->child_travellers_last_names);
                $c_d = explode(",", $details[0]->child_travellers_dobs);
            }

            if ($details[0]->num_of_infants > 0) {
                $i_t = explode(",", $details[0]->infant_travellers_titles);
                $i_f = explode(",", $details[0]->infant_travellers_first_names);
                $i_l = explode(",", $details[0]->infant_travellers_last_names);
                $i_d = explode(",", $details[0]->infant_travellers_dobs);
            }

            $data = json_decode($details[0]->extra_info);
            require_once (APPPATH . 'lib/nusoap.php');

            $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
            $headerpara = array();
            $headerpara["UserName"] = 'redytrip';
            $headerpara["Password"] = 'redytrip@12';
            $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
            $client = new SoapClient($wsdl);
            $client->__setSoapHeaders(array(
                $client_header
            ));
            $ticket = array();
            $ticket["Ticket"]['wsTicketRequest']['BookingID'] = $details[0]->booking_id . "";
            $ticket["Ticket"]["wsTicketRequest"]["Origin"] = $data->rest->Origin;
            $ticket["Ticket"]["wsTicketRequest"]["Destination"] = $data->rest->Destination;
            if (!is_array($data->rest->Segment->WSSegment)) {
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["SegmentIndicator"] = $data->rest->Segment->WSSegment->SegmentIndicator;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineCode"] = $data->rest->Segment->WSSegment->Airline->AirlineCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineName"] = $data->rest->Segment->WSSegment->Airline->AirlineName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["FlightNumber"] = $data->rest->Segment->WSSegment->FlightNumber;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["FareClass"] = $data->rest->Segment->WSSegment->FareClass;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportCode"] = $data->rest->Segment->WSSegment->Origin->AirportCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportName"] = $data->rest->Segment->WSSegment->Origin->AirportName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["Terminal"] = $data->rest->Segment->WSSegment->Origin->Terminal;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityCode"] = $data->rest->Segment->WSSegment->Origin->CityCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityName"] = $data->rest->Segment->WSSegment->Origin->CityName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryCode"] = $data->rest->Segment->WSSegment->Origin->CountryCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryName"] = $data->rest->Segment->WSSegment->Origin->CountryName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportCode"] = $data->rest->Segment->WSSegment->Destination->AirportCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportName"] = $data->rest->Segment->WSSegment->Destination->AirportName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["Terminal"] = $data->rest->Segment->WSSegment->Destination->Terminal;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityCode"] = $data->rest->Segment->WSSegment->Destination->CityCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityName"] = $data->rest->Segment->WSSegment->Destination->CityName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryCode"] = $data->rest->Segment->WSSegment->Destination->CountryCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryName"] = $data->rest->Segment->WSSegment->Destination->CountryName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["DepTIme"] = $data->rest->Segment->WSSegment->DepTIme;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["ArrTime"] = $data->rest->Segment->WSSegment->ArrTime;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["ETicketEligible"] = true;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Duration"] = $data->rest->Segment->WSSegment->Duration;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Stop"] = $data->rest->Segment->WSSegment->Stop;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Craft"] = $data->rest->Segment->WSSegment->Craft;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Status"] = $data->rest->Segment->WSSegment->Status;
                $ticket['Ticket']['wsTicketRequest']["Segment"]["WSSegment"][0]["OperatingCarrier"] = "";
            }
            else {
                for ($i = 0; $i < count($data->rest->Segment->WSSegment); $i++) {
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["SegmentIndicator"] = $data->rest->Segment->WSSegment[$i]->SegmentIndicator;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Airline"]["AirlineCode"] = $data->rest->Segment->WSSegment[$i]->Airline->AirlineCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Airline"]["AirlineName"] = $data->rest->Segment->WSSegment[$i]->Airline->AirlineName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["FlightNumber"] = $data->rest->Segment->WSSegment[$i]->FlightNumber;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["FareClass"] = $data->rest->Segment->WSSegment[$i]->FareClass;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["AirportCode"] = $data->rest->Segment->WSSegment[$i]->Origin->AirportCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["AirportName"] = $data->rest->Segment->WSSegment[$i]->Origin->AirportName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["Terminal"] = $data->rest->Segment->WSSegment[$i]->Origin->Terminal;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CityCode"] = $data->rest->Segment->WSSegment[$i]->Origin->CityCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CityName"] = $data->rest->Segment->WSSegment[$i]->Origin->CityName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CountryCode"] = $data->rest->Segment->WSSegment[$i]->Origin->CountryCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CountryName"] = $data->rest->Segment->WSSegment[$i]->Origin->CountryName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["AirportCode"] = $data->rest->Segment->WSSegment[$i]->Destination->AirportCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["AirportName"] = $data->rest->Segment->WSSegment[$i]->Destination->AirportName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["Terminal"] = $data->rest->Segment->WSSegment[$i]->Destination->Terminal;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CityCode"] = $data->rest->Segment->WSSegment[$i]->Destination->CityCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CityName"] = $data->rest->Segment->WSSegment[$i]->Destination->CityName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CountryCode"] = $data->rest->Segment->WSSegment[$i]->Destination->CountryCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CountryName"] = $data->rest->Segment->WSSegment[$i]->Destination->CountryName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["DepTIme"] = $data->rest->Segment->WSSegment[$i]->DepTIme;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["ArrTime"] = $data->rest->Segment->WSSegment[$i]->ArrTime;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["ETicketEligible"] = true;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Duration"] = $data->rest->Segment->WSSegment[$i]->Duration;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Stop"] = $data->rest->Segment->WSSegment[$i]->Stop;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Craft"] = $data->rest->Segment->WSSegment[$i]->Craft;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Status"] = $data->rest->Segment->WSSegment[$i]->Status;
                    $ticket['Ticket']['wsTicketRequest']["Segment"]["WSSegment"][$i]["OperatingCarrier"] = "";
                }
            }

            /*----*/
            $ticket['Ticket']['wsTicketRequest']["FareType"] = "PUB";
            if (!is_array($data->rest->FareRule->WSFareRule)) {
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Origin"] = $data->rest->FareRule->WSFareRule->Origin;

                // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Destinaton"] = $data['details']->rest->FareRule->WSFareRule->Destinaton;

                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Airline"] = $data->rest->FareRule->WSFareRule->Airline;

                // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareRestriction"] = $data['details']->rest->FareRule->WSFareRule->FareRestriction;

                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareBasisCode"] = $data->rest->FareRule->WSFareRule->FareBasisCode;

                // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareRuleDetail"] =$data['details']->rest->FareRule->WSFareRule->FareRuleDetail;

                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["DepartureDate"] = $data->rest->FareRule->WSFareRule->DepartureDate;
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["ReturnDate"] = $data->rest->FareRule->WSFareRule->ReturnDate;
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Source"] = $data->rest->FareRule->WSFareRule->Source;
            }
            else {
                for ($i = 0; $i < count($data->rest->FareRule->WSFareRule); $i++) {
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["Origin"] = $data->rest->FareRule->WSFareRule[$i]->Origin;

                    // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Destinaton"] = $data['details']->rest->FareRule->WSFareRule->Destinaton;

                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["Airline"] = $data->rest->FareRule->WSFareRule[$i]->Airline;

                    // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareRestriction"] = $data['details']->rest->FareRule->WSFareRule->FareRestriction;

                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["FareBasisCode"] = $data->rest->FareRule->WSFareRule[$i]->FareBasisCode;

                    // $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareRuleDetail"] =$data['details']->rest->FareRule->WSFareRule->FareRuleDetail;

                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["DepartureDate"] = $data->rest->FareRule->WSFareRule[$i]->DepartureDate;
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["ReturnDate"] = $data->rest->FareRule->WSFareRule[$i]->ReturnDate;
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["Source"] = $data->rest->FareRule->WSFareRule[$i]->Source;
                }
            }

            /*---*/

            // print_r($data->rest->Fare->Tax);die;

            $ticket['Ticket']['wsTicketRequest']["Fare"]["BaseFare"] = $data->rest->Fare->BaseFare;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["Tax"] = $data->rest->Fare->Tax;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AdditionalTxnFee"] = $data->rest->Fare->AdditionalTxnFee;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AgentCommission"] = $data->rest->Fare->AgentCommission;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TdsOnCommission"] = $data->rest->Fare->TdsOnCommission;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["IncentiveEarned"] = $data->rest->Fare->IncentiveEarned;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TdsOnIncentive"] = $data->rest->Fare->TdsOnIncentive;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["PLBEarned"] = $data->rest->Fare->PLBEarned;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TdsOnPLB"] = $data->rest->Fare->TdsOnPLB;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["PublishedPrice"] = $data->rest->Fare->PublishedPrice;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AirTransFee"] = $data->rest->Fare->AirTransFee;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["Currency"] = $data->rest->Fare->Currency;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["Discount"] = $data->rest->Fare->Discount;
            if (!is_array($data->rest->Fare->ChargeBU->ChargeBreakUp)) {
                $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"]["PriceId"] = $data->rest->Fare->ChargeBU->ChargeBreakUp->PriceId;
                $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"]["ChargeType"] = $data->rest->Fare->ChargeBU->ChargeBreakUp->ChargeType;
                $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"]["Amount"] = $data->rest->Fare->ChargeBU->ChargeBreakUp->Amount;
            }
            else {
                for ($i = 0; $i < count($data->rest->Fare->ChargeBU->ChargeBreakUp); $i++) {
                    $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"][$i]["PriceId"] = $data->rest->Fare->ChargeBU->ChargeBreakUp[$i]->PriceId;
                    $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"][$i]["ChargeType"] = $data->rest->Fare->ChargeBU->ChargeBreakUp[$i]->ChargeType;
                    $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"][$i]["Amount"] = $data->rest->Fare->ChargeBU->ChargeBreakUp[$i]->Amount;
                }
            }

            $ticket['Ticket']['wsTicketRequest']["Fare"]["OtherCharges"] = $data->rest->Fare->OtherCharges;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["FuelSurcharge"] = $data->rest->Fare->FuelSurcharge;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TransactionFee"] = $data->rest->Fare->TransactionFee;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["ReverseHandlingCharge"] = $data->rest->Fare->ReverseHandlingCharge;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["OfferedFare"] = $data->rest->Fare->OfferedFare;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AgentServiceCharge"] = $data->rest->Fare->AgentServiceCharge;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AgentConvienceCharges"] = $data->rest->Fare->AgentConvienceCharges;
            /*---*/
            $i = 0;
            if ($details[0]->num_of_adults >= 1) {
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $details[0]->lead_traveller_title;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $details[0]->lead_traveller_first_name;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $details[0]->lead_traveller_last_name;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1-1-1990'));
                if (is_array($data['fare_breakdown']['WSPTCFare'])) {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][0]->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][0]->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][0]->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][0]->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][0]->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][0]->AgentConvienceCharges / $details[0]->num_of_adults;
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare']->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare']->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare']->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare']->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare']->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare']->AgentConvienceCharges / $details[0]->num_of_adults;
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->rest->Fare->AgentCommission / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->rest->Fare->TdsOnCommission / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->rest->Fare->IncentiveEarned / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->rest->Fare->TdsOnIncentive / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->rest->Fare->PLBEarned / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->rest->Fare->TdsOnPLB / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->rest->Fare->PublishedPrice / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->rest->Fare->AirTransFee / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->rest->Fare->Discount / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->rest->Fare->OtherCharges / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->rest->Fare->TransactionFee / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->rest->Fare->ReverseHandlingCharge / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->rest->Fare->OfferedFare / $details[0]->num_of_adults;
                if ($details[0]->lead_traveller_title == 'Mr' || $details[0]->lead_traveller_title == 'Master') {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $details[0]->lead_traveller_mobile;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $details[0]->lead_traveller_email;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
            }

            /*---*/
            $i = 1;
            while ($i < $details[0]->num_of_adults) {
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $a_t[$i - 1];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $a_f[$i - 1];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $a_l[$i - 1];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1-1-1990'));
                if (is_array($data['fare_breakdown']['WSPTCFare'])) {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][0]->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][0]->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][0]->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][0]->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][0]->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][0]->AgentConvienceCharges / $details[0]->num_of_adults;
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare']->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare']->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare']->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare']->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare']->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare']->AgentConvienceCharges / $details[0]->num_of_adults;
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->rest->Fare->Discount / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->rest->Fare->OtherCharges / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->rest->Fare->AgentCommission / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->rest->Fare->TdsOnCommission / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->rest->Fare->IncentiveEarned / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->rest->Fare->TdsOnIncentive / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->rest->Fare->PLBEarned / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->rest->Fare->TdsOnPLB / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->rest->Fare->PublishedPrice / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->rest->Fare->AirTransFee / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->rest->Fare->TransactionFee / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->rest->Fare->ReverseHandlingCharge / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->rest->Fare->OfferedFare / $details[0]->num_of_adults;
                if ($a_t[$i - 1] == 'Mr' || $a_t[$i - 1] == 'Master') {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $details[0]->lead_traveller_mobile;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $details[0]->lead_traveller_email;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                $i++;
            }

            /*--*/
            $i = 0;
            while ($i < $details[0]->num_of_children) {
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $c_t[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $c_f[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $c_l[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1-1-1990'));
                if (count($data['fare_breakdown']['WSPTCFare']) == 1 && $data['fare_breakdown']['WSPTCFare'][1]->PassengerType == "Child") {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][1]->BaseFare / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][1]->Tax / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][1]->AdditionalTxnFee / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][1]->FuelSurcharge / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][1]->AgentServiceCharge / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][1]->AgentConvienceCharges / $details[0]->num_of_children;
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->rest->Fare->AgentCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->rest->Fare->TdsOnCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->rest->Fare->IncentiveEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->rest->Fare->TdsOnIncentive;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->rest->Fare->PLBEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->rest->Fare->TdsOnPLB;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->rest->Fare->PublishedPrice;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->rest->Fare->AirTransFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->rest->Fare->Discount;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->rest->Fare->OtherCharges;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->rest->Fare->TransactionFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->rest->Fare->ReverseHandlingCharge;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->rest->Fare->OfferedFare;
                if ($c_t[$i] == 'Mr' || $c_t[$i] == 'Master') {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $details[0]->lead_traveller_mobile;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $details[0]->lead_traveller_email;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                $i++;
            }

            /*--*/
            $i = 0;
            while ($i < $details[0]->num_of_infants) {
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $i_t[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $i_f[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $i_l[$i];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1-1-1990'));
                if (count($data->rest->FareBreakdown->WSPTCFare) == 2 && $data->rest->FareBreakdown->WSPTCFare[2]->PassengerType == "Infant") {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data->rest->FareBreakdown->WSPTCFare[2]->BaseFare / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data->rest->FareBreakdown->WSPTCFare[2]->Tax / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->rest->FareBreakdown->WSPTCFare[2]->AdditionalTxnFee / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->rest->FareBreakdown->WSPTCFare[2]->FuelSurcharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->rest->FareBreakdown->WSPTCFare[2]->AgentServiceCharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->rest->FareBreakdown->WSPTCFare[2]->AgentConvienceCharges / $details[0]->num_of_infants;
                }

                if (count($data->rest->FareBreakdown->WSPTCFare) == 1 && $data->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Infant") {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data->rest->FareBreakdown->WSPTCFare[2]->BaseFare / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data->rest->FareBreakdown->WSPTCFare[2]->Tax / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->rest->FareBreakdown->WSPTCFare[2]->AdditionalTxnFee / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->rest->FareBreakdown->WSPTCFare[2]->FuelSurcharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->rest->FareBreakdown->WSPTCFare[2]->AgentServiceCharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->rest->FareBreakdown->WSPTCFare[2]->AgentConvienceCharges / $details[0]->num_of_infants;
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->rest->Fare->AgentCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->rest->Fare->TdsOnCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->rest->Fare->IncentiveEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->rest->Fare->TdsOnIncentive;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->rest->Fare->PLBEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->rest->Fare->TdsOnPLB;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->rest->Fare->PublishedPrice;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->rest->Fare->AirTransFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->rest->Fare->Discount;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->rest->Fare->OtherCharges;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->rest->Fare->TransactionFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->rest->Fare->ReverseHandlingCharge;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->rest->Fare->OfferedFare;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $details[0]->lead_traveller_mobile;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $details[0]->lead_traveller_email;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                $i++;
            }

            /*--*/
            $ticket["Ticket"]["wsTicketRequest"]["Remarks"] = "FlightTicket";
            $ticket["Ticket"]["wsTicketRequest"]["InstantTicket"] = TRUE;
            /*--*/
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentInformationId"] = 0;
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["InvoiceNumber"] = 0;
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentId"] = "0";
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["Amount"] = $data->rest->Fare->OfferedFare;
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["IPAddress"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["TrackId"] = 0;
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentGateway"] = "APICustomer";
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentModeType"] = "Deposited";
            /*---*/
            $ticket["Ticket"]["wsTicketRequest"]["Source"] = $data->rest->Source;
            $retSess = $this->flight_model->getSessionId($ticketid[$i]);
            $ticket["Ticket"]["wsTicketRequest"]["SessionId"] = $retSess[0]->session_id;
            $ticket["Ticket"]["wsTicketRequest"]["IsOneWayBooking"] = TRUE;
            $ticket["Ticket"]["wsTicketRequest"]["CorporateCode"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["TourCode"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["Endorsement"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["PromotionalPlanType"] = "Normal";
            $header = array();
            $header['se'] = (array)$client->__call('Ticket', $ticket);

            // if ($header['se']['TicketResult']->Status->Description == "Sucessfull")
            // {
            //   $this->load->model('flight_model');
            //   $ret['pnr'] = $header['se']['TicketResult']->PNR;
            //   $ret['booking_id'] =  $header['se']['TicketResult']->BookingId;
            //   $ret['status'] = "Successful";
            //   $this->flight_model->updateLccTicketStatus($ticketid[$f],$ret);
            // }

            if ($header['se']['TicketResult']->PNR != "") {
                $this->load->model('flight_model');
                $ret['pnr'] = $header['se']['TicketResult']->PNR;
                $ret['booking_id'] = $header['se']['TicketResult']->BookingId;
                $ret['status'] = "Successful";
                $this->flight_model->updateLccTicketStatus($ticketid[$f], $ret);
                /*---get booking details*/

                // print_r($ret['booking_id']);die;

                require_once (APPPATH . 'lib/nusoap.php');

                $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
                $headerpara = array();
                $headerpara["UserName"] = 'redytrip';
                $headerpara["Password"] = 'redytrip@12';
                $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
                $client = new SoapClient($wsdl);
                $client->__setSoapHeaders(array(
                    $client_header
                ));
                $ticket_details = array();
                $ticket_details['GetBooking']['bookingRequest']['BookingId'] = intval($ret['booking_id']);
                $ticket_details['GetBooking']['bookingRequest']['Pnr'] = "" . $ret['pnr'];
                if (!is_array($data->rest->FareRule->WSFareRule)) $ticket_details['GetBooking']['bookingRequest']['Source'] = "" . $data->rest->FareRule->WSFareRule->Source;
                else $ticket_details['GetBooking']['bookingRequest']['Source'] = "" . $data->rest->FareRule->WSFareRule[0]->Source;

                // $ticket_details['GetBooking']['bookingRequest']['LastName'] = "".$details[0]->lead_traveller_last_name;

                $ticket_details['GetBooking']['bookingRequest']['LastName'] = "";
                $ticket_details['GetBooking']['bookingRequest']['TicketId'] = 0;
                $header = array();
                $header['se'] = (array)$client->__call('GetBooking', $ticket_details);

                // print_r($ticket_details);
                // print_r($header['se']);die;

                $info['Source'] = $data->rest->Source;
                $info['BookingId'] = $header['se']['GetBookingResult']->BookingId;
                $info['BaggageInfo'] = $header['se']['GetBookingResult']->Ticket->WSTicket->SegmentAdditionalInfo->WSSegAdditionalInfo->Baggage;
                $info['PNR'] = $header['se']['GetBookingResult']->PNR;
                $info['Source'] = $data->rest->Source;
                $fare_obj = $header['se']['GetBookingResult']->Fare;
                $info['TotalFare'] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $info['FareBreakdown'] = json_encode($header['se']['GetBookingResult']->Fare);
                if (is_array($header['se']['GetBookingResult']->Passenger->WSPassenger)) {
                    $info['LeadTitle'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->Title;
                    $info['LeadFirstName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->FirstName;
                    $info['LeadLastName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->LastName;
                    $info['LeadGender'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->Gender;
                    $info['LeadEmail'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->Email;
                    $iter = 0;
                    foreach($header['se']['GetBookingResult']->Passenger->WSPassenger as $ws) {
                        $passengers['Title'][$iter] = $ws->Title;
                        $passengers['FirstName'][$iter] = $ws->FirstName;
                        $passengers['LastName'][$iter] = $ws->LastName;
                        $passengers['Gender'][$iter] = $ws->Gender;
                        $passengers['Type'][$iter] = $ws->Type;
                        $fare_obj = $ws->Fare;
                        $passengers['IndividualFare'][$iter] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                        $iter++;
                    }

                    $info['Title'] = implode(',', $passengers['Title']);
                    $info['FirstName'] = implode(',', $passengers['FirstName']);
                    $info['LastName'] = implode(',', $passengers['LastName']);
                    $info['Gender'] = implode(',', $passengers['Gender']);
                    $info['Type'] = implode(',', $passengers['Type']);
                    $info['IndividualFare'] = implode(',', $passengers['IndividualFare']);
                }
                else {
                    $info['LeadTitle'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Title;
                    $info['LeadFirstName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->FirstName;
                    $info['LeadLastName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->LastName;
                    $info['LeadGender'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Gender;
                    $info['LeadEmail'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Email;
                    $info['Title'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Title;
                    $info['FirstName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->FirstName;
                    $info['LastName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->LastName;
                    $info['Gender'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Gender;
                    $info['Type'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Type;
                    $fare_obj = $header['se']['GetBookingResult']->Passenger->WSPassenger->Fare;
                    $info['IndividualFare'] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                }

                $info['Origin'] = $header['se']['GetBookingResult']->Origin;
                $info['Destination'] = $header['se']['GetBookingResult']->Destination;
                if (is_array($header['se']['GetBookingResult']->Segment->WSSegment)) {
                    $r = 0;
                    foreach($header['se']['GetBookingResult']->Segment->WSSegment as $wss) {
                        $segment['ConnectingJourney'][$r] = $wss->Origin->CityCode;
                        $segment['ConnectingJourney'][$r + 1] = $wss->Destination->CityCode;
                        $segment['ConnectingDepTime'][$r] = $wss->DepTIme;
                        $segment['ConnectingArrTime'][$r] = $wss->ArrTime;
                        $segment['ConnectingAirlineName'] = $wss->Airline->AirlineName;
                        $segment['ConnectingAirlineCode'] = $wss->Airline->AirlineCode;
                        $segment['ConnectingCityName'][$r] = $wss->Origin->CityName;
                        $segment['ConnectingCityName'][$r + 1] = $wss->Destination->CityName;
                        $segment['ConnectingCityCode'][$r] = $wss->Origin->CityCode;
                        $segment['ConnectingCityCode'][$r + 1] = $wss->Destination->CityCode;
                        $r++;
                    }

                    $info['ConnectingJourney'] = implode(',', $segment['ConnectingJourney']);
                    $info['ConnectingDepTime'] = implode(',', $segment['ConnectingDepTime']);
                    $info['ConnectingArrTime'] = implode(',', $segment['ConnectingArrTime']);
                    $info['ConnectingAirlineName'] = $segment['ConnectingAirlineName'];
                    $info['ConnectingAirlineCode'] = $segment['ConnectingAirlineCode'];
                    $info['ConnectingCityName'] = implode(',', $segment['ConnectingCityName']);
                    $info['ConnectingCityCode'] = implode(',', $segment['ConnectingCityCode']);
                }
                else {
                    $info['ConnectingDepTime'] = $header['se']['GetBookingResult']->Segment->WSSegment->DepTIme;
                    $info['ConnectingArrTime'] = $header['se']['GetBookingResult']->Segment->WSSegment->ArrTime;
                    $info['ConnectingAirlineName'] = $header['se']['GetBookingResult']->Segment->WSSegment->Airline->AirlineName;
                    $info['ConnectingAirlineCode'] = $header['se']['GetBookingResult']->Segment->WSSegment->Airline->AirlineCode;
                    $info['OriginCityCode'] = $header['se']['GetBookingResult']->Segment->WSSegment->Origin->CityCode;
                    $info['OriginCityName'] = $header['se']['GetBookingResult']->Segment->WSSegment->Origin->CityName;
                    $info['DestinationCityCode'] = $header['se']['GetBookingResult']->Segment->WSSegment->Destination->CityCode;
                    $info['DestinationCityName'] = $header['se']['GetBookingResult']->Segment->WSSegment->Destination->CityName;
                }

                if (is_array($header['se']['GetBookingResult']->Ticket->WSTicket)) {
                    $t = 0;
                    foreach($header['se']['GetBookingResult']->Ticket->WSTicket as $wst) {
                        $tickets['TicketId'][$t] = $wst->TicketId;
                        $tickets['TicketNumber'][$t] = trim($wst->TicketNumber, ' ');
                        $tickets['IssueDate'][$t] = $wst->IssueDate;
                        $t++;
                    }

                    $info['TicketId'] = implode(',', $tickets['TicketId']);
                    $info['TicketNumber'] = implode(',', $tickets['TicketNumber']);
                    $info['IssueDate'] = implode(',', $tickets['IssueDate']);
                }
                else {
                    $info['TicketId'] = $header['se']['GetBookingResult']->Ticket->WSTicket->TicketId;
                    $info['TicketNumber'] = trim($header['se']['GetBookingResult']->Ticket->WSTicket->TicketNumber, ' ');
                    $info['IssueDate'] = $header['se']['GetBookingResult']->Ticket->WSTicket->IssueDate;
                }

                $info['PayuId'] = $_SESSION['payu_id'];
                $this->load->model('flight_model');
                $ticket_booking_id = $this->flight_model->updatePayuId($info['PayuId'], $header['se']['GetBookingResult']->BookingId);
                $ticket_booking_id[$f] = $this->flight_model->setTicketDetails($info);
            }
        }

        $this->ticket_details($ticket_booking_id);
    }

    function ticket_return()
    {

        for ($in_out = 0; $in_out < 2; $in_out++) {
            if ($in_out == 0) {
                $ticketid = $_SESSION['outbound'];
                $inout_ticketid[$in_out] = $ticketid;
            }
            else {
                $ticketid = $_SESSION['inbound'];
                $inout_ticketid[$in_out] = $ticketid;
            }

            $this->load->model('flight_model');
            $getPassengerDetailsObj = new GetPassengerDetails;

            $details = $this->flight_model->getticketDetails($ticketid);

            $det = $getPassengerDetailsObj->setPassengerDetails($details);
            $data = json_decode($details[0]->extra_info);

            /**
            * Object initialisation
            */
            $flightsAPIAuthObj = new FlightsAPIAuth;
            $flightsSearchRequestObj = new FlightsSearchRequest;
            $flightsSOAPObj = new FlightsSOAP;
            $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;
            $getReturnTicketDetailsObj = new GetReturnTicketDetails;

            /**
            * Set Request Authentication Data array
            * UserName
            * Password
            */
            $flightsAPIAuthObj->setUserId("redytrip");
            $flightsAPIAuthObj->setPassword("redytrip@12");
            $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

            $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
            $flightsSOAPObj->setSOAPClient();
            $flightsSOAPObj->setSOAPHeader($authDataArr);

            $ticket = $getReturnTicketDetailsObj->setReturnTicketDetails($data,$details,$in_out);

            $result = $flightsSOAPObj->makeSOAPCall("Ticket", $ticket);

            /*---------------------------------------------------------------*/
            if ($result->TicketResult->PNR != "") {
                $this->load->model('flight_model');
                $ret['pnr'] = $result->TicketResult->PNR;
                $ret['booking_id'] = $result->TicketResult->BookingId;
                $ret['status'] = "Successful";
                $this->flight_model->updateLccTicketStatus($ticketid, $ret);
                /*---get booking details*/

                $getReturnTicketBookingDetailsObj = new GetReturnTicketBookingDetails;
                $ticket_details = $getReturnTicketBookingDetailsObj->setReturnTicketBookingDetails($data,$ret);

                $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
                $flightsSOAPObj->setSOAPClient();
                $flightsSOAPObj->setSOAPHeader($authDataArr);

                $result = $flightsSOAPObj->makeSOAPCall("GetBooking", $ticket_details);

                $getReturnTicketInfoObj = new GetReturnTicketInfo;
                $info = $getReturnTicketInfoObj->setReturnTicketInfo($result,$data);
                
                $info['PayuId'] = $_SESSION['payu_id'];
                $this->load->model('flight_model');
                $ticket_booking_id = $this->flight_model->updatePayuId($info['PayuId'], $result->GetBookingResult->BookingId);
                echo "<pre>";
                $ticket_bookingid[$in_out] = $this->flight_model->setTicketDetails($info);
            }
        }
        $this->ticket_details($ticket_bookingid);
    }

    function guest_ticket()
    {
        $this->load->view('common/header.php');
        $this->load->view('flights/guest_dashboard.php');
        $this->load->view('common/footer.php');
    }

    function ticket_details_assorted($ticketid)
    {
        $this->load->model('flight_model');
        $i = 0;
        if (is_array($ticketid)) {
            for ($i = 0; $i < count($ticketid); $i++) {
                $data[$i] = $this->flight_model->fetchTicketDetailsById($ticketid[$i]);
            }
        }
        else {
            $data[$i] = $this->flight_model->fetchTicketDetailsById($ticketid);
        }
        $_SESSION['ticket_details'][] = $data;
        redirect('common/multiplex_tickets');
    }

    function ticket_details($ticketid, $guest_email = null)
    {
        //$_SESSION['currentUrlFlight'] = current_full_url();
        unset($_SESSION['currentUrlFlight']);
        unset($_SESSION['currentUrl']);
        $this->load->model('flight_model');
        $i = 0;
        if( !empty($guest_email) ){
            //here the ticket id is the booking id
            $data[$i] = $this->flight_model->fetchTicketDetails($ticketid);
        } else {
            if (is_array($ticketid)) {
                for ($i = 0; $i < count($ticketid); $i++) {
                    $data[$i] = $this->flight_model->fetchTicketDetailsById($ticketid[$i]);
                }
            } else {
                $data[0] = $this->flight_model->fetchTicketDetails($ticketid);
            }
        }

        if( isset($_SESSION['myTicketChk']) ){
            if( empty($data[0]) ){
                $errorMessage = array(
                    'errCode' => '1',
                    'errMessage' => "The Booking id you have entered is invalid. Please try again."
                );
                $this->load->view('common/header.php');
                $this->load->view('flights/multiway_tickets.php', $errorMessage);
                $this->load->view('common/footer.php');
            }else{
                if( $_SESSION['myTicketChk'] == 1 ){
                    $this->load->view('common/header.php');
                    $this->load->view('flights/multiway_tickets.php', array(
                        'data' => $data
                    ));
                    $this->load->view('common/footer.php');
                }
            }
        }else{
            foreach( $data as $d ){
                $departureArray = explode(',', $d[0]->ConnectingDepTime);
                if( is_array($departureArray) ){
                    $depature = date('d M Y', strtotime($departureArray[0]));
                }else{
                    $depature = date('d M Y', strtotime($departureArray));
                }
                $additionalInfo = array(
                    'searchId' => $d[0]->id,
                    'BookingId' => $d[0]->BookingId,
                    'PNR' => $d[0]->PNR,
                    'TotalFare' => $d[0]->TotalFare,
                    'Origin' => $d[0]->Origin,
                    'Destination' => $d[0]->Destination,
                    'ConnectingAirlineName' => $d[0]->ConnectingAirlineName,
                    'ConnectingDepTime' => $depature,
                );
                $this->send_email($d[0]->BookingId, $_SESSION['user_details'][0]->user_email, $_SESSION['user_details'][0]->user_first_name, $additionalInfo);
            }
            $this->load->view('common/header.php');
            $this->load->view('flights/multiway_tickets.php', array(
                'data' => $data
            ));
            $this->load->view('common/footer.php');
        }
    }

    function send_email($bid, $to_mail_id, $name, $additionalInfo){
            $link = site_url('api/flights/ticket_page?booking_id='.$bid);

            $to = $to_mail_id;
            //define the subject of the email
            $subject = 'Test email'; 
            //define the message to be sent. Each line should be separated with \n
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Thank you for choosing Farebucket.</p><p>Your ticket From: '.$additionalInfo["Origin"].' To: '.$additionalInfo["Destination"].' ('.$additionalInfo["ConnectingAirlineName"].')</p><p>Dated: '.$additionalInfo["ConnectingDepTime"].', Has been generated.</p><p>Your Booking ID: '.$additionalInfo["BookingId"].'</p><p>Farebucket Booking ID: '.$additionalInfo["searchId"].'</p><p>PNR: '.$additionalInfo["PNR"].'</p><p>Please find the link to your ticket(s) below.</p>Links: <a href="'.$link.'">Ticket</a> <hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>1234567890</strong> <br/>Email: <strong><a href="emailto:hseldon@trantor.com">admin@farebucket.com</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
            //define the headers we want passed. Note that they are separated with \r\n
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Admin <info@farebucket.com>" . "\r\n";
            //send the email
            $mail_sent = @mail( $to, $subject, $message, $headers );
    }

    function generate_ticket()
    {
        $info = array();
        $passengers = array();
        $segment = array();
        $tickets = array();
        $ticket = $this->input->get('ticket_id');
        $this->load->model('flight_model');
        $data = $this->flight_model->fetchTicketDetails($ticket);
        require_once (APPPATH . 'lib/nusoap.php');

        $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
        $headerpara = array();
        $headerpara["UserName"] = 'redytrip';
        $headerpara["Password"] = 'redytrip@12';
        $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array(
            $client_header
        ));
        $ticket_details = array();
        $ticket_details['GetBooking']['bookingRequest']['BookingId'] = "" . $data[0]->BookingId;
        $ticket_details['GetBooking']['bookingRequest']['Pnr'] = "" . $data[0]->PNR;
        $ticket_details['GetBooking']['bookingRequest']['Source'] = "" . $data[0]->Source;
        $ticket_details['GetBooking']['bookingRequest']['LastName'] = "" . $data[0]->LeadLastName;
        $ticket_details['GetBooking']['bookingRequest']['TicketId'] = 0;
        $header = array();
        $header['se'] = (array)$client->__call('GetBooking', $ticket_details);
        $info['BookingId'] = $header['se']['GetBookingResult']->BookingId;
        $info['PNR'] = $header['se']['GetBookingResult']->PNR;
        $fare_obj = $header['se']['GetBookingResult']->Fare;
        $info['TotalFare'] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
        $info['FareBreakdown'] = json_encode($header['se']['GetBookingResult']->Fare);
        if (is_array($header['se']['GetBookingResult']->Passenger->WSPassenger)) {
            $info['LeadTitle'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->Title;
            $info['LeadFirstName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->FirstName;
            $info['LeadLastName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->LastName;
            $info['LeadGender'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->Gender;
            $info['LeadEmail'] = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->Email;
            $iter = 0;
            foreach($header['se']['GetBookingResult']->Passenger->WSPassenger as $ws) {
                $passengers['Title'][$iter] = $ws->Title;
                $passengers['FirstName'][$iter] = $ws->FirstName;
                $passengers['LastName'][$iter] = $ws->LastName;
                $passengers['Gender'][$iter] = $ws->Gender;
                $fare_obj = $ws->Fare;
                $passengers['IndividualFare'][$iter] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                $iter++;
            }

            $info['Title'] = implode(',', $passengers['Title']);
            $info['FirstName'] = implode(',', $passengers['FirstName']);
            $info['LastName'] = implode(',', $passengers['LastName']);
            $info['Gender'] = implode(',', $passengers['Gender']);
            $info['IndividualFare'] = implode(',', $passengers['IndividualFare']);
        }
        else {
            $info['LeadTitle'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Title;
            $info['LeadFirstName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->FirstName;
            $info['LeadLastName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->LastName;
            $info['LeadGender'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Gender;
            $info['LeadEmail'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Email;
            $info['Title'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Title;
            $info['FirstName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->FirstName;
            $info['LastName'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->LastName;
            $info['Gender'] = $header['se']['GetBookingResult']->Passenger->WSPassenger->Gender;
            $fare_obj = $header['se']['GetBookingResult']->Passenger->WSPassenger[0]->Fare;
            $info['IndividualFare'] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
        }

        $info['Origin'] = $header['se']['GetBookingResult']->Origin;
        $info['Destination'] = $header['se']['GetBookingResult']->Destination;
        if (is_array($header['se']['GetBookingResult']->Segment->WSSegment)) {
            $r = 0;
            foreach($header['se']['GetBookingResult']->Segment->WSSegment as $wss) {
                $segment['ConnectingJourney'][$r] = $wss->Origin->CityCode;
                $segment['ConnectingJourney'][$r + 1] = $wss->Destination->CityCode;
                $segment['ConnectingDepTime'][$r] = $wss->DepTIme;
                $segment['ConnectingArrTime'][$r] = $wss->ArrTime;
                $r++;
            }

            $info['ConnectingJourney'] = implode(',', $segment['ConnectingJourney']);
            $info['ConnectingDepTime'] = implode(',', $segment['ConnectingDepTime']);
            $info['ConnectingArrTime'] = implode(',', $segment['ConnectingArrTime']);
        }
        else {
            $info['ConnectingDepTime'] = $header['se']['GetBookingResult']->Segment->WSSegment->DepTIme;
            $info['ConnectingArrTime'] = $header['se']['GetBookingResult']->Segment->WSSegment->ArrTime;
        }

        if (is_array($header['se']['GetBookingResult']->Ticket->WSTicket)) {
            $t = 0;
            foreach($header['se']['GetBookingResult']->Ticket->WSTicket as $wst) {
                $tickets['TicketId'][$t] = $wst->TicketId;
                $tickets['TicketNumber'][$t] = trim($wst->TicketNumber, ' ');
                $tickets['IssueDate'][$t] = $wst->IssueDate;
                $t++;
            }

            $info['TicketId'] = implode(',', $tickets['TicketId']);
            $info['TicketNumber'] = implode(',', $tickets['TicketNumber']);
            $info['IssueDate'] = implode(',', $tickets['IssueDate']);
        }
        else {
            $info['TicketId'] = $header['se']['GetBookingResult']->Ticket->WSTicket->TicketId;
            $info['TicketNumber'] = trim($header['se']['GetBookingResult']->Ticket->WSTicket->TicketNumber, ' ');
            $info['IssueDate'] = $header['se']['GetBookingResult']->Ticket->WSTicket->IssueDate;
        }

        $info['PayuId'] = $_SESSION['payu_id'];
        $this->load->model('flight_model');
        $ticket_booking_id = $this->flight_model->updatePayuId($info['PayuId'], $header['se']['GetBookingResult']->BookingId);
        $ticket_id = $this->flight_model->setTicketDetails($info);
    }

    // function get_booking()
    // {
    //   require_once (APPPATH . 'lib/nusoap.php');
    //   $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
    //   $headerpara = array();
    //   $headerpara["UserName"] = 'redytrip';
    //   $headerpara["Password"] = 'redytrip@12';
    //   $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
    //   $client = new SoapClient($wsdl);
    //   $client->__setSoapHeaders(array(
    //     $client_header
    //   ));
    //   $ticket_details = array();
    //   $ticket_details['GetBooking']['bookingRequest']['BookingId'] = "106267";
    //   $ticket_details['GetBooking']['bookingRequest']['Pnr'] = "6KO4XK";
    //   $ticket_details['GetBooking']['bookingRequest']['Source'] = "Amadeus";
    //   $ticket_details['GetBooking']['bookingRequest']['LastName'] = "subramanian";
    //   $ticket_details['GetBooking']['bookingRequest']['TicketId'] = 0;
    //   $header = array();
    //   $header['se'] = (array)$client->__call('GetBooking', $ticket_details);
    // }

    function cancel_request()
    {
        $ticketid = $this->input->post('ticket_id');
        $data['ticket_id'] = $ticketid;
        $this->load->model('flight_model');
        $result = $this->flight_model->cancelTicketById($ticketid);
        $today = date("Y-m-d H:i:s");
        $this->flight_model->saveCancellationDate($today, $ticketid);
        $sub_val = json_decode(stripslashes($result[0]->extra_info));
        $booking_id = $result[0]->booking_id;
        $pnr = $result[0]->pnr;

        $res = $this->flight_model->getTicketIdByBookingId($booking_id);

        if( empty($res) ){
            $val = $this->flight_model->updateTicketStatus($booking_id);
            echo json_encode("Failed");
            die;
        }

        require_once (APPPATH . 'lib/nusoap.php');
        $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
        $headerpara = array();
        $headerpara["UserName"] = 'redytrip';
        $headerpara["Password"] = 'redytrip@12';
        $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
        $client = new SoapClient($wsdl, array('trace' => 1));
        $client->__setSoapHeaders(array(
            $client_header
        ));

        $request = array();
        $request['SendChangeRequest']['request']['BookingId'] = "".$booking_id;
        $request['SendChangeRequest']['request']['RequestType'] = "Cancellation";
        if( is_array($res[0]->TicketId) ){
            foreach( $res[0]->TicketId as $tid ){
                $request['SendChangeRequest ']['request']['TicketId']['int'][] = intval($tid);
            }
        }else{
            $request['SendChangeRequest']['request']['TicketId']['int'][] = intval($res[0]->TicketId);
        }
        $request['SendChangeRequest']['request']['Remarks'] = "not feeling well";
        $request['SendChangeRequest']['request']['ChangeRequestModeComment'] = "NotSet";
        $request['SendChangeRequest']['request']['SubAgentID'] = 0;
        $request['SendChangeRequest']['request']['IsFullBookingCancel'] = true;
        // $request['SendChangeRequest']['request']['ChangeRequestModeComment'] = 1;
        $header = [];

        try{
            $header['se'] = (array)$client->__call('SendChangeRequest', $request);
        }catch(SoapFault $fault){
            echo json_encode(false);
            die;
        }

        if ($header['se']['SendChangeRequestResult']->WSSendChangeRequestResponse->Status->Description == "Successfull") {
            $changeRequestId['GetChangeRequestStatus']['request']['RequestId'] = $header['se']['SendChangeRequestResult']->WSSendChangeRequestResponse->ChangeRequestId;
            $changeReqResult = (array)$client->__call('GetChangeRequestStatus', $changeRequestId);

            if( isset($changeReqResult['GetChangeRequestStatusResult']->Status) && $changeReqResult['GetChangeRequestStatusResult']->Status->Description == 'Sucessfull' ){
                $refundAmt = $changeReqResult['GetChangeRequestStatusResult']->RefundedAmount;
                $canlAmt = $changeReqResult['GetChangeRequestStatusResult']->CancellationCharge;
            }
            $this->flight_model->updateRefundAndCanlAmt($ticketid, $refundAmt, $canlAmt);
            $val = $this->flight_model->updateTicketStatus($ticketid);
        }
        $this->send_cancellation_email( $_SESSION['user_details'][0]->user_email, $pnr, $_SESSION['user_details'][0]->user_first_name );
        //send the refund and cancellation amt once they appear here. For now only success or failure is being sent.
        echo json_encode($header['se']['SendChangeRequestResult']->WSSendChangeRequestResponse->Status->Description);
    }

    //later, a function which handles all emails can be written. Switch cases for different senarios must be used.
    function send_cancellation_email($email_id, $pnr, $name){
            $to = $email_id;
            //define the subject of the email
            $subject = 'Cancellation email'; 
            //define the message to be sent. Each line should be separated with \n
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Your ticket with PNR '.$pnr.', Has now been cancelled. The refund amount, if any, will be credited back to you in 7-8 working days. Please contact customer support at Phone num: +91-1234567890, Email ID: admin@farebucket.com for any further assistance.</p> <hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>1234567890</strong> <br/>Email: <strong><a href="emailto:hseldon@trantor.com">admin@farebucket.com</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
            //define the headers we want passed. Note that they are separated with \r\n
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Admin <info@farebucket.com>" . "\r\n";
            //send the email
            $mail_sent = @mail( $to, $subject, $message, $headers );
    }

    function guest_flights_ticket()
    {
        $this->load->view('common/header.php');
        $this->load->view('flights/guest_page.php');
        $this->load->view('common/footer.php');
    }

    function get_ticket_response()
    {
        require_once (APPPATH . 'lib/nusoap.php');

        $wsdl = "http://api.tektravels.com/tboapi_v7/service.asmx?wsdl";
        $headerpara = array();
        $headerpara["UserName"] = 'redytrip';
        $headerpara["Password"] = 'redytrip@12';
        $client_header = new SoapHeader('http://192.168.0.170/TT/BookingAPI', 'AuthenticationData', $headerpara, false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array(
            $client_header
        ));
        $ticket_response = array();
        $ticket_response["GetTicketResponse"]["SessionId"] = "f8fe49f7-5977-4675-a216-5b5d8cdaf651";
        $header = array();
        $header['se'] = (array)$client->__call('GetTicketResponse', $ticket_response);
    }

    function guest_details()
    {
        unset($_SESSION['invalidGuestErrorMessage']);
        $guest_email = $this->input->get('guest_email');
        $guest_ticket_id = $this->input->get('ticket_id');
        $this->load->model('flight_model');
        $guest_id = $this->flight_model->validate_guest($guest_email);
        if ($guest_id) {
            $_SESSION['myTicketChk'] = 1;
            $this->ticket_details($guest_ticket_id, $guest_email);
        }
        else {
            $_SESSION['invalidGuestErrorMessage'] = '<div class="select-error"><h6 style="text-align:center;">Invalid User id or Ticket id.</h6></div>';
            $this->guest_ticket();
        }
    }

    function ticket_page()
    {
        $bookingid = $this->input->get('booking_id');
        $this->load->model('flight_model');
        $data = $this->flight_model->fetchTicketDetails($bookingid);
        $data1 = $this->flight_model->getTicketByBookingID($bookingid);
        $this->load->view('flights/invoice_page.php', array(
            'data' => $data,
            'data1' => $data1
        ));
    }
}

?>