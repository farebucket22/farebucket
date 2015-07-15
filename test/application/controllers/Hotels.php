<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once (APPPATH . 'controllers/scaffolding/hotelsCURL.php');
require_once (APPPATH . 'controllers/scaffolding/hotelsAPIResponseParser.php');

class Hotels extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('hotel_model');
	}

	public function index(){
		$this->load->view('hotel_welcome');
	}

	public function authenticate(){
		$url = "http://tboapi.travelboutiqueonline.com/SharedAPI/SharedData.svc/rest/Authenticate";

        $headerData = array(
            "Content-Type:application/json",
            "Accept-Encoding:gzip, deflate"
        );

        $requestData = array(
            'ClientId' => 'tboprod',
            'UserName' => 'PNYR196',
            'Password' => 'travel/090',
            'LoginType' => 2,
            'EndUserIp' => $_SERVER['REMOTE_ADDR']
        );
        $requestData = json_encode($requestData);

        $hotelsCURLObj = new HotelsCurl;
        $response = $hotelsCURLObj->makeRequest($url, $headerData, $requestData);
        $response = json_decode(gzdecode($response));

        $tokenId = $response->TokenId;
        $agencyId = $response->Member->AgencyId;

        // $this->load->model('hotel_model');
        $data = array(
            'token_id' => $tokenId,
            'agency_id' => $agencyId
        );
        $this->hotel_model->setAuthenticationData($data);
	}

	public function getCityId(){
		//print_r($this);die;
		$data = $this->input->get(null, true);
        $countryCode = $data['country_code'];
        $cityName = $data['city_name'];

        // $this->load->model('hotel_model');
        $authData = $this->hotel_model->getTokenId();

        $url = 'http://tboapi.travelboutiqueonline.com/SharedAPI/SharedData.svc/rest/DestinationCityList';

        $headerData = array(
				"Content-Type:application/json",
				"Accept-Encoding:gzip, deflate"
		);

		$requestData = array(
			'TokenId' => '' . $authData->token_id,
			'ClientId' => 'tboprod',
			'EndUserIp' => $_SERVER['REMOTE_ADDR'],
			'CountryCode' => ''.$countryCode
		);
		$requestData = json_encode($requestData);

        $hotelsCURLObj = new HotelsCurl;
        $response = $hotelsCURLObj->makeRequest($url, $headerData, $requestData);
        $response = json_decode(gzdecode($response), true);

        $hotelsAPIResponseParserObj = new HotelsAPIResponseParser;
        $cityId = $hotelsAPIResponseParserObj->getCityId($response, $cityName);
        print_r(json_encode($cityId));
	}

	public function getSearchResults(){
		$data = $this->input->get(null, true);

		$checkinDate = $data['checkin_date'];
		$checkoutDate = $data['checkout_date'];
		$numNights = date_diff($checkout_date, $checkin_date);

		$countryCode = $data['country_code'];
		$cityId = $data['city_id'];
		$resultCount = null;
		$preferredCurrency = "INR";
		$numRooms = $data['room_count'];
		$preferredHotel = "";
		$maxRating = 5;
		$minRating = 1;
		$reviewScore = null;
		$isNearby = false;

		$authData = $this->hotel_model->getTokenId();
		$tokenId = ''.$authData->token_id;

		$endUserIp = $_SERVER['REMOTE_ADDR'];
		$bookingMode = 1;

		$roomCount = $data['room_count'];
		$j=0;
		for($i=0;$i<$roomCount;$i++){
			$j = $i + 1;
			$room[$i]['NoOfAdults'] = $data['room_'.$j.'_adult_count'];
			$room[$i]['NoOfChild'] = $data['room_'.$j.'_child_count'];
			$l=0;
			for($k=0;$k<$room[$i]['NoOfChild'];$k++){
				$l = $k+1;
				$room[$i]['ChildAge'][] = $data['room_'.$j.'_child_'.$l.'_age'];
			}
		}

		$url = 'http://tboapi.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc/rest/GetHotelResult/';

		$requestData = array(
            'BookingMode' => $bookingMode,
            'CheckInDate' => $checkinDate,
            'NoOfNights' => $numNights,
            'CountryCode' => $countryCode,
            'CityId' => '' . $cityId,
            'ResultCount' => $resultCount,
            'PreferredCurrency' => $preferredCurrency,
            'GuestNationality' => 'IN',
            'NoOfRooms' => $roomCount,
            'RoomGuests' => $room,
            'PreferredHotel' => $preferredHotel,
            'MaxRating' => $maxRating,
            'MinRating' => $minRating,
            'ReviewScore' => $reviewScore,
            'IsNearBySearchAllowed' => $isNearby,
            'EndUserIp' => $endUserIp,
            'TokenId' => $tokenId
        );

        $headerData = array(
            "Content-Type:application/json",
            "Accept-Encoding:gzip, deflate"
        );

        $requestData = json_encode($requestData);

        $hotelsCURLObj = new HotelsCurl;
        $response = $hotelsCURLObj->makeRequest($url, $headerData, $requestData);
        $response = json_decode(gzdecode($response), true);

        print_r($response);

	}
}