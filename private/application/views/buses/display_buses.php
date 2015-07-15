<?php 
    if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 ){
        $count = $_GET['flight_num'];
        $_SESSION['cnt_val'] = $_GET['flight_num'];
    }            
?>
<style>
    #search tbody td:nth-child(5){
        font-size: 12px;
        font-weight: normal;
        font-style: normal;
    }  
    .resultsRow{
        margin-left: 10px;
        margin-top: -7.5px;
    }
    .ellipse{
        height:18px;
    }
    .lowSeatCaution{
        color: #FF8300;
        font-weight: 600;
    }
    .filtersLabel label{
        font-size: 12px;
    }
    table.dataTable{
        margin-top:0 !important;
    }
</style>
<form action="<?php echo site_url('bus_api/buses/getTripDetails');?>" method="get" id="book_bus_form" style="display:none;">
    <input type="text" name="boardingpnts" id="allData" style="display:none;"/>
    <input type="text" name="cancellation" id="cancelPolicy" style="display:none;"/>
    <input type="text" name="chosenone" id="busId" style="display:none;"/>
    <input type="text" name="source" id="source_name" value="<?php echo $_GET['source_city_name'];?>" style="display:none;"/>
    <input type="text" name="destination" id="destination_name" value="<?php echo $_GET['destination_city_name'];?>" style="display:none;"/>
    <input type="text" name="destinationList" id="destination_id" value="<?php echo $_GET['destination_id'];?>" style="display:none;"/>
    <input type="text" name="sourceList" id="source_id" value="<?php echo $_GET['source_id'];?>" style="display:none;"/>
    <input type="text" name="datepicker" id="journey_date" value="<?php echo $_GET['journey_date'];?>" style="display:none;"/>
    <?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 ):?>
        <input type="text" name="get_req_active" value="1" style="display:none;"/>
    <?php endif;?>
</form>

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

