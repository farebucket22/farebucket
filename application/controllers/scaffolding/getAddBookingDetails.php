<?php
	class GetAddBookingDetails{
		private $add_booking_details;

		public function setAddBookingDetails($ticket){
			$add_booking_details = array();
	        $add_booking_details['RefId'] = intval($ticket->TicketResult->RefId);
	        $add_booking_details['BookingStatus'] = "" . $ticket->TicketResult->Status->Description;

	        return $add_booking_details;
		}
	}	
?>