<?php 
$refund = '';
$data1[0]->extra_info = json_decode($data1[0]->extra_info,true);
//print_r($data[0]->ConnectingCityName);
if(empty($data[0]->ConnectingCityName))
{
	//print_r('fail');die;
	$stop_count = 0;
}
else
{
	
	$split_city = explode(",",$data[0]->ConnectingCityName);
	$stop_count = count($split_city)-2;
	$check = count($split_city);
	//print_r($check);die;
}
$origin_city = explode(",",$data[0]->ConnectingCityName);
$origin_code = explode(",",$data[0]->ConnectingCityCode);
$con_dep = explode(",",$data[0]->ConnectingDepTime);
$dest_city = explode(",",$data[0]->DestinationCityName);
$dest_code = explode(",",$data[0]->DestinationCityCode);
$con_arr = explode(",",$data[0]->ConnectingArrTime);

if( isset( $data1[0]->extra_info['booking_details'] ) ){
	$length = count($data1[0]->extra_info['booking_details']);
}else if( isset( $data1[0]->extra_info['booking_details']['rest']) ){
	$length = count($data1[0]->extra_info['booking_details']['rest']['Segment']['WSSegment']);
}else{
	$length = count($data1[0]->extra_info['rest']['Segment']['WSSegment']);	
}

//print_r($data1[0]->extra_info);die;?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Farebucket | Flights</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('img/favicon.ico')?>">
		<link rel="stylesheet" href="<?php echo base_url('css/bootstrap24.min.css'); ?>" media="all">
		<link rel="stylesheet" href="<?php echo base_url('css/bootstrap-theme.min.css'); ?>" media="all">
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300|Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo base_url('css/main.css'); ?>" media="all">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="<?php echo base_url();?>js/vendor/bootstrap.min.js"></script>
</head>
<style>
	.etckt-container{
		text-align: right;
	}

	.intin-container{
		border-top: 2px solid #27ae60;
	}

	.intin-container h3{
		float:left;
	}

	.tckt-dep{
		font-style: italic;
	}

	.passenger-list{
		padding: 25px;
		outline: 4px solid #27ae60;
	}

	.passenger-headers h6{
		font-size: 13px;
		font-weight: bold;
	}

	body {
		margin: 0;
		padding: 0;
		background-color: #FAFAFA;
		font: 12pt "Tahoma";
	}

	*{
		box-sizing: border-box;
		-moz-box-sizing: border-box;
	}

	.wrap {
		padding: 1cm;
		height: 237mm;
	}

	@page {
		size: A4;
		margin: 0;
	}

	@media print {

		.etckt-container{
			text-align: right;
		}

		.intin-container{
			border-top: 2px solid #27ae60;
		}

		.intin-container h3{
			float:left;
		}

		.tckt-dep{
			font-style: italic;
		}

		.passenger-list{
			padding: 25px;
			outline: 4px solid #27ae60;
		}

		.passenger-headers h6{
			font-size: 13px;
			font-weight: bold;
		}

		.col-lg-offset-10{
			margin-left: 41.66666666666667%
		}

		.col-lg-offset-4{
			margin-left: 16.66666666666667%
		}

		.col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-13, .col-lg-14, .col-lg-15, .col-lg-16, .col-lg-17, .col-lg-18, .col-lg-19, .col-lg-20, .col-lg-21, .col-lg-22, .col-lg-23, .col-lg-24 {
		float: left;
		}
		.col-lg-24 {
		width: 100%;
		}
		.col-lg-23 {
		width: 95.83333333333333%;
		}
		.col-lg-22 {
		width: 91.66666666666666%;
		}
		.col-lg-21 {
		width: 87.5%;
		}
		.col-lg-20 {
		width: 83.33333333333334%;
		}
		.col-lg-18 {
		width: 75%;
		}
		.col-lg-16 {
		width: 66.66666666666666%;
		}
		.col-lg-14 {
		width: 58.333333333333336%;
		}
		.col-lg-13 {
		width: 51.66666666666667%;
		}
		.col-lg-12 {
		width: 50%;
		}
		.col-lg-11 {
		width: 45.833333333333333%;
		}
		.col-lg-10 {
		width: 41.66666666666667%;
		}
		.col-lg-9 {
		width: 37.5%;
		}
		.col-lg-8 {
		width: 33.33333333333333%;
		}
		.col-lg-7 {
		width: 29.16666666666667%;
		}
		.col-lg-6 {
		width: 25%;
		}
		.col-lg-5 {
		width: 20.833333333333333%;
		}
		.col-lg-4 {
		width: 16.666666666666664%;
		}
		.col-lg-3 {
		width: 12.5%;
		}
		.col-lg-2 {
		width: 8.333333333333332%;
		}
		.col-lg-1 {
		width: 4.166666666666666%;
		}
	}

