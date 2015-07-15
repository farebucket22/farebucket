<?php

class hotel_model extends CI_Model{

	function getHotelBookings(){
		 $query = $this->db->query('select * from farebucket_hotel_details');
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result;
        }
        else 
            return false;
	}

    function updateTicketStatus($bookin_no){
        if($this->db->update('farebucket_hotel_details', array('status' => "Cancelled"),array('BookingRefNo' => $bookin_no)))
            return true;
        else
            return false;
    }

    function getCancellation(){
        $cancellation = $this->db->get_where('farebucket_hotel_details', array('status' => "Cancelled"));
        if( $cancellation->num_rows > 0 ){
            return $cancellation->result();
        }else{
            return false;
        }
    }
}

?>