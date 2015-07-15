<?php
session_start();
class User extends MY_Controller{
    public function __construct(){
        parent::__construct();
    }
    
    public function index() {
        $data = array(
            'user_details' => $_SESSION['user_details'],
            // 'tab_name' => $_SESSION['user_profile_tab_name'],
            //'err_msg' => $_SESSION['user_profile_err_msg']
        );
        $_SESSION['calling_controller_name'] = "User";
        $this->load->view('common/header.php');
        $this->load->view("user/home", $data);
        $this->load->view('common/footer.php');
    }
    
    public function update_profile() {
        $userId = $this->input->post('userIdField');
        $data = array(
            "user_title" => $this->input->post("userTitleField"),
            "user_first_name" => $this->input->post("userFirstNameField"),
            "user_last_name" => $this->input->post("userLastNameField"),
            "user_mobile" => $this->input->post("userMobileField"),
            "user_dob" => date("Y-m-d", strtotime($this->input->post("userDobField"))),
            "user_address" => $this->input->post("userAddressField")
        );
        
        $this->load->model('user_model');
        $res = $this->user_model->update_user_details($userId, $data);
        
        $userDetailsResult = $this->user_model->get_user_details($userId);
        $_SESSION['user_details'] = $userDetailsResult;
        $_SESSION['user_profile_tab_name'] = "profile";
        $_SESSION['user_profile_err_msg'] = "Updated Successfully.";
        
        redirect('user');
    }
    
    public function update_password(){
        $userId = $this->input->post('userIdField');
        $userOldPassword = $this->input->post('userOldPasswordField');
        $userNewPassword = $this->input->post('userNewPasswordField');
        
        $userDetails = $_SESSION['user_details'];
        
        if($userOldPassword === $userDetails[0]->user_password){
            $data = array(
                "user_password" => $userNewPassword
            );
            $this->load->model('user_model');
            $this->user_model->update_user_password($userId, $data);
            $userDetailsResult = $this->user_model->get_user_details($userId);
            $_SESSION['user_details'] = $userDetailsResult;
            $_SESSION['user_profile_tab_name'] = "profile";
            $_SESSION['user_profile_err_msg'] = "Updated Successfully.";
        } else {
            $_SESSION['user_profile_tab_name'] = "password";
            $_SESSION['user_profile_err_msg'] = "Old password entered incorrect.";
        }
        
        redirect('user');
    }
    
    public function get_activity_bookings(){
        $userId = $this->input->post('user_id');
        $this->load->model('activity_model');
        $activityBookingDetails = $this->activity_model->get_booking_details($userId);
        
        echo json_encode($activityBookingDetails);
    }

    public function get_ticket_bookings(){
        $userId = $this->input->post('user_id');
        $this->load->model('flight_model');
        $flightBookingDetails = $this->flight_model->getUserDetailsById($userId);

        foreach( $flightBookingDetails as $fbd ){
            $fbd->date = date('c', strtotime($fbd->date));
        }

        echo json_encode($flightBookingDetails);
    }

    public function get_bus_bookings(){
        $userId = $this->input->post('user_id');
        $this->load->model('bus_model');
        $this->load->model('login_model');
        $busBookingDetails = $this->bus_model->getBookingByUserId($userId); 
        $userDetails = $this->login_model->getUserById($userId);

        $retArr = array();
        $fare = array();
        $seats = array();
        $var = new StdClass;
        $i = 0;
        
        foreach( $busBookingDetails as $bbd ){
            $fare = array();
            $seats = array();
            $var = json_decode($bbd->all_details, false);
            $var->dateOfIssue = date('d-m-Y', strtotime($var->dateOfIssue));
            $var->doj = date('c', strtotime($var->doj));
            
            if( is_array($var->inventoryItems) ){
                foreach( $var->inventoryItems as $i ){
                    $fare[] = $i->fare;
                    $seats[] = $i->seatName;
                }
                $var->seatCSV = implode(',', $seats);
                $var->totalFare = array_sum( $fare );
            }else{
                $var->seatCSV = $var->inventoryItems->seatName;
                $var->totalFare = $var->inventoryItems->fare;
            }

            $var->userDetails = $userDetails;
            $var->fb_bookingId = $bbd->fb_bookingId;
            $var->db_status = $bbd->status;
            $retArr[] = $var;
        }

        echo json_encode($retArr);
    }

