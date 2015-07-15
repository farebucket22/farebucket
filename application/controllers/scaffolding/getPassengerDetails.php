<?php
	class GetPassengerDetails{
		private $data;

		 /**
	     * Sets the value of data.
	     *
	     * @param mixed $data the data
	     *
	     * @return self
	     */
		public function setPassengerDetails($details){
			
			if ($details[0]->num_of_adults > 1) {
	            $data['a_t'] = explode(",", $details[0]->adult_travellers_titles);
	            $data['a_f'] = explode(",", $details[0]->adult_travellers_first_names);
	            $data['a_l'] = explode(",", $details[0]->adult_travellers_last_names);
	            $data['a_p_n'] = explode(",", $details[0]->adult_pass_number);
	            $data['a_p_e'] = explode(",",$details[0]->adult_pass_expiry);
	        }
	        else{
	        	$data['a_t'] = "";
	        	$data['a_f'] = "";
	        	$data['a_l'] = "";
	        	$data['a_p_n'] = "";
	        	$data['a_p_e'] = "";
	        }

	        if ($details[0]->num_of_children > 0) {
	            $data['c_t'] = explode(",", $details[0]->child_travellers_titles);
	            $data['c_f'] = explode(",", $details[0]->child_travellers_first_names);
	            $data['c_l'] = explode(",", $details[0]->child_travellers_last_names);
	            $data['c_d'] = explode(",", $details[0]->child_travellers_dobs);
	            $data['c_p_n'] = explode(",", $details[0]->child_pass_number);
	            $data['c_p_e'] = explode(",", $details[0]->child_pass_expiry);
	        }
	        else{
	        	$data['c_t'] = "";
	        	$data['c_f'] = "";
	        	$data['c_l'] = "";
	        	$data['c_d'] = "";
	        	$data['c_p_n'] = "";
				$data['c_p_e'] = "";
	        }

	        if ($details[0]->num_of_infants > 0) {
	            $data['i_t'] = explode(",", $details[0]->infant_travellers_titles);
	            $data['i_f'] = explode(",", $details[0]->infant_travellers_first_names);
	            $data['i_l'] = explode(",", $details[0]->infant_travellers_last_names);
	            $data['i_d'] = explode(",", $details[0]->infant_travellers_dobs);
	            $data['i_p_n'] = explode(",", $details[0]->infant_pass_number);
	            $data['i_p_e'] = explode(",", $details[0]->infant_pass_expiry);
	        }
	        else{
	        	$data['i_t'] = "";
	        	$data['i_f'] = "";
	        	$data['i_l'] = "";
	        	$data['i_d'] = "";
	        	$data['i_p_n'] = "";
				$data['i_p_e'] = "";
	        }
	        return $data;
	    }

	}
?>