<script type="text/javascript"> 

function stopRKey(evt) { 
    var evt = (evt) ? evt : ((event) ? event : null); 
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
    if ((evt.keyCode == 13)) {return false;} 
} 

document.onkeypress = stopRKey; 

</script>
<?php
    $flights_len = count($_SESSION['flight_data']);
    $details = array();
    $diff_mode_flag = $cab_present = 0;
    foreach( $_SESSION['flight_data'] as $chk ){
        if( $chk['ov']->mode != 'flight' ){
            $diff_mode_flag = 1;
        }
        if( $chk['ov']->mode == 'cab' ){
            $cab_present = 1;
        }

    }

    for( $i = 0 ; $i < $flights_len ; $i++ ){
        if( $_SESSION['flight_data'][$i]['ov']->mode == 'flight' ){
            $details[$i] = json_decode($_SESSION['flight_data'][$i]['booking_details']);
        }
    }
        $adult = $_SESSION['details']['adult_count'];
        $child = $_SESSION['details']['youth_count'];
        $infant = $_SESSION['details']['kids_count'];
        if($adult && $child && $infant)
        $pass = $adult.'Adult(s) '.$child.'Child(s) '.$infant.'Infant(s)';
        else if($adult && $child)
        $pass = $adult.'Adult(s) '.$child.'Child(s)';
        else if($adult && $infant)
        $pass =  $adult.'Adult(s) '.$infant.'Infant(s)';     
        else if($child && $infant)  
        $pass =  $child.'Child(s) '.$infant.'Infant(s)'; 
        else if($adult)
        $pass = $adult.'Adult(s)';
        else if($child)
        $pass = $child.'Child(s)';
        else
        $pass = $infant.'Infant(s)';

        $flight_base_fare = 0;
        $flight_tax_fare = 0;
        $bus_base_fare = 0;
        $bus_tax_fare = 0;
        $cab_base_fare = 0;
        $cab_tax_fare = 0;
        $same_flag = 0;
        $total_base_fare = 0;
        $total_tax_fare = 0;
        $total_fare = 0;
        $old_total = 0;

        for( $i = 0 ; $i < $flights_len ; $i++ ){
            if( $_SESSION['flight_data'][$i]['ov']->mode == 'flight' ){
                if( $same_flag == 0 ){
                    $same_flag = 1;
                }
                if((isset($_SESSION['hasFareChanged'])) && ($_SESSION['hasFareChanged']) == 1){
                    $total_base_fare += $_SESSION['new_base_fare'];
                    $total_tax_fare += $_SESSION['new_tax'];
                    $total_fare += $_SESSION['new_total_fare'];
                    $old_total += $_SESSION['old_total_fare'];
                }
                else{
                    $fare_obj = $details[$i]->rest->Fare;
                    $total_base_fare += $fare_obj->BaseFare;
                    $total_tax_fare += $fare_obj->AdditionalTxnFee + $fare_obj->AirTransFee + $fare_obj->Tax + $fare_obj->OtherCharges + $fare_obj->ServiceTax;
                }

            }elseif( $_SESSION['flight_data'][$i]['ov']->mode == 'cab' ){
                $same_flag = 2;
                $cab_base_fare = $_SESSION['flight_data'][$i]['ov']->totalFare;
                $cab_tax_fare = 0;
            }elseif( $_SESSION['flight_data'][$i]['ov']->mode == 'bus' ){
                $same_flag = 2;
                $bus_base_fare += $_SESSION['flight_data'][$i]['ov']->fareDet['baseFare'];
                $bus_tax_fare += $_SESSION['flight_data'][$i]['ov']->fareDet['serviceTaxAbsolute'];
            }
        }
        $total_base_fare = $total_base_fare + $cab_base_fare + $bus_base_fare;
        $total_tax_fare = $total_tax_fare + $cab_tax_fare + $bus_tax_fare;
        $total_fare = $total_base_fare+$total_tax_fare;

        //echo "<pre>";
        //print_r( $total_fare);die;
        
        $convenience_charge = ($convenience_charge/100)*$total_fare;
        $old_conv = ($convenience_charge/100)*$old_total;
        $old_total_field = $convenience_charge + $old_total;
        $total_fare_field = $convenience_charge + $total_fare;

