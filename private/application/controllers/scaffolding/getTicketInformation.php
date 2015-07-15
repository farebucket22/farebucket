<?php
	class GetTicketInformation{
		private $info;

		public function setTicketInformation($data, $bookingResult){

			$info['Source'] = $data['Source'];
            $info['BookingId'] = $bookingResult->GetBookingResult->BookingId;
            $info['PNR'] = $bookingResult->GetBookingResult->PNR;
            $info['Source'] = $data['Source'];
            if( is_array($bookingResult->GetBookingResult->Ticket->WSTicket->SegmentAdditionalInfo->WSSegAdditionalInfo) ){
                foreach( $bookingResult->GetBookingResult->Ticket->WSTicket->SegmentAdditionalInfo->WSSegAdditionalInfo as $sai ){
                    $bagCsv[] = $sai->Baggage;
                }
                $info['BaggageInfo'] = implode(',', $bagCsv);
            }else{
                $info['BaggageInfo'] = $bookingResult->GetBookingResult->Ticket->WSTicket->SegmentAdditionalInfo->WSSegAdditionalInfo->Baggage;
            }
            $fare_obj = $bookingResult->GetBookingResult->Fare;
            $info['TotalFare'] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
            $info['FareBreakdown'] = json_encode($bookingResult->GetBookingResult->Fare);
            if (is_array($bookingResult->GetBookingResult->Passenger->WSPassenger)) {
                $info['LeadTitle'] = $bookingResult->GetBookingResult->Passenger->WSPassenger[0]->Title;
                $info['LeadFirstName'] = $bookingResult->GetBookingResult->Passenger->WSPassenger[0]->FirstName;
                $info['LeadLastName'] = $bookingResult->GetBookingResult->Passenger->WSPassenger[0]->LastName;
                $info['LeadGender'] = $bookingResult->GetBookingResult->Passenger->WSPassenger[0]->Gender;
                $info['LeadEmail'] = $bookingResult->GetBookingResult->Passenger->WSPassenger[0]->Email;
                $iter = 0;
                foreach($bookingResult->GetBookingResult->Passenger->WSPassenger as $ws) {
                    $passengers['Title'][$iter] = $ws->Title;
                    $passengers['FirstName'][$iter] = $ws->FirstName;
                    $passengers['LastName'][$iter] = $ws->LastName;
                    $passengers['Gender'][$iter] = $ws->Gender;
                    $passengers['Type'][$iter] = $ws->Type;
                    $fare_obj = $ws->Fare;
                    $passengers['IndividualFare'][$iter] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                    $iter++;
                }

                $info['Title'] = implode(',', $passengers['Title']);
                $info['FirstName'] = implode(',', $passengers['FirstName']);
                $info['LastName'] = implode(',', $passengers['LastName']);
                $info['Gender'] = implode(',', $passengers['Gender']);
                $info['Type'] = implode(',', $passengers['Type']);
                $info['IndividualFare'] = implode(',', $passengers['IndividualFare']);
            }
            else {
                $info['LeadTitle'] = $bookingResult->GetBookingResult->Passenger->WSPassenger->Title;
                $info['LeadFirstName'] = $bookingResult->GetBookingResult->Passenger->WSPassenger->FirstName;
                $info['LeadLastName'] = $bookingResult->GetBookingResult->Passenger->WSPassenger->LastName;
                $info['LeadGender'] = $bookingResult->GetBookingResult->Passenger->WSPassenger->Gender;
                $info['LeadEmail'] = $bookingResult->GetBookingResult->Passenger->WSPassenger->Email;
                $info['Title'] = $bookingResult->GetBookingResult->Passenger->WSPassenger->Title;
                $info['FirstName'] = $bookingResult->GetBookingResult->Passenger->WSPassenger->FirstName;
                $info['LastName'] = $bookingResult->GetBookingResult->Passenger->WSPassenger->LastName;
                $info['Gender'] = $bookingResult->GetBookingResult->Passenger->WSPassenger->Gender;
                $info['Type'] = $bookingResult->GetBookingResult->Passenger->WSPassenger->Type;
                $fare_obj = $bookingResult->GetBookingResult->Passenger->WSPassenger->Fare;
                $info['IndividualFare'] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
            }

            $info['Origin'] = $bookingResult->GetBookingResult->Origin;
            $info['Destination'] = $bookingResult->GetBookingResult->Destination;
            if (is_array($bookingResult->GetBookingResult->Segment->WSSegment)) {
                $r = 0;
                foreach($bookingResult->GetBookingResult->Segment->WSSegment as $wss) {
                    $segment['ConnectingJourney'][$r] = $wss->Origin->CityCode;
                    $segment['ConnectingJourney'][$r + 1] = $wss->Destination->CityCode;
                    $segment['ConnectingDepTime'][$r] = $wss->DepTIme;
                    $segment['ConnectingArrTime'][$r] = $wss->ArrTime;
                    $segment['ConnectingAirlineName'] = $wss->Airline->AirlineName;
                    $segment['ConnectingAirlineCode'] = $wss->Airline->AirlineCode;
                    $segment['ConnectingCityName'][$r] = $wss->Origin->CityName;
                    $segment['ConnectingCityName'][$r + 1] = $wss->Destination->CityName;
                    $segment['ConnectingCityCode'][$r] = $wss->Origin->CityCode;
                    $segment['ConnectingCityCode'][$r + 1] = $wss->Destination->CityCode;
                    $r++;
                }

                $info['ConnectingJourney'] = implode(',', $segment['ConnectingJourney']);
                $info['ConnectingDepTime'] = implode(',', $segment['ConnectingDepTime']);
                $info['ConnectingArrTime'] = implode(',', $segment['ConnectingArrTime']);
                $info['ConnectingAirlineName'] = $segment['ConnectingAirlineName'];
                $info['ConnectingAirlineCode'] = $segment['ConnectingAirlineCode'];
                $info['ConnectingCityName'] = implode(',', $segment['ConnectingCityName']);
                $info['ConnectingCityCode'] = implode(',', $segment['ConnectingCityCode']);
            }
            else {
                $info['ConnectingDepTime'] = $bookingResult->GetBookingResult->Segment->WSSegment->DepTIme;
                $info['ConnectingArrTime'] = $bookingResult->GetBookingResult->Segment->WSSegment->ArrTime;
                $info['ConnectingAirlineName'] = $bookingResult->GetBookingResult->Segment->WSSegment->Airline->AirlineName;
                $info['ConnectingAirlineCode'] = $bookingResult->GetBookingResult->Segment->WSSegment->Airline->AirlineCode;
                $info['OriginCityCode'] = $bookingResult->GetBookingResult->Segment->WSSegment->Origin->CityCode;
                $info['OriginCityName'] = $bookingResult->GetBookingResult->Segment->WSSegment->Origin->CityName;
                $info['DestinationCityCode'] = $bookingResult->GetBookingResult->Segment->WSSegment->Destination->CityCode;
                $info['DestinationCityName'] = $bookingResult->GetBookingResult->Segment->WSSegment->Destination->CityName;
            }

            if (is_array($bookingResult->GetBookingResult->Ticket->WSTicket)) {
                $t = 0;
                foreach($bookingResult->GetBookingResult->Ticket->WSTicket as $wst) {
                    $tickets['TicketId'][$t] = $wst->TicketId;
                    $tickets['TicketNumber'][$t] = trim($wst->TicketNumber, ' ');
                    $tickets['IssueDate'][$t] = $wst->IssueDate;
                    $t++;
                }

                $info['TicketId'] = implode(',', $tickets['TicketId']);
                $info['TicketNumber'] = implode(',', $tickets['TicketNumber']);
                $info['IssueDate'] = implode(',', $tickets['IssueDate']);
            }
            else {
                $info['TicketId'] = $bookingResult->GetBookingResult->Ticket->WSTicket->TicketId;
                $info['TicketNumber'] = trim($bookingResult->GetBookingResult->Ticket->WSTicket->TicketNumber, ' ');
                $info['IssueDate'] = $bookingResult->GetBookingResult->Ticket->WSTicket->IssueDate;
            }
        return $info;
		}
	}
?>