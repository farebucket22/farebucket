<?php
    class GetReturnTicketDetails{
        private $ticket;

        public function setReturnTicketDetails($data,$details,$in_out,$det){

            $ticket["Ticket"]['wsTicketRequest']['BookingID'] = $details[0]->booking_id . "";
            $ticket["Ticket"]["wsTicketRequest"]["Origin"] = $data->rest->Origin;
            $ticket["Ticket"]["wsTicketRequest"]["Destination"] = $data->rest->Destination;
            if (!is_array($data->rest->Segment->WSSegment)) {
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["SegmentIndicator"] = $data->rest->Segment->WSSegment->SegmentIndicator;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineCode"] = $data->rest->Segment->WSSegment->Airline->AirlineCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Airline"]["AirlineName"] = $data->rest->Segment->WSSegment->Airline->AirlineName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["FlightNumber"] = $data->rest->Segment->WSSegment->FlightNumber;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["FareClass"] = $data->rest->Segment->WSSegment->FareClass;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportCode"] = $data->rest->Segment->WSSegment->Origin->AirportCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["AirportName"] = $data->rest->Segment->WSSegment->Origin->AirportName;
                if (isset($data->rest->Segment->WSSegment->Origin->Terminal)) {
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["Terminal"] = $data->rest->Segment->WSSegment->Origin->Terminal;
                }
                else {
                    $book["Book"]["bookRequest"]["Segment"]["WSSegment"][0]["Origin"]["Terminal"] = "";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityCode"] = $data->rest->Segment->WSSegment->Origin->CityCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CityName"] = $data->rest->Segment->WSSegment->Origin->CityName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryCode"] = $data->rest->Segment->WSSegment->Origin->CountryCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Origin"]["CountryName"] = $data->rest->Segment->WSSegment->Origin->CountryName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportCode"] = $data->rest->Segment->WSSegment->Destination->AirportCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["AirportName"] = $data->rest->Segment->WSSegment->Destination->AirportName;
                if (isset($data->rest->Segment->WSSegment->Destination->Terminal)) {
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["Terminal"] = $data->rest->Segment->WSSegment->Destination->Terminal;
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["Terminal"] = "";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityCode"] = $data->rest->Segment->WSSegment->Destination->CityCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CityName"] = $data->rest->Segment->WSSegment->Destination->CityName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryCode"] = $data->rest->Segment->WSSegment->Destination->CountryCode;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Destination"]["CountryName"] = $data->rest->Segment->WSSegment->Destination->CountryName;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["DepTIme"] = $data->rest->Segment->WSSegment->DepTIme;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["ArrTime"] = $data->rest->Segment->WSSegment->ArrTime;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["ETicketEligible"] = true;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Duration"] = $data->rest->Segment->WSSegment->Duration;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Stop"] = $data->rest->Segment->WSSegment->Stop;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Craft"] = $data->rest->Segment->WSSegment->Craft;
                $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][0]["Status"] = $data->rest->Segment->WSSegment->Status;
                $ticket['Ticket']['wsTicketRequest']["Segment"]["WSSegment"][0]["OperatingCarrier"] = "";
            }
            else {
                for ($i = 0; $i < count($data->rest->Segment->WSSegment); $i++) {
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["SegmentIndicator"] = $data->rest->Segment->WSSegment[$i]->SegmentIndicator;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Airline"]["AirlineCode"] = $data->rest->Segment->WSSegment[$i]->Airline->AirlineCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Airline"]["AirlineName"] = $data->rest->Segment->WSSegment[$i]->Airline->AirlineName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["FlightNumber"] = $data->rest->Segment->WSSegment[$i]->FlightNumber;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["FareClass"] = $data->rest->Segment->WSSegment[$i]->FareClass;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["AirportCode"] = $data->rest->Segment->WSSegment[$i]->Origin->AirportCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["AirportName"] = $data->rest->Segment->WSSegment[$i]->Origin->AirportName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["Terminal"] = $data->rest->Segment->WSSegment[$i]->Origin->Terminal;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CityCode"] = $data->rest->Segment->WSSegment[$i]->Origin->CityCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CityName"] = $data->rest->Segment->WSSegment[$i]->Origin->CityName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CountryCode"] = $data->rest->Segment->WSSegment[$i]->Origin->CountryCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Origin"]["CountryName"] = $data->rest->Segment->WSSegment[$i]->Origin->CountryName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["AirportCode"] = $data->rest->Segment->WSSegment[$i]->Destination->AirportCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["AirportName"] = $data->rest->Segment->WSSegment[$i]->Destination->AirportName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["Terminal"] = $data->rest->Segment->WSSegment[$i]->Destination->Terminal;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CityCode"] = $data->rest->Segment->WSSegment[$i]->Destination->CityCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CityName"] = $data->rest->Segment->WSSegment[$i]->Destination->CityName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CountryCode"] = $data->rest->Segment->WSSegment[$i]->Destination->CountryCode;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Destination"]["CountryName"] = $data->rest->Segment->WSSegment[$i]->Destination->CountryName;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["DepTIme"] = $data->rest->Segment->WSSegment[$i]->DepTIme;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["ArrTime"] = $data->rest->Segment->WSSegment[$i]->ArrTime;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["ETicketEligible"] = true;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Duration"] = $data->rest->Segment->WSSegment[$i]->Duration;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Stop"] = $data->rest->Segment->WSSegment[$i]->Stop;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Craft"] = $data->rest->Segment->WSSegment[$i]->Craft;
                    $ticket["Ticket"]["wsTicketRequest"]["Segment"]["WSSegment"][$i]["Status"] = $data->rest->Segment->WSSegment[$i]->Status;
                    $ticket['Ticket']['wsTicketRequest']["Segment"]["WSSegment"][$i]["OperatingCarrier"] = "";
                }
            }

            /*----*/
            $ticket['Ticket']['wsTicketRequest']["FareType"] = "PUB";
            if (!is_array($data->rest->FareRule->WSFareRule)) {
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Origin"] = $data->rest->FareRule->WSFareRule->Origin;
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Airline"] = $data->rest->FareRule->WSFareRule->Airline;
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["FareBasisCode"] = $data->rest->FareRule->WSFareRule->FareBasisCode;
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["DepartureDate"] = $data->rest->FareRule->WSFareRule->DepartureDate;
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["ReturnDate"] = $data->rest->FareRule->WSFareRule->ReturnDate;
                $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"]["Source"] = $data->rest->FareRule->WSFareRule->Source;
            }
            else {
                for ($i = 0; $i < count($data->rest->FareRule->WSFareRule); $i++) {
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["Origin"] = $data->rest->FareRule->WSFareRule[$i]->Origin;
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["Airline"] = $data->rest->FareRule->WSFareRule[$i]->Airline;
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["FareBasisCode"] = $data->rest->FareRule->WSFareRule[$i]->FareBasisCode;
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["DepartureDate"] = $data->rest->FareRule->WSFareRule[$i]->DepartureDate;
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["ReturnDate"] = $data->rest->FareRule->WSFareRule[$i]->ReturnDate;
                    $ticket['Ticket']['wsTicketRequest']["FareRule"]["WSFareRule"][$i]["Source"] = $data->rest->FareRule->WSFareRule[$i]->Source;
                }
            }

            /*---*/

            $ticket['Ticket']['wsTicketRequest']["Fare"]["BaseFare"] = $data->rest->Fare->BaseFare;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["Tax"] = $data->rest->Fare->Tax;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AdditionalTxnFee"] = $data->rest->Fare->AdditionalTxnFee;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AgentCommission"] = $data->rest->Fare->AgentCommission;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TdsOnCommission"] = $data->rest->Fare->TdsOnCommission;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["IncentiveEarned"] = $data->rest->Fare->IncentiveEarned;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TdsOnIncentive"] = $data->rest->Fare->TdsOnIncentive;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["PLBEarned"] = $data->rest->Fare->PLBEarned;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TdsOnPLB"] = $data->rest->Fare->TdsOnPLB;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["PublishedPrice"] = $data->rest->Fare->PublishedPrice;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AirTransFee"] = $data->rest->Fare->AirTransFee;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["Currency"] = $data->rest->Fare->Currency;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["Discount"] = $data->rest->Fare->Discount;
            if (!is_array($data->rest->Fare->ChargeBU->ChargeBreakUp)) {
                $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"]["PriceId"] = $data->rest->Fare->ChargeBU->ChargeBreakUp->PriceId;
                $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"]["ChargeType"] = $data->rest->Fare->ChargeBU->ChargeBreakUp->ChargeType;
                $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"]["Amount"] = $data->rest->Fare->ChargeBU->ChargeBreakUp->Amount;
            }
            else {
                for ($i = 0; $i < count($data->rest->Fare->ChargeBU->ChargeBreakUp); $i++) {
                    $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"][$i]["PriceId"] = $data->rest->Fare->ChargeBU->ChargeBreakUp[$i]->PriceId;
                    $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"][$i]["ChargeType"] = $data->rest->Fare->ChargeBU->ChargeBreakUp[$i]->ChargeType;
                    $ticket['Ticket']['wsTicketRequest']["Fare"]["ChargeBU"]["ChargeBreakUp"][$i]["Amount"] = $data->rest->Fare->ChargeBU->ChargeBreakUp[$i]->Amount;
                }
            }

            $ticket['Ticket']['wsTicketRequest']["Fare"]["OtherCharges"] = $data->rest->Fare->OtherCharges;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["FuelSurcharge"] = $data->rest->Fare->FuelSurcharge;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["TransactionFee"] = $data->rest->Fare->TransactionFee;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["ReverseHandlingCharge"] = $data->rest->Fare->ReverseHandlingCharge;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["OfferedFare"] = $data->rest->Fare->OfferedFare;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AgentServiceCharge"] = $data->rest->Fare->AgentServiceCharge;
            $ticket['Ticket']['wsTicketRequest']["Fare"]["AgentConvienceCharges"] = $data->rest->Fare->AgentConvienceCharges;
            /*---*/
            $i = 0;
            if ($details[0]->num_of_adults >= 1) {
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $details[0]->lead_traveller_title;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $details[0]->lead_traveller_first_name;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $details[0]->lead_traveller_last_name;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1-1-1990'));
                if (is_array($data->rest->FareBreakdown->WSPTCFare)) {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = (int)$data->rest->FareBreakdown->WSPTCFare[0]->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = (int)$data->rest->FareBreakdown->WSPTCFare[0]->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->rest->FareBreakdown->WSPTCFare[0]->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->rest->FareBreakdown->WSPTCFare[0]->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->rest->FareBreakdown->WSPTCFare[0]->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->rest->FareBreakdown->WSPTCFare[0]->AgentConvienceCharges / $details[0]->num_of_adults;
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = (int)$data->rest->FareBreakdown->WSPTCFare->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = (int)$data->rest->FareBreakdown->WSPTCFare->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->rest->FareBreakdown->WSPTCFare->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->rest->FareBreakdown->WSPTCFare->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges / $details[0]->num_of_adults;
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->rest->Fare->AgentCommission / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->rest->Fare->TdsOnCommission / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->rest->Fare->IncentiveEarned / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->rest->Fare->TdsOnIncentive / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->rest->Fare->PLBEarned / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->rest->Fare->TdsOnPLB / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->rest->Fare->PublishedPrice / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->rest->Fare->AirTransFee / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->rest->Fare->Discount / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->rest->Fare->OtherCharges / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->rest->Fare->TransactionFee / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->rest->Fare->ReverseHandlingCharge / $details[0]->num_of_adults;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->rest->Fare->OfferedFare / $details[0]->num_of_adults;
                if ($details[0]->lead_traveller_title == 'Mr' || $details[0]->lead_traveller_title == 'Master') {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = $details[0]->pass_number;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = date('c', strtotime($details[0]->pass_expiry));
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $details[0]->lead_traveller_mobile;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
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
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $det['a_t'][$i - 1];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $det['a_f'][$i - 1];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $det['a_l'][$i - 1];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Adult";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime('1-1-1990'));
                if (is_array($data->rest->FareBreakdown->WSPTCFare)) {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = (int)$data->rest->FareBreakdown->WSPTCFare[0]->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = (int)$data->rest->FareBreakdown->WSPTCFare[0]->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->rest->FareBreakdown->WSPTCFare[0]->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->rest->FareBreakdown->WSPTCFare[0]->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->rest->FareBreakdown->WSPTCFare[0]->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->rest->FareBreakdown->WSPTCFare[0]->AgentConvienceCharges / $details[0]->num_of_adults;
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = (int)$data->rest->FareBreakdown->WSPTCFare->BaseFare / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = (int)$data->rest->FareBreakdown->WSPTCFare->Tax / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->rest->FareBreakdown->WSPTCFare->AdditionalTxnFee / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->rest->FareBreakdown->WSPTCFare->FuelSurcharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->rest->FareBreakdown->WSPTCFare->AgentServiceCharge / $details[0]->num_of_adults;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->rest->FareBreakdown->WSPTCFare->AgentConvienceCharges / $details[0]->num_of_adults;
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->rest->Fare->Discount;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->rest->Fare->OtherCharges;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->rest->Fare->AgentCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->rest->Fare->TdsOnCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->rest->Fare->IncentiveEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->rest->Fare->TdsOnIncentive;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->rest->Fare->PLBEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->rest->Fare->TdsOnPLB;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->rest->Fare->PublishedPrice;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->rest->Fare->AirTransFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->rest->Fare->TransactionFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->rest->Fare->ReverseHandlingCharge;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->rest->Fare->OfferedFare;
                if ($det['a_t'][$i - 1] == 'Mr' || $det['a_t'][$i - 1] == 'Master') {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = $det['a_p_n'][$i-1];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = date('c', strtotime($det['a_p_e'][$i-1]));
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $details[0]->lead_traveller_mobile;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
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
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $det['c_t'][$j];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $det['c_f'][$j];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $det['c_l'][$j];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Child";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($det['c_d'][$j]));
                
                if (count($data->rest->FareBreakdown->WSPTCFare) >=2 && $data->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Child") {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = (int)$data->rest->FareBreakdown->WSPTCFare[1]->BaseFare / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = (int)$data->rest->FareBreakdown->WSPTCFare[1]->Tax / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->rest->FareBreakdown->WSPTCFare[1]->AdditionalTxnFee / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->rest->FareBreakdown->WSPTCFare[1]->FuelSurcharge / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->rest->FareBreakdown->WSPTCFare[1]->AgentServiceCharge / $details[0]->num_of_children;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->rest->FareBreakdown->WSPTCFare[1]->AgentConvienceCharges / $details[0]->num_of_children;
                
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->rest->Fare->AgentCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->rest->Fare->TdsOnCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->rest->Fare->IncentiveEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->rest->Fare->TdsOnIncentive;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->rest->Fare->PLBEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->rest->Fare->TdsOnPLB;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->rest->Fare->PublishedPrice;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->rest->Fare->AirTransFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->rest->Fare->Discount;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->rest->Fare->OtherCharges;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->rest->Fare->TransactionFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->rest->Fare->ReverseHandlingCharge;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->rest->Fare->OfferedFare;
                if ($det['c_t'][$j] == 'Mr' || $det['c_t'][$j] == 'Master') {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = $det['c_p_n'][$j];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = date('c', strtotime($det['c_p_e'][$j]));
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $details[0]->lead_traveller_mobile;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $details[0]->lead_traveller_email;
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
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Title"] = $det['i_t'][$j];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["FirstName"] = $det['i_f'][$j];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["LastName"] = $det['i_l'][$j];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Type"] = "Infant";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["DateOfBirth"] = date('c',strtotime($det['i_d'][$j]));

                if (count($data->rest->FareBreakdown->WSPTCFare) == 3 && $data->rest->FareBreakdown->WSPTCFare[2]->PassengerType == "Infant") {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = (int)$data->rest->FareBreakdown->WSPTCFare[2]->BaseFare / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = (int)$data->rest->FareBreakdown->WSPTCFare[2]->Tax / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->rest->FareBreakdown->WSPTCFare[2]->AdditionalTxnFee / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->rest->FareBreakdown->WSPTCFare[2]->FuelSurcharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->rest->FareBreakdown->WSPTCFare[2]->AgentServiceCharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->rest->FareBreakdown->WSPTCFare[2]->AgentConvienceCharges / $details[0]->num_of_infants;
                }
                if (count($data->rest->FareBreakdown->WSPTCFare) == 2 && $data->rest->FareBreakdown->WSPTCFare[1]->PassengerType == "Infant"){
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["BaseFare"] = (int)$data->rest->FareBreakdown->WSPTCFare[1]->BaseFare / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Tax"] = (int)$data->rest->FareBreakdown->WSPTCFare[1]->Tax / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AdditionalTxnFee"] = $data->rest->FareBreakdown->WSPTCFare[1]->AdditionalTxnFee / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["FuelSurcharge"] = $data->rest->FareBreakdown->WSPTCFare[1]->FuelSurcharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentServiceCharge"] = $data->rest->FareBreakdown->WSPTCFare[1]->AgentServiceCharge / $details[0]->num_of_infants;
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentConvienceCharges"] = $data->rest->FareBreakdown->WSPTCFare[1]->AgentConvienceCharges / $details[0]->num_of_infants;   
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ServiceTax"] = $data->rest->Fare->ServiceTax;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AgentCommission"] = $data->rest->Fare->AgentCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnCommission"] = $data->rest->Fare->TdsOnCommission;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["IncentiveEarned"] = $data->rest->Fare->IncentiveEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnIncentive"] = $data->rest->Fare->TdsOnIncentive;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PLBEarned"] = $data->rest->Fare->PLBEarned;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TdsOnPLB"] = $data->rest->Fare->TdsOnPLB;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["PublishedPrice"] = $data->rest->Fare->PublishedPrice;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["AirTransFee"] = $data->rest->Fare->AirTransFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["Discount"] = $data->rest->Fare->Discount;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OtherCharges"] = $data->rest->Fare->OtherCharges;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["TransactionFee"] = $data->rest->Fare->TransactionFee;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["ReverseHandlingCharge"] = $data->rest->Fare->ReverseHandlingCharge;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Fare"]["OfferedFare"] = $data->rest->Fare->OfferedFare;
                if ($det['i_t'][$j] == 'Mr' || $det['i_t'][$j] == 'Master') {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Male";
                }
                else {
                    $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Gender"] = "Female";
                }

                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportNumber"] = $det['i_p_n'][$j];
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["PassportExpiry"] = date('c', strtotime($det['i_p_e'][$j]));
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Pincode"] = "";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Country"] = "IN";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Phone"] = $details[0]->lead_traveller_mobile;
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["AddressLine1"] = "#37, 2nd cross, s.k.garden, bangalore - 560043";
                $ticket["Ticket"]["wsTicketRequest"]["Passenger"]["WSPassenger"][$i]["Email"] = $details[0]->lead_traveller_email;
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
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["Amount"] = $data->rest->Fare->OfferedFare;
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["IPAddress"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["TrackId"] = 0;
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentGateway"] = "APICustomer";
            $ticket["Ticket"]["wsTicketRequest"]["PaymentInformation"]["PaymentModeType"] = "Deposited";
            /*---*/
            $ticket["Ticket"]["wsTicketRequest"]["Source"] = $data->rest->Source;
            if ($in_out == 0) {
                $ticket["Ticket"]["wsTicketRequest"]["SessionId"] = $_SESSION['outbound_id'];
            }
            else {
                $ticket["Ticket"]["wsTicketRequest"]["SessionId"] = $_SESSION['inbound_id'];
            }

            $ticket["Ticket"]["wsTicketRequest"]["IsOneWayBooking"] = "FALSE";
            $ticket["Ticket"]["wsTicketRequest"]["CorporateCode"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["TourCode"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["Endorsement"] = "";
            $ticket["Ticket"]["wsTicketRequest"]["PromotionalPlanType"] = "Normal";     
                    
            return $ticket;
        }
    }
?>