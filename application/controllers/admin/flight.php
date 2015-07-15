<?php
require_once (APPPATH . 'controllers/scaffolding/flightsAPIAuth.php');
require_once (APPPATH . 'controllers/scaffolding/flightsSOAP.php');
require_once (APPPATH . 'controllers/scaffolding/flightsSearchRequest.php');
require_once (APPPATH . 'controllers/scaffolding/flightsAPISearchResponseHandler.php');

class flight extends MY_Controller{
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
        $this->load->model('admin/flight_model');
        $flights = $this->flight_model->getFlightBookings();
        $this->load->view('admin/header');
        $this->load->view('admin/flights/list', array('flights' => $flights));
    }

    function deduct(){
        $this->load->view('admin/header');        
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
        $this->load->view('admin/header');        
        $this->load->view('admin/flights/admin_background_view', $data);
    }

    function add_background(){
        $this->load->view('admin/header');
        $this->load->view('admin/flights/admin_background_create');
    }

    function add_text(){
        $this->load->view('admin/header');        
        $this->load->view('admin/flights/admin_background_create_text');   
    }

    function edit(){
        $id = $this->input->get('id');
        $this->load->view('admin/header');        
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
        $this->form_validation->set_rules('image_url', 'Image', 'required|max_length[255]');
        $this->form_validation->set_rules('image_text', 'Image', 'required|max_length[255]');
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
    function balance(){
        /**
        * Object initialisation
        */
        $flightsAPIAuthObj = new FlightsAPIAuth;
        $flightsSearchRequestObj = new FlightsSearchRequest;
        $flightsSOAPObj = new FlightsSOAP;
        $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;

        /**
        * Set Request Authentication Data array
        * UserName
        * Password
        */
        $flightsAPIAuthObj->setUserId("PNYR196");
        $flightsAPIAuthObj->setPassword("travel/090");
        $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

        $flightsSOAPObj->setSOAPUrl("http://airapi.travelboutiqueonline.com/tboapi_v7/service.asmx?wsdl");
        $flightsSOAPObj->setSOAPClient();
        $flightsSOAPObj->setSOAPHeader($authDataArr);

        $requestArr['GetAgencyBalance']['isAirlineLcc'] = 1;

        $result = $flightsSOAPObj->makeSOAPCall("GetAgencyBalance", $requestArr);
        $balance = $result->GetAgencyBalanceResult->Balance;

        /*******cabs balance*************/

        require_once (APPPATH . 'lib/nusoap.php');
        $wsdl = "http://wheelz.wheelzindia.com/Service.asmx?wsdl";
        $client_header = new SoapHeader('http://wheelz.wheelzindia.com/AvailableBalance_ByAccountID', 'AuthenticationData',false);
        $client = new SoapClient($wsdl);
        $client->__setSoapHeaders(array($client_header));
        $status_check = array();
        $status_check['AvailableBalance_ByAccountID']['AccountId'] = 53;
        $status_check['AvailableBalance_ByAccountID']['UserName'] = "255872";
        $status_check['AvailableBalance_ByAccountID']['Password'] = "278552";
        $header = array();
        $header = (array)$client->__call('AvailableBalance_ByAccountID', $status_check); 
        $cab_balance = $header['AvailableBalance_ByAccountIDResult'];

        $this->load->view('admin/header');        
        $this->load->view('admin/flights/agency_balance',array('balance'=>$balance, 'cab_balance'=>$cab_balance));

    }

}