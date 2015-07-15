<?php
    class GetTicketDetails{
    	private $ticket;

    	public function setTicketDetails($data,$details){

    		$ticket["Ticket"]['wsTicketRequest']['BookingID'] = $details[0]->booking_id . "";
	        $ticket["Ticket"]["wsTicketRequest"]["Origin"] = $data['origin'];
	        $ticket["Ticket"]["wsTicketRequest"]["Destination"] = $data['destination'];

	        if( count($data['booking_details']) > 1 ){
	            for ($i = 0; $i < count($data['booking_details']); $i++) {
	                $ticket['Ticket']['wsTicketRequest']["Segment"]["WSSegment"][$i] = $data['booking_details'][$i];
	            }
	        }else{
	            $ticket['Ticket']['wsTicketRequest']["Segment"]["WSSegment"] = $data['booking_details'][0];
	        }

	        $ticket['Ticket']['wsTicketRequest']["FareType"] = "PUB";
	        $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"] = $data['fare_rule']['WSFareRule'];
	        $ticket['Ticket']['wsTicketRequest']["Fare"] = $data['fare'];

	        /*---*/
	        $i = 0;
	        if ($details[0]->num_of_adults >= 1) {
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $details[0]->lead_traveller_title;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $details[0]->lead_traveller_first_name;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $details[0]->lead_traveller_last_name;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c', strtotime('1-1-1990'));
	            if (count($data['fare_breakdown']['WSPTCFare']) > 3) {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare']['BaseFare'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare']['Tax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare']['AdditionalTxnFee'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare']['FuelSurcharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare']['AgentServiceCharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare']['AgentConvienceCharges'] / $details[0]->num_of_adults;
	            }
	            else {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][$i]['BaseFare'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][$i]['Tax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][$i]['AdditionalTxnFee'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][$i]['FuelSurcharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][$i]['AgentServiceCharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][$i]['AgentConvienceCharges'] / $details[0]->num_of_adults;
	            }

	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['fare']['AgentCommission'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['fare']['TdsOnCommission'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['fare']['IncentiveEarned'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['fare']['TdsOnIncentive'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['fare']['PLBEarned'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['fare']['TdsOnPLB'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['fare']['PublishedPrice'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['fare']['AirTransFee'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['fare']['Discount'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['fare']['OtherCharges'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['fare']['TransactionFee'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['fare']['ReverseHandlingCharge'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['fare']['OfferedFare'] / $details[0]->num_of_adults;
	            if ($details[0]->lead_traveller_title == 'Mr' || $details[0]->lead_traveller_title == 'Master') {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
	            }
	            else {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
	            }

	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $details[0]->lead_traveller_mobile;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $details[0]->lead_traveller_email;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
	        }

	        /*---*/
	        $i = 1;
	        while ($i < $details[0]->num_of_adults) {
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $a_t[$i - 1];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $a_f[$i - 1];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $a_l[$i - 1];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_a'][$j]));
	            if (count($data['fare_breakdown']['WSPTCFare']) > 3) {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare']['BaseFare'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare']['Tax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare']['AdditionalTxnFee'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare']['FuelSurcharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare']['AgentServiceCharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare']['AgentConvienceCharges'] / $details[0]->num_of_adults;
	            }
	            else {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][0]['BaseFare'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][0]['Tax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][0]['AdditionalTxnFee'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][0]['FuelSurcharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][0]['AgentServiceCharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][0]['AgentConvienceCharges'] / $details[0]->num_of_adults;
	            }

	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['fare']['Discount'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['fare']['OtherCharges'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['fare']['AgentCommission'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['fare']['TdsOnCommission'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['fare']['IncentiveEarned'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['fare']['TdsOnIncentive'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['fare']['PLBEarned'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['fare']['TdsOnPLB'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['fare']['PublishedPrice'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['fare']['AirTransFee'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['fare']['TransactionFee'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['fare']['ReverseHandlingCharge'] / $details[0]->num_of_adults;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['fare']['OfferedFare'] / $details[0]->num_of_adults;
	            if ($a_t[$i - 1] == 'Mr' || $a_t[$i - 1] == 'Master') {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
	            }
	            else {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
	            }

	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $details[0]->lead_traveller_mobile;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $details[0]->lead_traveller_email;
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
	            $i++;
	        }

	        /*--*/
	        $j = 0;
	        while ($j < $details[0]->num_of_children) {
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $c_t[$j];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $c_f[$j];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $c_l[$j];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Child";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_a'][$j]));
	            if (count($data['fare_breakdown']['WSPTCFare']) == 2 && $data['fare_breakdown']['WSPTCFare'][1]->PassengerType == "Child") {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][1]['BaseFare'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][1]['Tax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][1]['AdditionalTxnFee'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][1]['FuelSurcharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][1]['AgentServiceCharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][1]['AgentConvienceCharges'] / $details[0]->num_of_adults;
	            }

	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['fare']['AgentCommission'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['fare']['TdsOnCommission'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['fare']['IncentiveEarned'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['fare']['TdsOnIncentive'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['fare']['PLBEarned'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['fare']['TdsOnPLB'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['fare']['PublishedPrice'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['fare']['AirTransFee'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['fare']['Discount'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['fare']['OtherCharges'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['fare']['TransactionFee'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['fare']['ReverseHandlingCharge'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['fare']['OfferedFare'];
	            if ($c_t[$j] == 'Mr' || $c_t[$j] == 'Master') {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
	            }
	            else {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
	            }

	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
	            $i++;
	            $j++;
	        }

	        /*--*/
	        $j = 0;
	        while ($j < $details[0]->num_of_infants) {
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $i_t[$j];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $i_f[$j];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $i_l[$j];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Infant";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($data['dob_a'][$j]));
	            if (count($data['fare_breakdown']['WSPTCFare']) == 3 && $data['fare_breakdown']['WSPTCFare'][2]->PassengerType == "Infant") {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][2]['BaseFare'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][2]['Tax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][2]['AdditionalTxnFee'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][2]['FuelSurcharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][2]['AgentServiceCharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][2]['AgentConvienceCharges'] / $details[0]->num_of_adults;
	            }

	            if (count($data['fare_breakdown']['WSPTCFare']) == 2 && $data['fare_breakdown']['WSPTCFare'][1]->PassengerType == "Infant") {
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = $data['fare_breakdown']['WSPTCFare'][2]['BaseFare'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = $data['fare_breakdown']['WSPTCFare'][2]['Tax'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data['fare_breakdown']['WSPTCFare'][2]['AdditionalTxnFee'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data['fare_breakdown']['WSPTCFare'][2]['FuelSurcharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data['fare_breakdown']['WSPTCFare'][2]['AgentServiceCharge'] / $details[0]->num_of_adults;
	                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data['fare_breakdown']['WSPTCFare'][2]['AgentConvienceCharges'] / $details[0]->num_of_adults;
	            }

	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data['fare']['ServiceTax'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data['fare']['AgentCommission'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data['fare']['TdsOnCommission'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data['fare']['IncentiveEarned'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data['fare']['TdsOnIncentive'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data['fare']['PLBEarned'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data['fare']['TdsOnPLB'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data['fare']['PublishedPrice'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data['fare']['AirTransFee'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data['fare']['Discount'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data['fare']['OtherCharges'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data['fare']['TransactionFee'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data['fare']['ReverseHandlingCharge'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data['fare']['OfferedFare'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = "0001-01-01T00:00:00";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $data['lead_adult_mobile_no'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $data['lead_adult_email_id'];
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Code"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Meal"]["Description"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Code"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Seat"]["Description"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFAirline"] = "";
	            $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FFNumber"] = "";
	            $i++;
	            $j++;
	        }

	        /*--*/
	        $ticket["Ticket"]["wsTicketRequest"]["Remarks"] = "FlightTicket";
	        $ticket["Ticket"]["wsTicketRequest"]["InstantTicket"] = TRUE;
	        /*--*/
	        $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentInformationId"] = 0;
	        $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["InvoiceNumber"] = 0;
	        $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentId"] = "0";
	        $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["Amount"] = $_SESSION['onewayFlightTravellerData']['total_fare_field'];
	        $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["IPAddress"] = "";
	        $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["TrackId"] = 0;
	        $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentGateway"] = "APICustomer";
	        $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentModeType"] = "Deposited";
	        /*---*/
	        $ticket["Ticket"]["wsTicketRequest"]["Source"] = $data['Source'];
	        $ticket["Ticket"]["wsTicketRequest"]["SessionId"] = $_SESSION['sess_id'][$_SESSION['cnt_val'] - 1];
	        $ticket["Ticket"]["wsTicketRequest"]["IsOneWayBooking"] = TRUE;
	        $ticket["Ticket"]["wsTicketRequest"]["CorporateCode"] = "";
	        $ticket["Ticket"]["wsTicketRequest"]["TourCode"] = "";
	        $ticket["Ticket"]["wsTicketRequest"]["Endorsement"] = "";
	        $ticket["Ticket"]["wsTicketRequest"]["PromotionalPlanType"] = "Normal";

	        return $ticket;
    	}
    }
?>