<?php  @session_start();

require_once (APPPATH . 'controllers/scaffolding/hotelsCURL.php');
require_once (APPPATH . 'controllers/scaffolding/hotelsAPIResponseHandler.php');

class New_Request extends MY_Controller
{
    public function authenticate_test(){
        $hotelsCURLObj = new HotelsCURL();

        $url = "http://tboapi.travelboutiqueonline.com/SharedAPI/SharedData.svc/rest/Authenticate";
        $requestData = array(
            'ClientId' => 'tboprod',
            'UserName' => 'PNYR196',
            'Password' => 'travel/090',
            'LoginType' => 1,
            'EndUserIp' => $_SERVER['REMOTE_ADDR']
        );
        $headerData = array(
            "Content-Type:application/json",
            "Accept-Encoding:gzip, deflate"
        );

        $hotelsCURLObj->setUrl($url);
        $hotelsCURLObj->setHeader($headerData);
        $hotelsCURLObj->setRequestData(json_encode($requestData));

        $curlObj = $hotelsCURLObj->initCURL();
        $result = gzdecode($hotelsCURLObj->makeRequest($curlObj));

        $tokenId = $result->TokenId;
        $agencyId = $result->Member->AgencyId;
        $this->load->model('hotel_model');
        $data = array(
            'token_id' => $tokenId,
            'agency_id' => $agencyId
        );
        $this->hotel_model->authentication($data);
    }