    public function get_cab_bookings(){
        $userId = $this->input->post('user_id');
        $this->load->model('cab_model');
        $this->load->model('login_model');
        $cabBookingDetails = $this->cab_model->getBookingByUserId($userId); 
        $userDetails = $this->login_model->getUserById($userId);
        foreach( $cabBookingDetails as $cbd ){
            $cbd->booking_date = date('c', strtotime($cbd->booking_date));
            $cbd->journey_date = date('c', strtotime($cbd->journey_date));
        }

        $retArr = array(
            'cabBookingDetails' => $cabBookingDetails,
            'userDetails' => $userDetails
        );
        echo json_encode($retArr);
    }

    public function get_hotel_bookings(){
        $userId = $this->input->post('user_id');

        $this->load->model('hotel_model');
        $this->load->model('login_model');
        $hotelBookingDetails = $this->hotel_model->getBookingByUserId($userId); 
        $userDetails = $this->login_model->getUserById($userId);

        foreach( $hotelBookingDetails as $hbd ){
            $hbd->check_in = date('c', strtotime($hbd->check_in));
            $hbd->check_out = date('c', strtotime($hbd->check_out));
        }

        $retArr = array(
            'hotelBookingDetails' => $hotelBookingDetails,
            'userDetails' => $userDetails
        );
        echo json_encode($retArr);
    }

    public function update_status(){
        $booking_id = $this->input->post('booking_id');
        $activity_id = $this->input->post('activity_id');
        $this->load->model('activity_model');
        $updated_status = $this->activity_model->update_actvity_status($booking_id);
        if($updated_status)
        {
            
            $vendor_name = $this->activity_model->get_vendor_name($activity_id);
            $this->load->model('admin/vendor_model');
            
            $vendor_email = $this->vendor_model->get_vendor_email($vendor_name);
            /*****mail to the activity vendor*****/
            $to = $vendor_email;
            $subject = 'Cancellation email';
            $message = 'Activity with id: '.$activity_id.' has been cancelled';
            $headers = "From: admin@farebucket.com";
            $mail_sent = @mail( $to, $subject, $message, $headers );
        
            $activity_status = array("act_stat" => $updated_status);
            $this->send_cancellation_email( $booking_id, $_SESSION['user_details'][0]->user_email );
            echo json_encode($activity_status); 
        }
        else
        {
            $activity_status = array("act_stat" => $updated_status);
            echo json_encode($activity_status);   
        }
    }
    
    public function send_cancellation_email($booking_id, $email){
        $to = $email;
        //define the subject of the email
        $subject = 'Cancellation email'; 
        //define the message to be sent. Each line should be separated with \n
        $message = "Your ticket with booking id ".$booking_id.", Has now been cancelled. The refund amount, if any, will be credited back to you in 7-8 working days. Please contact customer support at Phone num: +91-1234567890, Email ID: admin@farebucket.com for any further assistance.";
        //define the headers we want passed. Note that they are separated with \r\n
        $headers = "From: admin@farebucket.com";
        //send the email
        $mail_sent = @mail( $to, $subject, $message, $headers );
    }

    public function guest_details(){
        // converts POST to GET
        $url = '?';
        $i = 0;
        foreach ($_POST as $v_key => $v_val) {
            if( $i == 0 ){
                $url .= $v_key."=".$v_val;
            }else{
                $url .= "&".$v_key."=".$v_val;
            }
            $i++;
        }
        $farebucketId = $_POST['ticket_id'];
        $moduleCode = substr($farebucketId, 2, 2);
        switch( $moduleCode ){
            case 'FL':
                redirect('api/flights/guest_details'.$url);
                break;
            case 'HO':
                redirect('new_request/ticket_page'.$url);
                break;
            case 'BU':
                redirect('bus_api/buses/ticket_page'.$url);
                break;
            case 'CA':
                redirect('cab_api/cabs/getCabTicket'.$url);
                break;
            case 'AC':
                redirect('activity/getActivityTicket'.$url);
                break;
            default:
            break;
        }
    }
    
}