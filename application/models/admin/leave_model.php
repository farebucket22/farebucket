<?php      class leave_model extends CI_Model {

    /**************************************************************************
      Used in 
         admin/leave/index
         admin/sub_sub_category/create
    **************************************************************************/
    function getallleaves()
    {
        $query = $this->db->query('select * from farebucket_activity_leave ');
        if($query->num_rows > 0)
        {
            return $query->result();
        }
        
        return false;
    }
    
    /**************************************************************************
      Used in 
         admin/leave/edit
         admin/sub_sub_category/index
    **************************************************************************/
    function getleavebyid($id)
    {
        $query = $this->db->query('select * from farebucket_activity_leave where activity_leave_id='.$id);
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result[0];
        }
        else 
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/leave/add
    **************************************************************************/
    function checkleavebyname($name)
    {
        $query = $this->db->get_where('farebucket_activity_leave', array('activity_leave_name' => $name));
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result;
        }
        else 
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/leave/update
    **************************************************************************/
    function getotherleaves($name,$id)
    {
        $query = $this->db->query('select * from farebucket_activity_leave where activity_leave_name="'.$name.'" and activity_leave_id !='.$id);
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result;
        }
        else 
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/leave/add
    **************************************************************************/
    function createleave($param)
    {
        if($this->db->insert('farebucket_activity_leave',$param))
            return true;
        else
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/leave/update
    **************************************************************************/
    function updateleave($param,$id)
    {
        if($this->db->update('farebucket_activity_leave',$param,array('activity_leave_id' => $id)))
            return true;
        else
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/leave/delete
    **************************************************************************/
    function deletebyid($id)
    {    
        $query = $this->db->query('DELETE FROM farebucket_activity_leave WHERE activity_leave_id='.$id);
        if ($query)
        {
            return $query;
        } 

        return false;
    }
    
    /**************************************************************************
      Used in 
         admin/sub_sub_category/get_subcats_for_cat
    **************************************************************************/
    function getleaveforactivity($cat_id)
    {
        $query = $this->db->get_where('farebucket_activity_leave',array('activity_id' => $cat_id));
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result;
        }
        return false;
    }
}

?>