    function authenticate()
    {
        $url = 'http://tboapi.travelboutiqueonline.com/SharedAPI/SharedData.svc/rest/Authenticate';
        $ch = curl_init($url);
        $jsonData = array(
            'ClientId' => 'tboprod',
            'UserName' => 'PNYR196',
            'Password' => 'travel/090',
            'LoginType' => 2,
            'EndUserIp' => $_SERVER['REMOTE_ADDR']
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

        $file = fopen("hotelAuthenticateReq.txt", "w");
        fwrite($file, json_encode($jsonData));
        fclose($file);

        $file = fopen("hotelAuthenticateResp.txt", "w");
        fwrite($file, json_encode($res));
        fclose($file);

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

    public function citySearch(){
		
        $hotelsCURLObj = new HotelsCURL();
        $hotelsAPIResponseHandlerObj = new HotelsAPIResponseHandler();

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

        $cityName = '' . $city_name[0];
        $this->load->model('hotel_model');
        $apiId = $this->hotel_model->get_token_id();
        $url = 'http://tboapi.travelboutiqueonline.com/SharedAPI/SharedData.svc/rest/DestinationCityList';
		
		if( count($country_code) < 2 ){
			$response["Error"] = 0;
			$result = null;
		}else{
			$requestData = array(
				'TokenId' => '' . $apiId->token_id,
				'ClientId' => 'tboprod',
				'EndUserIp' => $_SERVER['REMOTE_ADDR'],
				'CountryCode' => '' . $country_code[count($country_code) - 2]
			);
			
			$headerData = array(
				"Content-Type:application/json",
				"Accept-Encoding:gzip, deflate"
			);
			
			$time1 = date("h:i:s");
			$hotelsCURLObj->setUrl($url);
			$hotelsCURLObj->setHeader($headerData);
			$hotelsCURLObj->setRequestData(json_encode($requestData));		

			$curlObj = $hotelsCURLObj->initCURL();
			$result = json_decode(gzdecode($hotelsCURLObj->makeRequest($curlObj)), true);
			$time2 = date("h:i:s");
				
			$file = fopen("hotelCitySearchReq.txt", "w");
			fwrite($file, $time1);
			fclose($file);
			
			$file = fopen("hotelCitySearchReq.txt", "a");
			fwrite($file, json_encode($requestData));
			fclose($file);
			
			$file = fopen("hotelCitySearchResp.txt", "w");
			fwrite($file, $time2);
			fclose($file);

			$file = fopen("hotelCitySearchResp.txt", "a");
			fwrite($file, json_encode($result));
			fclose($file);

		}
		
		$response = $hotelsAPIResponseHandlerObj->citySearchResponse($result, $cityName);
		
        if($response["Error"] != 0){
            $err_msg['flag'] = $response["Error"];
            $err_msg['msg'] = $response["ErrorMsg"];
            echo json_encode($err_msg);
            die;
        } else{
            $this->hotelSearch($response["CityId"], $country_code[count($country_code) - 2], $data);
        }
    }

    function hotelSearch($city_id, $country_code, $data){
        $hotelsCURLObj = new HotelsCURL();

        $url = 'http://tboapi.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc/rest/GetHotelResult/';
        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();

        if (isset($data['single_rooms'])) {
            $room_count = $data['single_rooms'];
            for ($i = 0; $i < $data['single_rooms']; $i++) {
                $j = $i + 1;
                $room[$i]['NoOfAdults'] = intval($data['adult_count_single-' . $j]);
                $room[$i]['NoOfChild'] = intval($data['child_count_single-' . $j]);
                if ($data['child_count_single-' . $j] != 0){
                    for ($k = 0; $k < $data['child_count_single-' . $j]; $k++) {
                        $room[$i]['ChildAge'][$k] = (string)12;
                    }
                }
            }
        } else {
            $room_count = $data['multi_rooms'];
            for ($i = 0; $i < $data['multi_rooms']; $i++) {
                $j = $i + 1;
                $room[$i]['NoOfAdults'] = intval($data['adult_count_multi-' . $j]);
                $room[$i]['NoOfChild'] = intval($data['child_count_multi-' . $j]);
                if ($data['child_count_multi-' . $j] != 0){
                    for ($k = 0; $k < $data['child_count_multi-' . $j]; $k++) {
                        $room[$i]['ChildAge'][$k] = (string)12;
                    }
                }
            }
        }

        $checkin = $data['checkin_time'];
		$checkin_date = new DateTime($checkin);
        $checkout = $data['checkout_time'];
		$checkout_date = new DateTime($checkout);
        $difference = $checkout_date->diff($checkin_date);
		$diff = $difference->format('%a');
		
        $requestData = array(
            'BookingMode' => 1,
            'CheckInDate' => '' . $data['checkin_time'],
            'NoOfNights' => $diff,
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
            'EndUserIp' => $_SERVER['REMOTE_ADDR'],
            'TokenId' => '' . $api_id->token_id
        );
        $headerData = array(
            "Content-Type:application/json",
            "Accept-Encoding:gzip, deflate"
        );
		
		$time1 = date("h:i:s");
        $hotelsCURLObj->setUrl($url);
        $hotelsCURLObj->setHeader($headerData);
        $hotelsCURLObj->setRequestData(json_encode($requestData));
		
        $curlObj = $hotelsCURLObj->initCURL();
		
        $result = gzdecode($hotelsCURLObj->makeRequest($curlObj));
		
		$time2 = date("h:i:s");

		$file = fopen("hotelSearchReq.txt", "w");
        fwrite($file, $time1);
        fclose($file);
		
        $file = fopen("hotelSearchReq.txt", "a");
        fwrite($file, json_encode($requestData));
        fclose($file);
		
		$file = fopen("hotelSearchResp.txt", "w");
        fwrite($file, $time2);
        fclose($file);

        $file = fopen("hotelSearchResp.txt", "a");
        fwrite($file, json_encode($result));
        fclose($file);

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

        if( isset($temp_arr['HotelSearchResult']['Error']['ErrorCode']) && ($temp_arr['HotelSearchResult']['Error']['ErrorCode'] == 0)){
            $result = json_encode($temp_arr);
            echo $result;
        }
		else if(isset($temp_arr['HotelSearchResult']['Error']['ErrorCode']) && $temp_arr['HotelSearchResult']['Error']['ErrorCode'] == 2 ){
			echo "No hotels found";
		}
		else{
            $err_msg['flag'] = 3;
            $err_msg['msg'] = 'An Error occurred. Please try again.';
            echo json_encode($err_msg);
        }
    }

    function hotel_info()
    {
		unset($_SESSION['hotel_final_fare']);
        $hotelsCURLObj = new HotelsCURL();

        $data = $this->input->post(null, true);

        $url = 'http://tboapi.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc/rest/GetHotelInfo/';

        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();

        $requestData = array(
            'ResultIndex' => $data['result_index'],
            'HotelCode' => '' . $data['hotel_code'],
            'TokenId' => '' . $api_id->token_id,
            'TraceId' => '' . $data['trace_id'],
            'EndUserIp' => $_SERVER['REMOTE_ADDR']
        );
        $headerData = array(
            "Content-Type:application/json",
            "Accept-Encoding:gzip, deflate"
        );
		
		$time1=date("h:i:s");
        $hotelsCURLObj->setUrl($url);
        $hotelsCURLObj->setHeader($headerData);
        $hotelsCURLObj->setRequestData(json_encode($requestData));

        $curlObj = $hotelsCURLObj->initCURL();
        $result = gzdecode($hotelsCURLObj->makeRequest($curlObj));
		$time2 = date("h:i:s");
		
		$file = fopen("hotelInfoReq.txt", "w");
        fwrite($file, $time1);
        fclose($file);
		
		$file = fopen("hotelInfoReq.txt", "a");
        fwrite($file, json_encode($requestData));
        fclose($file);

        $file = fopen("hotelInfoResp.txt", "w");
        fwrite($file, $time2);
        fclose($file);

        $file = fopen("hotelInfoResp.txt", "a");
        fwrite($file, json_encode($result));
        fclose($file);
        
        echo $result;
    }

    function room_info()
    {
        $hotelsCURLObj = new HotelsCURL();

        $data = $this->input->post(null, true);

        $url = 'http://tboapi.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc/rest/GetHotelRoom/';
        
        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();

        $requestData = array(
            'EndUserIp' => '192.168.10.130',
            'TokenId' => '' . $api_id->token_id,
            'TraceId' => '' . $data['trace_id'],
            'ResultIndex' => $data['result_index'],
            'HotelCode' => '' . $data['hotel_code'],
        );
        $headerData = array(
            "Content-Type:application/json",
            "Accept-Encoding:gzip, deflate"
        );
	
		$time1 = date("h:i:s");
        $hotelsCURLObj->setUrl($url);
        $hotelsCURLObj->setHeader($headerData);
        $hotelsCURLObj->setRequestData(json_encode($requestData));

        $curlObj = $hotelsCURLObj->initCURL();
        $result = gzdecode($hotelsCURLObj->makeRequest($curlObj));
		$time2 = date("h:i:s");
		
		$file = fopen("hotelRoomReq.txt", "w");
        fwrite($file, $time1);
        fclose($file);
		
		$file = fopen("hotelRoomReq.txt", "a");
        fwrite($file, json_encode($requestData));
        fclose($file);

        $file = fopen("hotelRoomResp.txt", "w");
        fwrite($file, $time2);
        fclose($file);

        $file = fopen("hotelRoomResp.txt", "a");
        fwrite($file, json_encode($result));
        fclose($file);

        unset($_SESSION['hotelBlockRoomRequest']);
        echo $result;
    }

    function selected_hotel()
    {
        $data = $this->input->post();
		$_SESSION['currentUrlHotel'] = current_full_url();
        if ((isset($_SESSION['hotel_query'])) && (isset($_SESSION['hotel_query']['multi_rooms']))) {
            redirect('new_request/multi_hotel');
        }
        else {
			if( !isset($_SESSION['cancelPolicy']) && !isset($_SESSION['checkTime']) ){
				$_SESSION['cancelPolicy'] = $_POST['cancelPolicy'];
				$_SESSION['checkTime'] = $_POST['checkTime'];
				$_SESSION['data_post'] = $_POST;
			}
            if( isset($_SESSION['data_post']) ){
                $data = $_SESSION['data_post'];
				unset($_SESSION['data_post']);
            }
            $this->load->model('admin/convenience_model');
            $data['convenience_charge'] = $this->convenience_model->get_convenience_charge('hotels');
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
        $data['convenience_charge'] = $this->convenience_model->get_convenience_charge('hotels');
        $this->load->view('common/header.php');
        $this->load->view('hotels/hotel_multi_traveller_details.php', array(
            'data' => $data
        ));
        $this->load->view('common/footer.php');
    }

    public function store_block_room_info(){
        $single = $this->input->post('single_rooms');
        $multi = $this->input->post('multi_rooms');
		$clicked_room = $this->input->post('index');
		$room = explode("~s~", $this->input->post('room_type'));
        if (isset($multi)) {
            $room = explode("~s~", $this->input->post('room_type'));
            $TraceId = json_decode($this->input->post('trace_id'));
            $info = json_decode($this->input->post('hotel_info'));
            $price = $_POST['priceDetails']['Price'];
        } else {
            $room = explode("~s~", $this->input->post('room_type'));
            $TraceId = json_decode($this->input->post('trace_id'));
            $info = json_decode($this->input->post('hotel_info'));
            $price = $_POST['priceDetails']['Price'];
        }

        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();
        $url = 'http://tboapi.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc/rest/BlockRoom/';
        if ($single) {
            $total_room = $this->input->post('single_rooms');
        } else {
            $total_room = $this->input->post('multi_rooms');
        }
		
		if( $clicked_room == 'room_selected-0' ){
			$c = 1;
		}
		else if( $clicked_room == 'room_selected-1' ){
			$c = 2;
		}
		else if( $clicked_room == 'room_selected-2' ){
			$c = 3;
		}
		else if( $clicked_room == 'room_selected-3' ){
			$c = 4;
		}
        $lastIndexFlag = 0;
		$i=0;
        if($total_room > 1){
            if( isset($_SESSION['hotelBlockRoomRequest']) ){
                //this basically adds new hotel data at the end of the hotel details array
                $hotel_details = $_SESSION['hotelBlockRoomRequest']['HotelRoomsDetails'];
                $i = count($hotel_details);

				if($i == $total_room && isset($_SESSION['hotel_again'])){
					$_SESSION['hotel_again'] += 1;
					if( isset($_SESSION['hotel_fare'][$c-1]) ){
						$_SESSION['hotel_final_fare'] = $_SESSION['hotel_final_fare'] - $_SESSION['hotel_fare'][$c-1];
					}
					if( $c >= 2 ){
						$_SESSION['hotel_again'] = $c-1;
					}
					$i = $_SESSION['hotel_again'];
					
					if($_SESSION['hotel_again'] == $total_room){
						$_SESSION['hotel_again'] = 0;
					}
				}
				else if($i == $total_room){
					$i = $c-1;
					$_SESSION['hotel_again'] = 1;
				}
				
				if($c == 1){
					unset($_SESSION['hotel_final_fare']);
					unset($_SESSION['hotel_fare']);
				}
				if($c == 2 && (isset($_SESSION['hotel_fare'][1])) ){
					$i = $c - 1;
					$_SESSION['hotel_final_fare'] =  $_SESSION['hotel_fare'][0];
					unset($_SESSION['hotel_fare'][1]);
					unset($_SESSION['hotel_fare'][2]);
					unset($_SESSION['hotel_fare'][3]);
				}
				if($c == 3 && (isset($_SESSION['hotel_fare'][2]))){
					$i = $c - 1;
					$_SESSION['hotel_final_fare'] = $_SESSION['hotel_fare'][0] + $_SESSION['hotel_fare'][1];
					unset($_SESSION['hotel_fare'][3]);
				}
				if($c == 4 && (isset($_SESSION['hotel_fare'][3]))){
					$_SESSION['hotel_final_fare'] = $_SESSION['hotel_fare'][0] + $_SESSION['hotel_fare'][1] + $_SESSION['hotel_fare'][2];
				}
				
                $hotel_details[$i]['RoomIndex'] = "" . $room[0];
                $hotel_details[$i]['RoomTypeCode'] = (isset($room[1])) ? "" . $room[1] : "";
                $hotel_details[$i]['RoomTypeName'] = (isset($room[2])) ? "" . $room[2] : "";
                $hotel_details[$i]['RatePlanCode'] = (isset($room[3])) ? "" . $room[3] : "";
                $hotel_details[$i]['BedTypeCode'] = null;
                $hotel_details[$i]['SmokingPreference'] = 0;
                $hotel_details[$i]['Supplements'] = null;
                $hotel_details[$i]['Price'] = $price;
                if( $i == ($total_room-1) ){
                    $lastIndexFlag = 1;
					unset($_SESSION['hotel_again']);
                }
            }else{
				if($c == 1){
					unset($_SESSION['hotel_final_fare']);
					unset($_SESSION['hotel_fare']);
				}
                $hotel_details[$i]['RoomIndex'] = "" . $room[0];
                $hotel_details[$i]['RoomTypeCode'] = (isset($room[1])) ? "" . $room[1] : "";
                $hotel_details[$i]['RoomTypeName'] = (isset($room[2])) ? "" . $room[2] : "";
                $hotel_details[$i]['RatePlanCode'] = (isset($room[3])) ? "" . $room[3] : "";
                $hotel_details[$i]['BedTypeCode'] = null;
                $hotel_details[$i]['SmokingPreference'] = 0;
                $hotel_details[$i]['Supplements'] = null;
                $hotel_details[$i]['Price'] = $price;
            }

        }else{
            $lastIndexFlag = 1;
			unset($_SESSION['hotel_final_fare']);
            $hotel_details[$i]['RoomIndex'] = "" . $room[0];
            $hotel_details[$i]['RoomTypeCode'] = (isset($room[1])) ? "" . $room[1] : "";
            $hotel_details[$i]['RoomTypeName'] = (isset($room[2])) ? "" . $room[2] : "";
            $hotel_details[$i]['RatePlanCode'] = (isset($room[3])) ? "" . $room[3] : "";
            $hotel_details[$i]['BedTypeCode'] = null;
            $hotel_details[$i]['SmokingPreference'] = 0;
            $hotel_details[$i]['Supplements'] = null;
            $hotel_details[$i]['Price'] = $price;
        }

        $requestData = array(
            'ResultIndex' => $info->ResultIndex,
            'HotelCode' => $info->HotelCode,
            'HotelName' => $info->HotelName,
            'GuestNationality' => 'IN',
            'NoOfRooms' => $total_room,
            'ClientReferenceNo' => 0,
            'IsVoucherBooking' => true,
            'HotelRoomsDetails' => $hotel_details,
            'TokenId' => '' . $api_id->token_id,
            'TraceId' => trim($TraceId) ,
            'EndUserIp' => '192.168.10.130'
        );
		
		$_SESSION['hotel_fare'][$c-1] = $hotel_details[$i]['Price']['PublishedPriceRoundedOff'];
		
		if(isset($_SESSION['hotel_final_fare'])){
			$_SESSION['hotel_final_fare'] = $_SESSION['hotel_final_fare'] + $_SESSION['hotel_fare'][$c-1];
		}
		else{
			$_SESSION['hotel_final_fare'] = $_SESSION['hotel_fare'][$c-1];
		}
        $_SESSION['hotelBlockRoomRequest'] = $requestData;
		
        if( $lastIndexFlag ){
            $ret = $this->block_room($requestData);
        }else{
			$resultArray['message'] = 'stored';
			$resultArray['fare'] = $_SESSION['hotel_final_fare'];
			echo json_encode($resultArray);
        }

    }

    function block_room($requestData = null)
    {
        $hotelsCURLObj = new HotelsCURL();
        $url = 'http://tboapi.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc/rest/BlockRoom/';

        $headerData = array(
            "Content-Type:application/json",
            "Accept-Encoding:gzip, deflate"
        );
		
		$time1 = date("h:i:s");
        $hotelsCURLObj->setUrl($url);
        $hotelsCURLObj->setHeader($headerData);
        $hotelsCURLObj->setRequestData(stripslashes(json_encode($requestData)));

        $curlObj = $hotelsCURLObj->initCURL();
        $result = gzdecode($hotelsCURLObj->makeRequest($curlObj));
        $res = json_decode($result, true);
		$time2 = date("h:i:s");

        $file = fopen("hotelBlockRoomReq.txt", "w");
        fwrite($file, $time1);
        fclose($file);
		
		$file = fopen("hotelBlockRoomReq.txt", "a");
        fwrite($file, json_encode($requestData));
        fclose($file);

        $file = fopen("hotelBlockRoomResp.txt", "w");
        fwrite($file, $time2);
        fclose($file);
		
		$file = fopen("hotelBlockRoomResp.txt", "a");
        fwrite($file, json_encode($result));
        fclose($file);

        if (isset($res['BlockRoomResult']['AvailabilityType'])) {
            if ($res['BlockRoomResult']['AvailabilityType'] == 'Confirm' || $res['BlockRoomResult']['AvailabilityType'] == 'Available') {
				$resultArray['fare'] = $_SESSION['hotel_final_fare'];
				$resultArray['res'] = $res;
				echo json_encode($resultArray);
            }
            else {
                echo json_encode('Failure');
            }
        } else {
			if($res['BlockRoomResult']['Error']['ErrorMessage'] == 'You are not authorised to Generate Voucher.'){
				echo json_encode("Not available");
			}
			else if( $res['BlockRoomResult']['Error']['ErrorMessage'] == 'Error at ReadResponse : Msg :-Error in Supplier Response: The given key was not present in the dictionary.' || $res['BlockRoomResult']['Error']['ErrorMessage']=' Unable to proceed with block rooms' ){
				unset($_SESSION['hotel_final_fare']);
				echo json_encode('Error');
			}
			else{
				echo json_encode('Failure');
			}    
        }
    }

    function payment_gateway(){
		
		if( isset($_POST['email_id']) ){
			if( isset( $_SESSION['user_details'][0] ) ){
			$firstname = $_SESSION['user_details'][0]->user_first_name;
			$userid = $_SESSION['user_details'][0]->user_id;
			$email = $_SESSION['user_details'][0]->user_email;
			$phone = $_SESSION['user_details'][0]->user_mobile;
			}
			else{
				$firstname = $_SESSION['user_details']['user_first_name'];
				$userid = $_SESSION['user_details']['user_id'];
				$email = $_POST['email_id'];
				$phone = $_POST['phone_no'];
			}
			$fbBooking = 'TSHO';
			$this->load->model('hotel_model');
			$returnId = $this->hotel_model->getLastFbBookingId();
			if(strlen($returnId) === 0){
				$randomNum = 1;
			}
			else{
				$firstFourLetters = substr($returnId, 0, 4);
				$splitReturnID = explode($firstFourLetters, $returnId);
				$randomNum = intval($splitReturnID[1]) + 1;
			} 

			$randomNum = sprintf("%06d", $randomNum);
			$fbBookingId = $fbBooking . $randomNum;
			
			$_SESSION['hotelBookingId'] = $fbBookingId;
			$_SESSION['farebucket_txnid'] = $fbBookingId;
		}
		
        if( isset($_POST['extra_info']) ){
            if( $_POST['call_func'] == 'single' ){
				$data = $_POST;
				$data['extra_info'] = json_decode($data['extra_info']);
				$data['info'] = json_decode($data['info']);
				
				$data_store['destination'] = $data['des'];
				$data_store['room_count'] = $data['count'];
				$data_store['user_id'] = $userid;
				$data_store['user_email'] = $email;
				$data_store['check_in'] = $data['check_in'];
				$data_store['check_out'] = $data['check_out'];
				$data_store['hotel_name'] = $data['extra_info']->HotelInfoResult->HotelDetails->HotelName;
				$data_store['published_price'] = $data['info']->Price->PublishedPriceRoundedOff;
				if( $_POST['finalFare'] == "" ){
					$data_store['hotel_price'] = $_SESSION['hotel_price_single_final'];
				}
				else{
					$data_store['hotel_price'] = $_POST['finalFare'];
				}
				$data_store['offered_price'] = $data['info']->Price->OfferedPrice;
				$data_store['Convinience_charge'] = $_SESSION['hotel_conv_charge'];            
				$data_store['info'] = $data['room_info'];
				$data_store['necessary_info'] = "";
				$data_store['title'] = implode(",", $data['title_a']);
				$data_store['first_name'] = implode(",", $data['first_name_a']);
				$data_store['last_name'] = implode(",", $data['last_name_a']);
				$data_store['age'] = implode(",", $data['age_a']);
				$data_store['pass_number'] = implode(",", $data['pass_number_a']);
				$data_store['pass_expiry'] = implode(",", $data['pass_expiry_a']);
				$data_store['status'] = "Pending";
				$data_store['BookingRefNo'] = "";
				$data_store['BookingId'] = "";
				$data_store['ConfirmationNo'] = "";
				$data_store['fb_bookingId'] = $fbBookingId;
				$this->load->model('hotel_model');
				$this->hotel_model->hotel_details($data_store);
				$this->hotel_model->hotel_booking_details($data_store);
                $_SESSION['hotel_single_post_data'] = $_POST;
				
                $params = array (
                    'key' => $_POST["key"], 
                    'txnid' => $fbBookingId, 
                    'amount' => $data_store['hotel_price'],
                    'firstname' => $firstname, 
                    'email' => $email, 
                    'phone' => $phone,
                    'productinfo' => $_POST["productinfo"], 
                    'surl' => 'new_request/hotel_book',
                    'furl' => 'api/flights/booking_failed'
                );
            }else{
                $_SESSION['hotel_multi_post_data'] = $_POST;
				
				$data = $_POST;
				$data['extra_info'] = json_decode($data['extra_info']);

				$data_store['destination'] = "";
				$data_store['room_count'] = "";
				$data_store['user_id'] = $userid;
				$data_store['user_email'] = $email;
				$data_store['check_in'] = "";
				$data_store['check_out'] = "";
				$data_store['hotel_name'] = "";
				$data_store['published_price'] = "";
				$data_store['offered_price'] = "";
				$data_store['Convinience_charge'] = "";            
				$data_store['info'] = "";
				$data_store['necessary_info'] = "";
				if( $_POST['finalFare'] == "" ){
					$data_store['hotel_price'] = $_SESSION['hotel_price_multi'];
				}
				else{
					$data_store['hotel_price'] = $_POST['finalFare'];
				}
				$data_store['title'] = implode(",", $data['title_a']);
				$data_store['first_name'] = implode(",", $data['first_name_a']);
				$data_store['last_name'] = implode(",", $data['last_name_a']);
				$data_store['age'] = implode(",", $data['age_a']);
				$data_store['pass_number'] = implode(",", $data['pass_number_a']);
				$data_store['pass_expiry'] = implode(",", $data['pass_expiry_a']);
				$data_store['status'] = "Pending";
				$data_store['BookingRefNo'] = "";
				$data_store['BookingId'] = "";
				$data_store['ConfirmationNo'] = "";
				$data_store['fb_bookingId'] = $fbBookingId;
				$this->load->model('hotel_model');
				$this->hotel_model->hotel_details($data_store);
				$this->hotel_model->hotel_booking_details($data_store);

                $params = array (
                    'key' => $_POST["key"], 
                    'txnid' =>  $fbBookingId, 
                    'amount' => $data_store['hotel_price'],
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
                if( isset($_POST['mihpayid']) ){
                    $_SESSION['hotelPayId'] = $_POST['mihpayid'];
                }
                $params = array (
                    'surl' => 'new_request/hotel_book',
                    'furl' => 'api/flights/booking_failed'
                );
            }else if( isset($_SESSION['hotel_multi_post_data']) ){
                if( isset($_POST['mihpayid']) ){
                    $_SESSION['hotelPayId'] = $_POST['mihpayid'];
                }
                $params = array (
                    'surl' => 'new_request/multi_hotel_book',
                    'furl' => 'api/flights/booking_failed'
                );
            }
        }

        require_once (APPPATH . 'lib/payu.php');
        if ( count( $_POST ) ) {
            pay_page( $params, 'hXS7CHnJ' );
        }
    }

    function hotel_book()
    {
        $passenger = $_SESSION['hotel_single_post_data'];
        $HotelRoomsDetails = $_SESSION['hotelBlockRoomRequest']['HotelRoomsDetails'];
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
        $url = 'http://tboapi.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc/rest/Book/';
        $ch = curl_init($url);
        for ($i = 0; $i < $total_room; $i++) {

            $hotel_details[$i] = $HotelRoomsDetails[$i];

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
                    $hotel_details[$i]['HotelPassenger'][$j]['Age'] = intval($passenger['age_a'][$j+$adult_count_prev]);
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
                    $hotel_details[$i]['HotelPassenger'][$j]['Age'] = intval($passenger['age_a'][$j+$adult_count_prev]);
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
                $hotel_details[$i]['HotelPassenger'][$j]['Age'] = intval($passenger['age_k'][$o]);
                $hotel_details[$i]['HotelPassenger'][$j]['PassportNo'] = null;
                $hotel_details[$i]['HotelPassenger'][$j]['PassportIssueDate'] = null;
                $hotel_details[$i]['HotelPassenger'][$j]['PassportExpDate'] = null;
            }

        }

        if( isset($_SERVER['HTTP_CLIENT_IP']) ){
            $ip_addr = $_SERVER['HTTP_CLIENT_IP'];
        }else if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ){
            $ip_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip_addr = $_SERVER['REMOTE_ADDR'];
        }

        $jsonData = array(
            'ResultIndex' => $data['info']->ResultIndex,
            'HotelCode' => $data['info']->HotelCode,
            'HotelName' => $data['info']->HotelName,
            'GuestNationality' => 'IN',
            'NoOfRooms' => $data['count'],
            'ClientReferenceNo' => 0,
            "IsVoucherBooking" => true,
            'HotelRoomsDetails' => $hotel_details,
            'TokenId' => '' . $api_id->token_id,
            'TraceId' => '' . $data['room_details']->GetHotelRoomResult->TraceId,
            'EndUserIp' => $ip_addr
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

        $file = fopen("hotelBookReq.txt", "w");
        fwrite($file, $jsonDataEncoded);
        fclose($file);

        $file = fopen("hotelBookResp.txt", "w");
        fwrite($file, json_encode($res));
        fclose($file);

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

        if (isset($res['BookResult']['HotelBookingStatus']) && ($res['BookResult']['HotelBookingStatus'] === 'Confirmed' || $res['BookResult']['HotelBookingStatus'] === 'Vouchered')) {
            $pass['destination'] = $data['des'];
            $pass['room_count'] = $data['count'];
            $pass['user_id'] = $_SESSION['user_details'][0]->user_id;
            $pass['user_email'] = $_SESSION['user_details'][0]->user_email;
            $pass['check_in'] = $data['check_in'];
            $pass['check_out'] = $data['check_out'];
            $pass['hotel_name'] = $data['extra_info']->HotelInfoResult->HotelDetails->HotelName;
            $pass['hotel_price'] = $data['info']->Price->PublishedPrice;
            $pass['BookingRefNo'] = $res['BookResult']['BookingRefNo'];
            $pass['BookingId'] = $res['BookResult']['BookingId'];
            $pass['ConfirmationNo'] = $res['BookResult']['ConfirmationNo'];
            $data_store['necessary_info'] = $ne;
            $data_store['status'] = "Success";
            $data_store['BookingRefNo'] = (!empty($res['BookResult']['BookingRefNo'])) ? $res['BookResult']['BookingRefNo'] : '12345';
            $data_store['BookingId'] = (!empty($res['BookResult']['BookingId'])) ? $res['BookResult']['BookingId'] : '12345';
            $data_store['ConfirmationNo'] = $res['BookResult']['ConfirmationNo'];
            $_SESSION['pass'][0] = $pass;
            $data_store['fb_bookingId'] = $_SESSION['hotelBookingId'];
            $hotelPayUid = $_SESSION['hotelPayId'];
            $this->load->model('hotel_model');
            $this->hotel_model->updateStatusAfterPay($data_store,$_SESSION['hotelBookingId']);
            $this->hotel_model->hotel_booking_details($data_store);
            $this->hotel_model->updatePayuId($hotelPayUid, $data_store['fb_bookingId']);

            $mail_chk = $this->send_email($_SESSION['user_details'][0]->user_email, $data_store['ConfirmationNo'], $_SESSION['user_details'][0]->user_first_name);
            redirect('new_request/get_voucher?BookingId='.$data_store['BookingId']);
        }
        else {
            redirect('new_request/booking_failed');
        }
    }

    function get_voucher(){
        if( $this->input->get('BookingId') ){
            $booking_id = $this->input->get('BookingId');
        }else{
            $booking_id = $this->input->post('booking_id');
        }

        if( isset($_SERVER['HTTP_CLIENT_IP']) ){
            $ip_addr = $_SERVER['HTTP_CLIENT_IP'];
        }else if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ){
            $ip_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip_addr = $_SERVER['REMOTE_ADDR'];
        }

        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();

        $url = 'http://tboapi.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc/rest/GenerateVoucher/';
        $ch = curl_init($url);
        $jsonData = array(
            "BookingId" => $booking_id,
            "EndUserIp" => '192.168.10.130',
            "TokenId" => '' . $api_id->token_id
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

        if( $res['GenerateVoucherResult']['Error']['ErrorCode'] == 0 ){
            redirect('new_request/ticket_page?BookingId='.$booking_id);
        }
    }

    public function send_email($email_id, $hash_val, $name){
        $link = site_url('new_request/ticket_page?BookingId='.$hash_val);
        $cust_support_data = cust_support_helper();
        $to = $email_id;
        //define the subject of the email
        $subject = 'Ticket Links'; 
        //define the message to be sent. Each line should be separated with \n
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Thank you for choosing Farebucket. Please find the link to your ticket(s) below.</p>Links: <a href="'.$link.'">Ticket</a> <hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>'.$cust_support_data->phone_number.'</strong> <br/>Email: <strong><a href="emailto:'.$cust_support_data->email.'">'.$cust_support_data->email.'</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 PNYR196 LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
        //define the headers we want passed. Note that they are separated with \r\n
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Admin <info@farebucket.com>" . "\r\n";
        $headers .= "Cc: Support <support@farebucket.com>" . "\r\n";

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
            $url = 'http://tboapi.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc/rest/Book/';
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
                $data_store['fb_bookingId'] = $_SESSION['hotelBookingId'];
                $this->load->model('hotel_model');
                $this->hotel_model->updateStatusAfterPay($data_store);
                $this->hotel_model->hotel_booking_details($data_store);
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
        if( isset($_GET['ticket_id']) && !empty($_GET['ticket_id']) ){
            $user_data = array(
                'fb_bookingId' => $_GET['ticket_id'],
                'user_email' => $_GET['guest_email']
            );

            $this->load->model('hotel_model');
            $pass = $this->hotel_model->get_hotel_details_user_details($user_data);

            for( $i = 0 ; $i < count($pass) ; $i++ ){
                $pass[$i] = (array)$pass[$i];
            }

        }else{
            if( isset($_SESSION['pass']) )$pass = $_SESSION['pass'];
            else{
                $data_store = $_GET['BookingId'];
                $this->load->model('hotel_model');
                $pass = $this->hotel_model->get_hotel_details($data_store);
            }
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
        $this->load->view('hotels/invoice_page.php', array(
            'data' => $details,
            'api_id' => $api_id
        ));
    }

    function hotel_cancel()
    {
        if( isset($_SERVER['HTTP_CLIENT_IP']) ){
            $ip_addr = $_SERVER['HTTP_CLIENT_IP'];
        }else if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ){
            $ip_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip_addr = $_SERVER['REMOTE_ADDR'];
        }
        $today = date("Y-m-d H:i:s");
        $id = $_POST['retHotelData']['BookingRefNo'];
        $this->load->model('hotel_model');
        $this->hotel_model->saveCancellationDate($today, $id);

        $this->load->model('hotel_model');
        $api_id = $this->hotel_model->get_token_id();
        $url = 'http://tboapi.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc/rest/SendChangeRequest/';
        $ch = curl_init($url);
        $jsonData = array(
            'RequestType' => 4,
            'Remarks' => "On Request",
            'BookingId' => '' . $_POST['retHotelData']['BookingId'],
            'EndUserIp' => '192.168.10.130',
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
        curl_close($ch);
        $res = json_decode($result, true);

        if ( $res['HotelChangeRequestResult']['Error']['ErrorCode'] == 0 ) /*check $res variable*/ {
            /*if cancel is succes, update the database*/
            $this->load->model('admin/hotel_model');
            $this->hotel_model->updateTicketStatus($_POST['retHotelData']['BookingId']);
            $this->hotel_model->updateChangeReqId($_POST['retHotelData']['BookingId']);
            echo json_encode(true);
        }
        else {
            echo json_encode(false);
        }
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