?>
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
        font-family: "Oswald";
        padding-top: 0px;
    }
    .travel-margin{
        margin-left: 1.05%;
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
        border-top: none !important;
        line-height: 1.8em !important;
        font-size: 12px !important;
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
    .tripSummary{
        font-family: "Open Sans", sans-serif;
        font-size: 18px;
        font-weight: 400;
        color: #000;
    }
    .row-padding{
        padding-right: 10px;
        padding-left: 10px;
    }

    .activityLocaltionName{
        margin-top: -5px;
        font-size: 11px;
    }
    .sod_select{
        margin-top: 0;
    }

    .fl_nav_bar{
        border-bottom:1px solid #ddd;
        height: 72px;
        margin-top: 7px;
    }
    .sod_select{
        text-transform: none;
    }
    small.help-block{
        color: #ff0000;
    }

    .fl_no{
        padding-top: 7px;
    }
</style>
<!--modal screen-->
<div class="modal fade" id="login_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h4>Please Login or Register to Continue</h4></center>
                <!-- <div class="pull-right"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div> -->
            </div>
            <div class="modal-body">
                <div class="login_logo"></div>                
                <!-- The form is placed inside the body of modal -->
                <div class="row">
                    <div class="col-lg-12">
                        <form action="<?php echo base_url('index.php/login/guest_register'); ?>" id="guest_register" method="post" class="form-horizontal"  accept-charset="utf-8">
                            <h4 class="col-xs-24 userLoginHeader loginFormField center-align-text">Guest Login</h4>
                            <div class="row">
                                <div class="col-lg-offset-5 col-lg-14 loginFormField">
                                    <input type="email" class="form-control" name="guest_email" placeholder="E-Mail"/>
                                </div>
                            </div>
                            <div class="col-lg-offset-15 row">
                                <button type="button" class="btn btn-change res-btn resultsRow" id="guest_reg_submit">Login</button>
                            </div>
                        </form>
                        <div class="orIcon">OR</div>
                    </div>
                    <div class="col-lg-12 sp-mid">
                        <form action="<?php echo base_url('index.php/login/login_user_modal'); ?>" id="login_user" method="post" class="form-horizontal"  accept-charset="utf-8">
                            <h4 class="col-xs-24 userLoginHeader loginFormField center-align-text">User Login</h4>
                            <div class="row">
                                <div class="col-lg-offset-5 col-lg-14 loginFormField">
                                    <input type="email" class="form-control" name="email" placeholder="E-Mail"/>
                                </div>
                                <div class="col-lg-offset-5 col-lg-14 loginFormField">
                                    <input type="password" class="form-control" name="password" placeholder="Password"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-offset-5 col-lg-5">
                                    <button type="button" class="btn btn-change res-btn resultsRow" id="login_user_submit">Login</button>
                                </div>
                            </div>
                            <div class="row reg_link">
                                <div class="col-lg-offset-5 col-lg-12 left-text">
                                    <a href="#" id="register">Register</a><br />
                                    <a href="#" id="forgot_pass">Forgot Password</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="loginMessageBlock">
                        <center><small class="errorMessage" style="display:none;"></small></center>
                        <center><small class="successMessage" style="display:none;"></small></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end modal screen-->

<!-- change password modal -->
<div class="modal fade fp_resize" id="forgot_pass_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Forgot Password
                <!-- <div class="pull-right"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div> -->
            </div>
            <div class="modal-body">
                <div class="row">
                    <label for="email">Please enter the registered Email Id.</label>
                    <div class="form-group">
                        <div class="col-xs-offset-4 col-xs-16 control-label remove-padding">
                            <input type="text" name="email" id="email_fp" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group left-text col-xs-offset-4">
                        <div class="col-xs-5 control-label remove-padding form-padding">
                            <button type="button" class="btn btn-change" id="chk_email_btn">Submit</button>
                        </div>
                        <div class="col-xs-5 control-label remove-padding form-padding">
                            <button type="button" class="btn btn-change" id="cancel_email_btn">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- change password modal end -->

<!-- register modal -->
<div class="modal fade reg_resize" id="register_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Register New User
                <!-- <div class="pull-right"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div> -->
            </div>
            <div class="modal-body">
                <div class="col-xs-24 userRegisterContainer">
                    <div class="row">
                        <form id="form-1" class="col-xs-24 userRegistrationForm">
                            <div class="col-xs-9 col-xs-offset-2 title_user remove-padding">
                                <div class="form-group title_error">
                                    <select class="selectpicker control-label add-on-table" name="title_user"><option value="">Title</option><option>Miss</option><option>Mr</option><option>Mrs</option></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-9 col-xs-offset-2 control-label remove-padding">
                                    <input class="userRegisterFirstName loginFormField form-control forceAlpha" type="text" name="firstName" placeholder="First Name" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-9 col-xs-offset-1 control-label remove-padding"> 
                                    <input class="userRegisterLastName loginFormField form-control forceAlpha" type="text" name="lastName" placeholder="Last Name" />
                                </div>
                            </div>
                            <div class="col-xs-13 col-xs-offset-2 remove-padding">
                                <div class="form-group gender_error pull-left">
                                    <div class="btn-group remove-padding control-label" data-toggle="buttons">
                                        <label class="btn btn-change-radio">
                                            <input class="form-control" type="radio" name="gender" value="male" /> Male
                                        </label>
                                        <label class="btn btn-change-radio">
                                            <input class="form-control" type="radio" name="gender" value="female" /> Female
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="birth_date col-xs-21 col-xs-offset-2 control-label inner-addon right-addon remove-padding">
                                    <i class="glyphicon"></i>
                                    <input name="dob" id="date-1" readonly class="form-control" type="text" placeholder="Birthday Date" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-21 col-xs-offset-2 control-label remove-padding">
                                    <input class="userRegisterEmail loginFormField form-control" type="text" name="email" placeholder="Email" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-21 col-xs-offset-2 control-label remove-padding">
                                    <input class="userRegisterEmail loginFormField form-control forceNumeric" type="text" name="phone_no" placeholder="Phone Number" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-21 col-xs-offset-2 control-label remove-padding">
                                    <input class="userRegisterPassword loginFormField form-control" type="password" name="password" placeholder="Password" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-21 col-xs-offset-2 control-label remove-padding">
                                    <input class="userRegisterConfirmPassword loginFormField form-control" type="password" name="confirm_password" placeholder="Confirm Password" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-21 col-xs-offset-2 registerMessageBlock">
                                    <small class="errorMessage" style="display:none;"></small>
                                    <small class="successMessage" style="display:none;"></small>
                                </div>
                            </div>
                            <button type="submit" class="col-xs-9 col-xs-offset-2 loginFormField userRegisterBtn reg-submit"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Register</button>
                            <button type="button" class="col-xs-9 col-xs-offset-1 loginFormField userRegisterBtn btn btn-change" id="cancel_register_btn">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- register modal end -->

<!-- terms and conditions modal -->
<div class="modal fade reg_resize" id="termsAndConditions">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Terms And Conditions
                <div class="pull-right"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>
            </div>
            <div class="modal-body">
                <?php include(APPPATH . "views/common/termsncond.php");?>
            </div>
        </div>
    </div>
</div>
<!-- terms and conditions modal end -->

<!-- popover screen -->
<div class="hidden-fields">
    <?php for( $hc = 0 ; $hc < $flights_len ; $hc++):?>
        <div id="popoverHiddenContent-<?php echo $hc+1;?>" style="display: none">
            <?php if( isset($_SESSION['flight_data'][$hc]) && $_SESSION['flight_data'][$hc]['ov']->mode == 'flight'): 
                    if( is_array($_SESSION['flight_data'][$hc]['ov']->WSSegment) ):?>

                <?php $max_arr_len = count($_SESSION['flight_data'][$hc]['ov']->WSSegment); $iter = 0;?>
                <?php foreach( $_SESSION['flight_data'][$hc]['ov']->WSSegment as $ws ):?>
                    <?php
                        $depTime = date('H:i', strtotime($ws->DepTIme));
                        $arrTime = date('H:i', strtotime($ws->ArrTime));
                        $depDate = date('d M Y', strtotime($ws->DepTIme));
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
                $depDate = date('d M Y', strtotime($_SESSION["flight_data"][$hc]["ov"]->WSSegment->DepTIme));
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

    <?php for( $ht = 0 ; $ht < $flights_len ; $ht++):?>
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
    <?php endif; endfor;?>
</div>

<!-- popover screen end -->

<div class="wrap">
    <div class="clear-top">
        <div class="grey-bottom-separator center-align-text">
            <span class="glyphicon glyphicon-user hulk-class"></span>
            <?php if($adult == 1):?>
                <span><?php echo $adult;?> Adult, </span>
            <?php else:?>
                <span><?php echo $adult;?> Adults, </span>
            <?php endif;?>
            <?php if($child == 1):?>
                <span><?php echo $child;?> Child, </span>
            <?php else:?>
                <span><?php echo $child;?> Children, </span>
            <?php endif;?>
            <?php if($infant == 1):?>
                <span><?php echo $infant;?> Infant</span>
            <?php else:?>
                <span><?php echo $infant;?> Infants</span>
            <?php endif;?>
        </div>
        <nav class="navbar fixed-top fl_nav_bar">
            <div  class="container-fluid" id="info">
                <div class="row">
                    <?php for( $n = 0 ; $n < $flights_len ; $n++):?>
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
                                            <div class="col-lg-11 remove-padding ellipse" id="origin<?php echo $n+1;?>"><?php echo $_SESSION['flight_data'][$n]['ov']->org;?></div>
                                            <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                            <div class="col-lg-11 remove-padding ellipse" id="destination<?php echo $n+1;?>"><?php echo $_SESSION['flight_data'][$n]['ov']->dest;?></div>
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
                                    <div class="link-btn-de link-btn-de-traveller">
                                        <a href="#" class='btn-de' tabindex="0" id='popover-toggle-<?php echo $n+1;?>' data-toggle="popover" data-placement="bottom">DETAILS</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor;?>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="col-xs-24 col-sm-14 col-md-18">
                <?php if( $same_flag != 2 ):?>
                    <form action="<?php echo site_url('api/flights/book_multi')?>" method="post" id="travellers-form" enctype="multipart/form-data">
                        <input name="details" value="" style="display:none;"/>
                        <input name="key" value="gtKFFx" style="display:none;"/>
                        <input name="multiplex_total_fare" value="<?php echo $total_fare_field;?>" style="display:none;"/>
                        <input name='productinfo' type='text' value='multiplexed tickets' style="display:none;"/>
                    </form>
                <?php else:?>
                    <form action="<?php echo site_url('common/book_assorted')?>" method="post" id="travellers-form" enctype="multipart/form-data">
                        <input name="details" value="" style="display:none;"/>
                        <input name="key" value="gtKFFx" style="display:none;"/>
                        <input name="multiplex_total_fare" value="<?php echo $total_fare_field;?>" style="display:none;"/>
                        <input name='productinfo' type='text' value='multiplexed tickets' style="display:none;"/>
                    </form>
                <?php endif;?>
            </div>
            <div class="col-xs-24 col-sm-6 col-md-6 bookingDetailsContainer">
                <div class="row bookingSelectionDetails tripSummary center-align-text">Trip Summary</div>
                <div class="row">
                    <div class="col-xs-24 col-sm-24 col-md-24 bookingSelectionDetails">
                        <table class="table table-condensed table-custom" >
                            <tbody>
                                <tr>
                                    <td>No.of Passengers</td>
                                    <td class="right-text tf-cng">
                                        <?php 
                                            if( $adult ){
                                                if( $adult == 1 )
                                                    echo $adult." Adult";
                                                else
                                                    echo $adult." Adults";
                                            } 
                                            if( $child ){ 
                                                if( $child == 1 )
                                                    echo ", ".$child." Child";
                                                else
                                                    echo ", ".$child." Children";
                                            } 
                                            if( $infant ){
                                                if( $infant == 1 )
                                                    echo "& ".$infant." infant";
                                                else
                                                    echo "& ".$infant." infants";
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Taxes</td>
                                    <td class="right-text tf-cng"><i class="fa fa-inr"></i>&nbsp;<?php echo number_format($total_tax_fare);?></td>
                                </tr>
                                <tr>
                                    <td>Base Fare</td>
                                    <td class="right-text tf-cng"><i class="fa fa-inr"></i>&nbsp;<?php echo number_format($total_base_fare);?></td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="right-text tf-cng discount-value"><i class="fa fa-inr"></i>&nbsp;<span>0</span></td>
                                </tr>
                                <tr>
                                    <td>Convenience Charge <span class="glyphicon glyphicon-question-sign convenienceMsg searchHelp"></span></td>
                                    <td class="right-text tf-cng discount-value">&#x20B9; <?php echo $convenience_charge;?></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <?php if(isset($_SESSION['hasFareChanged']) && ($_SESSION['hasFareChanged'] == 1)):?>
                                <tr>
                                    <td></td>
                                    <td class="right-text tf-cng">
                                        <div class="strikeClass"><i class="fa fa-inr"></i>&nbsp;<strike class="oldTotalFare"><?php echo $old_total_field;?></strike></div>    
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Total Fare</td>
                                    <td class="right-text tf-cng"><div class="row totFare"><strike><i class="fa fa-inr"></i>&nbsp;<?php echo $total_fare_field;?></strike></div><div class="row finalFare"><i class="fa fa-inr"></i>&nbsp;<span><?php echo $total_fare_field;?></span></div></td>
                                </tr>
                                <tr class='discountMessageBlock center-align-text'>
                                    <td colspan="2"><small class="errorMessage" style="display:none;"></small><small class="successMessage" style="display:none;"></small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-10">
                    </div>
                    <div class="col-xs-14">
                        <div class="row">
                        </div>
                        <div class="row yheight pull-right">                            
                            <div class="col-xs-24 discount-area" >
                                <div class="input-group pull-right input-group-row">
                                    <input type="text" class=" dis-code form-control discount-btn" placeholder="Discount Code" />
                                    <span class="input-group-btn">
                                        <button type="button" class="apply-btn btn btn-change-grp">APPLY</button>
                                    </span>
                                </div>
                                <div class="cancel-btn-row" style="display:none;">
                                    <button type="button" id="discount_cancel_btn" class="btn btn-change btn-cancel-spl">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <!--div class="row zheight">
                           <button type="button" class="col-xs-16 pull-right bookingBtn applyDiscountCodeBtn bookingFormField"><span class="glyphicon glyphicon-circle-arrow-down"></span>&nbsp;APPLY</button> 
                        </div-->
                    </div>
                    <div class="col-xs-10">
                    </div>
                    <div class="col-xs-14">
                        <div class="row">
                            <div class="row xheight">
                                <div class="form-group pull-right yheight" style="margin-bottom:0px;">
                                    <label for="tnc" class="control-label termncond"><input type="checkbox" name="tnc"/> I accept the <a href="#" data-toggle="modal" data-target="#termsAndConditions">Terms and Conditions.</a></label>
                                </div>
                            </div>
                            <button type="button" class="col-xs-22 xheight bookingBtn applyDiscountCodeBtn activitiesCheckoutBtn bookingFormField pull-right" id="checkoutBtn"><span class="glyphicon glyphicon-circle-arrow-right"></span>&nbsp;CHECKOUT</button>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

jQuery.fn.ForceNumericOnly = function(){
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            if( $(this).val().length >= 17 && e.keyCode !== 8 ){
                return false;
            }
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (key == 8 || key == 9 || key == 13 || key == 46 || key == 110 || key == 190 || key == 107 || key == 109 || key == 219 || key == 221 || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
        });
    });
};

