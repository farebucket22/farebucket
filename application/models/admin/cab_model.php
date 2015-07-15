<?php

class cab_model extends CI_Model{

	function getCabBookings(){
		 $query = $this->db->query('select * from farebucket_cab_details');
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result;
        }
        else 
            return false;
	}

    function updateTicketStatus($ref_id,$booking_id){
    if($this->db->update('farebucket_cab_details', array('booking_status' => "Cancelled"),array('booking_ref_id' => $booking_id)))
            return true;
        else
            return false;
    }

    function getCancellation(){
        $cancellation = $this->db->get_where('farebucket_cab_details', array('booking_status' => "Cancelled"));
        if( $cancellation->num_rows > 0 ){
            return $cancellation->result();
        }else{
            return false;
        }
    }
}
?>