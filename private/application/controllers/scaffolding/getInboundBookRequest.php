<?php
    class GetInboundBookRequest{
        private $book;

        public function setInboundBookRequest($data){

            $book["Book"]["bookRequest"]["Remarks"] = "FlightBook";
            $book["Book"]["bookRequest"]["InstantTicket"] = True;
            $book["Book"]["bookRequest"]["Fare"]["BaseFare"] = $data['details']->inbound_booking_details->rest->Fare->BaseFare;
            $book["Book"]["bookRequest"]["Fare"]["Tax"] = $data['details']->inbound_booking_details->rest->Fare->Tax;
            $book["Book"]["bookRequest"]["Fare"]["ServiceTax"] = $data['details']->inbound_booking_details->rest->Fare->ServiceTax;
            $book["Book"]["bookRequest"]["Fare"]["AdditionalTxnFee"] = $data['details']->inbound_booking_details->rest->Fare->AdditionalTxnFee;
            $book["Book"]["bookRequest"]["Fare"]["AgentCommission"] = $data['details']->inbound_booking_details->rest->Fare->AgentCommission;
            $book["Book"]["bookRequest"]["Fare"]["TdsOnCommission"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnCommission;
            $book["Book"]["bookRequest"]["Fare"]["IncentiveEarned"] = $data['details']->inbound_booking_details->rest->Fare->IncentiveEarned;
            $book["Book"]["bookRequest"]["Fare"]["TdsOnIncentive"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnIncentive;
            $book["Book"]["bookRequest"]["Fare"]["PLBEarned"] = $data['details']->inbound_booking_details->rest->Fare->PLBEarned;
            $book["Book"]["bookRequest"]["Fare"]["TdsOnPLB"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnPLB;
            $book["Book"]["bookRequest"]["Fare"]["PublishedPrice"] = $data['details']->inbound_booking_details->rest->Fare->PublishedPrice;
            $book["Book"]["bookRequest"]["Fare"]["AirTransFee"] = $data['details']->inbound_booking_details->rest->Fare->AirTransFee;
            $book["Book"]["bookRequest"]["Fare"]["Currency"] = $data['details']->inbound_booking_details->rest->Fare->Currency;
            $book["Book"]["bookRequest"]["Fare"]["Discount"] = $data['details']->inbound_booking_details->rest->Fare->Discount;
            $book["Book"]["bookRequest"]["Fare"]["OtherCharges"] = $data['details']->inbound_booking_details->rest->Fare->OtherCharges;
            $book["Book"]["bookRequest"]["Fare"]["FuelSurcharge"] = $data['details']->inbound_booking_details->rest->Fare->FuelSurcharge;
            $book["Book"]["bookRequest"]["Fare"]["TransactionFee"] = $data['details']->inbound_booking_details->rest->Fare->TransactionFee;
            $book["Book"]["bookRequest"]["Fare"]["ReverseHandlingCharge"] = $data['details']->inbound_booking_details->rest->Fare->ReverseHandlingCharge;
            $book["Book"]["bookRequest"]["Fare"]["OfferedFare"] = $data['details']->inbound_booking_details->rest->Fare->OfferedFare;
            $book["Book"]["bookRequest"]["Fare"]["AgentServiceCharge"] = $data['details']->inbound_booking_details->rest->Fare->AgentServiceCharge;
            $book["Book"]["bookRequest"]["Fare"]["AgentConvienceCharges"] = $data['details']->inbound_booking_details->rest->Fare->AgentConvienceCharges;

            // echo '<pre>';print_r($data);

            /*---lead traveller---*/
            $i = 0;
            if ($data['details']->adult_count_field >= 1) {
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_lead'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_lead'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_lead'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime("1-1-1990"));
                if (is_array($data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare)) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->BaseFare / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->Tax / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->AdditionalTxnFee / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->FuelSurcharge / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentServiceCharge / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentConvienceCharges / $data['details']->adult_count_field;
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->BaseFare / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->Tax / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->FuelSurcharge / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->AgentServiceCharge / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges / $data['details']->adult_count_field;
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details']->inbound_booking_details->rest->Fare->ServiceTax / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details']->inbound_booking_details->rest->Fare->AgentCommission / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnCommission / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details']->inbound_booking_details->rest->Fare->IncentiveEarned / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnIncentive / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details']->inbound_booking_details->rest->Fare->PLBEarned / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnPLB / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details']->inbound_booking_details->rest->Fare->PublishedPrice / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details']->inbound_booking_details->rest->Fare->AirTransFee / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details']->inbound_booking_details->rest->Fare->Discount / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details']->inbound_booking_details->rest->Fare->OtherCharges / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details']->inbound_booking_details->rest->Fare->TransactionFee / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details']->inbound_booking_details->rest->Fare->ReverseHandlingCharge / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details']->inbound_booking_details->rest->Fare->OfferedFare / $data['details']->adult_count_field;
                if ($data['title_lead'] == 'Mr' || $data['title_lead'] == 'Master') {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
            }

            /*------*/
            $i = 1;
            while ($i < $data['details']->adult_count_field) {
                $j = $i - 1;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_a'][$j];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_a'][$j];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_a'][$j];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_a'][$j]));

                // print_r($data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]);die;

                if (is_array($data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare)) {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->BaseFare / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->Tax / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->AdditionalTxnFee / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->FuelSurcharge / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentServiceCharge / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[0]->AgentConvienceCharges / $data['details']->adult_count_field;
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->BaseFare / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->Tax / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->FuelSurcharge / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->AgentServiceCharge / $data['details']->adult_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges / $data['details']->adult_count_field;
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details']->inbound_booking_details->rest->Fare->ServiceTax / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details']->inbound_booking_details->rest->Fare->AgentCommission / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnCommission / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details']->inbound_booking_details->rest->Fare->IncentiveEarned / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnIncentive / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details']->inbound_booking_details->rest->Fare->PLBEarned / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnPLB / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details']->inbound_booking_details->rest->Fare->PublishedPrice / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details']->inbound_booking_details->rest->Fare->AirTransFee / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details']->inbound_booking_details->rest->Fare->Discount / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details']->inbound_booking_details->rest->Fare->OtherCharges / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details']->inbound_booking_details->rest->Fare->TransactionFee / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details']->inbound_booking_details->rest->Fare->ReverseHandlingCharge / $data['details']->adult_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details']->inbound_booking_details->rest->Fare->OfferedFare / $data['details']->adult_count_field;
                if ($data['title_a'][$j] == 'Mr' || $data['title_a'][$j] == 'Master') {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
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
            while ($i < $data['details']->youth_count_field) {
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_k'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_k'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_k'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_k'][$j]));
                if (count($data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare) > 1 && $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Child") {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->BaseFare / $data['details']->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->Tax / $data['details']->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->AdditionalTxnFee / $data['details']->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->FuelSurcharge / $data['details']->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->AgentServiceCharge / $data['details']->youth_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->AgentConvienceCharges / $data['details']->youth_count_field;
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details']->inbound_booking_details->rest->Fare->ServiceTax / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details']->inbound_booking_details->rest->Fare->AgentCommission / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnCommission / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details']->inbound_booking_details->rest->Fare->IncentiveEarned / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnIncentive / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details']->inbound_booking_details->rest->Fare->PLBEarned / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnPLB / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details']->inbound_booking_details->rest->Fare->PublishedPrice / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details']->inbound_booking_details->rest->Fare->AirTransFee / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details']->inbound_booking_details->rest->Fare->Discount / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details']->inbound_booking_details->rest->Fare->OtherCharges / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details']->inbound_booking_details->rest->Fare->TransactionFee / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details']->inbound_booking_details->rest->Fare->ReverseHandlingCharge / $data['details']->youth_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details']->inbound_booking_details->rest->Fare->OfferedFare / $data['details']->youth_count_field;
                if ($data['title_k'][$i] == 'Mr' || $data['title_k'][$i] == 'Master') {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
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
            while ($i < $data['details']->kids_count_field) {
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $data['title_i'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $data['first_name_i'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $data['last_name_i'][$i];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_i'][$j]));
                if (count($data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare) > 2 && $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[2]->PassengerType == "Infant") {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[2]->BaseFare / $data['details']->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[2]->Tax / $data['details']->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[2]->AdditionalTxnFee / $data['details']->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[2]->FuelSurcharge / $data['details']->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[2]->AgentServiceCharge / $data['details']->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[2]->AgentConvienceCharges / $data['details']->kids_count_field;
                }

                if (count($data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare) == 2 && $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Infant") {
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->BaseFare / $data['details']->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->Tax / $data['details']->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->AdditionalTxnFee / $data['details']->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->FuelSurcharge / $data['details']->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->AgentServiceCharge / $data['details']->kids_count_field;
                    $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['details']->inbound_booking_details->rest->FareBreakdown->WSPTCFare[1]->AgentConvienceCharges / $data['details']->kids_count_field;
                }

                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['details']->inbound_booking_details->rest->Fare->ServiceTax / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['details']->inbound_booking_details->rest->Fare->AgentCommission / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnCommission / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['details']->inbound_booking_details->rest->Fare->IncentiveEarned / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnIncentive / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['details']->inbound_booking_details->rest->Fare->PLBEarned / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['details']->inbound_booking_details->rest->Fare->TdsOnPLB / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['details']->inbound_booking_details->rest->Fare->PublishedPrice / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['details']->inbound_booking_details->rest->Fare->AirTransFee / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['details']->inbound_booking_details->rest->Fare->Discount / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['details']->inbound_booking_details->rest->Fare->OtherCharges / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['details']->inbound_booking_details->rest->Fare->TransactionFee / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['details']->inbound_booking_details->rest->Fare->ReverseHandlingCharge / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['details']->inbound_booking_details->rest->Fare->OfferedFare / $data['details']->kids_count_field;
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
                $book["Book"]["bookRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
                $i++;
            }

            /*....*/
            $book["Book"]["bookRequest"]["Origin"] = $data['details']->inbound_booking_details->rest->Origin;
            $book["Book"]["bookRequest"]["Destination"] = $data['details']->inbound_booking_details->rest->Destination;
            /*....*/
            if (!is_array($data['details']->inbound_booking_details->rest->Segment->WSSegment)) {
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["SegmentIndicator"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->SegmentIndicator;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Airline->AirlineCode;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Airline->AirlineName;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["FlightNumber"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->FlightNumber;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["FareClass"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->FareClass;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Origin->AirportCode;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Origin->AirportName;
                if (isset($data['details']->inbound_booking_details->rest->Segment->WSSegment->Origin->Terminal)) {
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["Terminal"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Origin->Terminal;
                }
                else {
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["Terminal"] = "";
                }

                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Origin->CityCode;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Origin->CityName;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Origin->CountryCode;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Origin->CountryName;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Destination->AirportCode;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Destination->AirportName;
                if (isset($data->rest->Segment->WSSegment->Destination->Terminal)) {
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["Terminal"] = $data->rest->Segment->WSSegment->Destination->Terminal;
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["Terminal"] = "";
                }

                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Destination->CityCode;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Destination->CityName;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Destination->CountryCode;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Destination->CountryName;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["DepTIme"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->DepTIme;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["ArrTime"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->ArrTime;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["ETicketEligible"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->ArrTime;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Duration"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Duration;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Stop"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Stop;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Craft"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Craft;
                $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Status"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Status;
            }
            else {
                for ($t = 0; $t < count($data['details']->inbound_booking_details->rest->Segment->WSSegment); $t++) {
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["SegmentIndicator"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->SegmentIndicator;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Airline"]["AirlineCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Airline->AirlineCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Airline"]["AirlineName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Airline->AirlineName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["FlightNumber"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->FlightNumber;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["FareClass"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->FareClass;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["AirportCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Origin->AirportCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["AirportName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Origin->AirportName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["Terminal"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Origin->Terminal;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CityCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Origin->CityCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CityName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Origin->CityName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CountryCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Origin->CountryCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Origin"]["CountryName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Origin->CountryName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["AirportCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Destination->AirportCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["AirportName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Destination->AirportName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["Terminal"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Destination->Terminal;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CityCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Destination->CityCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CityName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Destination->CityName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CountryCode"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Destination->CountryCode;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Destination"]["CountryName"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Destination->CountryName;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["DepTIme"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->DepTIme;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["ArrTime"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->ArrTime;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["ETicketEligible"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->ArrTime;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Duration"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Duration;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Stop"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Stop;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Craft"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Craft;
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][$t]["Status"] = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$t]->Status;
                }
            }

            $book["Book"]["bookRequest"]["FareType"] = "PUB";
            /*----352a001f-5214-4067-a67f-b9be1e2f92fc*/
            if (!is_array($data['details']->inbound_booking_details->rest->FareRule->WSFareRule)) {
                $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Origin"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule->Origin;
                $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Destination"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule->Destination;
                $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Airline"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule->Airline;
                $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["FareRestriction"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule->FareRestriction;
                $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["FareBasisCode"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule->FareBasisCode;
                $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["DepartureDate"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule->DepartureDate;
                $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["ReturnDate"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule->ReturnDate;
                $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][0]["Source"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule->Source;
                $book["Book"]["bookRequest"]["Source"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule->Source;
            }
            else {
                for ($t = 0; $t < count($data['details']->inbound_booking_details->rest->FareRule->WSFareRule); $t++) {
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Origin"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule[$t]->Origin;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Destination"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule[$t]->Destination;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Airline"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule[$t]->Airline;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["FareRestriction"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule[$t]->FareRestriction;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["FareBasisCode"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule[$t]->FareBasisCode;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["DepartureDate"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule[$t]->DepartureDate;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["ReturnDate"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule[$t]->ReturnDate;
                    $book["Book"]["bookRequest"]["FareRule"]["WSFareRule"][$t]["Source"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule[$t]->Source;
                    $book["Book"]["bookRequest"]["Source"] = $data['details']->inbound_booking_details->rest->FareRule->WSFareRule[$t]->Source;
                }
            }

            $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentInformationId"] = 0;
            $book["Book"]["bookRequest"]["PaymentInformation"]["InvoiceNumber"] = 0;
            $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentId"] = 0;
            $book["Book"]["bookRequest"]["PaymentInformation"]["Amount"] = 14024;
            $book["Book"]["bookRequest"]["PaymentInformation"]["IPAddress"] = "";
            $book["Book"]["bookRequest"]["PaymentInformation"]["TrackId"] = 0;
            $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentGateway"] = "APICustomer";
            $book["Book"]["bookRequest"]["PaymentInformation"]["PaymentModeType"] = "Deposited";
            $book["Book"]["bookRequest"]["SessionId"] = $_SESSION['inbound_id'];
            $book["Book"]["bookRequest"]["PromotionalPlanType"] = "Normal";

            return $book;

        }
    }
?>