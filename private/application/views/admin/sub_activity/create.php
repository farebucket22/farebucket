<!DOCTYPE <html>
<head>
	<title></title>
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
        <link href="<?php echo base_url(); ?>css/bootstrap.css" type="text/css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url(); ?>uploadify/uploadify.css" type="text/css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">
         <script src="http://code.jquery.com/jquery-latest.js"></script>
         <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
         <script src="<?php echo base_url('uploadify/jquery.uploadify.min.js')?>"></script>
         <!--this is extra-->
         <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
         
</head>
<style>
     .logo_image
      {
        margin-top:-7%;
      }
        
      
      .left_pane
      {
          height:140px;
          margin-top:18%;
          
      }
  
      .main_form
      {
          margin-top:10%;
      }
      
      #act
      {
          width:200px;
      }
      
      .second_header
      {
          margin-top:3.7%;
          border:1px solid #005702;
      }

      .lists
      {
          font-weight: bold;
          
      }
      
      .lists > a
      {
          background:white;
          text-decoration:none;
          color:black;
      }
      a:hover
      {
          background:green;
      }
      
      .form1
      {
          margin-top:5%;
      }
  
      .select1
      {
          margin-top:5%;
      }
      
      .text1
      {
          margin-top:1.5%;
          width:400px;
      }
      
      .text2
      {
          margin-top:2%;
      }
      .text3
      {
          margin-top:2%;
      }
</style>
<body>
    <?php if($this->session->flashdata('returnMsg')) : ?>
        <?php echo $this->session->flashdata('returnMsg')?>
    <?php endif; ?>
          
    <?php if (validation_errors()) : ?>
        <div class="alert alert-danger" style="margin-top:8%;">
            <h6><?php echo validation_errors(); ?></h6>
        </div>
    <?php endif; ?>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container">
                  <div class="navbar-header">
                    <a class="navbar-brand logo_image" href="#"><img src="<?php echo base_url(); ?>img/logo.png"></a>
                  </div>
                  <div class="navbar-collapse collapse header_container">
                  <ul class="nav navbar-nav headers">
                      <li class="dropdown user-dropdown active" style="width:107px;">
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
                    <li class="dropdown user-dropdown " style="width:107px;">
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

    <form action="<?php echo site_url('admin/sub_activity/add'); ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-8" style="margin-top:5%;">
                    <div class="col-md-12">
                        <div class="form-group form1">
                            <input  name="activity_sub_type_name" class="form-control" id="act_name" placeholder="Sub Activity Name" style="width:350px;">
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:-1.5%;">
                        <div class="form-group select1">
                            <select id="activity_id" name="activity_id" class="form-control sel_country" style="width:350px;">
                                <option value="">Activity</option>
                                <?php foreach($activities as $activity)
                                    echo '<option value="'.$activity->activity_id.'"'.set_select("activity", "'.$activity->activity_id.'").'>'.$activity->activity_name.'</option>';
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 text1" style="margin-top:-4.5%;">
                        <textarea name="activity_sub_type_description" class="form-control short_des" id="short_des" rows="7" cols="40" placeholder="Description" style="margin-top:18%;"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form1">
                            <input  name="activity_sub_type_max_tickets" class="form-control act_no" id="loc_lat" placeholder="Maximum Ticket" style="width:350px;">
                        </div>
                    </div>
              </div>
            </div>
            
            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="col-md-4">
                        <div class="form-group form1">
                            <input  name="activity_sub_type_adult_price" class="form-control act_no" id="adult_fare" placeholder="Adult Price" style="width:350px;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form1">
                            <input  name="activity_sub_type_adult_vendor_price" class="form-control act_no" id="exampleInputEmail2" placeholder="Adult Vendor Price" style="width:350px; margin-left:57%">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form1">
                            <input  name="activity_sub_type_adult_tax" class="form-control act_no" id="adult_tax" placeholder="Adult Price Tax Amount" style="width:350px; margin-left:115%;">
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-8">
                    <div class="col-md-9">
                        <div class="checkbox">
                            <label>
                                <input name="activity_sub_type_kid_status" id="check_kid" type="checkbox">Kids Allowed
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-4">
                        <div class="form-group form1">
                            <input disabled id="kid_1" name="activity_sub_type_kid_price" class="form-control act_no" id="exampleInputEmail2" placeholder="kid Price" style="width:350px; ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form1">
                            <input disabled id="kid_2" name="activity_sub_type_kid_vendor_price" class="form-control act_no" id="exampleInputEmail2" placeholder="Kid Vendor Price" style="width:350px; margin-left:57%">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form1">
                            <input disabled id="kid_3" name="activity_sub_type_kid_tax" class="form-control act_no" id="exampleInputEmail2" placeholder="Kids Price Tax Amount" style="width:350px;  margin-left:115%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-8">
                    <div class="col-md-offset-5" style="margin-left:69%;">
                        <button type="submit" class="btn btn-primary" style="width:100px;">ADD</button>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){

            $("input:checkbox").click(function() {
                $("#kid_1,#kid_2,#kid_3").attr("disabled", !this.checked);
            });

            
        });
    </script>
</body>
</html>
