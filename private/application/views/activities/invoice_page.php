<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Farebucket | Activity</title>
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
					<h3>Farebucket Booking ID - <span> <?php if( $data->booking_id != 0 )  {echo $data->fb_bookingId;} else {echo "failure";}; ?></span></h3>
					<h5>Booking Date - <span><?php echo date("l, d-m-y H:i:s");?> hrs</span></h5>
				</div>
			</div>
			<div class="row">
				<h4>Itinerary</h4>
				<div class="row intin-container">
					<h3><?php echo $data->activity_name;?></h3>
					<h3>&nbsp-&nbsp</h3>
					<h3><?php echo $data->sub_activity_name;?></h3>
					<h3>&nbsp|&nbsp</h3>
					<h3><?php echo date("l, d-m-y", strtotime($data->activity_booking_date));?></h3>
				</div>
			</div>
			<div class="row passenger-list">

				<?php 
					if( $data->adult_title_string != NULL ) {
						$adult_title = explode(",",($data->adult_title_string));
						$adult_first = explode(",",($data->adult_first_name_string));
						$adult_last  = explode(",",($data->adult_last_name_string));
					} else {
						$adult_title = $adult_first = $adult_last = NULL;
					}
				?>


				<?php 
					if( $data->child_title_string != NULL ) {
						$child_title = explode(",",($data->child_title_string));
						$child_first = explode(",",($data->child_first_name_string));
						$child_last  = explode(",",($data->child_last_name_string));
					} else {
						$child_title = $child_first = $child_last = NULL;
					}
				?>
				
				

				<?php
					$adult_count = 0;
					$child_count = 0;
					if( $adult_title ){ $adult_count = count($adult_title); } else { $adult_count = 0;};
					if( $child_title ){ $child_count = count($child_title); } else { $child_count = 0;};
				?>
				
				<div class="col-lg-8 passenger-headers">
					<h6>Passenger Names</h6>
					<?php for( $i=0 ; $i < $adult_count ; $i++ ){?>
						<p><?php echo $adult_title[$i];?> <?php echo $adult_first[$i];?> <?php echo $adult_last[$i];}?></p>
					<?php for( $i=0 ; $i < $child_count ; $i++ ){?>
						<p><?php echo $child_title[$i];?> <?php echo $child_first[$i];?> <?php echo $child_last[$i];}?></p>
				</div>
				<div class="col-lg-4 passenger-headers">
				
					<h6>Type</h6>
					<?php for($i = 0 ; $i < $adult_count ; $i++) {
						echo "<p>Adult</p>";
					};?>

					<?php for($i = 0 ; $i < $child_count ; $i++) {
						echo "<p>Child</p>";
					};?>
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
		</div>
	</div>
</body>
<script>
	$(document).ready(function(){
		$('#cmd').on('click', function(){
			$(this).hide();
			window.print();
		});
	});
</script>
</html>