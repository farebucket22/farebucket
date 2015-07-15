<?php


class city_model extends CI_Model {

    /**************************************************************************
      Used in 
         admin/city/index
         admin/sub_sub_category/create
    **************************************************************************/
    function getallcities()
    {
        $query = $this->db->query('select * from farebucket_activity_city ');
        if($query->num_rows > 0)
        {
            return $query->result();
        }
        
        return false;
    }
    
    /**************************************************************************
      Used in 
         admin/city/edit
         admin/sub_sub_category/index
    **************************************************************************/
    function getcitybyid($id)
    {
        $query = $this->db->query('select * from farebucket_activity_city where activity_city_id='.$id);
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
         admin/city/add
    **************************************************************************/
    function checkcitybyname($name)
    {
        $query = $this->db->get_where('farebucket_activity_city', array('activity_city_name' => $name));
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
         admin/city/update
    **************************************************************************/
    function getothercities($name,$id)
    {
        $query = $this->db->query('select * from farebucket_activity_city where activity_city_name="'.$name.'" and activity_city_id !='.$id);
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
         admin/city/add
    **************************************************************************/
    function createcity($param)
    {
        if($this->db->insert('farebucket_activity_city',$param))
            return true;
        else
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/city/update
    **************************************************************************/
    function updatecity($param,$id)
    {
        if($this->db->update('farebucket_activity_city',$param,array('activity_city_id' => $id)))
            return true;
        else
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/city/delete
    **************************************************************************/
    function deletebyid($id)
    {    
        $query = $this->db->query('DELETE FROM farebucket_activity_city WHERE activity_city_id='.$id);
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
    function getcityforcountry($cat_id)
    {
        $query = $this->db->get_where('farebucket_activity_city',array('activity_country_id' => $cat_id));
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result;
        }
        return false;
    }
}

?>
