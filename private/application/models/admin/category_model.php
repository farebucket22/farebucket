<?php

class category_model extends CI_Model {

    /**************************************************************************
      Used in 
         admin/category/index
    **************************************************************************/
    function getallcategories()
    {
        $query = $this->db->query('select * from farebucket_activity_category');
        if($query->num_rows > 0)
        {
            return $query->result();
        }
        
        return false;
    }
    
    /**************************************************************************
      Used in 
         admin/category/edit
    **************************************************************************/
    function getcategorybyid($id)
    {
        $query = $this->db->query('select * from farebucket_activity_category where activity_category_id='.$id);
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
         admin/category/add
    **************************************************************************/
    function getcategorybyname($name)
    {
        $query = $this->db->get_where('farebucket_activity_category', array('activity_category_name' => $name));
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
         admin/category/add
    **************************************************************************/
    function createcategory($param)
    {
        if($this->db->insert('farebucket_activity_category',$param))
            return true;
        else
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/category/update
    **************************************************************************/
    function updatecategory($param,$id)
    {
        if($this->db->update('farebucket_activity_category',$param,array('activity_category_id' => $id)))
            return true;
        else
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/category/delete
    **************************************************************************/
    function deletebyid($id)
    {    
        $query = $this->db->query('DELETE FROM farebucket_activity_category WHERE activity_category_id='.$id);
        if ($query)
        {
            return $query;
        } 

        return false;
    }
}

?>
