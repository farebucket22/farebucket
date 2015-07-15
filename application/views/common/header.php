<?php 
	$cust_support_data = cust_support_helper();
    $url = $_SERVER['REQUEST_URI'];
    $active_tag = explode('/', $_SERVER['REQUEST_URI']);
	$discounts = get_discounts_helper();
	if(!isset($_SESSION['login_status'])){
		$_SESSION['login_status'] = 0;
	}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta https-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <META https-EQUIV="Pragma" CONTENT="no-cache">
        <META https-EQUIV="Expires" CONTENT="-1">
        <meta name="description" content="">
        <meta name="google-site-verification" content="6gvB4kuN00tAfAkOLsm4UobQ2cF7mKU3BsWGu7v1Nac" />
        <meta name="msvalidate.01" content="A321CCC9BE83C7EE98489A86620296C2" />
        <meta name="alexaVerifyID" content="3qk72BLhvPbuSL3t9BQ6zKXYCaY"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('img/favicon.ico')?>">
        <link rel="stylesheet" href="<?php echo base_url('css/bootstrap24.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/bootstrap-theme.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/jquery-ui.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/jquery-ui.structure.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/jquery-ui.theme.min.css'); ?>">
        <link href='https://fonts.googleapis.com/css?family=Oswald:400,700,300|Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('js/vendor/vegas/jquery.vegas.css'); ?>" />
        <link rel="stylesheet" href="<?php echo base_url('css/bootstrapValidator.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/selectordie.css');?>">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/jquery.mCustomScrollbar.css'); ?>" />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url('css/fotorama.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/main.css'); ?>">

        <script src="<?php echo base_url('js/vendor/modernizr-2.6.2-respond-1.1.0.min.js'); ?>"></script>
        <script src="<?php echo base_url('js/vendor/jquery-1.11.2.min.js'); ?>"></script>
        <script src="<?php echo base_url('js/vendor/jquery-ui.min.js'); ?>"></script>
        <script src="<?php echo base_url();?>js/vendor/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url();?>js/vendor/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url('js/jquery.mixitup.js'); ?>"></script>
        <script src="<?php echo base_url('js/vendor/fotorama.js'); ?>"></script>
        <script src="<?php echo base_url('js/vendor/jquery.mCustomScrollbar.concat.min.js'); ?>"></script>
        <script src="<?php echo base_url();?>js/vendor/autoNumeric.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>js/vendor/selectordie.min.js" type="text/javascript"></script>
        <!-- Go to www.addthis.com/dashboard to customize your tools -->
        <script type="text/javascript" src="https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54cb863b242a6cac" async="async"></script>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-61721445-1', 'auto');
            ga('send', 'pageview');
            /* (function(){    
                addedAlready = 0;
                $(window).on('resize load', function(){
                    if( $(this).width() < 1220 || $(this).height() < 580 ){
                        if( !addedAlready ){
                            var body = $('body');
                            body.addClass('modal-open');
                            body.append('<div class="modal-white fade in"><center><img class="img-responsive logo" src="<?php echo base_url("img/logo.png"); ?>" alt="FareBucket Logo" /><h3 class="vam">Hi there! <br/>We are working on optimising our site for smaller desktops/tablets/phones. <br/>If you are on desktop, please expand your browser for best results.</h3><h3 style="font-weight:normal;margin-top:30px;">Team Farebucket</h3><center></div>');
                            addedAlready++
                        }
                    }else{
                        var body = $('body');
                        body.removeClass('modal-open');
                        body.children().remove('div.modal-white');
                        addedAlready = 0;
                    }
                });
            })(); */
        </script>
		<style>
            .dropdown-menu{
                min-width:350px;
            }
			.discount-p{
                font-family: "Open Sans", sans-serif;
            }
            ul.nav a.click, .click:hover{
                color: #1e884b !important;
                background-color: transparent !important;
            }
            .discount-list{
                text-align: center;
            }
            hr { 
                display: block;
                margin-top: 0.5em;
                margin-bottom: 0.5em;
                margin-left: auto;
                margin-right: auto;
                border-style: inset;
                border-width: 1px;
            }
            .dis-service{
                text-transform: capitalize;
            }
			.main-header{
                z-index:99999;
            }
        </style>

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <nav class="navbar navbar-default navbar-fixed-top main-header" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <div class="logoContainer">
                    <span class="verticalAlignHelper"></span>
                    <a class="logoLink" href="<?php echo site_url('default_controller') ?>"><img class="img-responsive logo" src="<?php echo base_url('img/logo.png'); ?>" alt="FareBucket Logo" /></a>
                  </div>
                </div>           
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav headerNav">
                    <li class="flightsLink <?php if(($active_tag[1] == 'api' || $active_tag[1] == 'flights') && $active_tag[count($active_tag)-1] != 'guest_ticket')echo 'active_tab';?>"><a href="<?php echo site_url('flights') ?>">Flights</a></li>
                    <li class="busesLink <?php if($active_tag[1] == 'bus_api' || $active_tag[1] == 'buses')echo 'active_tab';?>"><a href="<?php echo site_url('buses') ?>">Buses</a></li>
                    <li class="cabsLink <?php if($active_tag[1] == 'cab_api' || $active_tag[1] == 'cabs')echo 'active_tab';?>"><a href="<?php echo site_url('cabs') ?>">Cabs</a></li>
                    <li class="hotelsLink <?php if($active_tag[1] == 'hotel_api' || $active_tag[1] == 'hotels' || $active_tag[1] == 'new_request')echo 'active_tab';?>"><a href="<?php echo site_url('hotels') ?>">Hotels</a></li>
                    <li class="activitiesLink <?php if($active_tag[1] == 'activity')echo 'active_tab';?>"><a href="<?php echo site_url('activity') ?>">Activities</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right headerNav ticket_header">
						<li class="offerLink"><a class="dropdown-toggle click" data-toggle="dropdown">Offers!</a>
                            <ul class="dropdown-menu">
                                <li class="discount-list">
                                    <p class="discount-head col-lg-8">Discount Code</p>
                                    <p class="discount-head col-lg-8">Discount Value</p>
                                    <p class="discount-head col-lg-8">Discount Service</p>
                                    <hr>
                                </li>
                                <?php foreach ($discounts as $discount) {
                                    if($discount->display_on_site == 'Yes' && $discount->discount_code_status == 1){
                                        echo '<li class="discount-list"><p class="discount-p col-lg-8">'.$discount->discount_code_name.'</p>';
                                        if($discount->discount_code_type == 'amount'){
                                            echo '<p class="discount-p col-lg-8">&#8377;'.$discount->discount_code_value.'</p>';
                                        }
                                        else{
                                            echo '<p class="discount-p col-lg-8">'.$discount->discount_code_value.'%</p>';
                                        }
                                        echo '<p class="discount-p dis-service col-lg-8">'.$discount->discount_code_module.'</p></li>';
                                    }
                                }?>                                
                            </ul>
                        </li>
                        <li class="headerContact"><a href="javascript:void(0);"><span class="glyphicon glyphicon-phone-alt"></span>&nbsp;<?php echo $cust_support_data->phone_number;?></a></li>
                        <li class="mytcktlink <?php if($active_tag[count($active_tag)-1] == 'guest_ticket')echo 'active_tab';?>"><a href="<?php echo site_url('tickets/guest_ticket') ?>">My Tickets</a></li>
                        <?php
                        if(isset($_SESSION['login_status']))
                        {
                            if($_SESSION['login_status'] == 0){
                                echo '<li class="loginLink"><a href="'.site_url("login").'">Login</a></li>';
                            }else{
                                if(isset($_SESSION['user_details'][0])){
                                    $first_name = $_SESSION['user_details'][0]->user_first_name;
                                    echo '<li class="userWelcome userLink"><a href="'.site_url("user").'"><span class="glyphicon glyphicon-user"></span>&nbsp&nbspHi '.$first_name.'</a></li>';
                                    echo '<li class="userWelcome"><a href="'.site_url("login/logout_user").'"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbspLogout</a></li>';
                                }
                                else{
                                    $first_name = "Guest"; 
                                    echo '<li class="userWelcome userLink"><a href="javascript:void(0);"><span class="glyphicon glyphicon-user"></span>&nbsp&nbspHi '.$first_name.'</a></li>';
                                    echo '<li class="userWelcome"><a href="'.site_url("login/logout_user").'"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbspLogout</a></li>';
                                }
                            }
                        }
                        ?>
                  </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>