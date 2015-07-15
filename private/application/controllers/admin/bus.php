<?php
class bus extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        
        if(!$this->session->userdata('admin_logged_in'))
            redirect('admin/login');
    }

     function index(){
        $this->load->model('admin/bus_model');
        $buses = $this->bus_model->getBusBookings();
        $this->load->view('admin/bus/list', array('buses' => $buses));
    }  
}
?>