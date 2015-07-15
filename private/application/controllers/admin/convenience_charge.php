<?php
class convenience_charge extends CI_Controller{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        
       if(!$this->session->userdata('admin_logged_in'))
            redirect('admin/login');
    }

    function index()
    {
    	$this->load->model('admin/convenience_model');
    	$charge = $this->convenience_model->getconveniencecharge();
    	$this->load->view('admin/convenience_charge/list',array('charge' => $charge));
    }

    function edit()
    {
        $this->load->model('admin/convenience_model');
        $charge = $this->convenience_model->getconveniencecharge();
        $this->load->view('admin/convenience_charge/edit',array('charge' => $charge));
    }

    function update()
    {
       $this->load->model('admin/convenience_model');
       $update = $this->convenience_model->updateconveniencecharge($this->input->post('convenience_charge'));
       $charge = $this->convenience_model->getconveniencecharge();
       $this->load->view('admin/convenience_charge/list',array('charge' => $charge));
    }
}
?>