jQuery.fn.ForceAlphaOnly = function(){
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            if( $(this).val().length >= 80 && e.keyCode !== 8){
                return false;
            }
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (key == 8 || key == 9 || key == 13 || key == 46 || key == 190 || (key >= 65 && key <=90 ));
        });
    });
};


var pattern = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;

$(document).ready(function(){
    var container = document.getElementById("travellers-form");
    var adult_count = "<?php echo $adult;?>";
    var kid_count = "<?php echo $child;?>";
    var infant_count = "<?php echo $infant;?>";

    $('strike').hide();
    $('.oldTotalFare').show();

    <?php if( !$diff_mode_flag ):?>
    if( adult_count > 0 )
    {
        var i=1;
        $('#travellers-form').append('<div class="form-padding row"> <div class="col-lg-24"> <h4>Adult '+i+' </h4><span class="leadGuestLabel">(<svg height="8" width="8"><circle cx="4" cy="4" r="3" fill="#27ae60"/></svg>Lead Traveller)</span> </div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 control-label form-padding"> <select id="not_selected" class="selectpicker traveller_title form-control" name="title_lead"> <option>Title</option> <option>Master</option> <option>Miss</option> <option>Mr</option> <option>Mrs</option> </select> </div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 control-label form-padding "> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="first_name_lead" placeholder="First Name"> </div></div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 control-label form-padding"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="last_name_lead" placeholder="Last Name"> </div></div></div><div class="form-padding row"> <div class="col-lg-10 col-md-10 col-sm-9 col-xs-23 control-label form-padding"> <div class="form-group"> <input type="text" class="form-control" name="email_id_lead" placeholder="Email Id"> </div></div></div><div class="form-padding row"> <div class="form-group form-padding col-xs-2 col-sm-1 col-md-2 col-lg-2 control-label"> <input type="text" value="+91" class="small-input-prefix forceNumeric"/> </div><div class="form-group form-padding col-xs-24 col-sm-9 col-md-8 col-lg-8 control-label"> <input type="text" class="form-control forceNumeric mobileNumber" name="mobile_no_lead" placeholder="Phone Number"/> </div></div><div class="form-padding row passportContent"> <div class="col-lg-10 col-md-10 col-sm-9 col-xs-23 control-label form-padding"> <div class="form-group"> <input type="text" class="form-control" name="pass_number" placeholder="Passport Number"> </div></div><div class="col-lg-10 col-md-10 col-sm-9 col-xs-23 control-label form-padding"> <div class="inner-addon right-addon"><i class="glyphicon"></i> <div class="form-group"> <input name="pass_expiry" readonly id="expiry-date-'+i+'" class="form-control" type="text" placeholder="Passport Expiry Date"> </div></div></div><div class="col-lg-4 col-md-4 col-sm-6 col-xs-16 form-padding"></div></div>');
    }

    for (var i = 1 ; i < adult_count ; i++) 
    {
        var j = i+1;
        $('#travellers-form').append('<div class="form-padding row"> <div class="col-lg-24"> <h4>Adult '+j+'</h4> </div></div><div class="form-padding row"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select id="not_selected" class="selectpicker traveller_title form-control" name="title_a[]"> <option>Title</option> <option>Master</option> <option>Miss</option> <option>Mr</option> <option>Mrs</option> </select> </div><div class="form-group"> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 control-label form-padding"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="first_name_a[]" placeholder="First Name"> </div></div></div><div class="form-group"> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 control-label form-padding"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="last_name_a[]" placeholder="Last Name"> </div></div></div></div><div class="form-padding row passportContent"> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 control-label form-padding"> <div class="form-group"> <input type="text" class="form-control" name="pass_number_a[]" placeholder="Passport Number"> </div></div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 control-label form-padding"> <div class="inner-addon right-addon"><i class="glyphicon"></i> <div class="form-group"> <input name="pass_expiry_a[]" readonly id="expiry-adult-date-'+j+'" class="form-control" type="text" placeholder="Passport Expiry Date"> </div></div></div></div>');
    };    
    
    for(var i =0 ;i < kid_count ; i++)
    {
        var j = i+1;
        $('#travellers-form').append('<div class="form-padding row"> <div class="col-lg-24"> <h4>Child '+j+'</h4> </div></div><div class="form-padding row"> <div class="col-lg-3 col-md-4 col-sm-6 col-xs-16 form-padding"> <select id="not_selected" class="selectpicker traveller_title form-control" name="title_k[]"> <option>Title</option> <option>Master</option> <option>Miss</option> </select> </div><div class="col-lg-8 col-md-10 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="first_name_k[]" placeholder="First Name"> </div></div><div class="col-lg-8 col-md-10 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="last_name_k[]" placeholder="Last Name"> </div></div><div class="col-lg-4 col-md-4 col-sm-9 col-xs-23 form-padding control-label "> <div class="form-group "> <div class="inner-addon right-addon "><i class="glyphicon"></i> <input name="dob_k[]" id="kids-dob-'+j+'" class="form-control" type="text" readonly placeholder="Date Of Birth"> </div></div></div></div><div class="form-padding row passportContent "> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label "> <div class="form-group "> <input type="text" class="form-control" name="pass_number_k[]" placeholder="Passport Number "> </div></div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label "> <div class="form-group "> <div class="inner-addon right-addon "><i class="glyphicon "></i> <input name="pass_expiry_k[]" readonly id="expiry-kids-date-'+j+'" class="form-control " type="text " placeholder="Passport Expiry Date "> </div></div></div></div>');
    };

    for(var i =0 ;i < infant_count ; i++)
    {
        var j = i+1;
        $('#travellers-form').append('<div class="form-padding row"> <div class="col-lg-24"> <h4>Infant '+j+'</h4> </div></div><div class="form-padding row"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select id="not_selected" class="selectpicker traveller_title form-control" name="title_i[]"> <option>Title</option> <option>Master</option> <option>Miss</option> </select> </div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="first_name_i[]" placeholder="First Name"> </div></div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="last_name_i[]" placeholder="Last Name"> </div></div><div class="col-lg-4 col-md-4 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <div class="inner-addon right-addon"><i class="glyphicon "></i> <input name="dob_i[]" id="infant-dob-'+j+'" class="form-control" type="text" readonly placeholder="Date Of Birth"> </div></div></div></div><div class="form-padding row passportContent"> <div class="col-lg-8 col-md-10 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control" name="pass_number_i[]" placeholder="Passport Number"> </div></div><div class="col-lg-8 col-md-10 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <div class="inner-addon right-addon"><i class="glyphicon"></i> <input name="pass_expiry_i[]" readonly id="expiry-infant-date-'+j+'" class="form-control" type="text" placeholder="Passport Expiry Date"> </div></div></div></div>');
    };

    $('#travellers-form').append('<div class="form-padding row" style="width:20%;margin:0% 18.3% 10%;"><div class="col-lg-24 remove-padding"><button id="form-submit-button" type="button" style="display:none;"><span class="glyphicon glyphicon-search"></span> CHECKOUT</button></div></div>');

    $.datepicker.setDefaults({dateFormat: "yy-mm-dd"});
    
    /***select or die*******/
    $('select').selectOrDie({
        placeholderOption: true,
        onChange: function(){
            this.id = 'selected';
            $(this).parent().siblings('small.help-block').hide();
            $(this).parent('span').css('border-color', '#27ae60');
        },
    });

    //popover

    //internation flights
    var international_var = <?php echo $_SESSION['IsDomestic'];?>;

    if( international_var ){
        $('.passportContent').hide();
    }
    //internation flights end
    <?php else:?>
        var total_passenger = <?php echo $_SESSION['details']['total_count'];?>;

        for (var i = 0 ; i < total_passenger ; i++) 
        {
            var j = i + 1;
            $('#travellers-form').append(' <div class="form-padding row"> <div class="col-lg-24"> <h4> Passenger '+j+' </h4> </div></div><div class="form-padding row"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select class="selectpicker form-control title" name="Title'+i+'"> <option>Title</option> <option> Mr </option> <option> Ms </option> <option> Mrs </option> </select> </div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding"> <input type="text" class="form-control" name="fname'+i+'" placeholder="First Name"> </div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding"> <input type="text" class="form-control" name="lname'+i+'" placeholder="Last Name"> </div></div><div class="form-padding row"> <div class="col-lg-3 col-md-3 col-sm-9 col-xs-23 form-padding"> <select class="selectpicker form-control gender" name="sex'+i+'"> <option>Gender</option> <option> Male </option> <option> Female </option> </select> </div><div class="col-lg-4 col-md-4 col-sm-9 col-xs-23 form-padding"> <input type="text" class="form-control" name="dob'+i+'" id="age_dob'+i+'" placeholder="DOB"> <input type="text" class="form-control" name="age'+i+'" id="age_dob_hid'+i+'" style="display:none;"> </div></div>');
        };

        $('#travellers-form').append('<div class="col-lg-10 col-md-10 col-sm-9 col-xs-23 form-padding"> </div>');
        $('#travellers-form').append('<div class="form-padding row"> <div class="col-lg-24"> <h4> Contact Details </h4> </div></div><div class="form-padding row"> <div class="col-lg-8 col-md-12 col-sm-12 col-xs-23 form-padding"> <input type="text" class="form-control forceNumeric" name="mobile" placeholder="Mobile Number"> </div><div class="col-lg-8 col-md-12 col-sm-12 col-xs-23 form-padding"> <input type="text" class="form-control" name="email_id" placeholder="Email Id"> </div></div><div class="form-padding row"> <div class="col-lg-16 col-md-16 col-sm-24 col-xs-23 form-padding"> <textarea rows="4" cols="25" type="text" class="form-control form-padding" name="addressPickup" placeholder="Pickup Address"></textarea> <textarea rows="4" cols="25" type="text" class="form-control form-padding" name="addressDrop" placeholder="Drop Address"></textarea> </div></div><div class="form-padding row"> <div class="col-lg-4 col-md-12 col-sm-12 col-xs-23 form-padding"> <select name="id_proof" id="proof" class="form-control idproof" type="text" placeholder="Age"> <option> Pan Card </option> <option> Driving Licence </option> <option> Voting Card </option> <option> Adhar Card </option> </select> </div><div class="col-lg-4 col-md-12 col-sm-12 col-xs-23 form-padding"> <input type="text" class="form-control" name="id_no" placeholder="Id Number"> </div></div>');
        $('#travellers-form').append('<div class="form-padding row" style="width:20%;margin:0% 18.3% 10%;"><div class="col-lg-24 remove-padding"><button id="form-submit-button" type="submit" style="display:none;"><span class="glyphicon glyphicon-search"></span> CHECKOUT</button></div></div>');

        $.datepicker.setDefaults({dateFormat: "yy-mm-dd"});

        <?php for ( $i = 0 ; $i < $_SESSION['details']['total_count'] ; $i++): ?>
            $('#age_dob<?php echo $i;?>').datepicker({
                changeYear: true,
                changeMonth: true,
                maxDate: 0,
                onSelect: function(){
                    var sm = $('#travellers-form').data('bootstrapValidator');
                    sm.updateStatus($(this), 'VALID');
                    var date1 = $(this).datepicker('getDate');
                    var date2 = new Date();
                    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                    var age_dob = Math.ceil(timeDiff / (1000 * 3600 * 24 * 365.242199)); 
                    if( age_dob > 12 ){
                        $('#age_dob_hid<?php echo $i;?>').val(age_dob+'-adult');
                    }else if( age_dob <= 12 && age_dob > 2 ){
                        $('#age_dob_hid<?php echo $i;?>').val(age_dob+'-child');
                    }else if( age_dob <= 2 ){
                        $('#age_dob_hid<?php echo $i;?>').val(age_dob+'-infant');
                    }else{
                        alert('Error, Try again.');
                    }
                }
            });

        $('select').selectOrDie({
            placeholderOption: true,
            onChange: function(){
                this.id = 'selected';
                $(this).parent().siblings('small.help-block').hide();
                $(this).parent('span').css('border-color', '#27ae60');
            },
        });

        <?php endfor;?>

        /***select or die*******/

        $('.age').selectOrDie({
            placeholder: 'Age'
        });
        $('.idproof').selectOrDie({
            placeholder: 'Id_Proof'
        });
        $('.gender').selectOrDie({
            placeholder: 'Gender'
        });
        //validation form

        //internation flights
        var international_var = 1;

        if( international_var ){
            $('.passportContent').hide();
        }
        //internation flights end
    <?php endif;?>

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

    //close popover on body click.

    $('body').on('click', function (e) {
        //only buttons
        if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('.popover.in').length === 0) { 
            $('[data-toggle="popover"]').popover('hide');
        }
        //buttons and icons within buttons
        /*
        if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('[data-toggle="popover"]').length === 0
            && $(e.target).parents('.popover.in').length === 0) { 
            $('[data-toggle="popover"]').popover('hide');
        }
        */
    });

    //autoNumeric
    $('.finalFare span, .discount-value span').autoNumeric({
        aSep: ',',
        dGroup: 2
    })

    //validation form

    $('#travellers-form').bootstrapValidator({
            live: 'disabled',
            fields: {
                'title_lead': {
                    validators: {
                        notEmpty: {
                            message: 'Title is required'
                        }
                    }
                },
                'first_name_lead': {
                    validators: {
                        notEmpty: {
                            message: 'First Name is required'
                        }
                    }
                },
                'last_name_lead': {
                    validators: {
                        notEmpty: {
                            message: 'Last Name is required'
                        }
                    }
                },
                'email_id_lead': {
                    validators: {
                        notEmpty: {
                            message: 'E-Mail is required'
                        },
                        emailAddress: {
                            message: 'A valid E-Mail address is required'
                        }
                    }
                },
                'mobile_no_lead': {
                    validators: {
                        notEmpty: {
                            message: 'Phone Number is required'
                        },
                        numeric: {
                            message: 'A Valid Phone Number is required'
                        }
                    }
                },
                'title_a[]': {
                    validators: {
                        notEmpty: {
                            message: 'Title is required'
                        }
                    }
                },
                'first_name_a[]': {
                    validators: {
                        notEmpty: {
                            message: 'First Name is required'
                        },
                        stringLength: {
                            max: 50,
                            message: 'First Name must be less than 50 characters'
                        }
                    }
                },
                'last_name_a[]': {
                    validators: {
                        notEmpty: {
                            message: 'Last Name is required'
                        },
                        stringLength: {
                            max: 50,
                            message: 'Last Name must be less than 50 characters'
                        }
                    }
                },
                'title_k[]': {
                    validators: {
                        notEmpty: {
                            message: 'Title is required'
                        }
                    }
                },
                'first_name_k[]': {
                    validators: {
                        notEmpty: {
                            message: 'First Name is required'
                        },
                        stringLength: {
                            max: 50,
                            message: 'First Name must be less than 50 characters'
                        }
                    }
                },
                'last_name_k[]': {
                    validators: {
                        notEmpty: {
                            message: 'Last Name is required'
                        },
                        stringLength: {
                            max: 50,
                            message: 'Last Name must be less than 50 characters'
                        }
                    }
                },
                'dob_k[]': {
                    validators: {
                        notEmpty: {
                            message: 'Date of Birth is required'
                        }
                    }
                },
                'title_i[]': {
                    validators: {
                        notEmpty: {
                            message: 'Title is Required'
                        }
                    }
                },
                'first_name_i[]': {
                    validators: {
                        notEmpty: {
                            message: 'First Name is required'
                        },
                        stringLength: {
                            max: 50,
                            message: 'First Name must be less than 50 characters'
                        }
                    }
                },
                'last_name_i[]': {
                    validators: {
                        notEmpty: {
                            message: 'Last Name is required'
                        },
                        stringLength: {
                            max: 50,
                            message: 'Last Name must be less than 50 characters'
                        }
                    }
                },
                'dob_i[]': {
                    validators: {
                        notEmpty: {
                            message: 'Date of Birth is required'
                        }
                    }
                },
                'tnc': {
                    validators: {
                        choice: {
                            min: 1,
                            message: 'Please accept the Terms and Conditions before checkout.'
                        }
                    }
                },
                'pass_expiry[]': {
                    validators: {
                        notEmpty: {
                            message: 'Passport Expiry Date is required'
                        }
                    }
                },
                'pass_number[]': {
                    validators: {
                        notEmpty: {
                            message: 'Passport Number is required'
                        }
                    }
                },
            }
        });

$('#form-1').bootstrapValidator({
    live: 'disabled',
        fields: {
            title_user: {
                validators: {
                    notEmpty: {
                        message: 'Title is required'
                    }
                }
            },
            firstName: {
                validators: {
                    notEmpty: {
                        message: 'First Name is required'
                    }
                }
            },
            lastName: {
                validators: {
                    notEmpty: {
                        message: 'Last Name is required'
                    }
                }
            },
            gender: {
                choice: {
                        min: 1,
                        max: 1,
                        message: 'Please choose 1 - 2 languages you can speak'
                },
                validators: {
                    notEmpty: {
                        message: 'Gender is required'
                    }
                }
            },
            dob: {
                validators: {
                    notEmpty: {
                        message: 'Date of Birth is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'Email is required'
                    },
                    emailAddress:{
                        message: 'Please Enter a Valid Email id'
                    }
                }
            },
            phone_no: {
                validators: {
                    notEmpty: {
                        message: 'Phone number is required.'
                    },
                    numeric: {
                        message: 'Please enter a Valid Phone number.'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Password is required'
                    },
                    identical: {
                        field: 'confirm_password',
                        message: 'Password Mismatch.'
                    },
                    stringLength: {
                        min: 4,
                        message: 'Password must be a minimum of 4 Characters long.'
                    }
                },
            },
            confirm_password: {
                validators: {
                    notEmpty: {
                        message: 'Confirm Password is required'
                    },
                    stringLength: {
                        min: 4,
                        message: 'Password must be a minimum of 4 Characters long.'
                    },
                    identical: {
                        field: 'password',
                        message: 'Password Mismatch.'
                    },
                },
            }
        }
    });

    //allow only numeric keys for certain fields
    $('.forceNumeric').ForceNumericOnly();
    //allow only numeric keys for certain fields end

    //allow only alphabetic keys begin
    $('.forceAlpha').ForceAlphaOnly();
    //allow only alphabetic keys end

    $(document).ready(function(){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('login/login_status'); ?>",
        })
        .done(function(data){
            var lgd_in = $.parseJSON(data);
            if(lgd_in.value !== 1){
                $('#login_modal').modal({backdrop: 'static', keyboard: false});
                $('#login_modal').modal('show');
            }else{
                $('#error_text').hide();
            }
        });
    });

    var modalWidth = 0;
    var modalHeight = 0;

    $('#login_modal').on('shown.bs.modal', function(){
        modalWidth = ($('#login_modal .modal-content .modal-body').width())/2;
        modalHeight = ($('#login_modal .modal-content .modal-body').height())/2;
        $('.orIcon').css({
            'top': (modalHeight - 25),
            'left': (modalWidth - 17.5)
        });
    });

    $("#checkoutBtn").click(function(){
        $('#form-submit-button').click();
    });

    $('#travellers-form').on('click', '#form-submit-button', function(e){
        e.preventDefault();
        var not_selected_flag = 0;
        var all_selects = $(document).find('select.traveller_title');
        $.each( all_selects, function(i, val){
            console.log(val.id);
            if( val.id === 'not_selected' ){
                not_selected_flag = 1;
            }
        });

        if( not_selected_flag ){
            $.each( all_selects, function(i, val){
                if( val.id === 'not_selected' ){
                    $(val).parent().siblings('small.help-block').show();
                    $(this).parent('span').css('border-color', '#f00');
                    $('#travellers-form').data('bootstrapValidator').validate();
                }
            });
        }else{
           $('#travellers-form').data('bootstrapValidator').validate();
            if( $('#travellers-form').data('bootstrapValidator').isValid() ){
                $('#travellers-form').trigger('travelFormSubmitted');
            }else{
                return false;
            }
        }
    });

    $('#travellers-form').on('travelFormSubmitted', function(){
        var mobileNumber = $('.mobileNumber').val();
        $('.mobileNumber').val($('.small-input-prefix').val() +""+ mobileNumber);
        if( $('input[name=tnc]').is(":checked") ){
            this.submit();
        }else{
            $('input[name=tnc]').parent('label').css('color', '#f00');
            $('.discountMessageBlock .errorMessage').html('Please accept the Terms and Conditions before checkout').fadeIn(200);
            setTimeout(function(){
                $('input[name=tnc]').parent('label').css('color', '#000');
                $('.discountMessageBlock .errorMessage').fadeOut(200);
            }, 3000);
        }
    });

    $.datepicker.setDefaults({
            dateFormat: "dd-mm-yy"
    });

    $('#expiry-date-1').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: 'c:c+100',
    });

    for(var w = 2 ; w <= kid_count+1 ; w++) { 
        $('#expiry-adult-date-'+w).datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: 'c:c+100',
        });
    };

    for(var x = 1 ; x <= kid_count ; x++) { 
        $('#kids-dob-'+x).datepicker({
            minDate:'-12Y', 
            maxDate:'0',
            changeMonth: true,
            changeYear: true,
            yearRange: 'c-12:c',
        });
    };

    for(var y = 1 ; y <= infant_count ; y++) {
        $('#infant-dob-'+y).datepicker({
            minDate:'-12Y', 
            maxDate:'0',
            changeMonth: true,
            changeYear: true,
            yearRange: 'c-2:c',
        });
    };

    for(var z1 = 1 ; z1 <= kid_count ; z1++) {
        $('#expiry-kids-date-'+z1).datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: 'c:c+100',
        });
    };

    for(var z2 = 1 ; z2 <= infant_count ; z2++) {
        $('#expiry-infant-date-'+z2).datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: 'c:c+100',
        });
    };

