<?php
class convenience_charge extends CI_Controller{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->output->set_header('Pragma: no-cache');          
        
       if(!$this->session->userdata('admin_logged_in'))
            redirect('admin/login');
    }

    function index()
    {
    	$this->load->model('admin/convenience_model');
    	$charge = $this->convenience_model->getconveniencecharge();
        $this->load->view('admin/header');        
    	$this->load->view('admin/convenience_charge/list',array('charge' => $charge));
    }

    function edit()
    {
        $id = $this->input->get('id');
        $this->load->model('admin/convenience_model');
        $charge = $this->convenience_model->getconveniencechargebyid($id);
        $this->load->view('admin/header');        
        $this->load->view('admin/convenience_charge/edit',array('charge' => $charge));
    }

    function update()
    {
       $this->load->model('admin/convenience_model');
       $update = $this->convenience_model->updateconveniencecharge($this->input->post());
       $charge = $this->convenience_model->getconveniencecharge();
       $this->load->view('admin/header');
       $this->load->view('admin/convenience_charge/list',array('charge' => $charge));
    }
}
?>