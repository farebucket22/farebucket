<?php
class Default_Controller extends MY_Controller{

	function index()
	{
		$this->load->model('admin/default_model');
		$data = $this->default_model->get_controller();
		redirect($data);
	}

}
?>