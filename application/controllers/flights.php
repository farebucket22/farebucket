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
        if(isset($_SESSION['multiTravelData'])){
            unset($_SESSION['multiTravelData']);
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
        $total_fare = $this->input->post('total_fare');
        $this->load->model('flight_model');
        $result = $this->flight_model->checkDiscountCode($discountCode, $discountModule);
        if(!empty($result)){
            $discount['code'] = $discountCode;
            $discount['type'] = $result[0]->discount_code_type;
            $discount['value'] = $result[0]->discount_code_value;
            if($discount['value'] === 'percent'){
                $discount['percent'] = $discount['value']/100;
				if( $discount['percent'] >= $total_fare ){
					$finalFare = $total_fare;
					$discount['message'] = "Discount cannot be applied for this amount.";
				}
				else{
					$finalFare = $total_fare-($total_fare*$discount['percent']);
					$discount['message'] = "";
				}
            }
            else{
				if( $discount['value'] >= $total_fare ){
					$finalFare = $total_fare;
					$discount['message'] = "Discount cannot be applied for this amount.";
				}
				else{
					$finalFare = $total_fare-$discount['value'];
					$discount['message'] = "";
				}
            }
            $discount['finalFare'] = $finalFare;
            if($discountModule === 'buses'){
                $_SESSION["bus_db_fare_params"]['bus_total_Fare'] = $finalFare;
            }
            else if($discountModule === 'cabs'){
                $_SESSION['cab_total_fare'] = $finalFare;
            }
            else if($discountModule === 'activities'){
                $_SESSION['activity_final_fare'] = $finalFare;
            }
            else if($discountModule === 'flights'){
                if(isset($_SESSION['onewayFlightTravellerData']['total_fare_field'])){
                    $_SESSION['onewayFlightTravellerData']['total_fare_field'] = $finalFare;
                }
                else if(isset($_SESSION['multiway_postdata'])){
                    $_SESSION['multiway_postdata']['total_fare_multi'] = $finalFare;
                }
                else{
                    $_SESSION['flight_db_fare_params']['flight_total_Fare'] = $finalFare;
                }
            }
            echo json_encode($discount);
        }
        else{
            echo "Failure";
        }
    }

    function payment_gateway(){
        
        if( isset($_POST['call_func']) && $_POST['call_func'] == 'flight_oneway'){
            $amount = $_POST['amount'];
            $_SESSION['calling_function'] = 'flight_oneway';

            $_SESSION['ticket_id'] = $_POST['ticket_id'];
            $hash_val = ( isset($_SESSION['fb_bookingId_flights']) ) ? $_SESSION['fb_bookingId_flights'] : "123";

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

            if( isset($_SESSION['flight_db_fare_params']["flight_total_Fare"]) ){
                $amount = $_SESSION['flight_db_fare_params']["flight_total_Fare"];
            }else{
                $amount = floatval(str_replace( ',', '', $_POST['amount']));
            }
            $_SESSION['calling_function'] = 'flight_return';

            $hash_val = ( isset($_SESSION['fb_bookingId_flights']) ) ? $_SESSION['fb_bookingId_flights'] : "123";
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
            $hash_val = ( isset($_SESSION['fb_bookingId_flights']) ) ? $_SESSION['fb_bookingId_flights'] : "123";
			
			if(isset($_SESSION['user_details'][0])){
                $firstname = $_SESSION['user_details'][0]->user_first_name;
                $email = $_SESSION['user_details'][0]->user_email;
                $phone = $_SESSION['user_details'][0]->user_mobile;
            }
            else{
				$firstname = $_SESSION['user_details']['user_first_name'];
				$email = $_SESSION['user_details']['user_email'];
				$phone = "123";
            }

            $params = array ('key' => $_SESSION['multiway_postdata']['key'], 
                            'txnid' =>  $hash_val, 
                            'amount' => $_SESSION['multiway_postdata']['total_fare_multi'],
                            'firstname' => $firstname,
                            'email' => $email,
                            'phone' => $phone,
                            'productinfo' => 'Multiway ticket', 
                            'surl' => 'api/flights/ticket_multiway',
                            'furl' => 'api/flights/booking_failed'
                        );

        }else{
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
            pay_page( $params, 'hXS7CHnJ' );
        }
    }

    function oneway()
    {
        $_SESSION['onewayGetStrings'] = $_GET;
        $_SESSION['onewayGetStrings']['total_count'] = $_SESSION['onewayGetStrings']['adult_count'] + $_SESSION['onewayGetStrings']['youth_count'] + $_SESSION['onewayGetStrings']['kids_count'];
        $_SESSION['currentUrlFlight'] = current_full_url();
        $this->load->view("common/header.php");
        $this->load->view('flights_view/display_flights');
        $this->load->view("common/footer.php");
    }

    function time_out(){
        $this->load->view("common/header.php");
        $this->load->view('flights/time_out.php');
        $this->load->view("common/footer.php");
    }

    function return_parameters()
    {
        $_SESSION['currentUrlFlight'] = current_full_url();
        $this->load->view("common/header.php");
        $this->load->view('flights_view/display_return_flights_new');
        $this->load->view("common/footer.php");
    }

}