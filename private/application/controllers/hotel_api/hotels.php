<?php
@session_start();  
class Hotels extends CI_Controller
{
	function get_country()
	{
		require_once (APPPATH . 'lib/nusoap.php');
	    $wsdl = "http://api.tektravels.com/tbohotelapi_v6/hotelservice.asmx?wsdl";
	    $headerpara = array();
	    $headerpara["UserName"] = 'reddytrip';
	    $headerpara["Password"] = 'reddytrip@12';
	    $client_header = new SoapHeader('http://TekTravel/HotelBookingApi', 'AuthenticationData', $headerpara, false);
	    $client = new SoapClient($wsdl);
	    $client->__setSoapHeaders(array(
	      $client_header
	    ));
	    $country_search = array();
	    $header = array();
    	$header['se'] = (array)$client->__call('GetCountryList', $country_search);
    	print_r($header['se']);
	}

	function get_city()
	{
		$data = $this->input->post(null, true);
		$search_string = explode(",",$data['typed-string-single']);
		$search_count = count($search_string);
		require_once (APPPATH . 'lib/nusoap.php');
	    $wsdl = "http://api.tektravels.com/tbohotelapi_v6/hotelservice.asmx?wsdl";
	    $headerpara = array();
	    $headerpara["UserName"] = 'reddytrip';
	    $headerpara["Password"] = 'reddytrip@12';
	    $client_header = new SoapHeader('http://TekTravel/HotelBookingApi', 'AuthenticationData', $headerpara, false);
	    $client = new SoapClient($wsdl);
	    $client->__setSoapHeaders(array(
	      $client_header
	    ));
	    $city_search = array();
	    $city_search['GetDestinationCityList']['request']['CountryName'] = trim($search_string[$search_count-1]);
	    $header = array();
	    $header['se'] = (array)$client->__call('GetDestinationCityList', $city_search);
	    if(count((array)$header['se']['GetDestinationCityListResult']->CityList))
	    {
	    	$search_list = count($header['se']['GetDestinationCityListResult']->CityList->WSCity);
	    	$search_city = $header['se']['GetDestinationCityListResult']->CityList->WSCity;
	    	$j = 0;
	    	for($i = 0; $i < $search_list; $i++)
	    	{
	    		$cityName = explode(",", $search_city[$i]->CityName);
	    		if(trim($cityName[0])==$search_string[0])
	    		{
	    			$city_id = $search_city[$i]->CityCode;
	    			break;
	    		}
	    	}
	    	$data = $this->search_hotels($city_id,$data,$search_string[0],$search_string[$search_count-1]);
	    	echo $data;
	    }
	    else
	    {
	    	echo json_encode('No Search Results.');
	    }
	}

	function hotel_search(){
		if( isset($_SESSION['hotel_query']) ){
			unset($_SESSION['hotel_query']);
		}
    //$_SESSION['currentSearchUrl'] = $_SERVER['PATH_INFO'] . "?" . $_SERVER['QUERY_STRING'];
		$_SESSION['currentUrl'] = current_full_url();
		$data = $this->input->get(null, true);
		$this->load->view('common/header.php');
		$this->load->view('hotels/search_results.php', array( 'query' => $data ));
		$this->load->view('common/footer.php');
	}

