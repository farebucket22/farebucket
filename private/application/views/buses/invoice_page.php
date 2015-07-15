<?php
	$oneDay=24*60;
	$noOfDays = floor($data->pickupTime / $oneDay);
	$time = ($data->pickupTime) % $oneDay;
	$hours = floor($time/60);
	$minutes = floor($time%60);
	if($hours < 10) $hours = '0'.$hours;
	if($minutes < 10) $minutes = '0'.$minutes;
	$no_pax = count($data->inventoryItems);
?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Farebucket | Buses</title>
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
					<h3>Farebucket Booking ID - <span> <?php echo $_GET['booking_number']; ?></span></h3>
					<h4>PNR - <span><?php echo $data->pnr;?></span></h4>
					<h5>Booking Date - <span><?php echo date('d M Y', strtotime($data->dateOfIssue)); ?></span></h5>
				</div>
			</div>
			<div class="row">
				<h4>Itinerary</h4>
				<div class="row intin-container">
					<h3><?php echo $data->travels;?></h3>
					<h3><span class="h3">&nbsp;|&nbsp;<?php echo $source;?></span>-&nbsp;<span class="h3"><?php echo $destination;?></span></h3>
					<h3>&nbsp;|&nbsp;<?php echo date('l, d M Y', strtotime($data->doj));?>&nbsp;<span class="h3"><?php echo $hours.":".$minutes." Hrs";?></span></h3>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-9">
					<span class="tckt-dep">Bus Info</span>
					<h5><b>Bus Type:</b> <?php echo $data->busType;?></h5>
					<h5><b>Bus Operator:</b> <?php echo $data->travels;?></h5>
					<h5><b>Contact:</b> <?php echo $data->pickUpContactNo;?></h5>

					<div class="col-lg-18 remove-padding">
						<h5><b>Boarding Point:</b> <?php echo $data->pickupLocation;?></h5>
						<h5><b>Landmark:</b> <?php echo $data->pickupLocationLandmark;?></h5>
						<h5><b>Address:</b> <?php echo $data->pickUpLocationAddress;?></h5>
					</div>
				</div>
				<div class="col-lg-10">
					<div class="col-lg-24 center-align-text"><span class="tckt-dep">Journey</span></div>
					<div class="col-lg-12 center-align-text">
						<span class="h5"><b>Departure</b></span>
						<h4><?php echo $source;?></h4>
						<h6 class="remove-margin"><?php echo date('d M Y', strtotime($data->doj));?></h6>
					</div>
					<div class="col-lg-12 center-align-text">
						<span class="h5"><b>Arrival</b></span>
						<h4><?php echo $destination;?></h4>
						<h6 class="remove-margin"><?php echo date('d M Y', strtotime($data->doj));?></h6>
					</div>
				</div>
				<div class="col-lg-5">
				<div class="col-lg-24 center-align-text"><span class="tckt-dep">Fare</span></div>
					<?php if(is_array($data->inventoryItems)):?>
						<table class="col-lg-24">
							<tbody>
								<?php $iter = 1; foreach($data->inventoryItems as $im):?>
								<tr>
									<td class="left-text"><span class='h5'><b>Base Fare <?php echo $iter?></b></span></td>
									<td class="right-text"><h4><?php echo $im->fare;?></h4></td>
								</tr>
								<?php $iter++; endforeach;?>
							</tbody>
						</table>
					<?php else:?>
						<table class="col-lg-24">
							<tbody>
								<tr>
									<td class="left-text"><span class='h5'><b>Base Fare</b></span></td>
									<td class="right-text"><h4><?php echo $data->inventoryItems->fare;?></h4></td>
								</tr>
								<tr>
									<td class="left-text"><span class='h5'><b>Service Tax</b></span></td>
									<td class="right-text"><h4>0</h4></td>
								</tr>
							</tbody>
						</table>
					<?php endif;?>
				</div>
			</div>

			<div class="row passenger-list">
				<?php if(is_array($data->inventoryItems)):?>
					<?php foreach($data->inventoryItems as $im):?>
						<?php if($im->passenger->primary == 'true'):?>
							<div class="col-lg-8 passenger-headers">
								<h5><b>Passenger Name</b></h5>
								<p><?php echo $im->passenger->title;?>. <?php echo $im->passenger->name;?></p>
							</div>
							<div class="col-lg-4 passenger-headers">
								<h5><b>No of Occupants</b></h5>
								<p><?php echo $no_pax?></p>
							</div>
							<div class="col-lg-6 passenger-headers">
								<h5><b>Inventory ID</b></h5>
								<p><?php echo $data->inventoryId;?></p>
							</div>
							<div class="col-lg-6 passenger-headers">
								<h5><b>Seat Name</b></h5>
								<p><?php echo $im->seatName;?></p>
							</div>
						<?php else:?>
							<div class="col-lg-8 passenger-headers">
								<p><?php echo $im->passenger->title;?>. <?php echo $im->passenger->name;?></p>
							</div>
							<div class="col-lg-4 passenger-headers">
							</div>
							<div class="col-lg-6 passenger-headers">
								<p><?php echo $data->inventoryId;?></p>
							</div>
							<div class="col-lg-6 passenger-headers">
								<p><?php echo $im->seatName;?></p>
							</div>
						<?php endif;?>
					<?php endforeach;?>
				<?php else:?>
					<div class="col-lg-8 passenger-headers">
						<h5><b>Passenger Name</b></h5>
						<p><?php echo $data->inventoryItems->passenger->title;?>. <?php echo $data->inventoryItems->passenger->name;?></p>
					</div>
					<div class="col-lg-4 passenger-headers">
						<h5><b>No of Occupants</b></h5>
						<p>1</p>
					</div>
					<div class="col-lg-6 passenger-headers">
						<h5><b>Inventory ID</b></h5>
						<p><?php echo $data->inventoryId;?></p>
					</div>
					<div class="col-lg-6 passenger-headers">
						<h5><b>Seat Name</b></h5>
						<p><?php echo $data->inventoryItems->seatName;?></p>
					</div>
				<?php endif;?>
			</div>
			<div class="row center-align-text remove-padding">
				<button id="cmd" class="btn btn-change">Print Ticket</button>
			</div>
			<div class="row">
				<div class="col-lg-14 passenger-headers form-padding baggageInfo">
					<h3>Cancellation Policy</h3>
					<div><?php echo $_SESSION['cancelPolicy']; ?></div>
				</div>
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