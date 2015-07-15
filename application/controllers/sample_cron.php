<?php

class Sample_Cron extends MY_Controller{
	public function index()
	{
		/*****cron to delete the booking dates which exceeds the current date*****/
		$this->load->model('activity_model');
		$current_date = date("m/d/Y");
		$activity_booking_date = $this->activity_model->get_booking_date();
		for( $i = 0 ; $i < count($activity_booking_date) ; $i++)
		{
			if(strtotime($current_date) > strtotime($activity_booking_date[$i]->activity_booking_date))
			{
				$this->activity_model->update_status_by_date($activity_booking_date[$i]->activity_booking_date);
			}
	    }
	    $this->load->model('flight_model');
	    $current_date = date("Y-m-d");
	    $flight_booking_date = $this->flight_model->get_booking_date();
	    for( $i = 0 ; $i < count($flight_booking_date) ; $i++)
		{
			if(strtotime($current_date) > strtotime($flight_booking_date[$i]->date))
			{
				$this->flight_model->update_status_by_date($flight_booking_date[$i]->date);
			}
	    }

	    /*****cron to run hotel authenication every day once*****/
		mysql_query('DELETE FROM farebucket_hotel_authentication LIMIT 1');
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
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		$res = json_decode($result,true);
		print_r($res);
		$token_id = $res['TokenId'];
		$agency_id = $res['Member']['AgencyId'];
		$this->load->model('hotel_model');
		$data = array('token_id' => $token_id, 'agency_id' => $agency_id);
		$this->hotel_model->authentication($data);
	}

	public function flight_booking_check(){
		/*****cron to delete the booking dates which exceeds the current date*****/
		mysql_query('UPDATE farebucket_ticket_confirmation SET status = "Closed" WHERE booking_id IN (SELECT BookingId FROM farebucket_ticket_save WHERE DepTime < NOW())');
	}

	public function cab_booking_check(){
		/*****cron to delete the booking dates which exceeds the current date*****/
		mysql_query('UPDATE farebucket_cab_details SET booking_status = "Closed" WHERE CabRequiredOn < NOW()');
	}

	public function bus_booking_check(){
		/*****cron to delete the booking dates which exceeds the current date*****/
		mysql_query('UPDATE farebucket_bus_details SET status = "Closed" WHERE doj < NOW()');
	}

}

?>