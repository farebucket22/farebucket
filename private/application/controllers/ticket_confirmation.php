<?php
session_start();
class ticket_confirmation extends MY_Controller{
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		$user_id = $this->input->get('user_id');
		$this->load->model('flight_model');
		$data = $this->flight_model->getUserDetailsById($user_id);
		if($data){
			$this->load->view("common/header.php");
			$this->load->view('flights/ticket_confirmation_page', array('data'=>$data));
			$this->load->view("common/footer.php");
		}
		else{
			//handle else part with a warning in the view
			$this->load->view("common/header.php");
			$this->load->view('flights/ticket_confirmation_page', array('data'=>$data));
			$this->load->view("common/footer.php");
		}
	}

	function set_ticket_details(){
		$data = $this->input->post("data");
		$this->load->model('flight_model');
		$ticket_id = $this->flight_model->postTicket($data);
		if($ticket_id){
			echo json_encode($ticket_id);
		}
		else{
			echo "failure";
		}
	}

	function dummy_page(){
		$data = $this->input->get(null, true);
		$_SESSION['ticket_id'] = $data['ticket_id'];
		redirect('api/flights/ticket');
		$this->load->view("common/header.php");
		$this->load->view('flights/dummy_page', array('data' => $data));
		$this->load->view("common/footer.php");
	}

	function dummy_return_page(){
		print_r('hi dummy_return_page');
		$data = $this->input->get(null, true);
		$_SESSION['outbound'] = $data['out_id'];
		$_SESSION['inbound'] = $data['in_id'];
		redirect('api/flights/ticket_return');
		$this->load->view("common/header.php");
		$this->load->view('flights/dummy_return_page', array('data' => $data));
		$this->load->view("common/footer.php");
	}

	function update_flight_status(){
		$data = $this->input->post(null, true);
		$this->load->model('flight_model');
		$this->flight_model->updateStatus($data);
		redirect('ticket_confirmation?user_id=randomID');
	}

	function payment_status(){
		$id = $this->input->get(null, true);
		$data = $this->input->post(null, true);

		$this->load->model('flight_model');
		$this->flight_model->updateStatus(array('status'=>$data, 'ticket_id'=>$id));
		redirect('ticket_confirmation?user_id=randomID');
	}

	function get_data()
	{
		$data = $this->input->post( null, true );
		$_SESSION['travellers'] = $data;
		echo json_encode( $data );
	}


}