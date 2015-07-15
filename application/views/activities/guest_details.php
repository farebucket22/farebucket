<script type="text/javascript"> 

function stopRKey(evt) { 
    var evt = (evt) ? evt : ((event) ? event : null); 
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
    if ((evt.keyCode == 13)) {return false;} 
} 

document.onkeypress = stopRKey; 

</script>
<?php
    $cc = ($convenience_charge/100)*$booking_amount;
    $total_activity_fare = $cc + $booking_amount;
    $_SESSION['activity_final_fare'] = $total_activity_fare;

?>
<style>
	.payu-image{
        width:15%;
        margin-top: 175px;
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
                                    <input name="dob" id="date-1" class="form-control" type="text" placeholder="Birthday Date" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-21 col-xs-offset-2 control-label remove-padding">
                                    <input class="userRegisterEmail loginFormField form-control" type="text" name="email" placeholder="Email" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-21 col-xs-offset-2 control-label remove-padding">
                                    <input class="userRegisterEmail loginFormField form-control" type="text" name="phone_no" placeholder="Phone Number" />
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

<div class="wrap">
    <div class="container-fluid clear-top">    
        <div class="row resultsSearchContainer navbar-fixed-top">
        <?php
            $this->load->view("common/search.php");
        ?>
        </div>
    </div>
    <div class="container-fluid main clear-top">
        <div class="row guestDetailsCheckoutArea">
            <form class="col-xs-24 col-sm-12 col-sm-offset-1 col-md-12 col-md-offset-1 guestDetailsContainer" id="guestDetailsForm" method="POST" action="<?php echo site_url('activity/create_activity_booking');?>">
                <input name="key" value="LcXB2s" style="display:none;"/>
                <div class="row adultGuestHeader">Adults</div>
                <?php
                for($i=0;$i<$adult_count;$i++){
                ?>
                <div class="row guestHeaderArea">
                    <div class="col-xs-2 guestHeader">Adult<?php echo ($i+1); ?></div>
                <?php
                if($i===0){
                    echo '<span class="col-xs-5 leadGuestLabel">(<svg height="8" width="8"><circle cx="4" cy="4" r="3" fill="#27ae60" /></svg>Lead Guest)</span>';
                }
                ?>
                </div>
                <div class="row guestNameInputArea">
                    <div class="col-xs-3">
                        <select class="titleSelect traveller_title guestField" id="not_selected" name="adultTitleSelect-<?php echo $i; ?>">
                            <option value="title">Title</option>
                            <option value="Mr">Mr</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Ms">Ms</option>
                        </select>
                        <small class="help-block" style="display:none;">The title is required</small>
                    </div>
                    <div class="col-xs-8 control-label">
                        <div class="form-group">
                            <input class="col-xs-8 firstNameInput guestField form-control forceAlpha" required type="text" placeholder="First Name" name="adultFirstName-<?php echo $i; ?>" />
                        </div>
                    </div>
                    <div class="col-xs-8 control-label">
                        <div class="form-group">
                            <input class="col-xs-8 lastNameInput guestField form-control forceAlpha" required type="text" placeholder="Last Name" name="adultLastName-<?php echo $i; ?>"/>
                        </div>
                    </div>
                </div>
                <?php
                if($i===0){
                    echo '<div class="row leadTravellerInfo">';
                    echo '<div class="col-xs-8 control-label">';
                    echo '<div class="form-group">';
                    echo '<input class="col-xs-8 mobileNumberInput guestField form-control forceNumeric" required type="text" placeholder="Mobile Number" name="leadGuestMobile" />';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="col-xs-8 control-label">';
                    echo '<div class="form-group">';
                    echo '<input class="col-xs-8 emailInput guestField form-control" required type="email" placeholder="Email Id" name="leadGuestEmail" />';
                    echo '</div>';
                    echo '</div>';
                    echo '</div><br/>';
                } else{
                    echo '<br/>';
                }
                ?>
                <?php
                }
                if($child_count) {
                ?>
                <hr>
                <div class="row kidGuestHeader">Children</div>
                <?php
                }
                for($i=0;$i<$child_count;$i++){
                ?>
                    <div class="row guestHeaderArea">
                        <div class="col-xs-2 guestHeader">Child<?php echo ($i+1); ?></div>
                    </div>
                    <div class="row guestNameInputArea">
                        <div class="col-xs-3">
                            <select class="titleSelect traveller_title guestField" id="not_selected" name="childTitleSelect-<?php echo $i; ?>">
                                <option value="title">Title</option>
                                <option value="Miss">Miss</option>
                                <option value="Master">Master</option>
                            </select>
                            <small class="help-block" style="display:none;">Title is required</small>
                        </div>
                        <div class="col-xs-8 control-label">
                            <div class="form-group">
                                <input class="col-xs-8 form-control forceAlpha firstNameInput guestField" required type="text" placeholder="First Name" name="childFirstName-<?php echo $i; ?>" />
                            </div>
                        </div>
                        <div class="col-xs-8 control-label">
                            <div class="form-group">
                                <input class="col-xs-8 form-control forceAlpha lastNameInput guestField" required type="text" placeholder="Last Name" name="childLastName-<?php echo $i; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row childDobArea">
                        <div class="col-xs-8 control-label">
                            <div class="form-group">
                                <input type="text" class="col-xs-8 dobInput form-control guestField" name="childDob-<?php echo $i; ?>" id="datepicker-<?php echo $i; ?>" placeholder="Date of Birth">
                            </div>
                        </div>
                    </div><br/>
                <?php
                }
                ?>
                    <input type="hidden" name="activitySubTypeId" value="<?php echo $activity_sub_type_id; ?>" />
                    <input type="hidden" name="adultCount" value="<?php echo $adult_count; ?>" />
                    <input type="hidden" name="childCount" value="<?php echo $child_count; ?>" />
                    <input type="hidden" name="bookingAmount" value="<?php echo $_SESSION['activity_final_fare']; ?>" />
                    <input type="hidden" name="bookingDate" value="<?php echo $booking_date; ?>" />
            </form>

        <div class="col-xs-24 col-sm-6 col-md-6 bookingDetailsContainer">
            <div class="row tripSummary center-align-text">Trip Summary</div>
            <div class="row">
                <div class="col-xs-24 col-sm-24 col-md-24 bookingSelectionDetails">
                    <table class="table table-condensed table-custom" >
                        <tbody>
                            <tr>
                                <td>Activity Details</td>
                                <td class="right-text tf-cng">
                                    <div class="row row-padding"><?php echo $activity_name; ?></div>
                                    <div class="row row-padding activityLocaltionName"><?php echo $activity_location_name; ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>Rating</td>
                                <td class='avgRatingsList right-text'>
                                    <?php
                                    for($i=0;$i<$activity_avg_rating;$i++){
                                        echo '<li><span class="glyphicon glyphicon-star"></span></li>';
                                    }
                                    for($i=0;$i<(5-$activity_avg_rating);$i++){
                                        echo '<li><span class="glyphicon glyphicon-star-empty"></span></li>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>No.of Passengers</td>
                                <td class="right-text tf-cng">
                                    <?php 
                                        if( $adult_count ){
                                            if( $adult_count == 1 )
                                                echo $adult_count." Adult";
                                            else
                                                echo $adult_count." Adults";
                                        } 
                                        if( $child_count ){ 
                                            if( $child_count == 1 )
                                                echo ", ".$child_count." Child";
                                            else
                                                echo ", ".$child_count." Children";
                                        } 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Booking Amount</td>
                                <td class="right-text tf-cng">&#x20B9; <?php echo number_format($booking_amount, 2); ?></td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td class="right-text tf-cng discount-value">&#x20B9; <span>0</span></td>
                            </tr>
                            <tr>
                                <td>Convenience Charge <span class="glyphicon glyphicon-question-sign convenienceMsg searchHelp"></span></td>
                                <td class="right-text tf-cng discount-value">&#x20B9; <?php echo $cc;?></td>
                            </tr>
                            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                            <tr>
                                <td>Total Fare <span class="glyphicon glyphicon-question-sign totalNote"></span> </td>
                                <td class="right-text tf-cng"><div class="row totFare row-padding"><strike>&#x20B9; <?php echo $total_activity_fare; ?></strike></div><div class="row finalFare row-padding">&#x20B9; <span><?php echo $total_activity_fare; ?></span></div></td>
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
                <div class="col-xs-14">
                    <div class="row">
                        <div class="row xheight terms-error">
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
		<div class="payu-image-container">
            <img class="payu-image pull-right" src="<?php echo base_url('img/payu_cert.png'); ?>" />
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery.fn.ForceNumericOnly = function(){
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            if( $(this).val().length >= 17){
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

var pattern = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;

var discountBtnFlag = 0;
(function(){

        // login group
        var login = new Object;
        var user = new Object;

        $('#guest_reg_submit').on('click', function(){
            login.guest_email = $('input[name=guest_email]').val();
            login.isAjax = 1;
			$('#guest_reg_submit').attr('disabled','true');
			$('#login_user_submit').attr('disabled','true');

            if((login.guest_email == null || login.guest_email == "")){
                $('input[name=guest_email]').animate({'border-color': '#f00'}, 100);
                $('.loginMessageBlock .errorMessage').html('Please enter an E-Mail address first.').fadeIn(200);
				$('#guest_reg_submit').removeAttr('disabled');
				$('#login_user_submit').removeAttr('disabled');
                setTimeout(function(){
                    $('.loginMessageBlock .errorMessage').fadeOut(200);
                    $('input[name=guest_email]').animate({'border-color': '#27ae60'}, 100);
                }, 3000);
                return false;
            }else{
                if( !pattern.test($('input[name=guest_email]').val()) ){
                    $('.loginMessageBlock .errorMessage').html('Please enter a Valid E-Mail address.').fadeIn(200);
					$('#guest_reg_submit').removeAttr('disabled');
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
						$('#guest_reg_submit').removeAttr('disabled');
						$('#login_user_submit').removeAttr('disabled');
                        if( retObj ){
                            $('.loginMessageBlock .successMessage').html("Logged in successfully!").fadeIn(200);
                            setTimeout(function(){
                                $('.loginMessageBlock .successMessage').fadeOut(200);
                                $('#login_modal').modal('hide');
                            }, 3000);
                            var baseUrl = "<?php echo base_url('index.php/user')?>";
                            var logoutUrl = "<?php echo base_url('index.php/flights')?>";
                            $('.ticket_header').append('<li class="userWelcome userLink"><a href="javascript:void(0);"><span class="glyphicon glyphicon-user"></span>&nbsp&nbspHi Guest</a></li><li class="userWelcome"><a href="'+logoutUrl+'"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbspLogout</a></li>');
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

        $('strike').hide();

        /***select or die*******/
        $('.titleSelect').selectOrDie({
            placeholderOption: true,
            onChange: function(){
                this.id = 'selected';
                $(this).parent().siblings('small.help-block').hide();
                $(this).parent('span').css('border-color', '#27ae60');
            },
        });

        $.datepicker.setDefaults({
            dateFormat: "dd-mm-yy"
        });
        
        $(".citySelect").removeAttr('disabled');
        
        <?php if( !empty($child_count) ): ?>
            var childCount = <?php echo $child_count; ?>;
        <?php else:?>
            var childCount = <?php echo 0; ?>;
        <?php endif;?>
        
        for(var i=0;i<childCount;i++){
            $( "#datepicker-"+i ).datepicker({
                changeYear: true,
                changeMonth: true,
                minDate: "-12Y",
                maxDate: 0,
                onSelect: function(){
                    var sm = $('#guestDetailsForm').data('bootstrapValidator');
                    sm.updateStatus($(this), 'VALID');
                }
            });
        }

    //adding dynamic fields into bv for validation

    $('#guestDetailsForm').bootstrapValidator({
        live: "disabled",
    });

    var travellerForm = $('#guestDetailsForm');
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
                            message: "First Name is required."
                        }
                    }
                });
            break;
            case 'Last Name':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "Last Name is required."
                        }
                    }
                });
            break;
            case 'Date of Birth':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "Date of Birth is required."
                        },
                        numeric:{
                            message: "Please Enter a Valid Date of Birth."
                        }
                    }
                });
            break;
            case 'Mobile Number':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "Mobile Number is required."
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
                            message: "Email Id is required."
                        },
                        emailAddress:{
                            message: "Please Enter a Valid Email Id."
                        }
                    }
                });
            break;
            case 'Address':
                travellerFormBv.addField(key, {
                    validators:{
                        notEmpty:{
                            message: "Address is required."
                        }
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

    //allow only numeric keys for certain fields
    $('.forceNumeric').ForceNumericOnly();
    //allow only numeric keys for certain fields end

    //allow only alphabetic keys begin
    $('.forceAlpha').ForceAlphaOnly();
    //allow only alphabetic keys end


    //adding dynamic fields into bv for validation end

    var lgd_in = "<?php echo $_SESSION['login_status'];?>";

    $(".activitiesCheckoutBtn").click(function(e){
        e.preventDefault();
        if( $('input[name=tnc]').is(":not(:checked)") ){
            $('input[name=tnc]').parent('label').css('color', '#f00');
            $('.discountMessageBlock .errorMessage').html('Please accept the Terms and Conditions before checkout').fadeIn(200);
            setTimeout(function(){
                $('input[name=tnc]').parent('label').css('color', '#000');
                $('.discountMessageBlock .errorMessage').fadeOut(200);
            }, 3000);
        }
        var not_selected_flag = 0;
        var all_selects = $(document).find('select.traveller_title');
        $.each( all_selects, function(i, val){
            if( val.id === 'not_selected' ){
                not_selected_flag = 1;
            }
        });

        if( not_selected_flag ){
            $.each( all_selects, function(i, val){
                if( val.id === 'not_selected' ){
                    $(val).parent().siblings('small.help-block').show();
                    $(val).parent('span').css('border-color', '#f00');
                    $('#guestDetailsForm').data('bootstrapValidator').validate();
                }
            });
        }else{
            $('form#guestDetailsForm').data('bootstrapValidator').validate();            
            if( $('form#guestDetailsForm').data('bootstrapValidator').isValid() ){
                $('form#guestDetailsForm').trigger('travelFormSubmitted');
            }else{
                return false;
            }
        }
    });

    $('form#guestDetailsForm').on('travelFormSubmitted', function(){
        this.submit();
    });

//discount section

    var discountClick = 0;
    var total_fare = parseFloat("<?php echo $total_activity_fare;?>");
    var discounted_val = 0;

    $('.finalFare span, .TotFare1 span, .discount-value span').autoNumeric({
        aSep: ',',
        dGroup: 2,
    });

    $(".apply-btn").click(function(){

        var discountCode = $('.dis-code').val();
        var discountModule = "activities";

        if( discountCode === "" || discountCode === null ){
            $('.discountMessageBlock .errorMessage').html('Please enter a valid Code').fadeIn(200);
            setTimeout(function(){
                $('.discountMessageBlock .errorMessage').fadeOut(200);
            }, 3000);
        }else{
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('flights/apply_discount_code');?>",
                data: { discountCode: discountCode, discountModule: discountModule, total_fare:total_fare }
            })
            .done(function(data){
                if(data === "Failure"){
                    $('.discountMessageBlock .errorMessage').html('The discount code you have entered is Invalid').fadeIn(200);
                    setTimeout(function(){
                        $('.discountMessageBlock .errorMessage').fadeOut(200);
                    }, 3000);
                }else{
                    var data = $.parseJSON(data);
                    $('.cancel-btn-row').show();
                    $('.input-group-row').hide();
                    discountClick++;
                    $('input#discountCode').val(data.code);
                    $('input#discountValue').val(data.value);
                    $('strike').slideDown();
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
                        message: 'The Password must be a minimum of 4 Characters long.'
                    }
                },
            },
            confirm_password: {
                validators: {
                    notEmpty: {
                        message: 'The Confirm Password is required'
                    },
                    stringLength: {
                        min: 4,
                        message: 'TPassword must be a minimum of 4 Characters long.'
                    },
                    identical: {
                        field: 'password',
                        message: 'Password Mismatch.'
                    },
                },
            }
        }
    });

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