// convenience msg
        $('.convenienceMsg').on('mouseenter', function(){
            $(this).tooltip({
                placement: 'top',
                trigger: 'hover',
                title: "<?php echo $convenience_charge_msg;?>",
            });
            $(this).tooltip('show');
        });
// convenience msg end

    // login group
        var login = new Object;
        var user = new Object;

        $('#guest_reg_submit').on('click', function(){
            login.guest_email = $('input[name=guest_email]').val();

            if((login.guest_email == null || login.guest_email == "")){
                $('input[name=guest_email]').animate({'border-color': '#f00'}, 100);
                $('.loginMessageBlock .errorMessage').html('Please enter an E-Mail address first.').fadeIn(200);
                setTimeout(function(){
                    $('input[name=guest_email]').animate({'border-color': '#27ae60'}, 100);
                    $('.loginMessageBlock .errorMessage').fadeOut(200);
                }, 3000);
                return false;
            }else{
                if( !pattern.test($('input[name=guest_email]').val()) ){
                    $('.loginMessageBlock .errorMessage').html('Please enter a Valid E-Mail address.').fadeIn(200);
                    $('input[name=guest_email]').animate({'border-color': '#f00'}, 100);
                    setTimeout(function(){
                        $('.loginMessageBlock .errorMessage').fadeOut(200);
                        $('input[name=guest_email]').animate({'border-color': '#27ae60'}, 100);
                    }, 3000);
                    setTimeout(function(){$('input[name=guest_email]').popover().click();}, 5000);
                    return false;
                }else{
                    $.post($("#guest_register").attr('action'), login, function( retObj ){
                        retObj = $.parseJSON(retObj);
                        if( retObj ){
                            $('.loginMessageBlock .successMessage').html("You have been logged in successfully.").fadeIn(200);
                            setTimeout(function(){
                                $('.loginMessageBlock .successMessage').fadeOut(200);
                                $('#login_modal').modal('hide');
                            }, 3000);
                            var baseUrl = "<?php echo base_url('index.php/user')?>";
                            var logoutUrl = "<?php echo base_url('index.php/flights')?>";
                            $('.ticket_header').html('<li class="mytcktlink"><a href="<?php echo site_url("api/flights/guest_ticket") ?>">My Tickets</a></li><li class="userWelcome userLink"><a href="javascript:void(0);"><span class="glyphicon glyphicon-user"></span>&nbsp&nbspHi Guest</a></li><li class="userWelcome"><a href="'+logoutUrl+'"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbspLogout</a></li>');
                            $('.loginLink').hide();  
                        } else {
                            $('.loginMessageBlock .errorMessage').html('You have already registered with this E-Mail ID. Please sign up to Farebucket or use a different E-Mail ID.').fadeIn(200);
                            setTimeout(function(){
                                $('.loginMessageBlock .errorMessage').fadeOut(200);
                            }, 3000);
                            return false;
                        }
                    },'json');
                }
            }
        });


        $('#login_user_submit').on('click', function(){
            user.email = $('input[name=email]').val();
            user.password = $('input[name=password]').val();
            
            if((user.email == null || user.email == "")&&(user.password == null || user.password == "")){
                $('input[name=email]').animate({'border-color': '#f00'}, 100);
                $('input[name=password]').animate({'border-color': '#f00'}, 100);
                $('.loginMessageBlock .errorMessage').html('Please enter an E-Mail address and Password first.').fadeIn(200);
                setTimeout(function(){
                    $('input[name=email]').animate({'border-color': '#27ae60'}, 100);
                    $('input[name=password]').animate({'border-color': '#27ae60'}, 100);
                    $('.loginMessageBlock .errorMessage').fadeOut(200);
                }, 3000);
                return false;
            }

            else{
                $.post($("#login_user").attr('action'), user, function( retObj ){
                    if( retObj ){
                        if(retObj['status'] === "success")
                        {
                            $('.loginMessageBlock .successMessage').html('You have logged in successfully!').fadeIn(200);
                            setTimeout(function(){
                                $('.loginMessageBlock .successMessage').fadeOut(200);
                                var baseUrl = "<?php echo base_url('index.php/user')?>";
                                var logoutUrl = "<?php echo base_url('index.php/login/logout_user')?>";
                                $('#login_modal').modal('hide');
                                $('.ticket_header').html('<li class="mytcktlink"><a href="<?php echo site_url("api/flights/guest_ticket") ?>">My Tickets</a></li><li class="userWelcome userLink"><a href="'+baseUrl+'"><span class="glyphicon glyphicon-user"></span>&nbsp&nbsp'+retObj["first_name"]+'</a></li><li class="userWelcome"><a href="'+logoutUrl+'"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbspLogout</a></li>');
                                $('.loginLink').hide();
                            }, 3000);
                        }
                        else
                        {
                            $('.loginMessageBlock .errorMessage').html('Incorrect Credentials. Please try again.').fadeIn(200);
                            setTimeout(function(){
                                $('.loginMessageBlock .errorMessage').fadeOut(200);
                            }, 3000);
                            $('#email').val("");
                            $('#pass').val("");
                        }
                    } else {
                        $('.loginMessageBlock .errorMessage').html('Your user name or password seems to be incorrect. Please check your details').fadeIn(200);
                        setTimeout(function(){
                            $('.loginMessageBlock .errorMessage').fadeOut(200);
                        }, 3000);
                        return false;
                    }
                },'json');
            }
        });

        $('#login_user').bootstrapValidator({
                message: 'This value is not valid',
                live: 'disabled',
                feedbackIcons: {
                  valid: 'glyphicon glyphicon-ok',
                  invalid: 'glyphicon glyphicon-remove',
                  validating: 'glyphicon glyphicon-refresh'
                },
            fields: {
                guest_email: {
                    validators: {
                        notEmpty: {
                            message: 'E-Mail is required'
                        }
                    }
                }
            }
        });

    // login group end

    //discount section

    var discountClick = 0;
    var total_fare = parseInt("<?php echo $total_fare_field;?>");
    var discounted_val = 0;

    $('.finalFare span, .TotFare1 span').autoNumeric({
        aSep: ',',
        dGroup: 2,
    });

    $(".apply-btn").click(function(){

        var discountCode = $('.dis-code').val();
        var discountModule = "flights";

        if( discountCode === "" || discountCode === null ){
            $('.discountMessageBlock .errorMessage').html('Please enter a valid Code').fadeIn(200);
            setTimeout(function(){
                $('.discountMessageBlock .errorMessage').fadeOut(200);
            }, 3000);
        }else{
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('flights/apply_discount_code');?>",
                data: { discountCode: discountCode, discountModule: discountModule }
            })
            .done(function(data){
                if(data === "Failure"){
                    $('.discountMessageBlock .errorMessage').html('The discount code you have entered is Invalid').fadeIn(200);
                    setTimeout(function(){
                        $('.discountMessageBlock .errorMessage').fadeOut(200);
                    }, 3000);
                }else{
                    $('.cancel-btn-row').show();
                    $('.input-group-row').hide();
                    discountClick++;
                    var discount_details = $.parseJSON(data);
                    if( discount_details.discount_code_type === "percent" ){
                        var value = parseInt(discount_details.discount_code_value);
                        per_cent = value/100;
                        discounted_val = total_fare-(total_fare*per_cent);
                        $('.discount-value span').autoNumeric('set', (total_fare*per_cent));
                        $('strike').slideDown();
                        $('.finalFare span').autoNumeric('set', discounted_val);
                        $('.discountMessageBlock .successMessage').html('discount has been applied').fadeIn(200);
                        setTimeout(function(){
                            $('.discountMessageBlock .successMessage').fadeOut(200);
                        }, 3000);
                    }else{
                        var value = parseInt(discount_details.discount_code_value);
                        $('.discount-value span').autoNumeric('set', value);
                        discounted_val = total_fare-value;
                        $('strike').slideDown();
                        $('.finalFare span').autoNumeric('set', discounted_val);
                        $('.discountMessageBlock .successMessage').html('discount has been applied').fadeIn(200);
                        setTimeout(function(){
                            $('.discountMessageBlock .successMessage').fadeOut(200);
                        }, 3000);
                    }
                }
            });
        }
    });

    $('#discount_cancel_btn').on('click', function(){

        discounted_val = total_fare;
        $('.finalFare span').autoNumeric('set', total_fare);
        $('strike').hide();

        if( discountClick ){
            if(total_fare === discounted_val){
                $('.discount-value span').html('0');
                $('.discountMessageBlock .successMessage').html('Discount Cancelled').fadeIn(200);
                setTimeout(function(){
                    $('.discountMessageBlock .successMessage').fadeOut(200);
                }, 3000);
            }else{
                $('.discountMessageBlock .errorMessage').html('Discount already applied').fadeIn(200);
                setTimeout(function(){
                    $('.discountMessageBlock .errorMessage').fadeOut(200);
                }, 3000);
            }
        }

        $('.cancel-btn-row').hide();
        $('.input-group-row').show();
    });

