<?php 
    if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 ){
        $count_num = $_SESSION['cnt_val'];   
    }     
    if( isset($_SESSION['ovMode']) && empty($_SESSION['flight_data']) ){
        if( !isset($_SESSION['recount']) ){
            $count_num += 1;
            $_SESSION['recount'] = 1;
            $_SESSION['cnt_val'] = $count_num;
        }
    }
    if(isset($_SESSION['redir_data'])){
        $_SESSION['redir_data']['travel_mode'] = 'bus';  
    }   
?>
<style>
    label:hover{
        cursor: pointer;
    }
    
    .container_vert_un img, .container_vert img{
        height: 34px;
    }

    img{
        height: 24px;
    }

    span.caption{
        font-size: 12px;
        font-family: "Open Sans";
        font-weight: 600;
    }
</style>
<script language="javascript" type="text/javascript">
imgseaterArr=new Array(); 
imgladiesseaterArr=new Array();
imgvsleeperArr=new Array();
imgladiesvsleeperArr=new Array();
imghsleeperArr=new Array();
imgladieshsleeperArr=new Array();

for(var i=0;i<100;i++)
{
    imgseaterArr[i]=new Array('../../../images/bus_seater_empty.png','../../../images/bus_seater_selected.png'); 
    imgladiesseaterArr[i]=new Array('../../../images/bus_seater_ladies.png','../../../images/bus_seater_selected.png'); 

    imgvsleeperArr[i]=new Array('../../../images/bus_sleeper_empty_vertical.png','../../../images/bus_sleeper_selected_vertical.png');
    imgladiesvsleeperArr[i]=new Array('../../../images/bus_sleeper_ladies_vertical.png','../../../images/bus_sleeper_selected_vertical.png');

    imghsleeperArr[i]=new Array('../../../images/bus_sleeper_empty_horizontal.png','../../../images/bus_sleeper_selected_horizontal.png');
    imgladieshsleeperArr[i]=new Array('../../../images/bus_sleeper_ladies_horizontal.png','../../../images/bus_sleeper_selected_horizontal.png');

}

<?php if(isset($_SESSION['details']['total_count'])):?>
var max_count = 0;
var max_seat_selectable = <?php echo $_SESSION['details']['total_count'];?>;

function max_seat_check(chk,ind){
    if( chk.checked ){
        max_count++;
        if( max_count > max_seat_selectable ){
            $('.error-msg .max-seat-error').fadeIn(200);
            setTimeout(function(){
                $('.error-msg .max-seat-error').fadeOut(200);
            }, 4000);
            max_count--;
            chk.checked = false;
            return false;
        }else{
            return true;
        }
    }else{
        max_count--;
        return true;
    }
}
<?php else:?>
function max_seat_check(chk,ind){
    return true;
}
<?php endif;?>

function swapseater(chk,ind){ 
    if( max_seat_check(chk,ind) && chk.checked ){
        img=document.images['img'+ind]; 
        img.src=imgseaterArr[ind][1]; 
        img.alt=imgseaterArr[ind][1]; 
    }else{
        img=document.images['img'+ind];
        img.src=imgseaterArr[ind][0]; 
        img.alt=imgseaterArr[ind][0];
    }
}

function swapladiesseater(chk,ind){ 
    if( max_seat_check(chk,ind) && chk.checked ){
        img=document.images['img'+ind]; 
        img.src=imgladiesseaterArr[ind][1]; 
        img.alt=imgladiesseaterArr[ind][1]; 
    }else{ 
        img=document.images['img'+ind]; 
        img.src=imgladiesseaterArr[ind][0]; 
        img.alt=imgladiesseaterArr[ind][0]; 
    } 
}

function swapvsleeper(chk,ind){ 
    if( max_seat_check(chk,ind) && chk.checked ){
        img=document.images['vsleep'+ind]; 
        img.src=imgvsleeperArr[ind][1]; 
        img.alt=imgvsleeperArr[ind][1]; 
    }else{ 
        img=document.images['vsleep'+ind]; 
        img.src=imgvsleeperArr[ind][0]; 
        img.alt=imgvsleeperArr[ind][0]; 
    } 
}

function swapladiesvsleeper(chk,ind){ 
    if( max_seat_check(chk,ind) && chk.checked ){
        img=document.images['vsleep'+ind]; 
        img.src=imgladiesvsleeperArr[ind][1]; 
        img.alt=imgladiesvsleeperArr[ind][1]; 
    }else{ 
        img=document.images['vsleep'+ind]; 
        img.src=imgladiesvsleeperArr[ind][0]; 
        img.alt=imgladiesvsleeperArr[ind][0]; 
    } 
}

function swaphsleeper(chk,ind){ 
    if( max_seat_check(chk,ind) && chk.checked ){
        img=document.images['hsleep'+ind]; 
        img.src=imghsleeperArr[ind][1]; 
        img.alt=imghsleeperArr[ind][1]; 
    }else{ 
        img=document.images['hsleep'+ind]; 
        img.src=imghsleeperArr[ind][0]; 
        img.alt=imghsleeperArr[ind][0]; 
    } 
}


function swapladieshsleeper(chk,ind){ 
    if( max_seat_check(chk,ind) && chk.checked ){
        img=document.images['hsleep'+ind]; 
        img.src=imgladieshsleeperArr[ind][1]; 
        img.alt=imgladieshsleeperArr[ind][1]; 
    }else{ 
        img=document.images['hsleep'+ind]; 
        img.src=imgladieshsleeperArr[ind][0]; 
        img.alt=imgladieshsleeperArr[ind][0]; 
    } 
}
    </script>
</head>
<style>
    .fl_septr1{
        margin-bottom: 6px;
    }
    .number_of_seats_count{
        margin:0;
    }
    .selected_seats{
        margin:0;
    }
</style>
    
<?php if( !isset($_SESSION['boardingpnts']) ):?>
<body>
    <div class="wrap">
        <div class="container-fluid clear-top">
            <center><h3>Sorry, The page you are trying to visit is unavailable. Please try searching again.</h3></center>
        </div>
    </div>
