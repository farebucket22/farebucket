<?php
class flight extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        
        if(!$this->session->userdata('admin_logged_in'))
            redirect('admin/login');
    }

     function index(){
        $this->load->model('admin/flight_model');
        $flights = $this->flight_model->getFlightBookings();
        $this->load->view('admin/flights/list', array('flights' => $flights));
    }

    function deduct(){
    	$this->load->view('admin/flights/service_charge');
    }

    function check_service_charge()
    {
    	$data['deduct_service_charge'] = $this->input->post('deduct_service_charge');
    	$this->load->model('admin/flight_model');
    	if($data['deduct_service_charge']!=="on")
    	{
    		$data['deduct_service_charge'] = "off";
    	}
    	$this->flight_model->setServiceChargeStatus($data);
    	redirect('admin/flight');

    }

    function list_background(){
        $this->load->model('admin/flight_model');
        $data = array();
        if( $this->flight_model->getBackgrounds() ){
            $data['backgrounds'] = $this->flight_model->getBackgrounds();
        }
        $this->load->view('admin/flights/admin_background_view', $data);
    }

    function add_background(){
        $this->load->view('admin/flights/admin_background_create');
    }

    function add_text(){
        $this->load->view('admin/flights/admin_background_create_text');   
    }

    function edit(){
        $id = $this->input->get('id');
        $this->load->view('admin/flights/edit_background',array('id' => $id));
    }

    function del_background(){
        $id = $this->input->get('del_id');
        $this->load->model('admin/flight_model');
        if( $this->flight_model->delBackground($id) ){
            $this->list_background();
        } else {
            $this->list_background();
        }
    }

    function add_new_background(){
        $this->form_validation->set_rules('image_url', 'Image', 'required|max_length[40]');
        $this->form_validation->set_rules('image_text', 'Image', 'required|max_length[40]');
        if($this->form_validation->run() == FALSE){
            $this->add_background();
        }else{
            $data = $this->input->post(null, true);
            $this->load->model('admin/flight_model');
            $dat = $this->flight_model->setBackground($data,$this->input->get('id'));
            if( $dat ){
                $this->list_background();
            }else{
                $this->list_background();
            }

        }

    }

}