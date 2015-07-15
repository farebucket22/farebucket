<?php
class cust_support_model extends CI_Model {

	function getCustSupportData(){
		$result = $this->db->get('farebucket_cust_support');
		if( $result->num_rows() > 0 ){
			return $result->result();
		}else{
			return false;
		}
	}

	function updateCustSupportData( $data ){
		$this->db->where('id', $data['id']);
		$data = $this->db->update('farebucket_cust_support', array('phone_number' => $data['phone_number'], 'email' => $data['email']));
		if( $data ){
			return true;
		}else{
			return false;
		}

	}
}

?>
