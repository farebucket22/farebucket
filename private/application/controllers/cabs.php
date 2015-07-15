<?php
@session_start();
class Cabs extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $_SESSION['calling_controller_name'] = "cabs";
        //$_SESSION['currentUrl'] = current_full_url();
        if( isset($_SESSION['redir_data']) ){
            unset($_SESSION['redir_data']);
        }
    }

    function index(){
        //$_SESSION['currentUrl'] = current_full_url();
        $this->load->model('flight_model');
        $data = $this->flight_model->get_background_image('cabs');
        $this->load->view("common/header.php");
    	$this->load->view('cabs/cabs_view',array('data' => $data));
        $this->load->view("common/footer.php");
    }

    function select_cabs(){
        //$_SESSION['currentUrl'] = current_full_url();
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
        $convenience = $this->convenience_model->get_convenience_charge();
        $data['convenience_charge'] = $convenience->convenience_charge;
        $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
        $this->load->view('cabs/cabs_travellers_detais', array('data' => $data));
        $this->load->view("common/footer.php");
    }

    function cab_book(){
        print_r($this->input->post(null, true));
    }

    function payment_gateway(){

        if( isset($_POST['extra_info']) ){
            $date = $_POST['pickupDate'];
            $timeH = $_POST['timeHours'];
            $timeM = $_POST['timeMins'];
            $cabRequiredOn = $date. " " . $timeH. ":" . $timeM;
            $_SESSION['cabs_outstation_post_data'] = $_POST;
            $characters = '0123456789';
            $hash_val = 'FBFL';
            for ($i = 0; $i < 9; $i++) {
                $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
            }
            $_SESSION['farebucket_txnid'] = $hash_val;
            	
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
        pay_page( $params, 'eCwWELxi' );
    }
    
}
?>