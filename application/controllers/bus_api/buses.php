<?php  @session_start();
require_once (APPPATH . 'lib/OAuthStore.php');
require_once (APPPATH . 'lib/OAuthRequester.php');
class Buses extends MY_Controller
{
    function dummy()
    {
        $this->load->view('common/header.php');
        $this->load->model('admin/convenience_model');
        $convenience = $this->convenience_model->get_convenience_charge('buses');
        $data['convenience_charge'] = $convenience->convenience_charge;
        $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
        $this->load->view('buses/bus_travellers_details.php',array('data' => $data));
        $this->load->view('common/footer.php');
    }

    function invokeGetRequest($requestUrl)
    {
        global $key, $secret, $base_url, $source, $destination, $doj, $tripId, $boarding;
        $key = "9r5c0GbdlNmJ4BYDxsQYkP4mcpAh7G";
        $secret = "Uf8ZsgFEu2rRLvsJ01bZYvP3S3rsjy";
        $base_url = "http://api.seatseller.travel/";
        $url = $base_url . $requestUrl;
        $curl_options = array(
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ) ,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT => 0
        );
        $options = array(
            'consumer_key' => $key,
            'consumer_secret' => $secret
        );
        OAuthStore::instance("2Leg", $options);
        $method = "GET";
        $params = null;
        try {
            $request = new OAuthRequester($url, $method, $params);
            $result = $request->doRequest();
            $response = $result['body'];
            if ($_SESSION['type'] == 1){
                echo json_encode($response);
            } else {
                return json_decode($response, true);
            }
        }

        catch(OAuthException2 $e) {
            return $e->getMessage();
        }

