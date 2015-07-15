<?php 
    if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 ){
        $count = $_GET['flight_num'];
        $_SESSION['cnt_val'] = $_GET['flight_num'];
    }  
?> 
<?php if(isset($_SESSION['redir_data']) && $_SESSION['redir_data']['travel_mode'] == 'cab' ):?>
	<form action="<?php echo site_url('common/change_travel_mode');?>" method="post" id="cabs_form" style="display:none;">
		<input type="text" name="source" value="<?php echo $_SESSION['redir_data']['source'];?>" />
		<input type="text" name="destination" value="<?php echo $_SESSION['redir_data']['destination'];?>" />
		<input type="text" name="adult_count" value="<?php echo $_SESSION['redir_data']['adult_count'];?>" />
		<input type="text" name="youth_count" value="<?php echo $_SESSION['redir_data']['youth_count'];?>" />
		<input type="text" name="total_count" value="<?php echo $_SESSION['redir_data']['total_count'];?>" />
		<input type="text" name="to_date" value="<?php echo $_SESSION['redir_data']['journey_date']?>" />
		<input type="text" name="from_date" value="<?php echo $_SESSION['redir_data']['journey_date'];?>" />
		<input type="text" name="cab_type" value="<?php echo $_GET['flight_type'];?>" />
		<input type="text" name="compacts" value="" id="Compact" />
		<input type="text" name="sedans" value="" id="Sedan" />
		<input type="text" name="suvs" value="" id="suv" />
		<input type="text" name="total_fare" value="" id="total_fare" />
		<input type="text" name="pax_info" value="" id="pax_info" />
		<input type="text" name="extra_info" value="" id="extra_info" />
		<input type="text" name="travel_mode" value="cab" />
	</form>
<?php else:?>
    <form action="<?php echo site_url('cabs/travellers_details')?>" method="post" id="cabs_form" style="display:none">
    	<input type="text" name="source" value="<?php if(isset($_GET['local_cab_src'])){echo $_GET['local_cab_src'];}else{echo $_GET['out_cab_src'];}?>" />
    	<input type="text" name="destination" value="<?php if(isset($_GET['local_cab_dest'])){echo $_GET['local_cab_dest'];}else{echo $_GET['out_cab_dest'];}?>" />
    	<input type="text" name="to_date" value="<?php echo $_GET['to_date'];?>" />
    	<input type="text" name="cab_type" value="<?php echo $_GET['flight_type'];?>" />
    	<input type="text" name="compacts" value="" id="Compact" />
    	<input type="text" name="sedans" value="" id="Sedan" />
    	<input type="text" name="suvs" value="" id="suv" />
    	<input type="text" name="total_fare" value="" id="total_fare" />
    	<input type="text" name="pax_info" value="" id="pax_info" />
    	<input type="text" name="extra_info" value="" id="extra_info" />
    </form>
<?php endif;?>
<style>
    .reg_link{
        text-align: right;
    }
    .reg_link a{
        color: #000;
    }
    .reg_link a:hover{
        color: #27ae60;
    }
    .fl_overwiew{
        height:65px;
    }
    .travel-text{
        padding-top: 0;
    }
    .fl_btn1{
        margin-top: 9px;
    }
    .fl_septr1{
        height:60px;
    }

    .bookingDetailsContainer{
        background-color: #fff;
        border: 2px solid #c3c3c3;
    }
    .bookingSelectionDetails, .bookingSelectionAmountContainer{
        color: #000;
    }
    .tripSummary{
        font-size: 18px;
        padding-left: 8px;
        font-weight: 700;
    }
    .gly-cts{
        top:-1px;
        font-size: 12px;
    }
    .summarySeparator{
        margin: 4px 0;
        border-bottom: 1px solid #ddd;
    }
    .table-custom>tbody>tr>td{
        border-top: none;
        line-height: 1.8em;
        font-size: 12px;
    }
    .tf-cng{
        font-family: "Oswald"
    }
    .bookingSelectionAmountContainer{
        margin-top: 0px;
    }
    .totFare{
        margin-top: -10px;
        margin-right: 0px;
        font-size: 11px;
        height: 15px;
        margin-bottom: 4px;
    }
    .finalFare{
        font-size: 18px;
        margin-right: 0px;
    }
    input.dis-code{
        font-size: 12px;
    }
    .pax_title{
    	width: 100%;
    }

    .fl_no{
    	font-family: "Oswald";
    	padding-top: 14px;
    }

    .cab-info{
    	font-size: 12px;
		text-align: center;
    }

    .cab-info p, .per-cab-fare p{
    	margin-bottom: 0;
    }

    .per-cab-fare{
    	font-size: 12px;
    }
    .perCabTitle{
        font-size: 14px;
    }
    .fl_overwiew .ellipse{
      height: 20px;
    }

    .fareBreakdown{
        font-size: 14px;
        top:-4px;
        color: #27ae60;
        transition: 0.2s all;
    }

    .fareBreakdown:hover{
        cursor: pointer;
        transition: 0.2s all;
        color: #000;
    }

    .smallerFonts{
        font-size: 12px;
    }
    .cabMultiplier{
        font-size: 12px;
    }
    .fareCalc{
        text-align: left;
    }

</style>

