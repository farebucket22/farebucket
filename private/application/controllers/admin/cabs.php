<?php
class cabs extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        
        if(!$this->session->userdata('admin_logged_in'))
            redirect('admin/login');
    }

     function index(){
        $this->load->model('admin/cab_model');
        $cabs = $this->cab_model->getCabBookings();
        $this->load->view('admin/cab/list', array('cabs' => $cabs));
    }  
}
?>