</body>
<?php endif;?>
<div class="hidden-fields">
    <?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 ):?>
        <?php for( $hc = 0 ; $hc < $count_num ; $hc++):?>
            <div id="popoverHiddenContent-<?php echo $hc+1;?>" style="display: none;">
                <?php if( (isset($_SESSION['flight_data'][$hc]) && $_SESSION['flight_data'][$hc]['ov']->mode == 'flight') || (isset($_SESSION['ovMode']) && $_SESSION['ovMode']->mode =="flight") ) : 
                    if( isset($_SESSION['flight_data'][$hc]['ov']) ) { $flight_ov_data = $_SESSION['flight_data'][$hc]['ov']; }
                    if( isset($_SESSION['ovMode']) ) { $flight_ov_data = $_SESSION['ovMode']; }
                    
                    if( is_array($flight_ov_data->WSSegment) ):?>
                    
                    <?php $max_arr_len = count($flight_ov_data->WSSegment); $iter = 0;?>
                    <?php foreach( $flight_ov_data->WSSegment as $ws ):?>
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
                                echo '<div class="col-lg-12 remove-padding"> Layover - '.$flight_ov_data->layover[$iter].' </div>';
                                echo '<div class="col-lg-6 remove-padding grey-line"></div>';
                            echo '</div>';}
                        ?>
                        <?php $iter++;?>
                    <?php endforeach;?>
                <?php else:?>
                <?php
                    if( isset($_SESSION['flight_data'][$hc]['ov']) ) { $flight_ov_data = $_SESSION['flight_data'][$hc]['ov']; }
                    if( isset($_SESSION['ovMode']) ) { $flight_ov_data = $_SESSION['ovMode']; } 

                    $depTime = date('H:i', strtotime($flight_ov_data->WSSegment->DepTIme));
                    $arrTime = date('H:i', strtotime($flight_ov_data->WSSegment->ArrTime));
                    $depDate = date('D, jS M Y', strtotime($flight_ov_data->WSSegment->DepTIme));
                    $var1 = strtotime($flight_ov_data->WSSegment->DepTIme);
                    $var2 = strtotime($flight_ov_data->WSSegment->ArrTime);
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
                                if(isset($flight_ov_data->WSSegment->OperatingCarrier)){$OperatingCarrier = $flight_ov_data->WSSegment->OperatingCarrier;}else{$OperatingCarrier =  "";}
                                if(isset($flight_ov_data->WSSegment->Craft)){$Craft = $flight_ov_data->WSSegment->Craft;}else{$Craft =  "";}
                            ?>
                            <div class="row date-text"><?php echo $OperatingCarrier;?> - <?php echo $Craft;?></div>
                        </div>
                        <div class="col-lg-12 travel-text-margin">
                            <div class="row travel-text">
                                <div class="col-lg-11 remove-padding" id="originPop"><?php echo $flight_ov_data->WSSegment->Origin->CityName;?><div class="row center-align-text time_text" id="from"><?php echo $depTime;?> </div></div>
                                <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                <div class="col-lg-11 remove-padding" id="destinationPop"><?php echo $flight_ov_data->WSSegment->Destination->CityName;?><div class="row center-align-text time_text" id="to"><?php echo $arrTime;?> </div></div>
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

        <?php for( $ht = 0 ; $ht < $count_num ; $ht++):?>
            <?php if( (isset($_SESSION['flight_data'][$ht]) && $_SESSION['flight_data'][$ht]['ov']->mode == 'flight') || (isset($_SESSION['ovMode']) && $_SESSION['ovMode']->mode =="flight") ) : ?>
            <?php 
                if( isset($_SESSION['flight_data'][$ht]['ov']) ) { $flight_ov_data = $_SESSION['flight_data'][$ht]['ov']; }
                if( isset($_SESSION['ovMode']) ) { $flight_ov_data = $_SESSION['ovMode']; }
            ?>
            <div id="popoverHiddenTitle-<?php echo $ht+1;?>" style="display: none">
                <div class="row">
                    <div class="col-lg-12 pull-left">
                        <div class="col-lg-4">
                            <?php if( !is_array( $flight_ov_data->WSSegment ) ):?>
                                <img src="<?php echo base_url('img/AirlineLogo/'.$flight_ov_data->WSSegment->Airline->AirlineCode.'.gif');?>" onError="this.src='<?php echo base_url('img/flightIcon.png'); ?>'" alt="jA" width='30px' />
                            <?php else:?>
                                <img src="<?php echo base_url('img/AirlineLogo/'.$flight_ov_data->WSSegment[0]->Airline->AirlineCode.'.gif');?>" onError="this.src='<?php echo base_url('img/flightIcon.png'); ?>'" alt="jA" width='30px' />
                            <?php endif;?>
                        </div>
                        <?php if( !is_array( $flight_ov_data->WSSegment ) ):?>
                            <div class="col-lg-offset-2 col-lg-12 pop-title-text"><?php echo $flight_ov_data->WSSegment->Airline->AirlineName;?></div>
                        <?php else:?>
                            <div class="col-lg-offset-2 col-lg-12 pop-title-text"><?php echo $flight_ov_data->WSSegment[0]->Airline->AirlineName;?></div>
                        <?php endif;?>
                    </div>
                    <div class="col-lg-12 pull-right">
                        <div class="col-lg-4 col-lg-offset-8 img-cal">
                            <img src="<?php echo base_url('img/calendar_icon.png');?>" alt="jA" width='18px' />
                        </div>
                        <div class="col-lg-12 pop-title-text"><?php echo date('d M Y', strtotime($flight_ov_data->travel_date));?></div>
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

