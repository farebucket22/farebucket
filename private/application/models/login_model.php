<?php

class Login_Model extends CI_Model{
    
    public function check_login($email, $password) {
        $data = $this->db->get_where('farebucket_user', array('user_email' => $email, 'user_password'=>$password));
        return $data->result();
    }

    public function register_user($firstName, $lastName, $dob, $email, $password, $phone_no, $gender, $title, $type=null){
    	$data = array(
		   'user_first_name' => $firstName,
		   'user_last_name' => $lastName,
		   'user_dob' => $dob,
		   'user_email' => $email,
		   'user_password' => $password,
		   'user_mobile' => $phone_no,
		   'user_gender' => $gender,
           'user_type' => $type,
		   'user_title' => $title
		);

		$this->db->insert('farebucket_user', $data);

		return $this->db->insert_id();
    }

    public function check_new_user($email, $type){
        $g_flag = 0;
    	$data = array(
    		'user_email' => $email
		);

        //deletes guest entries with the same email id
        $ret = $this->db->get_where('farebucket_user', $data);
        if( $ret->num_rows > 0 ){
            $fnd = $ret->result();

            foreach( $fnd as $f ){
                if( $f->user_type == 2 ){
                    $g_flag = 1;
                }
            }

            if( $g_flag ){
                $data = array(
                    'user_email' => $email,
                    'user_type' => 2
                );
                $this->db->delete('farebucket_user', $data);
            }

        }

        $data = array(
            'user_email' => $email,
            'user_type' => $type
        );

    	$ret = $this->db->get_where('farebucket_user', $data);
    	if( $ret->num_rows > 0 ){
    		return true;
    	}else{
    		return false;
    	}
    }

    public function getUserById($id){
        $ret = $this->db->get_where('farebucket_user', array('user_id' => $id));
        if( $ret ){
            $details = $ret->result();
            return $details[0];
        }else{
            return false;
        }
    }

    public function ret_pass($email){
        $data = array(
            'user_email' => $email
        );
        $ret = $this->db->get_where('farebucket_user', $data);
        if( $ret->num_rows > 0 ){
            $data = $ret->result();
            if( $data[0]->user_type == 2 ){
                return false;
            } else {
                $details = array(
                    'user_first_name' => $data[0]->user_first_name,
                    'user_password' => $data[0]->user_password,
                );
                
                return $details;
            }
        }else{
            return false;
        }
    }

}

?>