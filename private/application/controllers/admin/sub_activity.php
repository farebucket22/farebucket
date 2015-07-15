<?php


class sub_activity extends CI_Controller{
    
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
        $this->load->model('admin/sub_activity_model');
        $this->load->model('admin/activity_model');
        $sub_activities = $this->sub_activity_model->getallsub_activities();
        $activities = $this->activity_model->getallactivities();
        
        
        
        
        if($sub_activities)
        {
            foreach($sub_activities as $sub_activity)
            {
                foreach($activities as $cat)
                {
                    if($cat->activity_id == $sub_activity->activity_id)
                    {
                        $sub_activity->activity_name = $cat->activity_name;
                    }
                }
            }
        }
        
        $data = array('sub_activities' => $sub_activities,'activities' => $activities);
        $this->load->view('admin/sub_activity/list',$data);
    }
    
   

      function details()
    {
        $this->load->model('admin/activity_model');
        $activities = $this->activity_model->getallactivities();
        $data = array('activities' => $activities);
        $this->load->view('admin/sub_activity/create',$data);
    }
    
    function add()
    {
        
        $this->form_validation->set_rules('activity_sub_type_name', 'SubActivity', 'required');
        $this->form_validation->set_rules('activity_id', 'Activity', 'required');
        $this->form_validation->set_rules('activity_sub_type_description', 'Sub_Activity_Description', 'required');
        $this->form_validation->set_rules('activity_sub_type_max_tickets', 'Max_Ticket', 'required|numeric');
        $this->form_validation->set_rules('activity_sub_type_adult_price', 'AdultFare', 'required|numeric');
        $this->form_validation->set_rules('activity_sub_type_adult_vendor_price', 'AdultVendorFare', 'required|numeric');
        $this->form_validation->set_rules('activity_sub_type_adult_tax', 'AdultTax', 'required|numeric');
        if($this->input->post('activity_sub_type_kid_status')==="On")
        {
        $this->form_validation->set_rules('activity_sub_type_kid_price', 'KidFare', 'required|numeric');
        $this->form_validation->set_rules('activity_sub_type_kid_vendor_price', 'KidVendorFare', 'required|numeric');
        $this->form_validation->set_rules('activity_sub_type_kid_tax', 'KidTax', 'required|numeric');
        }
       
        if($this->form_validation->run() == FALSE)
        {
            
            $this->details();
        }
        else
        {
            $data = $this->input->post(null,TRUE);
            $act_id = $data['activity_id'];
            $adult_price = $data['activity_sub_type_adult_price'];
            $this->load->model('admin/activity_model');
            $this->activity_model->add_onward_price($act_id,$adult_price);
            
            $this->load->model('admin/sub_activity_model');
             if($this->sub_activity_model->createsub_activity($data))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>SubActivity successfully created</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>SubActivity not created! Please try again later.</h6></div>');

            redirect('admin/sub_activity/');
        }
    }
    
 
    
    
       function edit()
    {
        $activity_sub_activity_id = $this->input->get('activity_sub_type_id');
        $this->load->model('admin/sub_activity_model');
        $this->load->model('admin/activity_model');
        $sub_activity = $this->sub_activity_model->getsub_activitybyid($activity_sub_activity_id);
      
        $activities = $this->activity_model->getallactivities();
        
        $data = array('activities' => $activities,'sub_activity' => $sub_activity,);
        $this->load->view('admin/sub_activity/edit',$data);
        
    }
    
    function update()
    {   
        //print_r($this->input->post(null,true));die;
        $this->form_validation->set_rules('activity_sub_type_name', 'SubActivity', 'required');
        $this->form_validation->set_rules('activity_id', 'Activity', 'required');
        $this->form_validation->set_rules('activity_sub_type_description', 'SubActivity_Description', 'required');
        $this->form_validation->set_rules('activity_sub_type_max_tickets', 'Max_Ticket', 'required|numeric');
        $this->form_validation->set_rules('activity_sub_type_adult_price', 'AdultFare', 'required|numeric');
        $this->form_validation->set_rules('activity_sub_type_adult_vendor_price', 'AdultVendorFare', 'required|numeric');
        $this->form_validation->set_rules('activity_sub_type_adult_tax', 'AdultTax', 'required|numeric');
        if($this->input->post('activity_sub_type_kid_status')==="on")
        {
        $this->form_validation->set_rules('activity_sub_type_kid_price', 'KidFare', 'required|numeric');
        $this->form_validation->set_rules('activity_sub_type_kid_vendor_price', 'KidVendorFare', 'required|numeric');
        $this->form_validation->set_rules('activity_sub_type_kid_tax', 'KidTax', 'required|numeric');
        $change = 0;
        }
        else
        {
            $change = 1;
        }
        if($this->form_validation->run() == FALSE)
        {
            
            $this->details();
        }
        else
        {
            $activity_sub_activity_id = $this->input->get('activity_sub_type_id');
            $data = $this->input->post(null,TRUE);
            if($change == 1)
                $data['activity_sub_type_kid_status'] = "off";
            $act_id = $data['activity_id'];
            $adult_price = $data['activity_sub_type_adult_price'];
            $this->load->model('admin/activity_model');
            $this->activity_model->add_onward_price($act_id,$adult_price);
            
            $this->load->model('admin/sub_activity_model');
             if($this->sub_activity_model->updatesub_activity($data,$activity_sub_activity_id))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>SubActivity updated successfully created</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>SubActivity not updated! Please try again later.</h6></div>');

            redirect('admin/sub_activity/');
        }
    }

    function delete()
    {
        $activity_sub_activity_id = $_GET['activity_sub_type_id'];
        //Loading model
        $this->load->model('admin/sub_activity_model');
        
        if($this->sub_activity_model->deleteById($activity_sub_activity_id))
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>sub_activity was successfully deleted</h6></div>');
        else
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>sub_activity not deleted! Please try again later.</h6></div>');
            redirect('admin/sub_activity');            
    }
}

?>