<div class="hidden-fields">
    <?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 ):?>
    <?php for( $hc = 0 ; $hc < $count ; $hc++):?>
        <div id="popoverHiddenContent-<?php echo $hc+1;?>" style="display: none;">
            <?php if( isset($_SESSION['flight_data'][$hc]) && $_SESSION['flight_data'][$hc]['ov']->mode == 'flight'): 
                    if( is_array($_SESSION['flight_data'][$hc]['ov']->WSSegment) ):?>

                <?php $max_arr_len = count($_SESSION['flight_data'][$hc]['ov']->WSSegment); $iter = 0;?>
                <?php foreach( $_SESSION['flight_data'][$hc]['ov']->WSSegment as $ws ):?>
                    <?php
                        $depTime = date('H:i', strtotime($ws->DepTIme));
                        $arrTime = date('H:i', strtotime($ws->ArrTime));
                        $depDate = date('D, jS M Y', strtotime($ws->DepTIme));
                        $var1 = strtotime($ws->DepTIme);
                        $var2 = strtotime($ws->ArrTime);
                        $var3 = $var2 - $var1;
                        $n = $var3/(60*60);
                        $d = 0;
                        $hrs = floor($n);
                        if( $hrs > 24 ){
                            $d = $hrs/24;
                            $d = floor($d);
                        }
                        $fraction = $n - $hrs;
                        $mins = $fraction * 60;
                    ?>
                    <div class="row center-align-text fl_row">
                        <!-- first flight -->
                        <div class="col-lg-6">
                            <div class="row date-text center-align-text">Flight Number</div>
                            <div class="row date-text"><?php if(isset($ws->OperatingCarrier) ){echo $ws->OperatingCarrier;}else{echo "";}?> - <?php if(isset($ws->Craft) ){echo $ws->Craft;}else{echo "";}?></div>
                        </div>
                        <div class="col-lg-12 remove-padding travel-text-margin">
                            <div class="row travel-text">
                                <div class="col-lg-11 remove-padding" id="originPop"><?php echo $ws->Origin->CityName;?><div class="row center-align-text time_text" id="from"><?php echo $depTime;?></div></div>
                                <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                <div class="col-lg-11 remove-padding" id="destinationPop"><?php echo $ws->Destination->CityName;?><div class="row center-align-text time_text" id="to"><?php echo $arrTime;?></div></div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <?php if($d > 0):?>
                                <div class="row dur-text"><?php echo $hrs."h"." ".$mins."m";?></div>
                            <?php else:?>
                                <div class="row dur-text"><?php echo $d.'d '.$hrs."h"." ".$mins."m";?></div>
                            <?php endif;?>
                            <div class="row dur-text center-align-text"><?php echo $depDate;?></div>
                        </div>
                    </div>
                    <?php 
                        if( $iter != $max_arr_len - 1 ){
                        echo '<div class="row center-align-text margin-center">';
                            echo '<div class="col-lg-6 remove-padding grey-line"></div>';
                            echo '<div class="col-lg-12 remove-padding"> Layover - '.$_SESSION['flight_data'][$hc]['ov']->layover[$iter].' </div>';
                            echo '<div class="col-lg-6 remove-padding grey-line"></div>';
                        echo '</div>';}
                    ?>
                    <?php $iter++;?>
                <?php endforeach;?>
            <?php else:?>
            <?php
                $depTime = date('H:i', strtotime($_SESSION["flight_data"][$hc]["ov"]->WSSegment->DepTIme));
                $arrTime = date('H:i', strtotime($_SESSION["flight_data"][$hc]["ov"]->WSSegment->ArrTime));
                $depDate = date('D, jS M Y', strtotime($_SESSION["flight_data"][$hc]["ov"]->WSSegment->DepTIme));
                $var1 = strtotime($_SESSION["flight_data"][$hc]["ov"]->WSSegment->DepTIme);
                $var2 = strtotime($_SESSION["flight_data"][$hc]["ov"]->WSSegment->ArrTime);
                $var3 = $var2 - $var1;
                $n = $var3/(60*60);
                $d = 0;
                $hrs = floor($n);
                if( $hrs > 24 ){
                    $d = $hrs/24;
                    $d = floor($d);
                }
                $fraction = $n - $hrs;
                $mins = $fraction * 60;
            ?>
                <div class="row center-align-text fl_row">
                    <!-- first flight -->
                    <div class="col-lg-6">
                        <div class="row date-text center-align-text">Flight Number</div>
                        <?php
                            if(isset($_SESSION["flight_data"][$hc]["ov"]->WSSegment->OperatingCarrier)){$OperatingCarrier = $_SESSION["flight_data"][$hc]["ov"]->WSSegment->OperatingCarrier;}else{$OperatingCarrier =  "";}
                            if(isset($_SESSION["flight_data"][$hc]["ov"]->WSSegment->Craft)){$Craft = $_SESSION["flight_data"][$hc]["ov"]->WSSegment->Craft;}else{$Craft =  "";}
                        ?>
                        <div class="row date-text"><?php echo $OperatingCarrier;?> - <?php echo $Craft;?></div>
                    </div>
                    <div class="col-lg-12 travel-text-margin">
                        <div class="row travel-text">
                            <div class="col-lg-11 remove-padding" id="originPop"><?php echo $_SESSION['flight_data'][$hc]['ov']->WSSegment->Origin->CityName;?><div class="row center-align-text time_text" id="from"><?php echo $depTime;?> </div></div>
                            <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                            <div class="col-lg-11 remove-padding" id="destinationPop"><?php echo $_SESSION['flight_data'][$hc]['ov']->WSSegment->Destination->CityName;?><div class="row center-align-text time_text" id="to"><?php echo $arrTime;?> </div></div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <?php if($d > 0):?>
                            <div class="row dur-text"><?php echo $hrs."h"." ".$mins."m";?></div>
                        <?php else:?>
                            <div class="row dur-text"><?php echo $d.'d '.$hrs."h"." ".$mins."m";?></div>
                        <?php endif;?>
                        <div class="row date-text center-align-text"><?php echo $depDate;?></div>
                    </div>
                </div>
            <?php endif;?>
            <?php elseif( isset($_SESSION['flight_data'][$hc]) && $_SESSION['flight_data'][$hc]['ov']->mode == 'bus' ):?>
                <?php
                    $bus_arrival = $_SESSION["flight_data"][$hc]["ov"]->arrivalTime;
                    $bus_departure = $_SESSION["flight_data"][$hc]["ov"]->departureTime;
                    $bus_dur = $bus_arrival - $bus_departure;
                    $oneDay=24*60;
                    $noOfDays = floor($bus_dur / $oneDay);
                    $noOfDaysA = floor($bus_arrival / $oneDay);
                    $noOfDaysD = floor($bus_departure / $oneDay);
                    $time = ($bus_dur) % $oneDay;
                    $timeA = ($bus_arrival) % $oneDay;
                    $timeD = ($bus_departure) % $oneDay;
                    $hours = floor($time/60);
                    $hoursA = floor($timeA/60);
                    $hoursD = floor($timeD/60);
                    $minutes = floor($time%60);
                    $minutesA = floor($timeA%60);
                    $minutesD = floor($timeD%60);
                    if($hours < 10)
                        $hours = '0'.$hours;
                    if($minutes < 10)
                        $minutes = '0'.$minutes;
                    if($hoursA < 10)
                        $hoursA = '0'.$hoursA;
                    if($minutesA < 10)
                        $minutesA = '0'.$minutesA;
                    if($hoursD < 10)
                        $hoursD = '0'.$hoursD;
                    if($minutesD < 10)
                        $minutesD = '0'.$minutesD;
                ?>
                <div class="row center-align-text fl_row">
                    <!-- first bus -->
                    <div class="col-lg-6">
                        <div class="row date-text-mod center-align-text">Bus Type</div>
                        <div class="row date-text-mod"><?php echo $_SESSION["flight_data"][$hc]["ov"]->busType;?></div>
                    </div>
                    <div class="col-lg-12 travel-text-margin">
                        <div class="row travel-text">
                            <div class="col-lg-11 remove-padding" id="originPop"><?php echo $_SESSION["flight_data"][$hc]["ov"]->org;?><div class="row center-align-text time_text" id="from"><?php echo $hoursA.':'.$minutesA?></div></div>
                            <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                            <div class="col-lg-11 remove-padding" id="destinationPop"><?php echo $_SESSION["flight_data"][$hc]["ov"]->dest;?><div class="row center-align-text time_text" id="to"><?php echo $hoursD.':'.$minutesD?></div></div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row date-text-mod">Duration</div>
                        <div class="row date-text-mod center-align-text"><?php echo $hours.' Hrs '.$minutes.' Mins';?></div>
                    </div>
                </div>
            <?php elseif( isset($_SESSION['flight_data'][$hc]) && $_SESSION['flight_data'][$hc]['ov']->mode == 'cab'):?>
                <?php 
                    $noOfComp = $_SESSION['flight_data'][$hc]['ov']->cabs['compacts'];
                    $noOfSed = $_SESSION['flight_data'][$hc]['ov']->cabs['sedans'];
                    $noOfSuv = $_SESSION['flight_data'][$hc]['ov']->cabs['suvs'];
                    $pax_info = json_decode($_SESSION['flight_data'][$hc]['ov']->paxInfo);
                ?>
                <!--the code below checks in compacts sedans and suv exist and if they do, it runs a row clone. the three variables about act like quantitative multipliers-->
                <?php if( $noOfComp ):?>
                    <div class="row center-align-text fl_row">
                        <!-- first Cab -->
                        <div class="col-lg-6">
                            <div class="row date-text-mod center-align-text">Cab Type</div>
                            <div class="row date-text-mod">Compact (<?php echo $noOfComp;?>)</div>
                        </div>
                        <div class="col-lg-12 travel-text-margin">
                            <div class="row travel-text">
                                <div class="row date-text-mod center-align-text">Total Pax</div>
                                <div class="pax_count">
                                    <?php $d=0; foreach($pax_info->compact as $c):?>
                                        <span><?php echo $c;?></span>
                                        <?php if( $d != count($pax_info->compact) - 1 ):?>
                                        <span> ,</span>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="row date-text-mod">Fare</div>
                            <div class="row date-text-mod center-align-text"><?php echo $_SESSION['flight_data'][$hc]['ov']->fareDet[0];?><span class="date-text-mod">per cab</span></div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if( $noOfSed ):?>
                    <div class="row center-align-text fl_row">
                        <!-- first Cab -->
                        <div class="col-lg-6">
                            <div class="row date-text-mod center-align-text">Cab Type</div>
                            <div class="row date-text-mod">Compact (<?php echo $noOfSed;?>)</div>
                        </div>
                        <div class="col-lg-12 travel-text-margin">
                            <div class="row travel-text">
                                <div class="row date-text-mod center-align-text">Total Pax</div>
                                <div class="pax_count">
                                    <?php $d=0; foreach($pax_info->sedan as $c):?>
                                        <span><?php echo $c;?></span>
                                        <?php if( $d != count($pax_info->sedan) - 1 ):?>
                                        <span> ,</span>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="row date-text-mod">Fare</div>
                            <div class="row date-text-mod center-align-text"><?php echo $_SESSION['flight_data'][$hc]['ov']->fareDet[1];?><span class="date-text-mod">per cab</span></div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if( $noOfSuv ):?>
                    <div class="row center-align-text fl_row">
                        <!-- first Cab -->
                        <div class="col-lg-6">
                            <div class="row date-text-mod center-align-text">Cab Type</div>
                            <div class="row date-text-mod">Compact (<?php echo $noOfSuv;?>)</div>
                        </div>
                        <div class="col-lg-12 travel-text-margin">
                            <div class="row travel-text">
                                <div class="row date-text-mod center-align-text">Total Pax</div>
                                <div class="pax_count">
                                    <?php $d=0; foreach($pax_info->suv as $c):?>
                                        <span><?php echo $c;?></span>
                                        <?php if( $d != count($pax_info->suv) - 1 ):?>
                                        <span> ,</span>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="row date-text-mod">Fare</div>
                            <div class="row date-text-mod center-align-text"><?php echo $_SESSION['flight_data'][$hc]['ov']->fareDet[2];?><span class="date-text-mod">per cab</span></div>
                        </div>
                    </div>
                <?php endif;?>
            <?php else:?>
                <div></div>
            <?php endif;?>
        </div>
    <?php endfor;?>

    <?php for( $ht = 0 ; $ht < $count ; $ht++):?>
        <?php if( isset($_SESSION['flight_data'][$ht]) && $_SESSION['flight_data'][$ht]['ov']->mode == 'flight' ):?>
        <div id="popoverHiddenTitle-<?php echo $ht+1;?>" style="display: none">
            <div class="row">
                <div class="col-lg-12 pull-left">
                    <div class="col-lg-4">
                        <?php if( !is_array( $_SESSION['flight_data'][$ht]['ov']->WSSegment ) ):?>
                            <img src="<?php echo base_url('img/AirlineLogo/'.$_SESSION["flight_data"][$ht]["ov"]->WSSegment->Airline->AirlineCode.'.gif');?>" onError="this.src='<?php echo base_url('img/flightIcon.png'); ?>'" alt="jA" width='30px' />
                        <?php else:?>
                            <img src="<?php echo base_url('img/AirlineLogo/'.$_SESSION["flight_data"][$ht]["ov"]->WSSegment[0]->Airline->AirlineCode.'.gif');?>" onError="this.src='<?php echo base_url('img/flightIcon.png'); ?>'" alt="jA" width='30px' />
                        <?php endif;?>
                    </div>
                    <?php if( !is_array( $_SESSION['flight_data'][$ht]['ov']->WSSegment ) ):?>
                        <div class="col-lg-offset-2 col-lg-12 pop-title-text"><?php echo $_SESSION["flight_data"][$ht]["ov"]->WSSegment->Airline->AirlineName;?></div>
                    <?php else:?>
                        <div class="col-lg-offset-2 col-lg-12 pop-title-text"><?php echo $_SESSION["flight_data"][$ht]["ov"]->WSSegment[0]->Airline->AirlineName;?></div>
                    <?php endif;?>
                </div>
                <div class="col-lg-12 pull-right">
                    <div class="col-lg-4 col-lg-offset-8 img-cal">
                        <img src="<?php echo base_url('img/calendar_icon.png');?>" alt="jA" width='18px' />
                    </div>
                    <div class="col-lg-12 pop-title-text"><?php echo date('d M Y', strtotime($_SESSION['flight_data'][$ht]['travel_date']));?></div>
                </div>
            </div>
        </div>
    <?php elseif( isset($_SESSION['flight_data'][$ht]) && $_SESSION['flight_data'][$ht]['ov']->mode == 'bus' ):?>
        <div id="popoverHiddenTitle-<?php echo $ht+1;?>" style="display: none">
            <div class="row">
                <div class="col-lg-12 pull-left">
                    <div class="col-lg-24 pop-title-text"><?php echo $_SESSION["flight_data"][$ht]["ov"]->travels;?></div>
                </div>
                <div class="col-lg-12 pull-right">
                    <div class="col-lg-4 col-lg-offset-8 img-cal">
                        <img src="<?php echo base_url('img/calendar_icon.png');?>" alt="jA" width='18px' />
                    </div>
                    <div class="col-lg-12 pop-title-text"><?php echo date('d M Y', strtotime($_SESSION["flight_data"][$ht]["ov"]->doj));?></div>
                </div>
            </div>
        </div>
    <?php elseif( isset($_SESSION['flight_data'][$ht]) && $_SESSION['flight_data'][$ht]['ov']->mode == 'cab' ):?>
        <div id="popoverHiddenTitle-<?php echo $ht+1;?>" style="display: none">
            <div class="row">
                <div class="col-lg-12 pull-left">
                    <div class="col-lg-24 pop-title-text">
                        <div class="col-lg-11 remove-padding" id="originPop"><?php echo $_SESSION['flight_data'][$ht]['ov']->org?></div>
                        <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                        <div class="col-lg-11 remove-padding" id="destinationPop"><?php echo $_SESSION['flight_data'][$ht]['ov']->dest?></div>
                    </div>
                </div>
                <div class="col-lg-12 pull-right">
                    <div class="col-lg-4 col-lg-offset-8 img-cal">
                        <img src="<?php echo base_url('img/calendar_icon.png');?>" alt="jA" width='18px' />
                    </div>
                    <div class="col-lg-12 pop-title-text"><?php echo date('d M Y', strtotime($_SESSION['flight_data'][$ht]['ov']->doj));?></div>
                </div>
            </div>
        </div>
    <?php else:?>
        <div></div>
    <?php endif; endfor; endif;?>
