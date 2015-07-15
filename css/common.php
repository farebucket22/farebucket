<?php
session_start();
class common extends MY_Controller{
    public function __construct(){
        parent::__construct();
    }

    public function terms(){
    	$data = 'footer_link';
		$this->load->view("common/header.php");
		$this->load->view('common/termsncond', array('data' => $data));
		$this->load->view("common/footer.php");
    }

    public function privacy(){
    	$data = 'footer_link';
		$this->load->view("common/header.php");
		$this->load->view('common/privacy', array('data' => $data));
		$this->load->view("common/footer.php");
    }

    public function contact(){
        $data = 'footer_link';
        $this->load->view("common/header.php");
        $this->load->view('common/contact', array('data' => $data));
        $this->load->view("common/footer.php");
    }

    public function about(){
        $data = 'footer_link';
        $this->load->view("common/header.php");
        $this->load->view('common/about', array('data' => $data));
        $this->load->view("common/footer.php");
    }

    public function send_mail_contact(){
        $data = $this->input->post('data');

        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lc6mwQTAAAAACHB9B19bAKZc-CE7Mg_k3a_4DIz&response=".$data['query']."&remoteip=".$_SERVER['REMOTE_ADDR']);
        if($response."success" == false){
            echo json_encode(false);
        }else{
            $to = 'admin@farebucket.com';
            $subject = 'Farebucket Contact Us mail.';
            $message = $data['query'];
            $headers = "From: ".$data['email']."";
            $mail_sent = @mail( $to, $subject, $message, $headers );

            if( $mail_sent ){
                echo json_encode(true);
            }else{
                echo json_encode(false);
            }
        }

    }