	function set_hotel_search_multi_url(){
		print_r("hello");die;
		$overview = array();
		if($this->input->get(null,true)){
			$data = $this->input->get(null, true);
			$_SESSION['hotel_query'] = $data;
		}
		else{
			$data = $this->input->post(null, true);
			$data['hotel_extra_info'] = json_decode($data['hotel_extra_info']);
			$data['hotel_info'] = json_decode($data['hotel_info']);
			$data['room_details'] = json_decode($data['room_details']);
			$_SESSION['hotel_data'][$_SESSION['hotel_query']['hotel_num']-2] = $data;
		}
	
		if($_SESSION['hotel_query']['hotel_num'] <= $_SESSION['hotel_query']['count_hotels'])
		{
			if( $_SESSION['hotel_query']['hotel_num'] > 1){
				$j = $_SESSION['hotel_query']['hotel_num'] - 1;
				$k = $j-1;
				$hotel_count = $_SESSION['hotel_query']['hotel_num'];
				$hotel_count_prev = $hotel_count - 1;
				$_SESSION['hotel_query']['hotel_num'] = $_SESSION['hotel_query']['hotel_num']+1;
				$_SESSION['hotel_url'] = "hotel_api/hotels/multi_hotel_search?typed-string-multi='.$_SESSION['hotel_query']['typed-string-multi_'.$hotel_count_prev.''].'&search-string-multi='.$_SESSION['hotel_query']['search-string-multi_'.$hotel_count_prev.''].'&city_name='.$_SESSION['hotel_query']['city_name'][$k].'&checkin_time='.$_SESSION['hotel_query']['checkin_time'][$k].'&checkout_time='.$_SESSION['hotel_query']['checkout_time'][$k].'&multi_rooms='.$_SESSION['hotel_query']['multi_rooms'].'&adult_count_multi-1='.$_SESSION['hotel_query']['adult_count_multi-1'].'&child_count_multi-1='.$_SESSION['hotel_query']['child_count_multi-1'].'&adult_count_multi-2='.$_SESSION['hotel_query']['adult_count_multi-2'].'&child_count_multi-2='.$_SESSION['hotel_query']['child_count_multi-2'].'&adult_count_multi-3='.$_SESSION['hotel_query']['adult_count_multi-3'].'&child_count_multi-3='.$_SESSION['hotel_query']['child_count_multi-3'].'&adult_count_multi-4='.$_SESSION['hotel_query']['adult_count_multi-4'].'&child_count_multi-4='.$_SESSION['hotel_query']['child_count_multi-4'].'&flight_type='.$_SESSION['hotel_query']['flight_type'].'&hotel_num='.$hotel_count";
				print_r($_SESSION['hotel_url']);die;
				redirect('hotel_api/hotels/multi_hotel_search?typed-string-multi='.$_SESSION['hotel_query']['typed-string-multi_'.$hotel_count.''].'&search-string-multi='.$_SESSION['hotel_query']['search-string-multi_'.$hotel_count.''].'&city_name='.$_SESSION['hotel_query']['city_name'][$j].'&checkin_time='.$_SESSION['hotel_query']['checkin_time'][$j].'&checkout_time='.$_SESSION['hotel_query']['checkout_time'][$j].'&multi_rooms='.$_SESSION['hotel_query']['multi_rooms'].'&adult_count_multi-1='.$_SESSION['hotel_query']['adult_count_multi-1'].'&child_count_multi-1='.$_SESSION['hotel_query']['child_count_multi-1'].'&adult_count_multi-2='.$_SESSION['hotel_query']['adult_count_multi-2'].'&child_count_multi-2='.$_SESSION['hotel_query']['child_count_multi-2'].'&adult_count_multi-3='.$_SESSION['hotel_query']['adult_count_multi-3'].'&child_count_multi-3='.$_SESSION['hotel_query']['child_count_multi-3'].'&adult_count_multi-4='.$_SESSION['hotel_query']['adult_count_multi-4'].'&child_count_multi-4='.$_SESSION['hotel_query']['child_count_multi-4'].'&flight_type='.$_SESSION['hotel_query']['flight_type'].'&hotel_num='.$hotel_count);
			}else{
				$hotel_count = $_SESSION['hotel_query']['hotel_num'];
				$_SESSION['hotel_query']['hotel_num'] = $_SESSION['hotel_query']['hotel_num']+1;
				$_SESSION['hotel_url'] = "hotel_api/hotels/multi_hotel_search?typed-string-multi='.$_SESSION['hotel_query']['typed-string-multi_1'].'&search-string-multi='.$_SESSION['hotel_query']['search-string-multi_1'].'&city_name='.$_SESSION['hotel_query']['city_name'][0].'&checkin_time='.$_SESSION['hotel_query']['checkin_time'][0].'&checkout_time='.$_SESSION['hotel_query']['checkout_time'][0].'&multi_rooms='.$_SESSION['hotel_query']['multi_rooms'].'&adult_count_multi-1='.$_SESSION['hotel_query']['adult_count_multi-1'].'&child_count_multi-1='.$_SESSION['hotel_query']['child_count_multi-1'].'&adult_count_multi-2='.$_SESSION['hotel_query']['adult_count_multi-2'].'&child_count_multi-2='.$_SESSION['hotel_query']['child_count_multi-2'].'&adult_count_multi-3='.$_SESSION['hotel_query']['adult_count_multi-3'].'&child_count_multi-3='.$_SESSION['hotel_query']['child_count_multi-3'].'&adult_count_multi-4='.$_SESSION['hotel_query']['adult_count_multi-4'].'&child_count_multi-4='.$_SESSION['hotel_query']['child_count_multi-4'].'&flight_type='.$_SESSION['hotel_query']['flight_type'].'&hotel_num='.$hotel_count";
				print_r($_SESSION['hotel_url']);die;
				redirect('hotel_api/hotels/multi_hotel_search?typed-string-multi='.$_SESSION['hotel_query']['typed-string-multi_1'].'&search-string-multi='.$_SESSION['hotel_query']['search-string-multi_1'].'&city_name='.$_SESSION['hotel_query']['city_name'][0].'&checkin_time='.$_SESSION['hotel_query']['checkin_time'][0].'&checkout_time='.$_SESSION['hotel_query']['checkout_time'][0].'&multi_rooms='.$_SESSION['hotel_query']['multi_rooms'].'&adult_count_multi-1='.$_SESSION['hotel_query']['adult_count_multi-1'].'&child_count_multi-1='.$_SESSION['hotel_query']['child_count_multi-1'].'&adult_count_multi-2='.$_SESSION['hotel_query']['adult_count_multi-2'].'&child_count_multi-2='.$_SESSION['hotel_query']['child_count_multi-2'].'&adult_count_multi-3='.$_SESSION['hotel_query']['adult_count_multi-3'].'&child_count_multi-3='.$_SESSION['hotel_query']['child_count_multi-3'].'&adult_count_multi-4='.$_SESSION['hotel_query']['adult_count_multi-4'].'&child_count_multi-4='.$_SESSION['hotel_query']['child_count_multi-4'].'&flight_type='.$_SESSION['hotel_query']['flight_type'].'&hotel_num='.$hotel_count);
			}
		}
		else{
			redirect('new_request/selected_hotel');
		}
	}

	function multi_hotel_search(){
		print_r($_SESSION['currentUrl']);die;
		$_SESSION['currentUrl'] = current_full_url();
		$data = $this->input->get(null, true);
		$this->load->view('common/header.php');
		$this->load->view('hotels/search_results_multi.php', array( 'query' => $data ));
		$this->load->view('common/footer.php');
	}
}
?>