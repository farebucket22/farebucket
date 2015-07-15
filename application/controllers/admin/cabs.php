<?php
class cabs extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->output->set_header('Pragma: no-cache');  
        
        if(!$this->session->userdata('admin_logged_in'))
            redirect('admin/login');
    }

     function index(){
        $this->load->model('admin/cab_model');
        $cabs = $this->cab_model->getCabBookings();
        $this->load->view('admin/header');        
        $this->load->view('admin/cab/list', array('cabs' => $cabs));
    }  

    function show_balance(){
        $data = $_GET;
        $this->load->view('admin/header');        
        $this->load->view('admin/cab/show_balance', $data);
    }
}
?>