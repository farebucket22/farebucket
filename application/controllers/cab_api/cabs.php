<?php
session_start();  
class Cabs extends CI_Controller
{
	function status()
	{
		require_once (APPPATH . 'lib/nusoap.php');
		$wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
		$client_header = new SoapHeader('http://wheelz.wheelzindia.com/WhoAmI', 'AuthenticationData', false);
    	$client = new SoapClient($wsdl);
    	$client->__setSoapHeaders(array($client_header));
    	$status_check = array();
    	$status_check['WhoAmI']['AccountId'] = 53;
    	$status_check['WhoAmI']['UserName'] = "255872";
    	$status_check['WhoAmI']['Password'] = "278552";
    	$header = array();
        $header = (array)$client->__call('WhoAmI', $status_check);
        print_r($header);
	}

    function flights_to_cabs()
   {    
        if($this->input->get(null,true))
        {
            //for direct search hence get is used
            $_SESSION['calling_controller_name'] = "cabs";
            $_SESSION['currentUrl'] = current_full_url();
            $_SESSION['currentUrlCabs'] = current_full_url();
            $data['data'] = $this->input->get(null,true);
            $source_split = explode(",",$data['data']['source']);
            $destination_split = explode(",",$data['data']['destination']);
            $data['data']['source'] = $source_split[0];
            if($data['data']['source'] == "Delhi")
               $data['data']['source'] = "Delhi /NCR";
            $data['data']['destination'] = $destination_split[0];
            if( $data['data']['source'] == "Madras" )$data['data']['source'] = "Chennai";
            if( $data['data']['destination'] == "Madras" )$data['data']['destination'] = "Chennai";
            $check = 0; 
            $_SESSION['cab_src'] = $data['data']['source'];
            $_SESSION['cab_dest'] = $data['data']['destination'];
            $_SESSION['travel_id'] = 2;
            $_SESSION['day_id'] = 0;
            $source_result = $this->source_cities($data,0);
            $_SESSION['cab_state_id'] = $source_result;
            $destination_result = $this->destination_cities($source_result,$data,0);
            $_SESSION['cab_city_id'] = $destination_result;
            $search_result = $this->search_cabs($source_result,$destination_result,$data,0);
        } 
        else
        {
            if( isset($_SESSION['multiTravelData']['cabData']) ){
                if( isset( $_POST['data']['flight_num'] ) && isset($_SESSION['multiTravelData']['cabData']['flight_num']) && $_POST['data']['flight_num'] == $_SESSION['multiTravelData']['cabData']['flight_num'] ){
                    $number = $_POST['data']['flight_num'] - 1;
                    if(isset($_SESSION['details']['from'])){
                        $src_name = explode(',', $_SESSION['details']['from'][$number]);
                        $dest_name = explode(',', $_SESSION['details']['to'][$number]);
                        if($src_name === $_SESSION['multiTravelData']['cabData']['source'] && $dest_name === $_SESSION['multiTravelData']['cabData']['destination']){
                            echo json_encode($_SESSION['multiTravelData']['cabData']);
                            die;
                        }
                    }
                }
            }
            if( isset($_POST['data']['flight_num']) ){
                $flight_num = $_POST['data']['flight_num'];
            }else{
                $flight_num = 1;
            }
            //for direct search hence post is used - ajax also done from flights
            $data = $this->input->post(null,true);
            if( $data['data']['source'] == "Madras" )$data['data']['source'] = "Chennai";
            if( $data['data']['destination'] == "Madras" )$data['data']['destination'] = "Chennai";
            $source_result = $this->source_cities($data,2);
            $destination_result = $this->destination_cities($source_result,$data,2);
            $search_result = $this->search_cabs($source_result,$destination_result,$data,2,$flight_num);
        }    
    } 
	/*****to show the source cities*****/
  
	function source_cities($data = null, $change = null)
	{
        if($this->input->post('type'))
        {
            $check = 1;
        }
        else
        {
            $check = 0;
        }
        require_once (APPPATH . 'lib/nusoap.php');
		$wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
		$client_header = new SoapHeader('http://wheelz.wheelzindia.com/SourceCity_InTravelTypeID', 'AuthenticationData',false);
    	$client = new SoapClient($wsdl);
    	$client->__setSoapHeaders(array($client_header));
    	$source = array();
        $source['SourceCity_InTravelTypeID']['TravelTypeID'] = 2;
        if($check == 1 && $this->input->post('type') != 2)
            $source['SourceCity_InTravelTypeID']['TravelTypeID'] = 1;
    	$source['SourceCity_InTravelTypeID']['AccountId'] = 53;
    	$source['SourceCity_InTravelTypeID']['UserName'] = "255872";
    	$source['SourceCity_InTravelTypeID']['Password'] = "278552";
    	$header = array();
        $header = (array)$client->__call('SourceCity_InTravelTypeID', $source);
        $statename = array();
        $stateid = array();
        $dom = new DOMDocument;
        $dom->loadXML('<root>'.$header['SourceCity_InTravelTypeIDResult']->any.'</root>');
        $states = $dom->getElementsByTagName('State');
        if($check != 1 )
            $requested_src = $data['data']['source'];
        else
            $requested_src = 0;
        $count = 0;
        $source_index = 0;
        foreach ($states as $state) 
        {
            $count++;
            if($requested_src == $state->nodeValue)
                $source_index = $count;
            $statename[] = print_r($state->nodeValue, PHP_EOL);
        }
        $state_id = $dom->getElementsByTagName('StateID');
        $res_sourceid = 0;
        $count_check = 0;
        $count = 0;
        foreach ($state_id as $state) 
        {
            $count++;
            if($count == $source_index)    
                $res_sourceid = $state->nodeValue;
            $stateid[] = print_r($state->nodeValue, PHP_EOL);
        }
        $result = array(
            'statename' => $statename,
            'stateid' => $stateid 
            );

        if($check == 1)
        {
            echo json_encode($result);
        }
        else
        {
            return $res_sourceid;
        }
   }
	/*****to show destination cities*****/

