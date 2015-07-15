<?php

class cab_model extends CI_Model{

	function insert_details($total_cars,$guest_info,$ref_id,$type,$sum,$total_fare,$cab_min_slab)
	{
		$user_id = 0;
		if( isset($_SESSION['user_details'][0]->user_id) ){
			$user_id = $_SESSION['user_details'][0]->user_id;
			$user_email = $_SESSION['user_details'][0]->user_email;
		}
		$guest_info['user_id'] = $user_id;
		$guest_info['user_email'] = $user_email;
		$guest_info['booking_ref_id'] = $ref_id;
		$guest_info['total_cars'] = $total_cars; 
		$guest_info['car_type'] = $type;
		$guest_info['passengers'] = $sum;
		$guest_info['total_fare'] = $total_fare;
		$guest_info['cab_min_slab'] = $cab_min_slab;
		$data = $this->db->insert('farebucket_cab_details',$guest_info);
		if($data)
			return $this->db->insert_id();
		else
			return false;
	}

	function insert_booking_details($total_cars,$guest_info,$ref_id,$type,$sum,$total_fare,$cab_min_slab)
	{
		$user_id = 0;
		if( isset($_SESSION['user_details'][0]->user_id) ){
			$user_id = $_SESSION['user_details'][0]->user_id;
			$user_email = $_SESSION['user_details'][0]->user_email;
		}
		$guest_info['user_id'] = $user_id;
		$guest_info['user_email'] = $user_email;
		$guest_info['booking_ref_id'] = $ref_id;
		$guest_info['total_cars'] = $total_cars; 
		$guest_info['car_type'] = $type;
		$guest_info['passengers'] = $sum;
		$guest_info['total_fare'] = $total_fare;
		$guest_info['cab_min_slab'] = $cab_min_slab;
		$data = $this->db->insert('farebucket_cab_confirmation',$guest_info);
		if($data)
			return $this->db->insert_id();
		else
			return false;
	}

	function update_details($id,$confirm_ref_id)
	{
		if($this->db->update('farebucket_cab_details',array('confirm_ref_id' => $confirm_ref_id, 'booking_status' => "success"),array('id' => $id)))
		{
			return true;
		}
			
		else
			return false;
	}

	function update_booking_details($id,$confirm_ref_id)
	{
		if($this->db->update('farebucket_cab_confirmation',array('confirm_ref_id' => $confirm_ref_id, 'booking_status' => "success"),array('id' => $id)))
			return true;
		else
			return false;
	}

	function update_booking($id,$booked_on,$journey_date)
	{
		if($this->db->update('farebucket_cab_details', array('booking_date' => $booked_on, 'journey_date' => $journey_date),array('confirm_ref_id' => $id)))
			return true;
		else
			return false;
	}

	function get_details($id)
	{
		$data = $this->db->get_where("farebucket_cab_details", array('confirm_ref_id' => $id));
		if( $data ){
			$details = $data->result();
			return $details;
		}else{
			return false;
		}
	}

	function getBookingByUserId($id)
	{
		$data = $this->db->get_where("farebucket_cab_details", array('user_id' => $id));
		if( $data ){
			$details = $data->result();
			return $details;
		}else{
			return false;
		}
	}

    function updateTicketStatus( $id, $booking_id = null ){
    if($this->db->update('farebucket_cab_details', array('booking_status' => "Cancelled"), array('id' => $id)))
            return true;
        else
            return false;
    }

    function updateRefundAmtAndCanlAmt( $id, $refundAmt, $canlAmt ){
    if($this->db->update('farebucket_cab_details', array('refundAmt' => $refundAmt, 'canlAmt' => $canlAmt), array('id' => $id)))
            return true;
        else
            return false;
    }

    function getLastFbBookingId(){
    	$data = $this->db->query('select fb_bookingId from farebucket_cab_details order by id desc limit 0,1');
    	$res = $data->result();
    	$fbId = ($res[0]->fb_bookingId);
    	return $fbId;	
    }

	function getCabRefByUserDetails($data){
		$ret = $this->db->get_where('farebucket_cab_details', $data);
		if( $ret->num_rows() > 0 ){
			return $ret->result();
		}else{
			return false;
		}
	}

	function getCabDetailsByBookingRef($data){
		$ret = $this->db->get_where('farebucket_cab_details', $data);
		if( $ret->num_rows() > 0 ){
			return $ret->result();
		}else{
			return false;
		}
	}

	public function updatePayuId($payu_id, $booking_id){
      $this->db->where('fb_bookingId', $booking_id);
      if($this->db->update('farebucket_cab_details', array('payu_id' => $payu_id)))
        return true;
      else
        return false;
    }

    function saveCancellationDate($today, $ticketid){
    	$this->db->where('fb_bookingId', $ticketid);
    	if($this->db->update('farebucket_cab_details', array('cancellationDate' => $today)))
        return true;
      else
        return false;
    }

}
?>