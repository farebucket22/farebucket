<?php
@session_start();
class Login extends MY_Controller{
    public function __construct(){
        parent::__construct();
    }
    
    public function index() {
        if($_SESSION['login_status'] == 0){
            $data = array(
                "message" => ""
            );
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
            $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
            $this->output->set_header('Pragma: no-cache');
            $this->load->view("common/header.php");
            $this->load->view("login/home", $data);
            $this->load->view("common/footer.php");
        } else if($_SESSION['login_status']==1){
            $controller_name = $_SESSION['currentUrl'];
            redirect($controller_name);
        }
    }

    public function guest_login() {
        $this->load->view("common/header.php");
        $this->load->view("login/guest_login");
        $this->load->view("common/footer.php");
    }

    public function guest_register()
    {
        $_SESSION['calling_view_name'] = "login";
        $firstName = "guest";
        $lastName = "guest";
        $dob = '1970-1-1';
        $dob = date('Y-m-d', strtotime($dob));
        $email = $this->input->post('guest_email');
        $password = "none";
        $phone_no = "0";
        $gender = "guest";
        $title = "guest";
        $type = 2;

        //always send guest
        $checkLoginResult = 1;

        if($checkLoginResult){
            $this->load->model('login_model');
            $userId = $this->login_model->register_user($firstName, $lastName, $dob, $email, $password, $phone_no, $gender, $title, $type);
            $guestDetails = array(
                'user_id' => $userId,
                'user_first_name' => $firstName,
                'user_last_name' => $lastName,
                'user_dob' => $dob,
                'user_email' => $email,
                'user_password' => $password,
                'user_mobile' => $phone_no,
                'user_gender' => $gender,
                'user_title' => $title,
                'user_type' => $type,
                'user_address' => '',
            );
            $_SESSION['login_status'] = 1;
            $_SESSION['user_details'] = $guestDetails;
            $_SESSION['user_profile_tab_name'] = "profile";
            if( isset($_SESSION['calling_controller_name']) ){
                $controller_name = $_SESSION['calling_controller_name'];
            }else{
                $controller_name = "flights";
            }
            if($controller_name === "activity/get_activity"){
                $activityId = intval($_SESSION['activity_id']);
                $queryString = "?activity_id=".$activityId;
            } else{
                $queryString="";
            }

            echo json_encode(true);
        }else{
            echo json_encode(false);
        }
    }

    public function login_user_modal()
    {
        $retData = $this->input->post(null,true);
        $_SESSION['calling_view_name'] = "login";
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        
        $this->load->model("login_model");
        $checkLoginResult = $this->login_model->check_login($email, $password);
        if(!empty($checkLoginResult)){
            $_SESSION['login_status'] = 1;
            $_SESSION['user_details'] = $checkLoginResult;
            $_SESSION['user_profile_tab_name'] = "profile";
            $_SESSION['user_profile_err_msg'] = "";
            $resData['status'] = "success";
            $resData['first_name'] = "Hi ".$checkLoginResult[0]->user_first_name;
            echo json_encode($resData);
        } else{
            $resData['status'] = "failure";
            echo json_encode($resData);
        }
    }
    
    public function login_user() {
        $_SESSION['calling_view_name'] = "login";
        $email = $this->input->post('userLoginEmail');
        $password = $this->input->post('userLoginPassword');
        
        if( isset($_SESSION['user_details']) ){
            unset($_SESSION['user_details']);
        }
        $this->load->model("login_model");
        $checkLoginResult = $this->login_model->check_login($email, $password);

        if(!empty($checkLoginResult)){
            //session_start();
            $_SESSION['login_status'] = 1;
            $_SESSION['user_details'] = $checkLoginResult;
            $_SESSION['user_profile_tab_name'] = "profile";
            if(isset($_SESSION['currentUrl'])){
                $current = $_SESSION['currentUrl'];
            }
            else
                $current = "";
				
            if(isset($_SESSION['currentUrlFlight']))    
                $currentflight = $_SESSION['currentUrlFlight'];
            else
                $currentflight = "";

            if( (isset($_SESSION['calling_controller_name'])) && ($_SESSION['calling_controller_name'] == "flights") || ((isset($_SESSION['ovMode'])) && ($_SESSION['ovMode']->mode == 'flight')) ) {
                redirect($currentflight);
            }
            else{
                    if( ((isset($_SESSION['currentUrlBus'])) && ((isset($_SESSION['calling_controller_name'])) && ($_SESSION['calling_controller_name'] == "buses")))  || ((isset($_SESSION['ovMode'])) && ($_SESSION['ovMode']->mode == 'bus')) ){
                        redirect($_SESSION['currentUrlBus']);
                    }
                    else if ( (isset($_SESSION['currentUrlCabs']) ) || ((isset($_SESSION['ovMode'])) && ($_SESSION['ovMode']->mode == 'cab')) ){
                        redirect($_SESSION['currentUrlCabs']);
                    }
					else if( isset($_SESSION['currentUrlHotel']) && (isset($_SESSION['calling_controller_name']) && ( $_SESSION['calling_controller_name'] === "hotels" ) ) ){
                        redirect($_SESSION['currentUrlHotel']);
                    }
					else if( isset($_SESSION['calling_controller_name']) && (isset($_SESSION['currentUrl']) && (($_SESSION['calling_controller_name'] === "activity") ) )){
						redirect($_SESSION['currentUrl']);
					}
                    else{
                        redirect($current);  
                    }
                        
            }
            $_SESSION['login_status'] = 1;
                
        } else{
            $message = "Email Id or Password incorrect. Please try again.";
            $data = array(
                "message" => $message
            );
            $this->load->view("common/header.php");
            $this->load->view("login/home", $data);
            $this->load->view("common/footer.php");
        }
    }
    
