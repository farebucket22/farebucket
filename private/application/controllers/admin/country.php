<?php

class country extends CI_Controller{
    
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
        $this->load->model('admin/country_model');
        
        $countries = $this->country_model->getallcountries();
        
        $data = array('countries' => $countries);
        $this->load->view('admin/country/list',$data);
    }
    
    function checkcountry()
    {
        $name = $_POST['name'];
        
        $this->load->model('admin/country_model');
        $country = $this->country_model->getcountrybyname($name);
        
        if(!(is_numeric($name)))
        {
            if(!$country)
            {
                return true;
            }
            else
            {
                //if edit and not create
                if(isset($_GET['id']))
                {
                    if($_GET['id'] == $country->activity_country_id)
                        return true;
                }
                $this->form_validation->set_message('checkcountry', 'Country already exists! Please try again with a different name.');
                return false;
            }
        }
        else
        {
             $this->form_validation->set_message('checkcountry', 'Country name cant be numberic! Please provide a valid country name.');
            return false;
        }
    }
    
    
    function add()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|min_length[4]|max_length[50]|callback_checkcountry');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $data['activity_country_name'] = $this->input->post('name');
            
            $this->load->model('admin/country_model');
            
            if($this->country_model->createcountry($data))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Country successfully created.</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Country not created! Please try again later.</h6></div>');
            
            redirect('admin/country');
        }
    }
    
    function edit()      
    {
        $id = $this->input->get('id');
        
        $this->load->model('admin/country_model');
        $country = $this->country_model->getcountrybyid($id);
        $data = array('country' => $country);
        $this->load->view('admin/country/edit',$data);
    }
    
    function update()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|min_length[4]|max_length[50]|callback_checkcountry');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->edit();
        }
        else
        {
            $id = $this->input->get('id');
            $data['activity_country_name'] = $this->input->post('name');
            
            $this->load->model('admin/country_model');
            
            if($this->country_model->updatecountry($data,$id))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success"><h6>Country successfully updated</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger"><h6>Country not udpated! Please try again later.</h6></div>');
            
            redirect('admin/country');
        }
    }
    
    function delete()
    {
        $id = $_GET['id'];
        //Loading model
        $this->load->model('admin/country_model');
        
        if($this->country_model->deletebyid($id))
        {
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Country was successfully deleted</h6></div>');
        }
        else
        {
            if($this->db->_error_number())
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>DB Error: '.$this->db->_error_number().' Delete correspoding Localities before attempting to delete City </h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Country not deleted! Please try again later.</h6></div>');       
        }
        redirect('admin/country');
    }
}

?>