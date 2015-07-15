<?php
class hotel_model extends CI_Model
{
	function authentication($data)
	{
		$data = $this->db->insert('farebucket_hotel_authentication',$data);
		if($data)
			return true;
		else
			return false;
	}

	function get_token_id()
	{
		$data = $this->db->query("select * from farebucket_hotel_authentication");
		if($data){
			return ($data->result()[0]);
		}
			
		else
			return false;
	}

	function hotel_details($info)
	{
		$data = $this->db->insert('farebucket_hotel_details',$info);
		if($data)
			return true;
		else
			return false;
	}

	function hotel_booking_details($info)
	{
		$data = $this->db->insert('farebucket_hotel_confirmation',$info);
		if($data)
			return true;
		else
			return false;
	}

	function get_hotel_details($brn){
		$data = $this->db->get_where('farebucket_hotel_details', array('ConfirmationNo' => $brn));
		if($data)
			return ($data->result()[0]);
		else
			return false;
	}

	function get_hotel_details_user_details($data){
		$ret = $this->db->get_where('farebucket_hotel_details', $data);
		if($ret->num_rows() > 0)
			return ($ret->result());
		else
			return false;
	}

	function getLastFbBookingId(){
    	$data = $this->db->query('SELECT fb_bookingId FROM farebucket_hotel_details ORDER BY fb_bookingId DESC LIMIT 0,1');
    	$res = $data->result();
    	if(!isset($res[0])){
    		$fbId = 0;
    		return $fbId;
    	}
		else{
			$fbId = ($res[0]->fb_bookingId);
			return $fbId;
		}
    }
    
	function getBookingByUserId($user_id){
		$data = $this->db->get_where('farebucket_hotel_details', array('user_id' => $user_id));
		if($data->num_rows() > 0)
			return ($data->result());
		else
			return false;
	}

	public function updateTicketStatus($booking_id){
      $this->db->where('BookingId', $booking_id);
      if($this->db->update('farebucket_hotel_details', array('status' => 'Cancelled')))
        return true;
      else
        return false;
    }
	
	public function updateStatusAfterPay($data,$booking_id){
      $this->db->where('BookingId', $booking_id);
      if($this->db->update('farebucket_hotel_details', $data))
        return true;
      else
        return false;
    }

	public function updatePayuId($payu_id, $booking_id){
      $this->db->where('fb_bookingId', $booking_id);
      if($this->db->update('farebucket_hotel_details', array('payu_id' => $payu_id)))
        return true;
      else
        return false;
    }

	public function updateChangeReqId($changeReqId, $booking_id){
      $this->db->where('BookingId', $booking_id);
      if($this->db->update('farebucket_hotel_details', array('changeReqId' => $changeReqId)))
        return true;
      else
        return false;
    }

    function saveCancellationDate($today, $id){
    	$this->db->where('BookingRefNo', $id);
    	if($this->db->update('farebucket_hotel_details', array('cancellationDate' => $today)))
        return true;
      else
        return false;
    }

}
?>