<script type="text/javascript"> 

function stopRKey(evt) { 
    var evt = (evt) ? evt : ((event) ? event : null); 
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
    if ((evt.keyCode == 13 && evt.target.nodeName !== "TEXTAREA" )) {return false;}
} 

document.onkeypress = stopRKey; 

</script>
<?php 
    $cCharge = $data['convenience_charge'];
    $convenience_charge_msg = $data['convenience_charge_msg'];
    @session_start();
    $fares_base = array();
    $fares_total = array();
    $fares_service = array();

    $data = $_GET;
    $_SESSION['info'] = $_GET;
    $data['seat_details'] = $_SESSION['seat_details'];
    $data['journey_details'] = $_SESSION['journey_details'];

    foreach ($data['chkchk'] as $ch) {
        foreach ($data['seat_details']['seats'] as $s) {
            foreach ($s as $s_key => $s_val) {
                if( $s_key == 'name' && $s_val == $ch){
                    $fares_base[] = $s['baseFare'];
                    $fares_service[] = $s['serviceTaxAbsolute'];
                    $fares_total[] = $s['fare'];
					if( $s['ladiesSeat'] == 'true' ){
						$seat[] = 'IsLadies';
					}
					else{
						$seat[] = 'No';
					}
                }
            }
        }
    }

    $total_bus_fare = array_sum($fares_total);
    $original_total_fare = $total_bus_fare;
    $convenience_charge = ($cCharge/100)*$total_bus_fare;
    $total_bus_fare = $convenience_charge + $total_bus_fare;
    $_SESSION['bus_db_fare_params']['bus_offered_price'] = 0;
    $_SESSION['bus_db_fare_params']['bus_published_price'] = $original_total_fare;
    $_SESSION['bus_db_fare_params']['bus_convenience_charge'] = $total_bus_fare - $original_total_fare;
    $_SESSION['bus_db_fare_params']["bus_total_Fare"] = $total_bus_fare;
?>
<style>
	.payu-image{
        width:15%;
        margin-top: 75px;
        margin-right: 45px;
    }
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
    .fl_info{
        margin-top: 0;
    }
    .travel-text{
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
        padding-right: 7.5px;
        padding-left: 7.5px;
    }

    .activityLocaltionName{
        margin-top: -5px;
        font-size: 11px;
    }
    .sod_select{
        margin-top: 0;
    }

    small.help-block{
        color: #ff0000;
    }

    .busPopPaddingTop{
        padding: 0;
        padding-top: 3px;
        font-size: 12px;
    }
    .sod_select{
        text-transform: none;
    }

    .totalNote{
        color: #27ae60;
        transition: 0.2s all;
    }

    .totalNote:hover{
        cursor: pointer;
        transition: 0.2s all;
        color: #000;
    }

