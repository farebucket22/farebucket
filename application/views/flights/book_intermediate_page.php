<?php 
if(isset($passenger)){
	if( isset($passenger['kid_title_csv']) ){
		$kid_title_csv = $passenger['kid_title_csv'];
		$kid_first_name_csv = $passenger['kid_first_name_csv'];
		$kid_last_name_csv = $passenger['kid_last_name_csv'];
		$kid_dob_csv = $passenger['kid_dob_csv'];
	}
	if(isset($passenger['infant_title_csv'])){
		$infant_title_csv = $passenger['infant_title_csv'];
		$infant_first_name_csv = $passenger['infant_first_name_csv'];
		$infant_last_name_csv = $passenger['infant_last_name_csv'];
		$infant_dob_csv = $passenger['infant_dob_csv'];
	}
}
if( isset($_SESSION['user_details']) && isset($_SESSION['user_details'][0]) ){
	$userFirstName = $_SESSION['user_details'][0]->user_first_name;
	$userEmail = $_SESSION['user_details'][0]->user_email;
	$userPhone = $_SESSION['user_details'][0]->user_mobile;
}else{
	$userFirstName = $_SESSION['user_details']['user_first_name'];
	$userEmail = $_SESSION['user_details']['user_email'];
	$userPhone = "123456786";
}
?>
<div class="wrap">
	<div class="container-fluid clear-top main">
		<form action="<?php echo site_url('flights/payment_gateway');?>" method='POST' id="payment_form" style="display:none;">
			<input name='key' type='text' value='LcXB2s' />
			<input name='call_func' type='text' value='flight_oneway' />
			<input name='ticket_id' type='text' value='' />
			<input name='amount' type='text' value='<?php echo $_SESSION['onewayFlightTravellerData']['total_fare_field'];?>' />
			<input name='firstname' type='text' value='<?php echo $userFirstName;?>' />
			<input name='email' type='text' value='<?php echo $userEmail;?>' />
			<input name='phone' type='text' value='<?php echo $userPhone;?>' />
			<input name='productinfo' type='text' value='ticket to <?php echo $data['destination'];?>' />
		</form>
		<div class="row">
			<div class="col-lg-24 vam">
				<center><span class="h3">Please wait... This may take a moment.</span></center>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-24">
				<div class="spinner" style="margin: 10px auto;">
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
			var date = "<?php echo $data['travel_date'];?>";

			var set_parameters = 
			{
				is_one_way: "1",
				num_of_city: "1",
				total_fare: "<?php echo $_SESSION['onewayFlightTravellerData']['total_fare_field'];?>",
				pnr: "<?php echo $data['pnr'];?>", 
				source: "<?php echo $data['origin'];?>",
				destination: "<?php echo $data['destination'];?>",
				src_airport_name: "<?php if(count($data['booking_details']) > 1) {echo $data['booking_details'][0]['Origin']['AirportName'];}else {echo $data['booking_details'][0]['Origin']['AirportName'];};?>",
				dest_airport_name: "<?php if(count($data['booking_details']) > 1) {echo $data['booking_details'][0]['Destination']['AirportName'];}else {echo $data['booking_details'][count($data['booking_details']) - 1]['Destination']['AirportName'];};?>",
				airline_name: "<?php echo $data['airline_name_field'];?>", 
				date: date,
				arrival_time: "<?php echo $data['to_field'];?>",
				departure_time: "<?php echo $data['from_field'];?>", 
				flight_duration: "<?php echo $data['flight_duration_field'];?>",
				status: "<?php echo $data['status'];?>", 
				lead_traveller_title: "<?php echo $passengerData['lead_adult_title'];?>", 
				lead_traveller_first_name: "<?php echo $passengerData['lead_adult_first_name'];?>", 
				lead_traveller_last_name: "<?php echo $passengerData['lead_adult_last_name'];?>", 
				lead_traveller_email: "<?php echo $passengerData['lead_adult_email_id'];?>", 
				lead_traveller_mobile: "<?php echo $passengerData['lead_adult_mobile_no'];?>",
				pass_number: "<?php echo $passengerData['pass_number'];?>",
				pass_expiry: "<?php echo $passengerData['pass_expiry'] ?>",
				adult_travellers_titles: "<?php if($data['adult_count_field'] > 1){ echo $passenger['adult_title_csv']; }else{echo '';};?>",
				adult_travellers_first_names: "<?php if($data['adult_count_field'] > 1){echo $passenger['adult_first_name_csv'];}else{echo '';};?>",
				adult_travellers_last_names: "<?php if($data['adult_count_field'] > 1){echo $passenger['adult_last_name_csv'];}else{echo '';};?>",
				adult_pass_number: "<?php if($data['adult_count_field'] > 1) { echo $passenger['pass_number_a'];} else { echo '';}; ?>",
				adult_pass_expiry: "<?php if($data['adult_count_field'] > 1) { echo $passenger['pass_expiry_a'];} else { echo '';}; ?>",
				child_travellers_titles: "<?php if($data['youth_count_field']){echo $kid_title_csv;}else{echo '';};?>",
				child_travellers_first_names: "<?php if($data['youth_count_field']){echo $kid_first_name_csv;}else{echo '';};?>",
				child_travellers_last_names: "<?php if($data['youth_count_field']){echo $kid_last_name_csv;}else{echo '';};?>",
				child_travellers_dobs: "<?php if($data['youth_count_field']){echo $kid_dob_csv;}else{echo '';};?>",
				child_pass_number: "<?php if($data['youth_count_field']) { echo $passenger['pass_number_k'];} else { echo '';}; ?>",
				child_pass_expiry: "<?php if($data['youth_count_field']) { echo $passenger['pass_expiry_k'];} else { echo '';}; ?>",
				infant_travellers_titles: "<?php if($data['kids_count_field']){echo $infant_title_csv;}else{echo '';};?>",
				infant_travellers_first_names: "<?php if($data['kids_count_field']){echo $infant_first_name_csv;}else{echo '';};?>",
				infant_travellers_last_names: "<?php if($data['kids_count_field']){echo $infant_last_name_csv;}else{echo '';};?>",
				infant_travellers_dobs:"<?php if($data['kids_count_field']){echo $infant_dob_csv;}else{echo '';};?>",
				infant_pass_number: "<?php if($data['kids_count_field']) { echo $passenger['pass_number_i'];} else { echo ''; }; ?>",
				infant_pass_expiry: "<?php if($data['kids_count_field']) { echo $passenger['pass_expiry_i'];} else { echo ''; }; ?>",
				user_id: "<?php if( isset($_SESSION['user_details']) && isset($_SESSION['user_details'][0]) ){ echo $_SESSION['user_details'][0]->user_id;}else{ echo $_SESSION['user_details']['user_id'];}?>",
				num_of_adults: "<?php echo $data['adult_count_field'];?>", 
				num_of_children: "<?php echo $data['youth_count_field'];?>", 
				num_of_infants: "<?php echo $data['kids_count_field'];?>",
				booking_id: "<?php echo $data['booking_id'];?>",
				fb_bookingId: "<?php echo $data['fbBookingId'];?>",
				extra_info: JSON.stringify(<?php echo json_encode($data);?>)
			};	
			$.ajax({
				url: "<?php echo site_url('ticket_confirmation/set_ticket_details');?>", 
				type: "POST",
				data: { data : set_parameters }
			})
			.done(function(retData){
				if(retData != "failure"){
					$('input[name=ticket_id]').val(retData);
					$('#payment_form').submit();
				}else{
					alert('Error, please try again.');
				}
			});
		});
	</script>
</html>