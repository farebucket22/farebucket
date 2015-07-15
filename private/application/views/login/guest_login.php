<div class="wrap">
    <div class="container-fluid main clear-top">
        <div class="row">
            <div class="col-xs-24 col-sm-12 userRegisterContainer">
                <form class="guest_login col-xs-16 col-xs-offset-4 userRegistrationForm" action="<?php echo base_url('index.php/login/guest_register'); ?>" method="POST">
                    <div class="col-xs-24 userRegisterErrorMessage"></div>
                    <h4 class="col-xs-24 userRegisterHeader loginFormField">Guest login</h4>
                    <input class="col-xs-14 col-xs-offset-5 userRegisterEmail loginFormField" type="email" placeholder="Email" name="guest_email"/>
                    <button type="submit" class="col-xs-9 col-xs-offset-10 loginFormField userRegisterBtn"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Guest Login</button>
                </form>
            </div>
            <div class="col-xs-24 col-sm-12 userLoginContainer">
                <form class="col-xs-16 col-xs-offset-4 userLoginForm" action="<?php echo base_url('index.php/login/login_user_modal'); ?>" method="POST">
                    <?php
                    ?>
                    <div class="col-xs-24 userLoginErrorMessage"><?php if(isset($message)){echo $message;}?></div>
                    <h4 class="col-xs-24 userLoginHeader loginFormField">Login</h4>
                    <input class="col-xs-14 col-xs-offset-5 userLoginEmail loginFormField" name="userLoginEmail" type="email" placeholder="Email" />
                    <input class="col-xs-14 col-xs-offset-5 userLoginPassword loginFormField" name="userLoginPassword" type="password" placeholder="Password" />
                    <button type="submit" class="col-xs-9 col-xs-offset-10 loginFormField userLoginBtn"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('.userRegisterBtn').click(function(e) {
        e.preventDefault();
        if($(".userRegisterEmail").val() === "")
            var message = "Please enter your email id.";
        else
            var message = "success";
        if(message==="success"){
            $('.userRegistrationForm').submit();
        } else {
            $(".userRegisterErrorMessage").html(message);
        }
    });
    
    $('.userLoginBtn').click(function(e) {
        e.preventDefault();
        var message = validLogin();
        if(message==="success"){
            $('.userLoginForm').submit();
        } else {
            $(".userLoginErrorMessage").html(message);
        }
    });
    
    $(".loginFormField").on("focus", function(){
        $(".userRegisterErrorMessage").html("");
        $(".userLoginErrorMessage").html("");
    });
});
</script>