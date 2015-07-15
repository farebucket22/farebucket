<?php
@session_start();
class Cabs extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $_SESSION['calling_controller_name'] = "cabs";
        if( isset($_SESSION['redir_data']) ){
            unset($_SESSION['redir_data']);
        }
    }

    function index(){
        $_SESSION['currentUrl'] = current_full_url();
        if(!isset($_SESSION['login_status'])){
            $_SESSION['login_status'] = 0;
        }
        $this->load->model('flight_model');
        $data = $this->flight_model->get_background_image('cabs');
        $this->load->view("common/header.php");
        $this->load->view('cabs/cabs_view',array('data' => $data));
        $this->load->view("common/footer.php");
    }

    function select_cabs(){
        $this->load->view("common/header.php");
        $this->load->view('cabs/cabs_select');
        $this->load->view("common/footer.php");
    }

    function travellers_details(){
        $data = array();
        $temp_arr = array();
        $data = $this->input->post(null, true);
        $_SESSION['save_cab_data'] = $data;

        $data['extra_info'] = json_decode(stripcslashes($data['extra_info']));
        $data['pax_info'] = json_decode(stripcslashes($data['pax_info']));
        $temp_arr = explode('-', $data['source']);
        $data['state_id'] = $temp_arr[0];
        $data['source'] = $temp_arr[1];
        $temp_arr = explode('-', $data['destination']);
        $data['city_id'] = $temp_arr[0];
        if(count($temp_arr)>1)
            $data['destination'] = $temp_arr[1];
        else
            $data['destination'] = $temp_arr[0];
        $this->load->view("common/header.php");
        $this->load->model('admin/convenience_model');
        $convenience = $this->convenience_model->get_convenience_charge('cabs');
        $data['convenience_charge'] = $convenience->convenience_charge;
        $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
        $this->load->view('cabs/cabs_travellers_detais', array('data' => $data));
        $this->load->view("common/footer.php");
    }

    function payment_gateway(){

        $fbBooking = 'FBCA';
        $this->load->model('cab_model');
        $returnId = $this->cab_model->getLastFbBookingId();
        if($returnId === 0){
            $randomNum = 1;
        }
        else{
            $splitReturnID = explode("TSCA",$returnId);
            $randomNum = $splitReturnID[1] + 1;
        }
    
        $randomNum = sprintf("%06d",$randomNum);
        $fbBookingId = $fbBooking . $randomNum;
        $_SESSION['cabBookingId'] = $fbBookingId;

        if( isset($_POST['extra_info']) ){
            $date = $_POST['pickupDate'];
            $timeH = $_POST['timeHours'];
            $timeM = $_POST['timeMins'];
            $cabRequiredOn = $date. " " . $timeH. ":" . $timeM;
            $_SESSION['cabs_outstation_post_data'] = $_POST;
            $_SESSION['discountCodeCab'] = $_POST['discountCode'];
            $_SESSION['discountValueCab'] = $_POST['discountValue'];

            //cabs agency balance check//

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
            $balance = $header['AvailableBalance_ByAccountIDResult'];

            $cab_fare = $_SESSION['cab_total_fare'];

            if($cab_fare > $balance){
                redirect('api/flights/transactionFail');
            }
            //cabs agency balance check end//

            $hash_val = $_SESSION['cabBookingId'];
                
            $params = array ('key' => $_POST["key"], 
                'txnid' =>  $hash_val, 
                'amount' => $_SESSION['cab_total_fare'],
                'firstname' => $_POST["first_name"], 
                'email' => $_POST["Email"], 
                'phone' => $_POST["Phone_num"],
                'productinfo' => $_POST["productinfo"],
                'cabRequiredOn' => $cabRequiredOn,
                'surl' => 'cab_api/cabs/general_booking',
                'furl' => 'api/flights/booking_failed'
            );
            
        }
        
        if( isset($_POST['mihpayid']) ){
            $_SESSION['cabPayId'] = $_POST['mihpayid'];
            $params = array (
                'surl' => 'cab_api/cabs/general_booking',
                'furl' => 'api/flights/booking_failed'
            );
        }

        require_once (APPPATH . 'lib/payu.php');
        if ( count( $_POST ) ) 
        pay_page( $params, 'hXS7CHnJ' );
    }
    
}
?>