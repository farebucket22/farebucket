<?php
class cust_support extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->output->set_header('Pragma: no-cache');  
        
        if(!$this->session->userdata('admin_logged_in'))
            redirect('admin/login');
	}

	function index(){
		$this->load->model('admin/cust_support_model');
		$result = $this->cust_support_model->getCustSupportData();
		$data = array(
			'cust_details' => $result[0]
		);        
		$this->load->view('admin/header');
		$this->load->view('admin/cust_support/cust_support_list.php', $data);
	}

	function update_cust_support_data(){
		$data = $this->input->post();
		$this->load->model('admin/cust_support_model');
		$result = $this->cust_support_model->updateCustSupportData($data);
		if( $result ){
			redirect('admin/category');
		}else{
			echo "<pre>";
			print_r('An error occured. Please Try again.');die;
		}
	}

}
?>