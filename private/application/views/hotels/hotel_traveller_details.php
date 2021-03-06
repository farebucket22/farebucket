<script type="text/javascript"> 

function stopRKey(evt) { 
    var evt = (evt) ? evt : ((event) ? event : null); 
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
    if ((evt.keyCode == 13)) {return false;} 
} 

document.onkeypress = stopRKey; 

</script>
<?php 
    $room_type = $data['room_type'];
    $room_chosen_arr = explode("~s~", $room_type);
    $room_chosen = $room_chosen_arr[2];
    $iso = explode(",",$data['search-string-single']);
    $destination = explode(",",$data['typed-string-single']);
    $data['checkin_time'] = str_replace("/","-",$data['checkin_time']);
    $data['checkout_time'] = str_replace("/","-",$data['checkout_time']);
    $diff=date_diff(date_create($data['checkin_time']),date_create($data['checkout_time']));
    $stay_days = $diff->format("%a");
    $hotel_extra_info = json_decode($data['hotel_extra_info']);
    $hotel_info = json_decode($data['hotel_info']);
    $room_details = json_decode($data['room_details']);
    $hotel_price = $hotel_info->Price->PublishedPrice;
    $hotel_name = $hotel_info->HotelName;
    $hotel_stars = $hotel_info->StarRating.' stars';
    $total_rooms = $data['single_rooms'];
    for($j = 0 ; $j<$total_rooms;$j++)
    {
        $i = $j+1;
        $adult[$j] = $data['adult_count_single-'.$i];
        $child[$j] = $data['child_count_single-'.$i];
    }
    $hotel_tax = $hotel_info->Price->Tax;
    $hotel_discount = $hotel_info->Price->Discount;
    $_SESSION['hotel_price_single'] = $hotel_price;
    $convenience_charge_msg = $data['convenience_charge']->convenience_charge_msg;
    $convenience_charge = ($data['convenience_charge']->convenience_charge/100)*$hotel_price;
    $total_hotel_fare = $convenience_charge + $_SESSION['hotel_price_single'];
    $_SESSION['hotel_price_single'] = $total_hotel_fare;
    
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
        padding-left: 8px;
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

    .date-text{
        padding: 0;
    }
    .sod_select{
        text-transform: none;
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
    <div id="popoverHiddenContent" style="display: none">
        <div class="row center-align-text fl_row">
            <!-- first flight -->
            <div class="col-lg-10 left-text">
                <div class="row date-text center-align-text"><?php echo $hotel_name;?></div>
                <div class="row date-text"><?php echo $room_chosen;?></div>
            </div>
            <div class="col-lg-offset-2 col-lg-4 travel-text-margin center-align-text remove-padding">
                <div class="date-text">Stay</div>
                <div class="row travel-text">
                    <div class="date-text" id="from"><?php echo $stay_days.'N ';echo $stay_days+1;echo 'D';?></div>
                </div>
            </div>
            <div class="col-lg-6 col-lg-offset-3 center-align-text">
                <div class="row date-text center-align-text popover-total-fare" style="font-size:18px; padding-top: 1px;"> &#x20B9;<span style="font-family:oswald;font-size:18px;"><?php echo $hotel_price;?></span></div>
            </div>
        </div>
    </div>
    <div id="popoverHiddenTitle" style="display: none">
        <div class="row">
            <div class="col-lg-12 pull-left">
                <div class="col-lg-10">
                    <?php echo $destination[0];echo ', ';echo $iso[count($iso)-2];?>
                </div>
            </div>
                <div class="col-lg-12 pull-right">
                <div class="col-lg-4 col-lg-offset-8 img-cal">
                    <img src="<?php echo base_url('img/calendar_icon.png');?>" alt="jA" width='18px' />
                </div>
                <div class="col-lg-12 pop-title-text"><?php echo date("d M Y",strtotime($data['checkin_time']));?></div>
            </div>
        </div>
    </div>
</div> 
<!-- popover screen end -->

<div class="wrap">
    <div class="clear-top">
        <div class="grey-bottom-separator center-align-text">
            <span class="glyphicon glyphicon-user hulk-class"></span>
            <?php if(array_sum($adult) == 1):?>
                <span><?php echo array_sum($adult);?> Adult, </span>
            <?php else:?>
                <span><?php echo array_sum($adult);?> Adults, </span>
            <?php endif;?>
            <?php if(array_sum($child) == 1):?>
                <span><?php echo array_sum($child);?> Child</span>
            <?php else:?>
                <span><?php echo array_sum($child);?> Children</span>
            <?php endif;?>
            <span>&nbsp;|</span>
            <span class="glyphicon glyphicon-calendar hulk-class"></span>
            <span class="TravelDate"></span>Check-In: <?php echo date("D, jS M Y",strtotime($data['checkin_time']));?>, Check-Out: <?php echo date("D, jS M Y",strtotime($data['checkout_time']));?>
        </div>
        <nav class="navbar fixed-top" style="margin-top:0.5%; box-shadow: 0 1px 2px rgba(0,0,0,.05);">
            <div  class="container-fluid" id="info">
                <div class="row fl_overwiew">
                    <div class="col-lg-5 fl_septr1">
                        <div class="col-lg-2 fl_no">1</div>
                        <div class="col-lg-3 hotel_bg_nav"></div>
                        <div class="col-lg-14 fl_info">
                            <div class="row">
                                <div class="col-lg-offset-4 col-lg-18 center-align-text travel-text">
                                    <div class="row center-align-text">
                                        <div class="col-lg-18 remove-padding srcDest" id="origin" style="margin-left:10%;"><?php echo $destination[0].', '.$iso[count($iso)-2];?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-17 col-lg-offset-5 TravelDate center-align-text"><?php echo date("d M Y",strtotime($data['checkin_time'])).', ';echo $stay_days.'N ';echo $stay_days+1;echo 'D';?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-23 col-lg-offset-1 center-align-text TotFare1 fare-padding"> &#x20B9;<span><?php echo $hotel_price;?></span></div>
                            </div>
                        </div>
                        <div class="col-lg-4 pull-right">
                            <div class="row fl_btn1">
                                <div class="link-btn-de link-btn-de-traveller">
                                    <a href="#" tabindex="0" class='btn-de' id='popover-toggle' data-toggle="popover" data-placement="bottom">DETAILS</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="col-xs-24 col-sm-18 col-md-18" id="error_msg">
                <form action="<?php echo site_url('new_request/payment_gateway')?>" method="post" id="travellers-form" enctype="multipart/form-data">
                    <input name="details" value="" style="display:none;"/>
                    <input name='key' type='text' value='C0Dr8m' style="display:none;"/>
                    <input name="productinfo" value="hotel ticket to <?php echo $data["typed-string-single"];?>" style="display:none;">
                    <input name="call_func" value="single" style="display:none;"/>
                    <input name="extra_info" id="extra_info" value="" style="display:none;">
                    <input name="info" id="hotel_info" value="" style="display:none;">
                    <input name="room_info" id="room_info" value="" style="display:none;">
                    <input name="des" value="<?php echo $data["typed-string-single"];?>" style="display:none;">
                    <input name="iso" value="<?php echo $data["search-string-single"];?>" style="display:none;">
                    <?php $ad_cnt = 1; $ch_cnt = 1; foreach($adult as $ad):?>
                        <input type="text" name="adult_<?php echo $ad_cnt;?>" value="<?php echo $ad;?>" style="display:none;" />
                    <?php $ad_cnt++;?>
                    <?php endforeach;?>
                    <?php foreach($child as $ch):?>
                        <input type="text" name="child_<?php echo $ch_cnt;?>" value="<?php echo $ch;?>" style="display:none;" />
                    <?php $ch_cnt++;?>
                    <?php endforeach;?>
                    <input name="count" value="<?php echo $data["single_rooms"];?>" style="display:none;">
                    <input name="check_in" value="<?php echo $data["checkin_time"];?>" style="display:none;">
                    <input name="check_out" value="<?php echo $data["checkout_time"];?>" style="display:none;">
                    <input name="room_type" value="<?php echo $room_type;?>" style="display:none;">
                </form>
            </div>
            <div class="col-xs-24 col-sm-6 col-md-6 bookingDetailsContainer">
                <div class="row bookingSelectionDetails tripSummary center-align-text">Trip Summary</div>
                <div class="row">
                    <div class="col-xs-24 col-sm-24 col-md-24 bookingSelectionDetails">
                        <table class="table table-condensed table-custom" >
                            <tbody>
                                <tr>
                                    <td>No.of Rooms</td>
                                    <td class="right-text tf-cng">
                                        <?php echo $total_rooms;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>No.of Guests</td>
                                    <td class="right-text tf-cng">
                                        <?php 
                                            if( $adult ){
                                                if( array_sum($adult) == 1 )
                                                    echo array_sum($adult)." Adult";
                                                else
                                                    echo array_sum($adult)." Adults";
                                            } 
                                            if( $child ){ 
                                                if( array_sum($child) == 1 )
                                                    echo ", ".array_sum($child)." Child";
                                                else
                                                    echo ", ".array_sum($child)." Children";
                                            } 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Taxes</td>
                                    <td class="right-text tf-cng">&#x20B9; <?php echo number_format($hotel_tax);?></td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="right-text tf-cng discount-value">&#x20B9; <?php echo number_format($hotel_discount);?></td>
                                </tr>
                                <tr>
                                    <td>Convenience Charge <span class="glyphicon glyphicon-question-sign convenienceMsg searchHelp"></span></td>
                                    <td class="right-text tf-cng discount-value">&#x20B9; <?php echo $convenience_charge;?></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr>
                                    <td>Total Fare</td>
                                    <td class="right-text tf-cng"><div class="row totFare"><strike style="display:none;">&#x20B9; <?php echo $hotel_price;?></strike></div><div class="row finalFare">&#x20B9; <span><?php echo $hotel_price;?></span></div></td>
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

(function(){
    var extra_info = <?php echo json_encode($hotel_extra_info);?>;
    var information = <?php echo $data['hotel_info'];?>;
    var room_info = <?php echo $data['room_details'];?>;
    $('#extra_info').val(JSON.stringify(extra_info));
    $('#hotel_info').val(JSON.stringify(information));
    $('#room_info').val(JSON.stringify(room_info));
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

    // generates 100 options
    var ageSelectBoxOptions = '<option value="age">Age</option>';
    for( var i = 1 ; i < 100 ; i++ ) {
        ageSelectBoxOptions += "<option value='"+i+"'>"+i+"</option>";
    };
    // generates 100 options end
    
    var room_count = <?php echo $total_rooms;?>;
    var adult_count = <?php echo json_encode($adult);?>;
    var child_count = <?php echo json_encode($child);?>;
    for(var m = 0 ; m < room_count; m++)
    {
        var n = m + 1;
        $('#travellers-form').append('<div class="form-padding row" > <div class="col-lg-24"> <h4> Room '+n+' </h4></div></div>');
        for (var i = 0 ; i < adult_count[m] ; i++) 
        {
            if( i === 0 ){
                var j = i + 1;
                $('#travellers-form').append('<div class="form-padding row"> <div class="col-lg-24"> <h4> Adult '+j+' <span class="leadGuestLabel">(<svg height="8" width="8"><circle cx="4" cy="4" r="3" fill="#27ae60"/></svg>Lead Traveller)</span> </h4> </div></div><div class="form-padding row"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select id="not_selected" class="selectpicker traveller_title form-control title" name="title_a[]"> <option>Title</option> <option> Miss </option> <option> Mr </option> <option> Mrs </option> </select> </div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="first_name_a[]" placeholder="First Name"> </div></div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="last_name_a[]" placeholder="Last Name"> </div></div><div class="col-lg-4 col-md-4 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <select name="age_a[]" placeholder="Age" id="not_selected" class="ageSelect">'+ageSelectBoxOptions+'</select> </div></div></div><div class="form-padding row passportContent"> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control" name="pass_number_a[]" placeholder="Passport Number"> </div></div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <div class="inner-addon right-addon"> <i class="glyphicon"> </i> <input name="pass_expiry_a[]" readonly id="expiry-adult-date-'+j+'" class="form-control" type="text" placeholder="Passport Expiry Date"> </div></div></div></div>');
            }else{
                var j = i + 1;
                $('#travellers-form').append('<div class="form-padding row"> <div class="col-lg-24"> <h4> Adult '+j+' </h4> </div></div><div class="form-padding row"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select id="not_selected" class="selectpicker traveller_title form-control title" name="title_a[]"> <option>Title</option> <option> Miss </option> <option> Mr </option> <option> Mrs </option> </select> </div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="first_name_a[]" placeholder="First Name"> </div></div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="last_name_a[]" placeholder="Last Name"> </div></div><div class="col-lg-4 col-md-4 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <select name="age_a[]" placeholder="Age" id="not_selected" class="ageSelect">'+ageSelectBoxOptions+'</select> </div></div></div><div class="form-padding row passportContent"> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control" name="pass_number_a[]" placeholder="Passport Number"> </div></div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <div class="inner-addon right-addon"> <i class="glyphicon"> </i> <input name="pass_expiry_a[]" readonly id="expiry-adult-date-'+j+'" class="form-control" type="text" placeholder="Passport Expiry Date"> </div></div></div></div>');
            }
        };
        
        for(var i =0 ;i < child_count[m] ; i++)
        {
            var j = i + 1;
            $('#travellers-form').append('<div class="form-padding row"> <div class="col-lg-24"> <h4> Child '+j+' </h4> </div></div><div class="form-padding row"> <div class="col-lg-3 col-md-3 col-sm-6 col-xs-16 form-padding"> <select id="not_selected" class="selectpicker traveller_title form-control title" name="title_k[]"> <option>Title</option> <option> Master </option> <option> Miss </option> </select> </div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="first_name_k[]" placeholder="First Name"> </div></div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control forceAlpha" name="last_name_k[]" placeholder="Last Name"> </div></div><div class="col-lg-4 col-md-4 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <select name="age_k[]" id="not_selected" class="form-control ageSelect" type="text" placeholder="Age"> <option>Age</option> <option> 1 </option> <option> 2 </option> <option> 3 </option> <option> 4 </option> <option> 5 </option> <option> 6 </option> <option> 7 </option> <option> 8 </option> <option> 9 </option> <option> 10 </option> <option> 11 </option> <option> 12 </option> </select> </div></div></div><div class="form-padding row passportContent" style="width:65%;margin:auto;"> <div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <input type="text" class="form-control" name="pass_number_k[]" placeholder="Passport Number"> </div></div><div class="col-lg-8 col-md-8 col-sm-9 col-xs-23 form-padding control-label"> <div class="form-group"> <div class="inner-addon right-addon"> <i class="glyphicon"> </i> <input name="pass_expiry_k[]" readonly id="expiry-kids-date-'+j+'" class="form-control" type="text" placeholder="Passport Expiry Date"> </div></div></div></div>');
        };
    }

    $('#travellers-form').append('<div class="form-padding row" style="width:20%;margin:0% 18.3% 10%;"><div class="col-lg-24 remove-padding"><button id="form-submit-button" type="button" style="display:none;"><span class="glyphicon glyphicon-search"></span> CHECKOUT</button></div></div>');

    $.datepicker.setDefaults({dateFormat: "yy-mm-dd"});

    /***select or die*******/
    $('.title').selectOrDie({
        placeholderOption: true,
        onChange: function(){
            this.id = 'selected';
            $(this).parent().siblings('small.help-block').hide();
            $(this).parent('span').css('border-color', '#27ae60');
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

    $('.lead').selectOrDie({
        placeholder: 'Lead'
    });
    $('.pass_type').selectOrDie({
        placeholder: 'Type'
    });
    //validation form

    //internation flights
    var international_var = 1;

    if( international_var ){
        $('.passportContent').hide();
    }
    //internation flights end

    $('#travellers-form').bootstrapValidator({
        live: 'disabled',
        fields: {
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
             'age_a[]': {
                validators: {
                    notEmpty: {
                        message: 'Age is required'
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
            'age_k[]': {
                validators: {
                    notEmpty: {
                        message: 'Age is required'
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

    $('#form-submit-button').on('click', function(e){
        e.preventDefault();
        var not_selected_flag = 0;
        var age_not_selected_flag = 0;
        var all_selects = $(document).find('select.traveller_title');
        var all_age_selects = $(document).find('select.ageSelect');

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

        if( not_selected_flag || age_not_selected_flag ){
            $.each( all_selects, function(i, val){
                if( val.id === 'not_selected' ){
                    $(val).parent().siblings('small.help-block').show();
                    $(val).parent('span').css('border-color', '#f00');
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
        }else{
            $('#travellers-form').data('bootstrapValidator').validate();
            if( $('#travellers-form').data('bootstrapValidator').isValid() ){
                // $('#travellers-form input[name=details]').val(JSON.stringify(temp_array));
                $('#travellers-form').trigger('travelFormSubmitted');
            }else{
                return false;
            }
        }
    });

    $('#travellers-form').on('travelFormSubmitted', function(){
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
        changeYear: true,
        changeMonth: true,
        yearRange: 'c:c+100'
    });

    // for(var w = 2 ; w <= kid_count+1 ; w++) { 
    //     $('#expiry-adult-date-'+w).datepicker();
    // };

    // for(var x = 1 ; x <= kid_count ; x++) { 
    //     $('#kids-dob-'+x).datepicker({minDate:'-12Y', maxDate:'0'});
    // };

    // for(var y = 1 ; y <= infant_count ; y++) {
    //     $('#infant-dob-'+y).datepicker({minDate:'-2Y', maxDate:'0'});
    // };

    // for(var z1 = 1 ; z1 <= kid_count ; z1++) {
    //     $('#expiry-kids-date-'+z1).datepicker();
    // };

    // for(var z2 = 1 ; z2 <= infant_count ; z2++) {
    //     $('#expiry-infant-date-'+z2).datepicker();
    // };

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
    var total_fare = parseInt("<?php echo $total_hotel_fare;?>");
    var discounted_val = 0;

    $('.finalFare span, .TotFare1 span, .discount-value span, .popover-total-fare span').autoNumeric({
        aSep: ',',
        dGroup: 2,
    });

    $(".apply-btn").click(function(){

        var discountCode = $('.dis-code').val();
        var discountModule = "hotels";

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
})();
</script>