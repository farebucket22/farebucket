<?php
@session_start();
class flights extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $_SESSION['calling_controller_name'] = "flights";
    }

    function index(){
        unset($_SESSION['sess_id']);
        unset($_SESSION['cnt_val']);
        if(!isset($_SESSION['login_status'])){
            $_SESSION['login_status'] = 0;
        }
        if( isset($_SESSION['flight_data']) ){
            unset($_SESSION['responses']);
            unset($_SESSION['ticket_details']);
            unset($_SESSION['retId']);
            unset($_SESSION['flight_data']);
            unset($_SESSION['details']);
            $_SESSION['cnt_val'] = 0;
        }
        $_SESSION['currentUrlFlight'] = current_full_url();
        $this->load->model('flight_model');
        $data = $this->flight_model->get_background_image('flights');
        $this->load->view("common/header.php");
        $this->load->view('flights/flights_view',array('data' => $data));
        $this->load->view("common/footer.php");
    }

    function multiway_flight_save(){
        // $tempArray = [];
        // $popoverData = [];
        $navData = [];
        $data = $this->input->post(null, true);
        $cnt = $this->native_session->get('cnt_val');

        $data['isdestroy'] = 1;

        if ( $this->native_session->get('cnt_val') == 1 ) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $hash_val = '';
            for ($i = 0; $i < 10; $i++)
            {
                $hash_val .= $characters[rand(0, strlen($characters) - 1)];
            }
            $hash_val = md5($hash_val);
            $this->native_session->set('hash_val', $hash_val);
        }

        $data['hash_val'] = $this->native_session->get('hash_val');

        $this->load->model('flight_model');
        if( $this->flight_model->saveMultiwayDetails($data) ){

            if( $this->native_session->get('navData') ){
                $oldNavData = $this->native_session->get('navData');
            } else{
                $oldNavData = [];
            }

            $navData['airline_name_field'] = $data['airline_name_field'];
            $navData['from_field'] = $data['from_field'];
            $navData['to_field'] = $data['to_field'];
            $navData['flight_duration_field'] = $data['flight_duration_field'];
            $navData['total_fare_field'] = $data['total_fare_field'];
            $navData['travel_date'] = $data['travel_date'];
            $navData['city_name_from'] = $data['city_name_from'];
            $navData['city_name_to'] = $data['city_name_to'];     

            array_push($oldNavData, $navData);
            $this->native_session->set('navData', $oldNavData);

            $count = $this->native_session->get('cnt_val');
            $flights = $this->native_session->get('count_flights');
            if( $count < $flights + 1 ) {
                $count++;
                $this->native_session->set('cnt_val', $count);
                $temp_arr = explode( '?', $this->native_session->get('user_URL') );
                redirect('api/flights/multi_parameters?'.$temp_arr[1]);
            } else {
                redirect('api/flights/traveller_multi_details');
            }
        }
    }

    function multiway_flight_edit(){
        print_r( $this->session->all_userdata() );die;
    }

    function load_pop(){
        $this->load->view('common/header.php');
        $this->load->view('flights/pop_view.php');
        $this->load->view('common/footer.php');
    }

    public function apply_discount_code(){
        $discountCode = $this->input->post('discountCode');
        $discountModule = $this->input->post('discountModule');
        $this->load->model('flight_model');
        $result = $this->flight_model->checkDiscountCode($discountCode, $discountModule);
        if(!empty($result)){
            echo json_encode($result[0]);
        } else{
            echo "Failure";
        }
    }

    function payment_gateway(){
        if( isset($_POST['call_func']) && $_POST['call_func'] == 'flight_oneway'){
            $amount = $_POST['amount'];
            $_SESSION['calling_function'] = 'flight_oneway';

            //change this later
            $characters = '0123456789';
            $hash_val = 'FBFL';
            for ($i = 0; $i < 6; $i++) {
                $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
            }
            $_SESSION['farebucket_txnid'] = $hash_val;
            $_SESSION['ticket_id'] = $_POST['ticket_id'];

            $params = array ('key' => $_POST["key"], 
                            'txnid' =>  $hash_val, 
                            'amount' => $amount,
                            'firstname' => $_POST["firstname"], 
                            'email' => $_POST["email"], 
                            'phone' => $_POST["phone"],
                            'productinfo' => $_POST["productinfo"], 
                            'surl' => 'api/flights/ticket',
                            'furl' => 'api/flights/booking_failed'
                        ); 
        }else if( isset($_POST['call_func']) && $_POST['call_func'] == 'flight_return' ){
            $amount = floatval(str_replace( ',', '', $_POST['amount']));
            $_SESSION['calling_function'] = 'flight_return';
            $characters = '0123456789';
            $hash_val = 'FBFL';
            for ($i = 0; $i < 9; $i++) {
                $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
            }
            $_SESSION['farebucket_txnid'] = $hash_val;
            $_SESSION['outbound'] = $_POST['out_id'];
            $_SESSION['inbound'] = $_POST['in_id'];

            $params = array ('key' => $_POST["key"], 
                            'txnid' =>  $hash_val, 
                            'amount' => $amount,
                            'firstname' => $_POST["firstname"], 
                            'email' => $_POST["email"], 
                            'phone' => $_POST["phone"],
                            'productinfo' => $_POST["productinfo"], 
                            'surl' => 'api/flights/ticket_return',
                            'furl' => 'api/flights/booking_failed'
                        );
        }else if( isset($_SESSION['multiway_postdata']) ){
            $_SESSION['calling_function'] = 'flight_multiway';

            $characters = '0123456789';
            $hash_val = 'FBFL';
            for ($i = 0; $i < 6; $i++) {
                $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
            }
            $_SESSION['farebucket_txnid'] = $hash_val;

            $params = array ('key' => $_SESSION['multiway_postdata']['key'], 
                            'txnid' =>  $hash_val, 
                            'amount' => $_SESSION['multiway_postdata']['total_fare_multi'],
                            'firstname' => $_SESSION['user_details'][0]->user_first_name,
                            'email' => $_SESSION['user_details'][0]->user_email,
                            'phone' => $_SESSION['user_details'][0]->user_mobile,
                            'productinfo' => 'Multiway ticket', 
                            'surl' => 'api/flights/ticket_multiway',
                            'furl' => 'api/flights/booking_failed'
                        );

        }else{
            //this is not redirest in return flights
            //findout a way to redirect after payment return.
            // this could be done with session variables
            if( isset($_SESSION['calling_function']) && $_SESSION['calling_function'] == 'flight_oneway' ){
                $_SESSION['payu_id'] = $_POST['mihpayid'];
                $params = array (
                    'surl' => 'api/flights/ticket',
                    'furl' => 'api/flights/booking_failed'
                );
            }else if( isset($_SESSION['calling_function']) && $_SESSION['calling_function'] == 'flight_return' ){
                $_SESSION['payu_id'] = $_POST['mihpayid'];
                $params = array (
                    'surl' => 'api/flights/ticket_return',
                    'furl' => 'api/flights/booking_failed'
                );
            }else if( isset($_SESSION['multiway_postdata']) ){
                $_SESSION['payu_id'] = $_POST['mihpayid'];
                $params = array (
                    'surl' => 'api/flights/ticket_multiway',
                    'furl' => 'api/flights/booking_failed'
                );
            }else{
                print_r('An Error Occured, please try again.');die;
            }

        }
        
        require_once (APPPATH . 'lib/payu.php');
        if ( count( $_POST ) || isset($_SESSION['multiway_postdata']) ){
            pay_page( $params, '3sf0jURk' );
        }
    }

    function oneway()
    {
        $_SESSION['onewayGetStrings'] = $_GET;
        $_SESSION['currentUrlFlight'] = current_full_url();
        $this->load->view("common/header.php");
        $this->load->view('flights_view/display_flights_new');
        $this->load->view("common/footer.php");
    }

    function return_parameters()
    {
        $_SESSION['currentUrlFlight'] = current_full_url();
        $this->load->view("common/header.php");
        $this->load->view('flights_view/display_return_flights_new');
        $this->load->view("common/footer.php");
    }
    // function discount_percentage(){
    //     $data = $this->input->post(null, true);
    //     $total_raw = $data['original_total'];
    //     $discount = $data['discount_details']['discount_code_value']/100;
    //     $total_new = $total_raw - ($total_raw*$discount);
    //     $total = number_format($total_new,2);
    //     $_SESSION['total_fare'] = $total;
    //     echo json_encode($total);
    // }
}