//tooltip code
    var session_vars = <?php if( isset($_SESSION['details']) ){ echo json_encode($_SESSION['details']); }else{echo "";}?>;

    $('#origin1').on('mouseover', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: session_vars.from[0]
        });
        $(this).tooltip('show');
    });

    $('#destination1').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: session_vars.to[0]
        });
        $(this).tooltip('show');
    });

    $('#origin2').on('mouseover', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: session_vars.from[1]
        });
        $(this).tooltip('show');
    });

    $('#destination2').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: session_vars.to[1]
        });
        $(this).tooltip('show');
    });

    $('#origin3').on('mouseover', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: session_vars.from[2]
        });
        $(this).tooltip('show');
    });

    $('#destination3').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: session_vars.to[2]
        });
        $(this).tooltip('show');
    });

    $('#origin4').on('mouseover', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: session_vars.from[3]
        });
        $(this).tooltip('show');
    });

    $('#destination4').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: session_vars.to[3]
        });
        $(this).tooltip('show');
    });

//end tooltip code

// register modal
    $('#register').on('click', function(e){
        e.preventDefault();
        $('#login_modal').modal('hide');
        $('#register_modal').modal({backdrop: 'static', keyboard: false});
        $('#register_modal').modal('show');
    });
// register modal end

//forgot password code

    $('#forgot_pass').on('click', function( e ){
        e.preventDefault();
        $('#login_modal').modal('hide');
        $('#forgot_pass_modal').modal({backdrop: 'static', keyboard: false});
        $('#forgot_pass_modal').modal('show');

        $('#chk_email_btn').on('click', function(){

            if( $('input#email_fp').val() === '' || $('input#email_fp').val() === null ){
                $('#email_fp').popover({
                    content: 'Please enter a valid E-Mail ID first.',
                    container: 'body',
                    placement: 'right',
                    trigger: 'click',
                    delay: {show:500, hide:100}
                }).click();
                setTimeout(function(){$('#email_fp').popover().click();}, 5000);
                return false;
            }
            if( !pattern.test($('input#email_fp').val()) ){
                $('#email_fp').popover({
                    content: 'Please enter a valid E-Mail ID first.',
                    container: 'body',
                    placement: 'right',
                    trigger: 'click'
                }).click();
                setTimeout(function(){$('#email_fp').popover().click();}, 5000);
                return false;
            }
            var chk = $('input#email_fp').val();
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('login/check_user');?>",
                data: { email : chk }
            })
            .done(function( retDat ){
                retDat = $.parseJSON(retDat);

                if( retDat === "An E-Mail with a copy of your password has been sent to your registered email id. Please login to retrieve." ){
                    $('#email_fp').popover({
                        content: retDat,
                        container: 'body',
                        placement: 'right',
                        trigger: 'click',
                    }).click(); 
                    setTimeout( function(){
                        $('#email_fp').popover().click();
                        $('#forgot_pass_modal').modal('hide');
                        $('#login_modal').modal('show');
                    }, 3000);

                }else{
                    $('#email_fp').popover({
                        content: retDat,
                        container: 'body',
                        placement: 'right',
                        trigger: 'click',
                    }).click();
                    setTimeout(function(){$('#email_fp').popover().click();}, 5000);
                }

            });
        });
    });

    $('#cancel_email_btn').on('click', function(){
        $('#forgot_pass_modal').modal('hide');
        $('#login_modal').modal('show');
    }); 

    $('#cancel_register_btn').on('click', function(){
        $('#register_modal').modal('hide');
        $('#login_modal').modal('show');
        $('#form-1').data('bootstrapValidator').resetForm();
    }); 

    $.datepicker.setDefaults({
        dateFormat: "dd-mm-yy"
    });

    $('#date-1').datepicker({
        maxDate: '-18Y',
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:-18',
        onSelect: function(){
            var sm = $('#form-1').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
        }
    });

    $('button.reg-submit').on('click', function(e){
        e.preventDefault();
        $('#form-1').data('bootstrapValidator').validate();
        if( $('#form-1').data('bootstrapValidator').isValid() ){
            var fields = {};
            $("#form-1").find(":input").each(function() {
                // The selector will match buttons; if you want to filter
                // them out, check `this.tagName` and `this.type`; see
                // below
                fields[this.name] = $(this).val();
            });
            fields['gender'] = $("input[type='radio']:checked").val();
            fields['isAjax'] = 1;
            var obj = {data: fields};
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('login/register_user');?>",
                data: obj.data
            })
            .done(function(retData){
                var retMsg = $.parseJSON(retData);
                if( retMsg.message === "You have been registered successfully." ){
                    $('.registerMessageBlock .successMessage').html(retMsg.message).fadeIn(200);
                    setTimeout(function(){
                        $('.registerMessageBlock .successMessage').fadeOut(200).html('');
                    }, 3000);
                    window.location.reload();
                }else{
                    $('.registerMessageBlock .errorMessage').html(retMsg.message).fadeIn(200);
                    setTimeout(function(){
                        $('.registerMessageBlock .errorMessage').fadeOut(200).html('');
                    }, 3000);
                    $('#form-1').data('bootstrapValidator').resetForm();
                    return false;
                }
            });
        }else{
            return false;
        }
    });

    //adding dynamic fields into bv for validation

    var travellerForm = $('#travellers-form');
    var travellerFormBv = travellerForm.data('bootstrapValidator');

    var fields = {};
    travellerForm.find(":input:visible").each(function() {
        fields[this.name] = this.placeholder;
    });


