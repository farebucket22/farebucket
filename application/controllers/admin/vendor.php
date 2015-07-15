<?php
class vendor extends MY_Controller {
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
        $this->load->model('admin/vendor_model');
        $vendors = $this->vendor_model->getallvendors();
        $data = array('vendors' => $vendors);
        $this->load->view('admin/header');        
        $this->load->view('admin/vendor/list', $data);
    }

    function add(){
        $this->form_validation->set_rules('vendor_name', 'Vendor Name', 'required|min_length[4]|max_length[100]|');
        $this->form_validation->set_rules('vendor_email', 'Vendor Email', 'required|valid_email');
        $this->form_validation->set_rules('vendor_number', 'Vendor Number', 'required');
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $data['vendor_name'] = ucfirst($this->input->post('vendor_name'));
            $data['vendor_email'] = $this->input->post('vendor_email');
            $data['vendor_number'] = $this->input->post('vendor_number');
            $this->load->model('admin/vendor_model');
            
            if($this->vendor_model->addvendor($data))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Vendor successfully created</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Vendor not created! Please try again later.</h6></div>');

            redirect('admin/vendor');
        }
    }

    function edit()
    {
        $vendor_id = $this->input->get('id');
        $this->load->model('admin/vendor_model');
        $vendor = $this->vendor_model->getvendorbyid($vendor_id);
        $data = array('vendor' => $vendor);
        $this->load->view('admin/header');        
        $this->load->view('admin/vendor/edit',$data);
    }

    function update()
    {
        $this->form_validation->set_rules('vendor_name', 'Vendor Name', 'required|min_length[4]|max_length[100]|');
        $this->form_validation->set_rules('vendor_email', 'Vendor Email', 'required|valid_email');
        $this->form_validation->set_rules('vendor_number', 'Vendor Number', 'required');
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $vendor_id = $this->input->get('id');
            $data['vendor_name'] = ucfirst($this->input->post('vendor_name'));
            $data['vendor_email'] = $this->input->post('vendor_email');
            $data['vendor_number'] = $this->input->post('vendor_number');
            $this->load->model('admin/vendor_model');
            
            if($this->vendor_model->updatevendor($vendor_id,$data))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Vendor successfully updated</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Vendor not udpated! Please try again later.</h6></div>');
            
            redirect('admin/vendor');
        }
    }

    function delete()
    {
        $vendor_id = $_GET['id'];
        $this->load->model('admin/vendor_model');
        if($this->vendor_model->deleteById($vendor_id))
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Vendor was successfully deleted</h6></div>');
        else
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Vendor not deleted! Please try again later.</h6></div>');
            redirect('admin/vendor');            
    }
}
?>