<?php
@session_start();
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
		background: url( '../../img/calendar_icon.png' ) no-repeat center center;
	}
	.cab-bg{
		margin: 0 auto;
		height:45px;
		width:45px;
		background: url( '../../img/cabIcon.png' ) no-repeat center center;
		background-size: cover;
	}

	.flight-bg{
		margin: 0 auto;
		height:45px;
		width:45px;
		background: url( '../../img/flightIcon.png' ) no-repeat center center;
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
			<?php for($i = 0; $i<count($_SESSION['flight_data']); $i++): ?>
			<?php if( $_SESSION['flight_data'][$i]['ov']->mode == 'cab' ): ?>
				<?php foreach( $_SESSION['responses'][$i] as $r ):?>
				<div class="row">
					<div class="col-lg-24 img-container">
						<?php if($i == 0):?>
							<h4 class="col-xs-24 userLoginHeader yourTicketHeader loginFormField center-align-text">Your Tickets</h4>
						<?php endif;?>
						<div class="col-lg-2">
							<div class="cab-bg"></div>
					    </div>
					    <div class="col-lg-8">
							<div class="col-lg-6 remove-padding">
								<div class="row">
									<div class="srcDest center-align-text form-padding" id="origin"><?php echo $_SESSION['cab_src'];?></div>
								</div>
								<div class="row center-align-text time_text" id="from"><?php echo date("d M Y",strtotime($_SESSION['to_date']));?></div>
							</div>
							<div class="col-lg-4 remove-padding">
								<div class="srcDest"><span class="glyphicon glyphicon-play"></span></div>
							</div>
							<div class="mgn-right col-lg-6 remove-padding">
								<div class="row">
									<div class="srcDest center-align-text form-padding" id="destination"><?php if($_SESSION['travel_id'] == 2){echo $_SESSION['cab_dest'];}else{echo $_SESSION['cab_dest'].'0 kms/'.$_SESSION['cab_dest'].' hrs';}?></div>
								</div>
								<div class="row center-align-text time_text" id="to"><?php echo date("d M Y",strtotime($_SESSION['to_date']));?></div>
							</div>
						</div>
						<div class="col-lg-3 center-align-text">
							<div class="row">
								<img class="cab_icon" src="<?php echo base_url('img/'.lcfirst($_SESSION['car_type'][$i]).'.png');?>" />
							</div>
							<div class="row"><?php echo $_SESSION['car_type'][$i]?></div>
						</div>
						<div class="col-lg-4 center-align-text">
							<div class="row">
								<!--add profile icon here-->
							</div>
							<div class="row">
								<?php 
									if( $_SESSION['passengers'][$i] ){
	                                    if( $_SESSION['passengers'][$i] == 1 ){
	                                        echo $_SESSION['passengers'][$i]." Passenger";
	                                    }
	                                    else{
	                                        echo $_SESSION['passengers'][$i]." Passengers";
	                                    }
	                                }
								?>
							</div>
						</div>
					    <div class="col-lg-4 center-align-text">
					    	<div class="row">
					    		<div class="calendar"></div>
					    	</div>
							<div id="journey_date"><?php echo date("d M Y",strtotime($_SESSION['to_date']));?></div>
					    </div>
					    <div class="col-lg-3 ticket_link pull-right"> 
							<a target="_blank" class="btn btn-change" href="<?php echo site_url('cab_api/cabs/search_book?booking_number='.$r);?>">Ticket</a>
					    </div>
					</div>
				</div>
				<?php endforeach;?>
				<?php endif;?>
				<?php if( $_SESSION['flight_data'][$i]['ov']->mode == 'flight' ): ?>
				<?php
					$fdata[$i][0] = $_SESSION['ticket_details'][0][$i][0];
					$adult = 0;
					$child = 0;
					$infant = 0;
					$info = $fdata[$i][0];
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
				<div class="wrap">
					<div class="container clear-top">
						<?php 
							$con_dep = explode(",",$fdata[$i][0]->ConnectingDepTime);
							$con_arr = explode(",",$fdata[$i][0]->ConnectingArrTime);
						?>
						<div class="row">
							<div class="col-lg-24 img-container">
								<h4 class="col-xs-24 userLoginHeader yourTicketHeader loginFormField center-align-text">Your Tickets</h4>
								<div class="col-lg-2">
									<div class="flight-bg"></div>
							    </div>
							    <div class="col-lg-3">
									<div class="col-lg-6 remove-padding">
										<div class="row">
											<div class="srcDest center-align-text form-padding" id="origin"><?php echo $fdata[$i][0]->Origin;?></div>
										</div>
										<div class="row center-align-text time_text" id="from"><?php if(count($con_dep) == 1){echo date("d M Y",strtotime($fdata[$i][0]->ConnectingDepTime));}else{echo date("d M Y",strtotime($con_dep[0]));}?></div>
									</div>
									<div class="col-lg-4 remove-padding">
										<div class="srcDest"><span class="glyphicon glyphicon-play"></span></div>
									</div>
									<div class="mgn-right col-lg-6 remove-padding">
										<div class="row">
											<div class="srcDest center-align-text form-padding" id="destination"><?php echo $fdata[$i][0]->Destination;?></div>
										</div>
										<div class="row center-align-text time_text" id="to"><?php if(count($con_arr) == 1){echo date("d M Y",strtotime($fdata[$i][0]->ConnectingArrTime));}else{echo date("d M Y",strtotime($con_arr[count($con_arr)-1]));}?></div>
									</div>
								</div>
								<div class="col-lg-3 center-align-text">
									<div class="row">
										<img src="<?php echo base_url('img/AirlineLogo/'.$fdata[$i][0]->ConnectingAirlineCode.'.gif');?>" onError="this.src='<?php echo base_url('img/flightIcon.png'); ?>'"/>
									</div>
									<div class="row"><?php echo $fdata[$i][0]->ConnectingAirlineName;?></div>
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
									<div id="journey_date"><?php if(count($con_dep) == 1){echo date("d M Y",strtotime($fdata[$i][0]->ConnectingDepTime));}else{echo date("d M Y",strtotime($con_dep[0]));}?></div>
							    </div>
							    <div class="col-lg-3 ticket_link pull-right"> 
									<a target="_blank" class="btn btn-change" href="<?php echo site_url('api/flights/ticket_page?booking_id='.$fdata[$i][0]->BookingId);?>">Ticket</a>
							    </div>
							</div>
						</div>
					</div>
				</div>
			<?php endif;?>
				<?php if( $_SESSION['flight_data'][$i]['ov']->mode == 'bus' ):?>
				<div class="some"></div>
			<?php endif; endfor;?>
		</div>
	</div>
</body>