<div class="wrap">
    <div class="container-fluid clear-top">    
        <div class="row resultsSearchContainer navbar-fixed-top">
            <div class="panel-group" id="accordionSearch">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                        <?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 ):?>
                        <?php $count = $_SESSION['cnt_val'];?>
                            <div class="accord-content container-fluid">
                                <div class="row fl_overwiew">
                                        <?php for( $n = 0 ; $n < $count - 1 ; $n++):?>
                                            <div class="col-lg-5 fl_septr1">
                                                <div class="col-lg-2 fl_no"><?php echo $n+1;?></div>
                                                <?php if( isset($_SESSION['flight_data'][$n]['ov']->mode) && $_SESSION['flight_data'][$n]['ov']->mode == 'bus' ):?>
                                                    <div class="col-lg-3 bus_bg_nav sr_only"></div>
                                                <?php elseif(isset($_SESSION['flight_data'][$n]['ov']->mode) && $_SESSION['flight_data'][$n]['ov']->mode == 'cab'):?>
                                                    <div class="col-lg-3 cab_bg_nav sr_only"></div>
                                                <?php else:?>
                                                    <div class="col-lg-3 fl_bg_nav sr_only"></div>
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
                                                        <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date( 'd M Y', strtotime($_SESSION['details']['dates'][$n]) );?></div>
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
                                        <div class="col-lg-3 bus_bg_nav sr_only"></div>
                                        <div class="col-lg-14 fl_info">
                                            <div class="row">
                                                <div class="col-lg-offset-4 col-lg-20 center-align-text travel-text">
                                                    <div class="row center-align-text">
                                                        <div class="col-lg-11 remove-padding ellipse" id="origin"><?php echo $_GET['source_city_name'];?></div>
                                                        <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                                        <div class="col-lg-11 remove-padding ellipse" id="destination"><?php echo $_GET['destination_city_name'];?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date('D, jS M Y', strtotime($_GET['journey_date']));?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-23 col-lg-offset-1 center-align-text fare-padding TotFare1"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 pull-right">
                                            <div class="row fl_btn1 hide">
                                                <button type="button" class='btn btn-change btn-ed'>EDIT</button>
                                                <button type="button" class='btn btn-change btn-de' id='popover-toggle' data-toggle="popover" data-placement="bottom">DETAILS</button>
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
                                <div class="row fl_overwiew hulk-class">
                                    <div class="col-lg-5 fl_septr1">
                                        <div class="col-lg-2 fl_no">1</div>
                                        <div class="col-lg-3 bus_bg_nav sr_only"></div>
                                        <div class="col-lg-14 fl_info">
                                            <div class="row">
                                                <div class="col-lg-offset-4 col-lg-20 center-align-text travel-text">
                                                    <div class="row center-align-text">
                                                        <div class="col-lg-11 remove-padding ellipse" id="origin"><?php echo $_GET['source_city_name'];?></div>
                                                        <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                                        <div class="col-lg-11 remove-padding ellipse" id="destination"><?php echo $_GET['destination_city_name'];?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date('D, jS M Y', strtotime($_GET['journey_date']));?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-23 col-lg-offset-1 center-align-text fare-padding TotFare1"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 pull-right">
                                            <div class="row fl_btn1 hide">
                                                <button type="button" class='btn btn-change btn-ed'>EDIT</button>
                                                <button type="button" class='btn btn-change btn-de' id='popover-toggle' data-toggle="popover" data-placement="bottom">DETAILS</button>
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
                            <?php $this->load->view('buses/search_view.php');?>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid main clear-top">  
        <div class="row resultsFilters">
            <div class="col-xs-24 col-sm-4 col-sm-offset-cust-2 filterWeatherContainer">
                <div class="row">
                    <div class="col-xs-24 filtersContainer filtersLabel">
                        <div class="row">
                            <div class="col-xs-24 filterByArea filter"><h4>Filter By:</h4></div>
                        </div>
                        <div class="row">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#amountRangeAccordion">
                                                Price
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="amountRangeAccordion" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body">
                                            <div class="simple">
                                                <div class="slider-unit align-c">
                                                    <div id="slider-range" class="slider-control"></div>
                                                    <div>
                                                        <span>&#x20B9; </span><span id="range-bottom" class="range-value"></span> to <span>&#x20B9; </span><span id="range-top" class="range-value"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseFive">
                                                Departure Time
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body">
                                            <div class="simple">
                                                <div class="range-slide" id="slider-range-duration"></div>
                                                <span id="range-bottom-duration" class="range-value"></span> to <span id="range-top-duration" class="range-value"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#airlineAccordion">
                                                Travels
                                            </a>
                                            <a href="#" class="busesShowAllLink">Uncheck All</a>
                                        </h4>
                                    </div>
                                    <div id="airlineAccordion" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body" id="buses-check">
                                            <?php foreach($filter_data['travels'] as $fd):?>
                                                <div><label for="<?php echo $fd?>"><input id="<?php echo $fd?>" type="checkbox" value="<?php echo $fd;?>" checked="checked"><?php echo $fd;?></label></div>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseSix">
                                                Bus Type
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSix" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body" id="bus-type-check">
                                            <div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="true" checked="checked"/>AC</label></div>
                                            <div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="false" checked="checked"/>Non-AC</label></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseSix">
                                                Seat type
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSix" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body" id="seater-check">
                                            <div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="true"/>Show only Sleepers.</label></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#keywordAccordion">
                                                Keyword
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="keywordAccordion" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body">
                                            <input type="text" class="keywordSearch" id="keywordSearchFullWidth" placeholder="Enter Keyword" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                
                    </div>
                </div>
                <div class="row">
                    <div class="searchResultsWeatherDetails">
                        <div class="weatherDetails row center-align-text">
                            <div class="col-xs-24 weather_spinner">
                                <div id="spinner" class="spinner">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                    <div class="rect4"></div>
                                    <div class="rect5"></div>
                                </div>
                            </div>
                            <div class="col-xs-12 from">
                                <div class="row">
                                    <div class="col-xs-24 date"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 icon"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 summary h4"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 temperature"></div>
                                </div>
                            </div>
                            <div class="col-xs-12 to">
                                <div class="row">
                                    <div class="col-xs-24 date"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 icon"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 summary h4"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 temperature"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-24 col-sm-18 col-sm-offset-cust-3 resultsArea">
                <?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 || ( isset($_GET['flight_type']) && $_GET['flight_type'] == "local" ) ):?>
                <div class="row busAltFareMarginBottom">
                    <div  class="col-xs-9">
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
                                <div class="row flightOptionDetailsLine2">&#x20B9; 3999.00</div>
                            </div>
                            <a href="#" class="col-xs-4 flightOptionView">VIEW</a>
                        </div>
                    </div>
                    <div class="col-xs-9 col-xs-offset-1">
                        <div class="cabOptionContainer row">
                            <div class="col-xs-4 cabOptionLabel"></div>
                            <div class="col-xs-18 noCabsLabel">No Cabs available</div>
                            <div class="col-xs-18" id="cab_spin">
                                <div id="cab_spinner" class="spinner">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                    <div class="rect4"></div>
                                    <div class="rect5"></div>
                                </div>
                            </div>
                            <div class="col-xs-16 cabOptionDetails">
                                <div class="row cabOptionDetailsLine1">Cabs to Chennai starting from:</div>
                                <div class="row cabOptionDetailsLine2">&#x20B9; 3999.00</div>
                            </div>
                            <a href="#" class="col-xs-4 cabOptionView">VIEW</a>
                        </div>
                    </div>
                </div>
                <?php endif;?>
                <div class="row">
                    <div class="col-xs-24 col-sm-24 results remove-padding">
                        <div class="compare-unit select niceform">
                            <table class="table table-hover-custom select-list smaller bus-table-spacing hide" id="search">
                                <thead>
                                    <th class="th-cell-left left-text"><h4>Travels</h4></th>
                                    <th class="th-cell-mid left-text"><h4>Bus Type</h4></th>
                                    <th class="th-cell-mid left-text"><h4>Departure</h4></th>
                                    <th class="th-cell-mid left-text"><h4>Departure_hidden</h4></th>
                                    <th class="th-cell-mid left-text"><h4>Arrival</h4></th>
                                    <th class="th-cell-mid left-text"><h4>Duration</h4></th>
                                    <th class="th-cell-mid left-text"><h4>Seats</h4></th>
                                    <th class="th-cell-mid left-text price_disp"><h4>Fare</h4></th>
                                    <th class="th-cell-mid left-text"><h4>ac_hidden</h4></th>
                                    <th class="th-cell-mid left-text"><h4>Fare_hidden</h4></th>
                                    <th class="th-cell-mid left-text"><h4>seater_hidden</h4></th>
                                    <th class="th-cell-right left-text"></th>
                                </thead>
                                <tbody class="center-align-text">
                                <?php for( $i = 0 ; $i < count($data['availableTrips']) ; $i++)
                                {
                                    echo '<tr>';
                                    echo '<td class="bus-travels left-text">'.$data['availableTrips'][$i]['travels'].'</td>';
                                    echo '<td class="bus-type-text left-text">'.$data['availableTrips'][$i]['busType'].'</td>';
                                    $oneDay=24*60;
                                    $noOfDays = floor($data['availableTrips'][$i]['departureTime'] / $oneDay);
                                    $time = ($data['availableTrips'][$i]['departureTime']) % $oneDay;
                                    $hours = floor($time/60);
                                    $minutes = floor($time%60);
                                    if($hours < 10)
                                        $hours = '0'.$hours;
                                    if($minutes < 10)
                                        $minutes = '0'.$minutes;
                                    echo '<td>'.$hours.':'.$minutes.'</td>';
                                    echo '<td>'.$data['availableTrips'][$i]['departureTime'].'</td>';
                                    $oneDay=24*60;
                                    $noOfDays = floor($data['availableTrips'][$i]['arrivalTime'] / $oneDay);
                                    $time = ($data['availableTrips'][$i]['arrivalTime']) % $oneDay;
                                    $hours = floor($time/60);
                                    $minutes = floor($time%60);
                                    if($hours < 10)
                                        $hours = '0'.$hours;
                                    if($minutes < 10)
                                        $minutes = '0'.$minutes;
                                    echo '<td class="bus-type-dur">'.$hours.':'.$minutes.'</td>';
                                    $oneDay=24*60;
                                    $noOfDays = floor(($data['availableTrips'][$i]['arrivalTime'] - $data['availableTrips'][$i]['departureTime']) / $oneDay);
                                    $time = ($data['availableTrips'][$i]['arrivalTime'] - $data['availableTrips'][$i]['departureTime']) % $oneDay;
                                    $hours = floor($time/60);
                                    $minutes = floor($time%60);
                                    if($hours < 10)
                                        $hours = '0'.$hours;
                                    if($minutes < 10)
                                        $minutes = '0'.$minutes;
                                    echo '<td class="bus-type-dur">'.$hours.'h '.$minutes.'m'.'</td>';

                                    if( $data['availableTrips'][$i]['availableSeats'] < 10 ){
                                        echo '<td class="lowSeatCaution">'.$data['availableTrips'][$i]['availableSeats'].'</td>';
                                    }else{
                                        echo '<td>'.$data['availableTrips'][$i]['availableSeats'].'</td>';
                                    }

                                    if(is_array($data['availableTrips'][$i]['fares']))
                                        echo '<td><i class="fa fa-inr"></i>&nbsp;<span class="currency total_fare bus_total_fare">'.$data['availableTrips'][$i]['fares'][0].'</span>'.'<br /><h6>Details</h6></td>'; 
                                    else   
                                        echo '<td><i class="fa fa-inr"></i>&nbsp;<span class="currency total_fare bus_total_fare">'.$data['availableTrips'][$i]['fares'].'</span>'.'<br /><h6>Details</h6></td>';
                                        
                                    echo '<td>'.$data['availableTrips'][$i]['AC'].'</td>';

                                    if(is_array($data['availableTrips'][$i]['fares']))
                                        echo '<td>'.$data['availableTrips'][$i]['fares'][0].'</td>';
                                    else   
                                        echo '<td>'.$data['availableTrips'][$i]['fares'].'</td>';
                                    echo '<td>'.$data['availableTrips'][$i]['seater'].'</td>';  
                                    if( $data['availableTrips'][$i]['availableSeats'] == 0 ){
                                        echo '<td class="bookBtn"><button type="button" class="btn btn-change book_btn" disabled="disabled">SOLD OUT</button></td>';    
                                    }else{
                                        echo '<td class="bookBtn"><button type="button" data-busid="'.$data['availableTrips'][$i]['id'].'" class="btn btn-change book_btn">SELECT</button></td>';
                                    }
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="can_fare_hidden hide"></div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    var cancelPolicy = [];
    var return_data = <?php echo json_encode($data);?>;
    var selectedBuses = [];
    var selectedBusType = [];
    var selectedSeats = [];
    var max_round = 0;
    var min_round = 0;
    var rangeSlider = $( "#slider-range" );
    var rangeSliderDuration = $( "#slider-range-duration" );
    var min_duration = <?php echo $filter_data['min_time'];?>;
    var max_duration = <?php echo $filter_data['max_time'];?>;
    var min_fare = <?php echo $filter_data['min_fare'];?>;
    var max_fare = <?php echo $filter_data['max_fare'];?>;

    max_round = max_fare/100;
    min_round = min_fare/100;

    max_round = (Math.ceil(max_round)*100) + 100;
    min_round = (Math.floor(min_round)*100) - 100;

    var oTable = $('#search').DataTable({
        "bDeferRender": false,
        "aoColumnDefs": [
            { "aDataSort": [ 9, 7 ], "aTargets": [ 7 ] },
            { "targets": [3, 8, 9, 10], "visible": false },
            { "targets": 11, "orderable": false }
        ],
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('id', iDataIndex);
        },
        "language": {
            "zeroRecords": '<div> <div class="row"> <div class="col-xs-24 center-align-text"> <h2>Sorry, No matching records found for your search</h2> <span class="h4 mod_search_error" data-toggle="collapse" data-target="#collapseSearch" >Modify Search</span> <span>|</span> <span onclick="javascript: $('+ "'.busesShowAllLink'" +').trigger('+"'resetAllFilters'"+');" class="h4 reset_search_error">Reset Filters</span> </center> </div></div></div>'
        },
        "bDestroy": true,
        "aaSorting": [[7, 'asc']],
        "bPaginate": false,
        "bInfo": false,
        "bFilter": true,
        "bScrollCollapse": true,
        "fnInitComplete": function() {
            this.fnAdjustColumnSizing(true);
        }
    });

