<?php

class activity_model extends CI_Model{
    
    function getallactivities()
    {
        $query = $this->db->query('select * from farebucket_activity');
        if($query->num_rows > 0)
        {
            return $query->result();
        }
        
        return false;
    }
    
       function getactivitybyname($name)
    {
        $query = $this->db->get_where('farebucket_activity', array('activity_name' => $name));
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result[0];
        }
        else 
            return false;
    }
    
    function createactivity($param)
    {         
        if($this->db->insert('farebucket_activity',$param))
            return true;
        else
            return false;
    }
    
    function updateactivity($param,$id)
    {
        if($this->db->update('farebucket_activity',$param,array('activity_id' => $id)))
            return true;
        else
            return false;
    }
    
       function getactivitybyid($id)
    {
        $query = $this->db->query('select * from farebucket_activity where activity_id='.$id);
        if($query->num_rows > 0)
        {
            $result = $query->result();
            return $result[0];
        }
        else 
            return false;
    }
    

    
       function updatemainimage($param,$id)
    {
         if($this->db->update('farebucket_activity',$param,array('activity_id' => $id)))
            return true;
        else
            return false;
    }

    function add_onward_price($act_id,$adult_price)
    {

        $activity_onwards_price = $this->db->query('SELECT activity_onwards_price FROM farebucket_activity WHERE activity_id='.$act_id);
        $value = $activity_onwards_price->result();
        if($value[0]->activity_onwards_price == 0)
            $this->db->update('farebucket_activity',array('activity_onwards_price' => $adult_price ),array('activity_id' => $act_id));
        if($value[0]->activity_onwards_price > $adult_price)
            $this->db->update('farebucket_activity',array('activity_onwards_price' => $adult_price ),array('activity_id' => $act_id));
    }
    
     function deletebyid($id)
    {    
        $query = $this->db->query('DELETE FROM farebucket_activity WHERE activity_id='.$id);
        if ($query)
        {
            return $query;
        } 

        return false;
    }

    function getActivityBookings(){
        $this->db->select('*');
        $this->db->from('farebucket_activity_booking');
        $this->db->join('farebucket_user', 'farebucket_user.user_id = farebucket_activity_booking.user_id');
        $this->db->join('farebucket_activity', 'farebucket_activity.activity_id = farebucket_activity_booking.activity_id');
        $query = $this->db->get();

        if( $query ) {
            return $query->result();
        }
        else {
            return false;
        }
    }
    
}

?>
