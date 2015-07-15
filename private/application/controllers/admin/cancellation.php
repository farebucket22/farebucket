<?php
class cancellation extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        
        if(!$this->session->userdata('admin_logged_in'))
            redirect('admin/login');
    }

     function index(){
        $this->load->model('admin/bus_model');
        $buses = $this->bus_model->getCancellation();
        $this->load->model('admin/cab_model');
        $cabs = $this->cab_model->getCancellation();
        $this->load->model('admin/flight_model');
        $flights = $this->flight_model->getCancellation();
        $this->load->model('admin/hotel_model');
        $hotels = $this->hotel_model->getCancellation();

        $user_ids = [];

        if( !empty($buses) ){
            foreach( $buses as $bus ){
                $user_ids[] = $bus->user_id;
            }
        }

        if( !empty($cabs) ){
            foreach( $cabs as $cab ){
                $user_ids[] = $cab->user_id;
            }
        }
        
        if( !empty($flights) ){
            foreach( $flights as $flight ){
                $user_ids[] = $flight->user_id;
            }
        }
        
        if( !empty($v) ){
            foreach( $hotels as $hotel ){
                $user_ids[] = $hotel->user_id;
            }
        }
        $user_ids = array_unique($user_ids);
        $queryStr = "SELECT * FROM farebucket_user WHERE user_id = ";

        $i = 0;
        foreach( $user_ids as $uids ){
            if( $i == count($user_ids) - 1 ){
                $queryStr .= $uids;
            }else{
                $queryStr .= $uids . " OR user_id= ";
            }
            $i++;
        }

        $this->load->model('user_model');
        $users = $this->user_model->get_user_details_csv($queryStr);

        $data = array(
            "buses" => $buses,
            "cabs" => $cabs,
            "flights" => $flights,
            "hotels" => $hotels,
            "users" => $users
        );

        $this->load->view('admin/cancellations/list', $data);
    }  

    function refund(){
        $query = $_POST;
        $this->load->model('admin/default_model');
        $ret = $this->default_model->updateRefundValue($query);
        echo json_encode($ret);
    }
}
?>