</style>
<!--modal screen-->
<div class="modal fade" id="login_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h4>Please Login or Register to Continue</h4></center>
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
            </div>
            <div class="modal-body">
                <div class="col-xs-24 userRegisterContainer">
                    <div class="row">
                        <form id="form-1" class="col-xs-24 userRegistrationForm">
                            <div class="col-xs-9 col-xs-offset-2 title_user remove-padding">
                                <div class="form-group title_error">
                                    <select class="selectpicker control-label add-on-table" name="title_user"><option value="" disabled selected>Title</option><option>Ms</option><option>Mr</option><option>Mrs</option></select>
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
                                            <input class="form-control" type="radio" name="gender" value="male" placeholder="Gender"/> Male
                                        </label>
                                        <label class="btn btn-change-radio">
                                            <input class="form-control" type="radio" name="gender" value="female" placeholder="Gender"/> Female
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
    <div id="popoverHiddenContent" style="display: none">
        <div class="row center-align-text fl_row">
            <!-- first flight -->
            <div class="col-lg-8 left-text">
                <div class="row center-align-text" style="font-size:13px;"><?php echo $data['journey_details']['boardingpnts']['travels']?></div>
                <div class="row center-align-text" style="font-size:10px;"><?php echo $data['journey_details']['boardingpnts']['busType']?></div>
            </div>
            <div class="col-lg-10 remove-padding">
                <div class="row travel-text">
                    <?php
                        $oneDay=24*60;
                        $noOfDays = floor($data['journey_details']['boardingpnts']['departureTime'] / $oneDay);
                        $time = ($data['journey_details']['boardingpnts']['departureTime']) % $oneDay;
                        $hours = floor($time/60);
                        $minutes = floor($time%60);
                        if($hours < 10)
                            $hours = '0'.$hours;
                        if($minutes < 10)
                            $minutes = '0'.$minutes;
                    ?>
                    <div class="col-lg-11 remove-padding" id="originPop"><?php echo $hours.':'.$minutes;?></div>
                    <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                    <?php
                        $oneDay=24*60;
                        $noOfDays = floor($data['journey_details']['boardingpnts']['arrivalTime'] / $oneDay);
                        $time = ($data['journey_details']['boardingpnts']['arrivalTime']) % $oneDay;
                        $hours = floor($time/60);
                        $minutes = floor($time%60);
                        if($hours < 10)
                            $hours = '0'.$hours;
                        if($minutes < 10)
                            $minutes = '0'.$minutes;
                    ?>
                    <div class="col-lg-11 remove-padding" id="destinationPop"><?php echo $hours.':'.$minutes;?></div>
                </div>
            </div>
            <div class="col-lg-6 pull-right">
                <div class="row center-align-text" style="font-size:18px;"> &#x20B9;<span style="font-family:oswald;font-size:18px;"><?php echo number_format($total_bus_fare, 2);?></span></div>
            </div>
        </div>
    </div>
    <div id="popoverHiddenTitle" style="display: none">
        <div class="row">
            <div class="col-lg-12 pull-left center-align-text">
                <div class="row">
                    <div class="col-lg-11 col-md-24 col-sm-24 busPopPaddingTop ellipse">
                        <?php echo $data['journey_details']['source'];?>
                    </div>
                    <div class="col-lg-2 col-md-24 col-sm-24">
                        <span class='gly-ctm glyphicon glyphicon-play'></span>
                    </div>
                    <div class="col-lg-10 col-md-24 col-sm-24 busPopPaddingTop ellipse">
                        <?php echo $data['journey_details']['destination'];?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 pull-right">
                <div class="col-lg-4 col-lg-offset-6 img-cal">
                    <img src="<?php echo base_url('img/calendar_icon.png');?>" alt="jA" width='18px' />
                </div>
                <div class="col-lg-12 pop-title-text"><?php echo date("d M Y",strtotime($data['journey_details']['datepicker']));?></div>
            </div>
        </div>
    </div>
</div> 
<!-- popover screen end -->

<div class="wrap">
    <div class="clear-top">
        <nav class="navbar fixed-top" style="margin-top:0.5%; box-shadow: 0 1px 2px rgba(0,0,0,.05);">
            <div  class="container-fluid" id="info">
                <div class="row fl_overwiew">
                    <div class="col-lg-5 fl_septr1">
                        <div class="col-lg-2 fl_no">1</div>
                        <div class="col-lg-3 bus_bg_nav"></div>
                        <div class="col-lg-14 fl_info">
                            <div class="row">
                                <div class="col-lg-offset-4 col-lg-18 center-align-text travel-text">
                                    <div class="row center-align-text">
                                        <div class="col-lg-11 remove-padding srcDest ellipse" id="origin" style="font-size:12px;"><?php echo $data['journey_details']['source'];?>
                                        </div>
                                        <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                        <div class="col-lg-11 remove-padding srcDest ellipse" id="destination" style="font-size:12px;"><?php echo $data['journey_details']['destination'];?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date("D, jS M Y",strtotime($data['journey_details']['datepicker']));?></div>
                            </div>  
                            <div class="row">
                                <div class="col-lg-23 col-lg-offset-1 center-align-text TotFare1 fare-padding"> &#x20B9;<span><?php echo number_format($total_bus_fare, 2)?></span></div>
                            </div>
                        </div>
                        <div class="col-lg-4 pull-right">
                            <div class="row fl_btn1">
                                <div class="link-btn-de link-btn-de-traveller">
                                    <a tabindex="0" class='btn-de' id='popover-toggle' data-toggle="popover" data-placement="bottom">DETAILS</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="col-xs-24 col-sm-18 col-md-17" id="error_msg">
                <form action="<?php echo site_url('bus_api/buses/blockTicket')?>" method="GET" id="travellers-form" enctype="multipart/form-data">
                    <input name="arrival_time" value="<?php echo $data['arrival_time'];?>" style="display:none;"/>
                    <input name="discountCode" id="discountCode" value="" style="display:none;"/>
                    <input name="discountValue" id="discountValue" value="" style="display:none;"/>
                    <input name="key" value="LcXB2s" style="display:none;"/>
                    <input type="text" name="productinfo" style="display:none;" value="cab to <?php echo $data['chosendestination'];?>"/>
                </form>
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
                                        if( count($data['chkchk']) ){
                                            if( count($data['chkchk']) == 1 )
                                                echo count($data['chkchk'])." Passenger";
                                            else
                                                echo count($data['chkchk'])." Passengers";
                                        }
                                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Taxes</td>
                                    <td class="right-text tf-cng">&#x20B9; <?php echo number_format(array_sum($fares_service));?></td>
                                </tr>
                                <tr>
                                    <td>Base Fare</td>
                                    <td class="right-text tf-cng">&#x20B9; <?php echo number_format(array_sum($fares_base))?></td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="right-text tf-cng discount-value">&#x20B9; <span><?php echo number_format(0);?></span></td>
                                </tr>
                                <tr>
                                    <td>Convenience Charge <span class="glyphicon glyphicon-question-sign convenienceMsg searchHelp"></span></td>
                                    <td class="right-text tf-cng discount-value">&#x20B9; <?php echo number_format($convenience_charge, 2);?></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr>
                                    <td>Total Fare <span class="glyphicon glyphicon-question-sign totalNote"></span> </td>
                                    <td class="right-text tf-cng"><div class="row totFare" style="display:none;"><strike>&#x20B9; <?php echo number_format($total_bus_fare, 2);?></strike></div><div class="row finalFare">&#x20B9; <span><?php echo number_format($total_bus_fare, 2);?></span></div></td>
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
                    </div>
                    <div class="col-xs-10">
                    </div>
                    <div class="col-xs-24">
                        <div class="row">
                            <div class="row xheight">
                                <div class="form-group pull-right yheight" style="margin-bottom:0px;">
                                    <label for="tnc" class="control-label termncond"><input type="checkbox" name="tnc"/> I accept the <a href="#" data-toggle="modal" data-target="#termsAndConditions">Terms and Conditions.</a></label>
                                </div>
                            </div>
							<div class="payu-image-container">
								<img class="col-lg-12" src="<?php echo base_url('img/payu_cert.png'); ?>" />
								<button type="button" class="col-lg-12 xheight bookingBtn applyDiscountCodeBtn activitiesCheckoutBtn bookingFormField pull-right" id="checkoutBtn"><span class="glyphicon glyphicon-circle-arrow-right"></span>&nbsp;CHECKOUT</button>
							</div>
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
            if( $(this).val().length >= 10){
                if( e.keyCode === 9 || e.keyCode === 8 ){
                    return e.keyCode;
                }
                else
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

(function(){

var pattern = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;

//tooltip code
    $('#origin').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: "<?php echo $_SESSION['journey_details']['source'];?>"
        });
        $(this).tooltip('show');
    });

    $('#destination').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: "<?php echo $_SESSION['journey_details']['destination'];?>"
        });
        $(this).tooltip('show');
    });
