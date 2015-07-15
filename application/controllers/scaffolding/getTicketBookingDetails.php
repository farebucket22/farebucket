<?php			
	class GetTicketBookingDetails{
		private $ticket_details;

		public function setTicketBookingDetails($ret,$data){
			$ticket_details['GetBooking']['bookingRequest']['BookingId'] = intval($ret['booking_id']);
            $ticket_details['GetBooking']['bookingRequest']['Pnr'] = "" . $ret['pnr'];

            if (!is_array($data['fare_rule']['WSFareRule'])) 
            	$ticket_details['GetBooking']['bookingRequest']['Source'] = $data['Source'];
            else $ticket_details['GetBooking']['bookingRequest']['Source'] = $data['Source'];
            $ticket_details['GetBooking']['bookingRequest']['LastName'] = "";
            $ticket_details['GetBooking']['bookingRequest']['TicketId'] = 0;

            return $ticket_details;
		}
	}

			
?>