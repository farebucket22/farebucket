<?php 
@session_start();
$cust_support_data = cust_support_helper();
?>
<style>
	#ticket_header,#city_1,#city_2{
		font-size:20px;
		margin-top:10px;
	}
	.direction{
		margin-top:15px;
		
	}
	.ticket_link{
		font-size:15px;
		padding: 10px;
	}
	a:hover{
		text-decoration: none;
		cursor: pointer;
	}
	#cancel{
		color: black;
	}
	.ticket_cancel{
		font-size:15px;
		padding: 10px;
	}
	.yourTicketHeader{
		margin-top: 35px;
		margin-bottom: 35px;
	}
	.img-container{
		border-bottom: 1px solid #ddd;
		padding-bottom: 15px;
	}
	.calendar{
		margin: 0 auto;
		height:20px;
		width:20px;
		background: url( '../../../img/calendar_icon.png' ) no-repeat center center;
	}
	.hotel-bg{
		margin: 0 auto;
		height:10px;
		width:10px;
		background: url( '../../../img/hotelIcon.png' ) no-repeat center center;
		background-size: cover;
	}
</style>
<body>
	<div class="wrap">
		<div class="container clear-top">
			<?php for($i=0 ; $i<count($data); $i++):?>
			<div class="row">
				<div class="col-lg-24 img-container">
					<h4 class="col-xs-24 booking-header center-align-text">Your Booking has been confirmed.</h4>
				    	<div class="col-xs-24 booking-sub-header center-align-text">Please find your booking details below</div>
				    <div class="col-lg-4">
						<div class="row">
							<div class="srcDest center-align-text form-padding" id="origin"><?php echo $data[$i]['destination'];?></div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="row">
							<div class="srcDest center-align-text form-padding" id="destination"><?php echo $data[$i]['hotel_name'];?></div>
						</div>
					</div>
					<div class="col-lg-3 center-align-text">
						<div class="row">
							<img height="30px" width="30px" src="<?php echo base_url('img/hotelIcon.png');?>"/>
						</div>
						<div class="row"><?php echo 'Room(s):'.$data[$i]['room_count'];?></div>
					</div>
					<div class="col-lg-6 center-align-text">
						<div class="row">
							<div id="journey_date"><b>CheckIn :  </b><?php if(count($data)==1){echo date("d M Y",strtotime($data[$i]['check_in']));}else{echo date("d M Y",strtotime(str_replace("/","-",$data[$i]['check_in'])));}?></div>
						</div>
						<div class="row">
							<div id="journey_date"><b>CheckOut : </b><?php if(count($data)==1){echo date("d M Y",strtotime($data[$i]['check_out']));}else{echo date("d M Y",strtotime(str_replace("/","-",$data[$i]['check_out'])));}?></div>
						</div>
					</div>
				    <div class="col-lg-3 ticket_link pull-right"> 
						<a target="_blank" class="btn btn-change" href="<?php echo site_url('new_request/get_booking_details?booking_ref='.$data[$i]['BookingRefNo'].'&confirm_no='.$data[$i]['ConfirmationNo']);?>">Ticket</a>
				    </div>
				</div>
			</div>
		<?php endfor;?>
			<div class="row customerSupportInfo">
				<div class="col-lg-offset-8  col-lg-8 addnInfoBorder">
					<center><h6>For any queries or clarifications, contact Customer Support:</h6></center>
					<table class="table table-default">
						<tr>
							<td class="h4 left-text">Phone:</td>
							<td class="h4 right-text"><?php echo $cust_support_data->phone_number;?></td>
						</tr>
						<tr>
							<td class="h4 left-text">Email ID:</td>
							<td class="h4 right-text"><?php echo $cust_support_data->email;?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>