    public function change_travel_mode(){

        $overview_data = array();
        $overview_data['data'] = new stdClass();
        if( $this->input->get(null, true) ){
            $redir = $this->input->get(null, true);
            if( $redir['travel_mode'] == 'bus' ){
                $_SESSION['redir_data'] = $redir;
                redirect('bus_api/buses/getAvailableTrips?source_city_name='.$redir['source_city_name'].'&source_id='.$redir['source_id'].'&destination_city_name='.$redir['destination_city_name'].'&destination_id='.$redir['destination_id'].'&journey_date='.$redir['journey_date'].'&flight_type=multi&travel_mode=bus&get_req_active=1&flight_num='.$redir['flight_num']);
            }
            if( $redir['travel_mode'] == 'cab' ){
                $_SESSION['redir_data'] = $redir;
                redirect('cab_api/cabs/flights_to_cabs?source='.$redir['source'].'&destination='.$redir['destination'].'&adult_count='.$redir['adult_count'].'&youth_count='.$redir['youth_count'].'&total_count='.$redir['total_count'].'&journey_date='.$redir['journey_date'].'&flight_type=multi&travel_mode=cab&get_req_active=1&flight_num='.$redir['flight_num']);
            }
            if( $redir['travel_mode'] == 'flight' ){
                $_SESSION['redir_data'] = $redir;
                $j = $_SESSION['cnt_val'] - 1;
                redirect('api/flights/set_multi_url?city_from=' . $_SESSION['details']['from'][$j] . '&city_to=' . $_SESSION['details']['to'][$j] . '&adult_count=' . $_SESSION['details']['adult_count'] . '&youth_count=' . $_SESSION['details']['youth_count'] . '&kids_count=' . $_SESSION['details']['kids_count'] . '&multi_date=' . $_SESSION['details']['dates'][$j] . '&total_count=' . $_SESSION['details']['total_count'] . '&flight_num=' . $_SESSION['cnt_val'] . '&airline_preference_3=' . $_SESSION['airline_preference'] . '&class_of_travel_3=' . $_SESSION['class_travel']);
            }
        }else{
            // for buses the data after seat selection should be sent as a get not post as shown here.
            $data = $this->input->post(null, true);
            $baseFareDiffSeats = 0;
            $taxFareDiffSeats = 0;
            $totFareDiffSeats = 0;

            if( $data['travel_mode'] == 'bus' ){
                $data_store = array();

                if( is_array($_SESSION['journey_details']['boardingpnts']['fareDetails']) ){
                    $f = 0;
                    foreach( $data['chkchk'] as $fd ){
                        foreach( $_SESSION['seat_details']['seats'] as $s ){
                            if( $s['name'] == $fd ){
                                // it is assumed that fare is the total fare here.
                                $baseFareDiffSeats += $s['baseFare'];
                                $taxFareDiffSeats += $s['serviceTaxAbsolute'];
                                $totFareDiffSeats += $s['fare'];
                            }
                        }
                    }
                }else{
                    $baseFareDiffSeats = $_SESSION['journey_details']['boardingpnts']['fareDetails']['baseFare'];
                    $taxFareDiffSeats = $_SESSION['journey_details']['boardingpnts']['fareDetails']['serviceTaxAbsolute'];
                    $totFareDiffSeats = $_SESSION['journey_details']['boardingpnts']['fareDetails']['totalFare'];
                }

                $fareDet = array(
                    'baseFare' => $baseFareDiffSeats,
                    'serviceTaxAbsolute' => $taxFareDiffSeats,
                    'totalFare' => $totFareDiffSeats
                );

                $overview_data['data']->mode = 'bus';
                $overview_data['data']->org = $_SESSION['redir_data']['source_city_name'];
                $overview_data['data']->dest = $_SESSION['redir_data']['destination_city_name'];
                $overview_data['data']->doj = $_SESSION['journey_details']['boardingpnts']['doj'];
                $overview_data['data']->arrivalTime = $_SESSION['journey_details']['boardingpnts']['arrivalTime'];
                $overview_data['data']->departureTime = $_SESSION['journey_details']['boardingpnts']['departureTime'];
                $overview_data['data']->fareDet = $fareDet;
                $overview_data['data']->travels = $_SESSION['journey_details']['boardingpnts']['travels'];
                $overview_data['data']->busType = $_SESSION['journey_details']['boardingpnts']['busType'];
                $data_store['ov'] = $overview_data['data'];
                $data_store['info'] = $data;
                $cnt_val = $_SESSION['cnt_val'];
                if ($cnt_val < $_SESSION['details']['flights']) {
                    $cnt_val = $cnt_val + 1;
                    $_SESSION['cnt_val'] = $cnt_val;
                    $_SESSION['flight_data'][$cnt_val - 2] = $data_store;
                    redirect('api/flights/test_multi');
                }else {
                    $_SESSION['currentUrlFlight'] = current_full_url();
                    $_SESSION['calling_controller_name'] = "flights";
                    $_SESSION['flight_data'][$cnt_val - 1] = $data_store;
                    redirect('api/flights/traveller_multi_details');
                }
            }
            if( $data['travel_mode'] == 'cab' ){
                
                $data = $_POST;
                $data['extra_info'] = json_decode($data['extra_info']);
                $cabs = array(
                    'compacts' => $data['compacts'],
                    'sedans' => $data['sedans'],
                    'suvs' => $data['suvs']
                );
                $overview_data['data']->mode = 'cab';
                $overview_data['data']->org = $data['extra_info']->source;
                $overview_data['data']->dest = $data['extra_info']->destination;
                $overview_data['data']->doj = date('c', strtotime($data['to_date']));
                $overview_data['data']->totalFare = $data['total_fare'];
                $overview_data['data']->fareDet = $data['extra_info']->RequiredCarFare;
                $overview_data['data']->cabs = $cabs;
                $overview_data['data']->paxInfo = $data['pax_info'];
                $add['ov'] = $overview_data['data'];
                $add['extra_info'] = $data['extra_info'];
                $cnt_val = $_SESSION['cnt_val'];
                if ($cnt_val < $_SESSION['details']['flights']) {
                    $cnt_val = $cnt_val + 1;
                    $_SESSION['cnt_val'] = $cnt_val;
                    $_SESSION['flight_data'][$cnt_val - 2] = $add;
                    redirect('api/flights/test_multi');
                }else {
                    $_SESSION['currentUrlFlight'] = current_full_url();
                    $_SESSION['calling_controller_name'] = "flights";
                    $_SESSION['flight_data'][$cnt_val - 1] = $add;
                    redirect('api/flights/traveller_multi_details');
                }
            }

            //after checking the calling controller return back to it
        }
    }

