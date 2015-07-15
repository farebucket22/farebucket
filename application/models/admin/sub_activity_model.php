<?php


class Sub_activity_model extends CI_Model {

    /**************************************************************************
      Used in 
         admin/sub_activity/index
         admin/sub_sub_category/create
    **************************************************************************/
    function getallsub_activities()
    {
        $query = $this->db->query('select * from farebucket_activity_sub_type ');
        if($query->num_rows > 0)
        {
            return $query->result();
        }
        
        return false;
    }
    
  
    
   
    
    /**************************************************************************
      Used in 
         admin/sub_activity/update
    **************************************************************************/
    function getothersub_activities($name,$id)
    {
        $query = $this->db->query('select * from farebucket_activity_sub_type where activity_sub_activity_name="'.$name.'" and activity_sub_activity_id !='.$id);
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result;
        }
        else 
            return false;
    }

    function getsub_activitybyid($id)
    {
        $query = $this->db->query('select * from farebucket_activity_sub_type where activity_sub_type_id='.$id);
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
         admin/sub_activity/add
    **************************************************************************/
    function createsub_activity($param)
    {
        if($this->db->insert('farebucket_activity_sub_type',$param))
            return true;
        else
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/sub_activity/update
    **************************************************************************/
    function updatesub_activity($param,$id)
    {
        if($this->db->update('farebucket_activity_sub_type',$param,array('activity_sub_type_id' => $id)))
            return true;
        else
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/sub_activity/delete
    **************************************************************************/
    function deletebyid($id)
    {    
        $query = $this->db->query('DELETE FROM farebucket_activity_sub_type WHERE activity_sub_type_id='.$id);
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
    function getsub_activityforactivity($cat_id)
    {
        $query = $this->db->get_where('farebucket_activity_sub_type',array('activity_id' => $cat_id));
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result;
        }
        return false;
    }
}

?>
