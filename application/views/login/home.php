<head>
    <title>Farebucket: Chennai Cab Booking Services Online | Tour Operators Chennai</title>
    <meta name="Subject" content="Chennai Cab Booking Services Online" />
    <meta name="description" content="Farebucket take care of the minutest detail of the itinerary without springing any last minute surprises. Chennai Cab Booking Services Online | Tour Operators Chennai " />
    <meta name="keywords" content="Cheap Cabs In Bangalore,Cheap Cabs In Chennai,Cheap Domestic Air Tickets,Book Cheap Air Tickets India,Online Cab Booking Chennai,Online Cab Booking Bangalore ,Chennai Cab Booking Services Online,Cab Services In Chennai,Tour Operators Chennai,Travel Agents In Chennai" />
    <meta name="Language" content="English" />
    <meta name="Distribution" content="Global" />
    <meta name="Robots" content="All" />
    <meta name="Revisit-After" content="7 Days" />
</head>
<style type="text/css">
    .selectpicker{
        padding-left: 6px;
    }
</style>
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

<div class="wrap">
    <div class="container-fluid main clear-top">
        <div class="row">
            <div class="col-xs-24 col-sm-12 userRegisterContainer">
                <div class="row">
                    <form id="form-1" class="col-xs-16 col-xs-offset-4 userRegistrationForm" action="<?php echo base_url('index.php/login/register_user'); ?>" method="POST">
                        <div class="col-xs-24 userRegisterErrorMessage"></div>
                        <h4 class="col-xs-24 userRegisterHeader loginFormField">Register</h4>
                        <?php if( isset($message) && $message == "An Account with this E-Mail ID already exists. Please register with another E-Mail ID." ):?>
                            <div class="row error-text">
                                <div class="col-xs-24 has-error">
                                    <center><b><h5 class="help-block">An Account with this E-Mail ID already exists. Please register with another E-Mail ID.</h5></b></center>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if( isset($message) && $message == "You have been registered successfully." ):?>
                            <div class="row error-text">
                                <div class="col-xs-24 has-success">
                                    <center><b><h5 class="help-block">You have been registered successfully.</h5></b></center>
                                </div>
                            </div>
                        <?php endif;?>
                        <div class="col-xs-4 col-xs-offset-5 title_user remove-padding">
                            <div class="form-group title_error">
                                <select class="selectpicker control-label add-on-table" name="title_user"><option value="" disabled selected>Title</option><option>Miss</option><option>Mr</option><option>Mrs</option></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-8 col-xs-offset-5 control-label remove-padding">
                                <input class="userRegisterFirstName loginFormField form-control forceAlpha" type="text" name="firstName" placeholder="First Name" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-8 col-xs-offset-1 control-label remove-padding"> 
                                <input class="userRegisterLastName loginFormField form-control forceAlpha" type="text" name="lastName" placeholder="Last Name" />
                            </div>
                        </div>
                        <div class="col-xs-10 col-xs-offset-5 remove-padding">
                            <div class="form-group gender_error pull-left">
                                <div class="btn-group remove-padding control-label" id="passenger-gender" data-toggle="buttons">
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
                            <div class="birth_date col-xs-14 col-xs-offset-5 control-label inner-addon right-addon remove-padding">
                                <i class="glyphicon"></i>
                                <input name="dob" id="date-1" readonly class="form-control" type="text" placeholder="Birthday Date" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-14 col-xs-offset-5 control-label remove-padding">
                                <input class="userRegisterEmail registerEmail loginFormField form-control" type="text" name="email" placeholder="Email" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-14 col-xs-offset-5 control-label remove-padding">
                                <input class="userRegisterEmail loginFormField form-control forceNumeric" type="text" name="phone_no" placeholder="Phone Number" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-14 col-xs-offset-5 control-label remove-padding">
                                <input class="userRegisterPassword loginFormField form-control forceLimit" type="password" name="password" placeholder="Password" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-14 col-xs-offset-5 control-label remove-padding">
                                <input class="userRegisterConfirmPassword loginFormField form-control" type="password" name="confirm_password" placeholder="Confirm Password" />
                            </div>
                        </div>
                        <button type="submit" class="col-xs-9 col-xs-offset-5 loginFormField userRegisterBtn"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Register</button>
                    </form>
                </div>
            </div>
            <div class="col-xs-24 col-sm-12 userLoginContainer">
                <div class="row">
                    <form id ="form-2" class="col-xs-16 col-xs-offset-4 userLoginForm" action="<?php echo base_url('index.php/login/login_user'); ?>" method="POST">
                        <h4 class="col-xs-24 userLoginHeader loginFormField">Login</h4>
                        <div class="form-group">
                            <div class="col-xs-14 col-xs-offset-5 control-label remove-padding">
                                <input class="userLoginEmail loginFormField form-control" name="userLoginEmail" type="email" placeholder="Email" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-14 col-xs-offset-5 control-label remove-padding">
                                <input class="userLoginPassword loginFormField form-control" name="userLoginPassword" type="password" placeholder="Password" />
                            </div>
                        </div>
                        <button type="submit" class="col-xs-9 col-xs-offset-5 loginFormField userLoginBtn"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Login</button>
                    </form>
                </div>
                <div class="col-lg-offset-7 col-lg-12 row reg_link">
                    <a href="#" id="forgot_pass">Forgot Password</a>
                </div>
                <?php if( isset($message) && $message == "Email Id or Password incorrect. Please try again." ):?>
                    <div class="row error-text">
                        <div class="col-xs-16 col-xs-offset-7 has-error">
                            <h6 class="help-block">Email Id or Password incorrect. Please try again.</h6>
                        </div>
                    </div>
                <?php endif;?>
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
			if( $(this).val().length >= 17 ){
				if( e.keyCode === 9 || e.keyCode === 8 ){
					return e.keyCode;
				}
				else
					return false;
			}
			var key = e.charCode || e.keyCode || 0;
			// allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
			// home, end, period, and numpad decimal
			return (key == 8 || key == 9 || key == 13 || key == 46 || key == 110 || key == 107 || key == 109 || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
		});
	});
};