	function destination_cities($source_id = null, $data = null, $check = null)
	{
        $check_out = $this->input->post('type');
        if($check_out != NULL)
        {
            $check = 1;
            $source_id = $this->input->post('source_id');
        }   
        else
        {
            $check = 0;
        } 
		require_once (APPPATH . 'lib/nusoap.php');
		$wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
		$client_header = new SoapHeader('http://wheelz.wheelzindia.com/SourceCity_InTravelTypeID', 'AuthenticationData',false);
    	$client = new SoapClient($wsdl);
    	$client->__setSoapHeaders(array($client_header));
    	$destination = array();
    	$destination['DestinationCity_InTravelTypeID']['TravelTypeID'] = 2;
        if($this->input->post('type') == 1)
            $destination['DestinationCity_InTravelTypeID']['TravelTypeID'] = 1;
    	$destination['DestinationCity_InTravelTypeID']['StateID'] = $source_id;/*$this->input->post('state_id');*/
    	$destination['DestinationCity_InTravelTypeID']['AccountId'] = 53;
    	$destination['DestinationCity_InTravelTypeID']['UserName'] = "255872";
    	$destination['DestinationCity_InTravelTypeID']['Password'] = "278552";
    	$header = array();
        $header = (array)$client->__call('DestinationCity_InTravelTypeID', $destination);
        $statename = array();
        $stateid = array();
        $dom = new DOMDocument;
        $dom->loadXML('<root>'.$header['DestinationCity_InTravelTypeIDResult']->any.'</root>');
        $states = $dom->getElementsByTagName('Name');
        if($check !=1)
            $requested_dest = $data['data']['destination'];
        else
            $requested_dest = 0;
        $count = 0;
        $dest_index = 0;
        foreach ($states as $state) 
        {
            $count++;
            if($requested_dest == $state->nodeValue)
            {
                $dest_index = $count;
            }
            $statename[] = print_r($state->nodeValue, PHP_EOL);
        }
        $state_id = $dom->getElementsByTagName('CityID');
        $res_destid = 0;
        $count = 0; 
        foreach ($state_id as $state) 
        {
            $count++;
            if($count == $dest_index)    
                $res_destid = $state->nodeValue;
            $stateid[] = print_r($state->nodeValue, PHP_EOL);
        }
        $result = array(
            'statename' => $statename,
            'stateid' => $stateid 
            );
        if($check == 1)
        {
            echo json_encode($result);
        } 
        else
        {
            return $res_destid;
        }
   }

	/*****shows days of travel*****/

