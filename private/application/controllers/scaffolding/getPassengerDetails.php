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
	        }
	        else{
	        	$data['a_t'] = "";
	        	$data['a_f'] = "";
	        	$data['a_l'] = "";
	        }

	        if ($details[0]->num_of_children > 0) {
	            $data['c_t'] = explode(",", $details[0]->child_travellers_titles);
	            $data['c_f'] = explode(",", $details[0]->child_travellers_first_names);
	            $data['c_l'] = explode(",", $details[0]->child_travellers_last_names);
	            $data['c_d'] = explode(",", $details[0]->child_travellers_dobs);
	        }
	        else{
	        	$data['c_t'] = "";
	        	$data['c_f'] = "";
	        	$data['c_l'] = "";
	        	$data['c_d'] = "";
	        }

	        if ($details[0]->num_of_infants > 0) {
	            $data['i_t'] = explode(",", $details[0]->infant_travellers_titles);
	            $data['i_f'] = explode(",", $details[0]->infant_travellers_first_names);
	            $data['i_l'] = explode(",", $details[0]->infant_travellers_last_names);
	            $data['i_d'] = explode(",", $details[0]->infant_travellers_dobs);
	        }
	        else{
	        	$data['i_t'] = "";
	        	$data['i_f'] = "";
	        	$data['i_l'] = "";
	        	$data['i_d'] = "";
	        }
	        return $data;
	    }

	}
?>