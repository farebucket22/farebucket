<?php

class login extends CI_Controller
{
  
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        
    }
    
    public function index()
    {
      
        $this->load->view('admin/login_page');
        
    }
     public function checklogin()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|max_length[255]');
        $this->form_validation->set_rules('pwd', 'Password', 'required|max_length[50]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $email = $this->input->post('email');
            $pwd = $this->input->post('pwd');

            $this->load->model('admin/admin_model');

            $user = $this->admin_model->checkuser($email);
            if($user && ($user->password_hash == $pwd))
            {
                //Kill Previous Sessions
                $this->session->sess_destroy();

                //Create new Session
                $this->session->sess_create();

                $user_data = array('admin_name' => $user->user_name,
                                   'admin_user_id' => $user->id,
                                   'admin_email' => $user->email,
                                   'admin_logged_in' => true
                                  );
  
                //Add User data to session
                $this->session->set_userdata($user_data);

                redirect('admin/category');
            }
            else
            {    
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger danger_cus" id="invalid" ><h6>Invalid Email or Password</h6></div>');
                redirect('admin/login');
            }
        }
     }
    
}
?>