// this part checks the placeholder of a field and add various validation options eg: is e-mail validation for e-mail etc
    $.each(fields, function(key, val){
        switch( val ){
            case 'First Name':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "The Name is required."
                        }
                    }
                });
            break;
            case 'Last Name':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "The Name is required."
                        }
                    }
                });
            break;
            case 'DOB':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "The Age is required."
                        },
                        date: {
                            format: 'DD-MM-YYYY',
                            message: 'The Date is not a valid'
                        }
                    }
                });
            break;
            case 'Mobile Number':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "The Mobile Number is required."
                        },
                        numeric:{
                            message: "Please Enter a Valid Mobile Number."
                        }
                    }
                });
            break;
            case 'Email Id':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "The Email Id is required."
                        },
                        emailAddress:{
                            message: "Please Enter a Valid Email Id."
                        }
                    }
                });
            break;
            case 'Drop Address':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "The Drop Address is required."
                        }
                    }
                });
            break;
            case 'Pickup Address':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "The Pickup Address is required."
                        }
                    }
                });
            break;
            case 'Id Number':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "The Id Number is required."
                        }
                    }
                });
            break;
            default :
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "The Field is required."
                        }
                    }
                });            
        }
    });

    <?php if( !$cab_present ):?>
        $('textarea[name=addressDrop]').hide();
        $('textarea[name=addressPickup]').attr('placeholder','Address');
        travellerFormBv.removeField('addressPickup');
        $('textarea[name=addressPickup]').after('<div class="h6">*Address is optional</div>');
    <?php endif;?>

});
</script>