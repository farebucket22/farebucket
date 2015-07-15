 <head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css" />
    <link href=<?php echo base_url('css/uploadpreview/bootstrap-fileupload.min.css'); ?> rel="stylesheet" type="text/css">

    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src="<?php echo base_url('js/vendor/bootstrap.min.js'); ?>" ></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url('scripts/uploadpreview/bootstrap-fileupload.min.js'); ?>"></script>

</head>
<style>
    .main-st{
        margin-top: 100px;
    }
</style>
<body>
     <div class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container">
                  <div class="navbar-header">
                    <a class="navbar-brand logo_image" href="#"><img src="<?php echo base_url(); ?>img/logo.png"></a>
                  </div>
                  <div class="navbar-collapse collapse header_container">
                  <ul class="nav navbar-nav headers">
                      <li class="dropdown user-dropdown " style="width:107px;">
                           <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px;">
                                <i class="fa fa-user" ></i>
                                Activity
                                <b class="caret caret_custom"></b>
                           </a>
                            <ul class="dropdown-menu" style="width:130px; min-width:0;">
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo site_url('admin/activity/add')?>" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Activity_Add
                                    </a>
                                </li>
                                <li style=" text-align: center; "><a href="<?php echo site_url('admin/activity/list_activity_booking');?>" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Booking_List
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo site_url('admin/activity')?>" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Activity_List
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo site_url('admin/sub_activity/add')?>" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        SubActivityAdd
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo site_url('admin/sub_activity')?>" >
                                          <i class="fa fa-power-off">
                                          </i>
                                          SubActivityList
                                      </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo base_url();?>index.php/admin/category/" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Category
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo base_url();?>index.php/admin/country/" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Country
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo base_url();?>index.php/admin/city/" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        City
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo base_url();?>index.php/admin/leave/" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Leave
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo base_url();?>index.php/admin/vendor/" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Vendor
                                    </a>
                                </li>
                            </ul>
                      </li>
                       <li class="dropdown user-dropdown " style="width:107px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user" ></i>
                            Flight
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  "><a href="<?php echo site_url('admin/flight')?>" >
                                    <i class="fa fa-power-off">
                                    </i>
                                    Booking List
                                </a>
                            </li>
                            <li style=" text-align: center; "><a href="<?php echo site_url('admin/flight/deduct');?>" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Service Charge
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:107px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user" ></i>
                            Hotel
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  "><a href="<?php echo site_url('admin/hotel')?>" >
                                    <i class="fa fa-power-off">
                                    </i>
                                    Booking List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:107px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user" ></i>
                            Cab
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  "><a href="<?php echo site_url('admin/cabs')?>" >
                                    <i class="fa fa-power-off">
                                    </i>
                                    Booking List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:107px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user" ></i>
                            Bus
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  "><a href="<?php echo site_url('admin/bus')?>" >
                                    <i class="fa fa-power-off">
                                    </i>
                                    Booking List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown active" style="width:107px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user" ></i>
                            Common
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">
                          <li style="text-align: center;">
                                <a href="<?php echo site_url('admin/flight/list_background');?>" >
                                    <i class="fa fa-power-off"></i>
                                    Backgrounds
                                </a>
                          </li>
                          <li style="text-align: center;">
                                <a href="<?php echo base_url();?>index.php/admin/discount/" >
                                    <i class="fa fa-power-off"></i>
                                    Discount
                                </a>
                          </li>
                           <li style="text-align: center;">
                                <a href="<?php echo site_url('admin/convenience_charge/');?>" >
                                    <i class="fa fa-power-off"></i>
                                    Convenience_charge
                                </a>
                          </li>
                          <li style="text-align: center;">
                                <a href="<?php echo site_url('admin/default_page');?>" >
                                    <i class="fa fa-power-off"></i>
                                    Default_Page
                                </a>
                           </li>
                        </ul>
                    </li>
                      <li class="dropdown user-dropdown" style="width:150px;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; margin-right: 15px; text-align:center;">
                                <i class="fa fa-user" ></i>
                                <span class="glyphicon glyphicon-user"></span>
                                <?php echo($this->session->userdata('admin_name'));?>
                                <b class="caret caret_custom" style="margin-top:-12%;"></b>
                            </a>
                            <ul class="dropdown-menu" style="width:135px; min-width:0;">

                                <li style=" text-align:center; padding-right:0px;"><a href="<?php echo site_url('admin/login')?>" >
                                        <i class="fa fa-power-off">

                                        </i>
                                        LogOut
                                    </a>
                                </li>
                            </ul>
                      </li>
                    </ul>
                  </div>
                </div>
    </div>
    <div class="container main-st">
	    <div class="row">
		    <div class="col-lg-6">
                <form action="<?php echo site_url('admin/image/add_image'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="span9 offset3" style="padding-bottom: 10px; padding-left: 18px; ">
                            <span class="label label-info" >Maximum image size = 1MB . Allowed image formats = png, jpg, jpeg .</span>
                        </div>
                        <div class="span4 offset4" style="margin-left:1.5%; ">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-preview thumbnail" style="width: 320px; height: 240px;"></div>
                                <div>
                                    <span class="btn btn-file" style="background:darksalmon;"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="image" id="image"  /></span>
                                    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                            <!-- The below line is a tweak to ensure the form validation works -->
                            <input type="text" name="msg" id="msg" value="" style="display: none" />
                        </div>
                            <button type="submit" class="btn btn-default">submit</button>
                    </div>
                </form>
	    	</div>
    		<div class="col-lg-6">
		        <?php if($this->session->flashdata('returnMsg')) : ?>
		            <?php echo $this->session->flashdata('returnMsg')?>
		        <?php endif; ?>
				<?php if (validation_errors()) : ?>
					<div class="alert alert-danger" style="margin-top:8%;">
						<h6><?php echo validation_errors(); ?></h6>
					</div>
				<?php endif; ?>
			</div>
		</div>
    </div>
</body>
<script>
	(function(){
		$('#image').on('change', function(){
			$.ajax({
				type: "post",
				url: "<?php echo site_url('admin/image/add_background_image');?>"
				
			})
		})
	})();
</script>