<body>
    <div class="wrap">
        <div class="container-fluid clear-top">
            <div class="row resultsSearchContainer navbar-fixed-top">
                <div class="panel-group" id="accordionSearch">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                            <?php if( isset( $_GET['get_req_active'] ) && $_GET['get_req_active'] == 1 ):?>
                            <?php $count = $count_num;?>
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
                                                                    <div class="col-lg-11 remove-padding ellipse" id="origin<?php echo $n+1?>"><?php if ( isset($_SESSION['flight_data'][$n]['ov']) ) echo $_SESSION['flight_data'][$n]['ov']->org; elseif ( isset($_SESSION['ovMode']) ) echo $_SESSION['ovMode']->org ?></div>
                                                                    <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                                                    <div class="col-lg-11 remove-padding ellipse" id="destination<?php echo $n+1?>"><?php if( isset($_SESSION['flight_data'][$n]['ov']) ) echo $_SESSION['flight_data'][$n]['ov']->dest; else if( isset( $_SESSION['ovMode'] ) ) echo $_SESSION['ovMode']->dest ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-17 col-lg-offset-5 TravelDate center-align-text"><?php echo date( 'd M Y', strtotime($_SESSION['details']['dates'][$n]) );?></div>
                                                        </div>
                                                        <div class="row">
                                                            <?php if( isset($_SESSION['flight_data'][$n]['ov']->mode) && $_SESSION['flight_data'][$n]['ov']->mode == 'bus' ):?>
                                                                <div class="col-lg-23 col-lg-offset-1 center-align-text fare-padding TotFare1">&#x20B9; <span><?php echo $_SESSION['flight_data'][$n]['ov']->fareDet['totalFare'];?></span></div>
                                                            <?php elseif(isset($_SESSION['flight_data'][$n]['ov']->mode) && $_SESSION['flight_data'][$n]['ov']->mode == 'cab'):?>
                                                                <div class="col-lg-23 col-lg-offset-1 center-align-text fare-padding TotFare1">&#x20B9; <span><?php echo $_SESSION['flight_data'][$n]['ov']->totalFare;?></span></div>
                                                            <?php else:?>
                                                                <div class="col-lg-23 col-lg-offset-1 center-align-text fare-padding TotFare1">&#x20B9; <span><?php if(isset($_SESSION['flight_data'][$n])) echo $_SESSION['flight_data'][$n]['total_fare_field']; elseif(isset($_SESSION['ovMode'])) echo $_SESSION['ovMode']->total_fare_field?></span></div>
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
                                                            <div class="col-lg-11 remove-padding ellipse" id="origin"><?php echo $_GET['source'];?></div>
                                                            <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                                            <div class="col-lg-11 remove-padding ellipse" id="destination"><?php echo $_GET['destination'];?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-17 col-lg-offset-6 TravelDate center-align-text"><?php echo date('D, jS M Y', strtotime($_GET['datepicker']));?></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-23 col-lg-offset-1 center-align-text TotFare1 fare-padding"> &#x20B9;<span class='total_fare'>0</span></div>
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
                                <div class="accord-content container-fluid">
                                    <div class="row fl_overwiew hulk-class">
                                        <div class="col-lg-5 fl_septr1">
                                            <div class="col-lg-2 fl_no">1</div>
                                            <div class="col-lg-3 bus_bg_nav sr_only"></div>
                                            <div class="col-lg-14 fl_info">
                                                <div class="row">
                                                    <div class="col-lg-offset-4 col-lg-20 center-align-text travel-text">
                                                        <div class="row center-align-text">
                                                            <div class="col-lg-11 remove-padding ellipse" id="origin"><?php echo $_GET['source'];?></div>
                                                            <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                                            <div class="col-lg-11 remove-padding ellipse" id="destination"><?php echo $_GET['destination'];?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-17 col-lg-offset-6 TravelDate center-align-text"><?php echo date('D, jS M Y', strtotime($_GET['datepicker']));?></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-23 col-lg-offset-1 center-align-text TotFare1 fare-padding"> &#x20B9;<span class='total_fare'>0</span></div>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="container clear-top main clear-ov">
            <div class="col-lg-14">
                <?php
                echo "<div>";
                if(isset($_SESSION['redir_data']) && $_SESSION['redir_data']['travel_mode'] == 'bus' ){
                    echo "<form method='POST' action='".site_url('common/change_travel_mode')."' name='form3' id='form3' onSubmit=''>";
                    echo "<input type='text' name='travel_mode' value='bus' style='display:none;'/>";
                }else{
                    echo "<form method='GET' action='".site_url('bus_api/buses/selected_seats')."' name='form3' id='form3' onSubmit=''>";
                }
                    $image_seater_vacant = "../../../images/bus_seater_empty.png";
                    $image_seater_selected = "../../../images/bus_seater_selected.png";
                    $image_seater_unavailable = "../../../images/bus_seater_occupied.png";
                    $image_seater_ladies = "../../../images/bus_seater_ladies.png";
                    $image_sleeper_vacant = "../../../images/bus_sleeper_empty_horizontal.png";
                    $image_sleeper_unavailable = "../../../images/bus_sleeper_occupied_horizontal.png";
                    $image_sleeper_ladies = "../../../images/bus_sleeper_ladies_horizontal.png";
                    $image_vertical_sleeper_vacant = "../../../images/bus_sleeper_empty_vertical.png";
                    $image_vertical_sleeper_unavailable = "../../../images/bus_sleeper_occupied_vertical.png";
                    $image_vertical_sleeper_ladies = "../../../images/bus_sleeper_ladies_vertical.png";
                    $image_empty_row = "../../../images/no_seat.png";
                    if( isset($_SESSION['boardingpnts']['travels']) ){
                        echo "<h4>SEAT LAYOUT <span class='h6'>( ".$_SESSION['boardingpnts']['travels'].", ".$_SESSION['boardingpnts']['busType']." )</span> </h4>";
                        echo '<div class="row">';
                        echo '<div class="col-lg-24 error-msg"  style="height:20px;">';
                        echo '<small class="sel-seat-error" style="display:none;">Please select a seat.</small>';
                        echo '<small class="max-seat-error" style="display:none;">You can only choose a maximum of '.$_SESSION['details']['total_count'].' seats at a time.</small>';
                        echo '</div>';
                        echo '</div>';
                    }else{
                        echo "<h4>SEAT LAYOUT</h4>";
                        echo '<div class="row">';
                        echo '<div class="col-lg-24 error-msg"  style="height:20px;">';
                        echo '<small class="sel-seat-error" style="display:none;">Please select a seat.</small>';
                        echo '<small class="max-seat-error" style="display:none;">You can only choose a maximum of '.$_SESSION['details']['total_count'].' seats at a time.</small>';
                        echo '</div>';
                        echo '</div>';
                    }

                    $flag = 0; // for flaging if sleeper or seater bus
                    $flag2 = 0; //  for flaging if completely vertical sleepers
                    $flagseatsleep1 = 0; // for seaters in lower
                    $flagseatsleep2 = 0; // for upper sleepers
                    $flaglsleep = 0; // flag if lower has sleepers
                    $flaglseat = 0; // flag if lower has seats
                    $rowvalue = 1;
                    $y = 0;

                    // Getting the chosen bus id.

                    if (isset($_POST['chosentwo'])) {
                        $chosenbusid = $_GET['chosentwo'];
                        // echo "The chosen bus id on second page ( after the filtering) is".$chosenbusid;
                    }
                    else {
                        $chosenbusid = $_GET['chosenone'];
                        // echo "The chosen bus id on main page is".$chosenbusid;
                    }

                    $sourceid = $_GET['sourceList'];
                    $destinationid = $_GET['destinationList'];
                    $date = $_GET['datepicker'];

                    $data = array('seats' => $_SESSION['seats']);
                    $tripdetails2 = $data;
                    $seats = $tripdetails2['seats'];

                    // foreach loop for the value variable

                foreach($tripdetails2 as $key => $value) {
                    if (is_array($value)) {
                        $s = array(array());
                        $sleeper = array(array(array()));
                        $seatsleep = array(array(array()));

                        foreach($value as $k => $v) {
                            foreach($v as $k1 => $v1) //checking first for seater and sleeper bus
                            {
                                if (isset($v['zIndex']) && isset($v['length']) && isset($v['width'])) {
                                    if ($v['zIndex'] == 0) // checks lower berths
                                    {
                                        if (($v['length'] == 2 && $v['width'] == 1) || ($v['length'] == 1 && $v['width'] == 2)) // both vertical and horizontal sleepers in Lower Berth
                                        {
                                            $flaglsleep = 1;
                                            $seatsleep[$v['zIndex']][$v['row']][$v['column']] = $v;
                                        }
                                        elseif ($v['length'] == 1 && $v['width'] == 1) {
                                            $flagseatsleep1 = 1;
                                            $flaglseat = 1;
                                            $seatsleep[$v['zIndex']][$v['row']][$v['column']] = $v;
                                        }
                                    }
                                    elseif ($v['zIndex']== 1) // only sleepers in  upper berths
                                    {
                                        $seatsleep[$v['zIndex']][$v['row']][$v['column']] = $v;
                                        $flagseatsleep2 = 1;
                                    }
                                }
                            } //ends foreach ($v as $k1 => $v1)
                        } //ends foreach ($value as $k => $v)

                        if (($flagseatsleep1 == 1) && ($flagseatsleep2 == 1)) // if it is a seater+sleeper
                        {
                            $rowcountseater = count($seatsleep[0]);
                            $max = 0;
                            $mini = array(); // holds the number of seats in every row
                            for ($i = 0; $i <= $rowcountseater; $i++) {
                                if (isset($seatsleep[0][$i])) {
                                    $mini[$i] = count($seatsleep[0][$i]);
                                }
                            }

                            $min = max($mini);
                            $posi = array();
                            $countter = 0;
                            for ($j = 0; $j <= $rowcountseater; $j++) // for finding the maximum number of seats in each row and using that as the limit in the for loop
                            {
                                $countter = 0;
                                $i = 0;
                                do {
                                    if (!empty($seatsleep[0][$j])) {
                                        if (empty($seatsleep[0][$j][$i])) {
                                            if (empty($seatsleep[0][$j][$i + 1])) {
                                                if (isset($mini[$j])) {
                                                    if ($countter == $mini[$j]) {
                                                        $posi[$j] = $i;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        else {
                                            $countter++;
                                            $pos = $i;
                                        }
                                    }

                                    $i++;
                                }

                                while (($i < $min * 2));
                            }

                            $actual = max($posi);
                            for ($i = 0; $i <= $rowcountseater; $i++) {
                                if (!empty($seatsleep[0][$i])) {
                                    if (count($seatsleep[0][$i]) > $max) {
                                        $max = count($seatsleep[0][$i]);
                                    }

                                    if (count($seatsleep[0][$i]) < $min) {
                                        $min = count($seatsleep[0][$i]);
                                    }
                                }
                            }

                            $rowcountsleeper = max(array_keys($seatsleep[1])) + 1;
                            $rowcountseater = max(array_keys($seatsleep[0])) + 1;

                            if( isset($seatsleep[1][0]) ){
                                $sleeperperrowcount = max(array_keys($seatsleep[1][0])) + 1;
                            }else{
                                $sleeperperrowcount = max(array_keys($seatsleep[1])) + 1;
                            }

                            // For getting the number of seats per row in seater

                            for ($i = 0; $i <= $rowcountseater; $i++) {
                                if (!empty($seatsleep[0][$i])) {
                                    $flagS = 0;
                                    $flagSL = 0;
                                    $seatcount = count($seatsleep[0][$i]);
                                    if (!empty($seatsleep[0][$i][0])) {
                                        if (($seatsleep[0][$i][0]['length'] == 2 && $seatsleep[0][$i][0]['width'] == 1) || ($seatsleep[0][$i][0]['length'] == 1 && $seatsleep[0][$i][0]['width'] == 2)) {
                                            $flagSL = 1;
                                        }
                                        else {
                                            $flagS = 1;
                                        }

                                        for ($j = 1; $j < $seatcount; $j++) {
                                            if (!empty($seatsleep[0][$i][$j])) {
                                                if ($flagS == 1 && (($seatsleep[0][$i][$j]['length'] == 2 && $seatsleep[0][$i][$j]['width'] == 1) || ($seatsleep[0][$i][$j]['length'] == 1 && $seatsleep[0][$i][$j]['width'] == 2))) {
                                                    $flagSL = 1;
                                                    break;
                                                }
                                                elseif ($flagSL == 1 && ($seatsleep[0][$i][$j]['length'] == 1 && $seatsleep[0][$i][$j]['width'] == 1)) {
                                                    $flagS = 1;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }

                                if ($flagS == 1 && $flagSL == 1) {
                                    break;
                                }
                            }

                            if ($flagS == 1 && $flagSL == 1) {
                                $seatperrowcount = $min * 2;
                            }
                            else {
                                $seatperrowcount = $max;
                            }

                            // ends finding the limit for the seater loop (number of seats in a row)
                            // FUNCTION CALL (1) UPPER BERTHS IN SEATER+SLEEPER

                            generatelayout($rowcountsleeper, $sleeperperrowcount, $seatsleep, 1, 1);

                            // LOWER BERTHS
                            // if seats and sleepers lower berths

                            if ($flaglseat == 1 && $flaglsleep == 1) {
                                generatelayout($rowcountseater, $actual, $seatsleep, 0, 1);
                            }
                            elseif ($flaglseat == 1 && $flaglsleep == 0) {
                                generatelayout($rowcountseater, $seatperrowcount, $seatsleep, 0, 1);
                            }
                        } //ends if it is a seater+sleeper

                        //  If its not sleeper+seater -> basic seater/ sleeper

                        elseif ((($flagseatsleep1 == 0) && ($flagseatsleep2 == 0)) || (($flagseatsleep1 == 1) && ($flagseatsleep2 == 0)) || (($flagseatsleep1 == 0) && ($flagseatsleep2 == 1))) {

                            $sleepersize = array(array(array()));

                            foreach($value as $k => $v) {
                                foreach($v as $k1 => $v1) {
                                    if (isset($v['length']) && isset($v['width'])) {
                                        if ($v['length']== 1 && $v['width'] == 1) // condition for seater or semi-sleeper
                                        {
                                            $flag = 2;
                                            if (!strcmp($k1, 'row')) {
                                                $s[$v1][$v['column']] = $v;
                                            }
                                        }
                                        else
                                        if (($v['length'] == 2 && $v['width'] == 1) || ($v['length'] == 1 && $v['width'] == 2)) // condition for horizontal sleeper
                                        {
                                            $flag = 1;
                                            if ($v['length'] == 2 && $v['width'] == 1) {
                                                $flag2 = 1;
                                            }

                                            if (!strcmp($k1, 'row')) {
                                                if ($v1 >= $rowvalue) {
                                                    $rowvalue = $v1;
                                                }

                                                $sleeper[$v['zIndex']][$v1][$v['column']] = $v;
                                                $sleepersize[$v['zIndex']][$v1][$v['column']] = $v['column'];
                                            }
                                        }
                                    }
                                }
                            }

                            $rowcountseater = count($s);
                            if( !empty($s[0]) ){
                                $seatperrowcount = max(array_keys($s[0])) + 1;//count($s[0]);
                            }else{
                                $seatperrowcount = 1;
                            }
                            $c = 0;
                            for ($i = 0; $i <= $rowvalue; $i++) {
                                if (!empty($sleeper[0][$i])) {
                                    $c++;
                                }
                            }

                            $rowcountsleeper = $c;

                            // If it is a sleeper

                            if ($flag == 1) {
                                if (!empty($sleeper[0][$rowvalue])) {
                                    $sleeperperrowcount0 = count($sleeper[0][$rowvalue]);
                                }
                                else {
                                    $sleeperperrowcount0 = 0;
                                }

                                if (!empty($sleeper[1][$rowvalue])) {
                                    $sleeperperrowcount1 = count($sleeper[1][$rowvalue]);
                                } // change made here
                                else {
                                    $sleeperperrowcount1 = 0;
                                }

                                $sleeperperrowcount = max($sleeperperrowcount1, $sleeperperrowcount0);
                                $MAXX = 0;
                                for ($i = 1; $i >= 0; $i--) {
                                    for ($j = 0; $j <= $rowvalue; $j++) {
                                        if (!empty($sleepersize[$i][$j])) {
                                            $X = max($sleepersize[$i][$j]);
                                        }
                                        else {
                                            $X = 0;
                                        }

                                        if ($X > $MAXX) {
                                            $MAXX = $X;
                                        }
                                    }

                                    if ($flag2 == 1) // horizontal + vertical sleepers
                                    {

                                        // generate seatlayout
                                        generatelayout($rowvalue, $MAXX, $sleeper, $i, 0);
                                    }
                                    else {
                                        $Z = $sleeperperrowcount + 1;
                                        generatelayout($rowvalue, $Z, $sleeper, $i, 0);
                                    }
                                }
                            }
                            elseif ($flag == 2) // If it is seater
                            {
                                if (!empty($s)) {
                                    generateLayoutSeater($rowcountseater, $seatperrowcount, $s);
                                }
                            }
                        } // ends if NOT sleeper+seater
                    } //ends if(is_array($value))
                } // foreach loop for the value variable ends

                echo "<div class='hide'>";

                echo "<div>Seats</div>";
                echo "<textarea id='t' name='seatnames' class='input'>Seats:</textarea><br /><br />";
                echo "<input type='hidden' name='droppingpnts' class='droppingpnts' value=''/>";
                echo "<input type='hidden' name='arrival_time' value='".$_SESSION['boardingpnts']['arrivalTime']."'/>";
                
                echo "</div>";

                $y = 0;

                function generatelayout($rowcountsleeper, $sleeperperrowcount, $seatsleep, $UpperLower, $horVer)
                {
                    if ($UpperLower == 1) {
                        echo "<span class='caption'><br />UPPER SECTION</span> ";
                        $i = 1;
                        if ($horVer == 1) {
                            $klimit = ($sleeperperrowcount * 2 + 1);
                        }
                        elseif ($horVer == 0) {
                            $klimit = $sleeperperrowcount + 1;
                        }
                    }
                    elseif ($UpperLower == 0) {
                        echo "<br /><span class='caption'>LOWER SECTION</span> ";
                        $i = 0;
                        $klimit = $sleeperperrowcount;
                    }

                    $x = 0;
                    global $y;
                    echo "<table class='cust_bus_table' ><tbody>";
                    $l = 0;
                    for ($j = 0; $j <= $rowcountsleeper; $j++) {
                        echo "<tr>";
                        for ($k = 0; $k <= $klimit - 1; $k++) {
                            if (!empty($seatsleep[$i][$j][$k])) {
                                if ($seatsleep[$i][$j][$k]['length'] == 2 && $seatsleep[$i][$j][$k]['width'] == 1) {
                                    if (!strcmp($seatsleep[$i][$j][$k]['available'], 'true')) {
                                        if (!strcmp($seatsleep[$i][$j][$k]['ladiesSeat'], 'true')) {
                                            echo "<td><div id='c_b'><label for='hsleep" . $i . $j . $k . "'><img name='hsleep" . $y . "'src='../../../images/bus_sleeper_ladies_horizontal.png' title='Seat Number:" . $seatsleep[$i][$j][$k]['name'] . " | Fare: " . $seatsleep[$i][$j][$k]['fare'] . "' class='imagehover'/><input type='checkbox' name='chkchk[]' class='checkbox' id='hsleep" . $i . $j . $k . "' value='" . $seatsleep[$i][$j][$k]['name'] . "' onclick='swapladieshsleeper(this, " . $y++ . ")' style='display:none;'/></label></div></td>";
                                        }
                                        else {

                                            if( empty($y) ){
                                                $y = 1;
                                            }

                                            echo "<td><div id='c_b'><label for='hsleep" . $i . $j . $k . "'><img name='hsleep" . $y . "'src='../../../images/bus_sleeper_empty_horizontal.png' title='Seat Number:" . $seatsleep[$i][$j][$k]['name'] . " | Fare: " . $seatsleep[$i][$j][$k]['fare'] . "' class='imagehover'/><input type='checkbox' name='chkchk[]' class='checkbox' id='hsleep" . $i . $j . $k . "' value='" . $seatsleep[$i][$j][$k]['name'] . "'onclick='swaphsleeper(this, " . $y++ . ")' style='display:none;'/></label></div></td>";
                                        }
                                    }
                                    else {
                                        echo "<td><div><img src='../../../images/bus_sleeper_occupied_horizontal.png' title='Already Booked'/></div></td>";
                                    }
                                }
                                elseif ($seatsleep[$i][$j][$k]['length'] == 1 && $seatsleep[$i][$j][$k]['width'] == 2) {
                                    if (!strcmp($seatsleep[$i][$j][$k]['available'], 'true')) {
                                        if (!strcmp($seatsleep[$i][$j][$k]['ladiesSeat'], 'true')) {
                                            echo "<td><div id='c_b' class='container_vert'><label for='vsleep" . $i . $j . $k . "'><img name='vsleep" . $y . "'src='../../../images/bus_sleeper_ladies_vertical.png' title='Seat Number:" . $seatsleep[$i][$j][$k]['name'] . " | Fare: " . $seatsleep[$i][$j][$k]['fare'] . "' class='imagehover'/><input type='checkbox' class='checkbox' name='chkchk[]' id='vsleep" . $i . $j . $k . "' value='" . $seatsleep[$i][$j][$k]['name'] . "' onclick='swapvsleeper(this," . $y++ . ")' style='display:none;'/></label></div></td>";
                                        }
                                        else {
                                            echo "<td><div id='c_b' class='container_vert'><label for='vsleep" . $i . $j . $k . "'><img name='vsleep" . $y . "'src='../../../images/bus_sleeper_empty_vertical.png' title='Seat Number:" . $seatsleep[$i][$j][$k]['name'] . " | Fare: " . $seatsleep[$i][$j][$k]['fare'] . "' class='imagehover'/><input type='checkbox' class='checkbox' name='chkchk[]' id='vsleep" . $i . $j . $k . "' value='" . $seatsleep[$i][$j][$k]['name'] . "'onclick='swapvsleeper(this," . $y++ . ")' style='display:none;'/></label></div></td>";
                                        }
                                    }
                                    else {
                                        echo "<td><div class='container_vert_un'><img src='../../../images/bus_sleeper_occupied_vertical.png' title='Already Booked'/></div></td>";
                                    }
                                }
                                elseif ($seatsleep[$i][$j][$k]['length'] == 1 && $seatsleep[$i][$j][$k]['width'] == 1) {
                                    $storeseatname = $seatsleep[$i][$j][$k]['name'];
                                    if (!strcmp($seatsleep[$i][$j][$k]['available'], 'true')) {
                                        if (!strcmp($seatsleep[$i][$j][$k]['ladiesSeat'], 'true')) {
                                            echo "<td><div id='c_b'><label for='seat" . $j . $k . "'><img name='img" . $l . "' id='" . $k . $j . "' src='../../../images/bus_seater_ladies.png' title='Seat Number:" . $seatsleep[$i][$j][$k]['name'] . " | Fare: " . $seatsleep[$i][$j][$k]['fare'] . "' class='imagehover'/><input type='checkbox' class='checkbox' name='chkchk[]' id='seat" . $j . $k . "' value='" . $seatsleep[$i][$j][$k]['name'] . "' onclick='swapladiesseater(this," . $l++ . ")' style='display:none;'/></label></div></td>";
                                        }
                                        else {
                                            echo "<td><div id='c_b'><label for='seat" . $j . $k . "'><img name='img" . $l . "' id='" . $k . $j . "'src='../../../images/bus_seater_empty.png' title='Seat Number:" . $seatsleep[$i][$j][$k]['name'] . " | Fare: " . $seatsleep[$i][$j][$k]['fare'] . "' class='imagehover' /><input  type='checkbox' class='checkbox' name='chkchk[]' id='seat" . $j . $k . "' value='" . $seatsleep[$i][$j][$k]['name'] . "' onclick='swapseater(this," . $l++ . ")' style='display:none;'/></label></div></td>";
                                        }
                                    }
                                    else {
                                        echo "<td><div><img src='../../../images/bus_seater_occupied.png' title='Already Booked'/></div></td>";
                                    }
                                }
                            }

                            if (empty($seatsleep[$i][$j][$k])) {
                                if (empty($seatsleep[$i][$j])) {
                                    echo "<td><img src='../../../images/no_seat.png'/></td>";
                                }
                                elseif (!empty($seatsleep[$i][$j])) {
                                    echo "<td></td>";
                                }
                            }
                        }

                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                }

                function generateLayoutSeater($rowcountseater, $seatperrowcount, $s)
                {
                    if (!empty($s)) {
                        echo "<table class='cust_bus_table'><tbody>";
                        $k = 0;
                        for ($i = 0; $i <= $rowcountseater; $i++) {
                            echo "<tr>";
                            for ($j = 0; $j <= $seatperrowcount - 1; $j++) {
                                if (!empty($s[$i][$j])) {
                                    $storeseatname = $s[$i][$j]['name'];
                                    if (!strcmp($s[$i][$j]['available'], 'true')) {
                                        if (!strcmp($s[$i][$j]['ladiesSeat'], 'true')) {
                                            echo "<td><div id='c_b'><label for='seat" . $i . $j . "'><img name='img" . $k . "' id='" . $j . $i . "' src='../../../images/bus_seater_ladies.png' title='Seat Number:" . $s[$i][$j]['name'] . " | Fare: " . $s[$i][$j]['fare'] . "' class='imagehover'/><input type='checkbox' class='checkbox' name='chkchk[]' id='seat" . $i . $j . "' value='" . $s[$i][$j]['name'] . "' onclick='swapladiesseater(this," . $k++ . ")' style='display:none;'/></label></div></td>";
                                        }
                                        else {
                                            echo "<td><div id='c_b'><label for='seat" . $i . $j . "'><img name='img" . $k . "' id='" . $j . $i . "' src='../../../images/bus_seater_empty.png' title='Seat Number:" . $s[$i][$j]['name'] . " | Fare: " . $s[$i][$j]['fare'] . "' class='imagehover' /><input  type='checkbox' class='checkbox' name='chkchk[]' id='seat" . $i . $j . "' value='" . $s[$i][$j]['name'] . "' onclick='swapseater(this," . $k++ . ")' style='display:none;'/></label></div></td>";
                                        }
                                    }
                                    else {
                                        echo "<td><div><img src='../../../images/bus_seater_occupied.png' title='Already Booked'/></div></td>";
                                    }
                                }

                                if (empty($s[$i][$j])) {
                                    if (empty($s[$i])) {
                                        echo "<td><img src='../../../images/no_seat.png'/></td>";
                                    }
                                    elseif (!empty($s[$i])) {
                                        echo "<td></td>";
                                    }
                                }
                            }
                            echo "</tr>";
                        }

                        echo "</table><br />";
                    }
                }

                echo "<input type='hidden' name='chosensource' class='btnclass' value='" . $sourceid . "'/>";
                echo "<input type='hidden' name='chosendestination' class='btnclass' value='" . $destinationid . "'/>";
                echo "<input type='hidden' name='date' class='btnclass' value='" . $_GET['datepicker'] . "'/>";
                echo "<input type='hidden' name='chosenbus' class='btnclass' value='" . $chosenbusid . "' /></td>";
                echo "</div>";
                ?>
            </div>
            <div class="col-lg-10">
                <div class="row">
                    <h6>Seats</h6>
                    <table class="bus-legend">
                        <tbody>
                            <tr>
                                <td><img class="bus_legend_img" src='../../../images/bus_seater_occupied.png' alt="ac semi sleeper vacant"></td>
                                <td>Semi Sleeper Occupied</td>
                                <td><img class="bus_legend_img sleeper" src='../../../images/bus_sleeper_occupied_horizontal.png' alt="sleeper Occupied"></td>
                                <td>Sleeper Occupied</td>
                            </tr>
                            <tr>
                                <td><img class="bus_legend_img" src='../../../images/bus_seater_empty.png' alt="ac semi sleeper vacant"></td>
                                <td>Semi Sleeper Vacant</td>
                                <td><img class="bus_legend_img sleeper" src='../../../images/bus_sleeper_empty_horizontal.png' alt="sleeper vacant"></td>
                                <td>Sleeper Vacant</td>
                            </tr>
                            <tr>
                                <td><img class="bus_legend_img" src='../../../images/bus_seater_selected.png' alt="ac semi sleeper selected"></td>
                                <td>Semi Sleeper Selected</td>
                                <td><img class="bus_legend_img sleeper" src='../../../images/bus_sleeper_selected_horizontal.png' alt="ac semi sleeper vacant"></td>
                                <td>Sleeper Selected</td>
                            </tr>
                            <tr>
                                <td><img class="bus_legend_img" src='../../../images/bus_seater_ladies.png' alt="ac semi sleeper vacant"></td>
                                <td>Ladies Seat</td>
                                <td><img class="bus_legend_img sleeper" src='../../../images/bus_sleeper_ladies_horizontal.png' alt="sleeper vacant ladies"></td>
                                <td>Sleeper Vacant Ladies</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <h6 class="sel_seats_text">Selected Seats:</h6>
                        <h4 class="selected_seats"></h4>
                    </div>
                    <div class="col-xs-12">
                        <h6 class="number_of_seats">Number of Seats:</h6>
                        <h4 class="number_of_seats_count">0</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-offset-12 col-xs-12">
                        <h6 class="total_fare_text">Total Fare:</h6>
                        <h4><span class="h4">&#x20B9; </span><span class="total_fare" >0</span></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-24">
                        <h6>Boarding Points:</h6>
                        <?php 
                            if( isset($_SESSION['boardingpnts']['boardingTimes']) ){
                                if( key($_SESSION['boardingpnts']['boardingTimes']) == "0" ){
                                    echo "<div class='col-lg-20 remove-padding'>";
                                    echo "<div class='col-lg-24 error-msg-boarding' style='display:none;'><center><small class='sel-seat-error'>Please select a Boarding Point</small></center></div>";
                                    echo "<select name='droppingpnts' id='droppingpnts'>";
                                    echo "<option value='Please select boarding point'>Please select a boarding point</option>";
                                    foreach($_SESSION['boardingpnts']['boardingTimes'] as $dpp){
                                        $oneDay=24*60;
                                        $noOfDays = floor($dpp['time'] / $oneDay);
                                        $time = ($dpp['time']) % $oneDay;
                                        $hours = floor($time/60);
                                        $minutes = floor($time%60);
                                        if($hours < 10)
                                            $hours = '0'.$hours;
                                        if($minutes < 10)
                                            $minutes = '0'.$minutes;
                                        echo "<option value='".$dpp['bpId']."'>".$dpp['bpName']." - ".$hours.":".$minutes."</option>";
                                    }
                                    echo "</select></div>";
                                }else{
                                    $oneDay=24*60;
                                    $noOfDays = floor($_SESSION['boardingpnts']['boardingTimes']['time'] / $oneDay);
                                    $time = ($_SESSION['boardingpnts']['boardingTimes']['time']) % $oneDay;
                                    $hours = floor($time/60);
                                    $minutes = floor($time%60);
                                    if($hours < 10)
                                        $hours = '0'.$hours;
                                    if($minutes < 10)
                                        $minutes = '0'.$minutes;
                                    echo "<div class='col-lg-16 remove-padding'>";
                                    echo "<select name='droppingpnts' id='droppingpnts'>";
                                    echo "<option value='Please select boarding point'>Please select a boarding point</option>";
                                    echo "<option value='".$_SESSION['boardingpnts']['boardingTimes']['bpId']."'>".$_SESSION['boardingpnts']['boardingTimes']['bpName']." - ".$hours.":".$minutes."</option>";
                                    echo "</select></div>";
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-24">
                        <button type="submit" class="pull-left btn btn-change form-padding cont-btn">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script type="text/javascript">
    var droppingpnts_selected = 0;

    function swapImage(id,primary,secondary) 
    {
        src=document.getElementById(id).src;
        if (src.match(primary)) {
          document.getElementById(id).src=secondary;
        } else {
          document.getElementById(id).src=primary;
        }
    }


    function updateTextArea() 
    {
        $('.selected_seats').html('');
        $('.number_of_seats_count').html('0');
        $('span.total_fare').html('');
        var allVals = [];
        var allFares = [];
        $('#c_b :checked').each(function() {
            var fareExtractionArr = $(this).siblings().attr('title');
            var fareStrArr = fareExtractionArr.split('|');
            var fareStr = fareStrArr[1];
            var fareValueArr = fareStr.split(':');
            var fareValue = parseFloat(fareValueArr[1]);
            allFares.push(fareValue);
            allVals.push($(this).val());
        });

        var total_fare=0;

        $.each(allFares, function(i, val){
            total_fare += val;
        });
        $('span.total_fare').autoNumeric('set', total_fare);

        $('#t').val(allVals)
        var len = allVals.length;
        len = len - 1;
        if( len != -1 ){
            var seat = len+1;
            $('.sel_seats_text').html('Selected Seats:');
            $('.number_of_seats_count').html(seat);
        }else{
            $('.sel_seats_text').html('Selected Seats:');
        }
        
        $.each(allVals, function(i, val){
            $('.selected_seats').append(val);
            if( len != i ){
                $('.selected_seats').append(', ');
            }
        });
             
    }
$(function() 
{

    $(document).ready(function(){
        $('#form3').each(function() { this.reset() });
    });

//tooltip code
    $('#origin').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: "<?php echo $_GET['source'];?>"
        });
        $(this).tooltip('show');
    });

    $('#destination').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: "<?php echo $_GET['destination'];?>"
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

    $('span.total_fare').autoNumeric('init', {
        aSep: ',',
        dGroup: 2
    });

$('button.cont-btn').on('click', function( e ){
    e.preventDefault();
    var fields = {};
    $("#form3").find(":input:checked").each(function() {
        // The selector will match buttons; if you want to filter
        // them out, check `this.tagName` and `this.type`; see
        // below
        fields['seat'] = $(this).val();
    });
    if( typeof fields.seat === 'undefined' ){
        $('.error-msg .sel-seat-error').fadeIn(100);
        setTimeout(function(){
            $('.error-msg .sel-seat-error').fadeOut(100);
        }, 4000);
    }else if( droppingpnts_selected === 0 ){
        $('.error-msg-boarding').fadeIn(100);
        setTimeout(function(){
            $('.error-msg-boarding').fadeOut(100);
        }, 4000);
    }else{
        $('#form3').submit();
    }
});

<?php if(isset($_SESSION['details']['total_count'])):?>
        $('#c_b input').on('click', function(){
            if( $(this).is(':checked') ){
                if( max_count > max_seat_selectable ){
                    return false;
                }else{
                    updateTextArea();
                }
            }else{
                updateTextArea();
            }
        });
<?php else:?>
        $('#c_b input').on('click', function(){
            updateTextArea();
        });
<?php endif;?>
        
        $('#droppingpnts').selectOrDie({
            placeholderOption: true,
            size: 5,
            onChange: function(){
                droppingpnts_selected = 1;
                var selected_pickup = $(this).val();
                $('.droppingpnts').val(selected_pickup);
            }
        });
    });

</script>
<div id="selection"></div>
<?php
    @session_start();
    $input_arr = $_GET;
    $input_arr['boardingpnts'] = $_SESSION['boardingpnts'];

    if( isset($_SESSION['droppingpnts']) ){
        $input_arr['droppingpnts'] = $_SESSION['droppingpnts'];
    }

    $_SESSION['seat_details'] = $data;
    $_SESSION['journey_details'] = $input_arr;
?>
</html>