//end tooltip code

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

    //close popover on body click.

    $('body').on('click', function (e) {
        //only buttons
        if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('.popover.in').length === 0) { 
            $('[data-toggle="popover"]').popover('hide');
        }
    });
// close popover on body click end

// convenience msg
        $('.convenienceMsg').on('mouseenter', function(){
            $(this).tooltip({
                placement: 'top',
                trigger: 'hover',
                title: "<?php echo $convenience_charge_msg;?>",
            });
            $(this).tooltip('show');
        });

        $('.totalNote').on('mouseenter', function(){
            $(this).tooltip({
                placement: 'top',
                trigger: 'hover',
                title: "The total fare shown is the final fare and no further amount will be added during payment.",
            });
            $(this).tooltip('show');
        });
// convenience msg end

    // generates 100 options
    var ageSelectBoxOptionsFullRange = '<option value="age">Age</option>';
    for( var i = 1 ; i < 100 ; i++ ) {
        ageSelectBoxOptionsFullRange += "<option value='"+i+"'>"+i+"</option>";
    };
    var ageSelectBoxOptionsReduced = '<option value="age">Age</option>';
    for( var i = 6 ; i < 100 ; i++ ) {
        ageSelectBoxOptionsReduced += "<option value='"+i+"'>"+i+"</option>";
    };
    // generates 100 options end
    
    var total_passenger = <?php echo count($data['chkchk']);?>;
    var chkchk = <?php echo json_encode($data['chkchk']);?>;
	var seat_count = <?php echo count($seat); ?>;
    <?php if( isset($seat) ):?>
        var isLadiesSeat = <?php echo json_encode($seat);?>;
        for (var i = 0 ; i < total_passenger ; i++) 
        {
				if( isLadiesSeat[i] === 'IsLadies' ){
					var j = i + 1;
					$('#travellers-form').append('<input class="form-control" type="text" name="sex'+i+'" value="female" style="display:none;"/> <div class="form-padding row"> <div class="col-lg-24"> <h4> Passenger '+j+' (Ladies Seat) </h4> </div></div><div class="form-padding row"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select placeholder="Title" id="not_selected" class="selectpicker traveller_title form-control title" name="Title'+i+'"> <option> Title </option> <option> Ms </option> <option> Mrs </option> </select> </div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="fname'+i+'" placeholder="Name"> </div></div><div class="col-lg-3 col-md-3 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <select name="age'+i+'" placeholder="Age" id="not_selected" class="ageSelect">'+ageSelectBoxOptionsReduced+'</select> </div></div></div>');
				}else{
					var j = i + 1;
					if( i == 0 ){
						$('#travellers-form').append(' <div class="form-padding row"> <div class="col-lg-24"> <h4> Passenger '+j+' (Lead) </h4> </div></div><div class="row"> <div class="col-lg-24"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select placeholder="Title" id="not_selected" class="selectpicker traveller_title form-control title" name="Title'+i+'"> <option> Title </option> <option> Mr </option> <option> Ms </option> <option> Mrs </option> </select> </div><div class="form-group"> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <input type="text" class="form-control forceAlpha" name="fname'+i+'" placeholder="Name"> </div><div class="col-lg-3 col-md-3 col-sm-9 col-xs-23 form-padding form-group"> <select name="age'+i+'" placeholder="Age" id="not_selected" class="ageSelect">'+ageSelectBoxOptionsReduced+'</select> </div></div><div class="form-group"> <div class="col-lg-6 col-md-6 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group gender_error pull-left"> <div class="btn-group remove-padding control-label passenger-gender" id="passenger-gender" data-toggle="buttons"> <label class="btn btn-change-radio"> <input class="form-control" type="radio" name="sex'+i+'" value="male"/> Male </label> <label class="btn btn-change-radio"> <input class="form-control" type="radio" name="sex'+i+'" value="female"/> Female </label> </div></div></div></div></div></div>');
					}else{
						$('#travellers-form').append(' <div class="form-padding row"> <div class="col-lg-24"> <h4> Passenger '+j+' </h4> </div></div><div class="row"> <div class="col-lg-24"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select placeholder="Title" id="not_selected" class="selectpicker traveller_title form-control title" name="Title'+i+'"> <option> Title </option> <option> Mr </option> <option> Ms </option> <option> Mrs </option> </select> </div><div class="form-group"> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <input type="text" class="form-control forceAlpha" name="fname'+i+'" placeholder="Name"> </div><div class="col-lg-3 col-md-3 col-sm-9 col-xs-23 form-padding form-group"> <select name="age'+i+'" placeholder="Age" id="not_selected" class="ageSelect">'+ageSelectBoxOptionsFullRange+'</select> </div></div><div class="form-group"> <div class="col-lg-6 col-md-6 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group gender_error pull-left"> <div class="btn-group remove-padding control-label passenger-gender" id="passenger-gender" data-toggle="buttons"> <label class="btn btn-change-radio"> <input class="form-control" type="radio" name="sex'+i+'" value="male"/> Male </label> <label class="btn btn-change-radio"> <input class="form-control" type="radio" name="sex'+i+'" value="female"/> Female </label> </div></div></div></div></div></div>');
					}
				}
			
        }
    <?php else:?>
        for (var i = 0 ; i < total_passenger ; i++) {
            var j = i + 1;
            if( i == 0 ){
                $('#travellers-form').append(' <div class="form-padding row"> <div class="col-lg-24"> <h4> Passenger '+j+' (Lead) </h4> </div></div><div class="row"> <div class="col-lg-24"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select placeholder="Title" id="not_selected" class="selectpicker traveller_title form-control title" name="Title'+i+'"> <option> Title </option> <option> Mr </option> <option> Ms </option> <option> Mrs </option> </select> </div><div class="form-group"> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <input type="text" class="form-control forceAlpha" name="fname'+i+'" placeholder="Name"> </div><div class="col-lg-3 col-md-3 col-sm-9 col-xs-23 form-padding form-group"> <select name="age'+i+'" placeholder="Age" id="not_selected" class="ageSelect">'+ageSelectBoxOptionsReduced+'</select> </div></div><div class="form-group"> <div class="col-lg-6 col-md-6 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group gender_error pull-left"> <div class="btn-group remove-padding control-label passenger-gender" id="passenger-gender" data-toggle="buttons"> <label class="btn btn-change-radio"> <input class="form-control" type="radio" name="sex'+i+'" value="male"/> Male </label> <label class="btn btn-change-radio"> <input class="form-control" type="radio" name="sex'+i+'" value="female"/> Female </label> </div></div></div></div></div></div>');
            }else{
                $('#travellers-form').append(' <div class="form-padding row"> <div class="col-lg-24"> <h4> Passenger '+j+' </h4> </div></div><div class="row"> <div class="col-lg-24"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select placeholder="Title" id="not_selected" class="selectpicker traveller_title form-control title" name="Title'+i+'"> <option> Title </option> <option> Mr </option> <option> Ms </option> <option> Mrs </option> </select> </div><div class="form-group"> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <input type="text" class="form-control forceAlpha" name="fname'+i+'" placeholder="Name"> </div><div class="col-lg-3 col-md-3 col-sm-9 col-xs-23 form-padding form-group"> <select name="age'+i+'" placeholder="Age" id="not_selected" class="ageSelect">'+ageSelectBoxOptionsFullRange+'</select> </div></div><div class="form-group"> <div class="col-lg-6 col-md-6 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group gender_error pull-left"> <div class="btn-group remove-padding control-label passenger-gender" id="passenger-gender" data-toggle="buttons"> <label class="btn btn-change-radio"> <input class="form-control" type="radio" name="sex'+i+'" value="male"/> Male </label> <label class="btn btn-change-radio"> <input class="form-control" type="radio" name="sex'+i+'" value="female"/> Female </label> </div></div></div></div></div></div>');
            }
        };
    <?php endif;?>

    $('#travellers-form').append('<div class="form-padding row"> <div class="col-lg-24"> <h4> Lead traveller contact details: </h4> </div></div><div class="form-padding row"> <div class="control-label"> <div class="form-group form-padding col-xs-1 col-sm-2 col-md-2 col-lg-2 "> <input type="text" value="+91" class="small-input-prefix forceNumeric"/> </div><div class="form-group form-padding col-xs-24 col-sm-11 col-md-8 col-lg-8"> <input type="text" class="form-control forceNumeric mobileNumber" name="phone_no" placeholder="Phone Number"/> </div></div><div class="control-label"> <div class="col-lg-8 col-md-8 col-sm-12 col-xs-23 form-padding form-group"> <input type="text" class="form-control" name="email_id" placeholder="Email Id"> </div></div></div><div class="form-padding row"> <div class="control-group"> <div class="col-lg-18 col-md-18 col-sm-24 col-xs-23 form-padding form-group"> <textarea rows="4" cols="25" type="text" class="form-control" name="address" placeholder="Address"></textarea> <small>*Address field is optional</small> </div></div></div><div class="form-padding row"> <div class="col-lg-8 col-md-8 col-sm-12 col-xs-23 form-padding"> <select name="id_proof" id="not_selected" class="form-control idproof" type="text"> <option>ID Proof</option> <option> Pan Card </option> <option> Driving Licence </option> <option> Voting Card </option> <option> Adhar Card </option> </select> </div><div class="control-group"> <div class="col-lg-10 col-md-10 col-sm-12 col-xs-23 form-padding form-group"> <input type="text" class="form-control" name="id_no" placeholder="Id Number"> </div></div></div>');
 
    $('#travellers-form').append('<div class="form-padding row" style="width:20%;margin:0% 18.3% 10%;"><div class="col-lg-24 remove-padding"><button id="form-submit-button" type="button" style="display:none;"><span class="glyphicon glyphicon-search"></span> CHECKOUT</button></div></div>');

    $.datepicker.setDefaults({dateFormat: "yy-mm-dd"});

    /***select or die*******/
    $('.title').selectOrDie({
        placeholderOption: true,
        onChange: function(){
            this.id = 'selected';
            $(this).parent().siblings('small.help-block').hide();
            $(this).parent().css('border-color', '#27ae60');
        },
    });

    $('.ageSelect').selectOrDie({
        placeholderOption: true,
        size: 8,
        onChange: function(){
            this.id = 'selected';
            $(this).parent().siblings('small.help-block').hide();
            $(this).parent().css('border-color', '#27ae60');
        }
    });

    $('.idproof').selectOrDie({
        placeholderOption: true,
        onChange: function(){
            this.id = 'selected';
            $(this).parent().siblings('small.help-block').hide();
            $(this).parent().css('border-color', '#27ae60');
        }
    });

    //validation form

    $('#travellers-form').bootstrapValidator({
        live: "disabled",
		fields: {
			sex1: {
				validators: {
                    choice: {
                        min: 1,
                        max: 1,
                        message: 'Please choose Your gender.'
                    },
                    notEmpty: {
                        message: 'Gender is required'
                    }
                }
			}
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
                validators: {
                    choice: {
                        min: 1,
                        max: 1,
                        message: 'Please choose Your gender.'
                    },
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
                    },
					stringLength: {
                        min: 10,
                        message: 'Please enter a valid 10-digit mobile number.'
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

    var radio_btn = $('#passenger-gender').find(':input:radio');
    $.each(radio_btn, function(i, val){
        val.id = "not_selected";
    });
    var gender_not_selected_flag = 0;

    $(document).ready(function(){

        var timeInMins = 10 * 60 * 1000;

        setTimeout(function(){
            window.location.href = '<?php echo site_url("flights/time_out");?>';
        }, timeInMins);

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

    //adding dynamic fields into bv for validation

    var travellerForm = $('#travellers-form');
    var travellerFormBv = travellerForm.data('bootstrapValidator');

    var fields = {};
    travellerForm.find(":input:visible").each(function() {
        fields[this.name] = this.placeholder;
    });

    travellerForm.find("select").each(function() {
        fields[this.name] = $(this).attr('placeholder');
    });

    travellerForm.find(":input:radio").each(function() {
        $(this).attr('placeholder', 'Gender');
        fields[this.name] = $(this).attr('placeholder');
    });

// this part checks the placeholder of a field and add various validation options eg: is e-mail validation for e-mail etc
    $.each(fields, function(key, val){
        switch( val ){
            case 'Name':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "Name is required."
                        }
                    }
                });
            break;
            case 'Age':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "Age is required."
                        }
                    }
                });
            break;
            case 'Phone Number':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "Mobile Number is required."
                        },
                        numeric:{
                            message: "Please Enter a Valid Mobile Number."
                        },
						stringLength: {
							min: 10,
							message: 'Please enter a valid 10-digit mobile number.'
						}
                    }
                });
            break;
            case 'Email Id':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "Email Id is required."
                        },
                        emailAddress:{
                            message: "Please Enter a Valid Email Id."
                        }
                    }
                });
            break;
            case 'Address':
            break;
            case 'Title':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "Title is required."
                        }
                    }
                });
            break;
            case 'Gender':
                travellerFormBv.addField(key,{
                    validators:{
                        notEmpty: {
                            message: 'The gender is required'
                        },
                    }
                });
            break;
            default :
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "Field is required."
                        }
                    }
                });
        }
    });

    $(document).on('change', 'input[type=radio]', function(){
        $(this).attr('id', '');
        $(this).parent().next().children('input[type=radio]').attr('id', "");
        $(this).parents('div#passenger-gender').siblings('small.help-block').hide();
    });

    //allow only numeric keys for certain fields
    $('.forceNumeric').ForceNumericOnly();
    //allow only numeric keys for certain fields end`

    //allow only alphabetic keys begin
    $('.forceAlpha').ForceAlphaOnly();
    //allow only alphabetic keys end

    //adding dynamic fields into bv for validation end

    $('#form-submit-button').on('click', function(e){
        e.preventDefault();
        var not_selected_flag = 0;
        var age_not_selected_flag = 0;
        var id_not_selected_flag = 0;
		var gender_not_selected_flag = 1;
        var all_selects = $(document).find('select.traveller_title');
        var all_age_selects = $(document).find('select.ageSelect');
        var all_id_proofs = $(document).find('select.idproof');
        var radio_btn = $('#passenger-gender').find(':input:radio');

        $.each( all_selects, function(i, val){
            if( val.id === 'not_selected' ){
                not_selected_flag = 1;
            }
        });

        $.each( all_age_selects, function(i, val){
            if( val.id === 'not_selected' ){
                age_not_selected_flag = 1;
            }
        });

        $.each( all_id_proofs, function(i, val){
            if( val.id === 'not_selected' ){
                id_not_selected_flag = 1;
            }
        });
		
		<?php if( isset($seat) ):?>
        var isLadiesSeat = <?php echo json_encode($seat);?>;
        for (var i = 0 ; i < total_passenger ; i++) 
        {
            if( isLadiesSeat[i] === 'IsLadies' ){
                gender_not_selected_flag = 0;
            }
        }
        <?php endif; ?>

        $.each(radio_btn, function(i, val){
            if( val.id == "" ){
                gender_not_selected_flag = 0;
            }
        });


        if( not_selected_flag || age_not_selected_flag || id_not_selected_flag || gender_not_selected_flag ){
            $.each(radio_btn, function(i, val){
                if( gender_not_selected_flag == 1 ){
                    $(val).parents('div.passenger-gender').siblings('small.help-block').show();
					$('#travellers-form').data('bootstrapValidator').validate();
                }
            });
            $.each( all_selects, function(i, val){
                if( val.id === 'not_selected' ){
                    $(val).parent().siblings('small.help-block').show();
                    $(val).parent().css('border-color', '#f00');
                    $('#travellers-form').data('bootstrapValidator').validate();
                }
            });
            $.each( all_age_selects, function(i, val){
                if( val.id === 'not_selected' ){
                    $(val).parent().siblings('small.help-block').show();
                    $(val).parent().css('border-color', '#f00');
                    $('#travellers-form').data('bootstrapValidator').validate();
                }
            });
            $.each( all_id_proofs, function(i, val){
                if( val.id === 'not_selected' ){
                    $(val).parent().siblings('small.help-block').show();
                    $(val).parent().css('border-color', '#f00');
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
        if( mobileNumber.indexOf('+91') === -1 ){
            $('.mobileNumber').val($('.small-input-prefix').val() +""+ mobileNumber);
        }else{
            $('.mobileNumber').val(mobileNumber);
        }
        if( $('input[name=tnc]').is(":checked") ){
            this.submit();
        }else{
            $('input[name=tnc]').parent('label').css('color', '#f00');
            $('.discountMessageBlock .errorMessage').html('Please accept the Terms and Conditions before checkout').fadeIn(200);
            setTimeout(function(){
                $('input[name=tnc]').parent('label').css('color', '#000');
                $('.discountMessageBlock .errorMessage').fadeOut(200);
            }, 3000);
            return false;
        }
    });

    $.datepicker.setDefaults({
            dateFormat: "dd-mm-yy"
    });

    $('#expiry-date-1').datepicker();
	
    // login group
        var login = new Object;
        var user = new Object;

        var logged = 0;

        $('#guest_reg_submit').on('click', function(){
            $('#guest_reg_submit').attr('disabled','true');
            $('#login_user_submit').attr('disabled','true');
            login.guest_email = $('input[name=guest_email]').val();
            login.isAjax = 1;

            if((login.guest_email == null || login.guest_email == "")){
                $('input[name=guest_email]').animate({'border-color': '#f00'}, 100);
                $('.loginMessageBlock .errorMessage').html('Please enter an E-Mail address first.').fadeIn(200);
                $('#guest_reg_submit').removeAttr('disabled');
				$('#login_user_submit').removeAttr('disabled');
                setTimeout(function(){
                    $('input[name=guest_email]').animate({'border-color': '#27ae60'}, 100);
                    $('.loginMessageBlock .errorMessage').fadeOut(200);
                }, 3000);
                return false;
            }else{
                if( !pattern.test($('input[name=guest_email]').val()) ){
                    $('.loginMessageBlock .errorMessage').html('Please enter a Valid E-Mail address.').fadeIn(200);
                    $('#guest_reg_submit').attr('disabled','false');
					$('#login_user_submit').removeAttr('disabled');
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
                            $('.loginMessageBlock .successMessage').html("Logged in successfully!").fadeIn(200);
                            setTimeout(function(){
                                $('.loginMessageBlock .successMessage').fadeOut(200);
                                $('#login_modal').modal('hide');
                            }, 3000);
                            $('#guest_reg_submit').attr('disabled','false');
                            var baseUrl = "<?php echo base_url('index.php/user')?>";
                            var logoutUrl = "<?php echo base_url('login/logout_user')?>";
                            if( logged == 0 ){
                                $('.ticket_header').append('<li class="userWelcome userLink"><a href="javascript:void(0);"><span class="glyphicon glyphicon-user"></span>&nbsp&nbspHi Guest</a></li><li class="userWelcome"><a href="'+logoutUrl+'"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbspLogout</a></li>');
                                logged = 1;
                            }
                            $('.loginLink').hide();  
                        } else {
                            $('.loginMessageBlock .errorMessage').html('You have already registered with this E-Mail ID. Please sign up to Farebucket or use a different E-Mail ID.').fadeIn(200);
                            $('#guest_reg_submit').removeAttr('disabled');
							$('#login_user_submit').removeAttr('disabled');
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
			$('#guest_reg_submit').attr('disabled','true');
			$('#login_user_submit').attr('disabled','true');
            
            if((user.email == null || user.email == "")&&(user.password == null || user.password == "")){
                $('input[name=email]').animate({'border-color': '#f00'}, 100);
                $('input[name=password]').animate({'border-color': '#f00'}, 100);
                $('.loginMessageBlock .errorMessage').html('Please enter an E-Mail address and Password first.').fadeIn(200);
				$('#guest_reg_submit').removeAttr('disabled');
				$('#login_user_submit').removeAttr('disabled');
                setTimeout(function(){
                    $('.loginMessageBlock .errorMessage').fadeOut(200);
                    $('input[name=email]').animate({'border-color': '#27ae60'}, 100);
                    $('input[name=password]').animate({'border-color': '#27ae60'}, 100);
                }, 3000);
                return false;
            }

            else{
                $.post($("#login_user").attr('action'), user, function( retObj ){
                    if( retObj ){
                        if(retObj['status'] === "success")
                        {
							$('#login_user_submit').attr('disabled','true');
							$('#guest_reg_submit').attr('disabled','true');
                            $('.loginMessageBlock .successMessage').html('Logged in successfully!').fadeIn(200);
                            setTimeout(function(){
                                $('.loginMessageBlock .successMessage').fadeOut(200);
                                var baseUrl = "<?php echo base_url('index.php/user')?>";
                                var logoutUrl = "<?php echo base_url('index.php/login/logout_user')?>";
                                $('#login_modal').modal('hide');
                                $('.ticket_header').append('<li class="userWelcome userLink"><a href="'+baseUrl+'"><span class="glyphicon glyphicon-user"></span>&nbsp&nbsp'+retObj["first_name"]+'</a></li><li class="userWelcome"><a href="'+logoutUrl+'"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbspLogout</a></li>');
                                $('.loginLink').hide();
                            }, 3000);
                        }
                        else
                        {
                            $('.loginMessageBlock .errorMessage').html('Incorrect Credentials. Please try again.').fadeIn(200);
							$('#guest_reg_submit').removeAttr('disabled');
							$('#login_user_submit').removeAttr('disabled');
                            setTimeout(function(){
                                $('.loginMessageBlock .errorMessage').fadeOut(200);
                            }, 3000);
                            $('#email').val("");
                            $('#pass').val("");
                        }
                    } else {
                        $('.loginMessageBlock .errorMessage').html('Your user name or password seems to be incorrect. Please check your details').fadeIn(200);
                        $('#guest_reg_submit').removeAttr('disabled');
						$('#login_user_submit').removeAttr('disabled');
						setTimeout(function(){
                            $('.loginMessageBlock .errorMessage').fadeOut(200);
                        }, 3000);
                        return false;
                    }
                },'json');
            }
        });
    // login group end

//discount section

    $('strike').hide();

    $('.finalFare span, .TotFare1 span, .discount-value span').autoNumeric({
        aSep: ',',
        dGroup: 2,
    });

    total_fare = parseFloat(<?php echo $total_bus_fare;?>);
    var discountClick = 0;
    var discounted_val = 0;
    var value = 0;
    var code = "";
    var finalFare = 0;

    $(".apply-btn").click(function(){

        var discountCode = $('.dis-code').val();
        var discountModule = "buses";

        if( discountCode === "" || discountCode === null ){
            $('.discountMessageBlock .errorMessage').html('Please enter a valid Code').fadeIn(200);
            setTimeout(function(){
                $('.discountMessageBlock .errorMessage').fadeOut(200);
            }, 3000);
        }else{
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('flights/apply_discount_code');?>",
                data: { discountCode: discountCode, discountModule: discountModule, total_fare: total_fare }
            })
            .done(function(data){
                if(data === "Failure"){
                    $('.discountMessageBlock .errorMessage').html('The discount code you have entered is Invalid').fadeIn(200);
                    setTimeout(function(){
                        $('.discountMessageBlock .errorMessage').fadeOut(200);
                    }, 3000);
                }else{
                    var data = $.parseJSON(data);
                    console.log(data.finalFare);
                    $('.cancel-btn-row').show();
                    $('.input-group-row').hide();
                    discountClick++;
                    $('input#discountCode').val(data.code);
                    $('input#discountValue').val(data.value);
                    $('strike').slideDown();
					$('.totFare').show();
                    $('.finalFare span').html(data.finalFare);
                    $('.discountMessageBlock .successMessage').html('discount has been applied').fadeIn(200);
                    setTimeout(function(){
                        $('.discountMessageBlock .successMessage').fadeOut(200);
                    }, 3000);
                    if( data.type === "percent" ){
                        $('.discount-value span').html(total_fare*data.percent);
                    }else{
                        $('.discount-value span').html(data.value);
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
        if( logged === 1 ){
            $('#login_modal').modal('hide');
        }
        else{
            $('#login_modal').modal('show');
        }
        $('#form-1').data('bootstrapValidator').resetForm();
    }); 

    $.datepicker.setDefaults({
        dateFormat: "dd-mm-yy"
    });

    $('#date-1').datepicker({
        maxDate: '-18Y',
        changeMonth: true,
        changeYear: true,
        onSelect: function(){
            var sm = $('#form-1').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
        }
    });

    $( ".selector" ).datepicker( "option", "yearRange", "c-100:c-18" );

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


})();
    
function searchKey(e){
    if( e.keyCode === 13 ){
        e.preventDefault();
        alert('yes');
    }
    return false;
}
</script>