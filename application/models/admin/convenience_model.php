<?php
class convenience_model extends CI_Model {
	function getconveniencecharge()
    {
        $query = $this->db->get('farebucket_convenience_charge');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    function getconveniencechargebyid($id)
    {
        $query = $this->db->get_where('farebucket_convenience_charge', array('id' => $id));
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }


    function updateconveniencecharge($param)
    {
        $this->db->where('id', $param['id']);
        $ret = $this->db->update('farebucket_convenience_charge', array('convenience_charge' => $param['convenience_charge'],'convenience_charge_msg' => $param['convenience_charge_msg'],'module' => $param['module'])); 
        if( $ret )
            return true;
        else
            return false;
    }

    function get_convenience_charge($module)
    {
        $query = $this->db->get_where('farebucket_convenience_charge', array('module' => $module));
        if($query->num_rows() > 0){
            return $query->result()[0];
        }else{
            return false;
        }
    }

}
?>