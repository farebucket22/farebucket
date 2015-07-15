<?php
class hotel extends MY_Controller{
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
        $this->load->model('admin/hotel_model');
        $hotels = $this->hotel_model->getHotelBookings();
        $this->load->view('admin/header');        
        $this->load->view('admin/hotel/list', array('hotels' => $hotels));
    }  
}
?>