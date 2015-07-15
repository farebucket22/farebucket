<?php  @session_start();
class Activity extends MY_Controller{
    public function __construct(){
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $_SESSION['calling_controller_name'] = "activity";
    }

    public function index() {
        if(isset($_SESSION['city_id']))
        {
            unset($_SESSION['city_id']);
            unset($_SESSION['country_id']);
            unset($_SESSION['booking_details']);
            unset( $_SESSION['booking_id']);
        }
        if(!isset($_SESSION['login_status'])){
            $_SESSION['login_status'] = 0;
        }
        $_SESSION['currentUrl'] = current_full_url();
        $this->load->model('flight_model');
        $data = $this->flight_model->get_background_image('activities');
        $this->load->model("activity_model");
        $countryList = $this->activity_model->get_activity_countries();
        $value = array(
            "country_data" => $countryList,
            "data" => $data
        );
        $this->load->view('common/header.php');
        $this->load->view("activities/home", $value);
        $this->load->view('common/footer.php');
    }
    
    public function get_cities(){
        $countryId = $this->input->post('country_id');
        $this->load->model("activity_model");
        $cityList = $this->activity_model->get_activity_cities($countryId);
        echo json_encode($cityList);
    }
    
    public function get_search_results(){
        $_SESSION['calling_controller_name'] = "activity";
        $_SESSION['currentUrl'] = current_full_url();
            /***** Add Country & City selection to Session *****/
            $cityIdName = explode("-", $this->input->get('citySelect'));
            $countryIdName = explode("-", $this->input->get('countrySelect'));
            $_SESSION['city_id'] = $cityIdName[0]; $_SESSION['country_id'] = $countryIdName[0]; $_SESSION['city_name'] = $cityIdName[1]; $_SESSION['country_name'] = $countryIdName[1];
        
        /***** Get Country & City list from db to populate in new view *****/
        $this->load->model("activity_model");        
        $countryList = $this->activity_model->get_activity_countries();
        $cityList = $this->activity_model->get_activity_cities(intval($_SESSION['country_id']));
        
        /***** Get search results for specific city selected *****/
        $cityId = intval($_SESSION['city_id']);
        $this->load->model("activity_model");
        $activityResults = $this->activity_model->get_activity_search_results($cityId);
        
        /***** Package up data to pass to the View *****/
        $data = array(
            "activity_results" => $activityResults,
            "country_data" => $countryList,
            "city_data" => $cityList
        );
        
        /***** Load View *****/
        $this->load->view('common/header.php');
        $this->load->view("activities/search_results", $data);
        $this->load->view('common/footer.php');
    }
    
    public function get_activity() {
        if(isset($_GET['country_id'])){
            $_SESSION['country_id'] = $_GET['country_id'];
            $_SESSION['city_id'] = $_GET['city_id'];
            unset($_GET['country_id']);
            unset($_GET['city_id']);
        }
        $_SESSION['calling_controller_name'] = "activity";
        $_SESSION['currentUrl'] = current_full_url();
        /***** Get activity id from the url and write to session *****/
        $activityId = intval($this->input->get('activity_id'));
        $_SESSION['activity_id'] = $activityId;        
        /***** Get Country & City list from db to populate in new view *****/
        $this->load->model("activity_model");        
        $countryList = $this->activity_model->get_activity_countries();
        $cityList = $this->activity_model->get_activity_cities(intval($_SESSION['country_id']));
        
        /***** Unset Booking Details that may have already been set in an earlier selection *****/
        if(isset($_SESSION['booking_details'])){
            unset($_SESSION['booking_details']);
        }
        
        /***** Get search results for specific activity selected *****/
        $this->load->model("activity_model");
        $activityDetails = $this->activity_model->get_activity_details($activityId);

        /***** Get Activity Sub-Type details for specific activity selected *****/
        $this->load->model('activity_model');
        $activitySubTypeDetails = $this->activity_model->get_activity_sub_type_details($activityId);
        
        /***** Get Activity Sub-Type details for specific activity selected *****/
        $this->load->model('activity_model');
        $activityLeaveDetails = $this->activity_model->get_leave_dates($activityId);
        
        /***** Package up data to pass to the View *****/
        $data = array(
            "activity_details" => $activityDetails[0],
            "activity_ratings_user_details" => $activityDetails[1],
            "activity_sub_type_details" => $activitySubTypeDetails,
            "activity_leave_details" => $activityLeaveDetails,
            "country_data" => $countryList,
            "city_data" => $cityList
        );
        
        /***** Load View *****/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->output->set_header('Pragma: no-cache');
        $this->load->view('common/header.php');
        $this->load->view("activities/activity_details", $data);
        $this->load->view('common/footer.php');
    }
    
