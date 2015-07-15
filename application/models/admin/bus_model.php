<?php

class bus_model extends CI_Model{

	function getBusBookings(){
        $query = $this->db->query('select * from farebucket_bus_details');
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result;
        }
        else 
            return false;
	}

    function updateTicketStatus($bookin_no){
        if($this->db->update('farebucket_bus_details', array('status' => "Cancelled"),array('BookingRefNo' => $bookin_no)))
            return true;
        else
            return false;
    }

    function getCancellation(){
        $cancellation = $this->db->get_where('farebucket_bus_details', array('status' => "Cancelled"));
        if( $cancellation->num_rows > 0 ){
            return $cancellation->result();
        }else{
            return false;
        }
    }
}

?>