$(document).ready(function(){

    //tooltip code
        $('#origin').on('mouseenter', function(){
            $(this).tooltip({
                placement: 'bottom',
                trigger: 'hover',
                title: "<?php echo $_GET['source_city_name'];?>"
            });
            $(this).tooltip('show');
        });

        $('#destination').on('mouseenter', function(){
            $(this).tooltip({
                placement: 'bottom',
                trigger: 'hover',
                title: "<?php echo $_GET['destination_city_name'];?>"
            });
            $(this).tooltip('show');
        });
    //end tooltip code

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

    initRangeSlider();
    initRangeSliderDuration();

    $('#range-bottom, #range-top, .total_fare, .total_fare_det span, .base_fare span, .service_fare span, .can_fare span, .can_fare_hidden').autoNumeric({
        aSep : ',',
        dGroup : 2
    });

    // This should be after the airlines are enabled
    $('#buses-check input:checked').each(function() {
        selectedBuses.push($(this).attr('value'));
    });

    $('#buses-check').on('click',"input",function(){
        flashTable();
        $('.busesShowAllLink').html('Check All');
        var busContainer =  $('#buses-check');
        var busCheckboxes = busContainer.find(':input');
        var busCheckboxesChecked = busContainer.find(':input:checked');
        if( busCheckboxesChecked.length === busCheckboxes.length ){
            $('.busesShowAllLink').html('Uncheck All');
        }
        $('#buses-check input').each(function() {
            var isChecked = this.checked;
            if(isChecked) {
                var index = selectedBuses.indexOf(this.value);
                if(index == -1) {
                    selectedBuses.push(this.value);    
                }                
            } else {
                var index = selectedBuses.indexOf(this.value);
                if(index > -1) {
                    selectedBuses.splice(index,1);
                }
            }
        });
        oTable.draw();
    });

    $('.keywordSearch').on('keyup',function(){
         oTable.search($('.keywordSearch').val()).draw();
    });

    // This should be after the airlines are enabled
    $('#bus-type-check input:checked').each(function() {
        selectedBusType.push($(this).attr('value'));
    });

    $('#bus-type-check').on('click',"input",function(){
        flashTable();
        $('#bus-type-check input').each(function() {
            var isChecked = this.checked;
            if(isChecked) {
                var index = selectedBusType.indexOf(this.value);
                if(index == -1) {
                    selectedBusType.push(this.value);    
                }                
            } else {
                var index = selectedBusType.indexOf(this.value);
                if(index > -1) {
                    selectedBusType.splice(index,1);
                }
            }
        });
        oTable.draw();
    });

    // Enable all ticket by default
    selectedSeats.push('true');
    selectedSeats.push('false');

    // This should be after the ticket are enabled
    $('#seater-check input:checked').each(function() {
        selectedSeats.push($(this).attr('value'));
    });

    // this section is to add data within the details row on click of a row.
    $('#search tbody').on('click', 'td', function () {

        if( $(this).attr('class') === 'tr-drawer' || typeof $(this).attr('class') === 'undefined' ){
            return false;
        }else{
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var row_details_id = tr.attr('id');

            if( $(this).attr('class') == 'bookBtn' ){
                return true;
            }

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                tr.next('tr').removeClass('tr-drawer');
            } else {
                // Open this row
                row.child( format(row_details_id) ).show();
                tr.addClass('shown');
                tr.next('tr').addClass('tr-drawer');
            }

            var detailsRow = $('.details-row').height() - 50;
            if( detailsRow >= 250 ){
                $('.bus-cancel-text').css('height', detailsRow );
            }
        }

    });

    $('#seater-check').on('click',"input",function(){
        flashTable();
        $('#seater-check input').each(function() {
            var isChecked = this.checked;
            if( isChecked ){
                var index = selectedSeats.indexOf(this.value);
                if(index > -1){
                    selectedSeats.splice(index, 1);    
                }
            } else {
                var ind1 = selectedSeats.indexOf('true');
                var ind2 = selectedSeats.indexOf('false');
                if( ind1 == -1){
                    selectedSeats.push('true');
                }
            }
        });
        oTable.draw();
    });

    $('button.book_btn').on('click', function(){
        <?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 || ( isset($_GET['flight_type']) && $_GET['flight_type'] == "local" ) ):?>
            flightsAjax.abort();
            cabsAjax.abort();
        <?php endif;?>
        $('button.book_btn').attr('disabled', true);
        $('input#busId').val($(this).data('busid'));
        var table_row = $(this).closest('tr');
        var ID = table_row.attr('id');
        $('input#allData').val(JSON.stringify(return_data.availableTrips[ID]));
        if(!(cancelPolicy[ID])){
            cancellation(ID);
        }
        $('input#cancelPolicy').val(cancelPolicy[ID]);
        $('#book_bus_form').submit();
    });

    <?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 || ( isset($_GET['flight_type']) && $_GET['flight_type'] == "local" ) ):?>
        <?php if( isset($_GET['flight_type']) && $_GET['flight_type'] == "local" ):?>
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
            var source_split = "<?php echo $_GET['source_city_name'];?>";  
            var src = source_split.split(",");
                if(src[0] === "Delhi")
                {
                    src[0] = "Delhi /NCR";
                }
            var destination_split = "<?php echo $_GET['destination_city_name'];?>";
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

        $('#cab_spinner').show();
        var cabsAjax = $.ajax({
            url: "<?php echo site_url('cab_api/cabs/flights_to_cabs');?>",
            type: "POST",
            data: {data : cab_search_parameters}
        })
        .done(function (cabData){
            var result = $.parseJSON(cabData);
            <?php if( isset($_GET['flight_type']) && $_GET['flight_type'] == 'local' ):?>
                var search_base = "<?php echo site_url('cab_api/cabs/flights_to_cabs?source='.$_SESSION['onewayGetStrings']['from'].'&destination='.$_SESSION['onewayGetStrings']['to'].'&adult_count='.$_SESSION['onewayGetStrings']['adult_count'].'&youth_count='.$_SESSION['onewayGetStrings']['youth_count'].'&oneway_change_cab=true&journey_date='.$_SESSION['onewayGetStrings']['oneway_date'].'&total_count='.$_SESSION['onewayGetStrings']['total_count']);?>";
            <?php else:?>
                var search_base = "<?php echo site_url('common/change_travel_mode?source='.$_GET['source_city_name'].'&destination='.$_GET['destination_city_name'].'&adult_count='.$_SESSION['details']['adult_count'].'&youth_count='.$_SESSION['details']['youth_count'].'&flight_type=local&journey_date='.$_SESSION['details']['dp_dates'][0].'&total_count='.$_SESSION['details']['total_count'].'&oneway_change_cab=true&travel_mode=cab');?>";
            <?php endif;?>
            $('#cab_spin').hide();
            if(result !== false)
            {
                $('.cabOptionView').attr('href', search_base);
                $('.cabOptionDetails').show();
                $('.cabOptionDetailsLine1').html('Cabs to '+ dest[0]+' From:');
                $('.cabOptionDetailsLine2').html('&#x20B9; ' +result);
                $('.cabOptionView').show();
            }
            else
            {
                $('.cabOptionDetails').hide();
                $('.cabOptionView').hide();
                $('.noCabsLabel').show();
            }
        });

    <?php if( isset($_GET['flight_type']) && $_GET['flight_type'] == "local" ):?>
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
    var flightsAjax = $.ajax({
        url: "<?php echo site_url('api/flights/search_flights_oneway');?>",
        type: "POST",
        data: { data : search_parameters }
    })
    .done(function (flData){
        var result = $.parseJSON(flData);
        <?php if( isset($_GET['flight_type']) && $_GET['flight_type'] == "local" ):?>
            var search_base = "<?php echo $_SESSION['currentUrlFlight'];?>";
        <?php else:?>
            var search_base = "<?php echo site_url('common/change_travel_mode?city_from='.$_SESSION['details']['from'][($_SESSION['cnt_val']-1)].'&city_to='.$_SESSION['details']['to'][($_SESSION['cnt_val']-1)].'&adult_count='.$_SESSION['details']['adult_count'].'&youth_count='.$_SESSION['details']['youth_count'].'&journey_date='.$_SESSION['details']['dp_dates'][($_SESSION['cnt_val']-1)].'&total_count='.$_SESSION['details']['total_count'].'&flight_type=multi&travel_mode=flight');?>";
        <?php endif;?>
        $('#fl_spin').hide();
        if(result !== false)
        {
            $('.flightOptionView').attr('href', search_base);
            $('.flightOptionDetails').show();
            $('.flightOptionDetailsLine1').html('Flights to '+ dest[0]+' From:');
            $('.flightOptionDetailsLine2').html('&#x20B9; ' +result.fare_min);
            $('.flightOptionView').show();
        }else{
            $('.flightOptionDetails').hide();
            $('.flightOptionView').hide();
            $('.noFlightsLabel').show();
        }
    });

    <?php endif;?>

    $('table#search').removeClass('hide');

    //reset filters part
    $('.busesShowAllLink').on('click resetAllFilters', function(e){
        e.preventDefault();
        var busContainer = $('#buses-check');
        var busCheckboxes = busContainer.find(':input');
        var busCheckboxesChecked = busContainer.find(':input:checked');
        var Uncheck = 1;
        if( e.type === "resetAllFilters" && this.text === "Uncheck All" ){
            Uncheck = 0;
        }
        if( busCheckboxesChecked.length === busCheckboxes.length && Uncheck ){
            $.each(busCheckboxes, function(i, val){
                val.click();
            });
        }else{
            $.each(busCheckboxes, function(i, val){
                if( val.checked === false ){
                    val.click();
                }
            });
        }
    });

    $(document).on('resetAllFilters', function(){
        var busTypeContainer = $('#bus-type-check');
        var busSeatContainer = $('#seater-check');
        var busTypeCheckboxes = busTypeContainer.find(':input');
        var busSeatCheckboxes = busSeatContainer.find(':input');

        if( busSeatCheckboxes.get(0).checked === true ){
            busSeatCheckboxes.click();
        }

        $.each(busTypeCheckboxes, function(i, val){
            if( val.checked === false ){
                val.click();
            }
        });

        resetSlider();
        initRangeSlider();
        initRangeSliderDuration();
        oTable.draw();
    });

    //reset filters part end

    //weather

        var searchedDate = new Date("<?php echo date('c', strtotime($_GET['journey_date']));?>");
        var currentDate = new Date();
        var isPredictableWeather = 0;
        var timeDiff = Math.abs(searchedDate.getTime() - currentDate.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 

        if( diffDays >= 7 ){
            isPredictableWeather = 0;
        }else{
            isPredictableWeather = 1;
        }

        var required_date = searchedDate.getDate() + '-' + (searchedDate.getMonth() + 1) + '-' + searchedDate.getFullYear();
        var dt = searchedDate.toString();
        var display_date = dt.substr(8, 2) + " " + (dt.substr(4, 3)) + " " + dt.substr(11, 4);
        var sourceGetCity = "<?php echo $_GET['source_city_name']?>";
        var destinationGetCity = "<?php echo $_GET['destination_city_name']?>";
        var cityArray = sourceGetCity.split(',');
        var source_city_name = cityArray[0];
        cityArray = destinationGetCity.split(',');
        var destination_city_name = cityArray[0];
        var source_city_lat;
        var source_city_long;
        var destination_city_lat;
        var destination_city_long;

        var geocoder =  new google.maps.Geocoder();
        geocoder.geocode( { 'address': source_city_name }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                source_city_lat = results[0].geometry.location.lat();
                source_city_long = results[0].geometry.location.lng(); 
                $(document).trigger('secondAddress');
            } else {
                return;
            }
        });

        $(document).on('secondAddress', function(){
            geocoder.geocode( { 'address': destination_city_name }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    destination_city_lat = results[0].geometry.location.lat();
                    destination_city_long = results[0].geometry.location.lng();
                    $.ajax({
                        type: "POST",
                        url : "<?php echo site_url('weather');?>",
                        data : { choosen_date: required_date, source_city_lat : source_city_lat , source_city_long : source_city_long, destination_city_lat : destination_city_lat , destination_city_long : destination_city_long, is_predictable_weather: isPredictableWeather}
                    })
                    .done(function(weatherdata){
                        $('.weather_spinner').hide();
                        var wData = $.parseJSON(weatherdata);
                        if( wData.isHistoric ){
                            // historic weather
                            $('.weatherDetails .from .date').html('<div class="title">Weather for:</div><span>'+destination_city_name+' on - Today</span>');
                            $('.weatherDetails .from .icon').html(getWeatherIcon(wData.weatherResponseDestination));
                            $('.weatherDetails .from .summary').html(wData.weatherResponseDestination.summary);
                            $('.weatherDetails .from .temperature').html(' <div class="row actual"> <div class="col-xs-24"> <div class="title">Temperature</div> <span class="h4 min">'+ Math.round(wData.weatherResponseDestination.temperature) +'&deg;C</span> </div>');

                            $('.weatherDetails .to .date').html('<div class="title">Weather for:</div><span>'+source_city_name+' on - Today</span>');
                            $('.weatherDetails .to .icon').html(getWeatherIcon(wData.weatherResponseSource));
                            $('.weatherDetails .to .summary').html(wData.weatherResponseSource.summary);
                            $('.weatherDetails .to .temperature').html(' <div class="row actual"> <div class="col-xs-24"> <div class="title">Temperature</div> <span class="h4 min">'+ Math.round(wData.weatherResponseSource.temperature) +'&deg;C</span> </div>');
                        }else{
                            // current weather
                            $('.weatherDetails .from .date').html('<div class="title">Weather for:</div><span>'+destination_city_name+'<br /> on - '+display_date+'</span>');
                            $('.weatherDetails .from .icon').html(getWeatherIcon(wData.weatherResponseDestination));
                            $('.weatherDetails .from .summary').html(wData.weatherResponseDestination.summary);
                            $('.weatherDetails .from .temperature').html(' <div class="row actual"> <div class="col-xs-12"> <div class="title">Min</div><span class="h4 min">'+ Math.round(wData.weatherResponseDestination.minTemperature) +'&deg;C</span> </div><div class="col-xs-12"> <div class="title">Max</div><span class="h4 max">'+ Math.round(wData.weatherResponseDestination.maxTemperature) +'&deg;C</span> </div></div>');

                            $('.weatherDetails .to .date').html('<div class="title">Weather for:</div><span>'+source_city_name+'<br /> on - '+display_date+'</span>');
                            $('.weatherDetails .to .icon').html(getWeatherIcon(wData.weatherResponseSource));
                            $('.weatherDetails .to .summary').html(wData.weatherResponseSource.summary);
                            $('.weatherDetails .to .temperature').html(' <div class="row actual"> <div class="col-xs-12"> <div class="title">Min</div><span class="h4 min">'+ Math.round(wData.weatherResponseSource.minTemperature) +'&deg;C</span> </div><div class="col-xs-12"> <div class="title">Max</div><span class="h4 max">'+ Math.round(wData.weatherResponseSource.maxTemperature) +'&deg;C</span> </div></div>');
                        }
                    });
                } else {
                    return;
                }
            });
        });

    //weather end

});

    //creates a weather icon
    function getWeatherIcon(wData){
        var weatherIcon = '';
        switch(wData.icon){
            case 'clear-day' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/clear.png" />';
            break;
            case 'clear-night' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/clear.png" />';
            break;
            case 'rain' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/rain.png" />';
            break;
            case 'snow' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/snow.png" />';
            break;
            case 'sleet' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/sleet.png" />';
            break;
            case 'wind' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/wind.png" />';
            break;
            case 'fog' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/fog.png" />';
            break;
            case 'cloudy' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/cloudy.png" />';
            break;
            case 'partly-cloudy-day' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/partly_cloudy.png" />';
            break;
            case 'partly-cloudy-night' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/partly_cloudy.png" />';
            break;
            default :
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/clear.png" />';
            break;
        }
        return weatherIcon;

    }
    //creates a weather icon end

    function resetSlider() {
        var $slider = $("#slider-range");
        var $sliderDuration = $("#slider-range-duration");
        $slider.slider("values", 0, min_round);
        $slider.slider("values", 1, max_round);
        $sliderDuration.slider("values", 0, min_duration);
        $sliderDuration.slider("values", 1, max_duration);
    }

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 8;
             
            var iVersion = aData[iColumn];

            for (i = 0; i < selectedBusType.length; i++) { 
                if(iVersion == selectedBusType[i].trim()){
                    return true;
                }
            }
            return false;

        }
    );  

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 0;

            var iVersion = aData[iColumn];

            for (i = 0; i < selectedBuses.length; i++) { 
                if(iVersion == selectedBuses[i].trim()){
                    return true;
                }
            }
            return false;

        }
    ); 

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 10;
             
            var iVersion = aData[iColumn];

            for (i = 0; i < selectedSeats.length; i++) { 
                if(iVersion == selectedSeats[i].trim()){
                    return true;
                }
            }
            return false;

        }
    ); 

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 3;
            var iMin = rangeSliderDuration.slider("values", 0);
            var iMax = rangeSliderDuration.slider("values", 1);

            aData[iColumn] = parseInt(aData[iColumn]);
            var iVersion = aData[iColumn] == "-" ? 0 : aData[iColumn]*1;

            if ( iMin == "" && iMax == "" )
            {
                return true;
            }
            else if ( iMin == "" && iVersion < iMax )
            {
                return true;
            }
            else if ( iMin < iVersion && "" == iMax )
            {
                return true;
            }
            else if ( iVersion > iMin && iVersion < iMax )
            {
                return true;
            }
            return false;
        }
    ); 

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 9;
            var iMin = rangeSlider.slider("values", 0);
            var iMax = rangeSlider.slider("values", 1);
             
            var iVersion = aData[iColumn] == "-" ? 0 : aData[iColumn]*1;
            if ( iMin == "" && iMax == "" )
            {
                return true;
            }
            else if ( iMin == "" && iVersion < iMax )
            {
                return true;
            }
            else if ( iMin < iVersion && "" == iMax )
            {
                return true;
            }
            else if ( iMin < iVersion && iVersion < iMax )
            {
                return true;
            }
            return false;
        }
    ); 

    var initRangeSlider =  function(){
        rangeSlider.slider({
           range: true,
           min: min_round,
           max: max_round,
           step: 100,
           values: [ min_round, max_round ],
           slide: function( event, ui ) {
                var priceTextBtm = parseFloat(ui.values[ 0 ]).toFixed(2);
                var priceTextTop = parseFloat(ui.values[ 1 ]).toFixed(2);

                $('#range-bottom').html(priceTextBtm);
                $('#range-top').html(priceTextTop);
                $('#range-bottom').autoNumeric('set', priceTextBtm);
                $('#range-top').autoNumeric('set', priceTextTop);
           },
           stop: function(event, ui) {
                flashTable();
                oTable.draw();
           }
        });
    
    var rangeBottom = rangeSlider.slider("values", 0);
    var rangeTop = rangeSlider.slider("values", 1);

    rangeBottom = parseFloat(rangeBottom).toFixed(2);
    rangeTop = parseFloat(rangeTop).toFixed(2);

    $('#range-bottom').html(rangeBottom);
    $('#range-top').html(rangeTop);

    }

    var initRangeSliderDuration =  function(){
        rangeSliderDuration.slider({
            range: true,
            min: min_duration,
            max: max_duration,
            step: 10,
            values: [ min_duration , max_duration ],
            slide: function( event, ui ) {
                //to insert a colon inbetween the text
                var rangeBottomDurationStr = ui.values[ 0 ].convertToTime();
                var rangeTopDurationStr = ui.values[ 1 ].convertToTime();

                $('#range-bottom-duration').html(rangeBottomDurationStr);
                $('#range-top-duration').html(rangeTopDurationStr);
            },
            stop: function(event, ui) {
                flashTable();
                oTable.draw();
            }
        });
    
        var rangeBottomDuration = rangeSliderDuration.slider("values", 0);
        var rangeTopDuration = rangeSliderDuration.slider("values", 1);

        $('#range-bottom-duration').html(min_duration.convertToTime());
        $('#range-top-duration').html(max_duration.convertToTime());

    }

    Number.prototype.convertToTime = function( ){
        var hours = Math.floor( this / 60 );
        var minutes = this - (hours * 60);

        if(hours < 10) hours = '0' + hours;
        if(minutes < 10) minutes = '0' + minutes;

        if(minutes == 0) minutes = '00';

        return hours+':'+minutes;
    }

    function format (row_details_id) {
        var index = parseInt(row_details_id);
        var from_city = "<?php echo $_GET['source_city_name'];?>";
        var to_city = "<?php echo $_GET['destination_city_name'];?>";

        if( isNaN(index) ){
            return "<div></div>";
        }

        var base_fare_disp = (typeof return_data.availableTrips[index].fareDetails.baseFare === 'undefined') ? return_data.availableTrips[index].fareDetails[0].baseFare : return_data.availableTrips[index].fareDetails.baseFare;
        var service_tax_absolute_disp = (typeof return_data.availableTrips[index].fareDetails.serviceTaxAbsolute === 'undefined') ? return_data.availableTrips[index].fareDetails[0].serviceTaxAbsolute : return_data.availableTrips[index].fareDetails.serviceTaxAbsolute;
        var total_fare_disp = (typeof return_data.availableTrips[index].fareDetails.totalFare === 'undefined') ? return_data.availableTrips[index].fareDetails[0].totalFare : return_data.availableTrips[index].fareDetails.totalFare;

        return  '<div class="col-lg-24 details-row">'
                +'<div class="row busDetailHeaders">'
                    +'<div class="col-lg-4">Boarding Points</div>'
                    +'<div class="col-lg-4">Drop-Off Points</div>'
                    +'<div class="col-lg-11"><div class="col-lg-20">Cancellation Time</div><div class="col-lg-4">Cancellation Charge</div></div>'
                    +'<div class="col-lg-5">Fare Breakdown</div>'
                +'</div>'

                +'<div class="row">'
                    +'<div class="col-lg-4 bus-stop-pnts">'+boarding(index)+'</div>'
                    +'<div class="col-lg-4 bus-stop-pnts">'+dropping(index)+'</div>'
                    +'<div class="col-lg-11 bus-cancel-text">'+cancellation(index)+'</div>'
                    +'<div class="col-lg-5">'
                        +'<div class="row">'
                            +'<div class="col-xs-10 bus-stop-pnts left-text">Base Fare</div>'
                            +'<div class="col-xs-14 right-text base_fare">&#x20B9; <span>'+base_fare_disp+'</span></div>'
                        +'</div>'
                        +'<div class="row">'
                            +'<div class="col-xs-10 bus-stop-pnts left-text">Service Tax</div>'
                            +'<div class="col-xs-14 right-text service_fare">&#x20B9; <span>'+service_tax_absolute_disp+'</span></div>'
                        +'</div>'
                        +'<div class="row">'
                            +'<div class="col-xs-10 bus-stop-pnts left-text">Total Fare</div>'
                            +'<div class="col-xs-14 right-text total_fare_det">&#x20B9; <span>'+total_fare_disp+'</span></div>'
                        +'</div>'
                    +'</div>'
                +'</div>'
                +'</div>'
    }

    function boarding(index){

        var ret_str = "";
        if( typeof return_data.availableTrips[index].boardingTimes.length === 'undefined' ){
            var disp_time = parseInt(return_data.availableTrips[index].boardingTimes.time).convertToTime();
            ret_str += "<div class='row'><div class='col-lg-6'>"+disp_time+"</div><div class='col-lg-18 left-text wordBreak'>"+return_data.availableTrips[index].boardingTimes.location+"</div></div>"
        }else{
            $.each(return_data.availableTrips[index].boardingTimes, function(i, val){
                var disp_time = parseInt(val.time).convertToTime();
                ret_str += "<div class='row'><div class='col-lg-6'>"+disp_time+"</div><div class='col-lg-18 left-text wordBreak'>"+val.location+"</div></div>"
            });
        }
        return ret_str;
    }

    function dropping(index){
        var ret_str = "";

        if( typeof return_data.availableTrips[index].droppingTimes !== 'undefined' ){
            if( typeof return_data.availableTrips[index].droppingTimes.length === 'undefined' ){
                var temp_time = parseInt(return_data.availableTrips[index].droppingTimes.time) % 1440;
                var disp_time = temp_time.convertToTime();
                ret_str += "<div class='row'><div class='col-lg-6'>"+disp_time+"</div><div class='col-lg-18 left-text wordBreak'>"+return_data.availableTrips[index].droppingTimes.location+"</div></div>"
            }else{
                $.each(return_data.availableTrips[index].droppingTimes, function(i, val){
                    var temp_time = parseInt(val.time) % 1440;
                    var disp_time = temp_time.convertToTime();
                    ret_str += "<div class='row'><div class='col-lg-6'>"+disp_time+"</div><div class='col-lg-18 left-text wordBreak'>"+val.location+"</div></div>"
                });
            }
            return ret_str;
        }
    }

    function cancellation(index){
        var ret_str_can = "";
        var can_str = return_data.availableTrips[index].cancellationPolicy.split(';');
        var can_dep_time = parseInt(return_data.availableTrips[index].departureTime);
        var d = new Date(return_data.availableTrips[index].doj);
        var can_fare = parseInt(return_data.availableTrips[index].fares);
        var can_len = can_str.length;
        var rem_date = new Date(d);
        var rem_date_0 = new Date(d);
        var rem_date_1 = new Date(d);
        var rem_total = 0;
        var rem_time = 0;
        var rem_time_0 = 0;
        var rem_time_1 = 0;
        var mins_before = 0;
        var days_before = 0;

        var month = new Array();
        month[0] = "Jan";
        month[1] = "Feb";
        month[2] = "Mar";
        month[3] = "Apr";
        month[4] = "May";
        month[5] = "Jun";
        month[6] = "Jul";
        month[7] = "Aug";
        month[8] = "Sep";
        month[9] = "Oct";
        month[10] = "Nov";
        month[11] = "Dec";

        for( var i = 0 ; i < can_len ; i++ ){
            ret_str_can += '<div class="row">';
            if( i === 0 && can_str[i] === "0:-1:100:0" ){
                ret_str_can += '<div class="col-lg-20">No Refund</div><div class="col-lg-4 can_fare">&#x20B9; <span>0</span></div>'
            }else if( can_str[i] !== "" ){
                var info_arr = can_str[i].split(':');
                if( info_arr[1] === "-1" ){
                    mins_before = parseInt(info_arr[0])*60;
                    days_before = Math.floor(mins_before/1440);
                    rem_date.setDate(d.getDate() - days_before);
                    rem_time = can_dep_time - (mins_before % 1440);
                    ret_str_can += '<div class="col-lg-20">'+'Till <b>'+rem_time.convertToTime()+'</b> on <b>'+rem_date.getDate()+'-'+month[rem_date.getMonth()]+'-'+rem_date.getFullYear()+'</b></div>';
                }else{
                    mins_before = parseInt(info_arr[1])*60;
                    days_before = Math.floor(mins_before/1440);
                    rem_date_0.setDate(d.getDate() - days_before);
                    rem_time_0 = can_dep_time - (mins_before % 1440);
                    mins_before = parseInt(info_arr[0])*60;
                    days_before = Math.floor(mins_before/1440);
                    rem_date_1.setDate(d.getDate() - days_before);
                    rem_time_1 = can_dep_time - (mins_before % 1440);
                    if( info_arr[0] === "0" ){
                        ret_str_can += '<div class="col-lg-20">'+'After <b>'+rem_time_0.convertToTime()+'</b> on <b>'+rem_date_0.getDate()+'-'+month[rem_date_0.getMonth()]+'-'+rem_date_0.getFullYear()+'</b></div>';
                    }else{
                        ret_str_can += '<div class="col-lg-20">'+'between <b>'+rem_time_0.convertToTime()+'</b> on <b>'+rem_date_0.getDate()+'-'+month[rem_date_0.getMonth()]+'-'+rem_date_1.getFullYear()+'</b> - <b>'+ rem_time_1.convertToTime()+'</b> on <b>'+rem_date_1.getDate()+'-'+month[rem_date_1.getMonth()]+'-'+rem_date_1.getFullYear()+ '</b></div>';
                    }
                }
                //amount section
                if( !parseInt(info_arr[3]) ){
                    //percentage
                    var percentage = parseInt(info_arr[2])/100;
                    var total_fare =  parseInt(return_data.availableTrips[index].fares)
                    var per_total = percentage*total_fare;
                    rem_total = total_fare-per_total;
                    var cancellation_charge = total_fare - rem_total;

                    $('.can_fare_hidden').autoNumeric('set', cancellation_charge);
                    ret_str_can += '<div class="col-lg-4 can_fare">&#x20B9; <span>'+$('.can_fare_hidden').html()+'</span></div>';
                }else{
                    //amount
                    var a1 = parseInt(return_data.availableTrips[index].fares);
                    var a2 = parseInt(info_arr[2]);
                    rem_total = a1-a2;
                    var cancellation_charge = a1 - rem_total;

                    $('.can_fare_hidden').autoNumeric('set', cancellation_charge);
                    ret_str_can += '<div class="col-lg-4 can_fare">&#x20B9; <span>'+$('.can_fare_hidden').html()+'</span></div>';
                }
            }
            ret_str_can += '</div>';
            cancelPolicy[index] = (ret_str_can).toString();
        }
        return ret_str_can;
    }

    function flashTable(){
        $('#search').hide();
        $('#search').fadeIn(200);
    }

</script>