        catch(Exception $e1) {
            echo "generic exception" . $e1 . "<hr></br>";
        }
    }

    function invokePostRequest($requestUrl, $blockRequest)
    {
        require_once (APPPATH . 'lib/OAuthStore.php');
        require_once (APPPATH . 'lib/OAuthRequester.php');

        global $key, $secret, $base_url;
        $key = "9r5c0GbdlNmJ4BYDxsQYkP4mcpAh7G";
        $secret = "Uf8ZsgFEu2rRLvsJ01bZYvP3S3rsjy";
        $base_url = "http://api.seatseller.travel/";
        $url = $base_url . $requestUrl;
        $curl_options = array(
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ) ,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT => 0
        );
        $options = array(
            'consumer_key' => $key,
            'consumer_secret' => $secret
        );
        OAuthStore::instance("2Leg", $options);
        $method = "POST";
        $params = null;
        try {
            $request = new OAuthRequester($url, $method, $params, $blockRequest);
            $result = $request->doRequest(0, $curl_options);
            $response = $result['body'];
            return $response;
        }

        catch(OAuthException2 $e) {
            return $e->getMessage();
        }

        catch(Exception $e1) {
            echo "generic exception" . $e1 . "<hr></br>";
        }
    }

    function flights_to_buses(){
        if( isset($_SESSION['multiTravelData']['busData']) ){
            if( isset( $_POST['data']['flight_num'] ) && isset($_SESSION['multiTravelData']['busData']['flight_num']) && $_POST['data']['flight_num'] == $_SESSION['multiTravelData']['busData']['flight_num'] ){
                $number = $_POST['data']['flight_num'] - 1;
                if(isset($_SESSION['details'])){
                    $src_name = explode(',', $_SESSION['details']['from'][$number]);
                    $dest_name = explode(',', $_SESSION['details']['to'][$number]);
                    if($src_name === $_SESSION['multiTravelData']['busData']['source'] && $dest_name === $_SESSION['multiTravelData']['busData']['destination']){
                        echo json_encode($_SESSION['multiTravelData']['busData']);
                        die;
                    }
                }
            }
        }

        $data = $this->input->post('data');
        if( $data['source'] == "Madras" )$data['source'] = "Chennai";
        if( $data['destination'] == "Madras" )$data['destination'] = "Chennai";

        if( $data['source'] == "Delhi /NCR" )$data['source'] = "Delhi";
        if( $data['destination'] == "Delhi /NCR" )$data['destination'] = "Delhi";


        $ret_arr = array();
        $found_source_flag = $found_dest_flag = '';
        $_SESSION['type'] = 2;
        $source = $this->invokeGetRequest("sources");

        foreach( $source['cities'] as $c ){
            if( $c['name'] == $data['source']){
                $found_source_flag = $c['id'];
            }
        }

        if( $found_source_flag != '' ){
            $dest = $this->invokeGetRequest("destinations?source=" . $found_source_flag);
            foreach( $dest['cities'] as $c ){
                if( $c['name'] == $data['destination']){
                    $found_dest_flag = $c['id'];
                }
            }
        }

        if( $found_dest_flag != '' ){
            $min_price = $this->getMinPriceForTrip( $found_source_flag, $found_dest_flag, date('Y-m-d', strtotime($data['journey_date'])) );
            if($min_price == "" || $min_price == 99999999){
                $ret_arr['err'] = true;
                echo json_encode($ret_arr); 
            }
            else{
                $ret_arr['source'] = $data['source'];
                $ret_arr['source_id'] = $found_source_flag;
                $ret_arr['destination'] = $data['destination'];
                $ret_arr['destination_id'] = $found_dest_flag;
                $ret_arr['date'] = date('Y-m-d', strtotime($data['journey_date']));
                $ret_arr['min_price'] = $min_price;
                $ret_arr['err'] = false;
                if( isset($data['flight_num']) ){
                    $ret_arr['flight_num'] = $data['flight_num'];
                }
                $_SESSION['multiTravelData']['busData'] = $ret_arr;
                echo json_encode($ret_arr);
            }
        }else{
            $ret_arr['err'] = true;
            echo json_encode($ret_arr);
        }
    }

    function getMinPriceForTrip($sourceId, $destinationId, $date){
        $data = $this->invokeGetRequest("availabletrips?source=" . $sourceId . "&destination=" . $destinationId . "&doj=" . $date);
        
        if( $data != null ){
            if( isset($data['availableTrips']) ){
                foreach( $data['availableTrips'] as $d_key => $d_val ){
                    if( !is_int($d_key) ){
                        $send_arr['availableTrips'][0] = $data['availableTrips'];
                        unset($data);
                        $data = $send_arr;
                        break;
                    }
                }

                foreach($data['availableTrips'] as $trips) {
                    $travels_name[] = $trips['travels'];
                    $departure_times[] = $trips['departureTime'];
                    if (is_array($trips['fares'])) {
                        $fares[] = $trips['fares'][0];
                    }
                    else {
                        $fares[] = $trips['fares'];
                    }
                }
            }
            else{
                return false;
            }
            $min_fare = 99999999;
            foreach($fares as $fa) {
                if ($fa <= $min_fare) {
                    $min_fare = $fa;
                }
            }

            return $min_fare;
        }else{
            return false;
        }
    }

    function getAllSources()
    {
        $_SESSION['type'] = 1;
        $data = $this->invokeGetRequest("sources"); 
        $_SESSION['type'] = 2;
        return $data;
    }

    function getAllDestinations()
    {
        $_SESSION['type'] = 1;
        $data = $this->invokeGetRequest("destinations?source=" . $this->input->post('source_id'));
        $_SESSION['type'] = 2;
        return $data;
    }

    function getAvailableTrips()
    {
        $_SESSION['calling_controller_name'] = "buses";
        $_SESSION['currentUrl'] = current_full_url();
        $_SESSION['currentUrlBus'] = current_full_url();
        $_SESSION['type'] = 2;
        $sourceId = $this->input->get('source_id');
        $destinationId = $this->input->get('destination_id');
        if( isset($_GET['journey_date_mod']) ){
            $date = $this->input->get('journey_date_mod');
        }else{
            $date = $this->input->get('journey_date');
        }
        $data = $this->invokeGetRequest("availabletrips?source=" . $sourceId . "&destination=" . $destinationId . "&doj=" . $date);
        
        if( !isset($data) || ($data == null || empty($data)) ){
            redirect('bus_api/buses/no_trip?journey_date='.$date);
        }

        foreach( $data['availableTrips'] as $d_key => $d_val ){
            if( !is_int($d_key) ){
                $send_arr['availableTrips'][0] = $data['availableTrips'];
                unset($data);
                $data = $send_arr;
                break;
            }
        }

        foreach($data['availableTrips'] as $trips) {
            $travels_name[] = $trips['travels'];
            $departure_times[] = $trips['departureTime'];
            if (is_array($trips['fares'])) {
                $fares[] = $trips['fares'][0];
            }
            else {
                $fares[] = $trips['fares'];
            }
        }

        $max_fare = 0;
        $min_fare = 99999999;
        $max_time = 0;
        $min_time = 1440;
        foreach($fares as $fa) {
            if ($fa >= $max_fare) {
                $max_fare = $fa;
            }

            if ($fa <= $min_fare) {
                $min_fare = $fa;
            }
        }

        foreach($departure_times as $dp) {
            if ($dp > $max_time) {
                $max_time = $dp;
            }
            
            if ($dp < $min_time) {
                $min_time = $dp;
            }
        }

        $tempor = array_unique($travels_name);
        foreach($tempor as $tem) {
            if ($tem != '' || $tem != null) {
                $travels[] = $tem;
            }
        }
        sort($travels);
        
        if( ($max_time+10) <= 1440 ){
            $max_time = $max_time + 10;
        }else{
            $max_time = 1440;
        }
        $min_time = $min_time - 10;

        $filter_data = array(
            'travels' => $travels,
            'departure_times' => $departure_times,
            'max_time' => $max_time,
            'min_time' => $min_time,
            'max_fare' => $max_fare,
            'min_fare' => $min_fare,
        );

        $this->load->view("common/header.php");
        $this->load->view("buses/display_buses.php", array(
            'data' => $data,
            'filter_data' => $filter_data
        ));
        $this->load->view("common/footer.php");
    }

    function no_trip(){
        $date = $_GET['journey_date'];
        $today = date("Y-m-d");
        $date = new DateTime($date);
        $today = new DateTime($today);
        $diff = $today->diff($date);
        if($diff->m >= 8){
            $msg = array(
                'data' => "Sorry, there are no buses available for this date."
            );
        }
        else{
            $msg = array(
                'data' => "Sorry, No matching records found for your search"
            );
        }

        $this->load->view('common/header.php');
        $this->load->view('buses/failure_page.php', $msg);
        $this->load->view('common/footer.php');
    }

     function getTripDetails()
    {
        $boarding = json_decode($_POST['boardingpnts']);
        $cancellationPolicy = $_POST['cancellation'];
        unset($_POST['cancellation']);
        $_SESSION['cancellationPolicy'] = $cancellationPolicy;
        $droppingpnts = $this->input->post('droppingpnts');
        $droppingpnts = json_decode($droppingpnts);
        unset($_POST['droppingpnts']);
        $_SESSION['droppingpnts'] = $droppingpnts;
        $chosenone = $this->input->post('chosenone');

        $session_data = $_SESSION;
        $data = $this->invokeGetRequest("tripdetails?id=" .$chosenone );

        //resets all session data, it becomes necessary to input data again.
        $_SESSION = $data;
        $_SESSION['currentUrl'] = current_full_url();
        $_SESSION['cancellationPolicy'] = $cancellationPolicy;

        if( isset($session_data) ){
            $_SESSION = $session_data;
            $_SESSION['seats'] = $data['seats'];
        }

        $var = $this->input->post(null, true);
        $var['boardingpnts'] = json_decode($var['boardingpnts'], true);
        $_SESSION['boardingpnts'] = $var['boardingpnts'];
        unset($var['boardingpnts']);
        $data['all_data'] = $var;
        $_SESSION['type'] = 2;
        $url = '?';
        $i = 0;
        foreach ($var as $v_key => $v_val) {
            if( $i == 0 ){
                $url .= $v_key."=".$v_val;
            }else{
                $url .= "&".$v_key."=".$v_val;
            }
            $i++;
        }
        if( !isset($_SESSION['details']) && empty($_SESSION['details']['total_count']) ){
            $_SESSION['details']['total_count'] = 9;
        }
        redirect('bus_api/buses/seatSelection'.$url);
    }

    function seatSelection(){
        $_SESSION['currentUrl'] = current_full_url();
        $_SESSION['calling_controller_name'] = "buses";
        $data = $_POST;
        $this->load->view('common/header.php');
        $this->load->view('buses/buses_seat_selection.php', array('data' => $data));
        $this->load->view('common/footer.php');
    }

    function selected_seats()
    {
        $_SESSION['currentTravellerUrl'] = current_full_url();
        $data = $this->input->get(null, true);
        $data['journey_details'] = json_decode($this->input->post('journey_details') , true);
        $data['seat_details'] = json_decode($this->input->post('seat_details') , true);
        $this->load->model('admin/convenience_model');
        $convenience = $this->convenience_model->get_convenience_charge('buses');
        $data['convenience_charge'] = $convenience->convenience_charge;
        $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
        $this->load->view('common/header.php');
        $this->load->view('buses/bus_travellers_details.php', array(
            'data' => $data
        ));
        $this->load->view('common/footer.php');
    }

    function blockTicket()
    {
        unset($_SESSION['droppingpnts']);
        unset($_SESSION['boardingpnts']);
        unset($_SESSION['seats']);
        $json = array();
        $user_name = array();
        $user_gender = array();
        $user_age = array();
        $user_primary = array();
        $user_title = array();
        $inventoryItems = array(
            array()
        );
        $passenger = array(
            array()
        );
        $data = $this->input->get(null, true);
        $_SESSION['bus_get_data'] = $data;
        $data['info'] = $_SESSION['info'];
		
		if(isset($_SESSION['flight_data'])){

        	$flights_len = count($_SESSION['flight_data']);
        	$var = 0;
    		
        	if(isset($_SESSION['ovMode']) && isset($_SESSION['ov_set'])){	
            	for( $i = 0 ; $i < $flights_len ; $i++ ){
            		if( isset($_SESSION['flight_data'][$i]['ov']) ){
            			if( $_SESSION['flight_data'][$i]['ov']->mode == 'flight' && $_SESSION['ovMode']->mode == 'flight' ){
            				if( $_SESSION['flight_data'][$i]['ov']->travel_date == $_SESSION['ovMode']->travel_date ){
            					unset($_SESSION['ovMode']);
            				}else{
            					$var = 1;
            					$_SESSION['ovSet'] = 1;
            				}
            			}
            			}else{
            				$var = 1;
            				$_SESSION['ovSet'] = 1;
            			}
            		}
                	unset($_SESSION['ov_set']);
                }
            else{
            	unset($_SESSION['ovMode']);
            }
            	
            if($var == 1){
            	$flights_len += 1;
        	  }
        }

        $chosenbusid = $data['info']['chosenbus'];
        $sourceid = $data['info']['chosensource'];
        $destinationid = $data['info']['chosendestination'];
        $boardingpointid = $data['info']['droppingpnts'];
        if( isset($data['discountCode']) ){
            $_SESSION['discountCode'] = $data['discountCode'];
        }
        if( isset($data['discountValue']) ){
            $_SESSION['discountValue'] = $data['discountValue'];
        }
        $checkbox_no = count($data['info']['chkchk']);
        for ($i = 0; $i < $checkbox_no; $i++) {
            $user_name[$i] = 'test_'.$_GET['fname' . $i . ''];
            $user_gender[$i] = $_GET['sex' . $i . ''];
            if( isset( $_GET['sending_type'] ) && $_GET['sending_type'] == 'assorted' ){
                $user_age_raw = explode('-', $_GET['age' . $i . '']);
                $user_age[$i] = $user_age_raw[0];
            }else{
                $user_age[$i] = $_GET['age' . $i . ''];
            }
            $user_title[$i] = $_GET['Title' . $i . ''];
        }

        if( isset($_GET['phone_no']) ){
            $user_mobile = $_GET['phone_no'];
        }else{
            $user_mobile = $_GET['mobile'];
        }
        if( isset($_GET['address']) ){
            $user_address = $_GET['address'];
        }else{
            $user_address = $_GET['addressPickup'];
        }
        $user_email = $_GET['email_id'];
        $user_id_no = $_GET['id_no'];
        $user_idproof_type = $_GET['id_proof'];
        for ($i = 0; $i < $checkbox_no; $i++) {
            if ($i == 0) {
                $user_primary[$i] = 'true';
            }
            else {
                $user_primary[$i] = 'false';
            }
        }

        $tripdetails2 = $_SESSION['seat_details'];
        for ($i = 0; $i < count($tripdetails2['seats']); $i++) {
            $tripdetails2['seats'][$i] = (object)$tripdetails2['seats'][$i];
        }

        $tripdetails2 = (object)$tripdetails2;
        $seatschosen = $data['info']['seatnames'];
        $seats = explode(",", $seatschosen);
        for ($i = 0; $i < $checkbox_no; $i++) {
            foreach($tripdetails2 as $key => $value) {
                if (is_array($value)) {
                    foreach($value as $k => $v) {
                        foreach($v as $k1 => $v1) {
                            if (isset($v->name)) {
                                if (!strcmp($v->name, $seats[$i])) {
                                    $passenger[$i]['age'] = $user_age[$i];
                                    $passenger[$i]['primary'] = $user_primary[$i];
                                    $passenger[$i]['name'] = $user_name[$i];
                                    $passenger[$i]['title'] = $user_title[$i];
                                    $passenger[$i]['gender'] = $user_gender[$i];
                                    if ($i == 0) {
                                        $passenger[$i]['idType'] = $user_idproof_type;
                                        $passenger[$i]['email'] = $user_email;
                                        $passenger[$i]['idNumber'] = $user_id_no;
                                        $passenger[$i]['address'] = $user_address;
                                        $passenger[$i]['mobile'] = $user_mobile;
                                    }

                                    $inventoryItems[$i]['seatName'] = $v->name;
                                    $inventoryItems[$i]['ladiesSeat'] = $v->ladiesSeat;
                                    $inventoryItems[$i]['passenger'] = $passenger[$i];
                                    $inventoryItems[$i]['fare'] = $v->fare;
                                }
                            }
                        }
                    }
                }
            }
        }

        $json['availableTripId'] = $chosenbusid;
        $json['boardingPointId'] = $boardingpointid;
        $json['destination'] = $destinationid;
        $json['inventoryItems'] = $inventoryItems;
        $json['source'] = $sourceid;
        $request = json_encode($json);

        $result = $this->invokePostRequest("blockTicket", $request);

        if( $result == 'Request failed with code 500: Error: Seat is no longer available.' || $result == 'Request failed with code 500: Error: Invalid seat or seat fare in booking request.' ){
            redirect('bus_api/buses/booking_failed?seatUnavailable=true');
        }

        $fbBooking = 'TSBU';
        $this->load->model('bus_model');
        $returnId = $this->bus_model->getLastFbBookingId();
        if($returnId == 0){
            $randomNum = 1;
        }
        else{
            $firstFourLetters = substr($returnId, 0, 4);
            $splitReturnID = explode($firstFourLetters, $returnId);
            $randomNum = intval($splitReturnID[1]) + 1;
        } 
        
        $randomNum = sprintf("%06d",$randomNum);
        $fbBookingId = $fbBooking . $randomNum;
        $_SESSION['busBookingId'] = $fbBookingId;

        if( isset($_SESSION['user_details']['user_first_name']) && $_SESSION['user_details']['user_first_name'] == 'guest' ){
            $store_data = array(
                'all_details' => $request,
                'user_id' => $_SESSION['user_details']['user_id'],
                'user_email' => $_SESSION['user_details']['user_email'],
                'source' => $_SESSION['journey_details']['source'],
                'destination' => $_SESSION['journey_details']['destination'],
                'status' => 'pending',
                'doj' => $_SESSION['journey_details']['boardingpnts']['doj'],
                'fb_bookingId' => $_SESSION['busBookingId'],
                'offered_price' => (isset($_SESSION['bus_db_fare_params']['bus_offered_price'])) ? $_SESSION['bus_db_fare_params']['bus_offered_price'] : "",
                'published_price' => (isset($_SESSION['bus_db_fare_params']['bus_published_price'])) ? $_SESSION['bus_db_fare_params']['bus_published_price'] : "",
                'convenience_charge' => (isset($_SESSION['bus_db_fare_params']['bus_convenience_charge'])) ? $_SESSION['bus_db_fare_params']['bus_convenience_charge'] : "",
                'total_fare' => (isset($_SESSION['bus_db_fare_params']["bus_total_Fare"])) ? $_SESSION['bus_db_fare_params']["bus_total_Fare"] : "",
                'cancellationPolicy' => $_SESSION['cancellationPolicy'],
                'discountCode' => (isset($data['discountCode'])) ? $data['discountCode'] : "",
                'discountValue' => (isset($data['discountValue'])) ? $data['discountValue'] : "",
            );
        }else{
            $store_data = array(
                'all_details' => $request,
                'user_id' => $_SESSION['user_details'][0]->user_id,
                'user_email' => $_SESSION['user_details'][0]->user_email,
                'source' => $_SESSION['journey_details']['source'],
                'destination' => $_SESSION['journey_details']['destination'],
                'status' => 'pending',
                'doj' => $_SESSION['journey_details']['boardingpnts']['doj'],
                'fb_bookingId' => $_SESSION['busBookingId'],
                'cancellationPolicy' => $_SESSION['cancellationPolicy'],
                'discountCode' => (isset($data['discountCode'])) ? $data['discountCode'] : "",
                'discountValue' => (isset($data['discountValue'])) ? $data['discountValue'] : "",
            );
        }

        $bookingResponse = $this->bus_model->storeBooking($store_data);

        if( isset( $_GET['sending_type'] ) && $_GET['sending_type'] == 'assorted' ){
            if( $result ){
                redirect('common/store_block_id?resp='.$result.'&call_func=bus');
            }else{
                redirect('bus_api/buses/booking_failed');
            }
        }else{
            if( $result == 'Request failed with code 500: Error: Seat is no longer available.' || $result == 'Request failed with code 500: Error: Invalid seat or seat fare in booking request.' ){
                $this->bus_model->updateBookingStatus($bookingResponse, 'failed');
                redirect('bus_api/buses/booking_failed?seatUnavailable=true');
            }else{
                $this->bus_model->updateBookingStatus($bookingResponse, 'success');
                redirect('buses/payment_gateway?resp='.$result);
            }
        }
    }

    function intermediate_page(){
        $resp = $this->input->get('resp');
        $this->load->view('common/header.php');
        $this->load->view('buses/inter_page.php', array('resp' => $resp));
        $this->load->view('common/footer.php');
    }

    function store_data(){
        // ticket confirmation is also done here!
        $result = $_SESSION['bus_get_data']['blockTicketResponse'];
        $resp = $this->confirmTicket($result);
        $ret_data = $this->getTicket($resp);

        $data = array(
            'all_details' => json_encode($ret_data),
            'user_id' => $_SESSION['user_details'][0]->user_id,
            'user_email' => $_SESSION['user_details'][0]->user_email,
            'source' => $_SESSION['journey_details']['source'],
            'destination' => $_SESSION['journey_details']['destination'],
            'status' => 'pending',
            'doj' => $ret_data['doj'],
            'fb_bookingId' => $_SESSION['busBookingId'], 
            'offered_price' => $_SESSION['bus_db_fare_params']['bus_offered_price'],
            'published_price' => $_SESSION['bus_db_fare_params']['bus_published_price'],
            'convenience_charge' => $_SESSION['bus_db_fare_params']['bus_convenience_charge'],
            'total_fare' => $_SESSION['bus_db_fare_params']["bus_total_Fare"],
            'cancellationPolicy' => $_SESSION['cancellationPolicy'],
            'discountCode' => $_SESSION['discountCode'],
            'discountValue' => $_SESSION['discountValue'],   
        );
        $this->load->model('bus_model');
        $this->bus_model->putBusesBookResult($data);

        intval($ret_data['pickupTime']);
        $oneDay=24*60;
        $time = ($ret_data['pickupTime']) % $oneDay;
        $hours = floor($time/60);
        $minutes = floor($time%60);
        
        $additionalInfo = array(
            'TIN' => $ret_data['tin'],
            'Origin' => $_SESSION['journey_details']['source'],
            'Destination' => $_SESSION['journey_details']['destination'],
            'BookingId' => $resp,
            'pickUpLocationAddress' => $ret_data['pickUpLocationAddress'],
            'pickupTime' => $hours . "h " . $minutes . "m",
            'doj' => date('D, jS M Y', strtotime($ret_data['doj'])),
        );

        $mail_chk = $this->send_email($_SESSION['user_details'][0]->user_email, $_SESSION['busBookingId'], $_SESSION['user_details'][0]->user_first_name, $additionalInfo);
        redirect('bus_api/buses/ticket_page?busBookingId='.$_SESSION['busBookingId']);
    }

    function ticket_page(){
        if( isset($_GET['ticket_id']) ){
            $busBookingId = $this->input->get('ticket_id');
            $user_email = $this->input->get('guest_email');
        }else{
            $busBookingId = $this->input->get('busBookingId');
        }
        if(isset($_SESSION['busPayId'])){
            $busPayUid = $_SESSION['busPayId'];
            $this->load->model('bus_model');
            $this->bus_model->updatePayuId($busPayUid, $busBookingId);
        }
        if( isset($_GET['ticket_id']) ){
            $ret_data = $this->bus_model->getBusesBookResult($busBookingId, $user_email);
        }else{
            $ret_data = $this->bus_model->getBusesBookResultByFBID($busBookingId);
        }
        $_SESSION['cancelPolicy'] = $ret_data->cancellationPolicy;
        $ret_data->all_details = json_decode($ret_data->all_details);
        $fbBusId = $ret_data->fb_bookingId;
        $this->load->view('common/header.php');
        $this->load->view('buses/ticket_link_page.php', array('data' => $ret_data, 'fbBusId' => $fbBusId, ));
        $this->load->view('common/footer.php');
    }

    public function send_email($email_id, $hash_val, $name, $additionalInfo){
            $to = $email_id;
            $cust_support_data = cust_support_helper();
            //define the subject of the email
            $subject = 'Bus tickets - Farebucket'; 
            //define the message to be sent. Each line should be separated with \n            
            $link = site_url('bus_api/buses/show_ticket?booking_number='.$hash_val);
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Thank you for choosing Farebucket.</p><p>Your ticket From: '.$additionalInfo["Origin"].' To: '.$additionalInfo["Destination"].' (Pick Up Location: '.$additionalInfo["pickUpLocationAddress"].')</p><p>Dated: '.$additionalInfo["doj"].', Has been generated.</p><p>Your Booking ID: '.$additionalInfo["BookingId"].'</p><p>Farebucket Booking ID: '.$hash_val.'</p><p>TIN: '.$additionalInfo["TIN"].'</p><p>Please find the link to your ticket(s) below.</p>Links: <a href="'.$link.'">Ticket</a> <hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>'.$cust_support_data->phone_number.'</strong> <br/>Email: <strong><a href="emailto:'.$cust_support_data->email.'">'.$cust_support_data->email.'</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
            //define the headers we want passed. Note that they are separated with \r\n
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Admin <info@farebucket.com>" . "\r\n";
            $headers .= "Cc: Support <support@farebucket.com>" . "\r\n";
            //send the email
            $mail_sent = @mail( $to, $subject, $message, $headers );
    }


    function show_ticket(){
        $hash_val = $this->input->get('booking_number');
        $this->load->model('bus_model');
        $ret_data = $this->bus_model->getBusesBookResultByFBID($hash_val);
        $data = json_decode($ret_data->all_details);
        $_SESSION['cancelPolicy'] = $ret_data->cancellationPolicy;
        $fbBusId = $ret_data->fb_bookingId;
        $this->load->view('buses/invoice_page.php', array('data' => $data, 'hash_val' => $hash_val, 'fbBusId' => $fbBusId, 'source' => $ret_data->source, 'destination' => $ret_data->destination));
    }

    function booking_failed(){
        $this->load->view('common/header.php');
        $this->load->view('buses/failure_page.php');
        $this->load->view('common/footer.php');
    }

    function confirmTicket($blockKey)
    {
        return $this->invokePostRequest("bookticket?blockKey=" . $blockKey, "");
    }

    function getTicket($ticketId)
    {
        $resp = $this->invokeGetRequest("ticket?tin=" . $ticketId);
        return $resp;
    }

    function getCancellationData($cancellationId)
    {
        return invokeGetRequest("cancellationdata?tin=" . $cancellationId);
        echo " <hr>The ticket details are:" . $cancellationId . "<hr/>";
    }

    function cancelTicket($cancelRequest = null)
    { 
        if( $cancelRequest == null ){
            $data = $this->input->post(null, true);
            $fb_booking_id = $data['fb_bookingId'];
            $today = date("Y-m-d H:i:s");

            $this->load->model('bus_model');
            $this->bus_model->saveCancellationDate($today, $fb_booking_id);

            $json_req = array();            

            $json_req['tin'] = $data['tin'];
            $json_req['seatsToCancel'] = explode(',', $data['seat_name_csv']);

            $cancelRequest = json_encode($json_req);
            $ret = $this->invokePostRequest("cancelticket", $cancelRequest);

            if( $ret === 'Request failed with code 500: Error: Ticket is already cancelled.' ){
                $resp = explode('Error:', $ret);
                $message = $resp[count($resp)-1];
                $this->load->model('bus_model');
                $this->bus_model->updateTicketStatus($data['fb_bookingId']);
            }else{
                // after response is properly formatted. send mail and return.
                $resp = explode('</br>', $ret);
                $message = json_decode($resp[count($resp)-1]);
            }

            if( isset($message->cancellationCharge) ){
                $success = "Ticket has been cancelled successfully.";
                $cust_support_data = cust_support_helper();
                $mail_chk = $this->send_cancellation_email($data, $_SESSION['user_details'][0]->user_first_name, $cust_support_data);
                $this->bus_model->updateTicketStatus($data['fb_bookingId']);
                $this->bus_model->updateRefundAmtAndCanlAmt( $data['fb_bookingId'], $data['refundAmt'], $data['canlAmt'] );
                echo json_encode($success);
            }else{
                echo json_encode($message);
            }
        }else{
            return invokePostRequest("cancelticket", $cancelRequest);
        }
        
    }

    public function send_cancellation_email($cancelRequest, $name, $cust_support_data){
            $to = $cancelRequest['email_id'];
            //define the subject of the email
            $subject = 'Cancellation email'; 
            //define the message to be sent. Each line should be separated with \n
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Your ticket with booking id '.$cancelRequest['fb_bookingId'].' and PNR '.$cancelRequest['tin'].', Has now been cancelled. The refund amount, if any, will be credited back to you in 7-8 working days. Please contact customer support at Phone num: '.$cust_support_data->phone_number.', Email ID: '.$cust_support_data->email.' for any further assistance.</p><hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>'.$cust_support_data->phone_number.'</strong> <br/>Email: <strong><a href="javascript:void(0);">'.$cust_support_data->email.'</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
            //define the headers we want passed. Note that they are separated with \r\n
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Admin <info@farebucket.com>" . "\r\n";
            //send the email
            $mail_sent = @mail( $to, $subject, $message, $headers );
    }
}
?>