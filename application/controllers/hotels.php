<?php
@session_start();
class Hotels extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $_SESSION['calling_controller_name'] = "hotels";
        if( isset($_SESSION['hotel_single_post_data']) ){
            unset($_SESSION['hotel_single_post_data']);
        }
        if( isset($_SESSION['hotel_multi_post_data']) ){
            unset($_SESSION['hotel_multi_post_data']);
        }
        if(isset($_SESSION['hotel_query'])){
        	unset($_SESSION['hotel_query']);
        }
        if(isset($_SESSION['hotel_data'])){
        	unset($_SESSION['hotel_data']);
        }
    }

    public function index(){
		$_SESSION['calling_controller_name'] = "hotels";
		unset($_SESSION['currentUrlCabs']);
        unset($_SESSION['currentUrlBus']);
        $_SESSION['currentUrlHotel'] = current_full_url();
		$this->load->model('flight_model');
		$data = $this->flight_model->get_background_image('hotels');
		$this->load->view("common/header.php");
		$this->load->view('hotels/hotels_view',array('data' => $data));
		$this->load->view("common/footer.php");
    }
}