</div>


<div class="hidden-fields">
    <div id="popoverHiddenContent" style="display: none">
        <div class="row center-align-text fl_row">
            <div class="col-lg-7"></div>
            <div class="col-lg-5"></div>
            <div class="col-lg-4 per-cab-fare perCabTitle" id="perCabFare"><p>Per cab:</p></div>
            <div class="col-lg-8 per-cab-fare perCabTitle" id="totalFare"><p>Total:</p></div>
        </div>

		<div class="row center-align-text fl_row" id="compactDetails">
			<div class="col-lg-8 date-text" id="cabTypeNumber">Compact <br /> <span id="carModel"></span></div>
			<div class="col-lg-4 remove-padding left-text">
				<div class="cabMultiplier">x<span id="multiple">0</span></div>
			</div>
			<div class="col-lg-4 per-cab-fare" id="perCabFare">&#x20B9;<span>0</span></div>
			<div class="col-lg-8 per-cab-fare" id="totalFare">&#x20B9;<span>0</span></div>
		</div>

		<div class="row center-align-text fl_row" id="sedanDetails">
			<div class="col-lg-8 date-text" id="cabTypeNumber">Sedan <br /> <span id="carModel"></span></div>
			<div class="col-lg-4 remove-padding left-text">
				<div class="cabMultiplier">x<span id="multiple">0</span></div>
			</div>
			<div class="col-lg-4 per-cab-fare" id="perCabFare">&#x20B9;<span>0</span></div>
			<div class="col-lg-8 per-cab-fare" id="totalFare">&#x20B9;<span>0</span></div>
		</div>

		<div class="row center-align-text fl_row" id="suvDetails">
			<div class="col-lg-8 date-text" id="cabTypeNumber">SUV <br /> <span id="carModel"></span></div>
			<div class="col-lg-4 remove-padding left-text">
				<div class="cabMultiplier">x<span id="multiple">0</span></div>
			</div>
			<div class="col-lg-4 per-cab-fare" id="perCabFare">&#x20B9;<span>0</span></div>
			<div class="col-lg-8 per-cab-fare" id="totalFare">&#x20B9;<span>0</span></div>
		</div>
    </div>
    <?php $i = 1; if( isset($data['fare_details']) && !empty($data['fare_details']) ): foreach($data['fare_details'] as $fd): ?>
        <div id="popoverFareHiddenContent-<?php echo $data['CarTypeID'][$i-1];?>" style="display: none;">
            <div class="center-align-text smallerFonts">
                <h4>Fare Break Up</h4>            
                <table class="table table-cust">
                    <tr>
                        <td class="left-text">Journey Days</td>
                        <td class="right-text"><?php echo $data['days'][$i-1];?></td>
                    </tr>
                    <tr>
                        <td class="left-text">Min. Charge Distance</td>
                        <td class="right-text"><?php echo $data['min_kms'][$i-1];?> Kms / Day</td>
                    </tr>
                    <tr>
                        <td class="left-text">Approx. Roundtrip</td>
                        <td class="right-text"><?php echo $data['calculatable_kms'][$i-1];?> Kms</td>
                    </tr>
                </table>
                <div class="fareCalc">
                    <div>Fare Calculation: </div>
                    <div><?php echo $data['fare_details'][$i-1];?></div>
                    <div><b>Total Fare: &#x20B9; <?php echo $data['total_fare'][$i-1];?></b></div>
                    <hr>
                    <div>Additional Charges (After <?php echo $data['calculatable_kms'][$i-1];?> Kms): </div>
                    <div>+ <?php echo $data['XKm_Charges'][$i-1];?> Per Km</div>
                    <div>+ Driver Charges : &#x20B9; <?php echo $data['driver_bata'][$i-1];?>.00 Per Day</div>
                    <div>+ All Parking, Toll, Border Tax, wherever applicable will be charged</div>
                    <div>+ Night Charges : &#x20B9;200 between 2300 Hrs to 0500 Hrs</div>
                    <hr>
                </div>
                <div><b>One Day is One calendar day</b></div>
                <div><b>(From 12 midnight to 12 midnight)</b></div>
            </div>

        </div>
    <?php $i++;?>
    <?php endforeach;endif;?>

</div>