    public function book_assorted($input = null){

        if( $this->input->get(null, true) ){
            $input = $this->input->get('input');
        }else{
            $input = 0;
        }

        for( $i = $input ; $i < count($_SESSION['flight_data']) ; $i++ ){
            if( $_SESSION['flight_data'][$i]['ov']->mode == 'bus' ){

                if( $this->input->post(null, true) ){
                    $data = $this->input->post(null, true);
                    $_SESSION['current_post_data'] = $data;
                }else{
                    $data = $_SESSION['current_post_data'];
                }
                
                $str = '';
                $v = 0;
                $d_len = count($data);
                foreach ($data as $d_key => $d_val) {
                    if( $v == 0 ){
                        $str .= $d_key.'='.$d_val;
                    }else{
                        $str .= '&'.$d_key.'='.$d_val;
                    }
                    if( $v == $d_len - 1){
                        $str .= '&sending_type=assorted';
                    }
                    $v++;
                }
                $_SESSION['info'] = $_SESSION['flight_data'][$index]['info'];
                redirect('bus_api/buses/blockTicket?'.$str);

            }else if( $_SESSION['flight_data'][$i]['ov']->mode == 'cab' ){

                if( $this->input->post(null, true) ){
                    $data = $this->input->post(null, true);
                    $_SESSION['current_post_data'] = $data;
                }else{
                    $data = $_SESSION['current_post_data'];
                }

                $str = '';
                $v = 0;
                $d_len = count($data);
                foreach ($data as $d_key => $d_val) {
                    if( $v == 0 ){
                        $str .= $d_key.'='.$d_val;
                    }else{
                        $str .= '&'.$d_key.'='.$d_val;
                    }
                    if( $v == $d_len - 1){
                        $str .= '&sending_type=assorted&index='.$i;
                    }
                    $v++;
                }
                redirect('cab_api/cabs/general_booking?'.$str);

            }else if( $_SESSION['flight_data'][$i]['ov']->mode == 'flight' ){
                
                if( $this->input->post(null, true) ){
                    $data = $this->input->post(null, true);
                    $_SESSION['current_post_data'] = $data;
                }else{
                    $data = $_SESSION['current_post_data'];
                }
                $str = '';
                $v = 0;
                $d_len = count($data);
                foreach ($data as $d_key => $d_val) {
                    if( $v == 0 ){
                        $str .= $d_key.'='.$d_val;
                    }else{
                        $str .= '&'.$d_key.'='.$d_val;
                    }
                    if( $v == $d_len - 1){
                        $str .= '&sending_type=assorted&index='.$i;
                    }
                    $v++;
                }
                redirect('api/flights/book_assorted?'.$str);
            }else{
                print_r('An Error occured, please try again.');die;
            }
        }
        if( $input > count($_SESSION['flight_data']) - 1 ){
            redirect('common/payment_gateway');
        }
    }

    public function store_block_id(){
        $responses = array();
        $calling = $this->input->get('call_func');
        if( $calling == 'bus' ){
            $resp = $this->input->get('resp');
            $_SESSION['responses'][] = $resp;
            redirect('common/perform_action_bus');
        }else if( $calling == 'cab' ){
            $resp = $this->input->get(null, true);
            $r_len = count($resp) - 1;
            for( $k = 0 ; $k < $r_len ; $k++ ){
                $responses[] = $this->input->get($k);
            }
            $_SESSION['responses'][] = $responses;
            $input = count($_SESSION['responses']);
            redirect('common/book_assorted?input='.$input);
        }
        else if( $calling == 'flight' ){
            $_SESSION['responses'][] = $_SESSION['retId'];
            $input = count($_SESSION['responses']);
            redirect('common/book_assorted?input='.$input);
        }
    }

