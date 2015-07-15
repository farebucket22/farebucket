<?php
	$data->check_in = str_replace("/","-",$data->check_in);
	$data->check_out = str_replace("/","-",$data->check_out);
	$var1 = date(strtotime($data->check_in));
	$var2 = date(strtotime($data->check_out));
	$var3 = $var2-$var1;
	$nights = $var3/(60*60*24);
	$days = $nights + 1;
	$ne_info = json_decode($data->necessary_info);
	$policy = $_SESSION['cancelPolicy'];
?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Farebucket | Hotels</title>
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

		#cmd{
			margin-top:5px;
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
					<h3>Farebucket Booking ID - <span> <?php echo $data->fb_bookingId; ?></span></h3>
					<h5>Booking Date - <span><?php echo date('d M Y', strtotime('now')); ?></span></h5>
				</div>
			</div>
			<div class="row">
				<h4>Itinerary</h4>
				<div class="row intin-container">
					<h3><?php echo $data->destination;?></h3>
					<h3>&nbsp-&nbsp</h3>
					<h3><?php echo $data->hotel_name; ?></h3>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-9">
					<span class="tckt-dep">Hotel Name</span>
					<h4><?php echo $data->hotel_name;?></h4>
					<h6><b><?php echo $ne_info->hotel_details[0]->RoomTypeName;?></b></h6>
					<div class="col-lg-18 remove-padding">
							<h6 class='remove-margin'><?php echo $ne_info->Address;?></h6>
					</div>
				</div>
				<div class="col-lg-5">
					<span class="tckt-dep">Stay Info</span>
					<h5>Duration: </h5>
					<?php if( $nights == 1 ):?>
						<h4><?php echo $nights." Night & ".$days." Days";?></h4>
					<?php else:?>
						<h4><?php echo $nights." Nights & ".$days." Days";?></h4>
					<?php endif;?>
					<h5>No of Rooms: </h5>
					<?php if( $data->room_count == 1 ):?>
						<h4><?php echo $data->room_count." Room";?></h4>
					<?php else:?>
						<h4><?php echo $data->room_count." Rooms";?></h4>
					<?php endif;?>
				</div>
				<div class="col-lg-5">
					<span class="tckt-dep">Check-in Date</span>
					<h4><?php echo date('d M Y', strtotime($data->check_in));?></h4>
				</div>
				<div class="col-lg-5">
					<span class="tckt-dep">Check-out Date</span>
					<h4><?php echo date('d M Y', strtotime($data->check_out));?></h4>
				</div>
			</div>
			<div class="row passenger-list">
				<div class="col-lg-2 passenger-headers">
					<h6>Rooms</h6>

					<?php $room=1; foreach( $ne_info->hotel_details as $hd ):?>
						<?php $i=0; foreach( $hd->HotelPassenger as $hp ):?>
							<p><?php echo ($i == 0) ? "Room ".$room : '&nbsp;'?></p>
						<?php $i++; endforeach;?>
					<?php $room++;?>
					<?php endforeach;?>

				</div>
				<div class="col-lg-8 passenger-headers">
					<h6>Passenger Name</h6>

					<?php foreach( $ne_info->hotel_details as $hd ):?>
						<?php foreach( $hd->HotelPassenger as $hp ):?>
							<p><?php echo $hp->Title?> <?php echo $hp->FirstName?> <?php echo $hp->LastName?></p>
						<?php $i++; endforeach;?>
					<?php endforeach;?>

				</div>
				<div class="col-lg-3 passenger-headers">
					<h6>No of Occupants</h6>
					<p>1</p>
				</div>
				<div class="col-lg-4 passenger-headers">
					<h6>Booking Number</h6>
					<p><?php echo $data->BookingRefNo;?></p>
				</div>
				<div class="col-lg-4 passenger-headers">
					<h6>E-Booking Reference ID</h6>
					<p><?php echo $data->ConfirmationNo?></p>
				</div>
			</div>
			<div class="row center-align-text remove-padding">
				<button id="cmd" class="btn btn-change">Print Ticket</button>
			</div>
			<div class="row cancel-policy">
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

		var policy = <?php echo $policy; ?>;
		policy = policy.split('|');
		var policyStr = '<ul>';
        $.each(policy, function(i, val){
            policyStr += '<li>'+val.replace('INR', '&#x20B9;')+'</li>';
        }); 
        policyStr += "</ul>"
        $('.cancel-policy').html('<span class="h4">Cancellation Policy:</span> <br />'+policyStr+'</div>');
	});
</script>
</html>