<div class="wrap">
	<div class="container-fluid">    
	    <div class="row resultsSearchContainer navbar-fixed-top">
	        <div class="panel-group" id="accordionSearch">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                    <h4 class="panel-title">
						<?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 ):?>
							<div class="accord-content container-fluid">
							    <div class="row fl_overwiew">
                          <?php $count = $_SESSION['cnt_val'];?>
                          <?php for( $n = 0 ; $n < $count - 1 ; $n++):?>
                              <div class="col-lg-5 fl_septr1">
                                  <div class="col-lg-2 fl_no"><?php echo $n+1;?></div>
                                  <?php if( isset($_SESSION['flight_data'][$n]['ov']->mode) && $_SESSION['flight_data'][$n]['ov']->mode == 'bus' ):?>
                                      <div class="col-lg-3 bus_bg_nav"></div>
                                  <?php elseif(isset($_SESSION['flight_data'][$n]['ov']->mode) && $_SESSION['flight_data'][$n]['ov']->mode == 'cab'):?>
                                      <div class="col-lg-3 cab_bg_nav"></div>
                                  <?php else:?>
                                      <div class="col-lg-3 fl_bg_nav"></div>
                                  <?php endif;?>
                                  <div class="col-lg-14 fl_info">
                                      <div class="row">
                                          <div class="col-lg-offset-4 col-lg-18 travel-text">
                                              <div class="row center-align-text">
                                                  <div class="col-lg-11 remove-padding ellipse" id="origin<?php echo $n+1?>"><?php echo $_SESSION['flight_data'][$n]['ov']->org;?></div>
                                                  <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                                  <div class="col-lg-11 remove-padding ellipse" id="destination<?php echo $n+1?>"><?php echo $_SESSION['flight_data'][$n]['ov']->dest;?></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date( 'D, jS M Y', strtotime($_SESSION['details']['dates'][$n]) );?></div>
                                      </div>
                                      <div class="row">
                                          <?php if( isset($_SESSION['flight_data'][$n]['ov']->mode) && $_SESSION['flight_data'][$n]['ov']->mode == 'bus' ):?>
                                              <div class="col-lg-23 col-lg-offset-1 center-align-text fare-padding TotFare1">&#x20B9; <span><?php echo $_SESSION['flight_data'][$n]['ov']->fareDet['totalFare'];?></span></div>
                                          <?php elseif(isset($_SESSION['flight_data'][$n]['ov']->mode) && $_SESSION['flight_data'][$n]['ov']->mode == 'cab'):?>
                                              <div class="col-lg-23 col-lg-offset-1 center-align-text fare-padding TotFare1">&#x20B9; <span><?php echo $_SESSION['flight_data'][$n]['ov']->totalFare;?></span></div>
                                          <?php else:?>
                                              <div class="col-lg-23 col-lg-offset-1 center-align-text fare-padding TotFare1">&#x20B9; <span><?php echo $_SESSION['flight_data'][$n]['total_fare_field'];?></span></div>
                                          <?php endif;?>
                                      </div>
                                  </div>
                                  <div class="col-lg-4 pull-right">
                                      <div class="row fl_btn1">
                                          <div class="link-btn-ed">
                                              <a href="#" class='btn-ed' tabindex="0" id="edit-<?php echo $n+1;?>">EDIT</a>
                                          </div>
                                          <div class="link-btn-de">
                                              <a href="#" class='btn-de' tabindex="0" id='popover-toggle-<?php echo $n+1;?>' data-trigger="focus" data-toggle="popover" data-placement="bottom">DETAILS</a>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                      <?php endfor;?>
							        <div class="col-lg-5 fl_septr1 hulk-class">
							            <div class="col-lg-2 fl_no"><?php echo $n+1;?></div>
							            <div class="col-lg-3 cab_bg_nav"></div>
							            <div class="col-lg-15 fl_info">
							                <div class="row">
							                    <div class="col-lg-offset-3 col-lg-19 travel-text">
							                        <div class="row center-align-text">
							                            <div class="col-lg-11 remove-padding cab_src ellipse" id="src"><?php if(isset($_SESSION['travel_id']) && $_SESSION['travel_id'] == 2) echo $data['source']; else echo $data['source'][1];?></div>
							                            <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
							                            <div class="col-lg-11 remove-padding cab_dest ellipse" id="dest"><?php if(isset($_SESSION['travel_id']) && $_SESSION['travel_id'] == 2) echo $data['destination']; else echo $data['destination'].' 0kms/'.$data['destination'].' hrs';?></div>
							                        </div>
							                    </div>
							                </div>

                              <div class="row">
                                <?php if( isset($_GET['journey_date']) ):?>
                                  <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date('D, jS M Y', strtotime($_GET['journey_date']));?></div>
                                <?php else:?>
                                  <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date('D, jS M Y', strtotime($_GET['to_date']));?></div>
                                <?php endif;?>
                              </div>

							                <div class="row">
							                	<div class="TotFare1" id="cabTotalFare" style="display:none;"></div>
							                    <div class="col-lg-23 col-lg-offset-1 totFare2 cab-fare center-align-text" style="display:none;">&#x20B9; <span></span>
                                </div>
							                </div>
							            </div>
							            <div class="col-lg-4 pull-right">
							                <div class="row fl_btn1">
							                	<div class="link-btn-ed hide">
							                    	<a href="#" class='btn-ed'>EDIT</a>
							                    </div>
							                    <div class="link-btn-de">
							                    	<a href="#" class='btn-de' id='popover-toggle' data-trigger="focus" data-toggle="popover" data-placement="bottom">DETAILS</a>
						                    	</div>
							                </div>
							            </div>
							        </div>
							    </div>
							</div>
						<?php else:?>
	                    	<a class="accordion-toggle modSearch" data-toggle="collapse" data-target="#collapseSearch">
                                <div class="pull-right searchIcon">
                                    <div class="row center-align-text">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </div>
                                    <div class="row">
                                        <span>Modify <br/>Search</span>
                                    </div>
                                </div>
                            </a>
							<div class="accord-content container-fluid">
							    <div class="row fl_overwiew">
							        <div class="col-lg-5 fl_septr1 hulk-class">
							            <div class="col-lg-2 fl_no">1</div>
							            <div class="col-lg-3 cab_bg_nav"></div>
							            <div class="col-lg-15 fl_info">
							                <div class="row">
							                    <div class="col-lg-offset-3 col-lg-19 travel-text">
							                        <div class="row center-align-text">
							                            <div class="col-lg-11 remove-padding cab_src ellipse" id="src"><?php if(isset($_SESSION['travel_id']) && $_SESSION['travel_id'] == 2) echo $data['source']; else echo $data['source'][1];?></div>
							                            <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
							                            <div class="col-lg-11 remove-padding cab_dest ellipse" id="dest"><?php if(isset($_SESSION['travel_id']) && $_SESSION['travel_id'] == 2) echo $data['destination']; else echo $data['destination'].'0kms/'.$data['destination'].' hrs';?></div>
							                        </div>
							                    </div>
							                </div>
                              <div class="row">
                                <?php if( isset($_GET['journey_date']) ):?>
                                  <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date('D, jS M Y', strtotime($_GET['journey_date']));?></div>
                                <?php else:?>
                                  <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date('D, jS M Y', strtotime($_GET['to_date']));?></div>
                                <?php endif;?>
                              </div>
							                <div class="row">
							                	<div class="TotFare1" id="cabTotalFare" style="display:none;"></div>
							                    <div class="col-lg-23 col-lg-offset-1 totFare2 cab-fare center-align-text" style="display:none;">&#x20B9; <span></span></div>
							                </div>
							            </div>
							            <div class="col-lg-4 pull-right">
							                <div class="row fl_btn1">
							                	<div class="link-btn-ed hide">
							                    	<a href="#" class='btn-ed'>EDIT</a>
							                    </div>
							                    <div class="link-btn-de">
							                    	<a href="#" class='btn-de' id='popover-toggle' data-trigger="focus" data-toggle="popover" data-placement="bottom">DETAILS</a>
						                    	</div>
							                </div>
							            </div>
							        </div>
							    </div>
							</div>
						<?php endif;?>
						</h4>
					</div>
                    <div id="collapseSearch" class="panel-collapse collapse">
                        <div class="panel-body">
							<?php include( APPPATH.'views/cabs/search_view.php' );?>
                        </div>
                     </div>
				</div>
	        </div>
	    </div>
	</div>
	<?php $i =0;?>
	<div class="container clear-top cab_results">

		    <?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 || (isset($_GET['oneway_change_cab']) && $_GET['oneway_change_cab'] == "true") ):?>
		    <div class="row cabMarginBottom">
		        <div  class="col-sm-offset-cust-19 col-xs-7">
		            <div class="flightOptionContainer row">
		                <div class="col-xs-4 flightOptionLabel"></div>
		                <div class="col-xs-18 noFlightsLabel">No Flights available</div>
		                <div class="col-xs-18" id="fl_spin">
		                    <div id="fl_spinner" class="spinner">
		                        <div class="rect1"></div>
		                        <div class="rect2"></div>
		                        <div class="rect3"></div>
		                        <div class="rect4"></div>
		                        <div class="rect5"></div>
		                    </div>
		                </div>
		                <div class="col-xs-16 flightOptionDetails">
		                    <div class="row flightOptionDetailsLine1">Flights to Chennai starting from:</div>
		                    <div class="row flightOptionDetailsLine2">&#x20B9 3999.00</div>
		                </div>
		                <a href="#" class="col-xs-4 flightOptionView">VIEW</a>
		            </div>
		        </div>
				<div  class="col-xs-7 col-sm-offset-cust-3">
                    <div class="busOptionContainer row">
                        <div class="col-xs-4 busOptionLabel"></div>
                        <div class="col-xs-18 noBusesLabel">No Cabs available</div>
                        <div class="col-xs-18" id="bus_spin">
                            <div id="bus_spinner" class="spinner">
                                <div class="rect1"></div>
                                <div class="rect2"></div>
                                <div class="rect3"></div>
                                <div class="rect4"></div>
                                <div class="rect5"></div>
                            </div>
                        </div>
                        <div class="col-xs-16 busOptionDetails">
                            <div class="row busOptionDetailsLine1">Buses to Chennai from:</div>
                            <div class="row busOptionDetailsLine2">&#x20B9 3999.00</div>
                        </div>
                        <a href="#" class="col-xs-4 busOptionView">VIEW</a>
                    </div>
                </div>
		    </div>
		    <?php endif;?>
		<div class="row selectMessageBlock">
      <center><small class="errorMessage"></small></center>
      <center><small class="successMessage"></small></center>
    </div>
		<div class="row">
			<div class="col-lg-8">
			<?php if($data['CarTypeID'][0] != 1){
				echo "<div style='background: rgba(0,0,0,0.7);display:block;z-index:10000;width:300px;margin:0 auto;'>";
			}?>
				<div class="row">
					<div class="cab-container center-align-text">
						<h4>Compact</h4>
						<div class="car-bg compact"></div>
						<h3>Tata Indica</h3>
						<p>4 Pax + Driver</p>
						<p class="per_cab_fare">&#x20B9; <span><?php if($data['CarTypeID'][$i] == 1){echo $data['total_fare'][$i];}else{echo '-';}?></span>/cab</p>
						<p>Air-Conditioned</p>
						<div class="row add-section">
							<div class="col-lg-18">
								<p class="car_count" id="car_field0"><?php echo 'Compact (0 Selected)';?></p>
							</div>
							<div class="col-lg-6 car_count">
								<?php 
									if($data['CarTypeID'][0] == 1){
										$j = 1;
										echo '<button type="button" id="0" class="pull-left btn-del-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-minus"></span></button>';
										echo '<button type="button" id="0" class="pull-right btn-add-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-plus"></span></button>';
									}else{
										$j = 0;
										echo '<button disabled="disabled" type="button" id="0" class="pull-left btn-del-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-minus"></span></button>';
										echo '<button disabled="disabled" type="button" id="0" class="pull-right btn-add-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-plus"></span></button>';
									}
								;?>
								<!-- <p>No. of Pax</p> -->
								<?php 
								//if($data['CarTypeID'][0] == 1)
								//{
									//$j = 1;
									//echo '<select name="num_of_pax" id="num_of_pax-0" class="num_pax_sb"></select>';
								//}
								//else
								//{
								//	$j = 0;
								//	echo '<select disabled ="disabled" name="num_of_pax" id="num_of_pax-0" class="num_pax_sb"></select>';
								//}
								?>
							</div>
						</div>
						<div class="row add-section remove-padding">
							<div class="col-lg-24">
								<div class="tot_fare per_cab_fare">&#x20B9; <span><?php if($data['CarTypeID'][$i] == 1){echo $data['total_fare'][$i];$i++;}else{echo '-';}?></span> <span class="glyphicon glyphicon-question-sign fareBreakdown farePopover-1" tabindex="0" data-trigger="focus" data-toggle="popover" data-placement="top"></span> </div>
							</div>
						</div>
					</div>
				</div>
				<?php if($data['CarTypeID'][0] != 1){
					echo "</div>";
				}?>
			</div>
			<div class="col-lg-8">
				<?php if($data['CarTypeID'][$j] != 6){
				echo "<div style='background: rgba(0,0,0,0.7);display:block;z-index:10000;width:300px;margin:0 auto;'>";
				}?>
				<div class="row">
					<div class="cab-container center-align-text">
						<h4>Sedan</h4>
						<div class="car-bg sedan"></div>
						<h3>Indigo</h3>
						<p>4 Pax + Driver</p>
						<p class="per_cab_fare">&#x20B9; <span><?php if($data['CarTypeID'][$i] == 6){echo $data['total_fare'][$i];}else{echo '-';}?></span>/cab</p>
						<p>Air-Conditioned</p>
						<div class="row add-section">
							<div class="col-lg-18">
								<p class="car_count" id="car_field1"><?php echo 'Sedan (0 Selected)';?></p>
							</div>
							<div class="col-lg-6 car_count">
								<?php 
								if($data['CarTypeID'][$j] == 6){
									$j++;
									echo '<button type="button" id="1" class="pull-right btn-add-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-plus"></span></button>';
									echo '<button type="button" id="1" class="pull-left btn-del-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-minus"></span></button>';
								}else{
									echo '<button disabled="disabled" type="button" id="1" class="pull-right btn-add-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-plus"></span></button>';
									echo '<button disabled="disabled" type="button" id="1" class="pull-left btn-del-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-minus"></span></button>';
								}
								;?>
								<!-- <p>No. of Pax</p> -->
								<?php 
								//if($data['CarTypeID'][$j] == 6)
								//{
								//	echo '<select name="num_of_pax" id="num_of_pax-1" class="num_pax_sb"></select>';
								//}
								//else
								//{
									//$j = 0;
									//echo '<select disabled ="disabled" name="num_of_pax" id="num_of_pax-1" class="num_pax_sb"></select>';
								//}
								?>
							</div>
						</div>
						<div class="row add-section remove-padding">
							<div class="col-lg-24">
								<div class="tot_fare per_cab_fare">&#x20B9; <span><?php if($data['CarTypeID'][$i] == 6){echo $data['total_fare'][$i];$i++;}else{echo '-';}?></span> <span class="glyphicon glyphicon-question-sign fareBreakdown farePopover-6" tabindex="0" data-trigger="focus" data-toggle="popover" data-placement="top"></span> </div>
							</div>
						</div>
					</div>
				</div>
				<?php if($data['CarTypeID'][$j-1] != 6){
					echo "</div>";
				}?>
			</div>
			<div class="col-lg-8">
				<?php if($data['CarTypeID'][$j] != 21){
				echo "<div style='background: rgba(0,0,0,0.7);display:block;z-index:10000;width:300px;margin:0 auto;'>";
				}?>
				<div class="row">
					<div class="cab-container center-align-text">
						<h4>SUV</h4>
						<div class="car-bg suv"></div>
						<h3>Toyota Innova</h3>
						<p>6 Pax + Driver</p>
						<p class="per_cab_fare">&#x20B9; <span><?php if(isset($data['CarTypeID'][$i])){if($data['CarTypeID'][$i] == 21){echo $data['total_fare'][$i];}else{echo '-';}}?></span>/cab</p>
						<p>Air-Conditioned</p>
						<div class="row add-section">
							<div class="col-lg-18">
								<p class="car_count" id="car_field2"><?php echo 'SUV (0 Selected)';?></p>
							</div>
							<div class="col-lg-6 car_count">
								<?php 
								if($data['CarTypeID'][$j] == 21){
									echo '<button type="button" id="2" class="pull-right btn-add-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-plus"></span></button>';
									echo '<button type="button" id="2" class="pull-left btn-del-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-minus"></span></button>';
								}else{
									echo '<button disabled="disabled" type="button" id="2" class="pull-right btn-add-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-plus"></span></button>';
									echo '<button disabled="disabled" type="button" id="2" class="pull-left btn-del-cab btn-custom-calc plusBtn"><span class="glyphicon glyphicon-minus"></span></button>';
								}
								;?>
								<!-- <p>No. of Pax</p> -->
								<?php 
								// if($data['CarTypeID'][$j] == 21)
								// {
									// echo '<select name="num_of_pax" id="num_of_pax-2" class="num_pax_sb"></select>';
								// }
								// else
								// {
									// echo '<select disabled ="disabled" name="num_of_pax" id="num_of_pax-2" class="num_pax_sb"></select>';
								// }
								?>
							</div>
						</div>
						<div class="row add-section remove-padding">
							<div class="col-lg-24">
								<div class="tot_fare per_cab_fare">&#x20B9; <span><?php if(isset($data['CarTypeID'][$i])){if($data['CarTypeID'][$i] == 21){echo $data['total_fare'][$i];}else{echo '-';}}?></span> <span class="glyphicon glyphicon-question-sign fareBreakdown farePopover-21" tabindex="0" data-trigger="focus" data-toggle="popover" data-placement="top"></span> </div>
							</div>
						</div>
					</div>
				</div>
				<?php if($data['CarTypeID'][$j] != 21){
					echo "</div>";
				}?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-24 center-align-text">
        <?php if(isset($_SESSION['redir_data']) && $_SESSION['redir_data']['travel_mode'] == 'cab' ):?>
			<button type="button" class="btn btn-change btn-checkout">SELECT</button>
        <?php else:?>
            <button type="button" class="btn btn-change btn-checkout">BOOK</button>
        <?php endif;?>
			</div>
		</div>
	</div>
