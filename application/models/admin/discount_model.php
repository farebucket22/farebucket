<?php
class discount_model extends CI_Model {
	function getalldiscounts()
    {
        $query = $this->db->query('select * from farebucket_discount_code');
        if($query->num_rows > 0)
        {
            return $query->result();
        }
        
        return false;
    }

    function creatediscount($param)
    {
    	if($this->db->insert('farebucket_discount_code',$param))
            return true;
        else
            return false;
    }

    function getdiscountbyid($id)
    {
        $query = $this->db->query('select * from farebucket_discount_code where discount_code_id='.$id);
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result[0];
        }
        else 
            return false;
    }

    function updatediscount($param,$id)
    {
        if($this->db->update('farebucket_discount_code',$param,array('discount_code_id' => $id)))
            return true;
        else
            return false;
    }

    function deleteById($id)
    {
    	$query = $this->db->query('DELETE FROM farebucket_discount_code WHERE discount_code_id='.$id);
        if ($query)
        {
            return $query;
        } 

        return false;
    }
}
?>