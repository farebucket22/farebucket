<?php
	@session_start();  
	$cust_support_data = cust_support_helper();
	$data[0] = (array)$data[0];
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
		padding-top:15px;
	}
	.calendar{
		margin: 0 auto;
		height:20px;
		width:20px;
		background: url( '../../../img/calendar_icon.png' ) no-repeat center center;
	}
	.cab-bg{
		margin: 0 auto;
		height:45px;
		width:45px;
		background: url( '../../../img/cabIcon.png' ) no-repeat center center;
		background-size: cover;
	}
	.cab_icon{
		width:45px;
	}

</style>
<body>
	<div class="wrap">
		<div class="container clear-top">
			<?php for( $i = 0; $i < count($data); $i++ ){ ?>
			<div class="row">
				<div class="col-lg-24 img-container">
					<?php if($i == 0):?>
					<h4 class="col-xs-24 userLoginHeader loginFormField center-align-text">Your Booking has been confirmed.</h4>
					<div class="col-xs-24 booking-sub-header center-align-text">Please find your booking details below</div>
					<?php endif;?>
					<div class="col-lg-2">
						<div class="cab-bg"></div>
				    </div>
				    <div class="col-lg-8">
						<div class="col-lg-6 remove-padding">
							<div class="row">
								<div class="srcDest center-align-text form-padding" id="origin"><?php echo $data[0]['cab_src'];?></div>
							</div>
							<?php if( isset($data[0]['booking_date']) && !empty($data[0]['booking_date']) ):?>
								<div class="row center-align-text time_text" id="from"><?php echo date("D, jS M Y",strtotime($data[0]['booking_date']));?></div>
							<?php else:?>
								<div class="row center-align-text time_text" id="from"></div>
							<?php endif;?>
						</div>
						<div class="col-lg-4 remove-padding">
							<div class="srcDest"><span class="glyphicon glyphicon-play"></span></div>
						</div>
						<div class="mgn-right col-lg-6 remove-padding">
							<div class="row">
								<div class="srcDest center-align-text form-padding" id="destination"><?php if($data[0]['travel_id'] == 2){echo $data[0]['cab_dest'];}else{echo 
									$data[0]['cab_dest'].'0 kms/'.$data[0]['cab_dest'].' hrs';}?></div>
							</div>
							<?php if( isset($data[0]['journey_date']) && !empty($data[0]['journey_date']) ):?>
								<div class="row center-align-text time_text" id="to"><?php echo date("D, jS M Y",strtotime($data[0]['journey_date']));?></div>
							<?php else:?>
								<div class="row center-align-text time_text" id="to"></div>
							<?php endif;?>
						</div>
					</div>
					<div class="col-lg-3 center-align-text">
						<div class="row">
							<img class="cab_icon" src="<?php echo base_url('img/'.$data[0]['car_type'].'.png');?>" />
						</div>
						<div class="row"><?php echo $data[0]['car_type']?></div>
					</div>
					<div class="col-lg-4 center-align-text">
						<div class="row">
							<!--add profile icon here-->
						</div>
						<div class="row">
							<?php 
								if( $data[0]['passengers'][$i] ){
                                    if( $data[0]['passengers'][$i] == 1 )
                                        echo $data[0]['passengers'][$i]." Passenger";
                                    else
                                        echo $data[0]['passengers'][$i]." Passengers";
                                }
							?>
						</div>
					</div>
				    <div class="col-lg-4 center-align-text">
				    	<div class="row">
				    		<div class="calendar"></div>
				    	</div>
						<div id="journey_date"><?php echo $data[0]['CabRequiredOn'];?></div>
				    </div>
				    <div class="col-lg-3 ticket_link pull-right"> 
						<a target="_blank" class="btn btn-change" href="<?php echo site_url('cab_api/cabs/search_book?booking_number='.$data[0]["confirm_ref_id"]);?>">Ticket</a>
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