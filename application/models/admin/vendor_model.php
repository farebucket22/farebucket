<?php
class vendor_model extends CI_Model {
	
	function getallvendors()
    {
        $query = $this->db->query('select * from farebucket_vendor ');
        if($query->num_rows > 0)
        {
            return $query->result();
        }
        
        return false;
    }

    function addvendor($param)
    {
    	 if($this->db->insert('farebucket_vendor',$param))
            return true;
        else
            return false;
    }

    function getvendorbyid($id)
    {
    	$query = $this->db->query('select * from farebucket_vendor where id='.$id);
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result[0];
        }
        else 
            return false;
    }

    function updatevendor($id,$param)
    {
        if($this->db->update('farebucket_vendor',$param,array('id' => $id)))
            return true;
        else
            return false;
    }

    function deletebyid($id)
    {    
        $query = $this->db->query('DELETE FROM farebucket_vendor WHERE id='.$id);
        if ($query)
        {
            return true;
        } 

        return false;
    }

    function get_vendor_email($vendor_name)
    {
        $data = $this->db->get_where("farebucket_vendor", array('vendor_name'=>$vendor_name));
        if($data->num_rows() > 0){
            $ret = $data->result();
            return $ret[0]->vendor_email;
        }else{
            return false;
        }
    }
}
?>