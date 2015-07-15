<?php

class category extends CI_Controller{
    
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
        $this->load->model('admin/category_model');
        
        $categories = $this->category_model->getallcategories();
        
        $data = array('categories' => $categories);
        $this->load->view('admin/header');        
        $this->load->view('admin/category/list',$data);
    }
    
    function checkcategory()
    {
        $name = $_POST['name'];
        
        $this->load->model('admin/category_model');
        $category = $this->category_model->getcategorybyname($name);
        if(!$category)
        {
            return true;
        }
        else
        {
            //if edit and not create
            if(isset($_GET['id']))
            {
                if($_GET['id'] == $category->activity_category_id)
                    return true;
            }
            $this->form_validation->set_message('checkcategory', 'Category already exists! Please try again with a different name.');
            return false;
        }
    }
    
    
    function add()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|max_length[100]|callback_checkcategory');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $data['activity_category_name'] = $this->input->post('name');
            
            $this->load->model('admin/category_model');
            
            if($this->category_model->createcategory($data))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Category successfully created</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Category not created! Please try again later.</h6></div>');
            
            redirect('admin/category');
        }
    }
    
    function edit()      
    {
        $id = $this->input->get('id');
        
        $this->load->model('admin/category_model');
        $category = $this->category_model->getcategorybyid($id);
        $data = array('category' => $category);
        $this->load->view('admin/header');
        $this->load->view('admin/category/edit',$data);
    }
    
    function update()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|max_length[100]|callback_checkcategory');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->edit();
        }
        else
        {
            $id = $this->input->get('id');
            $data['activity_category_name'] = $this->input->post('name');
            
            $this->load->model('admin/category_model');
            
            if($this->category_model->updatecategory($data,$id))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Category successfully updated</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-error" ><h6>Category not udpated! Please try again later.</h6></div>');
            
            redirect('admin/category');
        }
    }
    
    function delete()
    {
        $id = $_GET['id'];
        //Loading model
        $this->load->model('admin/category_model');
        
        if($this->category_model->deletebyid($id))
        {
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Category was successfully deleted</h6></div>');
        }
        else
        {
            if($this->db->_error_number())
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-error" ><h6>DB Error: '.$this->db->_error_number().' Delete correspoding Localities before attempting to delete City </h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-error" ><h6>Category not deleted! Please try again later.</h6></div>');       
        }
        redirect('admin/category');
    }
}

?>