</div>
<script>
//global variables
var enteredFuntion = 0;
var addedPax = 0;

(function(){
// navbar modifiedsearch alter
    $('#collapseSearch').on('show.bs.collapse', function(e){
        if( e.target.id === 'collapseSearch' ){
            $('.searchIcon').html(' <div class="row center-align-text"><span class="glyphicon glyphicon-remove"></span></div><div class="row"><span>Close</span></div>');
            $('.searchIcon').css({
                "padding-right": "20px",
                "padding-top": "6px"
            });
        }
    });

    $('#collapseSearch').on('hide.bs.collapse', function(e){
        if( e.target.id === 'collapseSearch' ){
            $('.searchIcon').html(' <div class="row center-align-text"><span class="glyphicon glyphicon-search"></span></div><div class="row"><span>Modify <br/>Search</span></div>');
            $('.searchIcon').css({
                "padding-right": "16px"
            });
        }
    });
// navbar modifiedsearch alter end
var src = "<?php if(isset($_SESSION['travel_id']) && $_SESSION['travel_id'] == 2) echo $data['source']; else echo $data['source'][1];?>";
var dest = "<?php if(isset($_SESSION['travel_id']) && $_SESSION['travel_id'] == 2) echo $data['destination']; else echo $data['destination'].'0kms/'.$data['destination'].' hrs';?>";
    //tooltip code
        $('#src').on('mouseenter', function(){
            $(this).tooltip({
                placement: 'bottom',
                trigger: 'hover',
                title: src
            });
            $(this).tooltip('show');
        });

        $('#dest').on('mouseenter', function(){
            $(this).tooltip({
                placement: 'bottom',
                trigger: 'hover',
                title: dest
            });
            $(this).tooltip('show');
        });
    //end tooltip code

	var cnt = "<?php count($data['total_fare']);?>";

	<?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 || (isset($_GET['oneway_change_cab']) && $_GET['oneway_change_cab'] == "true") ):?>
		var pax = <?php echo intval($_GET['total_count']);?>;
		var rem_pax = pax;
	<?php else:?>
        var pax = 9;
        var rem_pax = pax;
    <?php endif;?>

	var cv = $('.car_field').val();
	var car_fare = {
		'0' : "<?php if(isset($data['CarTypeID'][0])){if($data['CarTypeID'][0] == 1){ echo $data['total_fare'][0];$i=1;}else{ echo '';$i=0;} }?>",
		'1' : "<?php if(isset($data['CarTypeID'][$i])){if($data['CarTypeID'][$i] == 6){ echo $data['total_fare'][$i];$i++;}else{ echo '';} }?>",
		'2' : "<?php if(isset($data['CarTypeID'][$i])){if($data['CarTypeID'][$i] == 21){ echo $data['total_fare'][$i];}else{ echo '';} }?>"
	};

	var car_model = {
		'0' : "<?php if(isset($data['CarTypeID'][0])){if($data['CarTypeID'][0] == 1){ echo $data['car_model'][0];$i=1;}else{ echo "";$i=0;} }?>",
		'1' : "<?php if(isset($data['CarTypeID'][$i])){if($data['CarTypeID'][$i] == 6){ echo $data['car_model'][$i];$i++;}else{ echo "";} }?>",
		'2' : "<?php if(isset($data['CarTypeID'][$i])){if($data['CarTypeID'][$i] == 21){ echo $data['car_model'][$i];}else{ echo "";} }?>",
	};


	var car_pax = {	
		'compact': [],
		'sedan': [],
		'suv': [],
	};

	var compact_seats = 4;
	var sedan_seats = 4;
	var suv_seats = 6;

	var cabs = $('.cab-container').find('.num_pax_sb');

	var times_entered = 0;

	// calc_seats( compact_seats, rem_pax, times_entered );
	// calc_seats( sedan_seats, rem_pax, times_entered );
	// calc_seats( suv_seats, rem_pax, times_entered );

	$('.totFare2 span, .per_cab_fare span').autoNumeric({
		aSep: ',',
		dGroup: 2,
	});

	$('#extra_info').val(JSON.stringify(<?php echo json_encode($data);?>));

	$('.btn-checkout').on('click', function(){
		if( addedPax > 0 ){
			$('#pax_info').val(JSON.stringify(car_pax));
			$('#cabs_form').submit();
		}else{
      $('.selectMessageBlock .errorMessage').html('Please choose a cab first').fadeIn(200);
        setTimeout(function(){
            $('.selectMessageBlock .errorMessage').fadeOut(200);
        }, 3000);
			return false;
		}

	});

	// assorted data search section

    <?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 || (isset($_GET['oneway_change_cab']) && $_GET['oneway_change_cab'] == "true") ):?>
      <?php if( isset($_GET['oneway_change_cab']) && $_GET['oneway_change_cab'] == "true" ):?>
          var source_split = "<?php echo $_SESSION['onewayGetStrings']['from'];?>";
          var src = source_split.split(",");
              if(src[0] === "Delhi")
              {
                  src[0] = "Delhi /NCR";
              }
          var destination_split = "<?php echo $_SESSION['onewayGetStrings']['to'];?>";
          var dest = destination_split.split(",");
          var cab_search_parameters = 
          {
              source : src[0],
              destination : dest[0],
              is_ajax : 1,
              adult_count: "<?php echo $_SESSION['onewayGetStrings']['adult_count'];?>", 
              youth_count: "<?php echo $_SESSION['onewayGetStrings']['youth_count'];?>",
              journey_date: "<?php echo $_SESSION['onewayGetStrings']['oneway_date'];?>",
          }
      <?php else:?>
          var source_split = "<?php echo $_GET['source'];?>";  
          var src = source_split.split(",");
              if(src[0] === "Delhi")
              {
                  src[0] = "Delhi /NCR";
              }
          var destination_split = "<?php echo $_GET['destination'];?>";
          var dest = destination_split.split(",");
          var cab_search_parameters = 
          {
              source : src[0],
              destination : dest[0],
              is_ajax : 1,
              adult_count: "<?php echo $_SESSION['details']['adult_count'];?>", 
              youth_count: "<?php echo $_SESSION['details']['youth_count'];?>",
              journey_date: "<?php echo $_SESSION['details']['dp_dates'][($_SESSION['cnt_val']-1)];?>",
          }
      <?php endif;?>
	    $('#bus_spinner').show();
	    $('.busOptionDetails').hide();
	    $('.busOptionView').hide();
	    $('.noBusesLabel').hide();
	    $.ajax({
	        url: "<?php echo site_url('bus_api/buses/flights_to_buses');?>",
	        type: "POST",
	        data: {data : cab_search_parameters}
	    })
	    .done(function (busData){
	        var result = $.parseJSON(busData);
	        var search_base = "<?php echo site_url();?>";

	        $('#bus_spin').hide();
	        if( result.err === false ){
	            var search_str = '/bus_api/buses/getAvailableTrips?source_city_name='+result.source+'&source_id='+result.source_id+'&destination_city_name='+result.destination+'&destination_id='+result.destination_id+'&journey_date='+result.date+'&flight_type=local';
	            search_base += search_str;
	            $('.busOptionView').attr('href', search_base);
	            $('.busOptionDetails').show();
	            $('.busOptionDetailsLine1').html('Buses to '+result.destination+' From:');
	            $('.busOptionDetailsLine2').html('&#x20B9; '+result.min_price);
	            $('.busOptionView').show();
	        }else{
	            $('.busOptionView').attr('href', "#");
	            $('.busOptionDetails').hide();
	            $('.busOptionView').hide();
	            $('.noBusesLabel').show();
	        }
	    });

  <?php if( isset($_GET['oneway_change_cab']) && $_GET['oneway_change_cab'] == "true" ):?>
    var from_raw = "<?php echo $_SESSION['onewayGetStrings']['from'];?>";
    var to_raw = "<?php echo $_SESSION['onewayGetStrings']['to'];?>";
    var p1 = from_raw.lastIndexOf('(');
    var q1 = from_raw.lastIndexOf(')');
    var p2 = to_raw.lastIndexOf('(');
    var q2 = to_raw.lastIndexOf(')');
    var from_arr = from_raw.slice( p1+1 , q1 );
    var to_arr = to_raw.slice( p2+1 , q2 );

    var search_parameters = 
    {
        from: from_arr,
        to: to_arr,
        adult_count: "<?php echo $_SESSION['onewayGetStrings']['adult_count'];?>", 
        youth_count: "<?php echo $_SESSION['onewayGetStrings']['youth_count'];?>", 
        kids_count: "<?php echo $_SESSION['onewayGetStrings']['kids_count'];?>", 
        total_count: "<?php echo $_SESSION['onewayGetStrings']['total_count'];?>",
        oneway_date: "<?php echo $_SESSION['onewayGetStrings']['oneway_date'];?>",
        airline_preference: "<?php echo '';?>",
        travel_class: "<?php echo 'All';?>"
    };
  <?php else:?>
    var from_raw = "<?php echo $_SESSION['details']['from'][($_SESSION['cnt_val']-1)];?>";
    var to_raw = "<?php echo $_SESSION['details']['to'][($_SESSION['cnt_val']-1)];?>";
    var p1 = from_raw.lastIndexOf('(');
    var q1 = from_raw.lastIndexOf(')');
    var p2 = to_raw.lastIndexOf('(');
    var q2 = to_raw.lastIndexOf(')');
    var from_arr = from_raw.slice( p1+1 , q1 );
    var to_arr = to_raw.slice( p2+1 , q2 );

    var search_parameters = 
    {
        from: from_arr,
        to: to_arr,
        adult_count: "<?php echo $_SESSION['details']['adult_count'];?>", 
        youth_count: "<?php echo $_SESSION['details']['youth_count'];?>", 
        kids_count: "<?php echo $_SESSION['details']['kids_count'];?>", 
        total_count: "<?php echo $_SESSION['details']['total_count'];?>",
        oneway_date: "<?php echo $_SESSION['details']['dp_dates'][($_SESSION['cnt_val']-1)];?>",
        airline_preference: "<?php echo '';?>",
        travel_class: "<?php echo 'All';?>"
    };
  <?php endif;?>
    $.ajax({
        url: "<?php echo site_url('api/flights/search_flights_oneway');?>",
        type: "POST",
        data: { data : search_parameters }
    })
    .done(function (flData){
        var result = $.parseJSON(flData);
        <?php if( isset($_GET['oneway_change_cab']) && $_GET['oneway_change_cab'] == "true" ):?>
          var search_base = "<?php echo $_SESSION['currentUrlFlight'];?>";
        <?php else:?>
          var search_base = "<?php echo site_url('common/change_travel_mode?city_from='.$_SESSION['details']['from'][($_SESSION['cnt_val']-1)].'&city_to='.$_SESSION['details']['to'][($_SESSION['cnt_val']-1)].'&adult_count='.$_SESSION['details']['adult_count'].'&youth_count='.$_SESSION['details']['youth_count'].'&journey_date='.$_SESSION['details']['dp_dates'][($_SESSION['cnt_val']-1)].'&total_count='.$_SESSION['details']['total_count'].'&flight_type=multi&travel_mode=flight');?>";
        <?php endif;?>
        $('#fl_spin').hide();
        if(result !== "false")
        {
            $('.flightOptionView').attr('href', search_base);
            $('.flightOptionDetails').show();
            $('.flightOptionDetailsLine1').html('Flights to '+ dest[0]+' From:');
            $('.flightOptionDetailsLine2').html('&#x20B9; ' +result.fare_min);
            $('.flightOptionView').show();
        }
        else
        {
            $('.flightOptionDetails').hide();
            $('.flightOptionView').hide();
            $('.noFlightsLabel').show();
        }
    });

    <?php endif;?>


	//select or die
	// $('select').selectOrDie({
	// 	size:3
	// });
	//select or die end

    //popover begin
    $("#popover-toggle-1").popover({
        html: true,
        container: 'body',
        content: function() {
            return $('#popoverHiddenContent-1').html();
        },
        title: function() {
            return $('#popoverHiddenTitle-1').html();
        },
    });

    $("#popover-toggle-2").popover({
        html: true,
        container: 'body',
        content: function() {
            return $('#popoverHiddenContent-2').html();
        },
        title: function() {
            return $('#popoverHiddenTitle-2').html();
        },
    });

    $("#popover-toggle-3").popover({
        html: true,
        container: 'body',
        content: function() {
            return $('#popoverHiddenContent-3').html();
        },
        title: function() {
            return $('#popoverHiddenTitle-3').html();
        },
    });

    $("#popover-toggle-4").popover({
        html: true,
        container: 'body',
        content: function() {
            return $('#popoverHiddenContent-4').html();
        },
        title: function() {
            return $('#popoverHiddenTitle-4').html();
        },
    });

    $('.btn-ed').on('click', function(){
        var fl_id = $(this).attr('id');
        var ed_arr = fl_id.split('-');
        var ed_num = ed_arr[1];
        var loc = "<?php echo site_url('api/flights/edit_fl?ind=')?>"+ed_num;
        window.location.href = loc;
    });
    //popover end


	//popover data
	    $("#popover-toggle").popover({
	        html: true,
	        container: 'body',
	        content: function() {
	            return $('#popoverHiddenContent').html();
	        },
	        title: function() {
	            return $('#popoverHiddenTitle').html();
	        },
	    });

        $(".farePopover-1").popover({
            html: true,
            container: 'body',
            content: function() {
                return $('#popoverFareHiddenContent-1').html();
            },
        });
	    $('#edit-btn').on('click', function(){
	        window.location.reload();
	    });

        $(".farePopover-6").popover({
            html: true,
            container: 'body',
            content: function() {
                return $('#popoverFareHiddenContent-6').html();
            },
        });
        $('#edit-btn').on('click', function(){
            window.location.reload();
        });

        $(".farePopover-21").popover({
            html: true,
            container: 'body',
            content: function() {
                return $('#popoverFareHiddenContent-21').html();
            },
        });
        $('#edit-btn').on('click', function(){
            window.location.reload();
        });
    //popover data end

	<?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 ):?>	

	$('button.btn-del-cab').on('click',function(){
		var ind = parseInt($(this).attr('id'));
		var ind_str = "";
		var sedan_chk_data = <?php echo json_encode($data);?>;
		var flag = 0;

		if( ind === 0 ){
			ind_str = "compact";
			var selected_seats = 4;
		}if( ind === 1 ){
			ind_str = "sedan";
			var selected_seats = 4;
		}if( ind === 2){
			ind_str = "suv";
			var selected_seats = 6;
		}

		var index = car_pax[ind_str].indexOf(selected_seats);

		if (index > -1) {
            addedPax--;
			car_pax[ind_str].splice(index, 1);
		}else{
            $('.selectMessageBlock .errorMessage').html('No cabs to remove').fadeIn(200);
            setTimeout(function(){
              $('.selectMessageBlock .errorMessage').fadeOut(200);
            }, 3000);
			return false;
		}

		rem_pax = rem_pax + (selected_seats);
		times_entered = 1;

		// calc_seats( compact_seats, rem_pax, times_entered );
		// calc_seats( sedan_seats, rem_pax, times_entered );
		// calc_seats( suv_seats, rem_pax, times_entered );

		if( $('#cabTotalFare').html() === "" ){
			var total_fare = 0;
		}else{ 
			var total_fare = parseInt($('#cabTotalFare').html());
		}
		var clicked_id = this.id;	
		var c = $('#car_field'+clicked_id).html().split("(");
		var d = c[1].split(" ");
		c[1] = d[0];
		var incr = parseInt(c[1]) - 1;
		$('#car_field'+clicked_id).html(c[0] + " ("+incr+" Selected)");
		if( c[0] === "MUV/SUV" ){
			$('#suv').val(incr-1);
		}else{
			$('#'+c[0]).val(incr-1);
		}
		total_fare = total_fare - parseInt(car_fare[parseInt(clicked_id)]);
		$('#cabTotalFare').html(total_fare);
		$('.totFare2 span').html(total_fare);
		$('input#total_fare').val(total_fare);
		$('.totFare2').show();
		$('.totFare2 span').autoNumeric('set', total_fare);

		add_cab_details(c[0], car_model[parseInt(clicked_id)], clicked_id, car_fare[parseInt(clicked_id)]);
	});

	$('button.btn-add-cab').on('click',function(){
		if( rem_pax > 0 ){
			var ind = parseInt($(this).attr('id'));
			var ind_str = "";
			var sedan_chk_data = <?php echo json_encode($data);?>;
			var flag = 0;
			//console.log(sedan_chk_data['car_model'][0]); 

			if( ind === 0 ){
				ind_str = "compact";
				var selected_seats = 4;
			}if( ind === 1 ){
				ind_str = "sedan";
				var selected_seats = 4;
			}if( ind === 2){
				ind_str = "suv";
				var selected_seats = 6;
			}

			car_pax[ind_str].push(selected_seats);

			rem_pax = rem_pax - (selected_seats);
			times_entered = 1;
			enteredFuntion = 0;

			// calc_seats( compact_seats, rem_pax, times_entered );
			// calc_seats( sedan_seats, rem_pax, times_entered );
			// calc_seats( suv_seats, rem_pax, times_entered );

			if( $('#cabTotalFare').html() === "" ){
				var total_fare = 0;
			}else{ 
				var total_fare = parseInt($('#cabTotalFare').html());
			}
			var clicked_id = this.id;	
			var c = $('#car_field'+clicked_id).html().split("(");
			var d = c[1].split(" ");
			c[1] = d[0];
			var incr = parseInt(c[1])+1;
			$('#car_field'+clicked_id).html(c[0] + " ("+incr+" Selected)");
			if( c[0] === "MUV/SUV" ){
				$('#suv').val(incr-1);
			}else{
				$('#'+c[0]).val(incr-1);
			}
			total_fare = total_fare + parseInt(car_fare[parseInt(clicked_id)]);
			$('#cabTotalFare').html(total_fare);
			$('.totFare2 span').html(total_fare);
			$('input#total_fare').val(total_fare);
			$('.totFare2').show();
			$('.totFare2 span').autoNumeric('set', total_fare);

			add_cab_details(c[0], car_model[parseInt(clicked_id)], clicked_id, car_fare[parseInt(clicked_id)]);
            addedPax++;
		}else{
            $('.selectMessageBlock .errorMessage').html('No more passengers left').fadeIn(200);
            setTimeout(function(){
              $('.selectMessageBlock .errorMessage').fadeOut(200);
            }, 3000);
			return false;
		}
	});
	<?php else:?>

	$('button.btn-del-cab').on('click',function(){
		var ind = parseInt($(this).attr('id'));
		var ind_str = "";
		var sedan_chk_data = <?php echo json_encode($data);?>;
		var flag = 0;

		if( ind === 0 ){
			ind_str = "compact";
			var selected_seats = 4;
		}if( ind === 1 ){
			ind_str = "sedan";
			var selected_seats = 4;
		}if( ind === 2){
			ind_str = "suv";
			var selected_seats = 6;
		}

		var index = car_pax[ind_str].indexOf(selected_seats);

		if (index > -1) {
            addedPax--;
			car_pax[ind_str].splice(index, 1);
		}else{
            $('.selectMessageBlock .errorMessage').html('No cabs to remove').fadeIn(200);
            setTimeout(function(){
              $('.selectMessageBlock .errorMessage').fadeOut(200);
            }, 3000);
			return false;
		}


        rem_pax = rem_pax + (selected_seats);
        times_entered = 1;

		// calc_seats( compact_seats, rem_pax, times_entered );
		// calc_seats( sedan_seats, rem_pax, times_entered );
		// calc_seats( suv_seats, rem_pax, times_entered );

		if( $('#cabTotalFare').html() === "" ){
			var total_fare = 0;
		}else{ 
			var total_fare = parseInt($('#cabTotalFare').html());
		}
		var clicked_id = this.id;	
		var c = $('#car_field'+clicked_id).html().split("(");
		var d = c[1].split(" ");
		c[1] = d[0];
		var incr = parseInt(c[1]) - 1;
		$('#car_field'+clicked_id).html(c[0] + " ("+incr+" Selected)");
		if( c[0] === "MUV/SUV" ){
			$('#suv').val(incr-1);
		}else{
			$('#'+c[0]).val(incr-1);
		}
		total_fare = total_fare - parseInt(car_fare[parseInt(clicked_id)]);
		$('#cabTotalFare').html(total_fare);
		$('.totFare2 span').html(total_fare);
		$('input#total_fare').val(total_fare);
		$('.totFare2').show();
		$('.totFare2 span').autoNumeric('set', total_fare);

		add_cab_details(c[0], car_model[parseInt(clicked_id)], clicked_id, car_fare[parseInt(clicked_id)]);
	});

	$('button.btn-add-cab').on('click',function(){
        if( rem_pax > 0 ){
            var ind = parseInt($(this).attr('id'));
            var ind_str = "";
            var sedan_chk_data = <?php echo json_encode($data);?>;
            var flag = 0;
            //console.log(sedan_chk_data['car_model'][0]); 

            if( ind === 0 ){
                ind_str = "compact";
                var selected_seats = 4;
            }if( ind === 1 ){
                ind_str = "sedan";
                var selected_seats = 4;
            }if( ind === 2){
                ind_str = "suv";
                var selected_seats = 6;
            }

            car_pax[ind_str].push(selected_seats);

            rem_pax = rem_pax - (selected_seats);
            times_entered = 1;
            enteredFuntion = 0;

            // calc_seats( compact_seats, rem_pax, times_entered );
            // calc_seats( sedan_seats, rem_pax, times_entered );
            // calc_seats( suv_seats, rem_pax, times_entered );

            if( $('#cabTotalFare').html() === "" ){
                var total_fare = 0;
            }else{ 
                var total_fare = parseInt($('#cabTotalFare').html());
            }
            var clicked_id = this.id;   
            var c = $('#car_field'+clicked_id).html().split("(");
            var d = c[1].split(" ");
            c[1] = d[0];
            var incr = parseInt(c[1])+1;
            $('#car_field'+clicked_id).html(c[0] + " ("+incr+" Selected)");
            if( c[0] === "MUV/SUV" ){
                $('#suv').val(incr-1);
            }else{
                $('#'+c[0]).val(incr-1);
            }
            total_fare = total_fare + parseInt(car_fare[parseInt(clicked_id)]);
            $('#cabTotalFare').html(total_fare);
            $('.totFare2 span').html(total_fare);
            $('input#total_fare').val(total_fare);
            $('.totFare2').show();
            $('.totFare2 span').autoNumeric('set', total_fare);

            add_cab_details(c[0], car_model[parseInt(clicked_id)], clicked_id, car_fare[parseInt(clicked_id)]);
            addedPax++;
        }else{
            $('.selectMessageBlock .errorMessage').html('You can only choose a maximum of 9 passengers.').fadeIn(200);
            setTimeout(function(){
              $('.selectMessageBlock .errorMessage').fadeOut(200);
            }, 5000);
            return false;
        }
	});

	<?php endif;?>
})();

