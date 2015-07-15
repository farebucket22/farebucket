<style>
.guest_ticket{
	margin-left:25%;
}
</style>
<div class="wrap">
    <div class="container-fluid main clear-top">
        <div class="row">
            <div class="col-xs-24 col-sm-12 guest_ticket userRegisterContainer">
                <form class="guest_login col-xs-16 col-xs-offset-4 userRegistrationForm" action="<?php echo site_url('user/guest_details'); ?>" method="POST">
                    <div class="col-xs-24 userRegisterErrorMessage">
                        <?php if( isset($_SESSION['invalidGuestErrorMessage']) ):?>
                            <?php echo $_SESSION['invalidGuestErrorMessage'];?>
                        <?php endif;?>
                    </div>
                    <h4 class="col-xs-24 userRegisterHeader loginFormField">Enter Your E-Mail &amp; Booking ID</h4>
                    <input class="col-xs-14 col-xs-offset-5 userRegisterEmail loginFormField" type="email" placeholder="Email" name="guest_email"/>
                    <input class="col-xs-14 col-xs-offset-5 ticketId loginFormField" name="ticket_id" type="text" placeholder="Booking ID" />
                    <button type="submit" class="col-xs-9 col-xs-offset-10 loginFormField userRegisterBtn"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Check In</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('.userRegisterBtn').click(function(e) {
    	e.preventDefault();
        if($(".userRegisterEmail").val() === "" || $(".ticketId").val() === "")
            var message = "All fields are mandatory.";
        else
            var message = "success";
        if(message==="success"){
            $('.userRegistrationForm').submit();
        } else {
            $(".userRegisterErrorMessage").html(message);
        }
    });
    
   
    $(".loginFormField").on("focus", function(){
        $(".userRegisterErrorMessage").html("");
    });
});
</script>