<?php


class admin_model extends CI_Model 
{
    function validate($data)
    {
        $admin_user = $this->db->query('SELECT * FROM farebucket_admin WHERE email="'.$data['user_email'].'"  and password= "'.$data['password'].'"');
        if($admin_user->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function checkUser($email)
    {
        $query = $this->db->query('select * from farebucket_admin where email = "' . $email . '"');
        if ($query->num_rows() > 0)
        {    
            $result =  $query->result();
            return $result[0];
        }
        else
            return false;
    }
}

?>
