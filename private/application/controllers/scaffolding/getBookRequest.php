<?php
    class GetBookRequest{
        private $book;

        public function setBookRequest($data, $passengerData){
            $book["Book"]["bookRequest"]["Remarks"] = "FlightBook";
            $book["Book"]["bookRequest"]["InstantTicket"] = True;
            $book["Book"]["bookRequest"]["Fare"] = $data['fare'];

            /*---lead traveller---*/
            $i = 0;
            //echo '<pre>';print_r($data);die;
            if ($data['adult_count_field'] >= 1) {
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $passengerData['lead_adult_title'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $passengerData['lead_adult_first_name'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $passengerData['lead_adult_first_name'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c', strtotime('1-1-1990'));

                if ( count($data['fare_breakdown']['WSPTCFare']) > 3 ) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare']['BaseFare'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare']['Tax'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare']['AdditionalTxnFee'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare']['FuelSurcharge'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare']['AgentServiceCharge'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare']['AgentConvienceCharges'] / $data['adult_count_field'];
                } else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][0]['BaseFare'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][0]['Tax'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][0]['AdditionalTxnFee'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][0]['FuelSurcharge'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][0]['AgentServiceCharge'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][0]['AgentConvienceCharges'] / $data['adult_count_field'];
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['fare']['AgentCommission'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['fare']['TdsOnCommission'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['fare']['IncentiveEarned'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['fare']['TdsOnIncentive'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['fare']['PLBEarned'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['fare']['TdsOnPLB'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['fare']['PublishedPrice'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['fare']['AirTransFee'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['fare']['Discount'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['fare']['OtherCharges'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['fare']['TransactionFee'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['fare']['ReverseHandlingCharge'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['fare']['OfferedFare'] / $data['adult_count_field'];
                if ($passengerData['lead_adult_title'] == 'Mr' || $passengerData['lead_adult_title'] == 'Master') {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                } else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                if (!empty($_POST['pass_number'])) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = $_POST['pass_number'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = $_POST['pass_expiry'];
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $passengerData['lead_adult_mobile_no'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $passengerData['lead_adult_email_id'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
            }

            /*------*/
            $i = 1;
            while ($i < $data['adult_count_field']) {
                $j = $i - 1;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $_POST['title_a'][$j];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $_POST['first_name_a'][$j];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $_POST['last_name_a'][$j];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1990-1-1'));
                if (is_array($data['fare_breakdown']['WSPTCFare'])) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][0]['BaseFare'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][0]['Tax'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][0]['AdditionalTxnFee'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][0]['FuelSurcharge'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][0]['AgentServiceCharge'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][0]['AgentConvienceCharges'] / $data['adult_count_field'];
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare']['BaseFare'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare']['Tax'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare']['AdditionalTxnFee'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare']['FuelSurcharge'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare']['AgentServiceCharge'] / $data['adult_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare']['AgentConvienceCharges'] / $data['adult_count_field'];
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['fare']['AgentCommission'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['fare']['TdsOnCommission'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['fare']['IncentiveEarned'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['fare']['TdsOnIncentive'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['fare']['PLBEarned'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['fare']['TdsOnPLB'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['fare']['PublishedPrice'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['fare']['AirTransFee'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['fare']['Discount'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['fare']['OtherCharges'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['fare']['TransactionFee'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['fare']['ReverseHandlingCharge'] / $data['adult_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['fare']['OfferedFare'] / $data['adult_count_field'];
                if ($_POST['title_a'][$j] == 'Mr' || $_POST['title_a'][$j] == 'Master') {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                if (!empty($_POST['pass_number_a'][0])) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = $_POST['pass_number_a'][$j];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = $_POST['pass_expiry_a'][$j];
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $passengerData['lead_adult_mobile_no'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $passengerData['lead_adult_email_id'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                $i++;
            }

            /*....*/
            $i = 0;
            while ($i < $data['youth_count_field']) {
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $_POST['title_k'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $_POST['first_name_k'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $_POST['last_name_k'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c', strtotime($_POST['dob_k'][$i]));
                if (count($data['fare_breakdown']['WSPTCFare']) > 1 && $data['fare_breakdown']['WSPTCFare'][1]['PassengerType'] == "Child") {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][1]['BaseFare'] / $data['youth_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][1]['Tax'] / $data['youth_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][1]['AdditionalTxnFee'] / $data['youth_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][1]['FuelSurcharge'] / $data['youth_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][1]['AgentServiceCharge'] / $data['youth_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][1]['AgentConvienceCharges'] / $data['youth_count_field'];
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['fare']['AgentCommission'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['fare']['TdsOnCommission'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['fare']['IncentiveEarned'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['fare']['TdsOnIncentive'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['fare']['PLBEarned'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['fare']['TdsOnPLB'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['fare']['PublishedPrice'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['fare']['AirTransFee'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['fare']['Discount'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['fare']['OtherCharges'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['fare']['TransactionFee'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['fare']['ReverseHandlingCharge'] / $data['youth_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['fare']['OfferedFare'] / $data['youth_count_field'];
                if ($_POST['title_k'][$i] == 'Mr' || $_POST['title_k'][$i] == 'Master') {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                if (!empty($_POST['pass_number_k'][0])) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = $_POST['pass_number_k'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = $_POST['pass_expiry_k'][$i];
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $passengerData['lead_adult_mobile_no'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $passengerData['lead_adult_email_id'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                $i++;
            }

            /*--*/
            $i = 0;
            while ($i < $data['kids_count_field']) {
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $_POST['title_i'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $_POST['first_name_i'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $_POST['last_name_i'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($_POST['dob_i'][$i]));
                if (count($data['fare_breakdown']['WSPTCFare']) > 2 && $data['fare_breakdown']['WSPTCFare'][2]['PassengerType'] == "Infant") {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][2]['BaseFare'] / $data['kids_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][2]['Tax'] / $data['kids_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][2]['AdditionalTxnFee'] / $data['kids_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][2]['FuelSurcharge'] / $data['kids_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][2]['AgentServiceCharge'] / $data['kids_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][2]['AgentConvienceCharges'] / $data['kids_count_field'];
                }

                if (count($data['fare_breakdown']['WSPTCFare']) == 2 && $data['fare_breakdown']['WSPTCFare'][1]->PassengerType == "Infant") {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][1]['BaseFare'] / $data['kids_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][1]['Tax'] / $data['kids_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][1]['AdditionalTxnFee'] / $data['kids_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][1]['FuelSurcharge'] / $data['kids_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][1]['AgentServiceCharge'] / $data['kids_count_field'];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][1]['AgentConvienceCharges'] / $data['kids_count_field'];
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['fare']['AgentCommission'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['fare']['TdsOnCommission'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['fare']['IncentiveEarned'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['fare']['TdsOnIncentive'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['fare']['PLBEarned'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['fare']['TdsOnPLB'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['fare']['PublishedPrice'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['fare']['AirTransFee'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['fare']['Discount'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['fare']['OtherCharges'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['fare']['TransactionFee'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['fare']['ReverseHandlingCharge'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['fare']['OfferedFare'] / $data['kids_count_field'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                if (!empty($_POST['pass_number_i'][0])) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = $_POST['pass_number_i'][$i];
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = $_POST['pass_expiry_i'][$i];
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $passengerData['lead_adult_mobile_no'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $passengerData['lead_adult_email_id'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                $i++;
            }
            /*....*/
            $book["Book"]["bookRequest"]["Origin"] = $data['origin'];
            $book["Book"]["bookRequest"]["Destination"] = $data['destination'];
            /*....*/

            if( count($data['booking_details']) > 1 ){
                for( $b = 0 ; $b < count($data['booking_details']) ; $b++ ){
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$b] = $data['booking_details'][$b];
                }
            }else{                
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"] = $data['booking_details'][0];
            }

            $book["Book"]["bookRequest"]["FareType"] = "PUB";
            $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"] = $data['fare_rule']['WSFareRule'];

            $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentInformationId"] = 0;
            $book["Book"]["bookRequest"]["PaymentInformation"]["InvoiceNumber"] = 0;
            $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentId"] = 0;
            $book["Book"]["bookRequest"]["PaymentInformation"]["Amount"] = 14024;
            $book["Book"]["bookRequest"]["PaymentInformation"]["IPAddress"] = "";
            $book["Book"]["bookRequest"]["PaymentInformation"]["TrackId"] = 0;
            $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentGateway"] = "APICustomer";
            $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentModeType"] = "Deposited";
            $book["Book"]["bookRequest"]["SessionId"] = $_SESSION['sess_id'][$_SESSION['cnt_val'] - 1];
            $book["Book"]["bookRequest"]["PromotionalPlanType"] = "Normal";
            $book["Book"]["bookRequest"]["Source"] = $data['Source'];

            return $book;
        }
            
    }            
?>