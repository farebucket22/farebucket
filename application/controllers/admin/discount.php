<?php
class discount extends CI_Controller{
    
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
    	$this->load->model('admin/discount_model');
    	$discounts = $this->discount_model->getalldiscounts();
        $this->load->view('admin/header');        
    	$this->load->view('admin/discount/list',array('discounts' => $discounts));
    }

    function add()
    {
        $this->form_validation->set_rules('discount_code_name', 'discount name', 'required');
        $this->form_validation->set_rules('discount_code_value', 'discount value', 'required|numeric');
        $this->form_validation->set_rules('discount_code_type', 'discount type', 'required');
        $this->form_validation->set_rules('discount_code_status', 'discount status', 'required');
        $this->form_validation->set_rules('discount_code_module', 'discount module', 'required');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $data['discount_code_name'] = $this->input->post('discount_code_name');
            $data['discount_code_value'] = $this->input->post('discount_code_value');
            $data['discount_code_type'] = $this->input->post('discount_code_type');
            if($this->input->post('discount_code_status') == 'active')
            	$data['discount_code_status'] = 1;
            else
            	$data['discount_code_status'] = 0;
            $data['discount_code_module'] = $this->input->post('discount_code_module');
            $data['display_on_site'] = $this->input->post('display_on_site');
            $this->load->model('admin/discount_model');
            
            if($this->discount_model->creatediscount($data))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Discount successfully created</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Discount not created! Please try again later.</h6></div>');

            redirect('admin/discount');
        }
    }

    function edit()
    {
        $discount_code_id = $this->input->get('discount_code_id');
        $this->load->model('admin/discount_model');
        $discount = $this->discount_model->getdiscountbyid($discount_code_id);
        $discount_details = $this->discount_model->getalldiscounts();
        $data = array('discount' => $discount,'discount_details' => $discount_details);
        $this->load->view('admin/header');        
        $this->load->view('admin/discount/edit',$data);
    }

      function update()
    {
        $this->form_validation->set_rules('discount_code_name', 'discount name', 'required');
        $this->form_validation->set_rules('discount_code_value', 'discount value', 'required|numeric');
        $this->form_validation->set_rules('discount_code_type', 'discount type', 'required');
        $this->form_validation->set_rules('discount_code_status', 'discount status', 'required');
        $this->form_validation->set_rules('discount_code_module', 'discount module', 'required');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->edit();
        }
        else
        {
            $discount_code_id = $this->input->get('discount_code_id');
            $data['discount_code_name'] = $this->input->post('discount_code_name');
            $data['discount_code_value'] = $this->input->post('discount_code_value');
            $data['discount_code_type'] = $this->input->post('discount_code_type');
            if($this->input->post('discount_code_status') == 'active')
            	$data['discount_code_status'] = 1;
            else
            	$data['discount_code_status'] = 0;
            $data['discount_code_module'] = $this->input->post('discount_code_module');
            $data['display_on_site'] = $this->input->post('display_on_site');
            $this->load->model('admin/discount_model');
            
            if($this->discount_model->updatediscount($data,$discount_code_id))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Discount successfully updated</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Discount not udpated! Please try again later.</h6></div>');
            
            redirect('admin/discount');
        }
    }

    function delete()
    {
        $discount_id = $_GET['discount_code_id'];
        $this->load->model('admin/discount_model');
        if($this->discount_model->deleteById($discount_id))
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Discount was successfully deleted</h6></div>');
        else
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Discount not deleted! Please try again later.</h6></div>');
            redirect('admin/discount');            
    }
}
?>