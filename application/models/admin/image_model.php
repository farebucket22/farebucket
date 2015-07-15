<?php
class image_model extends CI_Model 
{
    function getallimages($activity_id)
    {
        $query = $this->db->query('SELECT * FROM farebucket_activity_images WHERE activity_id='.$activity_id.'');
        
        if ($query->num_rows() > 0)
        {    
            $result =  $query->result();
            return $result;
        }
        else
            return false;
    }
    
    function add_image($param)
    {
        if($this->db->insert('farebucket_activity_images',$param))
            return $this->db->insert_id();
        else
            return false;
    }

    function add_flight_image($param)
    {
        if($this->db->insert('farebucket_media',$param))
            return $this->db->insert_id();
        else
            return false;
    }

    function edit_flight_image($param,$id)
    {
        if($this->db->update('farebucket_media',$param,array('id' => $id)))
            return true;
        else
            return false;
    }
    
    function getimage($id)
    {
        $query = $this->db->get_where('farebucket_activity_images',array('id' => $id));
        if ($query->num_rows() > 0)
        {    
            $result =  $query->result();
            return $result[0];
        }
        else
            return false;
    }
    
    function getimageactivityid($id)
    {
         $query = $this->db->get_where('farebucket_activity_images',array('id' => $id));
        if ($query->num_rows() > 0)
        {    
            $result =  $query->result();
            return $result[0]->activity_id;
        }
        else
            return false;
    }
    
    function set_is_main_indicator($id)
    {
         if($this->db->update('farebucket_activity_images',array('is_main'=> 1),array('id' => $id)))
         {
            $activity = $this->db->get_where('farebucket_activity_images',array('id' => $id));
            $act_id = $activity->result();
            $activity_id = $act_id[0]->activity_id;
            $result =$this->db->query('UPDATE farebucket_activity_images SET is_main=0 WHERE id!='.$id.' AND activity_id='.$activity_id);
            return true;
         }
          else
            return false;
    }
    
     function deletebyid($id)
    {    
        $query = $this->db->query('DELETE FROM farebucket_activity_images WHERE id='.$id);
        if ($query)
        {
            return $query;
        } 

        return false;
    }
    
    
}

?>