    public function submit_review(){
        /***** Get the current activity id from session *****/
        $activity_id = $_SESSION['activity_id'];
        
        /***** Pass the review details to the model *****/
        $rating = intval($this->input->post('reviewRating'));
        $comment = $this->input->post('reviewComment');
        foreach ($_SESSION['user_details'] as $userDetails){
            $userId = $userDetails->user_id;
        }
        $this->load->model('activity_model');
        $this->activity_model->set_activity_user_review($rating, $comment, $activity_id, $userId);      
        
        redirect("activity/get_activity?activity_id=".$activity_id);
    }
    
    public function save_booking_details() {
        $_SESSION['calling_controller_name'] = "activity";
        /***** Get Country & City list from db to populate in new view *****/
        $this->load->model("activity_model");        
        $countryList = $this->activity_model->get_activity_countries();
        $cityList = $this->activity_model->get_activity_cities(intval($this->get_session_data('country_id')));
        if(!isset($_SESSION['booking_details'])){
            /***** Capture form data *****/
            $activitySubTypeId = $this->input->post('activitySubTypeSelect');
            $bookingDate = $this->input->post('bookingDate');
            $adultCount = $this->input->post('adultCountSelect');
            $childCount = $this->input->post('kidCountSelect');
            $bookingAmount = $this->input->post('bookingAmount');
            $activityName = $this->input->post('activityName');
            $activityLocationName = $this->input->post('activityLocationName');
            $activityAvgRating = $this->input->post('activityAvgRating');
            $vendorAdultBookingAmount = $this->input->post('adultVendorPrice');
            $vendorChildBookingAmount = $this->input->post('childVendorPrice');
            $totalVendorPrice = $vendorAdultBookingAmount + $vendorChildBookingAmount;
            /***** Set the SESSION data *****/
            $bookingDetails = array('activitySubTypeSelect'=>$activitySubTypeId, 'bookingDate'=>$bookingDate, 'adultCountSelect'=>$adultCount, 'kidCountSelect'=>$childCount, 'bookingAmount'=>$bookingAmount, 'activityName'=>$activityName, 'activityLocationName'=>$activityLocationName, 'activityAvgRating'=>$activityAvgRating);
            $_SESSION['booking_details'] = $bookingDetails;
            $_SESSION['activity_total_vendor_price'] = $totalVendorPrice;
        } else{
            $bookingDetails = $_SESSION['booking_details'];
            $activitySubTypeId = $bookingDetails['activitySubTypeSelect'];
            $bookingDate = $bookingDetails['bookingDate'];
            $adultCount = $bookingDetails['adultCountSelect'];
            $childCount = $bookingDetails['kidCountSelect'];
            $bookingAmount = $bookingDetails['bookingAmount'];
            $activityName = $bookingDetails['activityName'];
            $activityLocationName = $bookingDetails['activityLocationName'];
            $activityAvgRating = $bookingDetails['activityAvgRating'];
            $vendorAdultBookingAmount = $this->input->post('adultVendorPrice');
            $vendorChildBookingAmount = $this->input->post('childVendorPrice');
            $totalVendorPrice = $vendorAdultBookingAmount + $vendorChildBookingAmount;
        }
        
        /***** Package up data to pass to the View *****/
        $data = array(
            "activity_sub_type_id" => $activitySubTypeId,
            "booking_date" => $bookingDate,
            "adult_count" => $adultCount,
            "child_count" => $childCount,
            "booking_amount" => $bookingAmount,
            "totalVendorPrice" => $totalVendorPrice,
            "activity_name" => $activityName,
            "activity_location_name" => $activityLocationName,
            "activity_avg_rating" => $activityAvgRating,
            "country_data" => $countryList,
            "city_data" => $cityList
        );
        
        /***** Load View *****/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('admin/convenience_model');
        $convenience = $this->convenience_model->get_convenience_charge('activities');
        $data['convenience_charge'] = $convenience->convenience_charge;
        $_SESSION['activity_conv_charge'] = $data['convenience_charge'];
        $data['convenience_charge_msg'] = $convenience->convenience_charge_msg;
        $this->load->view("common/header.php");
        $this->load->view("activities/guest_details", $data);
        $this->load->view("common/footer.php");
    }

