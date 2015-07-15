<?php
	class GetReturnTicketInfo{
		private $info;

		public function setReturnTicketInfo($result,$data){
			$info['Source'] = $data->rest->Source;
	        $info['BookingId'] = $result->GetBookingResult->BookingId;
	        $i=0;
	        $info['BaggageInfo'] = $result->GetBookingResult->Ticket->WSTicket->SegmentAdditionalInfo->WSSegAdditionalInfo[0]->Baggage;
	        $info['PNR'] = $result->GetBookingResult->PNR;
	        $info['Source'] = $data->rest->Source;
	        $fare_obj = $result->GetBookingResult->Fare;
	        $info['TotalFare'] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
	        $info['FareBreakdown'] = json_encode($result->GetBookingResult->Fare);
	        if (is_array($result->GetBookingResult->Passenger->WSPassenger)) {
	            $info['LeadTitle'] = $result->GetBookingResult->Passenger->WSPassenger[0]->Title;
	            $info['LeadFirstName'] = $result->GetBookingResult->Passenger->WSPassenger[0]->FirstName;
	            $info['LeadLastName'] = $result->GetBookingResult->Passenger->WSPassenger[0]->LastName;
	            $info['LeadGender'] = $result->GetBookingResult->Passenger->WSPassenger[0]->Gender;
	            $info['LeadEmail'] = $result->GetBookingResult->Passenger->WSPassenger[0]->Email;
	            $iter = 0;
	            foreach($result->GetBookingResult->Passenger->WSPassenger as $ws) {
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
	            $info['LeadTitle'] = $result->GetBookingResult->Passenger->WSPassenger->Title;
	            $info['LeadFirstName'] = $result->GetBookingResult->Passenger->WSPassenger->FirstName;
	            $info['LeadLastName'] = $result->GetBookingResult->Passenger->WSPassenger->LastName;
	            $info['LeadGender'] = $result->GetBookingResult->Passenger->WSPassenger->Gender;
	            $info['LeadEmail'] = $result->GetBookingResult->Passenger->WSPassenger->Email;
	            $info['Title'] = $result->GetBookingResult->Passenger->WSPassenger->Title;
	            $info['FirstName'] = $result->GetBookingResult->Passenger->WSPassenger->FirstName;
	            $info['LastName'] = $result->GetBookingResult->Passenger->WSPassenger->LastName;
	            $info['Gender'] = $result->GetBookingResult->Passenger->WSPassenger->Gender;
	            $info['Type'] = $result->GetBookingResult->Passenger->WSPassenger->Type;
	            $fare_obj = $result->GetBookingResult->Passenger->WSPassenger->Fare;
	            $info['IndividualFare'] = $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->BaseFare + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
	        }

	        $info['Origin'] = $result->GetBookingResult->Origin;
	        $info['Destination'] = $result->GetBookingResult->Destination;
	        if (is_array($result->GetBookingResult->Segment->WSSegment)) {
	            $r = 0;
	            foreach($result->GetBookingResult->Segment->WSSegment as $wss) {
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
	            $info['ConnectingDepTime'] = $result->GetBookingResult->Segment->WSSegment->DepTIme;
	            $info['ConnectingArrTime'] = $result->GetBookingResult->Segment->WSSegment->ArrTime;
	            $info['ConnectingAirlineName'] = $result->GetBookingResult->Segment->WSSegment->Airline->AirlineName;
	            $info['ConnectingAirlineCode'] = $result->GetBookingResult->Segment->WSSegment->Airline->AirlineCode;
	            $info['OriginCityCode'] = $result->GetBookingResult->Segment->WSSegment->Origin->CityCode;
	            $info['OriginCityName'] = $result->GetBookingResult->Segment->WSSegment->Origin->CityName;
	            $info['DestinationCityCode'] = $result->GetBookingResult->Segment->WSSegment->Destination->CityCode;
	            $info['DestinationCityName'] = $result->GetBookingResult->Segment->WSSegment->Destination->CityName;
	        }

	        if (is_array($result->GetBookingResult->Ticket->WSTicket)) {
	            $t = 0;
	            foreach($result->GetBookingResult->Ticket->WSTicket as $wst) {
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
	            $info['TicketId'] = $result->GetBookingResult->Ticket->WSTicket->TicketId;
	            $info['TicketNumber'] = trim($result->GetBookingResult->Ticket->WSTicket->TicketNumber, ' ');
	            $info['IssueDate'] = $result->GetBookingResult->Ticket->WSTicket->IssueDate;
	        }
	        return $info;

		}
	}
?>
