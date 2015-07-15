<?php


class country_model extends CI_Model {

    /**************************************************************************
      Used in 
         admin/country/index
    **************************************************************************/
    function getallcountries()
    {
        $query = $this->db->query('select * from farebucket_activity_country');
        if($query->num_rows > 0)
        {
            return $query->result();
        }
        
        return false;
    }
    
    /**************************************************************************
      Used in 
         admin/country/edit
    **************************************************************************/
    function getcountrybyid($id)
    {
        $query = $this->db->query('select * from farebucket_activity_country where activity_country_id='.$id);
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
         admin/country/add
    **************************************************************************/
    function getcountrybyname($name)
    {
        $query = $this->db->get_where('farebucket_activity_country', array('activity_country_name' => $name));
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
         admin/country/add
    **************************************************************************/
    function createcountry($param)
    {
        if($this->db->insert('farebucket_activity_country',$param))
            return true;
        else
            return false;
    }
    
    /**************************************************************************
      Used in 
         admin/country/update
    **************************************************************************/
    function updatecountry($param,$id)
    {
        if($this->db->update('farebucket_activity_country',$param,array('activity_country_id' => $id)))
            return true;
        else
            return false;
    }
    
   
    
    /**************************************************************************
      Used in 
         admin/country/delete
    **************************************************************************/
    function deletebyid($id)
    {    
        $query = $this->db->query('DELETE FROM farebucket_activity_country WHERE activity_country_id='.$id);
        if ($query)
        {
            return $query;
        } 

        return false;
    }
    
}

?>