</style>
<body>
	<div class="wrap">
		<div class="container clear-top">
			<div class="row">
				<div class="col-lg-12 img-container">
					<img class="img-responsive logo" src="<?php echo base_url();?>/img/logo.png" alt="FareBucket Logo" />
				</div>
				<div class="col-lg-12 etckt-container">
					<h2>E-Ticket</h2>

					<h3>Farebucket Booking ID - <span> <?php if( $data[0]->fbBooking_id != '0' ){echo $data[0]->fbBooking_id;} else {echo "failure";}; ?></span></h3>
					<h5>Booking Date - <span><?php echo date("l, d-M-y H:i:s");?> hrs</span></h5>
				</div>
			</div>
			<div class="row">
				<h4>Itinerary</h4>
				<div class="row intin-container">
					<h3><?php if(empty($data[0]->OriginCityName)){echo $origin_city[0];}else{echo $data[0]->OriginCityName;}?></h3>
					<h3>&nbsp-&nbsp</h3>
					<h3><?php if(empty($data[0]->DestinationCityName)){echo $origin_city[$check-1];}else{echo $data[0]->DestinationCityName;}?></h3>
					<h3>&nbsp|&nbsp</h3>
					<h3><?php echo date("l, d-M-y", strtotime($con_dep[0]));?></h3>
					<h3>, <?php echo date("H:i:s", strtotime($con_dep[0]));?>&nbsp hrs</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4">
					<span class="tckt-dep">Airline</span>
					<h4><?php echo $data[0]->ConnectingAirlineName;?></h4>
				</div>
				<div class="col-lg-6">
					<h5><?php if($stop_count) {echo $stop_count.'Stop(s)';}else{echo 'Non-Stop';};?></h5>
					<span class="tckt-dep">Flight Info</span>
					<?php
					if( isset($data1[0]->extra_info['non_refundable']) ){
						if($data1[0]->extra_info['non_refundable'] == 'true'){
							$refund = "Non-Refundable";
						}else{
							$refund = "Refundable";
						} 
					} else if( isset( $data1[0]->extra_info['booking_details'] ) ){
						if($data1[0]->extra_info['booking_details']['rest']['NonRefundable']){
							$refund = "Non-Refundable";
						}else{
							$refund = "Refundable";
						}
					}else{
						if($data1[0]->extra_info['rest']['NonRefundable']){
							$refund = "Non-Refundable";
						}else{
							$refund = "Refundable";
						}
					}
					?>
					<h5><?php echo $refund;?></h5>
					<h5>Duration: <?php echo $data1[0]->flight_duration;?></h5>
				</div>
				<div class="col-lg-5">
					<span class="tckt-dep">Departure</span>
					<h4><?php if($stop_count){echo $origin_city[0];}else{echo $data[0]->OriginCityName;} if($stop_count){echo '('.$origin_code[0].')';}else{echo '('.$data[0]->OriginCityCode.')';}?></h4>
					<h6><?php if($stop_count){echo date("l, d-M-y", strtotime($con_dep[0]));}else{echo date("l, d-M-y", strtotime($data[0]->ConnectingDepTime));}?>, <?php echo date ("H:i",strtotime($con_dep[0]));?>&nbsp hrs</h6>
				</div>
				<div class="col-lg-4">
					<span class="tckt-dep">Stops</span>
					<h5><?php if($stop_count) {echo $stop_count.'Stop(s)';}else{echo 'Non-Stop';};?></h5>
				</div>
				<div class="col-lg-5">
					<span class="tckt-dep">Arrival</span>
					<h4><?php if($stop_count){echo $origin_city[1];}else{echo $data[0]->DestinationCityName;} if($stop_count){echo '('.$origin_code[1].')';}else{echo '('.$data[0]->DestinationCityCode.')';}?></h4>
					<h6><?php if($stop_count){echo date("l, d-M-y", strtotime($con_arr[0]));}else{echo date("l, d-M-y", strtotime($data[0]->ConnectingArrTime));}?>, <?php echo date ("H:i",strtotime($con_arr[0]));?>&nbsp hrs</h6>
				</div>
			</div>
			<?php for($i = 1 ; $i <= $stop_count; $i++){?>
			<div class="row">
				<div class="col-lg-offset-10  col-lg-5">
					<!-- <span class="tckt-dep">Departure</span> -->
					<h4><?php echo $origin_city[$i];echo '('.$origin_code[$i].')';?></h4>
					<h6><?php echo date("l, d-M-y", strtotime($con_dep[$i]));?>, <?php echo date ("H:i",strtotime($con_dep[$i]));?>&nbsp hrs</h6>
				</div>
				<div class="col-lg-offset-4 col-lg-5">
					<!-- <span class="tckt-dep">Arrival</span> -->
					<h4><?php echo $origin_city[$i+1];echo '('.$origin_code[$i+1].')';?></h4>
					<h6><?php echo date("l, d-M-y", strtotime($con_arr[$i]));?>, <?php echo date ("H:i",strtotime($con_arr[$i]));?>&nbsp hrs</h6>
				</div>
			</div>
			<?php }?>
			<div class="row passenger-list">

				<?php 
					if( $data1[0]->child_travellers_titles != NULL ) {
						$child_title = explode(",",($data1[0]->child_travellers_titles));
						$child_first = explode(",",($data1[0]->child_travellers_first_names));
						$child_last  = explode(",",($data1[0]->child_travellers_last_names));
					} else {
						$adult_title = $adult_first = $adult_last = NULL;
					}
				?>


				<?php 
					if($data1[0]->infant_travellers_titles != NULL) {
						$infant_title = explode(",",($data1[0]->infant_travellers_titles));
						$infant_first = explode(",",($data1[0]->infant_travellers_first_names));
						$infant_last  = explode(",",($data1[0]->infant_travellers_last_names));
					} else {
						$child_title = $child_first = $child_last = NULL;
					}
				?>
				
				<?php 
					if($data1[0]->infant_travellers_titles != NULL) {
						$infant_title = explode(",",($data1[0]->infant_travellers_titles));
						$infant_first = explode(",",($data1[0]->infant_travellers_first_names));
						$infant_last  = explode(",",($data1[0]->infant_travellers_last_names));
					} else {
						$infant_title = $infant_first = $infant_last = NULL;
					}
				?>

				<?php
					$adult_count = 0;
					$child_count = 0;
					$infant_count = 0;
					if( $adult_title ){ $adult_count = count($adult_title); } else { $adult_count = 0;};
					if( $child_title ){ $child_count = count($child_title); } else { $child_count = 0;};
					if( $infant_title ){ $infant_count = count($infant_title); } else { $infant_count = 0;};
				?>
				
				<div class="col-lg-8 passenger-headers">
					<h6>Passenger Names</h6>
						<p><?php echo $data1[0]->lead_traveller_title?> <?php echo $data1[0]->lead_traveller_first_name?> <?php echo $data1[0]->lead_traveller_last_name?></p>
					<?php for( $i=0 ; $i < $adult_count ; $i++ ){?>
						<p><?php echo $adult_title[$i];?> <?php echo $adult_first[$i];?> <?php echo $adult_last[$i];}?></p>
					<?php for( $i=0 ; $i < $child_count ; $i++ ){?>
						<p><?php echo $child_title[$i];?> <?php echo $child_first[$i];?> <?php echo $child_last[$i];}?></p>
					<?php for( $i=0 ; $i < $infant_count ; $i++ ){?>
						<p><?php echo $infant_title[$i];?> <?php echo $infant_first[$i];?> <?php echo $infant_last[$i];}?></p>
				</div>
				<div class="col-lg-4 passenger-headers">
				
					<h6>Type</h6>
					<p>Adult</p>

					<?php for($i = 0 ; $i < $adult_count ; $i++) {
						echo "<p>Adult</p>";
					};?>

					<?php for($i = 0 ; $i < $child_count ; $i++) {
						echo "<p>Child</p>";
					};?>

					<?php for($i = 0 ; $i < $infant_count ; $i++) {
						echo "<p>Infant</p>";
					};?>

				</div>
				<div class="col-lg-6 passenger-headers">
					<h6>Airline PNR</h6>
					<p><?php echo $data1[0]->pnr;?></p>
				</div>
				<div class="col-lg-6 passenger-headers">
					<h6>E-Ticket Number</h6>
					<?php 
						$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$Eticket = '';
						for ($i = 0; $i < 10; $i++)
						{
							$Eticket .= $characters[rand(0, strlen($characters) - 1)];
						}
					?>
					<p><?php echo $Eticket?></p>
				</div>
			</div>
			<div class="row center-align-text form-padding">
				<button id="cmd" class="btn btn-change">Print Ticket</button>
			</div>
			<div class="row">
				<div class="col-lg-6 passenger-headers form-padding center-align-text baggageInfo">
					<h3>Important Information</h3>
					<ul class="center-align-text">
						<li>Baggage Allowance : <?php echo $data[0]->BaggageInfo ?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
	$(document).ready(function(){
		$('#siteTitle').html('Farebucket | Invoice');
		$('#cmd').on('click', function(){
			$(this).hide();
			window.print();
		});
	});
</script>
</html>