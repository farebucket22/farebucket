<?php
	if(isset($_SESSION['user_details'])){
		$user_details = $_SESSION['user_details'];
	}
	else{
		$user_details = "";
	}
	if(isset($_SESSION['currentUrl'])){
		$currentUrl = $_SESSION['currentUrl'];
	}
	if(isset($_SESSION['calling_controller_name'])){
		$calling_controller_name = $_SESSION['calling_controller_name'];
	}
	unset($_SESSION);
	$_SESSION['user_details'] = $user_details;
	$_SESSION['currentUrl'] = $currentUrl;
	$_SESSION['calling_controller_name'] = $calling_controller_name;
?>
<head>
<title>Farebucket: Lowest Airfare In India | Cheap Cab Services In Chennai | Cheap Cab Rates In Chennai</title>

<meta name="Subject" content="Lowest Airfare In India" />

<meta name="description" content="Farebucket make your dreams of an amazing travel plan come true and how! You would be amazed by our multiple options. Lowest Airfare In India , Cheap Cab Services In Chennai , Cheap Cab Rates In Chennai" />

<meta name="keywords" content=" Cheap Flight Tickets Booking, Cheap Air Tickets India, Lowest Airfare In India, Cheap Cab Services In Chennai, Cheap Cab Rates In Chennai, Cheap Call Taxi In Chennai, Cheap Call Taxi Tariff Chennai, Cheap Hotel Packages, Book Cheap Hotels In Chennai, Cheap Cabs In Bangalore, Cheap Cabs In Chennai, Cheap Domestic Air Tickets, Book Cheap Air Tickets India" />

<meta name="Language" content="English" />

<meta name="Distribution" content="Global" />

<meta name="Robots" content="All" />

<meta name="Revisit-After" content="7 Days" />
</head>
<style>
    .extra-margin{
        margin-top:7px;
    }
    .sod_placeholder{
        text-transform: none;
        height:13px;
    }
    .act-row{
      margin-top:6%;
    }
</style>
<div class="wrap">
    <div class="container-fluid main clear-top">
        <div class="row act-row">
            <div class="col-xs-24 col-sm-12 col-sm-offset-6 marketingMessage">Your Activities Start Here!</div>
        </div>
        
        <div class="row">
            <?php
              $include_path = APPPATH."views/common/search.php";
              include ($include_path);?>
        </div>
        
        <div class="row specialActivityArea">
            <a class="homeBGLink" href="#"><div class="col-xs-24 col-sm-7 col-sm-offset-17 specialActivityMessage"></div></a>
        </div>
    </div>
    
    <div class="slideLeftBtn"></div>
    <div class="slideRightBtn"></div>
</div>

<script type="text/javascript" src="<?php echo base_url('js/vendor/vegas/jquery.vegas.js'); ?>"></script>
<script type="text/javascript">

(function(){
    var link = "<?php echo site_url('/activity/get_activity?activity_id=');?>";
    
    $.vegas('slideshow', {
           backgrounds:[
              <?php foreach($data as $dat):?>
                    {     
                        src:'<?php echo base_url("img/activities/".$dat->image); ?>', fade:1000,
                        load:function() {
                            $(".homeBGLink").attr('href', link + "<?php echo $dat->image_url;?>");
                            $(".specialActivityMessage").html("<?php echo $dat->image_text;?>");
                        }
                    },
                <?php endforeach;?>
           ]
    });
    
    $(".slideLeftBtn").click(function(){
        $.vegas('previous');
    });
    
    $(".slideRightBtn").click(function(){
        $.vegas('next');
    });
    
})();
</script>