<?php
@session_start();
class Buses extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $_SESSION['calling_controller_name'] = "buses";
        $_SESSION['currentUrl'] = current_full_url();
        if( isset($_SESSION['redir_data']) ){
        	unset($_SESSION['redir_data']);
        }
        if( isset($_SESSION['details']) ){
            unset($_SESSION['details']);
        }
    }

    public function index(){
        if(!isset($_SESSION['login_status'])){
            $_SESSION['login_status'] = 0;
        }
        unset($_SESSION['droppingpnts']);
        unset($_SESSION['boardingpnts']);
        unset($_SESSION['seats']);
        $_SESSION['currentUrlBus'] = current_full_url();
		$this->load->model('flight_model');
		$data = $this->flight_model->get_background_image('buses');
		$this->load->view("common/header.php");
		$this->load->view('buses/bus_view',array('data' => $data));
		$this->load->view("common/footer.php");
    }

    function payment_gateway(){

        $_SESSION['bus_get_data']['blockTicketResponse'] = $this->input->get('resp');

        $characters = '0123456789';

        $hash_val = $_SESSION['busBookingId'];

        (isset($_SESSION['user_details'][0]->user_first_name)) ? $firstname = $_SESSION['user_details'][0]->user_first_name : $firstname = 'abc';
        (isset($_SESSION['user_details'][0]->user_email)) ? $email = $_SESSION['user_details'][0]->user_email : $email = 'abc@abc.com';
        (isset($_SESSION['user_details'][0]->user_mobile)) ? $phone = $_SESSION['user_details'][0]->user_mobile : $phone = '1234567890';

        $_SESSION['farebucket_txnid'] = $hash_val;
        $params = array ('key' => $_SESSION['bus_get_data']['key'], 
                        'txnid' =>  $hash_val, 
                        'amount' => $_SESSION["bus_db_fare_params"]['bus_total_Fare'],
                        'firstname' => $firstname, 
                        'email' => $email, 
                        'phone' => $phone,
                        'productinfo' => $_SESSION['bus_get_data']['productinfo'], 
                        'surl' => 'bus_api/buses/store_data?resp='.$_SESSION['bus_get_data']['blockTicketResponse'],
                        'furl' => 'bus_api/buses/booking_failed'
                    );

        if( isset($_POST['mihpayid']) ){
            $_SESSION['busPayId'] = $_POST['mihpayid'];
            $params = array (
                'surl' => 'bus_api/buses/store_data?resp='.$_SESSION['bus_get_data']['blockTicketResponse'],
                'furl' => 'bus_api/buses/booking_failed'
            );
            
        }
        require_once (APPPATH . 'lib/payu.php');
        if ( count( $_GET ) || count( $_POST ) ) 
        pay_page( $params, 'hXS7CHnJ' );
    }
}
?>