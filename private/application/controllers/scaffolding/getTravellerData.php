<?php
	class getTravellerData{
		private $data;

		public function setTravellerData($data){
			if ($data['details']->adult_count_field > 0) {
                $i = 1;
                $data['lead_adult_title'] = $data['title_lead'];
                $data['lead_adult_first_name'] = $data['first_name_lead'];
                $data['lead_adult_last_name'] = $data['last_name_lead'];
                $data['lead_adult_email_id'] = $data['email_id_lead'];
                $data['lead_adult_mobile_no'] = $data['mobile_no_lead'];
            }

            if (isset($data['details']->adult_count_field) && $data['details']->adult_count_field > 1) {
                $data['adult_title_csv'] = "";
                $data['adult_first_name_csv'] = "";
                $data['adult_last_name_csv'] = "";
                for ($i = 1; $i < $data['details']->adult_count_field; $i++) {
                    $j = $i - 1;
                    if ($data['adult_title_csv'] == "") {
                        $data['adult_title_csv'] = $data['title_a'][$j];
                        $data['adult_first_name_csv'] = $data['first_name_a'][$j];
                        $data['adult_last_name_csv'] = $data['last_name_a'][$j];
                    }
                    else {
                        $data['adult_title_csv'] = $data['adult_title_csv'] . ',' . $data['title_a'][$j];
                        $data['adult_first_name_csv'] = $data['adult_first_name_csv'] . ',' . $data['first_name_a'][$j];
                        $data['adult_last_name_csv'] = $data['adult_last_name_csv'] . ',' . $data['last_name_a'][$j];
                    }
                }
            }

            if (isset($data['details']->youth_count_field) && $data['details']->youth_count_field > 0) {
                $data['kid_title_csv'] = "";
                $data['kid_first_name_csv'] = "";
                $data['kid_last_name_csv'] = "";
                $data['kid_dob_csv'] = "";
                for ($i = 0; $i < $data['details']->youth_count_field; $i++) {
                    if ($data['kid_title_csv'] == "") {
                        $data['kid_title_csv'] = $data['title_k'][$i];
                        $data['kid_first_name_csv'] = $data['first_name_k'][$i];
                        $data['kid_last_name_csv'] = $data['last_name_k'][$i];
                        $data['kid_dob_csv'] = $data['dob_k'][$i];
                    }
                    else {
                        $data['kid_title_csv'] = $data['kid_title_csv'] . ',' . $data['title_k'][$i];
                        $data['kid_first_name_csv'] = $data['kid_first_name_csv'] . ',' . $data['first_name_k'][$i];
                        $data['kid_last_name_csv'] = $data['kid_last_name_csv'] . ',' . $data['last_name_k'][$i];
                        $data['kid_dob_csv'] = $data['kid_dob_csv'] . ',' . $data['dob_k'][$i];
                    }
                }
            }

            if (isset($data['details']->kids_count_field) && $data['details']->kids_count_field > 0) {
                $data['infant_title_csv'] = "";
                $data['infant_first_name_csv'] = "";
                $data['infant_last_name_csv'] = "";
                $data['infant_dob_csv'] = "";
                for ($i = 0; $i < $data['details']->kids_count_field; $i++) {
                    if ($data['infant_title_csv'] == "") {
                        $data['infant_title_csv'] = $data['title_i'][$i];
                        $data['infant_first_name_csv'] = $data['first_name_i'][$i];
                        $data['infant_last_name_csv'] = $data['last_name_i'][$i];
                        $data['infant_dob_csv'] = $data['dob_i'][$i];
                    }
                    else {
                        $data['infant_title_csv'] = $data['infant_title_csv'] . ',' . $data['title_i'][$i];
                        $data['infant_first_name_csv'] = $data['infant_first_name_csv'] . ',' . $data['first_name_i'][$i];
                        $data['infant_last_name_csv'] = $data['infant_last_name_csv'] . ',' . $data['last_name_i'][$i];
                        $data['infant_dob_csv'] = $data['infant_dob_csv'] . ',' . $data['dob_i'][$i];
                    }
                }
            }
            return $data;
		}
	}
?>