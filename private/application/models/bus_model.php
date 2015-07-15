<?php
class bus_model extends CI_Model{
	function putBusesBookResult($response){
		$ret = $this->db->insert('farebucket_bus_details', $response);
		if($ret)
			return $this->db->insert_id();
		else
			return false;
	}

	function storeBooking($data){
		$ret = $this->db->insert('farebucket_bus_confirmation', $data);
		if($ret)
			return $this->db->insert_id();
		else
			return false;
	}

	function updateBookingStatus($bookingId, $status){
		$this->db->where('id', $bookingId);
		if($this->db->update('farebucket_bus_confirmation', array('status' => $status))){
			return true;
		}else{
			return false;
		}
	}

	function getBusesBookResult($id){
		$ret = $this->db->get_where('farebucket_bus_details', array('fb_bookingId' => $id));
		if($ret){
			$details = $ret->result();
			return $details[0];
		}else{
			return false;
		}
	}

	function getBookingByUserId($id){
		$ret = $this->db->get_where('farebucket_bus_details', array('user_id' => $id));
		if($ret){
			$details = $ret->result();
			return $details;
		}else{
			return false;
		}
	}

	function updateTicketStatus($fb_bookingId){
		$this->db->where('fb_bookingId', $fb_bookingId);
		$ret = $this->db->update('farebucket_bus_details', array('status' => 'cancelled'));
		if( $ret ){
			return true;
		}else{
			return false;
		}
	}

	function updateRefundAmtAndCanlAmt($fb_bookingId, $refundAmt, $canlAmt){
		$this->db->where('fb_bookingId', $fb_bookingId);
		$ret = $this->db->update('farebucket_bus_details', array('refundAmt' => $refundAmt, 'canlAmt' => $canlAmt));
		if( $ret ){
			return true;
		}else{
			return false;
		}
	}

	function getLastFbBookingId(){
		$data = $this->db->query('select fb_bookingId from farebucket_bus_details order by id desc limit 0,1');
    	$res = $data->result();
    	$fbId = ($res[0]->fb_bookingId);
    	return $fbId;
	}

	public function updatePayuId($payu_id, $booking_id){
      $this->db->where('fb_bookingId', $booking_id);
      if($this->db->update('farebucket_bus_details', array('payu_id' => $payu_id)))
        return true;
      else
        return false;
    }

    function saveCancellationDate($today, $ticketid){
    	$this->db->where('fb_bookingId', $ticketid);
    	if($this->db->update('farebucket_bus_details', array('cancellationDate' => $today)))
        return true;
      else
        return false;
    }

}