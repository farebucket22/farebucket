<?php
	$data['details']->outbound_total_fare_field = floatval(str_replace(',', '', $data['details']->outbound_total_fare_field));
	$data['details']->outbound_total_fare_field = floatval(str_replace(',', '', $data['details']->outbound_total_fare_field));
	$total_fare_field = $data['details']->outbound_total_fare_field + $data['details']->inbound_total_fare_field;
	if( $_SESSION['IsDomestic'] == 1 ){
		$passenger['pass_number'] = '';
		$passenger['pass_expiry'] = '';
	}
?>
<!DOCTYPE html>
<div class="wrap">
	<div class="container-fluid clear-top main">
		<form action="<?php echo site_url('flights/payment_gateway');?>" method='POST' id="payment_form" style="display:none;">
			<input name='key' type='text' value='LcXB2s' />
			<input name='call_func' type='text' value='flight_return' />
			<input name='out_id' type='text' value='' />
			<input name='in_id' type='text' value='' />
			<input name='amount' type='text' value='<?php echo $total_fare_field;?>' />
			<input name='firstname' type='text' value='<?php echo $data['lead_adult_first_name'];?>' />
			<input name='email' type='text' value='<?php echo $data['lead_adult_email_id'];?>' />
			<input name='phone' type='text' value='<?php echo $data['lead_adult_mobile_no'];?>' />
			<input name='productinfo' type='text' value='return from <?php echo $data['details']->outbound_source;?> ticket to <?php echo $data['details']->outbound_destination;?>' />
		</form>
		<div class="row">
			<div class="col-lg-24 vam">
				<center><span class="h3">Please wait... This may take a moment.</span></center>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-24">
				<div class="spinner">
					<div class="rect1"></div>
					<div class="rect2"></div>
					<div class="rect3"></div>
					<div class="rect4"></div>
					<div class="rect5"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		<?php 
			if( is_array( $data['details']->outbound_booking_details->rest->Segment->WSSegment ) ){
				$out_len = count($data['details']->outbound_booking_details->rest->Segment->WSSegment);
				$airport_name_org_out = $data['details']->outbound_booking_details->rest->Segment->WSSegment[0]->Origin->AirportName;
				$airport_name_dest_out = $data['details']->outbound_booking_details->rest->Segment->WSSegment[$out_len - 1]->Destination->AirportName;
				$airline_name_out = $data['details']->outbound_booking_details->rest->Segment->WSSegment[0]->Airline->AirlineName;
				$raw_outbound_date = $data['details']->outbound_booking_details->rest->Segment->WSSegment[0]->DepTIme;
			}else if( !is_array( $data['details']->outbound_booking_details->rest->Segment->WSSegment ) ){
				$airport_name_org_out = $data['details']->outbound_booking_details->rest->Segment->WSSegment->Origin->AirportName;
				$airport_name_dest_out = $data['details']->outbound_booking_details->rest->Segment->WSSegment->Destination->AirportName;
				$airline_name_out = $data['details']->outbound_booking_details->rest->Segment->WSSegment->Airline->AirlineName;
				$raw_outbound_date = $data['details']->outbound_booking_details->rest->Segment->WSSegment->DepTIme;
			}

			if( isset($_SESSION['IsDomestic']) && $_SESSION['IsDomestic'] == 1 ){
				if( is_array( $data['details']->inbound_booking_details->rest->Segment->WSSegment ) ){
					$in_len = count($data['details']->inbound_booking_details->rest->Segment->WSSegment);
					$airport_name_org_in = $data['details']->inbound_booking_details->rest->Segment->WSSegment[0]->Origin->AirportName;
					$airport_name_dest_in = $data['details']->inbound_booking_details->rest->Segment->WSSegment[$in_len - 1]->Destination->AirportName;
					$airline_name_in = $data['details']->inbound_booking_details->rest->Segment->WSSegment[0]->Airline->AirlineName;
					$raw_inbound_date = $data['details']->inbound_booking_details->rest->Segment->WSSegment[0]->DepTIme;
				}else if( !is_array( $data['details']->inbound_booking_details->rest->Segment->WSSegment ) ){
					$airport_name_org_in = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Origin->AirportName;
					$airport_name_dest_in = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Destination->AirportName;
					$airline_name_in = $data['details']->inbound_booking_details->rest->Segment->WSSegment->Airline->AirlineName;
					$raw_inbound_date = $data['details']->inbound_booking_details->rest->Segment->WSSegment->DepTIme;
				}
			}
		?>

		var raw_outbound_date = "<?php echo $raw_outbound_date;?>";
		var outbound_date_arr = raw_outbound_date.split('T');
		var outbound_date = outbound_date_arr[0];
		<?php if( isset($_SESSION['IsDomestic']) && $_SESSION['IsDomestic'] == 1 ):?>
		var raw_inbound_date = "<?php echo $raw_inbound_date;?>";
		var inbound_date_arr = raw_inbound_date.split('T');
		var inbound_date = inbound_date_arr[0];
		<?php endif;?>		

		var set_outbound_parameters = 
		{
			is_return: "1",
			num_of_city: "1",
			total_fare: "<?php echo $data['details']->outbound_total_fare_field;?>",
			pnr: "<?php echo $data1['outbound_pnr'];?>", 
			source: "<?php echo $data['details']->outbound_source;?>",
			destination: "<?php echo $data['details']->outbound_destination;?>",
			src_airport_name: "<?php echo $airport_name_org_out;?>",
			dest_airport_name: "<?php echo $airport_name_dest_out;?>",
			airline_name: "<?php echo $airline_name_out;?>", 
			date: outbound_date,
			arrival_time: "<?php echo $data['details']->outbound_to_field;?>",
			departure_time: "<?php echo $data['details']->outbound_from_field;?>",
			flight_duration: "<?php echo $data['details']->outbound_flight_duration_field;?>",
			status: "<?php echo $data1['outbound_status'];?>", 
			lead_traveller_title: "<?php echo $data['lead_adult_title'];?>", 
			lead_traveller_first_name: "<?php echo $data['lead_adult_first_name'];?>", 
			lead_traveller_last_name: "<?php echo $data['lead_adult_last_name'];?>", 
			lead_traveller_email: "<?php echo $data['lead_adult_email_id'];?>", 
			lead_traveller_mobile: "<?php echo $data['lead_adult_mobile_no'];?>",
			pass_number: "<?php echo $passenger['pass_number'];?>",
			pass_expiry: "<?php echo $passenger['pass_expiry'] ?>",
			adult_travellers_titles: "<?php if($data['details']->adult_count_field > 1){echo $data['adult_title_csv'];}else{echo '';};?>",
			adult_travellers_first_names: "<?php if($data['details']->adult_count_field > 1){echo $data['adult_first_name_csv'];}else{echo '';};?>",
			adult_travellers_last_names: "<?php if($data['details']->adult_count_field > 1){echo $data['adult_last_name_csv'];}else{echo '';};?>",
			adult_pass_number: "<?php if($data['details']->adult_count_field > 1) { echo $passenger['pass_number_a'];} else { echo '';}; ?>",
			adult_pass_expiry: "<?php if($data['details']->adult_count_field > 1) { echo $passenger['pass_expiry_a'];} else { echo '';}; ?>",
			child_travellers_titles: "<?php if($data['details']->youth_count_field){echo $data['kid_title_csv'];}else{echo '';};?>",
			child_travellers_first_names: "<?php if($data['details']->youth_count_field){echo $data['kid_first_name_csv'];}else{echo '';};?>",
			child_travellers_last_names: "<?php if($data['details']->youth_count_field){echo $data['kid_last_name_csv'];}else{echo '';};?>",
			child_travellers_dobs: "<?php if($data['details']->youth_count_field){echo $data['kid_dob_csv'];}else{echo '';};?>",
			child_pass_number: "<?php if($data['details']->youth_count_field) { echo $passenger['pass_number_k'];} else { echo '';}; ?>",
			child_pass_expiry: "<?php if($data['details']->youth_count_field) { echo $passenger['pass_expiry_k'];} else { echo '';}; ?>",
			infant_travellers_titles: "<?php if($data['details']->kids_count_field){echo $data['infant_title_csv'];}else{echo '';};?>",
			infant_travellers_first_names: "<?php if($data['details']->kids_count_field){echo $data['infant_first_name_csv'];}else{echo '';};?>",
			infant_travellers_last_names: "<?php if($data['details']->kids_count_field){echo $data['infant_last_name_csv'];}else{echo '';};?>",
			infant_travellers_dobs:"<?php if($data['details']->kids_count_field){echo $data['infant_dob_csv'];}else{echo '';};?>",
			infant_pass_number: "<?php if($data['details']->kids_count_field) { echo $passenger['pass_number_i'];} else { echo ''; }; ?>",
			infant_pass_expiry: "<?php if($data['details']->kids_count_field) { echo $passenger['pass_expiry_i'];} else { echo ''; }; ?>",
			user_id: "<?php if( isset($_SESSION['user_details']) && isset($_SESSION['user_details'][0]) ){ echo $_SESSION['user_details'][0]->user_id;}else{ echo $_SESSION['user_details']['user_id'];}?>",
			num_of_adults: "<?php echo $data['details']->adult_count_field;?>", 
			num_of_children: "<?php echo $data['details']->youth_count_field;?>", 
			num_of_infants: "<?php echo $data['details']->kids_count_field;?>",
			booking_id: "<?php echo $data1['outbound_booking_id'];?>", 
			extra_info: JSON.stringify(<?php echo json_encode($data['details']->outbound_booking_details)?>)
		};

		<?php if( isset($_SESSION['IsDomestic']) && $_SESSION['IsDomestic'] == 1 ):?>

		var set_inbound_parameters = 
		{
			is_return: "1",
			num_of_city: "1",
			total_fare: "<?php echo $data['details']->inbound_total_fare_field;?>",
			pnr: "<?php echo $data['inbound_pnr'];?>", 
			source: "<?php echo $data['details']->inbound_source;?>",
			destination: "<?php echo $data['details']->inbound_destination;?>",
			src_airport_name: "<?php echo $airport_name_org_in;?>",
			dest_airport_name: "<?php echo $airport_name_dest_in;?>",
			airline_name: "<?php echo $airline_name_in;?>", 
			date: inbound_date,
			arrival_time: "<?php echo $data['details']->inbound_to_field;?>",
			departure_time: "<?php echo $data['details']->inbound_from_field;?>",
			flight_duration: "<?php echo $data['details']->inbound_flight_duration_field;?>",
			status: "<?php echo $data['inbound_status'];?>", 
			lead_traveller_title: "<?php echo $data['lead_adult_title'];?>", 
			lead_traveller_first_name: "<?php echo $data['lead_adult_first_name'];?>", 
			lead_traveller_last_name: "<?php echo $data['lead_adult_last_name'];?>", 
			lead_traveller_email: "<?php echo $data['lead_adult_email_id'];?>", 
			lead_traveller_mobile: "<?php echo $data['lead_adult_mobile_no'];?>",
			pass_number: "<?php echo $passenger['pass_number'];?>",
			pass_expiry: "<?php echo $passenger['pass_expiry'] ?>",
			adult_travellers_titles: "<?php if($data['details']->adult_count_field > 1){echo $data['adult_title_csv'];}else{echo '';};?>",
			adult_travellers_first_names: "<?php if($data['details']->adult_count_field > 1){echo $data['adult_first_name_csv'];}else{echo '';};?>",
			adult_travellers_last_names: "<?php if($data['details']->adult_count_field > 1){echo $data['adult_last_name_csv'];}else{echo '';};?>",
			child_travellers_titles: "<?php if($data['details']->youth_count_field){echo $data['kid_title_csv'];}else{echo '';};?>",
			child_travellers_first_names: "<?php if($data['details']->youth_count_field){echo $data['kid_first_name_csv'];}else{echo '';};?>",
			child_travellers_last_names: "<?php if($data['details']->youth_count_field){echo $data['kid_last_name_csv'];}else{echo '';};?>",
			child_travellers_dobs: "<?php if($data['details']->youth_count_field){echo $data['kid_dob_csv'];}else{echo '';};?>",
			infant_travellers_titles: "<?php if($data['details']->kids_count_field){echo $data['infant_title_csv'];}else{echo '';};?>",
			infant_travellers_first_names: "<?php if($data['details']->kids_count_field){echo $data['infant_first_name_csv'];}else{echo '';};?>",
			infant_travellers_last_names: "<?php if($data['details']->kids_count_field){echo $data['infant_last_name_csv'];}else{echo '';};?>",
			infant_travellers_dobs:"<?php if($data['details']->kids_count_field){echo $data['infant_dob_csv'];}else{echo '';};?>",
			user_id: "<?php if( isset($_SESSION['user_details']) && isset($_SESSION['user_details'][0]) ){ echo $_SESSION['user_details'][0]->user_id;}else{ echo $_SESSION['user_details']['user_id'];}?>",
			num_of_adults: "<?php echo $data['details']->adult_count_field;?>", 
			num_of_children: "<?php echo $data['details']->youth_count_field;?>", 
			num_of_infants: "<?php echo $data['details']->kids_count_field;?>",
			booking_id: "<?php echo $data['inbound_booking_id'];?>", 
			fb_bookingId: "<?php echo $data['fbBookingId']; ?>",
			extra_info: JSON.stringify(<?php echo json_encode($data['details']->inbound_booking_details)?>)
		};

		<?php endif;?>

		var outbound_tkt_id;
		var inbound_tkt_id;

		$.ajax({
			url: "<?php echo site_url('ticket_confirmation/set_ticket_details');?>",
			type: "POST",
			data: { data : set_outbound_parameters }
		})
		.done(function(retData){
			outbound_tkt_id = retData;
			<?php if( isset($_SESSION['IsDomestic']) && $_SESSION['IsDomestic'] == 1 ):?>
				$.ajax({
					url: "<?php echo site_url('ticket_confirmation/set_ticket_details');?>",
					type: "POST",
					data: { data : set_inbound_parameters }
				})
				.done(function(retData){
					inbound_tkt_id = retData;
					if(retData != "failure"){
						$('input[name=out_id]').val(outbound_tkt_id);
						$('input[name=in_id]').val(inbound_tkt_id);
						$('#payment_form').submit();
					}else{
						alert('Error, please try again.');
					}
				});

			<?php else:?>

				$('input[name=out_id]').val(outbound_tkt_id);
				$('input[name=in_id]').val(null);
				$('#payment_form').submit();

			<?php endif;?>
		})
	});
</script>
</html>