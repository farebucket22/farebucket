<?php
session_start();
class New_Request extends MY_Controller
{
    function authenticate()
    {
        $url = 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate';
        $ch = curl_init($url);
        $jsonData = array(
            'ClientId' => 'ApiIntegration',
            'UserName' => 'reddytrip',
            'Password' => 'reddytrip@1',
            'LoginType' => 1,
            'EndUserIp' => '192.168.10.130'
        );
        $jsonDataEncoded = json_encode($jsonData);

        // print_r($jsonDataEncoded);//die;

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result, true);
        print_r($res);
        $token_id = $res['TokenId'];
        $agency_id = $res['Member']['AgencyId'];
        $this->load->model('hotel_model');
        $data = array(
            'token_id' => $token_id,
            'agency_id' => $agency_id
        );
        $this->hotel_model->authentication($data);
    }

    function country_search()
    {
        $url = 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/CountryList';
        $ch = curl_init($url);
        $this->load->model('hotel_model');
        $data = $this->hotel_model->get_token_id();
        $jsonData = array(
            'TokenId' => '' . $data->token_id,
            'ClientId' => 'ApiIntegration',
            'EndUserIp' => '192.168.10.130'
        );
        $jsonDataEncoded = json_encode($jsonData);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result, true);
        $xml = new SimpleXMLElement($res['CountryList']);
        for ($i = 0; $i < count($xml->Country); $i++) {
            $country_code[$i] = (string)$xml->Country[$i]->Code;
            $country_iso[$i] = (string)$xml->Country[$i]->Name;
        }

        $this->city_search($country_code, $country_iso, $data->token_id);
    }

    function city_search()
    {
        $err_msg = [];
        if ($this->input->get(null, true)) {
            $data = $this->input->get(null, true);
            $country_code = explode(",", $data['search-string-multi']);
            $city_name = explode(",", $data['typed-string-multi']);
        }
        else {
            $data = $this->input->post(null, true);
            $country_code = explode(",", $data['search-string-single']);
            $city_name = explode(",", $data['typed-string-single']);
        }

        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();
        $url = 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/DestinationCityList';
        $ch = curl_init($url);
        $jsonData = array(
            'TokenId' => '' . $api_id->token_id,
            'ClientId' => 'ApiIntegration',
            'EndUserIp' => '192.168.10.130',
            'CountryCode' => '' . $country_code[count($country_code) - 2],
        );
        $cityname = '' . $city_name[0];
        $jsonDataEncoded = json_encode($jsonData);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result, true);
        $cityid = 0;
        if ($res['DestinationCityList'] != null) {
            $xml = new SimpleXMLElement($res['DestinationCityList']);
            for ($i = 0; $i < count($xml->City); $i++) {
                if ((string)$xml->City[$i]->CityName == $cityname) {
                    $cityid = (string)$xml->City[$i]->CityId;
                }
            }
        }
        else {
            $err_msg['flag'] = 1;
            $err_msg['msg'] = 'Sorry, An error occoured. Please try again.';
            echo json_encode($err_msg);
            die;
        }

        if ($cityid == 0) {
            $err_msg['flag'] = 2;
            $err_msg['msg'] = 'Sorry there is no hotel available for this Destination.';
            echo json_encode($err_msg);
            die;
        }
        else {
            $this->hotel_search($cityid, $country_code[count($country_code) - 2], $data);
        }
    }

    function work()
    {
        $url = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/GetHotelResult/';
        $ch = curl_init($url);
        $this->load->model('hotel_model');
        $data = $this->hotel_model->get_token_id();

        // print_r($data);die;
        // The JSON data.

        $jsonData = array(
            'BookingMode' => 1,
            'CheckInDate' => '15/01/2015 00:00:00',
            'NoOfNights' => 2,
            'CountryCode' => 'IN',
            'CityId' => '10409',
            'ResultCount' => NULL,
            'PreferredCurrency' => 'INR',
            'GuestNationality' => 'IN',
            'NoOfRooms' => 4,
            'RoomGuests' => array(
                0 => array(
                    'NoOfAdults' => 2,
                    'NoOfChild' => 1,
                    'ChildAge' => array(
                        0 => 0,
                    ) ,
                ) ,
                1 => array(
                    'ChildAge' => array(
                        0 => '12',
                    ) ,
                    'NoOfAdults' => 1,
                    'NoOfChild' => 1,
                ) ,
                2 => array(
                    'ChildAge' => array(
                        0 => '4',
                        1 => '0',
                    ) ,
                    'NoOfAdults' => 1,
                    'NoOfChild' => 2,
                ) ,
                3 => array(
                    'ChildAge' => NULL,
                    'NoOfAdults' => 1,
                    'NoOfChild' => 0,
                )
            ) ,
            'PreferredHotel' => '',
            'MaxRating' => 5,
            'MinRating' => 1,
            'ReviewScore' => NULL,
            'IsNearBySearchAllowed' => false,
            'EndUserIp' => '123.1.1.1',
            'TokenId' => '' . $data->token_id
        );
        $jsonDataEncoded = json_encode($jsonData);

        // print_r($jsonDataEncoded);die;

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result, true);
        print_r($res);
    }

    function hotel_search($city_id, $country_code, $data)
    {
        $url = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/GetHotelResult/';
        $ch = curl_init($url);
        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();
        if (isset($data['single_rooms'])) {
            $room_count = $data['single_rooms'];
            for ($i = 0; $i < $data['single_rooms']; $i++) {
                $j = $i + 1;
                $room[$i]['NoOfAdults'] = intval($data['adult_count_single-' . $j]);
                $room[$i]['NoOfChild'] = intval($data['child_count_single-' . $j]);
                if ($data['child_count_single-' . $j] == 0) $room[$i]['ChildAge'][0] = 0;
                for ($k = 0; $k < $data['child_count_single-' . $j]; $k++) {
                    $room[$i]['ChildAge'][$k] = (string)12;
                }
            }
        }
        else {
            $room_count = $data['multi_rooms'];
            for ($i = 0; $i < $data['multi_rooms']; $i++) {
                $j = $i + 1;
                $room[$i]['NoOfAdults'] = intval($data['adult_count_multi-' . $j]);
                $room[$i]['NoOfChild'] = intval($data['child_count_multi-' . $j]);
                if ($data['child_count_multi-' . $j] == 0) $room[$i]['ChildAge'][0] = 0;
                for ($k = 0; $k < $data['child_count_multi-' . $j]; $k++) {
                    $room[$i]['ChildAge'][$k] = (string)12;
                }
            }
        }

        // The JSON data.

        $jsonData = array(
            'BookingMode' => 1,
            'CheckInDate' => '' . $data['checkin_time'],
            'NoOfNights' => 2,
            'CountryCode' => '' . $country_code,
            'CityId' => '' . $city_id,
            'ResultCount' => NULL,
            'PreferredCurrency' => 'INR',
            'GuestNationality' => 'IN',
            'NoOfRooms' => $room_count,
            'RoomGuests' => $room,
            'PreferredHotel' => '',
            'MaxRating' => 5,
            'MinRating' => 1,
            'ReviewScore' => NULL,
            'IsNearBySearchAllowed' => false,
            'EndUserIp' => '123.1.1.1',
            'TokenId' => '' . $api_id->token_id
        );
        $jsonDataEncoded = json_encode($jsonData);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        echo "<pre>";
        print_r($result);die;
        curl_close($ch);
        $temp_arr = json_decode($result, true);

        $high = 0;
        $low = 999999999;
        $hotelLocations = [];
        $hotelUniqueLocationsArr = [];
        $hotelLocationList = [];

        if( isset($temp_arr['HotelSearchResult']['HotelResults']) ){            
            foreach( $temp_arr['HotelSearchResult']['HotelResults'] as $hr ){
                $hotelLocations[] = $hr['HotelLocation'];
                if( $hr['Price']['PublishedPrice'] > $high ){
                    $high = $hr['Price']['PublishedPrice'];
                }

                if( $hr['Price']['PublishedPrice'] < $low ){
                    $low = $hr['Price']['PublishedPrice'];   
                }
            }

            $temp_arr['high'] = $high;
            $temp_arr['low'] = $low;
        }

        $hotelUniqueLocationsArr = array_unique($hotelLocations);
        foreach($hotelUniqueLocationsArr as $unique) {
            if ($unique != '' || $unique != null) {
                $hotelLocationList[] = $unique;
            }
        }
        sort($hotelLocationList);
        $temp_arr['hotelLocationList'] = $hotelLocationList;

        if( isset($temp_arr['HotelSearchResult']['Error']['ErrorCode']) && $temp_arr['HotelSearchResult']['Error']['ErrorCode'] == 0){
            $result = json_encode($temp_arr);
            echo $result;
        }else{
            $err_msg['flag'] = 3;
            $err_msg['msg'] = 'An Error occurred. Please try again.';
            echo json_encode($err_msg);
        }
    }

    function room_info()
    {
        $data = $this->input->post(null, true);
        $url = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/GetHotelRoom/';
        $ch = curl_init($url);
        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();
        $jsonData = array(
            'EndUserIp' => '192.168.10.130',
            'TokenId' => '' . $api_id->token_id,
            'TraceId' => '' . $data['trace_id'],
            'ResultIndex' => $data['result_index'],
            'HotelCode' => '' . $data['hotel_code'],
        );
        $jsonDataEncoded = json_encode($jsonData);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        echo $result;
    }

    function hotel_info()
    {
        $data = $this->input->post(null, true);
        $url = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/GetHotelInfo/';
        $ch = curl_init($url);
        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();
        $jsonData = array(
            'ResultIndex' => $data['result_index'],
            'HotelCode' => '' . $data['hotel_code'],
            'TokenId' => '' . $api_id->token_id,
            'TraceId' => '' . $data['trace_id'],
            'EndUserIp' => '192.168.10.130'
        );
        $jsonDataEncoded = json_encode($jsonData);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        echo $result;
    }

    function selected_hotel()
    {
        if ((isset($_SESSION['hotel_query'])) && (isset($_SESSION['hotel_query']['multi_rooms']))) {
            redirect('new_request/multi_hotel');
        }
        else {
            $data = $this->input->post(null, true);
            $this->load->model('admin/convenience_model');
            $convenience = $this->convenience_model->get_convenience_charge();
            $data['convenience_charge'] = $convenience->convenience_charge;
            $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
            $this->load->view('common/header.php');
            $this->load->view('hotels/hotel_traveller_details.php', array(
                'data' => $data
            ));
            $this->load->view('common/footer.php');
        }
    }

    function multi_hotel()
    {
        $data = $_SESSION['hotel_data'];
        $this->load->model('admin/convenience_model');
        $convenience = $this->convenience_model->get_convenience_charge();
        $data['convenience_charge'] = $convenience->convenience_charge;
        $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
        $this->load->view('common/header.php');
        $this->load->view('hotels/hotel_multi_traveller_details.php', array(
            'data' => $data
        ));
        $this->load->view('common/footer.php');
    }

    function block_room()
    {
        $single = $this->input->post('single_rooms');
        $multi = $this->input->post('multi_rooms');
        if (isset($single)) {
            $room = explode("~s~", $this->input->post('room_type'));
            $TraceId = json_decode($this->input->post('trace_id'));
            $info = json_decode($this->input->post('hotel_info'));
        }
        else {
            $room = explode("~s~", $this->input->post('room_type'));
            $TraceId = json_decode($this->input->post('trace_id'));
            $info = json_decode($this->input->post('hotel_info'));
        }

        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();
        $url = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/BlockRoom/';
        $ch = curl_init($url);
        if ($single) {
            $total_room = $this->input->post('single_rooms');
        }
        else {
            $total_room = $this->input->post('multi_rooms');
        }

        for ($i = 0; $i < $total_room; $i++) {
            $hotel_details[$i]['RoomIndex'] = "" . $room[0];
            $hotel_details[$i]['RoomTypeCode'] = (isset($room[1])) ? "" . $room[1] : "";
            $hotel_details[$i]['RoomTypeName'] = (isset($room[2])) ? "" . $room[2] : "";
            $hotel_details[$i]['RatePlanCode'] = (isset($room[3])) ? "" . $room[3] : "";
            $hotel_details[$i]['BedTypeCode'] = null;
            $hotel_details[$i]['SmokingPreference'] = 0;
            $hotel_details[$i]['Supplements'] = null;
            $hotel_details[$i]['Price']['CurrencyCode'] = "" . ($info->Price->CurrencyCode / $total_room);
            $hotel_details[$i]['Price']['RoomPrice'] = "" . ($info->Price->RoomPrice / $total_room);
            $hotel_details[$i]['Price']['Tax'] = "" . ($info->Price->Tax / $total_room);
            $hotel_details[$i]['Price']['ExtraGuestCharge'] = "" . ($info->Price->ExtraGuestCharge / $total_room);
            $hotel_details[$i]['Price']['ChildCharge'] = "" . ($info->Price->ChildCharge / $total_room);
            $hotel_details[$i]['Price']['OtherCharges'] = "" . ($info->Price->OtherCharges / $total_room);
            $hotel_details[$i]['Price']['Discount'] = "" . ($info->Price->Discount / $total_room);
            $hotel_details[$i]['Price']['PublishedPrice'] = "" . ($info->Price->PublishedPrice / $total_room);
            $hotel_details[$i]['Price']['PublishedPriceRoundedOff'] = "" . ($info->Price->PublishedPriceRoundedOff / $total_room);
            $hotel_details[$i]['Price']['OfferedPrice'] = "" . ($info->Price->OfferedPrice / $total_room);
            $hotel_details[$i]['Price']['OfferedPriceRoundedOff'] = "" . ($info->Price->OfferedPriceRoundedOff / $total_room);
            $hotel_details[$i]['Price']['AgentCommission'] = "" . ($info->Price->AgentCommission / $total_room);
            $hotel_details[$i]['Price']['AgentMarkUp'] = "" . ($info->Price->AgentMarkUp / $total_room);
            $hotel_details[$i]['Price']['ServiceTax'] = "" . ($info->Price->ServiceTax / $total_room);
            $hotel_details[$i]['Price']['TDS'] = "" . ($info->Price->TDS / $total_room);
        }

        $jsonData = array(
            'ResultIndex' => $info->ResultIndex,
            'HotelCode' => $info->HotelCode,
            'HotelName' => $info->HotelName,
            'GuestNationality' => 'IN',
            'NoOfRooms' => $total_room,
            'ClientReferenceNo' => 0,
            "IsVoucherBooking" => true,
            'HotelRoomsDetails' => $hotel_details,
            'TokenId' => '' . $api_id->token_id,
            'TraceId' => trim($TraceId) ,
            'EndUserIp' => '192.168.10.130'
        );
        $jsonDataEncoded = stripslashes(json_encode($jsonData));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($result, true);
        if (isset($res['BlockRoomResult']['AvailabilityType'])) {
            if ($res['BlockRoomResult']['AvailabilityType'] == 'Available') {
                echo json_encode($result);
            }
            else {
                echo json_encode($result);
            }
        }
        else {
            echo json_encode($res);
        }
    }

    function payment_gateway(){
        if( isset($_POST['extra_info']) ){
            if( $_POST['call_func'] == 'single' ){
                $_SESSION['hotel_single_post_data'] = $_POST;

                $characters = '0123456789';
                $hash_val = 'FBFL';
                for ($i = 0; $i < 9; $i++) {
                    $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
                }
                $_SESSION['farebucket_txnid'] = $hash_val;

                (isset($_SESSION['user_details'][0]->user_first_name)) ? $firstname = $_SESSION['user_details'][0]->user_first_name : $firstname = 'abc';
                (isset($_SESSION['user_details'][0]->user_email)) ? $email = $_SESSION['user_details'][0]->user_email : $email = 'abc@abc.com';
                (isset($_SESSION['user_details'][0]->user_mobile)) ? $phone = $_SESSION['user_details'][0]->user_mobile : $phone = '1234567890';

                $params = array (
                    'key' => $_POST["key"], 
                    'txnid' =>  $hash_val, 
                    'amount' => $_SESSION['hotel_price_single'],
                    'firstname' => $firstname, 
                    'email' => $email, 
                    'phone' => $phone,
                    'productinfo' => $_POST["productinfo"], 
                    'surl' => 'new_request/hotel_book',
                    'furl' => 'api/flights/booking_failed'
                );
            }else{
                $_SESSION['hotel_multi_post_data'] = $_POST;

                (isset($_SESSION['user_details'][0]->user_first_name)) ? $firstname = $_SESSION['user_details'][0]->user_first_name : $firstname = 'abc';
                (isset($_SESSION['user_details'][0]->user_email)) ? $email = $_SESSION['user_details'][0]->user_email : $email = 'abc@abc.com';
                (isset($_SESSION['user_details'][0]->user_mobile)) ? $phone = $_SESSION['user_details'][0]->user_mobile : $phone = '1234567890';

                $characters = '0123456789';
                $hash_val = 'FBFL';
                for ($i = 0; $i < 9; $i++) {
                    $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
                }
                $_SESSION['farebucket_txnid'] = $hash_val;

                $params = array (
                    'key' => $_POST["key"], 
                    'txnid' =>  $hash_val, 
                    'amount' => $_SESSION['hotel_price_multi'],
                    'firstname' => $firstname, 
                    'email' => $email, 
                    'phone' => $phone,
                    'productinfo' => $_POST["productinfo"], 
                    'surl' => 'new_request/multi_hotel_book',
                    'furl' => 'api/flights/booking_failed'
                );
            }
        }else{
            if( isset($_SESSION['hotel_single_post_data']) ){
                $params = array (
                    'surl' => 'new_request/hotel_book',
                    'furl' => 'api/flights/booking_failed'
                );
            }else if( isset($_SESSION['hotel_multi_post_data']) ){
                $params = array (
                    'surl' => 'new_request/multi_hotel_book',
                    'furl' => 'api/flights/booking_failed'
                );
            }
        }

        require_once (APPPATH . 'lib/payu.php');
        if ( count( $_POST ) ) 
        pay_page( $params, 'eCwWELxi' );
    }

    function hotel_book()
    {
        $fbBooking = 'FBHO';
        $this->load->model('hotel_model');
        $returnId = $this->hotel_model->getLastFbBookingId();
        if(strlen($returnId) === 0){
            $randomNum = 1;
        }
        else{
            $splitReturnID = explode("FBHO",$returnId);
            $randomNum = $splitReturnID[1] + 1;
        } 
        
        $randomNum = sprintf("%06d",$randomNum);
        $fbBookingId = $fbBooking . $randomNum;
        $data_store['fb_bookingId'] = $fbBookingId;

        $passenger = $_SESSION['hotel_single_post_data'];
        $data = array();
        $data = $passenger;
        $data['extra_info'] = json_decode($passenger['extra_info']);
        $data['info'] = json_decode($passenger['info']);
        $data['room_details'] = json_decode($passenger['room_info']);
        unset($passenger['extra_info']);
        unset($passenger['info']);
        unset($passenger['room_info']);
        $room = explode("~s~", $data['room_type']);
        $total_room = $data['count'];
        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();
        $url = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/Book/';
        $ch = curl_init($url);
        for ($i = 0; $i < $total_room; $i++) {
            $hotel_details[$i]['RoomIndex'] = "" . $room[0];
            $hotel_details[$i]['RoomTypeCode'] = "" . $room[1];
            $hotel_details[$i]['RoomTypeName'] = "" . $room[2];
            $hotel_details[$i]['RatePlanCode'] = "" . $room[3];
            $hotel_details[$i]['BedTypeCode'] = null;
            $hotel_details[$i]['SmokingPreference'] = 0;
            $hotel_details[$i]['Supplements'] = null;
            $hotel_details[$i]['Price']['CurrencyCode'] = "" . ($data['info']->Price->CurrencyCode / $total_room);
            $hotel_details[$i]['Price']['RoomPrice'] = "" . ($data['info']->Price->RoomPrice / $total_room);
            $hotel_details[$i]['Price']['Tax'] = "" . ($data['info']->Price->Tax / $total_room);
            $hotel_details[$i]['Price']['ExtraGuestCharge'] = "" . ($data['info']->Price->ExtraGuestCharge / $total_room);
            $hotel_details[$i]['Price']['ChildCharge'] = "" . ($data['info']->Price->ChildCharge / $total_room);
            $hotel_details[$i]['Price']['OtherCharges'] = "" . ($data['info']->Price->OtherCharges / $total_room);
            $hotel_details[$i]['Price']['Discount'] = "" . ($data['info']->Price->Discount / $total_room);
            $hotel_details[$i]['Price']['PublishedPrice'] = "" . ($data['info']->Price->PublishedPrice / $total_room);
            $hotel_details[$i]['Price']['PublishedPriceRoundedOff'] = "" . ($data['info']->Price->PublishedPriceRoundedOff / $total_room);
            $hotel_details[$i]['Price']['OfferedPrice'] = "" . ($data['info']->Price->OfferedPrice / $total_room);
            $hotel_details[$i]['Price']['OfferedPriceRoundedOff'] = "" . ($data['info']->Price->OfferedPriceRoundedOff / $total_room);
            $hotel_details[$i]['Price']['AgentCommission'] = "" . ($data['info']->Price->AgentCommission / $total_room);
            $hotel_details[$i]['Price']['AgentMarkUp'] = "" . ($data['info']->Price->AgentMarkUp / $total_room);
            $hotel_details[$i]['Price']['ServiceTax'] = "" . ($data['info']->Price->ServiceTax / $total_room);
            $hotel_details[$i]['Price']['TDS'] = "" . ($data['info']->Price->TDS / $total_room);

            $adult_count = $child_count = $adult_count_prev = 0;

            foreach ($passenger as $p_key => $p_val) {
                if( $p_key == 'adult_'.($i) ){
                    $adult_count_prev = $p_val;
                }
                if( $p_key == 'adult_'.($i+1) ){
                    $adult_count = $p_val;
                }
                if( $p_key == 'child_'.($i+1) ){
                    $child_count = $p_val;
                }
            }

            for ($j = 0; $j < $adult_count ; $j++) {                    
                if( $j == 0 ){
                    $hotel_details[$i]['HotelPassenger'][$j]['Title'] = $passenger['title_a'][$j+$adult_count_prev];
                    $hotel_details[$i]['HotelPassenger'][$j]['FirstName'] = $passenger['first_name_a'][$j+$adult_count_prev];
                    $hotel_details[$i]['HotelPassenger'][$j]['MiddleName'] = "";
                    $hotel_details[$i]['HotelPassenger'][$j]['LastName'] = $passenger['last_name_a'][$j+$adult_count_prev];
                    $hotel_details[$i]['HotelPassenger'][$j]['Phoneno'] = "";
                    $hotel_details[$i]['HotelPassenger'][$j]['Email'] = "";
                    $hotel_details[$i]['HotelPassenger'][$j]['PaxType'] = 1;
                    $hotel_details[$i]['HotelPassenger'][$j]['LeadPassenger'] = true;
                    $hotel_details[$i]['HotelPassenger'][$j]['Age'] = $passenger['age_a'][$j+$adult_count_prev];
                    $hotel_details[$i]['HotelPassenger'][$j]['PassportNo'] = null;
                    $hotel_details[$i]['HotelPassenger'][$j]['PassportIssueDate'] = null;
                    $hotel_details[$i]['HotelPassenger'][$j]['PassportExpDate'] = null;
                }else{
                    $hotel_details[$i]['HotelPassenger'][$j]['Title'] = $passenger['title_a'][$j+$adult_count_prev];
                    $hotel_details[$i]['HotelPassenger'][$j]['FirstName'] = $passenger['first_name_a'][$j+$adult_count_prev];
                    $hotel_details[$i]['HotelPassenger'][$j]['MiddleName'] = "";
                    $hotel_details[$i]['HotelPassenger'][$j]['LastName'] = $passenger['last_name_a'][$j+$adult_count_prev];
                    $hotel_details[$i]['HotelPassenger'][$j]['Phoneno'] = "";
                    $hotel_details[$i]['HotelPassenger'][$j]['Email'] = "";
                    $hotel_details[$i]['HotelPassenger'][$j]['PaxType'] = 1;
                    $hotel_details[$i]['HotelPassenger'][$j]['LeadPassenger'] = false;
                    $hotel_details[$i]['HotelPassenger'][$j]['Age'] = $passenger['age_a'][$j+$adult_count_prev];
                    $hotel_details[$i]['HotelPassenger'][$j]['PassportNo'] = null;
                    $hotel_details[$i]['HotelPassenger'][$j]['PassportIssueDate'] = null;
                    $hotel_details[$i]['HotelPassenger'][$j]['PassportExpDate'] = null;
                }
            }

            $v = $j;

            for ($j = $v, $o = 0; $j < $child_count+$v ; $j++, $o++) {
                $hotel_details[$i]['HotelPassenger'][$j]['Title'] = $passenger['title_k'][$o];
                $hotel_details[$i]['HotelPassenger'][$j]['FirstName'] = $passenger['first_name_k'][$o];
                $hotel_details[$i]['HotelPassenger'][$j]['MiddleName'] = "";
                $hotel_details[$i]['HotelPassenger'][$j]['LastName'] = $passenger['last_name_k'][$o];
                $hotel_details[$i]['HotelPassenger'][$j]['Phoneno'] = "";
                $hotel_details[$i]['HotelPassenger'][$j]['Email'] = "";
                $hotel_details[$i]['HotelPassenger'][$j]['PaxType'] = 2;
                $hotel_details[$i]['HotelPassenger'][$j]['LeadPassenger'] = false;
                $hotel_details[$i]['HotelPassenger'][$j]['Age'] = $passenger['age_k'][$o];
                $hotel_details[$i]['HotelPassenger'][$j]['PassportNo'] = null;
                $hotel_details[$i]['HotelPassenger'][$j]['PassportIssueDate'] = null;
                $hotel_details[$i]['HotelPassenger'][$j]['PassportExpDate'] = null;
            }

        }

        $jsonData = array(
            'ResultIndex' => $data['info']->ResultIndex,
            'HotelCode' => $data['info']->HotelCode,
            'HotelName' => $data['info']->HotelName,
            'GuestNationality' => 'IN',
            'NoOfRooms' => $data['count'],
            'ClientReferenceNo' => 0,
            "IsVoucherBooking" => false,
            'HotelRoomsDetails' => $hotel_details,
            'TokenId' => '' . $api_id->token_id,
            'TraceId' => '' . $data['extra_info']->HotelInfoResult->TraceId,
            'EndUserIp' => '192.168.10.130'
        );
        $jsonDataEncoded = json_encode($jsonData);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($result, true);

        $ne_info = array();
        if( isset($_SESSION['hotel_single_post_data']) ){
            $ne_info['Address'] = $data['extra_info']->HotelInfoResult->HotelDetails->Address;
            $ne_info['hotel_details'] = $hotel_details;
            $ne = json_encode($ne_info);
        }else{
            $ne_info['Address'] = $_SESSION['hotel_data'][$k]['hotel_extra_info']->Address;
            $ne_info['room_type'] = $_SESSION['hotel_data'][$k]['room_type'];
            $ne_info['hotel_details'] = $hotel_details;
            $ne = json_encode($ne_info);
        }

        if (isset($res['BookResult']['HotelBookingStatus']) && $res['BookResult']['HotelBookingStatus'] === 'Confirmed') {
            $pass['destination'] = $data['des'];
            $pass['room_count'] = $data['count'];
            $pass['check_in'] = $data['check_in'];
            $pass['check_out'] = $data['check_out'];
            $pass['hotel_name'] = $data['extra_info']->HotelInfoResult->HotelDetails->HotelName;
            $pass['hotel_price'] = $data['info']->Price->PublishedPrice;
            $pass['BookingRefNo'] = $res['BookResult']['BookingRefNo'];
            $pass['ConfirmationNo'] = $res['BookResult']['ConfirmationNo'];
            $data_store['destination'] = $data['des'];
            $data_store['room_count'] = $data['count'];
            $data_store['check_in'] = $data['check_in'];
            $data_store['check_out'] = $data['check_out'];
            $data_store['hotel_name'] = $data['extra_info']->HotelInfoResult->HotelDetails->HotelName;
            $data_store['hotel_price'] = $data['info']->Price->PublishedPrice;
            $data_store['info'] = $data['room_info'];
            $data_store['necessary_info'] = $ne;
            $data_store['title'] = implode(",", $data['title_a']);
            $data_store['first_name'] = implode(",", $data['first_name_a']);
            $data_store['last_name'] = implode(",", $data['last_name_a']);
            $data_store['age'] = implode(",", $data['age_a']);
            $data_store['pass_number'] = implode(",", $data['pass_number_a']);
            $data_store['pass_expiry'] = implode(",", $data['pass_expiry_a']);
            $data_store['status'] = "Success";
            $data_store['BookingRefNo'] = (!empty($res['BookResult']['BookingRefNo'])) ? $res['BookResult']['BookingRefNo'] : '12345';
            $data_store['ConfirmationNo'] = $res['BookResult']['ConfirmationNo'];
            $_SESSION['pass'][0] = $pass;
            $characters = '0123456789';
            $hash_val = 'FBHO';
            for ($i = 0; $i < 7; $i++) {
                $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
            }
            $data_store['hash_val'] = $hash_val;
            $this->load->model('hotel_model');
            $this->hotel_model->hotel_details($data_store);

            $mail_chk = $this->send_email($_SESSION['user_details'][0]->user_email, $data_store['BookingRefNo'], $_SESSION['user_details'][0]->user_first_name);
            redirect('new_request/ticket_page?BookingRefNo='.$data_store['BookingRefNo']);
        }
        else {

            redirect('new_request/booking_failed');
        }
    }

    public function send_email($email_id, $hash_val, $name){
        $link = site_url('new_request/ticket_page?BookingRefNo='.$hash_val);
        $to = $email_id;
        //define the subject of the email
        $subject = 'Ticket Links'; 
        //define the message to be sent. Each line should be separated with \n
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Thank you for choosing Farebucket. Please find the link to your ticket(s) below.</p>Links: <a href="'.$link.'">Ticket</a> <hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>1234567890</strong> <br/>Email: <strong><a href="emailto:hseldon@trantor.com">admin@farebucket.com</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';

        //define the headers we want passed. Note that they are separated with \r\n
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Sender <admin@farebucket.com>" . "\r\n";

        $mail_sent = @mail( $to, $subject, $message, $headers );
    }

    function multi_hotel_book()
    {
        for ($k = 0; $k < count($_SESSION['hotel_data']); $k++) {
            $passenger = $_SESSION['hotel_multi_post_data'];
            $data[$k]['extra_info'] = $_SESSION['hotel_data'][$k]['hotel_extra_info'];
            $data[$k]['info'] = $_SESSION['hotel_data'][$k]['hotel_info'];
            $data[$k]['room_details'] = $_SESSION['hotel_data'][$k]['room_details'];
            $room[$k] = explode("~s~", $_SESSION['hotel_data'][$k]['room_type']);
            $total_room = $_SESSION['hotel_data'][0]['multi_rooms'];
            $this->load->model('hotel_model');
            $api_id = $this->hotel_model->get_token_id();
            $url = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/Book/';
            $ch = curl_init($url);
            for ($i = 0; $i < $total_room; $i++) {
                $hotel_details[$i]['RoomIndex'] = "" . $room[$k][0];
                $hotel_details[$i]['RoomTypeCode'] = "" . $room[$k][1];
                $hotel_details[$i]['RoomTypeName'] = "" . $room[$k][2];
                $hotel_details[$i]['RatePlanCode'] = "" . $room[$k][3];
                $hotel_details[$i]['BedTypeCode'] = null;
                $hotel_details[$i]['SmokingPreference'] = 0;
                $hotel_details[$i]['Supplements'] = null;
                $hotel_details[$i]['Price']['CurrencyCode'] = "" . ($data[$k]['info']->Price->CurrencyCode / $total_room);
                $hotel_details[$i]['Price']['RoomPrice'] = "" . ($data[$k]['info']->Price->RoomPrice / $total_room);
                $hotel_details[$i]['Price']['Tax'] = "" . ($data[$k]['info']->Price->Tax / $total_room);
                $hotel_details[$i]['Price']['ExtraGuestCharge'] = "" . ($data[$k]['info']->Price->ExtraGuestCharge / $total_room);
                $hotel_details[$i]['Price']['ChildCharge'] = "" . ($data[$k]['info']->Price->ChildCharge / $total_room);
                $hotel_details[$i]['Price']['OtherCharges'] = "" . ($data[$k]['info']->Price->OtherCharges / $total_room);
                $hotel_details[$i]['Price']['Discount'] = "" . ($data[$k]['info']->Price->Discount / $total_room);
                $hotel_details[$i]['Price']['PublishedPrice'] = "" . ($data[$k]['info']->Price->PublishedPrice / $total_room);
                $hotel_details[$i]['Price']['PublishedPriceRoundedOff'] = "" . ($data[$k]['info']->Price->PublishedPriceRoundedOff / $total_room);
                $hotel_details[$i]['Price']['OfferedPrice'] = "" . ($data[$k]['info']->Price->OfferedPrice / $total_room);
                $hotel_details[$i]['Price']['OfferedPriceRoundedOff'] = "" . ($data[$k]['info']->Price->OfferedPriceRoundedOff / $total_room);
                $hotel_details[$i]['Price']['AgentCommission'] = "" . ($data[$k]['info']->Price->AgentCommission / $total_room);
                $hotel_details[$i]['Price']['AgentMarkUp'] = "" . ($data[$k]['info']->Price->AgentMarkUp / $total_room);
                $hotel_details[$i]['Price']['ServiceTax'] = "" . ($data[$k]['info']->Price->ServiceTax / $total_room);
                $hotel_details[$i]['Price']['TDS'] = "" . ($data[$k]['info']->Price->TDS / $total_room);

                $adult_count = $child_count = $adult_count_prev = 0;


                // this might not work for 3 rooms. cause of adult_count_prev
                // incase it doesnt you have to adult_count_prev += pval;
                //or make an array and do an array sum everytime
                
                foreach ($passenger as $p_key => $p_val) {
                    if( $p_key == 'adult_'.($i) ){
                        $adult_count_prev = $p_val;
                    }
                    if( $p_key == 'adult_'.($i+1) ){
                        $adult_count = $p_val;
                    }
                    if( $p_key == 'child_'.($i+1) ){
                        $child_count = $p_val;
                    }
                }

                for ($j = 0; $j < $adult_count ; $j++) {                    
                    if( $j == 0 ){
                        $hotel_details[$i]['HotelPassenger'][$j]['Title'] = $passenger['title_a'][$j+$adult_count_prev];
                        $hotel_details[$i]['HotelPassenger'][$j]['FirstName'] = $passenger['first_name_a'][$j+$adult_count_prev];
                        $hotel_details[$i]['HotelPassenger'][$j]['MiddleName'] = "";
                        $hotel_details[$i]['HotelPassenger'][$j]['LastName'] = $passenger['last_name_a'][$j+$adult_count_prev];
                        $hotel_details[$i]['HotelPassenger'][$j]['Phoneno'] = "";
                        $hotel_details[$i]['HotelPassenger'][$j]['Email'] = "";
                        $hotel_details[$i]['HotelPassenger'][$j]['PaxType'] = 1;
                        $hotel_details[$i]['HotelPassenger'][$j]['LeadPassenger'] = true;
                        $hotel_details[$i]['HotelPassenger'][$j]['Age'] = $passenger['age_a'][$j+$adult_count_prev];
                        $hotel_details[$i]['HotelPassenger'][$j]['PassportNo'] = null;
                        $hotel_details[$i]['HotelPassenger'][$j]['PassportIssueDate'] = null;
                        $hotel_details[$i]['HotelPassenger'][$j]['PassportExpDate'] = null;
                    }else{
                        $hotel_details[$i]['HotelPassenger'][$j]['Title'] = $passenger['title_a'][$j+$adult_count_prev];
                        $hotel_details[$i]['HotelPassenger'][$j]['FirstName'] = $passenger['first_name_a'][$j+$adult_count_prev];
                        $hotel_details[$i]['HotelPassenger'][$j]['MiddleName'] = "";
                        $hotel_details[$i]['HotelPassenger'][$j]['LastName'] = $passenger['last_name_a'][$j+$adult_count_prev];
                        $hotel_details[$i]['HotelPassenger'][$j]['Phoneno'] = "";
                        $hotel_details[$i]['HotelPassenger'][$j]['Email'] = "";
                        $hotel_details[$i]['HotelPassenger'][$j]['PaxType'] = 1;
                        $hotel_details[$i]['HotelPassenger'][$j]['LeadPassenger'] = false;
                        $hotel_details[$i]['HotelPassenger'][$j]['Age'] = $passenger['age_a'][$j+$adult_count_prev];
                        $hotel_details[$i]['HotelPassenger'][$j]['PassportNo'] = null;
                        $hotel_details[$i]['HotelPassenger'][$j]['PassportIssueDate'] = null;
                        $hotel_details[$i]['HotelPassenger'][$j]['PassportExpDate'] = null;
                    }
                }

                $v = $j;

                for ($j = $v, $o = 0; $j < $child_count+$v ; $j++, $o++) {
                    $hotel_details[$i]['HotelPassenger'][$j]['Title'] = $passenger['title_k'][$o];
                    $hotel_details[$i]['HotelPassenger'][$j]['FirstName'] = $passenger['first_name_k'][$o];
                    $hotel_details[$i]['HotelPassenger'][$j]['MiddleName'] = "";
                    $hotel_details[$i]['HotelPassenger'][$j]['LastName'] = $passenger['last_name_k'][$o];
                    $hotel_details[$i]['HotelPassenger'][$j]['Phoneno'] = "";
                    $hotel_details[$i]['HotelPassenger'][$j]['Email'] = "";
                    $hotel_details[$i]['HotelPassenger'][$j]['PaxType'] = 2;
                    $hotel_details[$i]['HotelPassenger'][$j]['LeadPassenger'] = false;
                    $hotel_details[$i]['HotelPassenger'][$j]['Age'] = $passenger['age_k'][$o];
                    $hotel_details[$i]['HotelPassenger'][$j]['PassportNo'] = null;
                    $hotel_details[$i]['HotelPassenger'][$j]['PassportIssueDate'] = null;
                    $hotel_details[$i]['HotelPassenger'][$j]['PassportExpDate'] = null;
                }
            }

            $jsonData = array(
                'ResultIndex' => $data[$k]['info']->ResultIndex,
                'HotelCode' => $data[$k]['info']->HotelCode,
                'HotelName' => $data[$k]['info']->HotelName,
                'GuestNationality' => 'IN',
                'NoOfRooms' => $_SESSION['hotel_data'][$k]['multi_rooms'],
                'ClientReferenceNo' => 0,
                "IsVoucherBooking" => true,
                'HotelRoomsDetails' => $hotel_details,
                'TokenId' => '' . $api_id->token_id,
                'TraceId' => '' . $data[$k]['room_details']->GetHotelRoomResult->TraceId,
                'EndUserIp' => '192.168.10.130'
            );
            $jsonDataEncoded = json_encode($jsonData);
            print_r($jsonDataEncoded);die;
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            $res = json_decode($result, true);
            print_r($result);

            $ne_info = array();
            $ne_info['Address'] = $_SESSION['hotel_data'][$k]['hotel_extra_info']->Address;
            $ne_info['room_type'] = $_SESSION['hotel_data'][$k]['room_type'];
            $ne_info['hotel_details'] = $hotel_details;
            $ne = json_encode($ne_info);

            if (isset($res['BookResult']['BookingRefNo'])) {
                $pass['destination'] = $_SESSION['hotel_data'][$k]['typed-string-multi'];
                $pass['room_count'] = $total_room;
                $pass['check_in'] = $_SESSION['hotel_data'][$k]['checkin_time'];
                $pass['check_out'] = $_SESSION['hotel_data'][$k]['checkout_time'];
                $pass['hotel_name'] = $data[$k]['info']->HotelName;
                $pass['hotel_price'] = $data[$k]['info']->Price->PublishedPrice;
                $pass['BookingRefNo'] = $res['BookResult']['BookingRefNo'];
                $pass['ConfirmationNo'] = $res['BookResult']['ConfirmationNo'];
                $data_store['destination'] = $_SESSION['hotel_data'][$k]['typed-string-multi'];
                $data_store['room_count'] = $total_room;
                $data_store['check_in'] = $_SESSION['hotel_data'][$k]['checkin_time'];
                $data_store['check_out'] = $_SESSION['hotel_data'][$k]['checkout_time'];
                $data_store['hotel_name'] = $data[$k]['info']->HotelName;
                $data_store['hotel_price'] = $data[$k]['info']->Price->PublishedPrice;
                $data_store['info'] = json_encode($data[$k]['room_details']);
                $data_store['necessary_info'] = $ne;
                $data_store['title'] = implode(",", $passenger['title_a']);
                $data_store['first_name'] = implode(",", $passenger['first_name_a']);
                $data_store['last_name'] = implode(",", $passenger['last_name_a']);
                $data_store['age'] = implode(",", $passenger['age_a']);
                $data_store['pass_number'] = implode(",", $passenger['pass_number_a']);
                $data_store['pass_expiry'] = implode(",", $passenger['pass_expiry_a']);
                $data_store['status'] = "Success";
                $data_store['BookingRefNo'] = $res['BookResult']['BookingRefNo'];
                $data_store['ConfirmationNo'] = $res['BookResult']['ConfirmationNo'];
                $_SESSION['pass'][$k] = $pass;
                $characters = '0123456789';
                $hash_val = 'FBHO';
                for ($i = 0; $i < 7; $i++) {
                    $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
                }
                $data_store['hash_val'] = $hash_val;
                $this->load->model('hotel_model');
                $this->hotel_model->hotel_details($data_store);
            }
            else {
                die;
                redirect('new_request/booking_failed');
            }
        }
        redirect('new_request/ticket_page');
    }

    function ticket_page()
    {
        unset($_SESSION['currentUrl']);
        if( isset($_SESSION['pass']) )$pass = $_SESSION['pass'];
        else{
            $data_store = $_GET['BookingRefNo'];
            $this->load->model('hotel_model');
            $this->hotel_model->get_hotel_details($data_store);
        }


        $this->load->view('common/header.php');
        $this->load->view('hotels/ticket_page.php', array(
            'data' => $pass
        ));
        $this->load->view('common/footer.php');
    }

    function get_booking_details()
    {
        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();
        $details = $this->hotel_model->get_hotel_details($this->input->get('confirm_no'));
        $this->load->view('common/header.php');
        $this->load->view('hotels/invoice_page.php', array(
            'data' => $details,
            'api_id' => $api_id
        ));
        $this->load->view('common/footer.php');

        // $url = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/HotelBookingDetail/';
        // $ch = curl_init($url);
        // $this->load->model('hotel_model');
        // $jsonData = array (
        //   'EndUserIp' => '192.168.10.130',
        //   'TokenId' => ''.$api_id->token_id,
        //   'BookingRefNo' => $this->input->get('booking_ref'),
        //   'ConfirmationNo' => $this->input->get('confirm_no'),
        //   'ClientReferenceNo' => 0
        // );
        // $jsonDataEncoded = json_encode($jsonData);
        // // print_r($jsonDataEncoded);
        // curl_setopt($ch, CURLOPT_POST,1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $result = curl_exec($ch);
        // curl_close($ch);
        // $res = json_decode($result,true);
        // print_r($res);

    }

    function hotel_cancel()
    {
        echo "<pre>";
        print_r($_POST);die;
        /*since cancel is not working ....now its redirected to the hotel admin panel list */
        print_r('please go back...cancel api is still not done');
        die;
        redirect('admin/hotel'); /*if cancel works remove this line*/
        $url = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/HotelCancel/';
        $ch = curl_init($url);
        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();
        $jsonData = array(
            'EndUserIp' => '192.168.10.130',
            'TokenId' => '' . $api_id->token_id,
            'BookingRefNo' => '' . $this->get->input('booking_no') ,
            'RequestType' => 'HotelCancel',
            'Remarks' => "Simply"
        );
        $jsonDataEncoded = json_encode($jsonData);
        print_r($jsonDataEncoded);
        die;
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result, true);
        print_r($res);
        if (1) /*check $res variable*/ {
            /*if cancel is succes, update the database*/
            $this->load->model('admin/hotel_model');
            $this->hotel_model->updateTicketStatus($this->input->get('booking_no'));
        }
        else {
            /*if cancel is not successful redirect to the admin panel of hotel list*/
        }

        redirect('admin/hotel');
    }

    function booking_failed()
    {
        $this->load->view('common/header.php');
        $this->load->view('hotels/failure_page.php');
        $this->load->view('common/footer.php');
    }

    //sorts and array of elements based on value without comparisons. O(kn) where, k = max number's length.
    public function value_sort( $input_array, $max_length ){

        $sort_arr = array();
        $digit = 1;
        $rem = 10;

        for( $i = 0 ; $i < $max_length ; $i++ ){
            $trunk = array();

            foreach( $input_array as $sam ){
                $n = $sam % $rem;
                $temp = intval($n / $digit);
                $sort_arr[$temp][] = $sam;
            }

            for( $j = 0 ; $j < count($input_array) ; $j++ ){
                if( isset($sort_arr[$j]) ){
                    foreach( $sort_arr[$j] as $s ){
                        $trunk[] = $s;
                    }
                    unset($sort_arr[$j]);
                }
            }

            $digit = $digit * 10;
            $rem = $rem * 10;
            $input_array = array();
            $input_array = $trunk;
        }
        return $input_array;
    }
}
?>