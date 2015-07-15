<html>
<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css" />      
    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src=<?php echo base_url('js/vendor/bootstrap.min.js'); ?> ></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    <script src=<?php echo base_url('bootstrap/js/custom.js'); ?> ></script>
</head>
<style>
.flightbox{
    margin-top:5%;
    margin-left:13%;
}
.cab-balance{
    margin-left: 50px;
}

.service_font{
    font-size: 16px;
}

.save-btn{
    margin-top:3%;
}
</style>
<style>
    .user-dropdown{
          width:90px;
      }
</style>
<body>
    <form action="<?php echo site_url('admin/flight/check_service_charge'); ?>" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">
            <div class="flightbox">
                <label class="service_font flight-balance" style="font-weight: bold;">
                    <p>Flights Agency Balance : &#x20B9; <?php echo $balance; ?></p>
                </label>
                <label class="service_font cab-balance" style="font-weight: bold;">
                    <p>Cabs Agency Balance : &#x20B9; <?php echo $cab_balance; ?></p>
                </label>
            </div>
        </div>
    </div>
</form>
</body>
</html>