<?php
class default_page extends MY_Controller{
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
    	$this->load->view('admin/header');
        $this->load->view('admin/default_page');
    }

    function set_page(){
    	$this->form_validation->set_rules('page_name','page_name', 'required');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $default_page['default_controller'] = $this->input->post('page_name');
            $this->session->set_userdata(array('default_controller' => $default_page));
            $this->load->model('admin/default_model');
            $data = $this->default_model->update_controller($default_page);
            if($data)
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Changed default page successfully</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Error occured while changing a default page.</h6></div>');

            redirect('admin/default_page');
        }
    }
}
?>