function add_cab_details( car_type, car_model, clicked_id, car_fare ){
	switch( car_type.trim() ){
		case 'Compact':
			add_comapct(car_model, car_fare, clicked_id);
			break;
		case 'Sedan':
			add_sedan(car_model, car_fare, clicked_id);
			break;
		case 'SUV':
			add_suv(car_model, car_fare, clicked_id);
			break;
	}
}

function add_comapct(car_model, car_fare, clicked_id){
	var c = $('#car_field'+clicked_id).html().split("(");
	var d = c[1].split(" ");
	c[1] = d[0];
	var multiplier = parseInt(c[1]);
	$('#compactDetails #carModel').html(car_model);
	$('#compactDetails #multiple').html(multiplier);
	$('#compactDetails #perCabFare span').html(car_fare);
	total_fare = parseInt(multiplier)*parseInt(car_fare);
	$('#compactDetails #totalFare span').html(total_fare);
}

function add_sedan(car_model, car_fare, clicked_id){
	var c = $('#car_field'+clicked_id).html().split("(");
	var d = c[1].split(" ");
	c[1] = d[0];
	var multiplier = parseInt(c[1]);
	$('#sedanDetails #carModel').html(car_model);
	$('#sedanDetails #multiple').html(multiplier);
	$('#sedanDetails #perCabFare span').html(car_fare);
	total_fare = parseInt(multiplier)*parseInt(car_fare);
	$('#sedanDetails #totalFare span').html(total_fare);
}

