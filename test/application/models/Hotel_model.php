<?php

class Hotel_model extends CI_Model
{

	public function __construct()
    {
        parent::__construct();
    }


	public function setAuthenticationData($data)
	{
		$data = $this->db->insert('fb_hotel_auth',$data);
		if($data)
			return true;
		else
			return false;
	}

	public function getTokenId()
	{
		$data = $this->db->query("select * from fb_hotel_auth");
		if($data){
			return ($data->result()[0]);
		}
		else
			return false;
	}
}