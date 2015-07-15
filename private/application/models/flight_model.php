<?php

class flight_model extends CI_Model{

	function getUserDetailsById($id){
		$data = $this->db->get_where("farebucket_ticket_confirmation", array('user_id' => intval($id)));
		if($data){
			$details = $data->result();
			return $details;	
		}
		else{
			return false;
		}
	}

	function postTicket($data){
		if($this->db->insert('farebucket_ticket_confirmation', $data)){
			return $this->db->insert_id();
		}
		else{
			return false;
		}
	}

	function updateStatus($data){
		$data['ticket_id'] = array('ticket_id' => $data['ticket_id']);
		$this->db->where('ticket_id', $data['ticket_id']['ticket_id']);
		if($this->db->update('farebucket_ticket_confirmation', array('status' => $data['status']['stat']))){
			return true;
		}
		else{
			return false;
		}
	}
	function getticketDetails($ticketid)
 	{
		$data = $this->db->get_where("farebucket_ticket_confirmation", array('ticket_id' => $ticketid));
		if($data){
		return $data->result(); 
		}
		else{
		return false;
		}
 	}

 	function saveMultiwayDetails( $data ){
		if( $query = $this->db->insert( 'farebucket_multiway_save', $data ) ) {
			return true;
		} else {
			return false;
		}
 	}

 	function getDetailsByHashValue($hash){
		$data = $this->db->get_where("farebucket_multiway_save", array('hash_val' => $hash));
		if($data){
			return $data->result();	
		}
		else{
			return false;
		}
 	}

 	function getSessionId($ticketid){
 		$data = $this->db->query('select session_id from farebucket_ticket_confirmation where ticket_id = "'.$ticketid.'"');
 		if($data){
	    	return $data->result(); 
	    }
	    else{
	    	return false;
	    }
 	} 

 	function getTicketResults($ticketid){
 		$data = $this->db->query('select * from farebucket_ticket_confirmation where ticket_id = "'.$ticketid.'"');
 		if($data){
	    	return $data->result(); 
	    }
	    else{
	    	return false;
	    }
 	}

 	function get_ticket_results($ticketid){
 		$data = $this->db->query('select * from farebucket_ticket_save where id = "'.$ticketid.'"');
 		if($data){
	    	return $data->result(); 
	    }
	    else{
	    	return false;
	    }
 	}	

 	function updateticketDetails($pnr,$booking_id,$ticketid)
 	{
 		if($this->db->update('farebucket_ticket_confirmation',array('pnr' => $pnr,'booking_id'=> $booking_id),array('ticket_id' => $ticketid)))
            return true;
        else
            return false;
 	} 

 	function cancelTicketById($ticketid){
 		$data = $this->db->query('select extra_info, booking_id, pnr from farebucket_ticket_confirmation where booking_id= "'.$ticketid.'"');
 		if($data){
	    	return $data->result(); 
	    }
	    else{
	    	return false;
	    }
 	}

 	//this may not be necessary later. it could be substituted by the function above.
 	function getTicketIdByBookingId($bookingId){
 		$data = $this->db->query('select TicketId from farebucket_ticket_save where BookingId = "'.$bookingId.'"');
 		if($data){
	    	return $data->result(); 
	    }
	    else{
	    	return false;
	    }
 	}

 	function updateLccTicketStatus($ticketid,$data)
 	{
 		if($this->db->update('farebucket_ticket_confirmation',$data,array('ticket_id' => $ticketid)))
            return true;
        else
            return false;
 	}	

 	function updateTicketStatus($ticketid)
 	{
 		$this->db->where('booking_id', $ticketid);
 		if($this->db->update('farebucket_ticket_confirmation', array('status' => "Cancelled")))
            return true;
        else
            return false;
 	}

 	function updateRefundAndCanlAmt($ticketid, $refundAmt, $canlAmt)
 	{
 		$this->db->where('BookingId', $ticketid);
 		if($this->db->update('farebucket_ticket_save', array('refundAmt' => $refundAmt, 'canlAmt' => $canlAmt)))
            return true;
        else
            return false;
 	}