    public function get_activity_sub_type_booking(){
        /***** Get the Booking Date and Activity Sub-Type Id posted via Ajax *****/
        $bookingDate = $this->input->post("bookingDate");
        $activitySubTypeId = $this->input->post("activitySubTypeId");

        /***** Fetch Booking Details and evaluate availability for specific Booking Date and Activity Sub-Type Id *****/
        $this->load->model("activity_model");
        $bookingDate = strtotime($bookingDate);
        $activitySubTypeId = intval($activitySubTypeId);
        $bookingDetails = $this->activity_model->get_booking_availability(date("m/d/Y", $bookingDate), $activitySubTypeId);
        if(empty($bookingDetails)){
            $adultCount = 0;
            $childCount = 0;
        }else{
            foreach($bookingDetails as $bd) {
                $adultCount = $bd->adult_count;
                $childCount = $bd->child_count;
            }
        }
        echo($adultCount."-".$childCount);
    }
    
    public function create_activity_booking(){        
        $userDetails = array();
        $userDetails[0] = new stdClass();
        if($_SESSION['login_status']==1){
            $userDetails = $_SESSION['user_details'];
            if( isset($userDetails['user_gender']) && $userDetails['user_gender'] == 'guest'){
                $userId = $userDetails['user_id'];
            }else{
                $userId = $userDetails[0]->user_id;
            }
        } else{
            redirect('login/guest_login');
        }
        
        $activityId = $_SESSION['activity_id'];
        $activitySubTypeId = $this->input->post('activitySubTypeId');
        $bookingAmount = $this->input->post('bookingAmount');
        $finalAmount = $_SESSION['activity_final_fare'];
        $bookingDate = $this->input->post('bookingDate');
        $leadGuestTitle = $this->input->post('adultTitleSelect-0');
        $leadGuestFirstName = $this->input->post('adultFirstName-0');
        $leadGuestLastName = $this->input->post('adultLastName-0');
        $leadGuestEmail = $this->input->post('leadGuestEmail');
        $leadGuestMobile = $this->input->post('leadGuestMobile');        
        $adultCount = $this->input->post('adultCount');
        $childCount = $this->input->post('childCount');
        $childTitleString = "";
        $childFirstNameString = "";
        $childLastNameString = "";
        $childDobString = "";

        $_SESSION['activity_key'] = $this->input->post('key');
        $_SESSION['activity_total_fare'] = $finalAmount;
        
        for($i=0;$i<$adultCount;$i++){
            $adultTitleArr[] = $this->input->post('adultTitleSelect-'.$i);
            $adultFirstNameArr[] = $this->input->post('adultFirstName-'.$i);
            $adultLastNameArr[] = $this->input->post('adultLastName-'.$i);
        }
        
        for($i=0;$i<$childCount;$i++){
            $childTitleArr[] = $this->input->post('childTitleSelect-'.$i);
            $childFirstNameArr[] = $this->input->post('childFirstName-'.$i);
            $childLastNameArr[] = $this->input->post('childLastName-'.$i);
            $childDobArr[] = $this->input->post('childDob-'.$i);
        }
        
        $adultTitleString = implode(",", $adultTitleArr);
        $adultFirstNameString = implode(",", $adultFirstNameArr);
        $adultLastNameString = implode(",", $adultLastNameArr);
        
        if($childCount>0){
            $childTitleString = implode(",", $childTitleArr);
            $childFirstNameString = implode(",", $childFirstNameArr);
            $childLastNameString = implode(",", $childLastNameArr);
            $childDobString = implode(",", $childDobArr);        
        }

        $fbBooking = 'FBAC';
        $this->load->model('activity_model');
        $returnId = $this->activity_model->getLastFbBookingId();
        if($returnId === 0){
            $randomNum = 1;
        }
        else{
            $splitReturnID = explode("FBAC",$returnId);
            $randomNum = $splitReturnID[1] + 10;
        } 
        
        $randomNum = sprintf("%06d",$randomNum);
        $fbBookingId = $fbBooking . $randomNum;
        $_SESSION['activityBookingId'] = $fbBookingId;

        (isset($_SESSION['user_details'][0]->user_email)) ? $userEmail = $_SESSION['user_details'][0]->user_email : $userEmail = $leadGuestEmail;
        
        $bookingDetails = array(
            'activity_id' => $activityId,
            'activity_sub_type_id' => $activitySubTypeId,
            'user_id' => $userId,
            'user_email' => $userEmail,
            'lead_guest_title' => $leadGuestTitle,
            'lead_guest_first_name' => $leadGuestFirstName,
            'lead_guest_last_name' => $leadGuestLastName,
            'lead_guest_email' => $leadGuestEmail,
            'lead_guest_mobile' => $leadGuestMobile,
            'activity_booking_amount' => $bookingAmount,
            'activity_booking_date' => $bookingDate,
            'adult_title_string' => $adultTitleString,
            'adult_first_name_string' => $adultFirstNameString,
            'adult_last_name_string' => $adultLastNameString,
            'child_title_string' => $childTitleString,
            'child_first_name_string' => $childFirstNameString,
            'child_last_name_string' => $childLastNameString,
            'child_dob_string' => $childDobString,
            'adult_count' => $adultCount,
            'child_count' => $childCount,
            'totalVendorPrice' => $_SESSION['activity_total_vendor_price'],
            'convenience_charge' => $_SESSION['activity_conv_charge'],
            'fb_bookingId' => $fbBookingId
            );
        
        $this->load->model('activity_model');
        $bookingId = $this->activity_model->set_booking_details($bookingDetails);
        
        redirect('activity/payment_gateway');
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
    
    public function update_booking_status() {
        unset($_SESSION['currentUrl']);
        $paymentStatus = $this->input->get('paymentStatus');
        $bookingId = $_SESSION['activityBookingId'];
        $this->load->model('activity_model');
        $activityPayUid = $_SESSION['activityPayId'];
        
        if($paymentStatus == "Success"){
            $this->activity_model->update_booking_status($bookingId, "Active");
            $this->activity_model->updatePayuId($activityPayUid, $bookingId);
            $data = $this->activity_model->get_ticket_by_booking_id($bookingId);

            $act['booking_id'] = $bookingId;
            $act['activityBookingId'] = $_SESSION['activityBookingId'];
            $act['act_date'] = $data[0]->activity_booking_date;
            $act['act_name'] = $this->activity_model->get_activity_name_by_id($data[0]->activity_id);
            $act['sub_act_name'] = $this->activity_model->get_subactivity_name_by_id($data[0]->activity_sub_type_id);
            $act['adult_count'] = $data[0]->adult_count;
            $act['child_count'] = $data[0]->child_count;
            $act['status'] = $data[0]->booking_status;
            $vendor_name = $this->activity_model->get_vendor_name($data[0]->activity_id);
            $this->load->model('admin/vendor_model');
            
            $cust_support_data = cust_support_helper();
            
            $vendor_email = $this->vendor_model->get_vendor_email($vendor_name);
            /*****mail to the activity vendor*****/
            $to = $vendor_email;
            $subject = 'Ticket Details';
            $message = '';
            foreach( $act as $k_a => $k_v ){
                $message .= $k_a.' : '.$k_v.' ';
            }
            $headers = "From: admin@farebucket.com";
            $mail_sent = @mail( $to, $subject, $message, $headers );
            /*****mail to the user who booked*****/
            if( isset($_SESSION['user_details'][0]) ){
                $to = $_SESSION['user_details'][0]->user_email;
                $name = $_SESSION['user_details'][0]->user_first_name;
            }
            else{
                $to = $_SESSION['user_details']['user_email'];
                $name = $_SESSION['user_details']['user_first_name'];
            }
            $subject = 'Activity Ticket Link';
            $link = site_url('activity/generate_act_ticket?booking_id='.$act['activityBookingId']);
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Thank you for choosing Farebucket.</p><p>Your ticket has been booked for the date '. $act['act_date'] .' <p>Your Booking ID is: '.$act['booking_id'].'</p><p>Farebucket Booking ID: '.$_SESSION['activityBookingId'].'</p><p>Please find the link to your ticket(s) below.</p>Links: <a href="'.$link.'">Ticket</a> <hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>'.$cust_support_data->phone_number.'</strong> <br/>Email: <strong><a href="emailto:'.$cust_support_data->email.'">'.$cust_support_data->email.'</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Admin <info@farebucket.com>" . "\r\n";
            $headers .= "Cc: Support <support@farebucket.com>" . "\r\n";
            $mail_sent = @mail( $to, $subject, $message, $headers ); 
        } else if($paymentStatus == "Failure"){
            $this->activity_model->update_booking_status($bookingId, "Payment Failed"); 
        }
        
        $data = array(
            "act" => $act
        );
        
        $this->load->view("common/header");
        $this->load->view("activities/ticket", $data);
        $this->load->view("common/footer");
    }

    function getActivityTicket(){

        $query = array(
            'fb_bookingId' => $_GET['ticket_id'],
            'user_email' => $_GET['guest_email']
        );
        $this->load->model('activity_model');
        $ret = $this->activity_model->getActivityByUserDetails($query);

        if($ret == 'False'){
            $_SESSION['invalidGuestErrorMessage'] = "Please enter valid User ID & Booking ID.";
            redirect('tickets/guest_ticket');
        }
        
        $act['booking_id'] = $ret[0]->booking_id;
        $act['activityBookingId'] = $ret[0]->fb_bookingId;
        $act['act_date'] = $ret[0]->activity_booking_date;
        $act['act_name'] = $this->activity_model->get_activity_name_by_id($ret[0]->activity_id);
        $act['sub_act_name'] = $this->activity_model->get_subactivity_name_by_id($ret[0]->activity_sub_type_id);
        $act['adult_count'] = $ret[0]->adult_count;
        $act['child_count'] = $ret[0]->child_count;
        $act['status'] = $ret[0]->booking_status;

        $data = array(
            "act" => $act
        );
        $this->load->view("common/header");
        $this->load->view("activities/ticket", $data);
        $this->load->view("common/footer");
    }

    public function cancel_ticket($bookingId) {
        $this->load->model('activity_model');
        $data = $this->activity_model->get_ticket_by_booking_id($bookingId);
        $act['booking_id'] = $bookingId;
        $act['act_date'] = $data[0]->activity_booking_date;
        $act['act_name'] = $this->activity_model->get_activity_name_by_id($data[0]->activity_id);
        $act['sub_act_name'] = $this->activity_model->get_subactivity_name_by_id($data[0]->activity_sub_type_id);
        $act['adult_count'] = $data[0]->adult_count;
        $act['child_count'] = $data[0]->child_count;
        $act['status'] = $data[0]->booking_status;
        $data = array(
            "act" => $act
        );

        $vendor_name = $this->activity_model->get_vendor_name($data[0]->activity_id);
        $this->load->model('admin/vendor_model');
        
        $vendor_email = $this->vendor_model->get_vendor_email($vendor_name);
        /*****mail to the activity vendor*****/
        $to = $vendor_email;
        $subject = 'Cancellation email';
        $message = '';
        foreach( $act as $k_a => $k_v ){
            $message .= $k_a.' : '.$k_v.' ';
        }
        $headers = "From: admin@farebucket.com";
        $mail_sent = @mail( $to, $subject, $message, $headers );

        $cust_support_data = cust_support_helper();

        /*****mail to the user who booked*****/
        $to = $_SESSION['user_details'][0]->user_email;
        $subject = 'Cancellation email'; 
        //define the message to be sent. Each line should be separated with \n
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Thank you for choosing Farebucket.</p><p>Your ticket has been booked for the date '. $act['act_date'] .' <p>Your Booking ID is: '.$act['booking_id'].'</p><p>Farebucket Booking ID: '.$_SESSION['activityBookingId'].'</p><p>Please find the link to your ticket(s) below.</p>Links: <a href="'.$link.'">Ticket</a> <hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>'.$cust_support_data->phone_number.'</strong> <br/>Email: <strong><a href="emailto:'.$cust_support_data->email.'">'.$cust_support_data->email.'</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
        //define the headers we want passed. Note that they are separated with \r\n
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Admin <admin@farebucket.com>" . "\r\n";
        $mail_sent = @mail( $to, $subject, $message, $headers ); 
        
        $this->load->view("common/header");
        $this->load->view("activities/ticket", $data);
        $this->load->view("common/footer");
    }
    
    public function apply_discount_code() {
        $discountCode = $this->input->post('discountCode');
        $discountModule = $this->input->post('discountModule');
        $this->load->model('activity_model');
        $result = $this->activity_model->check_discount_code($discountCode, $discountModule);
        if(!empty($result)){
            echo json_encode($result[0]);
        } else{
            echo json_encode("Failure");
        }
    }
    
    public function calculate_discount_amount() {
        $discountCodeValue = $this->input->post('discountCodeValue');
        $discountCodeType = $this->input->post('discountCodeType');
        $activityAmount = $this->input->post('activityAmount');
        $discountAmount = 0;
        $finalPrice = 0;
        
        if($discountCodeType === "Percent"){
            $discountAmount = (intval($discountCodeValue)/100)*$activityAmount;
            $finalPrice = $activityAmount - $discountAmount;
        } else if($discountCodeType === "Amount"){
            $discountAmount = intval($discountCodeValue);
            $finalPrice = $activityAmount - $discountAmount;
        }
        
        echo $discountAmount."-".$finalPrice;
    }

    public function generate_act_ticket() {
        $this->load->model('activity_model');
        $booking_id = $this->input->get('booking_id');
        $booking_details = $this->activity_model->get_ticket_by_booking_id($booking_id);
        $booking_details[0]->activity_name = $this->activity_model->get_activity_name_by_id($booking_details[0]->activity_id);
        $booking_details[0]->activity_phone_num = $this->activity_model->get_activity_phone_num_by_id($booking_details[0]->activity_id);
        $booking_details[0]->activity_address = $this->activity_model->get_activity_address_by_id($booking_details[0]->activity_id);
        $booking_details[0]->sub_activity_name = $this->activity_model->get_subactivity_name_by_id($booking_details[0]->activity_sub_type_id);
        $booking_details[0]->activity_details = $this->activity_model->get_activity_details_by_id($booking_details[0]->activity_id);
        $this->load->view('activities/invoice_page', array(
        'data' => $booking_details[0]
        ));
    }

    public function guest_cancel_ticket() {
        $this->load->model('activity_model');
        $booking_id = $this->input->get('booking_id');
        $cancel_result = $this->activity_model->update_actvity_status($booking_id);
        $this->cancel_ticket($booking_id);
    }
}
?>