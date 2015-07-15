<?php
$cust_support_data = cust_support_helper();
if( isset($errCode) && $errCode == '1' ):?>
	<div class="wrap">
		<div class="container clear-top">
			<div class="row">
				<center><h2><?php echo $errMessage;?></h2></center>
				<center><span class="h4 mod_search_error" onclick="javascript:location.href = '<?php echo site_url('api/flights/guest_ticket');?>'">Try again</span><center>
			</div>
		</div>
	</div>
<?php else:?>
<?php
	$adult = 0;
	$child = 0;
	$infant = 0;
	$info = $data[0][0];
	if(isset($info->Type)){
		$type_arr = explode(',', $info->Type);
		foreach( $type_arr as $tr ){
			if( $tr == 'Adult' ){
				$adult++;
			}elseif( $tr == 'Child' ){
				$child++;
			}elseif( $tr == 'Infant' ){
				$infant++;
			}
		}
	}else{
		$adult = $info->num_of_adults;
		$child = $info->num_of_children;
		$infant = $info->num_of_infants;
	}
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
		background-size: cover;
	}
	.flight-bg{
		margin: 0 auto;
		height:45px;
		width:45px;
		background: url( '../../../img/flightIcon.png' ) no-repeat center center;
		background-size: cover;
	}
</style>
<body>
	<div class="wrap">
		<div class="container clear-top">
			<?php for($i = 0; $i<count($data); $i++){ $con_dep = explode(",",$data[$i][0]->ConnectingDepTime);$con_arr = explode(",",$data[$i][0]->ConnectingArrTime);?>
			<div class="row">
				<div class="col-lg-24 img-container">
						<h4 class="col-xs-24 booking-header center-align-text">Your booking has been confirmed</h4>
						<div class="col-xs-24 booking-sub-header center-align-text">Please find your booking details below</div>
					<div class="col-lg-2">
						<div class="flight-bg"></div>
				    </div>
				    <div class="col-lg-3">
						<div class="col-lg-6 remove-padding">
							<div class="row">
								<div class="srcDest center-align-text form-padding" id="origin"><?php echo $data[$i][0]->Origin;?></div>
							</div>
							<div class="row center-align-text time_text" id="from"><?php if(count($con_dep) == 1){echo date("d M Y",strtotime($data[$i][0]->ConnectingDepTime));}else{echo date("d M Y",strtotime($con_dep[0]));}?></div>
						</div>
						<div class="col-lg-4 remove-padding">
							<div class="srcDest"><span class="glyphicon glyphicon-play"></span></div>
						</div>
						<div class="mgn-right col-lg-6 remove-padding">
							<div class="row">
								<div class="srcDest center-align-text form-padding" id="destination"><?php echo $data[$i][0]->Destination;?></div>
							</div>
							<div class="row center-align-text time_text" id="to"><?php if(count($con_arr) == 1){echo date("d M Y",strtotime($data[$i][0]->ConnectingArrTime));}else{echo date("d M Y",strtotime($con_arr[count($con_arr)-1]));}?></div>
						</div>
					</div>
					<div class="col-lg-3 center-align-text">
						<div class="row">
							<img src="<?php echo base_url('img/AirlineLogo/'.$data[$i][0]->ConnectingAirlineCode.'.gif');?>" onError="this.src='<?php echo base_url('img/flightIcon.png'); ?>'"/>
						</div>
						<div class="row"><?php echo $data[$i][0]->ConnectingAirlineName;?></div>
					</div>
					<div class="col-lg-3 center-align-text">
						<div class="row">
							<!--add profile icon here-->
						</div>
						<div class="row">
							<?php 
								if( $adult ){
                                    if( $adult == 1 )
                                        echo $adult." Adult";
                                    else
                                        echo $adult." Adults";
                                }
							?>
						</div>
						<div class="row">
							<?php 
								if( $child ){
                                    if( $child == 1 )
                                        echo $child." Child";
                                    else
                                        echo $child." Children";
                                }
							?>
						</div>
						<div class="row">
							<?php 
								if( $infant ){
                                    if( $infant == 1 )
                                        echo $infant." infant";
                                    else
                                        echo $infant." Infants";
                                }
							?>
						</div>
					</div>
				    <div class="col-lg-3 center-align-text">
				    	<div class="row">
				    		<div class="calendar"></div>
				    	</div>
						<div id="journey_date"><?php if(count($con_dep) == 1){echo date("d M Y",strtotime($data[$i][0]->ConnectingDepTime));}else{echo date("d M Y",strtotime($con_dep[0]));}?></div>
				    </div>
						<div class="col-lg-3 center-align-text">
							<h5>Booking id:</h5>
							<span><?php if(count($con_dep) == 1){ echo $data[$i][0]->fbBooking_id; }else{ echo $data[$i][0]->fbBooking_id; };?></span>
						</div>
				    <div class="col-lg-3 ticket_link pull-right"> 
						<a target="_blank" class="btn btn-change" href="<?php echo site_url('api/flights/ticket_page?booking_id='.$data[$i][0]->fbBooking_id);?>">Ticket</a>
				    	
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
<?php endif;?>