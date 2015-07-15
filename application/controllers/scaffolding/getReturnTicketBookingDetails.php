<?php
	class GetReturnTicketBookingDetails{
		private $ticket_details;

		public function setReturnTicketBookingDetails($data,$ret){
			$ticket_details['GetBooking']['bookingRequest']['BookingId'] = intval($ret['booking_id']);
            $ticket_details['GetBooking']['bookingRequest']['Pnr'] = "" . $ret['pnr'];
            if (!is_array($data->rest->FareRule->WSFareRule)) 
            	$ticket_details['GetBooking']['bookingRequest']['Source'] = "" . $data->rest->FareRule->WSFareRule->Source;
            else 
            	$ticket_details['GetBooking']['bookingRequest']['Source'] = "" . $data->rest->FareRule->WSFareRule[0]->Source;
           	
            $ticket_details['GetBooking']['bookingRequest']['LastName'] = "";
            $ticket_details['GetBooking']['bookingRequest']['TicketId'] = 0;

            return $ticket_details;
		}
	}
?>