    public function logout_user(){
	
		if( isset($_SESSION['calling_controller_name']) ){
			$controllerUrl = $_SESSION['calling_controller_name'];
		}
        else{
			$controllerUrl = 'flights';
		}
		if(isset($_SESSION['currentUrlHotel'])){
			$current = $_SESSION['currentUrlHotel'];
		}
		if( isset($_SESSION['currentUrl']) )
			$currentUrl = $_SESSION['currentUrl'];

        if(isset($_SESSION['calling_controller_name']) && ($_SESSION['calling_controller_name'] == 'flights') && (isset($_SESSION['currentUrlFlight']))){           
            unset($_SESSION);   		
            session_destroy();
			$_SESSION['login_status'] = 0;	
			redirect('flights');
        }
        else if(isset($_SESSION['calling_controller_name']) && ($_SESSION['calling_controller_name'] == 'buses')) {
            session_destroy();
			unset($_SESSION);    
			$_SESSION['login_status'] = 0;            
            redirect('buses');
        }
        else if(isset($_SESSION['calling_controller_name']) && ($_SESSION['calling_controller_name'] == 'cabs')) {           
			unset($_SESSION);    
			session_destroy();
			$_SESSION['login_status'] = 0;
            redirect('cabs');
        }
		else if(isset($_SESSION['calling_controller_name']) && ($_SESSION['calling_controller_name'] == 'hotels') && isset($_SESSION['currentUrlHotel'])) {    
			unset($_SESSION);    
            session_destroy();
			$_SESSION['login_status'] = 0;
            redirect('hotels');
        }
        else if(isset($_SESSION['currentUrl']) && $_SESSION['calling_controller_name'] == 'activity' ){
            $_SESSION['login_status'] = 0;
			redirect('activity');
        }
        else{
            unset($_SESSION);    
            session_destroy();
			$_SESSION['login_status'] = 0;
            redirect($controllerUrl);
        }     
    }

    public function register_user(){
        $_SESSION['calling_view_name'] = "login";
        $firstName = $this->input->post('firstName');
        $lastName = $this->input->post('lastName');
        $dob = $this->input->post('dob');
        $dob = date('Y-m-d', strtotime($dob));
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $phone_no = $this->input->post('phone_no');
        $gender = $this->input->post('gender');
        $title = $this->input->post('title_user');
        $type = 0;
        if( isset($_POST['isAjax']) && $_POST['isAjax'] == 1 ){
            $isAjax = $_POST['isAjax'];
        }else{
            $isAjax = 0;
        }
        $this->load->model("login_model");

        if($this->login_model->check_new_user($email, $type)){
            $message = "An Account with this E-Mail ID already exists. Please register with another E-Mail ID.";
            $data = array(
                "message" => $message
            );
            if( $isAjax ){
                echo json_encode($data);
            }else{
                $this->load->view("common/header.php");
                $this->load->view("login/home", $data);
                $this->load->view("common/footer.php");
            }

        }else{
            $userId = $this->login_model->register_user($firstName, $lastName, $dob, $email, $password, $phone_no, $gender, $title, $type);

            $checkLoginResult = $this->login_model->check_login($email, $password);
            if(!empty($checkLoginResult)){
                $_SESSION['login_status'] = 1;
                $_SESSION['user_details'] = $checkLoginResult;
                $_SESSION['user_profile_tab_name'] = "profile";
                $controller_name = $_SESSION['calling_controller_name'];
                if($controller_name === "activity/get_activity"){
                    $activityId = intval($_SESSION['activity_id']);
                    $queryString = "?activity_id=".$activityId;
                } else{
                    $queryString="";
                }

                if( $isAjax ){
                    $message = "You have been registered successfully.";
                    $data = array(
                        "message" => $message
                    );
                    echo json_encode($data);
                }else{
                    $message = "You have been registered successfully.";
                    $chk_mail = $this->send_registeration_email( $checkLoginResult[0]->user_email, $checkLoginResult[0]->user_password, $checkLoginResult[0]->user_first_name);					
					$data = array(
                        "message" => $message
                    );
                    $this->load->view("common/header.php");
                    $this->load->view("login/home", $data);
                    $this->load->view("common/footer.php");
                }

            } else{
                $message = "Sorry, could not register you at this time.";
                $data = array(
                    "message" => $message
                );
                if( $isAjax ){
                    echo json_encode($data);
                }else{
                    $this->load->view("common/header.php");
                    $this->load->view("login/home", $data);
                    $this->load->view("common/footer.php");
                }
            }
        }
    }

