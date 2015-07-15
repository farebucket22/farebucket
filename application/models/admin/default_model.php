<?php

class default_model extends CI_Model{

	function update_controller($data)
	{
		if($this->db->update('farebucket_default_page',$data,array('id' => 1)))
			return true;
		else
			return false;
	}

	function get_controller()
	{
		$controller = $this->db->query('select default_controller from farebucket_default_page where id = 1');
		return $controller->result()[0]->default_controller;
	}

	function updateRefundValue($query){
		$retVar = true;
		switch ($query['module']) {
			case ' BUS':					
				$this->db->where('id', $query['tableId']);
				if( $this->db->update('farebucket_bus_details', array('AmountRefunded' => 'Yes')) ){
					$retVar = true;
				}else{
					$retVar = false;
				}
				break;
			case ' FLIGHT':
				$this->db->where('id', $query['tableId']);
				if( $this->db->update('farebucket_ticket_save', array('AmountRefunded' => 'Yes')) ){
					$retVar = true;
				}else{
					$retVar = false;
				}
				break;
			case ' CAB':
				$this->db->where('id', $query['tableId']);
				if( $this->db->update('farebucket_cab_details', array('AmountRefunded' => 'Yes')) ){
					$retVar = true;
				}else{
					$retVar = false;
				}
				break;
			case ' HOTEL':
				$this->db->where('id', $query['tableId']);
				if( $this->db->update('farebucket_hotel_details', array('AmountRefunded' => 'Yes')) ){
					$retVar = true;
				}else{
					$retVar = false;
				}
				break;
			case ' ACTIVITY':
				$this->db->where('booking_id', $query['tableId']);
				if( $this->db->update('farebucket_activity_booking', array('AmountRefunded' => 'Yes')) ){
					$retVar = true;
				}else{
					$retVar = false;
				}
				break;
		}
		return $retVar;

	}

}
?>