<?php
	class GetPassengerData{
		private $passengerData;

		 /**
	     * Sets the value of passenger data.
	     *
	     * @param mixed $passengerData the passenger data
	     *
	     * @return self
	     */
		public function setPassengerData($data,$travellerData){

            $passengerData['adult_title_csv'] = "";
            $passengerData['adult_first_name_csv'] = "";
            $passengerData['adult_title_csv'] = ""; 
            $passengerData['adult_first_name_csv'] = "";
            $passengerData['adult_last_name_csv'] = "";
            $passengerData['pass_number_a']  = "";
            $passengerData['pass_expiry_a'] = ""; 
            $passengerData['kid_title_csv'] = ""; 
            $passengerData['kid_first_name_csv'] = "";        
            $passengerData['kid_last_name_csv'] = "";          
            $passengerData['kid_dob_csv'] = "";
            $passengerData['pass_number_k'] = ""; 
            $passengerData['pass_expiry_k'] = ""; 
            $passengerData['infant_title_csv'] = "";           
            $passengerData['infant_first_name_csv'] = "";
            $passengerData['infant_last_name_csv'] = "";
            $passengerData['infant_dob_csv'] = "";
            $passengerData['pass_number_i'] = ""; 
            $passengerData['pass_expiry_i'] = "";


		if ($data['adult_count_field'] > 0) {
            $i = 1;
            $passengerData['lead_adult_title'] = $travellerData['title_lead'];
            $passengerData['lead_adult_first_name'] = $travellerData['first_name_lead'];
            $passengerData['lead_adult_last_name'] = $travellerData['last_name_lead'];
            $passengerData['lead_adult_email_id'] = $travellerData['email_id_lead'];
            $passengerData['lead_adult_mobile_no'] = $travellerData['mobile_no_lead'];
            $passengerData['pass_expiry'] = $travellerData['pass_expiry'];
            $passengerData['pass_number'] = $travellerData['pass_number'];
        }

        $adult_count = $data['adult_count_field'];
        $child_count = $data['youth_count_field'];
        $infant_count = $data['kids_count_field'];

        $i=0;
        if (isset($data['adult_count_field']) && $data['adult_count_field'] > 1 ){
            while( $i+1 < $adult_count ) {
                $passengerData['adult_title_csv'][$i] = $travellerData['title_a'][$i];
                $passengerData['adult_first_name_csv'][$i] = $travellerData['first_name_a'][$i];
                $passengerData['adult_last_name_csv'][$i] = $travellerData['last_name_a'][$i];
                $passengerData['pass_number_a'][$i] = $travellerData['pass_number_a'][$i];
                $passengerData['pass_expiry_a'][$i] = $travellerData['pass_expiry_a'][$i];
                $i++;
            }
        }

        $j = 0;
        if (isset($data['youth_count_field']) && $data['youth_count_field'] >= 1){
            while($j < $child_count) {
                $passengerData['kid_title_csv'][$j] = $travellerData['title_k'][$j];
                $passengerData['kid_first_name_csv'][$j] = $travellerData['first_name_k'][$j];
                $passengerData['kid_last_name_csv'][$j] = $travellerData['last_name_k'][$j];
                $passengerData['kid_dob_csv'][$j] = $travellerData['dob_k'][$j];
                $passengerData['pass_number_k'][$j] = $travellerData['pass_number_k'][$j];
                $passengerData['pass_expiry_k'][$j] = $travellerData['pass_expiry_k'][$j];
                $j++;
            }
        }

        $k = 0;
        if (isset($data['kids_count_field']) && $data['kids_count_field'] >= 1){
            while( $k < $infant_count ) {
                $passengerData['infant_title_csv'][$k] = $travellerData['title_i'][$k];
                $passengerData['infant_first_name_csv'][$k] = $travellerData['first_name_i'][$k];
                $passengerData['infant_last_name_csv'][$k] = $travellerData['last_name_i'][$k];
                $passengerData['infant_dob_csv'][$k] = $travellerData['dob_i'][$k];
                $passengerData['pass_number_i'][$k] = $travellerData['pass_number_i'][$k];
                $passengerData['pass_expiry_i'][$k] = $travellerData['pass_expiry_i'][$k];
                $k++;
            }
        }
        return $passengerData;
	}
}
?>