    public function login_status(){
        if(isset($_SESSION['login_status']) && $_SESSION['login_status'] == 1){
            $retdata['value'] = 1;
            echo json_encode($retdata);
        }else{
            $retdata['value'] = 0;
            echo json_encode($retdata);
        }
    }

    public function change_pass(){
        $this->load->view('common/header.php');
        $this->load->view('common/change_pass_view.php');
        $this->load->view('common/footer.php');
    }

    function check_user(){
        $email = $this->input->post('email');
        $this->load->model('login_model');
        
        if( $retPass = $this->login_model->ret_pass( $email ) ){
            $chk_mail = $this->send_email( $email, $retPass['user_password'], $retPass['user_first_name'] );
            $param = 'An E-Mail with a copy of your password has been sent to your registered email id. Please login to retrieve.';
        } else{
            $param = 'The E-Mail ID you have entered is not registered. Please check your entry.';
        }

        echo json_encode($param);
    }

    public function send_email( $email_id, $pass, $name ){
            $cust_support_data = cust_support_helper();        
            $to = $email_id;
            //define the subject of the email
            $subject = 'Lost Password - Farebucket';
            //define the message to be sent. Each line should be separated with \n
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Your password is: '.$pass.'</p><hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>'.$cust_support_data->phone_number.'</strong> <br/>Email: <strong><a href="emailto:'.$cust_support_data->email.'">'.$cust_support_data->email.'</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
            //define the headers we want passed. Note that they are separated with \r\n
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Admin <admin@farebucket.com>" . "\r\n";
            //send the email
            $mail_sent = @mail( $to, $subject, $message, $headers );
    }
	
	public function send_registeration_email( $email_id, $password, $name ){
        $cust_support_data = cust_support_helper();
        $to = $email_id;
        //define the subject of the email
        $subject = 'Confirm Registration - Farebucket';
        //define the message to be sent. Each line should be separated with \n
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta name="viewport" content="width=device-width"/> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> <title>Farebucket</title> <link rel="stylesheet" href="http://www.farebucket.com/css/email.css"></head><body bgcolor="#FFFFFF"> <table class="head-wrap"> <tr> <td></td><td class="header container"> <div class="content"> <table> <tr> <td><img src="http://www.farebucket.com/img/logo.png"/> </td><td align="right"> <h6 class="collapse"></h6> </td></tr></table> </div></td><td></td></tr></table> <hr/> <table class="body-wrap"> <tr> <td></td><td class="container" bgcolor="#FFFFFF"> <div class="content"> <table> <tr> <td> <h3>Hi, '.$name.'</h3> <p class="lead">Thank you for registering to Farebucket.com </p><p class="lead">Your User ID is: '.$email_id.'</p><p class="lead">Your Password is: '.$password.'</p><hr/> <table class="social" width="100%"> <tr> <td> <table align="left" class="column"> <tr> <td> <h5 class="">Contact Info:</h5> <p>Phone: <strong>'.$cust_support_data->phone_number.'</strong> <br/>Email: <strong><a href="emailto:'.$cust_support_data->email.'">'.$cust_support_data->email.'</a></strong> </p></td></tr></table><span class="clear"></span> </td></tr></table> <hr/> </td></tr></table> </div></td><td></td></tr></table> <table class="footer-wrap"> <tr> <td></td><td class="container"> <div class="content"> <table> <tr> <td>© 2013–2015 Reddytrip LLP.</td></tr></table> </div></td><td></td></tr></table></body></html>';
        //define the headers we want passed. Note that they are separated with \r\n
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Admin <admin@farebucket.com>" . "\r\n";
        //send the email
        $mail_sent = @mail( $to, $subject, $message, $headers );
    }

}

?>