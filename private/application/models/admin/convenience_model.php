<?php
class convenience_model extends CI_Model {
	function getconveniencecharge()
    {
        $query = $this->db->query('select * from farebucket_convenience_charge');
        if($query->num_rows > 0)
        {
            return $query->result();
        }
        
        return false;
    }


    function updateconveniencecharge($param)
    {
        $this->db->where('id', 1);
        $ret = $this->db->update('farebucket_convenience_charge', array('convenience_charge' => $param)); 
        if( $ret )
            return true;
        else
            return false;
    }

    function get_convenience_charge()
    {
        $query = $this->db->query('select * from farebucket_convenience_charge');
        if($query->num_rows > 0)
        {
            return $query->result()[0];
        }
        
        return false;
    }

}
?>