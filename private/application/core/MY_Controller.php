<?php
class MY_Controller extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }
    
    public function set_user_session($data){
        $_SESSION['data'] = $data;
    }
    
    public function get_user_session(){
        return $_SESSION['data'];
    }
    
    public function get_session_data($req){
        return $_SESSION[$req];
    }
}
?>