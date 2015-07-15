<?php
class city extends CI_Controller{
    
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
        $this->load->model('admin/city_model');
        $this->load->model('admin/country_model');
        $cities = $this->city_model->getallcities();
        $countries = $this->country_model->getallcountries();
        
        //sort Categories by Name
       // usort($countries, array($this,'compare'));
        
        
        if($cities)
        {
            foreach($cities as $city)
            {
                foreach($countries as $cat)
                {
                    if($cat->activity_country_id == $city->activity_country_id)
                    {
                        $city->country_name = $cat->activity_country_name;
                    }
                }
            }
        }
        
        $data = array('cities' => $cities,'countries' => $countries);
        $this->load->view('admin/city/list',$data);
    }
    
    function checkcity()
    {
        $activity_city_name = $_POST['activity_city_name'];
        $cat_id = $_POST['country'];
        
        if(!$cat_id)
            return true;
        
        $this->load->model('admin/city_model');
            
        $cities = $this->city_model->checkcitybyname($activity_city_name);
        //print_r(is_numeric($activity_city_name));die;
        if(!(is_numeric($activity_city_name)))
        {
            if(!$cities)
            {
                return true;
            }
            else
            {
                $flag = FALSE;
                foreach($cities as $city)
                {
                    if($city->activity_country_id == $cat_id)
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
                    $this->form_validation->set_message('checkcity', 'City with selected Country already exists! Please try again with a different Name or Category.');
                    return false;
                }
            }
        }
        else
        {
             $this->form_validation->set_message('checkcity', 'City cant be numeric! Please give a valid city Name .');
             return false;
        }
    }
    
    function add()
    {
        $this->form_validation->set_rules('activity_city_name', 'city_name', 'required|min_length[4]|max_length[100]|callback_checkcity');
        $this->form_validation->set_rules('country', 'country', 'required');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $data['activity_city_name'] = ucfirst($this->input->post('activity_city_name'));
            $data['activity_country_id'] = $this->input->post('country');
            
            $this->load->model('admin/city_model');
            
            if($this->city_model->createcity($data))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>City successfully created</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>City not created! Please try again later.</h6></div>');

            redirect('admin/city');
        }
    }
    
    function edit()
    {
        $activity_city_id = $this->input->get('activity_city_id');
        
        $this->load->model('admin/city_model');
        $city = $this->city_model->getcitybyid($activity_city_id);
        
        $this->load->model('admin/country_model');
        $countries = $this->country_model->getallCountries();
        
        //sort Categories by Name
        //usort($countries, array($this,'compare'));
        
        
        $data = array('city' => $city, 'countries' => $countries);
        $this->load->view('admin/city/edit',$data);
    }
    
    function checkcityonupdate()
    {
        $activity_city_id = $_GET['activity_city_id'];
        $activity_city_name = $_POST['activity_city_name'];
        $cat_id = $_POST['country'];
        
        if(!$cat_id)
            return true;
        
        $this->load->model('admin/city_model');
            
        $cities = $this->city_model->getothercities($activity_city_name,$activity_city_id);
        
        
        if(!$cities)
        {
            return true;
        }
        else
        {
            $flag = FALSE;
            foreach($cities as $city)
            {
                if($city->activity_country_id == $cat_id)
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
                $this->form_validation->set_message('checkcityonupdate', 'City with selected Country already exists! Please try again with a different Name or Country.');
                return false;
            }
        }
    }
    
    function update()
    {
        $this->form_validation->set_rules('activity_city_name', 'Name', 'required|min_length[4]|max_length[100]|callback_checkcityonupdate');
        $this->form_validation->set_rules('country', 'Country', 'required');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->edit();
        }
        else
        {
            $activity_city_id = $this->input->get('activity_city_id');
            
            $data['activity_city_name'] = ucfirst($this->input->post('activity_city_name'));
            $data['activity_country_id'] = $this->input->post('country');
            
            $this->load->model('admin/city_model');
            
            if($this->city_model->updatecity($data,$activity_city_id))
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>City successfully updated</h6></div>');
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>City not udpated! Please try again later.</h6></div>');
            
            redirect('admin/city');
        }
    }
    
    function delete()
    {
        $activity_city_id = $_GET['activity_city_id'];
        //Loading model
        $this->load->model('admin/city_model');
        
        if($this->city_model->deleteById($activity_city_id))
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>City was successfully deleted</h6></div>');
        else
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>City not deleted! Please try again later.</h6></div>');
            redirect('admin/city');            
    }
}

?>
