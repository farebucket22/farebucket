<?php 

if( !function_exists('cust_support_helper') ){

	function cust_support_helper()
	{
		$CI =& get_instance();
		$CI->load->model('admin/cust_support_model');
		$cust_support_result = $CI->cust_support_model->getCustSupportData();
		return $cust_support_result[0];
	}	

}

?>