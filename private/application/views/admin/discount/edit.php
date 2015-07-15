<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">    
      
    
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src="<?php echo base_url('js/vendor/bootstrap.min.js');?>"></script>
    
</head>
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
    
    <div class="container content">
      <h2 style="margin-top:60px;">Edit City</h2>
      
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>
          
        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
        <?php endif; ?>
      
      <div class="edit-form span6">
      <form action="<?php echo site_url('admin/discount/update?discount_code_id='.$discount->discount_code_id); ?>" method="post" enctype="multipart/form-data">
           <input type="text" class="input-block-level" placeholder="Discount Name" id="dis_name" name="discount_code_name" value="<?php echo $discount->discount_code_name; ?>">
         <input type="number" class="input-block-level" placeholder="Discount Value" id="dis_amt" name="discount_code_value" value="<?php echo $discount->discount_code_value; ?>">
          <select name="discount_code_type" style="width:180px;" class="span6">
              <option value="">Select Discount Type</option>
              <option value="amount" <?php if($discount->discount_code_type == 'amount')echo 'selected';?>>Amount</option>
              <option value="percent" <?php if($discount->discount_code_type == 'percent')echo 'selected';?>>Percent</option>
          </select>
          <select name="discount_code_status" style="width:180px;" class="span6">
              <option value="">Select Discount Status</option>
              <option value="active" <?php if($discount->discount_code_status == 1)echo 'selected';?>>Active</option>
              <option value="inactive" <?php if($discount->discount_code_status == 0)echo 'selected';?>>In Active</option>
          </select>
          <select name="discount_code_module" style="width:180px;" class="span6">
              <option value="">Select Discount Module</option>
              <option value="all" <?php if($discount->discount_code_module == 'all')echo 'selected';?>>All</option>
              <option value="flights" <?php if($discount->discount_code_module == 'flights')echo 'selected';?>>Flight</option>
              <option value="activities" <?php if($discount->discount_code_module == 'activities')echo 'selected';?>>Activity</option>
              <option value="cabs" <?php if($discount->discount_code_module == 'cabs')echo 'selected';?>>Cab</option>
              <option value="buses" <?php if($discount->discount_code_module == 'buses')echo 'selected';?>>Bus</option>
              <option value="hotels" <?php if($discount->discount_code_module == 'hotels')echo 'selected';?>>Hotel</option>    
          </select>
          <button class="btn btn-info span12" type="submit">Update</button>
          <a href="<?php echo site_url('admin/discount'); ?>" class="btn btn-danger span12 upd-cancel">Cancel</a>
      </form>
      </div>
    </div>
</body>
</html>
