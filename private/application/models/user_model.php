<?php
class User_Model extends CI_Model{
    public function get_user_details($userId){
        $data = $this->db->get_where("farebucket_user", array('user_id'=>$userId));
        return $data->result();
    }
    
    public function update_user_details($userId, $data) {
        $this->db->where('user_id', $userId);
        return $this->db->update('farebucket_user', $data);
    }
    
    public function update_user_password($userId, $data){
        $this->db->where('user_id', $userId);
        return $this->db->update('farebucket_user', $data);
    }

    public function get_user_details_csv($query){     
        $result = $this->db->query( $query );
        if( $result->num_rows > 0 ){
            return $result->result();
        }
        else{
            return false;
        }
    }
}