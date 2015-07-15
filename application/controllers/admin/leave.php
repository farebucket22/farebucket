<?php      class leave extends CI_Controller{
    
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
        $this->load->model('admin/leave_model');
        $this->load->model('admin/activity_model');
        $leaves = $this->leave_model->getallleaves();
        $activities = $this->activity_model->getallactivities();
        
        if($leaves)
        {
            foreach($leaves as $leave)
            {
                foreach($activities as $cat)
                {
                    if($cat->activity_id == $leave->activity_id)
                    {
                        $leave->activity_name = $cat->activity_name;
                    }
                }
            }
        }
        
        $data = array('leaves' => $leaves,'activities' => $activities);
        $this->load->view('admin/header');        
        $this->load->view('admin/leave/list',$data);
    }
    
    function checkleave()
    {   
        $activity_leave_name = $_POST['activity_leave_name'];
        
        if(!isset($cat_id))
            return true;
        
        $this->load->model('admin/leave_model');
            
        $leaves = $this->leave_model->checkleavebyname($activity_leave_name);
        
        
        if(!$leaves)
        {
            return true;
        }
        else
        {
            $flag = FALSE;
            foreach($leaves as $leave)
            {
                if($leave->activity_id == $cat_id)
                {
                    $flag = TRUE;
                    break;
                }
            }
            if(!$flag)
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('checkleave', 'Leave with selected Activity already exists! Please try again with a different Name or Activity.');
                return false;
            }
        }
    }
    
    function add()
    {
        $this->form_validation->set_rules('activity_leave_name', 'leave_name', 'required|max_length[100]|callback_checkleave');
        $this->form_validation->set_rules('activity_leave_dates', 'leave_dates', 'callback_checkdates');
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $data['activity_leave_name'] = $this->input->post('activity_leave_name');
            $data['activity_id'] = $this->input->post('activity');	
            $data['activity_leave_dates'] = $this->input->post('activity_leave_dates');
            if( $this->input->post('activity_leave_days') != null ){
                $data['activity_leave_days'] = implode(',', $this->input->post('activity_leave_days'));
            }else{
                $data['activity_leave_days'] = -1;
            }

            $this->load->model('admin/leave_model');
            if($this->leave_model->createleave($data))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Leave successfully created</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Leave not created! Please try again later.</h6></div>');

            redirect('admin/leave');
        }
    }
    
    function edit()
    {
        $activity_leave_id = $this->input->get('activity_leave_id');
        
        $this->load->model('admin/leave_model');
        $leave = $this->leave_model->getleavebyid($activity_leave_id);
        
        $this->load->model('admin/activity_model');
        $activities = $this->activity_model->getallactivities();
        
        $data = array('leave' => $leave, 'activities' => $activities);
        $this->load->view('admin/header');        
        $this->load->view('admin/leave/edit',$data);
    }
    
    function checkleaveonupdate()
    {
        $activity_leave_id = $_GET['activity_leave_id'];
        $activity_leave_name = $_POST['activity_leave_name'];
        $cat_id = $_POST['activity'];
        
        if(!$cat_id)
            return true;
        
        $this->load->model('admin/leave_model');
            
        $leaves = $this->leave_model->getotherleaves($activity_leave_name,$activity_leave_id);
        
        
        if(!$leaves)
        {
            return true;
        }
        else
        {
            $flag = FALSE;
            foreach($leaves as $leave)
            {
                if($leave->activity_id == $cat_id)
                {
                    $flag = TRUE;
                    break;
                }
            }
            if(!$flag)
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('checkleaveonupdate', 'Leave with selected Activity already exists! Please try again with a different Name or Category.');
                return false;
            }
        }
    }
    
    function checkdates()
    {
        $activity_leave_dates = $_POST['activity_leave_dates'];
		if( $activity_leave_dates != null ){
			$leaves = explode(",",$activity_leave_dates);
			$flag = 1;
		}
		else{
			return true;
		}
       foreach($leaves as $l)
       {
           if(!is_numeric($l))
           {
               $flag = 0;
           }
           if( $l <0 || $l>31 )
           {
               $flag = -1;
           }
       }
       if($flag ==0)
       {
       $this->form_validation->set_message('checkdates', 'Enter proper leave dates with comma seperated values.');
       return false; 
       }
       else if($flag == -1)
       {
       $this->form_validation->set_message('checkdates', 'Enter dates between 0 and  31.');
       return false;     
       }
       else
       {
           return true;
       }
        
    }
    
    function update()
    {
        $this->form_validation->set_rules('activity_leave_name', 'Name', 'required|max_length[100]|callback_checkleaveonupdate');
        $this->form_validation->set_rules('activity', 'Country', 'required');
        $this->form_validation->set_rules('activity_leave_dates', 'leave_dates', 'callback_checkdates');
        if($this->form_validation->run() == FALSE)
        {
            $this->edit();
        }
        else
        {
            $activity_leave_id = $this->input->get('activity_leave_id');
            
            $data['activity_leave_name'] = $this->input->post('activity_leave_name');
            $data['activity_id'] = $this->input->post('activity');
			$data['activity_leave_dates'] = $this->input->post('activity_leave_dates');
            if( $this->input->post('activity_leave_days') != null ){
                $data['activity_leave_days'] = implode(',', $this->input->post('activity_leave_days'));
            }else{
                $data['activity_leave_days'] = -1;
            }
            
            $this->load->model('admin/leave_model');
            
            if($this->leave_model->updateleave($data,$activity_leave_id))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Leave successfully updated</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Leave not udpated! Please try again later.</h6></div>');
            
            redirect('admin/leave');
        }
    }
    
    function delete()
    {
        $activity_leave_id = $_GET['activity_leave_id'];
        //Loading model
        $this->load->model('admin/leave_model');
        
        if($this->leave_model->deleteById($activity_leave_id))
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Leave was successfully deleted</h6></div>');
        else
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Leave not deleted! Please try again later.</h6></div>');
            redirect('admin/leave');            
    }
}

?>