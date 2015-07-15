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
         <script src="//cdn.ckeditor.com/4.4.5/standard/ckeditor.js"></script>
         
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
    <form action="<?php echo site_url('admin/activity/add'); ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-8" style="margin-top:5%;">
                    <div class="col-md-6">
                        <div class="form-group form1">
                            <input  name="activity_name" class="form-control" id="act_name" placeholder="Activity Name" style="width:350px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group select1">
                            <select id="country_id" name="activity_country" class="form-control sel_country" style="width:350px;">
                                <option value="">Activity Country</option>
                                <?php foreach($countries as $country)
                                    echo '<option value="'.$country->activity_country_id.'"'.set_select("country", "'.$country->activity_country_id.'").'>'.$country->activity_country_name.'</option>';
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group select1">
                            <select name="activity_category_id" class="form-control sel_country" style="width:350px;">
                                <option value="">Activity Category</option>
                                <?php foreach($categories as $category)
                                        echo '<option value="'.$category->activity_category_id.'"'.set_select("category", "'.$category->activity_category_id.'").'>'.$category->activity_category_name.'</option>';
                                 ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group select1">
                            <select name="activity_city" id="city_id" class="form-control sel_country" style="width:350px;">
                                <option>Activity City</option>
                            </select>
                        </div>
                    </div>
                       
                </div>
                <div class="col-md-4 text1">
                    <textarea name="activity_description_short" class="form-control short_des" id="short_des" rows="7" cols="40" placeholder="Short Description" style="margin-top:18%;"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-8 text2" style="padding-left:2%;">
                    <textarea name="activity_description" class="form-control" id="overview" rows="7" placeholder="Overview"></textarea>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-8 text3" style="padding-left:2%;">
                    <textarea name="activity_details" class="form-control" id="details" rows="7" placeholder="Details"></textarea>
                </div>
                <div class="col-md-4"></div>
            </div>
            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="col-md-6">
                        <div class="form-group form1">
                            <input  name="activity_location_lat" class="form-control act_no" id="loc_lat" placeholder="Location Lat" style="width:350px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form1">
                            <input  name="activity_location_long" class="form-control act_no" id="loc_lang" placeholder="Location Long" style="width:350px;">
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="col-md-6">
                        <select name="activity_leave_id" class="form-control sel_country" style="width:350px; margin-top:5%;">
                            <option value="">Activity Leave</option>
                            <?php foreach($leaves as $leave)
                                    echo '<option value="'.$leave->activity_leave_id.'"'.set_select("leave", "'.$leave->activity_leave_id.'").'>'.$leave->activity_leave_name.'</option>';
                             ?>
                       </select>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form1">
                            <input  name="activity_rating_average_value" class="form-control act_no" id="activity_rating" placeholder="Activity Rating" style="width:350px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form1">
                            <select name="activity_vendor_name" class="form-control" style="width:350px; margin-top:5%;">
                              <option value="">Select Vendor</option>
                              <?php foreach($vendors as $vendor)
                                  echo '<option value="'.$vendor->id.'"'.set_select("vendor", "'.$vendor->id.'").'>'.$vendor->vendor_name.'</option>';
                              ?>
                             </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                </div>
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
            CKEDITOR.replace( 'overview' );CKEDITOR.replace( 'details' );
            $("input:checkbox").click(function() {
                $("#kid_1,#kid_2,#kid_3").attr("disabled", !this.checked);
            });

            $("#country_id").change(function(){

                var check_id = $(this).val();
                //console.log(check_id);
                var city = <?php echo json_encode($cities); ?> ;
                //console.log(sub_cat,check_id);
                var temp_sub_cat=new Array();

                $.each(city , function(i,sc){
                    if(check_id==sc.activity_country_id){
                        temp_sub_cat.push(sc);
                    }
                });

                if(temp_sub_cat.length){
                    $('#city_id option').remove();
                    $('#city_id').html('<option value ="">Activity City</option>');
                    $('#city_id').removeAttr('disabled');
                    for(var i=0;i<temp_sub_cat.length;i++){
                        $('#city_id').append('<option value='+temp_sub_cat[i].activity_city_id + '>'+ temp_sub_cat[i].activity_city_name + '</option>');
                    }
                }
                if(temp_sub_cat.length==0){
                    $('#city_id option').remove();
                    $('#city_id').html('<option value ="">Activity City</option>');
                    $('#city_id').attr('disabled');
                    alert('no city id');
                }
            });
        });
    </script>
</body>
</html>
