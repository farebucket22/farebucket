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

		if ($data['adult_count_field'] > 0) {
            $i = 1;
            $passengerData['lead_adult_title'] = $travellerData['title_lead'];
            $passengerData['lead_adult_first_name'] = $travellerData['first_name_lead'];
            $passengerData['lead_adult_last_name'] = $travellerData['last_name_lead'];
            $passengerData['lead_adult_email_id'] = $travellerData['email_id_lead'];
            $passengerData['lead_adult_mobile_no'] = $travellerData['mobile_no_lead'];
        }

        if (isset($data['adult_count_field']) && $data['adult_count_field'] > 1) {
            $passengerData['adult_title_csv'] = implode(',', $travellerData['title_a']);
            $passengerData['adult_first_name_csv'] = implode(',', $travellerData['first_name_a']);
            $passengerData['adult_last_name_csv'] = implode(',', $travellerData['last_name_a']);
        }

        if (isset($data['youth_count_field']) && $data['youth_count_field'] >= 1) {
            $passengerData['kid_title_csv'] = implode(',', $travellerData['title_k']);
            $passengerData['kid_first_name_csv'] = implode(',', $travellerData['first_name_k']);
            $passengerData['kid_last_name_csv'] = implode(',', $travellerData['last_name_k']);
            $passengerData['kid_dob_csv'] = implode(',', $travellerData['dob_k']);
        }

        if (isset($data['kids_count_field']) && $data['kids_count_field'] >= 1) {
            $passengerData['infant_title_csv'] = implode(',', $travellerData['title_i']);
            $passengerData['infant_first_name_csv'] = implode(',', $travellerData['first_name_i']);
            $passengerData['infant_last_name_csv'] = implode(',', $travellerData['last_name_i']);
            $passengerData['infant_dob_csv'] = implode(',', $travellerData['dob_i']);
        }

        return $passengerData;
	}
}
?>