jQuery.fn.ForceAlphaOnly = function(){
	return this.each(function()
	{
		$(this).keydown(function(e)
		{
			if( $(this).val().length >= 50){
				if( e.keyCode === 9 || e.keyCode === 8 ){
					return e.keyCode;
				}
				else
					return false;
			}
			var key = e.charCode || e.keyCode || 0;
			// allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
			// home, end, period, and numpad decimal
            return (key == 8 || key == 9 || key == 13 || key == 46 || key == 190 || (key >= 65 && key <=90 ));
		});
	});
};
jQuery.fn.ForceLimitOnly = function(){
	return this.each(function()
	{
		$(this).keydown(function(e)
		{
			if( $(this).val().length >= 20){
				if( e.keyCode === 9 || e.keyCode === 8 ){
					return e.keyCode;
				}
				else
					return false;
			}
			var key = e.charCode || e.keyCode || 0;
			// allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
			// home, end, period, and numpad decimal
            return (key);
		});
	});
};
$(document).ready(function(){

    $(document).on('change', 'input[type=radio]', function(){
        $(this).attr('id', '');
        $(this).parent().next().children('input[type=radio]').attr('id', "");
        $(this).parents('div#passenger-gender').siblings('small.help-block').hide();
    });

    var element_chk = document.getElementsByClassName('error-text');
    if( element_chk ){
        setTimeout(function(){
            $('.error-text').fadeOut();
        }, 3000);
    }

    var radio_btn = $('#passenger-gender').find(':input:radio');
    $.each(radio_btn, function(i, val){
        val.id = "not_selected";
    });
    console.log(radio_btn);

    $('#form-1').bootstrapValidator({
        live: 'disabled',
        fields: {
            title_user: {
                validators: {
                    notEmpty: {
                        message: 'The Title is required'
                    }
                }
            },
            firstName: {
                validators: {
                    notEmpty: {
                        message: 'The First Name is required'
                    },
                    stringLength: {
                        max: 50,
                        message: 'First Name must be less than 50 characters'
                    }
                }
            },
            lastName: {
                validators: {
                    notEmpty: {
                        message: 'The Last Name is required'
                    },
                    stringLength: {
                        max: 50,
                        message: 'Last Name must be less than 50 characters'
                    }
                }
            },
            gender: {
                validators: {
                    notEmpty: {
                        message: 'The Gender is required'
                    }
                }
            },
            dob: {
                validators: {
                    notEmpty: {
                        message: 'The Birthday Date is required'
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
                        message: 'The Email is required'
                    },
                    emailAddress:{
                        message: 'Please Enter a Valid Email id'
                    }
                }
            },
            phone_no: {
                validators: {
                    notEmpty: {
                        message: 'The Phone number is required.'
                    }, 
                    numeric: {
                        message: 'Please enter a Valid Phone number.'
                    },
                    stringLength: {
                        min: 10,
                        max: 20,
                        message: 'Please enter a valid phone number.'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The Password is required'
                    },
                    identical: {
                        field: 'confirm_password',
                        message: 'Password Mismatch.'
                    },
                    stringLength: {
                        min: 4,
						max: 20,
                        message: 'The Password must be between 4 and 20 characters.'
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
                        message: 'The Password must be between 4 and 20 characters.'
                    },
                    identical: {
                        field: 'password',
                        message: 'Password Mismatch.'
                    },
                },
            }
        }
    });
    
    $('#form-2').bootstrapValidator({
        live: 'disabled',
        fields: {
            userLoginEmail: {
                validators: {
                    notEmpty: {
                        message: 'The Email is required'
                    },
                    emailAddress:{
                        message: 'Please Enter a Valid Email id'
                    }
                }
            },
            userLoginPassword: {
                validators: {
                    notEmpty: {
                        message: 'The Password is required'
                    }
                }
            } 
        }
    });

    $('.userRegisterBtn').click(function(e) {

        e.preventDefault();
        var gender_not_selected_flag = 1;

        $.each(radio_btn, function(i, val){
            if( val.id == "" ){
                gender_not_selected_flag = 0;
            }
        });

        $.each(radio_btn, function(i, val){
            if( gender_not_selected_flag == 1 ){
                $(val).parents('#passenger-gender').siblings('small.help-block').show();
				$('#travellers-form').data('bootstrapValidator').validate();
            }
        });
        $('#form-1').submit();
    });
	
	$('.forceNumeric').ForceNumericOnly();
	$('.forceAlpha').ForceAlphaOnly();
	$('.forceLimit').ForceLimitOnly();
    
    $(".loginFormField").on("focus", function(){
        $(".userRegisterErrorMessage").html("");
        $(".userLoginErrorMessage").html("");
    });

     $.datepicker.setDefaults({
            dateFormat: "dd-mm-yy"
    });

    $('#date-1').datepicker({
        maxDate: '-18Y',
        changeMonth: true,
        changeYear: true,
        yearRange: '-100: -18',
        onSelect: function(){
            var sm = $('#form-1').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
        }
    });

//forgot password code

    $('#forgot_pass').on('click', function( e ){
        e.preventDefault();
        $('#login_modal').modal('hide');
        $('#forgot_pass_modal').modal({backdrop: 'static'});
        $('#forgot_pass_modal').modal('show');

        var pattern = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;

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
    }); 

});
</script>