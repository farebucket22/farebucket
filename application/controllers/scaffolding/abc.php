<?php
function ticket_return()
{   
    if( $_SESSION['IsDomestic'] == 0 ){
        redirect('api/flights/international_return_ticket');
    }
        $in_out = 0;
        $ticketid = $_SESSION['outbound'];
        $inout_ticketid[$in_out] = $ticketid;

        $this->load->model('flight_model');
        $getPassengerDetailsObj = new GetPassengerDetails;
        $details = $this->flight_model->getticketDetails($ticketid);

        $det = $getPassengerDetailsObj->setPassengerDetails($details);
        $data = json_decode($details[0]->extra_info);

        /**
        * Object initialisation
        */
        $flightsAPIAuthObj = new FlightsAPIAuth;
        $flightsSearchRequestObj = new FlightsSearchRequest;
        $flightsSOAPObj = new FlightsSOAP;
        $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;
        $getReturnTicketDetailsObj = new GetReturnTicketDetails;

        /**
        * Set Request Authentication Data array
        * UserName
        * Password
        */
        $flightsAPIAuthObj->setUserId("redytrip");
        $flightsAPIAuthObj->setPassword("redytrip@12");
        $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

        $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
        $flightsSOAPObj->setSOAPClient();
        $flightsSOAPObj->setSOAPHeader($authDataArr);
        $fare_breakdown = $_SESSION['outboundGFQResult']['GetFareQuoteResult']->Result;
        $ticket = $getReturnTicketDetailsObj->setReturnTicketDetails($data,$details,$in_out,$det,$fare_breakdown);

        $result = $flightsSOAPObj->makeSOAPCall("Ticket", $ticket);

            $xmlReq = $flightsSOAPObj->getLastRequest();
            $xmlResp = $flightsSOAPObj->getLastResponse();

            $file = fopen("ticketReqRetOut.xml", "w");
            fwrite($file, $xmlReq);
            fclose($file);

            $file = fopen("ticketRespRetOut.xml", "w");
            fwrite($file, $xmlResp);
            fclose($file);

        /*---------------------------------------------------------------*/
        if ($result->TicketResult->PNR != "") {
            $this->load->model('flight_model');
            $ret['pnr'] = $result->TicketResult->PNR;
            $ret['booking_id'] = $result->TicketResult->BookingId;
            $ret['status'] = "Successful";
            $this->flight_model->updateLccTicketStatus($ticketid, $ret);
            /*---get booking details*/

            $getReturnTicketBookingDetailsObj = new GetReturnTicketBookingDetails;
            $ticket_details = $getReturnTicketBookingDetailsObj->setReturnTicketBookingDetails($data,$ret);

            $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
            $flightsSOAPObj->setSOAPClient();
            $flightsSOAPObj->setSOAPHeader($authDataArr);

            $result = $flightsSOAPObj->makeSOAPCall("GetBooking", $ticket_details);

            $getReturnTicketInfoObj = new GetReturnTicketInfo;
            $info = $getReturnTicketInfoObj->setReturnTicketInfo($result,$data);
            
            $info['PayuId'] = $_SESSION['payu_id'];
            $this->load->model('flight_model');
            $ticket_booking_id = $this->flight_model->updatePayuId($info['PayuId'], $result->GetBookingResult->BookingId);
            $ticket_bookingid[$in_out] = $this->flight_model->setTicketDetails($info);
        }

        /****InBound Flight******/

        $in_out = 1;
        $ticketid = $_SESSION['inbound'];
        $inout_ticketid[$in_out] = $ticketid;

        $this->load->model('flight_model');
        $getPassengerDetailsObj = new GetPassengerDetails;
        $details = $this->flight_model->getticketDetails($ticketid);

        $det = $getPassengerDetailsObj->setPassengerDetails($details);
        $data = json_decode($details[0]->extra_info);

        /**
        * Object initialisation
        */
        $flightsAPIAuthObj = new FlightsAPIAuth;
        $flightsSearchRequestObj = new FlightsSearchRequest;
        $flightsSOAPObj = new FlightsSOAP;
        $flightsAPISearchResponseHandlerObj = new FlightsAPISearchResponseHandler;
        $getReturnTicketDetailsObj = new GetReturnTicketDetails;

        /**
        * Set Request Authentication Data array
        * UserName
        * Password
        */
        $flightsAPIAuthObj->setUserId("redytrip");
        $flightsAPIAuthObj->setPassword("redytrip@12");
        $authDataArr = array('UserName' => $flightsAPIAuthObj->getUserId(), 'Password' => $flightsAPIAuthObj->getPassword());

        $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
        $flightsSOAPObj->setSOAPClient();
        $flightsSOAPObj->setSOAPHeader($authDataArr);

		$fare_breakdown = $_SESSION['outboundGFQResult']['GetFareQuoteResult']->Result;
        $ticket = $getReturnTicketDetailsObj->setReturnTicketDetails($data,$details,$in_out,$det,$fare_breakdown);

        $result = $flightsSOAPObj->makeSOAPCall("Ticket", $ticket);
		
        /*---------------------------------------------------------------*/
        if ($result->TicketResult->PNR != "") {
            $this->load->model('flight_model');
            $ret['pnr'] = $result->TicketResult->PNR;
            $ret['booking_id'] = $result->TicketResult->BookingId;
            $ret['status'] = "Successful";
            $this->flight_model->updateLccTicketStatus($ticketid, $ret);
            /*---get booking details*/

            $getReturnTicketBookingDetailsObj = new GetReturnTicketBookingDetails;
            $ticket_details = $getReturnTicketBookingDetailsObj->setReturnTicketBookingDetails($data,$ret);

            $flightsSOAPObj->setSOAPUrl("http://api.tektravels.com/tboapi_v7/service.asmx?wsdl");
            $flightsSOAPObj->setSOAPClient();
            $flightsSOAPObj->setSOAPHeader($authDataArr);

            $result = $flightsSOAPObj->makeSOAPCall("GetBooking", $ticket_details);

            $getReturnTicketInfoObj = new GetReturnTicketInfo;
            $info = $getReturnTicketInfoObj->setReturnTicketInfo($result,$data);
            
            $info['PayuId'] = $_SESSION['payu_id'];
            $this->load->model('flight_model');
            $ticket_booking_id = $this->flight_model->updatePayuId($info['PayuId'], $result->GetBookingResult->BookingId);
            $ticket_bookingid[$in_out] = $this->flight_model->setTicketDetails($info);
        }


   // }
    $this->ticket_details($ticket_bookingid);
}
?>