	function available_days()
	{
		require_once (APPPATH . 'lib/nusoap.php');
		$wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
		$client_header = new SoapHeader('http://wheelz.wheelzindia.com/Days_InTravelTypeID', 'AuthenticationData',false);
    	$client = new SoapClient($wsdl);
    	$client->__setSoapHeaders(array($client_header));
    	$days = array();
    	$days['Days_InTravelTypeID']['TravelTypeID'] = 2;
    	$days['Days_InTravelTypeID']['AccountId'] = 53;
    	$days['Days_InTravelTypeID']['UserName'] = "255872";
    	$days['Days_InTravelTypeID']['Password'] = "278552";
    	$header = array();
    	$header = (array)$client->__call('Days_InTravelTypeID', $days);
        $duration = array();
        $day_name = array();
        $dom = new DOMDocument;
        $dom->loadXML('<root>'.$header['Days_InTravelTypeIDResult']->any.'</root>');
        $states = $dom->getElementsByTagName('DayID');
        foreach ($states as $state) 
        {
            $duration[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('DayName');
        foreach ($states as $state) 
        {
            $day_name[] = print_r($state->nodeValue, PHP_EOL);
        }
        $result = array(
            'duration' => $duration,
            'day_name' => $day_name 
            );
        echo json_encode($result);
	}

	/*****search cabs*****/

	function search_cabs($src_id = null, $dest_id = null, $data = null, $check = null,$flight_num=null) 
	{

        $_SESSION['currentUrl'] = current_full_url();
		unset($_SESSION['duration_selected_cabs']);
        $check_out = $this->input->get('flight_type');
        if($this->input->get(null, true) && $check_out != NULL && $data == null)
        {
            $check = 1;
            if($check_out != "local")
            {
                $src = explode("-",$this->input->get('out_cab_src'));
                $dest = explode("-",$this->input->get('out_cab_dest'));
                $_SESSION['day_id'] = $this->input->get('out_duration');
                $_SESSION['travel_id'] = 2;
				$_SESSION['duration_selected_cabs'] = $this->input->get('selected_duration');
            }
            else
            {
                $src = explode("-",$this->input->get('local_cab_src'));
                $_SESSION['travel_id'] = 1;
            }
            $_SESSION['cab_src'] = $src[1];
            if($check_out != "local")
            {
                $_SESSION['cab_dest'] = $dest[1];
            }
            else
            {
                $_SESSION['cab_dest'] = $this->input->get('local_cab_dest');
            }
            $_SESSION['adult_count'] = $this->input->get('adult_count');
            $_SESSION['youth_count'] = $this->input->get('youth_count');
            $_SESSION['to_date'] = $this->input->get('to_date');
        }
        else if($this->input->post(null,true))
        {
            $check = 2;
        }
        else
        {   
            $check = 0;
        }
		require_once (APPPATH . 'lib/nusoap.php');
		$wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
		$client_header = new SoapHeader('http://wheelz.wheelzindia.com/SearchCabs_InTravelTypeID ', 'AuthenticationData',false);
    	$client = new SoapClient($wsdl);
    	$client->__setSoapHeaders(array($client_header));
    	$cabs = array();
    	$cabs['SearchCabs_InTravelTypeID']['TravelTypeID'] = 2;
    	$cabs['SearchCabs_InTravelTypeID']['UsageTypeID'] = 0;
        if($this->input->get('flight_type') == "local")
        {
            $cabs['SearchCabs_InTravelTypeID']['TravelTypeID'] = 1;
            $cabs['SearchCabs_InTravelTypeID']['UsageTypeID'] = $this->input->get('local_cab_dest');
        }
        if($check == 0 || $check == 2)
        {
        	$cabs['SearchCabs_InTravelTypeID']['StateID'] = $src_id;
            $cabs['SearchCabs_InTravelTypeID']['CityID'] = $dest_id;
            $cabs['SearchCabs_InTravelTypeID']['TotalPax'] = $data['data']['adult_count'] + $data['data']['youth_count'];
        }
        else
        {
            $cabs['SearchCabs_InTravelTypeID']['StateID'] = $src[0];
            if($this->input->get('flight_type') == "local")
               $cabs['SearchCabs_InTravelTypeID']['CityID'] = 0;
            else
               $cabs['SearchCabs_InTravelTypeID']['CityID'] = $dest[0];
            $cabs['SearchCabs_InTravelTypeID']['TotalPax'] = $this->input->get('total_count');
        }
        $cabs['SearchCabs_InTravelTypeID']['TotalCar'] = 0;
        if($this->input->get('flight_type') == "local")
    	   $cabs['SearchCabs_InTravelTypeID']['DayID'] = 1;
        else if($this->input->get('flight_type') == "outstation")
            $cabs['SearchCabs_InTravelTypeID']['DayID'] = $this->input->get('out_duration');
        else
            $cabs['SearchCabs_InTravelTypeID']['DayID'] = 20;
    	$cabs['SearchCabs_InTravelTypeID']['AccountId'] = 53;
    	$cabs['SearchCabs_InTravelTypeID']['UserName'] = "255872";
    	$cabs['SearchCabs_InTravelTypeID']['Password'] = "278552";
         
    	$header = array();
    	$header = (array)$client->__call('SearchCabs_InTravelTypeID', $cabs);

        $dom = new DOMDocument;
        $dom->loadXML('<root>'.$header['SearchCabs_InTravelTypeIDResult']->any.'</root>');

        $states = $dom->getElementsByTagName('FareDetails');
        foreach ($states as $state) 
        {
            $fare_details[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('CalcKm');
        foreach ($states as $state) 
        {
            $calculatable_kms[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('Days');
        foreach ($states as $state) 
        {
            $days[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('MinKm');
        foreach ($states as $state) 
        {
            $min_kms[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('CarTypeID');
        foreach ($states as $state) 
        {
            $car_type[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('TariffId');
        foreach ($states as $state) 
        {
            $tariff_id[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('Car_Model');
        foreach ($states as $state) 
        {
            $car_model[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('SeatingCapacity');
        foreach ($states as $state) 
        {
            $seat_capacity[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('RequiredCarFare');
        foreach ($states as $state) 
        {
            $car_fare[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('XKm_Charges');
        foreach ($states as $state) 
        {
            $per_km_charge[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('Specification');
        foreach ($states as $state) 
        {
            $car_spec[] = print_r($state->nodeValue, PHP_EOL);
        }
        $states = $dom->getElementsByTagName('BATA');
        foreach ($states as $state) 
        {
            $driver_bata[] = print_r($state->nodeValue, PHP_EOL);
        }
        if(isset($driver_bata))
            $arr_len = count($driver_bata);
        else
            $arr_len = 0;
        for($i=0;$i<$arr_len;$i++)
        {
            $total_fare[$i] = $driver_bata[$i] + $car_fare[$i];
        }
        if(isset($total_fare))
        {
            if($this->input->get('flight_type') == "local")
                $des = $this->input->get('local_cab_dest');
            else
                $des = $data['data']['destination'];
            if($this->input->get(null,true))
            {
                if($check == 0)
                {
                     $result = array('source' =>  $data['data']['source'],'destination' => $des,'total_fare' => $total_fare,'tariff_id' => $tariff_id,'car_model' => $car_model,
                        'CarTypeID' => $car_type,'SeatingCapacity' => $seat_capacity,'RequiredCarFare' => $car_fare,
                        'XKm_Charges' => $per_km_charge,'driver_bata' => $driver_bata,'Specification' => $car_spec, 'calculatable_kms' => $calculatable_kms, 'min_kms' => $min_kms, 'fare_details' => $fare_details, 'days' => $days);
                }
                else if($this->input->get('flight_type') == "local")
                {
                    $result = array('source' =>  $src,'destination' => $des,'total_fare' => $total_fare,'tariff_id' => $tariff_id,'car_model' => $car_model,
                            'CarTypeID' => $car_type,'SeatingCapacity' => $seat_capacity,'RequiredCarFare' => $car_fare,
                            'XKm_Charges' => $per_km_charge,'driver_bata' => $driver_bata,'Specification' => $car_spec, 'calculatable_kms' => $calculatable_kms, 'min_kms' => $min_kms, 'fare_details' => $fare_details, 'days' => $days);
                }
                else
                {
                    $result = array('source' =>  $src[1],'destination' => $dest[1],'total_fare' => $total_fare,'tariff_id' => $tariff_id,'car_model' => $car_model,
                            'CarTypeID' => $car_type,'SeatingCapacity' => $seat_capacity,'RequiredCarFare' => $car_fare,
                            'XKm_Charges' => $per_km_charge,'driver_bata' => $driver_bata,'Specification' => $car_spec, 'calculatable_kms' => $calculatable_kms, 'min_kms' => $min_kms, 'fare_details' => $fare_details, 'days' => $days);
                }
            }
            else
            {
                $result = array('source' =>  $data['data']['source'],'destination' => $des,'total_fare' => $total_fare,'tariff_id' => $tariff_id,'car_model' => $car_model,
                        'CarTypeID' => $car_type,'SeatingCapacity' => $seat_capacity,'RequiredCarFare' => $car_fare,
                        'XKm_Charges' => $per_km_charge,'driver_bata' => $driver_bata,'Specification' => $car_spec, 'calculatable_kms' => $calculatable_kms, 'min_kms' => $min_kms, 'fare_details' => $fare_details, 'days' => $days);
            }
        }
        if($check == 2){
            if(isset($total_fare[0])){
                $_SESSION['multiTravelData']['cabData'] = array(
                    'fare_min' => $total_fare[0],
                    'flight_num' => $flight_num,
					'source' => $data['data']['source'],
                    'destination' => $des
                );
                echo json_encode($total_fare[0]);
            }else{
                echo json_encode(false);
            } 
        }else{
            $this->load->view('common/header');
            $this->load->view('cabs/cabs_select',array('data' => $result));
            $this->load->view('common/footer');
        } 
	}

   /******common function for booking*****/

    public function general_booking()
    {
        $fbBooking = 'TSCA';
        $this->load->model('cab_model');
        $returnId = $this->cab_model->getLastFbBookingId();
        if($returnId === 0){
            $randomNum = 1;
        }
        else{
            $firstFourLetters = substr($returnId, 0, 4);
            $splitReturnID = explode($firstFourLetters, $returnId);
            $randomNum = intval($splitReturnID[1]) + 1;
        } 
    
        $randomNum = sprintf("%06d",$randomNum);
        $fbBookingId = $fbBooking . $randomNum;
        $_SESSION['cabBookingId'] = $fbBookingId;

        unset($_SESSION['currentUrl']);
        if( $this->input->get(null, true) ){
            $data = $this->input->get(null, true);

            $pax_info = json_decode($_SESSION['flight_data'][$data['index']]['ov']->paxInfo);
            $extra_info = $_SESSION['flight_data'][$data['index']]['extra_info'];
            $_SESSION['to_date'] = date('Y-m-d', strtotime($_SESSION['flight_data'][$data['index']]['ov']->doj));

            $name_arr = explode(' ', $data['fname0']);
            $data['first_name'] = $name_arr[0];
            if(isset($name_arr[1])){$data['last_name'] =  $name_arr[1];}else{$data['last_name'] = '';};
            $guest = array(
                'pax_title' => $data['Title0'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'pickup_addr' => $data['addressPickup'],
                'drop_addr' => $data['addressDrop'],
                'CabRequiredOn' => $data['pickupDate'],
                'Phone_num' => $data['mobile'],
                'Email' => $data['email_id'],
                'stateID' => $_SESSION['cab_state_id'],
                'cityID' => $_SESSION['cab_city_id'],
                'cab_src' => $_SESSION['cab_src'],
                'cab_dest' => $_SESSION['cab_dest'],
                'travel_id' => $_SESSION['travel_id'],
                'fb_bookingId' => $_SESSION['cabBookingId'],
                'discountCode' => $_SESSION['discountCodeCab'],
                'discountValue' => $_SESSION['discountValueCab'],
            );

            $fb_bookingId = $_SESSION['cabBookingId'];
            $addnInfoPickupAddress = $data['addressPickup'];
            $addnInfoDropAddress = $data['addressDrop'];

            $this->load->model('cab_model');

            $compacts = count($pax_info->compact);
            $sedans = count($pax_info->sedan);
            $suvs = count($pax_info->suv);
            $_SESSION['cab_id'] = array();
            $i = 0;
            if( $compacts ){
                $sum = array_sum($pax_info->compact);
                $ret[$i] = $this->booking_cab( $compacts, 'compact', $extra_info, $guest, $sum,$i);
                $model = "compact";
                $id = $this->cab_model->insert_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $id_insert = $this->cab_model->insert_booking_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $_SESSION['cab_id'][$i] = $id;
                $_SESSION['car_type'][$i] = "Compact";
                $_SESSION['passengers'][$i] = $sum;
                $i++;
            }
            if( $sedans ){
                $sum = array_sum($pax_info->sedan);
                $ret[$i] = $this->booking_cab( $sedans, 'sedan', $extra_info, $guest, $sum,$i);
                $model = "sedan";
                $id = $this->cab_model->insert_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $id_insert = $this->cab_model->insert_booking_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $_SESSION['cab_id'][$i] = $id;
                $_SESSION['car_type'][$i] = "Sedan";
                $_SESSION['passengers'][$i] = $sum;
                $i++;
            }
            if( $suvs ){
                $sum = array_sum($pax_info->suv);
                $ret[$i] = $this->booking_cab( $suvs, 'suv', $extra_info, $guest, $sum,$i);
                $model = "suv";
                $id = $this->cab_model->insert_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $id_insert = $this->cab_model->insert_booking_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $_SESSION['cab_id'][$i] = $id;
                $_SESSION['car_type'][$i] = "Suv";
                $_SESSION['passengers'][$i] = $sum;
                $i++;
            }
            
            for($j = 0 ; $j<$i ; $j++)
            {
                $booking_reference[$j] = $this->confirm_booking($ret[$j],$_SESSION['cab_id'][$j]);
            }

            $q_str = '';
            $v = 0;
            $b_len = count($booking_reference);
            foreach ($booking_reference as $b_key => $b_val) {
                if( $v == 0 ){
                    $q_str .= $b_key.'='.$b_val;
                }else{
                    $q_str .= '&'.$b_key.'='.$b_val;
                }
                if( $v == $b_len - 1){
                    $q_str .= '&call_func=cab';
                }
                $v++;
            }
            redirect('common/store_block_id?'.$q_str);
        }else{
            $data = $_SESSION['cabs_outstation_post_data'];
            $date = $_SESSION['cabs_outstation_post_data']['pickupDate'];
            $timeH = $_SESSION['cabs_outstation_post_data']['timeHours'];
            $timeM = $_SESSION['cabs_outstation_post_data']['timeMins'];
            $cabRequiredOn = $date. " " . $timeH. ":" . $timeM;
            $pax_info = json_decode($_SESSION['cabs_outstation_post_data']['pax_info']);
            $extra_info = json_decode($_SESSION['cabs_outstation_post_data']['extra_info']);
            $guest = array(
                'pax_title' => $_SESSION['cabs_outstation_post_data']['pax_title'],
                'first_name' => $_SESSION['cabs_outstation_post_data']['first_name'],
                'last_name' => $_SESSION['cabs_outstation_post_data']['last_name'],
                'pickup_addr' => $_SESSION['cabs_outstation_post_data']['pickup_addr'],
                'drop_addr' => $_SESSION['cabs_outstation_post_data']['drop_addr'],
                'CabRequiredOn' => $cabRequiredOn,
                'Phone_num' => $_SESSION['cabs_outstation_post_data']['Phone_num'],
                'Email' => $_SESSION['cabs_outstation_post_data']['Email'],
                'stateID' => $_SESSION['cabs_outstation_post_data']['stateID'],
                'cityID' => $_SESSION['cabs_outstation_post_data']['cityID'],
                'cab_src' => $_SESSION['cab_src'],
                'cab_dest' => $_SESSION['cab_dest'],
                'travel_id' => $_SESSION['travel_id'],
                'fb_bookingId' => $_SESSION['cabBookingId']
            );

            $fb_bookingId = $_SESSION['cabBookingId'];
            $addnInfoPickupAddress = $_SESSION['cabs_outstation_post_data']['pickup_addr'];
            $addnInfoDropAddress = $_SESSION['cabs_outstation_post_data']['drop_addr'];

            $this->load->model('cab_model');

            $compacts = count($pax_info->compact);
            $sedans = count($pax_info->sedan);
            $suvs = count($pax_info->suv);
            $_SESSION['cab_id'] = array();
            $i = 0;
            if( $compacts ){
                $sum = array_sum($pax_info->compact);
                $ret[$i] = $this->booking_cab( $compacts, 'compact', $extra_info, $guest, $sum,$i);
                $model = "compact";
                $id = $this->cab_model->insert_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $id_insert = $this->cab_model->insert_booking_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $_SESSION['cab_id'][$i] = $id;
                $_SESSION['car_type'][$i] = "Compact";
                $_SESSION['passengers'][$i] = $sum;
                $i++;
            }
            if( $sedans ){
                $sum = array_sum($pax_info->sedan);
                $ret[$i] = $this->booking_cab( $sedans, 'sedan', $extra_info, $guest, $sum,$i);
                $model = "sedan";
                $id = $this->cab_model->insert_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $id_insert = $this->cab_model->insert_booking_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $_SESSION['cab_id'][$i] = $id;
                $_SESSION['car_type'][$i] = "Sedan";
                $_SESSION['passengers'][$i] = $sum;
                $i++;
            }
            if( $suvs ){
                $sum = array_sum($pax_info->suv);
                $ret[$i] = $this->booking_cab( $suvs, 'suv', $extra_info, $guest, $sum,$i);
                $model = "suv";
                $id = $this->cab_model->insert_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $id_insert = $this->cab_model->insert_booking_details($compacts,$guest,$ret[$i],$model,$sum,$_SESSION['cab_total_fare'],$_SESSION['cabMinSlab'],$_SESSION['cab_db_fare_params']['offered_price'], $_SESSION['cab_db_fare_params']['published_price'], $_SESSION['cab_db_fare_params']['convenience_charge']);
                $_SESSION['cab_id'][$i] = $id;
                $_SESSION['car_type'][$i] = "Suv";
                $_SESSION['passengers'][$i] = $sum;
                $i++;
            }
            for($j = 0 ; $j<$i ; $j++)
            {
                $booking_reference[$j] = $this->confirm_booking($ret[$j],$_SESSION['cab_id'][$j]);
            }

            if(count($booking_reference) == 1){
                $booking_reference = $booking_reference[0];
            }

            $additionalInfo = array(
                'fb_bookingId' => $fb_bookingId,
                'BookingId' => $booking_reference,
                'Origin' => $_SESSION['cab_src'],
                'Destination' => $_SESSION['cab_dest'],
                'pickUpLocationAddress' => $addnInfoPickupAddress,
                'dropLocationAddress' => $addnInfoDropAddress,
            );

            $this->send_email($_SESSION['user_details'][0]->user_email, $booking_reference, $_SESSION['user_details'][0]->user_first_name, $additionalInfo);
            
            $cabPayUid = $_SESSION['cabPayId'];
            $this->load->model('cab_model');
            $this->cab_model->updatePayuId($cabPayUid, $fb_bookingId);

            $cab_data = $this->cab_model->getCabDetailsByBookingRef(array('fb_bookingId' => $fb_bookingId));
            $this->load->view("common/header.php");
            $this->load->view('cabs/multiway_tickets',array('data' => $cab_data));
            $this->load->view("common/footer.php");
        }
    }

    public function getCabTicket(){
        $data = array(
            'user_email' => $_GET['guest_email'],
            'fb_bookingId' => $_GET['ticket_id']
        );
        $this->load->model('cab_model');
        $booking_reference = $this->cab_model->getCabRefByUserDetails($data);
        $this->load->view("common/header.php");
        $this->load->view('cabs/multiway_tickets',array('data' => $booking_reference));
        $this->load->view("common/footer.php");
    }

    public function send_email($email_id, $booking_reference, $name, $additionalInfo){
            $to = $email_id;
            $cust_support_data = cust_support_helper();
            //define the subject of the email
            $subject = 'Ticket Links'; 
            //define the message to be sent. Each line should be separated with \n
            $noOfBooking = count($booking_reference);
            $i = 1;
            $link = '';
            if( $noOfBooking > 1){
                foreach( $booking_reference as $br ){
                    $link .= "<a href='".site_url('cab_api/cabs/search_book?booking_number='.$br)."'>Ticket ".$i."</a><br />";
                    $i++;
                }
                $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Thank you for choosing Farebucket.</p><p>Your ticket From: '.$additionalInfo["Origin"].' To: '.$additionalInfo["Destination"].'</p><p>Your Booking ID: '.$additionalInfo["BookingId"].'</p><p>Farebucket Booking ID: '.$additionalInfo["fb_bookingId"].'</p><p>Pick Up Address: '.$additionalInfo["pickUpLocationAddress"].'</p><p>Drop Address: '.$additionalInfo["dropLocationAddress"].'</p><p>Please find the link to your ticket(s) below.</p>Links: <a href="'.$link.'">Ticket</a> <hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>'.$cust_support_data->phone_number.'</strong> <br/>Email: <strong><a href="emailto:'.$cust_support_data->email.'">'.$cust_support_data->email.'</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
            }else{
                $link = "<a href='".site_url('cab_api/cabs/search_book?booking_number='.$booking_reference[0])."'>Ticket 1</a><br />";
                $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Thank you for choosing Farebucket.</p><p>Your ticket From: '.$additionalInfo["Origin"].' To: '.$additionalInfo["Destination"].'</p><p>Your Booking ID: '.$additionalInfo["BookingId"].'</p><p>Farebucket Booking ID: '.$additionalInfo["fb_bookingId"].'</p><p>Pick Up Address: '.$additionalInfo["pickUpLocationAddress"].'</p><p>Drop Address: '.$additionalInfo["dropLocationAddress"].'</p><p>Please find the link to your ticket(s) below.</p>Links: <a href="'.$link.'">Ticket</a> <hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>'.$cust_support_data->phone_number.'</strong> <br/>Email: <strong><a href="emailto:'.$cust_support_data->email.'">'.$cust_support_data->email.'</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
            }
            
            //define the headers we want passed. Note that they are separated with \r\n
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Admin <info@farebucket.com>" . "\r\n";
            $headers .= "Cc: Support <support@farebucket.com>" . "\r\n";
            //send the email
            $mail_sent = @mail( $to, $subject, $message, $headers );
    }

	/*****booking a cab*****/

	function booking_cab($total_cars,$car_name,$info,$traveller_info,$passengers,$i)
	{  
            require_once (APPPATH . 'lib/nusoap.php');
    		$wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
    		$client_header = new SoapHeader('http://wheelz.wheelzindia.com/CreateBooking_GetReferenceID', 'AuthenticationData',false);
        	$client = new SoapClient($wsdl);
        	$client->__setSoapHeaders(array($client_header));
        	$book_cab = array();
        	$book_cab['CreateBooking_GetReferenceID']['GuestName'] = "".$traveller_info['first_name'];
            $book_cab['CreateBooking_GetReferenceID']['Total_Cars'] = $total_cars;
        	$book_cab['CreateBooking_GetReferenceID']['Total_Guests'] = $passengers;
        	$book_cab['CreateBooking_GetReferenceID']['GuestEmailID'] = "".$traveller_info['Email'];
        	$book_cab['CreateBooking_GetReferenceID']['PhoneNo'] = "".$traveller_info['Phone_num'];
        	$book_cab['CreateBooking_GetReferenceID']['CabRequiredOn'] = "".$traveller_info['CabRequiredOn'];
            $book_cab['CreateBooking_GetReferenceID']['TariffID'] = $info->tariff_id[$i];
        	$book_cab['CreateBooking_GetReferenceID']['TravelTypeID'] = $_SESSION['travel_id'];
        	$book_cab['CreateBooking_GetReferenceID']['PickupAddress'] = "".$traveller_info['pickup_addr'];
        	$book_cab['CreateBooking_GetReferenceID']['DropAddress'] = "".$traveller_info['drop_addr'];
        	$book_cab['CreateBooking_GetReferenceID']['SpecialInstructions'] = "";
            $book_cab['CreateBooking_GetReferenceID']['TotalFare'] = "".$total_cars * $info->total_fare[$i];
            if($traveller_info['travel_id'] == 2)
                $book_cab['CreateBooking_GetReferenceID']['DayID'] = $_SESSION['day_id'];
            else
                $book_cab['CreateBooking_GetReferenceID']['DayID'] = 1;
        	$book_cab['CreateBooking_GetReferenceID']['StateID'] = $traveller_info['stateID'];
            if($traveller_info['travel_id'] == 2)
                $book_cab['CreateBooking_GetReferenceID']['CityID'] = $traveller_info['cityID'];
            else
                 $book_cab['CreateBooking_GetReferenceID']['CityID'] = 0;
            	$book_cab['CreateBooking_GetReferenceID']['CarTypeID'] = $info->CarTypeID[$i];
            	$book_cab['CreateBooking_GetReferenceID']['AccountId'] = 53;
            	$book_cab['CreateBooking_GetReferenceID']['UserName'] = "255872";
            	$book_cab['CreateBooking_GetReferenceID']['Password'] = "278552";
            	$header = array();
            	$header = (array)$client->__call('CreateBooking_GetReferenceID', $book_cab);
            if($header['CreateBooking_GetReferenceIDResult'])
            {
        	   return $header['CreateBooking_GetReferenceIDResult'];/*reference id*/
            }
            else
            {
                return false;
            }   
	}

	/*****booking information*****/

	function confirm_booking($ref_id,$id)
	{
		require_once (APPPATH . 'lib/nusoap.php');
		$wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
		$client_header = new SoapHeader('http://wheelz.wheelzindia.com/ConfirmBooking_ByReferenceID ', 'AuthenticationData',false);
    	$client = new SoapClient($wsdl);
    	$client->__setSoapHeaders(array($client_header));
    	$confirm_book = array();
    	$confirm_book['ConfirmBooking_ByReferenceID']['ReferenceID'] = $ref_id;
    	$confirm_book['ConfirmBooking_ByReferenceID']['AccountId'] = 53;
    	$confirm_book['ConfirmBooking_ByReferenceID']['UserName'] = "255872";
    	$confirm_book['ConfirmBooking_ByReferenceID']['Password'] = "278552";
    	$header = array();
    	$header = (array)$client->__call('ConfirmBooking_ByReferenceID', $confirm_book);
    	$this->load->model('cab_model');
        $this->cab_model->update_details($id,$header['ConfirmBooking_ByReferenceIDResult']);
        $this->cab_model->update_booking_details($id,$header['ConfirmBooking_ByReferenceIDResult']);
        return $header['ConfirmBooking_ByReferenceIDResult'];
	}

    /*****Search booking by booking number*****/

    function search_book()
    {
        require_once (APPPATH . 'lib/nusoap.php');
        $wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
        $client_header = new SoapHeader('http://wheelz.wheelzindia.com/SearchBooking_ByBookingNo ', 'AuthenticationData',false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array($client_header));
        $search_book = array();
        $search_book['SearchBooking_ByBookingNo']['BookingNo'] = "".$this->input->get('booking_number');
        $search_book['SearchBooking_ByBookingNo']['AccountId'] = 53;
        $search_book['SearchBooking_ByBookingNo']['UserName'] = "255872";
        $search_book['SearchBooking_ByBookingNo']['Password'] = "278552";
        $header = array();
        $header = (array)$client->__call('SearchBooking_ByBookingNo', $search_book);

        $this->load->model('cab_model');
        $dom = new DOMDocument;
        $dom->loadXML('<root>'.$header['SearchBooking_ByBookingNoResult']->any.'</root>');
        $states = $dom->getElementsByTagName('CabRequiredOn');
        foreach ($states as $state) 
        {
            $journey_date = print_r($state->nodeValue, PHP_EOL);
        }
        $journey_date = explode("+", $journey_date);
        $states = $dom->getElementsByTagName('BookingOn');
        foreach ($states as $state) 
        {
            $booked_on = print_r($state->nodeValue, PHP_EOL);
        }
        $booked_on = explode("+", $booked_on);
        if($this->cab_model->update_booking($this->input->get('booking_number'),$booked_on[0],$journey_date[0]))
        {
            $data = $this->cab_model->get_details($this->input->get('booking_number'));
        }
        $this->load->view('cabs/invoice_page',array('data' => $data));
    }

    /*****Edit booking by booking number*****/

    function edit_booking()
    {
        require_once (APPPATH . 'lib/nusoap.php');
        $wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
        $client_header = new SoapHeader('http://wheelz.wheelzindia.com/EditBooking_ByBookingNo  ', 'AuthenticationData',false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array($client_header));
        $edit_book = array();
        $edit_book['EditBooking_ByBookingNo']['BookingNo'] = "WI2014923-546";
        $edit_book['EditBooking_ByBookingNo']['IsGuest'] = 4;
        $edit_book['EditBooking_ByBookingNo']['GuestName'] = "Bala";
        $edit_book['EditBooking_ByBookingNo']['IsTime'] = 1;
        $edit_book['EditBooking_ByBookingNo']['CabRequiredOn'] = "2014-09-25 08:00";
        $edit_book['EditBooking_ByBookingNo']['IsPickupAddress'] = 1;
        $edit_book['EditBooking_ByBookingNo']['PickupAddress'] = "#753 Shri Krishna Temple Road,Beside Cakewalk,Indiranagar, 1st Stage,Bengaluru 560 038";
        $edit_book['EditBooking_ByBookingNo']['AccountId'] = 53; 
        $edit_book['EditBooking_ByBookingNo']['UserName'] = "255872";
        $edit_book['EditBooking_ByBookingNo']['Password'] = "278552";
        $header = array();
        $header = (array)$client->__call('EditBooking_ByBookingNo', $edit_book);
        echo '<pre>';
        print_r($header);
    }

    /*****Cancel booking by booking number*****/

    function cancel_booking()
    {
        $cust_support_data = cust_support_helper();
        if( $this->input->post('confirm_ref_id') ){
            $booking_ref_id = $this->input->post('confirm_ref_id');
            $refundAmt = $this->input->post('refundAmt');
            $canlAmt = $this->input->post('canlAmt');
            $id = $this->input->post('booking_id');
            $email_id = $this->input->post('email_id');
        }else{
            $booking_ref_id = "".$this->input->get('ref_id');
            $id = $this->input->get('booking_id');
            $adminCancelFlag = 1;
        }
        $today = date("Y-m-d H:i:s");
        $this->load->model('cab_model');
        $this->cab_model->saveCancellationDate($today, $id);

        require_once (APPPATH . 'lib/nusoap.php');
        $wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
        $client_header = new SoapHeader('http://wheelz.wheelzindia.com/CancelBooking_ByBookingNo   ', 'AuthenticationData', false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array($client_header));
        $cancel_book = array();
        $cancel_book['$cancel_book']['BookingNo'] = $booking_ref_id;
        $cancel_book['$cancel_book']['CancellationReason'] = "Due to illness";
        $cancel_book['$cancel_book']['AccountId'] = 53; 
        $cancel_book['$cancel_book']['UserName'] = "255872";
        $cancel_book['$cancel_book']['Password'] = "278552";
        $header = array();
        $header = (array)$client->__call('CancelBooking_ByBookingNo', $cancel_book);

        if($header['CancelBooking_ByBookingNoResult'] == "Successfully Cancelled.")/*check $res variable*/
        {
            /*if cancel is succesfull, update the database*/
            if( $this->input->post('confirm_ref_id') ){
                $this->load->model('cab_model');
                $this->cab_model->updateRefundAmtAndCanlAmt( $id, $refundAmt, $canlAmt );
                $this->cab_model->updateTicketStatus( $id );
                $mail_chk = $this->send_cancellation_email( $email_id, $booking_ref_id, $_SESSION['user_details'][0]->user_first_name, $cust_support_data);
                echo json_encode("successful");
            }else{
                $this->load->model('admin/cab_model');
                $this->cab_model->updateTicketStatus( $id, $booking_ref_id );
                print_r($header);die;
            }
        }
        elseif ($header['CancelBooking_ByBookingNoResult'] == "Booking already cancelled" || $header['CancelBooking_ByBookingNoResult'] == -1 ) {
            $this->load->model('admin/cab_model');
            $this->cab_model->updateTicketStatus( $id, $booking_ref_id );
            /*if cancel is not successful redirect to the admin panel of cabs list*/
            if( $this->input->post('confirm_ref_id') ){
                echo json_encode("failed");    
            }else{
                redirect('admin/cabs');
            }
        }
        else
        {
            /*if cancel is not successful redirect to the admin panel of cabs list*/
            if( $this->input->post('booking_ref_id') ){
                echo json_encode("failed");    
            }else{
                echo json_encode($header['CancelBooking_ByBookingNoResult']);die;
            }
        }
      
    }

    public function send_cancellation_email($email_id, $booking_ref_id, $name, $cust_support_data){
            $to = $email_id;
            //define the subject of the email
            $subject = 'Cancellation email'; 
            //define the message to be sent. Each line should be separated with \n
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Your ticket with booking id '.$booking_ref_id.', Has now been cancelled. The refund amount, if any, will be credited back to you in 7-8 working days. Please contact customer support at Phone num: '.$cust_support_data->phone_number.', Email ID: '.$cust_support_data->email.' for any further assistance.</p><hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>'.$cust_support_data->phone_number.'</strong> <br/>Email: <strong><a href="javascript:void(0);">'.$cust_support_data->email.'</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
            //define the headers we want passed. Note that they are separated with \r\n
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Admin <info@farebucket.com>" . "\r\n";
            //send the email
            $mail_sent = @mail( $to, $subject, $message, $headers );
    }

    /*****View booking by dates*****/

    function booking_info_by_dates()
    {
        require_once (APPPATH . 'lib/nusoap.php');
        $wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
        $client_header = new SoapHeader('http://wheelz.wheelzindia.com/ViewBookingInfoWithStatus_BetweenDates','AuthenticationData',false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array($client_header));
        $book_info = array();
        $book_info['ViewBookingInfoWithStatus_BetweenDates']['StartDate'] = "2014-09-20";
        $book_info['ViewBookingInfoWithStatus_BetweenDates']['EndDate'] = "2014-09-25";
        $book_info['ViewBookingInfoWithStatus_BetweenDates']['Status'] = 5;
        $book_info['ViewBookingInfoWithStatus_BetweenDates']['AccountId'] = 53;
        $book_info['ViewBookingInfoWithStatus_BetweenDates']['UserName'] = "255872";
        $book_info['ViewBookingInfoWithStatus_BetweenDates']['Password'] = "278552";
        $header = array();
        $header = (array)$client->__call('ViewBookingInfoWithStatus_BetweenDates',$book_info);
        echo '<pre>';
        print_r($header);
    }

    function check_available_balance(){
        require_once (APPPATH . 'lib/nusoap.php');
        $wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
        $client_header = new SoapHeader("http://wheelz.wheelzindia.com/AvailableBalance_ByAccountID",'AuthenticationData',false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array($client_header));
        $bal = array();
        $bal['AvailableBalance_ByAccountID']['AccountId'] = 53;
        $bal['AvailableBalance_ByAccountID']['UserName'] = "255872";
        $bal['AvailableBalance_ByAccountID']['Password'] = "278552";
        $header = array();
        $header = (array)$client->__call('AvailableBalance_ByAccountID',$bal);

        if( isset($header['AvailableBalance_ByAccountIDResult']) ){
            $data = array(
                'error' => 0,
                'balance' => $header['AvailableBalance_ByAccountIDResult']
            );
        }else{
            $data = array(
                'error' => 1,
                'balance' => "Error, Please Try Again"
            );
        }
        redirect('admin/cabs/show_balance?error='.$data["error"].'&balance='.$data['balance']);
    }

}

?>