 	function get_user_info($id)
 	{
 		$retVal = $this->db->query('select airline_name_field,from_field,to_field,flight_duration_field,total_fare_field,adult_count_field,youth_count_field,kids_count_field,total_count_field,travel_date,booking_details,flight_type from farebucket_multiway_save where id = "'.$id.'"');
 		return $retVal->result();
 	}

 	function add_user_return_details($data)
 	{
 		$this->db->insert('farebucket_return_session',$data);
 		return $this->db->insert_id();
 	}

 	function get_return_user_info($id)
 	{
 		$retVal = $this->db->query('select * from farebucket_return_session where id = "'.$id.'"');
 		return $retVal->result();
 	}

 	function validate_guest($guest_email)
 	{
 		$retVal = $this->db->query('select user_id from farebucket_user where user_email = "'.$guest_email.'"');
 		if($retVal->num_rows() > 0)
 		{
 			return $retVal->result()[0];
 		}
 		else
 		{
 			return 0;
 		}
 	}

 	function get_booking_date() {
        $data = $this->db->query('select date from farebucket_ticket_confirmation');
        return $data->result();
    }

    function update_status_by_date($booking_date) {
        if($this->db->update('farebucket_ticket_confirmation',array('status' => "Closed"),array('date' => $booking_date))) {
            return true;
        }
        else {
            return false;
        }
    }

    function setTicketDetails($data){
   		$this->db->insert('farebucket_ticket_save', $data);
   		return $this->db->insert_id();
    }

    function fetchTicketDetailsById($id){
    	$data = $this->db->get_where('farebucket_ticket_save', array('id' => $id));
    	if( $data ){
    		return $data->result();
    	}else{
    		return false;
    	}
    }

    function fetchTicketDetails($id){
    	$data = $this->db->get_where('farebucket_ticket_save', array('fbBooking_id' => $id));
    	if( $data ){
    		return $data->result();
    	}else{
    		return false;
    	}
    }

    public function checkDiscountCode($discountCode, $discountModule) {        
        $data = $this->db->get_where("farebucket_discount_code", array('discount_code_name'=>$discountCode, 'discount_code_module'=>$discountModule));
        if($data->num_rows() > 0){
        	return $data->result();
        }else{
        	$data = $this->db->get_where("farebucket_discount_code", array('discount_code_name'=>$discountCode, 'discount_code_module'=>'all'));
        	return $data->result();
        }
    }

    function getTicketByBookingID($bookingid)
    {
      $data = $this->db->get_where("farebucket_ticket_confirmation", array('fb_bookingId' => $bookingid));    	
      if($data){
	    	return $data->result(); 
	    }
	    else{
	    	return false;
	    }
    }

    public function get_background_image($module){
    	$data = $this->db->get_where("farebucket_media",array('background_module' => $module));
    	if($data){
    		return $data->result();
    	}
    	else{
    		return false;
    	}
    }

 	function add_user_details($data)
 	{
 		$this->db->insert('farebucket_multiway_save',$data);
 		return $this->db->insert_id();
 	}

    public function updatePayuId($payu_id, $booking_id){
      $this->db->where('booking_id', $booking_id);
      if($this->db->update('farebucket_ticket_confirmation', array('payu_id' => $payu_id)))
        return true;
      else
        return false;
    }

    public function getLastFbBookingId(){
    	$data = $this->db->query('select fb_bookingId from farebucket_ticket_confirmation order by ticket_id desc limit 0,1');
    	$res = $data->result();
    	$fbId = ($res[0]->fb_bookingId);
    	return $fbId;	
    }

    function saveCancellationDate($today, $ticketid){
    	$this->db->where('BookingId', $ticketid);
    	if($this->db->update('farebucket_ticket_save', array('cancellationDate' => $today)))
        return true;
      else
        return false;
    }

}