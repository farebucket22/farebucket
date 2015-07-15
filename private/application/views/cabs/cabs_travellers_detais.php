<script type="text/javascript"> 

function stopRKey(evt) { 
    var evt = (evt) ? evt : ((event) ? event : null); 
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);     
    if ((evt.keyCode == 13 && evt.target.nodeName !== "TEXTAREA" )) {return false;}
} 

document.onkeypress = stopRKey; 

</script>
<?php
    @session_start();  
    if( !empty($data['pax_info']->compact) ){$compacts = count($data['pax_info']->compact);}else{$compacts = 0;}
    if( !empty($data['pax_info']->sedan) ){$sedans = count($data['pax_info']->sedan);}else{$sedans = 0;}
    if( !empty($data['pax_info']->suv) ){$suvs = count($data['pax_info']->suv);}else{$suvs = 0;}
    $total_fare = $data['total_fare'];
    $convenience_charge_msg = $data['convenience_charge_msg'];
    $convenience_charge = ($data['convenience_charge']/100)*$total_fare;
    $total_fare = $convenience_charge + $total_fare;
    $_SESSION['cab_total_fare'] = $total_fare;
    $compactMinSlab = 0;
    $sedanMinSlab = 0;
    $suvMinSlab = 0;
    //calculation of Min Slab.

    if( $compacts != 0 && $data['cab_type'] == 'local'){
        $minKey = key($data['extra_info']->XKm_Charges);
        for( $c = 0 ; $c < $compacts ; $c++ ){
            $compactMinSlab += intval($data['destination']) * intval($data['extra_info']->XKm_Charges[$minKey]) * 10;
        }

    }

    if( $sedans != 0 && $data['cab_type'] == 'local'){
        //check if compact is present.
        if( reset($data['extra_info']->CarTypeID) == 6 ){
            $minKey = key($data['extra_info']->XKm_Charges);
        }else{
            $minKey = key($data['extra_info']->XKm_Charges) + 1;
        }
        for( $s = 0 ; $s < $sedans ; $s++ ){
            $sedanMinSlab += intval($data['destination']) * intval($data['extra_info']->XKm_Charges[$minKey]) * 10;
        }
    }

    if( $suvs != 0 && $data['cab_type'] == 'local'){
        if( reset($data['extra_info']->CarTypeID) == 6 ){
            $minKey = key($data['extra_info']->XKm_Charges) + 1;
        }else{
            $minKey = key($data['extra_info']->XKm_Charges) + 2;
        }
        for( $su = 0 ; $su < $suvs ; $su++ ){
            $suvMinSlab += intval($data['destination']) * intval($data['extra_info']->XKm_Charges[$minKey]) * 10;
        }
    }
    
    $cabMinSlab = array(
        'compactMinSlab' => $compactMinSlab,
        'sedanMinSlab' => $sedanMinSlab,
        'suvMinSlab' => $suvMinSlab
    );
    $_SESSION['cabMinSlab'] = json_encode($cabMinSlab);
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
    .fl_overwiew .ellipse{
        height:22px;
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

    .cab_src, .cab_dest{
        font-family: "Oswald";
        font-size: 12px;
    }

    .fl_no{
        font-family: "Oswald";
        padding-top: 7px;
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

    small.help-block{
        color: #ff0000;
    }

    .date-text{
        padding-top: 15px;
        font-size: 12px;
    }

    .cab-info{
        margin-top: 15px;
    }

    .cab-info p{
        margin-top: -5px;
    }

    .per-cab-fare{
        margin-top: 10px;
    }
    .pickupControl{
        border:none;
        margin:5px;
        font-weight: 600;
        font-size: 13px;
        font-family: "Oswald", sans-serif;
    }
    .hoursMins{
        text-align: left;
        margin-top:0;
    }
    .pickupdate{
        margin-bottom: 0;
    }
    .cabTotFare1{
        padding-top: 0;
    }
    .sod_select{
        text-transform: none;
        margin-top: 0;
    }
    .paxTitle .sod_select{
        margin-top: 4px;
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
                                    <select class="selectpicker control-label add-on-table" name="title_user"><option value="" >Title</option><option>Miss</option><option>Mr</option><option>Mrs</option></select>
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

<div class="hidden-fields">
    <div id="popoverHiddenContent" style="display: none">
    
    <?php if( count($data['pax_info']->compact) ): for( $i = 1 ; $i <= count($data['pax_info']->compact) ; $i++ ):?>
        <div class="row center-align-text fl_row">
        <!-- first section -->
            <div class="col-lg-8 date-text">Compact - <?php echo $i;?></div>
            <div class="col-lg-8 remove-padding">
                <div class="row travel-text">
                    <div class="col-lg-12 remove-padding cab-info"><?php echo $data['extra_info']->car_model[0];?></div>
                    <div class="col-lg-12 remove-padding cab-info"><p>Pax:</p><span><?php echo $data['pax_info']->compact[$i-1];?></span></div>
                </div>
            </div>
            <div class="col-lg-8 per-cab-fare"><p>Per cab:</p>&#x20B9; <span><?php echo $data['extra_info']->total_fare[0];?></span></div>
        </div>
        <div class="row center-align-text">
        <!-- layover -->
        <div class="col-lg-24 remove-padding grey-line"></div>
        </div>
    <?php endfor; endif;?>

    <?php if( count($data['pax_info']->sedan) ): for( $i = 1 ; $i <= count($data['pax_info']->sedan) ; $i++ ):?>
        <div class="row center-align-text fl_row">
            <!-- first section -->
            <div class="col-lg-8 date-text">Sedan - <?php echo $i;?></div>
            <div class="col-lg-8 remove-padding">
                <div class="row travel-text">
                    <div class="col-lg-12 remove-padding cab-info"><?php if($data['extra_info']->car_model[0] == 'Indigo'){echo $data['extra_info']->car_model[0];}else{echo $data['extra_info']->car_model[1];}?></div>
                    <div class="col-lg-12 remove-padding cab-info"><p>Pax:</p><span><?php if(count($data['pax_info']->sedan) > 0){echo $data['pax_info']->sedan[$i-1];}else{echo $data['pax_info']->compact[$i-1];}?></span></div>
                </div>
            </div>
            <div class="col-lg-8 per-cab-fare"><p>Per cab:</p>&#x20B9; <span><?php if($data['extra_info']->car_model[0] == 'Indigo'){echo $data['extra_info']->total_fare[0];}else{echo $data['extra_info']->total_fare[1];}?></span></div>
        </div>
        <div class="row center-align-text">
            <!-- layover -->
            <div class="col-lg-24 remove-padding grey-line"></div>
        </div>
    <?php endfor; endif;?>

    <?php if( count($data['pax_info']->suv) ): for( $i = 1 ; $i <= count($data['pax_info']->suv) ; $i++ ):?>
        <div class="row center-align-text fl_row">
            <!-- first section -->
            <div class="col-lg-8 date-text">MUV/SUV - <?php echo $i;?></div>
            <div class="col-lg-8 remove-padding">
                <div class="row travel-text">
                    <div class="col-lg-12 remove-padding cab-info"><?php if($data['extra_info']->car_model[1] != 'Indigo'){echo $data['extra_info']->car_model[1];}else{echo $data['extra_info']->car_model[2];}?></div>
                    <div class="col-lg-12 remove-padding cab-info"><p>Pax:</p><span><?php if(count($data['pax_info']->suv) > 0){echo $data['pax_info']->suv[$i-1];}else{echo $data['pax_info']->sedan[$i-1];}?></span></div>
                </div>
            </div>
            <div class="col-lg-8 per-cab-fare"><p>Per cab:</p>&#x20B9; <span><?php if($data['extra_info']->car_model[1] != 'Indigo'){echo $data['extra_info']->total_fare[1];}else{echo $data['extra_info']->total_fare[2];}?></span></div>
        </div>
        <?php if( $i != $suvs ): ?>
            <div class="row center-align-text">
                <!-- layover -->
                <div class="col-lg-24 remove-padding grey-line"></div>
            </div>
        <?php endif;?>
    <?php endfor; endif;?>


    <div id="popoverHiddenTitle" style="display: none;">
        <div class="row">
            <div class="col-lg-12 pull-right">
                <div class="col-lg-offset-6 col-lg-4 img-cal">
                    <img src="<?php echo base_url('img/calendar_icon.png');?>" alt="jA" width='18px' />
                </div>
                <div class="col-lg-14 pop-title-text right-text"><?php echo date('D, jS M Y', strtotime($data['to_date']));?></div>
            </div>
        </div>
    </div> 

</div>

<div class="wrap">
    <div class="container-fluid main clear-top">
        <nav class="navbar fixed-top" style="margin-top:0.5%; box-shadow: 0 1px 2px rgba(0,0,0,.05);">
            <div  class="container-fluid" id="info">
                <div class="row fl_overwiew">
                    <div class="col-lg-5 fl_septr1">
                        <div class="col-lg-2 fl_no">1</div>
                        <div class="col-lg-3 cab_bg_nav"></div>
                        <div class="col-lg-14 fl_info">
                            <div class="row">
                                <div class="col-lg-offset-4 col-lg-18 travel-text">
                                    <div class="row center-align-text">
                                        <div class="col-lg-11 remove-padding cab_src ellipse" id="origin" data-original-title title><?php echo $data['source'];?></div>
                                        <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                        <div class="col-lg-11 remove-padding cab_dest ellipse" id="destination"><?php if($_SESSION['travel_id'] == 2) echo $data['destination']; else echo $data['destination'].'0kms/'.$data['destination'].'hrs';?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date('D, jS M Y', strtotime($data['to_date']));?></div>
                            </div> 
                            <div class="row">
                                <div class="col-lg-23 col-lg-offset-1 cabTotFare1 center-align-text"> &#x20B9; <span><?php echo $total_fare;?></span></div>
                            </div>
                        </div>
                        <div class="col-lg-4 pull-right">
                            <div class="row fl_btn1">
                                <div class="link-btn-de">
                                    <a href="#" tabindex="0" class='btn-de' id='popover-toggle' data-toggle="popover" data-placement="bottom">DETAILS</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="row" style="margin-top: 35px;">
            <div class="col-xs-24 col-md-offset-2 col-lg-offset-2 col-sm-14 col-md-14 col-lg-14">
                <form action="<?php echo site_url('cabs/payment_gateway')?>" method="post" id="cab_traveller_details_form">
                    <div class="row traveller-section">
                        <div class="col-lg-24 col md-24">
                            <h4>Lead Traveller Details:</h4>
                            <div class="row">
                                <div class="form-group paxTitle col-xs-24 col-sm-4 col-md-3 col-lg-3">
                                    <select name="pax_title" id="not_selected" class="pax_title traveller_title form-padding">
                                        <option>Title</option>
                                        <option value="master">Master</option>
                                        <option value="mr">Mr</option>
                                        <option value="mrs">Mrs</option>
                                        <option value="ms">Miss</option>
                                    </select>
                                </div>
                                <div class="form-group form-padding col-xs-24 col-sm-10 col-md-8 col-lg-8">
                                    <input type="text" class="form-control forceAlpha" name="first_name" placeholder="First Name"/>
                                </div>
                                <div class="form-group form-padding col-xs-24 col-sm-10 col-md-8 col-lg-8">
                                    <input type="text" class="form-control forceAlpha" name="last_name" placeholder="Last Name"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-padding col-xs-24 col-sm-8 col-md-10 col-lg-10">
                                    <textarea rows='5' cols='30' class="form-control cab_txt_area" name="pickup_addr" placeholder="Pickup Address"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-padding col-xs-24 col-sm-8 col-md-10 col-lg-10">
                                    <textarea rows='5' cols='30' class="form-control cab_txt_area" name="drop_addr" placeholder="Drop Address"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-padding col-xs-2 col-sm-1 col-md-2 col-lg-2 ">
                                    <input type="text" value="+91" class="small-input-prefix forceNumeric"/>
                                </div>
                                <div class="form-group form-padding col-xs-24 col-sm-8 col-md-8 col-lg-8">
                                    <input type="text" class="form-control forceNumeric mobileNumber" name="Phone_num" placeholder="Phone Number"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-padding col-xs-24 col-sm-8 col-md-10 col-lg-10">
                                    <input type="text" class="form-control" name="Email" placeholder="Email ID"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-padding col-xs-2 col-sm-1 col-md-2 col-lg-12">
                                    <label class="pickupControl">Pickup Date / Time</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="inner-addon right-addon">
                                        <i class="glyphicon"></i>
                                        <input name="pickupDate" id="date-1" readonly class="form-control form-padding pickupdate" type="text" placeholder="Depart Date" value="<?php if( isset($data['to_date']) ){echo $data['to_date'];}else{echo '';}?>">
                                    </div>
                                </div>
                                <div class="form-group form-padding col-xs-2 col-sm-1 col-md-2 col-lg-3"> 
                                    <select name="timeHours" id="not_selected" class="timeHours form-padding small-input-prefix forceNumeric hoursMins">
                                        <option>Hours</option>
                                        <?php for($i=0;$i<24;$i++):?>
                                                <option value="<?php echo $i; ?>"><?php $j = sprintf("%02d",$i); echo $j ?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                                <div class="form-group form-padding col-xs-2 col-sm-1 col-md-2 col-lg-3"> 
                                    <select name="timeMins" id="not_selected" class="timeMins form-padding small-input-prefix forceNumeric hoursMins">
                                        <option>Minutes</option>
                                        <?php for($i=5;$i<56;$i+=5):?>
                                                <option><?php $j = sprintf("%02d",$i); echo $j ?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                            </div>       
                        </div>
                    </div>
                    <input name='key' type='text' value='gtKFFx' style="display:none;"/>
                    <input type="text" name="extra_info" id="extra_info" style="display:none;"/>
                    <input type="text" name="pax_info" id="pax_info" style="display:none;"/>
                    <input type="text" name="compacts" style="display:none;" value="<?php echo $compacts;?>"/>
                    <input type="text" name="sedans" style="display:none;" value="<?php echo $sedans;?>"/>
                    <input type="text" name="suvs" style="display:none;" value="<?php echo $suvs;?>"/>
                    <input type="text" name="stateID" style="display:none;" value="<?php echo $data['state_id'];?>"/>
                    <input type="text" name="cityID" style="display:none;" value="<?php echo $data['city_id'];?>"/>
                    <input type="text" name="productinfo" style="display:none;" value="cab to <?php echo $data['city_id'];?>"/>
                    <button type="button" style="display:none" id="cab_traveller_details_form_submit">Submit</button>
                </form>
            </div>
            <div class="col-xs-24 col-sm-6 col-md-6 bookingDetailsContainer">
                <div class="row bookingSelectionDetails tripSummary center-align-text">Trip Summary</div>
                <div class="row">
                    <div class="col-xs-24 col-sm-24 col-md-24 bookingSelectionDetails">
                        <table class="table table-condensed table-custom" >
                            <tbody>
                                <tr>
                                    <td>Journey Date:</td>
                                    <td class="right-text tf-cng base-fare"><?php echo date('D, jS M Y', strtotime($data['to_date']));?></td>
                                </tr>
                                <tr>
                                    <td>Base fare</td>
                                    <td class="right-text tf-cng base-fare">&#x20B9; <span><?php echo $data['total_fare'];?></span></td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="right-text tf-cng discount-value">&#x20B9; <span>0</span></td>
                                </tr>
                                <tr>
                                    <td>Convenience Charge <span class="glyphicon glyphicon-question-sign convenienceMsg searchHelp"></span></td>
                                    <td class="right-text tf-cng convenience_charge">&#x20B9; <span><?php echo $convenience_charge;?><span></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr>
                                    <td>Total Fare</td>
                                    <td class="right-text tf-cng"><div class="row totFare"><strike>&#x20B9; <span><?php echo $total_fare;?></span></strike></div><div class="row finalFare">&#x20B9; <span><?php echo $total_fare;?></span></div></td>
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
<script type='text/javascript'>

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

        var src = "<?php echo $data['source'];?>";
        var destination = "<?php if($_SESSION['travel_id'] == 2) echo $data['destination']; else echo $data['destination'].'0kms/'.$data['destination'].'hrs';?>";
        
        $('#origin').on('mouseenter', function(){
            $(this).tooltip({
                placement: 'bottom',
                trigger: 'hover',
                title: src
            });
            $(this).tooltip('show');
        });

        $('#destination').on('mouseenter', function(){
            $(this).tooltip({
                placement: 'bottom',
                trigger: 'hover',
                title: destination
            });
            $(this).tooltip('show');
        });


        //autoNumeric
        $('.finalFare span, .totFare span, .cabTotFare1 span, .per-cab-fare span, .convenience_charge span, .base-fare span').autoNumeric({
            aSep: ',',
            dGroup: 2
        });
        //autoNumeric end

        //allow only numeric keys for certain fields
        $('.forceNumeric').ForceNumericOnly();
        //allow only numeric keys for certain fields end

        //allow only alphabetic keys begin
        $('.forceAlpha').ForceAlphaOnly();
        //allow only alphabetic keys end

        //popover section
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
        //popover section end

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

/***select or die*******/
$('.pax_title').selectOrDie({
    placeholderOption: true,
    onChange: function(){
        this.id = 'selected';
        $(this).parent().siblings('small.help-block').hide();
        $(this).parent('span').css('border-color', '#27ae60');
    },
});

$('.timeHours').selectOrDie({
    placeholderOption: true,
    size: 10,
    onChange: function(){
        this.id = 'selected';
        $(this).parent().siblings('small.help-block').hide();
        $(this).parent('span').css('border-color', '#27ae60');
    },
});

$('.timeMins').selectOrDie({
    placeholderOption: true,
    size: 10,
    onChange: function(){
        this.id = 'selected';
        $(this).parent().siblings('small.help-block').hide();
        $(this).parent('span').css('border-color', '#27ae60');
    },
});

var extra_info = JSON.stringify(<?php echo json_encode($data['extra_info']);?>);
var pax_info = JSON.stringify(<?php echo json_encode($data['pax_info']);?>);

$('#extra_info').val(extra_info);
$('#pax_info').val(pax_info);

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
        $('#cab_traveller_details_form_submit').click();
    });

// validation
        $('#cab_traveller_details_form').bootstrapValidator({
            live: 'disabled',
            fields: {
                'pax_title': {
                    validators: {
                        notEmpty: {
                            message: 'Title is required'
                        }
                    }
                },
                'first_name': {
                    validators: {
                        notEmpty: {
                            message: 'First Name is required'
                        }
                    }
                },
                'last_name': {
                    validators: {
                        notEmpty: {
                            message: 'Last Name is required'
                        }
                    }
                },
                'pickup_addr': {
                    validators: {
                        notEmpty: {
                            message: 'Pickup Address is required'
                        }
                    }
                },
                'drop_addr': {
                    validators: {
                        notEmpty: {
                            message: 'Drop Address is required'
                        }
                    }
                },
                'Phone_num': {
                    validators: {
                        notEmpty: {
                            message: 'Phone number is required'
                        },
                        numeric: {
                            message: 'Please enter a valid Phone number.'
                        }
                    }
                },
                'Email': {
                    validators: {
                        notEmpty: {
                            message: 'Email ID is required'
                        },
                        emailAddress: {
                            message: 'Please enter a valid Email ID.'
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
                    }
                },
                stringLength: {
                    min: 4,
                    message: 'Password must be a minimum of 4 Characters long.'
                }
            },
            confirm_password: {
                validators: {
                    notEmpty: {
                        message: 'Confirm Password is required'
                    }
                },
                identical: {
                    field: 'password',
                    message: 'Password Mismatch.'
                }
            }
        }
    });

    $('#cab_traveller_details_form_submit').on('click', function(e){
        e.preventDefault();
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
                    $('#cab_traveller_details_form').data('bootstrapValidator').validate();
                }
            });
        }else{
            // $('#travellers-form input[name=details]').val(JSON.stringify(temp_array));
            $('#cab_traveller_details_form').data('bootstrapValidator').validate();
            if( $('#cab_traveller_details_form').data('bootstrapValidator').isValid() ){
                $('#cab_traveller_details_form').trigger('travelFormSubmitted');
            }else{
                return false;
            }
        }
    });

    $('#cab_traveller_details_form').on('travelFormSubmitted', function(){
        $('.mobileNumber').val($('.small-input-prefix').val());
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

// validation end

$('strike').hide();

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
    var total_fare = parseInt("<?php echo $total_fare;?>");
    var discounted_val = 0;

    $('.finalFare span, .TotFare1 span, .discount-value span').autoNumeric({
        aSep: ',',
        dGroup: 2,
    });

    $(".apply-btn").click(function(){

        var discountCode = $('.dis-code').val();
        var discountModule = "cabs";

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