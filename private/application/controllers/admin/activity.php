<?php
class activity extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        
        if(!$this->session->userdata('admin_logged_in'))
            redirect('admin/login');
    }

    function list_activity_booking(){
        $this->load->model('admin/activity_model');
        $activities = $this->activity_model->getActivityBookings();
        $this->load->view('admin/activity/list_bookings', array('activities' => $activities));
    }
    
    function index(){
        $this->load->model('admin/activity_model');;
        $activities = $this->activity_model->getallactivities();
        $data = array('activities' => $activities);
        $this->load->view('admin/activity/list', $data);
    }
    
    function checkactivity(){
        $name = $_POST['activity_name'];
        
        $this->load->model('admin/activity_model');
        $activity = $this->activity_model->getactivitybyname($name);
        if(!$activity)
        {
            return true;
        }
        else
        {
            //if edit and not create
            if(isset($_GET['activity_id']))
            {
                if($_GET['activity_id'] == $activity->activity_id)
                    return true;
            }
            $this->form_validation->set_message('checkactivity', 'Activity already exists! Please try again with a different name.');
            return false;
        }
    }
    
    function rating()
    {
        $rating_value = $_POST['activity_rating_average_value'];
        if($rating_value > 0 && $rating_value<=5)
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('rating', 'Average rating value should be greater than 0 but less than 6');
            return false; 
        }
    }
    
    function details()
    {
        $this->load->model('admin/city_model');
        $this->load->model('admin/country_model');
        $this->load->model('admin/category_model');
        $this->load->model('admin/leave_model');
        $this->load->model('admin/vendor_model');
        $cities = $this->city_model->getallcities();
        $countries = $this->country_model->getallcountries();
        $categories = $this->category_model->getallcategories();
        $leaves = $this->leave_model->getallleaves();
        $vendors = $this->vendor_model->getallvendors();
        $data = array('cities' => $cities,'countries' => $countries,'categories' => $categories,'leaves' => $leaves,'vendors' => $vendors);
        $this->load->view('admin/activity/create',$data);
    }
    

    
    function add()
    {
        
        $this->form_validation->set_rules('activity_country', 'Country', 'required');
        $this->form_validation->set_rules('activity_category_id', 'Category', 'required');
        $this->form_validation->set_rules('activity_city', 'City', 'required');
        $this->form_validation->set_rules('activity_name', 'Activity', 'required|callback_checkactivity');
        $this->form_validation->set_rules('activity_description_short', 'ShortDescription', 'required');
        $this->form_validation->set_rules('activity_description', 'Description', 'required');
        $this->form_validation->set_rules('activity_details', 'Details', 'required');
        $this->form_validation->set_rules('activity_leave_id', 'Leave', 'required');
        $this->form_validation->set_rules('activity_location_lat', 'Latitde', 'required|numeric');
        $this->form_validation->set_rules('activity_location_long', 'Latitde', 'required|numeric');
        $this->form_validation->set_rules('activity_rating_average_value', 'RateValue', 'required');
        $this->form_validation->set_rules('activity_vendor_name', 'Vendor Name', 'required');
        if($this->form_validation->run() == FALSE)
        {
            $this->details();
        }
        else
        {
            $data = $this->input->post(null,TRUE);
             
            
            $this->load->model('admin/activity_model');
             if($this->activity_model->createactivity($data))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Activity successfully created</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Activity not created! Please try again later.</h6></div>');

            redirect('admin/activity/');
        }
    }
    
    function edit()
    {
        $activity_id = $this->input->get('activity_id');
        $this->load->model('admin/city_model');
        $this->load->model('admin/country_model');
        $this->load->model('admin/category_model');
        $this->load->model('admin/leave_model');
        $this->load->model('admin/activity_model');
        $this->load->model('admin/vendor_model');
        $activity = $this->activity_model->getactivitybyid($activity_id);
        $cities = $this->city_model->getallcities();
        $countries = $this->country_model->getallcountries();
        $categories = $this->category_model->getallcategories();
        $leaves = $this->leave_model->getallleaves();
        $vendors = $this->vendor_model->getallvendors();
        $data = array('cities' => $cities,'countries' => $countries,'categories' => $categories,'leaves' => $leaves,'activity' => $activity,'vendors' => $vendors);
        $this->load->view('admin/activity/edit',$data);
        
    }
    
    function update()
    {
        $this->form_validation->set_rules('activity_country', 'Country', 'required');
        $this->form_validation->set_rules('activity_category_id', 'Category', 'required');
        $this->form_validation->set_rules('activity_city', 'City', 'required');
        $this->form_validation->set_rules('activity_name', 'Activity', 'callback_checkactivity');
        $this->form_validation->set_rules('activity_description_short', 'ShortDescription', 'required');
        $this->form_validation->set_rules('activity_description', 'Description', 'required');
        $this->form_validation->set_rules('activity_details', 'Details', 'required');
        $this->form_validation->set_rules('activity_leave_id', 'Leave', 'required');
        $this->form_validation->set_rules('activity_location_lat', 'Latitde', 'required|numeric');
        $this->form_validation->set_rules('activity_location_long', 'Latitde', 'required|numeric');
        $this->form_validation->set_rules('activity_rating_average_value', 'RateValue', 'required|numeric|callback_rating');
        $this->form_validation->set_rules('activity_vendor_name', 'Vendor Name', 'required');
        if($this->form_validation->run() == FALSE)
        { 
            $this->details();
        }
        else
        {
            $activity_id = $this->input->get('activity_id');
            $data = $this->input->post(null,TRUE);
            
            $this->load->model('admin/activity_model');
             if($this->activity_model->updateactivity($data,$activity_id))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Activity updated successfully created</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Activity not updated! Please try again later.</h6></div>');

            redirect('admin/activity/');
        }
    }
    
    function delete()
    {
        $activity_id = $_GET['activity_id'];
        
        $this->load->model('admin/activity_model');
        
        if($this->activity_model->deletebyid($activity_id))
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Activity was successfully deleted</h6></div>');
        else
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Activity not deleted! Please try again later.</h6></div>');
            redirect('admin/activity'); 
    }

    function update_booking_status(){
        $booking_id = $this->input->get('booking_id');
        $this->load->model('activity_model');
        $updated_status = $this->activity_model->update_actvity_status($booking_id);
        
        if($updated_status)
        {
            $activity_status = array("act_stat" => $updated_status);
            redirect('admin/activity/list_activity_booking?activity_status='.$updated_status); 
        }
        else
        {
            $activity_status = array("act_stat" => $updated_status);
            redirect('admin/activity/list_activity_booking?activity_status='.$updated_status);   
        }
    }
    
}

?>
