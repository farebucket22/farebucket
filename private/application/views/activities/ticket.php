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
	/*.img-container{
		border-bottom: 1px solid #ddd;
		padding-bottom: 15px;
		padding-top:15px;
	}*/
	.calendar{
		margin: 0 auto;
		height:20px;
		width:20px;
		background: url( '../../img/calendar_icon.png' ) no-repeat center center;
		background-size: cover;
	}
	.act-bg{
		margin: 0 auto;
		height:45px;
		width:45px;
		background: url( '../../img/activityIcon.png' ) no-repeat center center;
		background-size: cover;
	}
	.cab_icon{
		width:45px;
	}
	/*#origin,#destination{
		font-size:12px;
	}*/
	.res-height{
		height: 45px;
	}
	
	.tb-cell{
		position: absolute;
		top:20%;
	}

</style>
<body>
	<div class="wrap">
		<div class="container clear-top">
			<?php for($i = 0; $i<1; $i++){ ?>
			<div class="row">
				<div class="col-lg-24 img-container">
					<?php if($i == 0):?>
					<h4 class="col-xs-24 booking-header center-align-text">Your Booking has been confirmed.</h4>
						<div class="col-xs-24 booking-sub-header center-align-text">Please find your booking details below</div>
					<?php endif;?>
					<div class="col-lg-2">
						<div class="act-bg"></div>
				    </div>
				    <div class="col-lg-6 res-height">
						<div class="col-lg-24 remove-padding srcDest center-align-text tb-cell" id="origin"><?php echo $act['act_name'];?></div>
					</div>
					<div class="col-lg-2 res-height center-align-text">
						<div class="row tb-cell"><?php echo $act['sub_act_name'];?></div>
					</div>
					<div class="col-lg-5 center-align-text">
						<div class="row">
							<!--add profile icon here-->
						</div>
						<div class="row res-height">
						    <div class="col-lg-12">
								<p id="adult_count">Adult:<?php echo $act['adult_count'];?></p>
							</div>
							<div class="col-lg-12">
								<p id="child_count">Child:<?php echo $act['child_count'];?></p>
							</div>
						</div>
					</div>
				    <div class="col-lg-4 res-height center-align-text">
				    	<div class="row">
				    		<div class="calendar"></div>
				    	</div>
						<div id="journey_date"><?php echo date("d M Y", strtotime($act['act_date']));?></div>
				    </div>
				    <div class="col-lg-5 res-height ticket_link pull-right"> 
						<a target="_blank" class="btn btn-change" href="<?php echo site_url('activity/generate_act_ticket?booking_id='.$act['booking_id']);?>">Ticket</a>
				    </div>
				</div>
			</div>
			<?php }?>
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