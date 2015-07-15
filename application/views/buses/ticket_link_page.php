<?php
	@session_start();
	$cust_support_data = cust_support_helper();
	$doj = strtotime($data->all_details->doj);
	$no_pax = count($data->all_details->inventoryItems);
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
		background: url( '../../../img/calendar_icon.png' ) no-repeat center center;
	}
	.bus-bg{
		margin: 0 auto;
		height:45px;
		width:45px;
		background: url( '../../../img/busIcon.png' ) no-repeat center center;
		background-size: cover;
	}
	.cab_icon{
		width:45px;
	}
	/*#origin,#destination{
		font-size:12px;
	}*/
</style>
<body>
	<div class="wrap">
		<div class="container clear-top">
			<?php for($i = 0; $i<1; $i++){ ?>
			<div class="row">
				<div class="col-lg-24 img-container">
					<?php if($i == 0):?>
						<h4 class="col-xs-24 booking-header center-align-text">Your booking has been confirmed</h4>
						<div class="col-xs-24 booking-sub-header center-align-text">Please find your booking details below</div>
					<?php endif;?>
					<div class="col-lg-2">
						<div class="bus-bg"></div>
				    </div>
				    <div class="col-lg-8">
						<div class="col-lg-6 remove-padding">
							<div class="row">
								<div class="srcDest center-align-text form-padding" id="origin"><?php echo $data->source;?></div>
							</div>
							<div class="row center-align-text time_text" id="from"><?php echo date("d M Y", $doj);?></div>
						</div>
						<div class="col-lg-4 remove-padding">
							<div class="srcDest"><span class="glyphicon glyphicon-play"></span></div>
						</div>
						<div class="mgn-right col-lg-6 remove-padding">
							<div class="row">
								<div class="srcDest center-align-text form-padding" id="destination"><?php echo $data->destination;?></div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 center-align-text">
						<div class="row"><?php echo $data->all_details->busType;?></div>
					</div>
					<div class="col-lg-4 center-align-text">
						<div class="row">
							<!--add profile icon here-->
						</div>
						<div class="row">
							<?php 
								if( $no_pax ){
                                    if( $no_pax == 1 )
                                        echo "1 Passenger";
                                    else
                                        echo "2 Passengers";
                                }
							?>
						</div>
					</div>
				    <div class="col-lg-4 center-align-text">
				    	<div class="row">
				    		<div class="calendar"></div>
				    	</div>
						<div id="journey_date"><?php echo date("D, jS M Y", $doj);?></div>
				    </div>
				    <?php if($data->status == "cancelled"):?>
				    	<div class="col-lg-3"></div>
			    	<?php else:?>
					    <div class="col-lg-3 ticket_link pull-right"> 
							<a target="_blank" class="btn btn-change" href="<?php echo site_url('bus_api/buses/show_ticket?booking_number='.$fbBusId);?>">Ticket</a>
					    </div>
					<?php endif;?>
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