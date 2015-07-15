<?php
    $cust_support_data = cust_support_helper();
?>
<?php $url = explode("/",$_SERVER['REQUEST_URI']);?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <META HTTP-EQUIV="Expires" CONTENT="-1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php if($_SESSION['calling_controller_name'] == 'cabs') echo '<title id="siteTitle">Farebucket | Cabs</title>';?>
        <?php if($_SESSION['calling_controller_name'] == 'flights') echo '<title id="siteTitle">Farebucket | Flights</title>';?>
        <?php if($_SESSION['calling_controller_name'] == 'buses') echo '<title id="siteTitle">Farebucket | Buses</title>';?>
        <?php if($_SESSION['calling_controller_name'] == 'hotels') echo '<title id="siteTitle">Farebucket | Hotels</title>';?>
        <?php if($_SESSION['calling_controller_name'] == 'activity') echo '<title id="siteTitle">Farebucket | Activity</title>';?>

        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('img/favicon.ico')?>">
        <link rel="stylesheet" href="<?php echo base_url('css/bootstrap24.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/bootstrap-theme.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/jquery-ui.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/jquery-ui.structure.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/jquery-ui.theme.min.css'); ?>">
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300|Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('js/vendor/vegas/jquery.vegas.css'); ?>" />
        <link rel="stylesheet" href="<?php echo base_url('css/bootstrapValidator.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/selectordie.css');?>">
        <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/jquery.mCustomScrollbar.css'); ?>" />
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url('css/fotorama.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/main.css'); ?>">

        <script src="<?php echo base_url('js/vendor/modernizr-2.6.2-respond-1.1.0.min.js'); ?>"></script>
        <script src="<?php echo base_url('js/vendor/jquery-1.11.2.min.js'); ?>"></script>
        <script src="<?php echo base_url('js/vendor/jquery-ui.min.js'); ?>"></script>
        <script src="<?php echo base_url();?>js/vendor/bootstrap.min.js"></script>
        <script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
        <script src="http://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url();?>js/vendor/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url('js/jquery.mixitup.js'); ?>"></script>
        <script src="<?php echo base_url('js/vendor/fotorama.js'); ?>"></script>
        <script src="<?php echo base_url('js/vendor/jquery.mCustomScrollbar.concat.min.js'); ?>"></script>
        <script src="<?php echo base_url();?>js/vendor/autoNumeric.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>js/vendor/selectordie.min.js" type="text/javascript"></script>
        <!-- Go to www.addthis.com/dashboard to customize your tools -->
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54cb863b242a6cac" async="async"></script>

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
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
                    <a class="logoLink" href="<?php echo site_url('activity') ?>"><img class="img-responsive logo" src="<?php echo base_url('img/logo.png'); ?>" alt="FareBucket Logo" /></a>
                  </div>
                </div>
            
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav headerNav">
                    <li class="flightsLink <?php if(($url[2] == 'api' || $url[2] == 'flights') && $url[count($url)-1] != 'guest_ticket')echo 'active_tab';?>"><a href="<?php echo site_url('flights') ?>">Flights</a></li>
                    <li class="busesLink <?php if($url[2] == 'bus_api' || $url[2] == 'buses')echo 'active_tab';?>"><a href="<?php echo site_url('buses') ?>">Buses</a></li>
                    <li class="cabsLink <?php if($url[2] == 'cab_api' || $url[2] == 'cabs')echo 'active_tab';?>"><a href="<?php echo site_url('cabs') ?>">Cabs</a></li>
                    <li class="hotelsLink <?php if($url[2] == 'hotel_api' || $url[2] == 'hotels')echo 'active_tab';?>"><a href="<?php echo site_url('hotels') ?>">Hotels</a></li>
                    <li class="activitiesLink <?php if($url[2] == 'activity')echo 'active_tab';?>"><a href="<?php echo site_url('activity') ?>">Activities</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right headerNav ticket_header">
                        <li class="headerContact"><a href="javascript:void(0);"><span class="glyphicon glyphicon-phone-alt"></span>&nbsp;<?php echo $cust_support_data->phone_number;?></a></li>
                        <li class="mytcktlink <?php if($url[count($url)-1] == 'guest_ticket')echo 'active_tab';?>"><a href="<?php echo site_url('api/flights/guest_ticket') ?>">My Tickets</a></li>
                        <?php
                        if(isset($_SESSION['login_status']))
                        {
                            if($_SESSION['login_status'] == 0){
                                echo '<li class="loginLink"><a href="'.base_url("index.php/login").'">Login</a></li>';
                            }else{
                                if(isset($_SESSION['user_details'][0])){
                                    $first_name = $_SESSION['user_details'][0]->user_first_name;
                                    echo '<li class="userWelcome userLink"><a href="'.base_url("index.php/user").'"><span class="glyphicon glyphicon-user"></span>&nbsp&nbspHi '.$first_name.'</a></li>';
                                    echo '<li class="userWelcome"><a href="'.base_url("index.php/login/logout_user").'"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbspLogout</a></li>';
                                }
                                else{
                                    $first_name = "Guest"; 
                                    echo '<li class="userWelcome userLink"><a href="javascript:void(0);"><span class="glyphicon glyphicon-user"></span>&nbsp&nbspHi '.$first_name.'</a></li>';
                                    echo '<li class="userWelcome"><a href="'.base_url("index.php/login/logout_user").'"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbspLogout</a></li>';
                                }
                            }
                        }
                        ?>
                    
                  </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>