function add_suv(car_model, car_fare, clicked_id){
	var c = $('#car_field'+clicked_id).html().split("(");
	var d = c[1].split(" ");
	c[1] = d[0];
	var multiplier = parseInt(c[1]);
	$('#suvDetails #carModel').html(car_model);
	$('#suvDetails #multiple').html(multiplier);
	$('#suvDetails #perCabFare span').html(car_fare);
	total_fare = parseInt(multiplier)*parseInt(car_fare);
	$('#suvDetails #totalFare span').html(total_fare);
}

// function calc_seats(availabe_seats, remaining_pax, times_entered){
// 	var index = enteredFuntion;
// 	if( times_entered === 1 ){
// 		$('#num_of_pax-'+index).html('');
// 	}
// 	if( availabe_seats != 0 && remaining_pax >= availabe_seats ){
// 		for( var i = 1 ; i <= availabe_seats ; i++ ){
// 			$('#num_of_pax-'+index).append('<option value="'+i+'">'+i+'</option>');
// 		}
// 	}else if( availabe_seats != 0 && remaining_pax <= availabe_seats ){
// 		for( var i = 1 ; i <= remaining_pax ; i++ ){
// 			$('#num_of_pax-'+index).append('<option value="'+i+'">'+i+'</option>');
// 		}
// 	}
// 	enteredFuntion++;
// 	return true;
// }

</script>