    function payment_gateway(){

        $characters = '0123456789';
        $hash_val = 'FBFL';
        for ($i = 0; $i < 9; $i++) {
            $hash_val.= $characters[rand(0, strlen($characters) - 1) ];
        }

        (isset($_SESSION['user_details'][0]->user_first_name)) ? $firstname = $_SESSION['user_details'][0]->user_first_name : $firstname = 'abc';
        (isset($_SESSION['user_details'][0]->user_email)) ? $email = $_SESSION['user_details'][0]->user_email : $email = 'abc@abc.com';
        (isset($_SESSION['user_details'][0]->user_mobile)) ? $phone = $_SESSION['user_details'][0]->user_mobile : $phone = '1234567890';

        $_SESSION['farebucket_txnid'] = $hash_val;
        $params = array ('key' => $_SESSION['current_post_data']['key'], 
                        'txnid' =>  $hash_val, 
                        'amount' => $_SESSION['current_post_data']['multiplex_total_fare'],
                        'firstname' => $firstname, 
                        'email' => $email, 
                        'phone' => $phone,
                        'productinfo' => $_SESSION['current_post_data']['productinfo'], 
                        'surl' => 'common/ticket_page',
                        'furl' => 'api/flights/booking_failed'
                    );
        require_once (APPPATH . 'lib/payu.php');
        if ( count( $_POST ) || count($_SESSION['current_post_data']) ) 
        pay_page( $params, 'eCwWELxi' );
    }

    public function ticket_page(){
        $flight_present_flag = 0;
        $str = '';
        for( $i = 0, $k = 0 ; $i < count($_SESSION['flight_data']) ; $i++ ){
            if( $_SESSION['flight_data'][$i]['ov']->mode == 'flight' ){
                $flight_present_flag = 1;
                $str .= '&flight_number'.$k."=".$_SESSION['responses'][$i][0];
                $k++;
            }
        }
        if( $flight_present_flag ){
            redirect('api/flights/ticket_assorted?flight_present=true'.$str);
        }
    }

    public function multiplex_tickets(){
        $this->load->view("common/header.php");
        $this->load->view('common/multiplexed_tickets');
        $this->load->view("common/footer.php");
    }

	function payment_gateway(){
        (isset($_SESSION['user_details'][0]->user_first_name)) ? $firstname = $_SESSION['user_details'][0]->user_first_name : $firstname = 'abc';
        (isset($_SESSION['user_details'][0]->user_email)) ? $email = $_SESSION['user_details'][0]->user_email : $email = 'abc@abc.com';
        (isset($_SESSION['user_details'][0]->user_mobile)) ? $phone = $_SESSION['user_details'][0]->user_mobile : $phone = '1234567890';

        $_SESSION['farebucket_txnid'] = $_SESSION['activityBookingId'];
        $params = array ('key' => $_SESSION['activity_key'], 
                        'txnid' =>  $_SESSION['activityBookingId'], 
                        'amount' => $_SESSION["activity_total_fare"],
                        'firstname' => $firstname, 
                        'email' => $email, 
                        'phone' => $phone,
                        'productinfo' => "Activities", 
                        'surl' => 'activity/update_booking_status?paymentStatus=Success',
                        'furl' => 'api/flights/booking_failed'
                    );
        if( isset($_POST['mihpayid']) ){
            $_SESSION['activityPayId'] = $_POST['mihpayid'];
            $params = array (
                'surl' => 'activity/update_booking_status?paymentStatus=Success',
                'furl' => 'api/flights/booking_failed'
            );
        }
        require_once (APPPATH . 'lib/payu.php');
        if ( count( $_POST ) || count( $_SESSION ) ) 
        pay_page( $params, 'hXS7CHnJ' );
    }

}