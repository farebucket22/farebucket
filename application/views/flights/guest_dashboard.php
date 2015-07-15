<head>
    <title>Farebucket:Cheap Cab Services In Chennai | Cheap Cab Rates In Chennai | Cheap Call Taxi In Chennai </title>

<meta name="Subject" content="Cheap Cab Services In Chennai" />

<meta name="description" content="Farebucket flood you with options right from the mode of transport to the type of hotel you would lodge in and to maybe the tourist guide who could speak your choice of language. Cheap Cab Services In Chennai , Cheap Cab Rates In Chennai , Cheap Call Taxi In Chennai" />

<meta name="keywords" content="Cheap Air Tickets India, Lowest Airfare In India, Cheap Cab Services In Chennai, Cheap Cab Rates In Chennai, Cheap Call Taxi In Chennai, Cheap Call Taxi Tariff Chennai, Cheap Hotel Packages, Book Cheap Hotels In Chennai, Cheap Cabs In Bangalore, Cheap Cabs In Chennai, Cheap Domestic Air Tickets, Book Cheap Air Tickets India, Cheap Flight Tickets Booking" />

<meta name="Language" content="English" />

<meta name="Distribution" content="Global" />

<meta name="Robots" content="All" />

<meta name="Revisit-After" content="7 Days" />
</head>
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
						<?php unset($_SESSION['invalidGuestErrorMessage']);?>
                    </div>
					<h4 class="col-xs-24 userRegisterHeader loginFormField">Enter your User ID &amp; Booking ID to retrieve your tickets</h4>
                    <input class="col-xs-14 col-xs-offset-5 userRegisterEmail loginFormField" type="email" placeholder="Email" name="guest_email"/>
                    <input class="col-xs-14 col-xs-offset-5 ticketId loginFormField" name="ticket_id" type="text" placeholder="Booking ID" />
                    <button type="submit" class="col-xs-9 col-xs-offset-10 loginFormField userRegisterBtn">Get Ticket</button>
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