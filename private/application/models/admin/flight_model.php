<?php

class flight_model extends CI_Model{

	function getFlightBookings(){
		 $query = $this->db->query('select * from farebucket_ticket_confirmation');
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result;
        }
        else 
            return false;
	}

	function setServiceChargeStatus($data){
		
	if($this->db->update('farebucket_service_charge',$data,array('id' => 1)))
            return true;
        else
            return false;
	}

    function getBackgrounds(){
        if( $query = $this->db->get('farebucket_media') )
            return $query->result();
        else
            return false;
    }

    function setBackground($data,$id){
        if($this->db->update('farebucket_media',$data,array('id' => $id)))
            return true;
        else
            return false;
    }

    function delBackground($data){
        if( $this->db->delete('farebucket_media', array('id' => $data)) )
            return true;
        else
            return false;
    }

    function getCancellation(){
        // $cancellation = $this->db->get_where('farebucket_ticket_confirmation', array('status' => "Cancelled"));
        $cancellation = $this->db->query('SELECT * FROM farebucket_ticket_confirmation INNER JOIN farebucket_ticket_save ON farebucket_ticket_confirmation.booking_id = farebucket_ticket_save.BookingId WHERE farebucket_ticket_confirmation.status = "Cancelled"');
        if( $cancellation->num_rows > 0 ){
            return $cancellation->result();
        }else{
            return false;
        }
    }
}
?>