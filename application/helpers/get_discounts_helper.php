<?php 

if( !function_exists('get_discounts_helper') ){

	function get_discounts_helper()
	{
		$CI =& get_instance();
		$CI->load->model('admin/discount_model');
    	$discounts = $CI->discount_model->getalldiscounts();
		return $discounts;	
	}	

}

?>