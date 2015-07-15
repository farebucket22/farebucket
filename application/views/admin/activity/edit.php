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
      .user-dropdown{
          width:90px;
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
     <form action="<?php echo site_url('admin/activity/update?activity_id='.$activity->activity_id); ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                    <div class="col-md-8" style="margin-top:5%;">
                         <div class="col-md-6">
                            <div class="form-group form1">
                               <input  name="activity_name" value="<?php echo $activity->activity_name; ?>"class="form-control" id="act_name" placeholder="Activity Name" style="width:350px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group select1">
                                <select id="country_id" name="activity_country" class="form-control sel_country" style="width:350px;">
                                    <option value="">Activity Country</option>
                                        <?php foreach($countries as $country) { ?>
                                        <option value="<?php echo $country->activity_country_id; ?>" <?php if($country->activity_country_id == $activity->activity_country) echo 'selected'; ?>><?php echo $country->activity_country_name; ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                       <div class="col-md-6">
                            <div class="form-group select1">
                                <select name="activity_category_id" value="<?php echo $activity->activity_category_id; ?>" class="form-control sel_country" style="width:350px;">
                                   <option value="">Activity Category</option>
                                   <?php foreach($categories as $category) { ?>
                                   <option value="<?php echo $category->activity_category_id; ?>" <?php if($category->activity_category_id == $activity->activity_category_id) echo 'selected'; ?>><?php echo $category->activity_category_name; ?></option>
                                   <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group select1">
                                <select name="activity_city" id="city_id" value="" class="form-control sel_country" style="width:350px;">
                                    <option value="">Activity City</option>
                                        <?php foreach($cities as $city) { ?>
                                        <option value="<?php echo $city->activity_city_id; ?>" <?php if($city->activity_city_id == $activity->activity_city) echo 'selected'; ?>><?php echo $city->activity_city_name; ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                    
                    </div>
                    <div class="col-md-4 text1" style="margin-top:6.5%;">
                        <textarea name="activity_description_short" value="" class="form-control short_des" id="short_des" rows="7" cols="40" placeholder="Short Description"><?php echo $activity->activity_description_short; ?></textarea>
                    </div>                
            </div>
            <div class="col-md-12">
                <div class="col-md-8 text2" style="padding-left:2%;">
                     <textarea name="activity_description" value="" class="form-control" id="overview" rows="7" placeholder="Overview"><?php echo $activity->activity_description; ?></textarea>
                </div>
                <div class="col-md-4">
                  <textarea name="activity_address" class="form-control address" id="address" rows="7" cols="40" placeholder="Activity Address" style="margin-top:18%;"><?php echo $activity->activity_address;?></textarea>
                </div>
                <div class="col-md-4">
                  <div class="form-group form1">
                    <input type="text" name="activity_phone_num" id="activity_phone_num" placeholder="Activity Phone Number" class="form-control activity_phone_num" value="<?php echo $activity->activity_phone_num;?>"/>
                  </div>
                </div>
                <div class="col-md-8 text3" style="padding-left:2%;">
                     <textarea name="activity_details" value="" class="form-control" id="details" rows="7" placeholder="Details"><?php echo $activity->activity_details; ?></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-8">
                     <div class="col-md-6">
                            <div class="form-group form1">
                               <input  name="activity_location_lat" value="<?php echo $activity->activity_location_lat; ?>" class="form-control act_no" id="loc_lat" placeholder="Location Lat" style="width:350px;">
                            </div>
                     </div>
                    <div class="col-md-6">
                            <div class="form-group form1">
                               <input  name="activity_location_long" value="<?php echo $activity->activity_location_long; ?>" class="form-control act_no" id="loc_lang" placeholder="Location Lang" style="width:350px;">
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
                            <?php foreach($leaves as $leave) { ?>
                            <option value="<?php echo $leave->activity_leave_id; ?>" <?php if($leave->activity_id == $activity->activity_id) echo 'selected'; ?>><?php echo $leave->activity_leave_name; ?></option>
                             <?php } ?>
                       </select>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group form1">
                               <input  value="<?php echo $activity->activity_rating_average_value; ?>" name="activity_rating_average_value" class="form-control act_no" id="activity_rating" placeholder="Activity Rating" style="width:350px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form1">
                            <input  name="activity_onwards_price" value="<?php echo $activity->activity_onwards_price; ?>" class="form-control act_no" id="activity_price" placeholder="Activity Price" style="width:350px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form1">
                            <select name="activity_vendor_name" class="form-control" style="width:350px; margin-top:5%;">
                              <option value="">Select Vendor</option>
                              <?php foreach($vendors as $vendor){?>
                                  <option value="<?php echo $vendor->vendor_name; ?>" <?php if($vendor->id == $activity->activity_vendor_name) echo 'selected'; ?>><?php echo $vendor->vendor_name; ?></option>
                              <?php }?>
                             </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                </div>
          </div>
                 
                 <div class="col-md-8">
                     <div class="col-md-offset-5" style="margin-left:66%;"> 
                      <button type="submit" class="btn btn-primary" style="width:100px;">UPDATE</button>
                      <a href="<?php echo site_url('admin/activity'); ?>" class="btn btn-danger span12 upd-cancel">Cancel</a>
                     </div>
                 <div class="col-md-4"></div>    
            </div>
            </div>
        </div>
      </form>  
      <script src=<?php echo base_url('js/vendor/bootstrap.min.js'); ?> ></script>    
      <script type="text/javascript">
  			$(document).ready(function(){
			  CKEDITOR.replace( 'overview' );CKEDITOR.replace( 'details' );
			  $("input:checkbox").click(function() {
			  $("#kid_1,#kid_2,#kid_3").attr("disabled", !this.checked); 
			});
				  $("#country_id").change(function(){
					var check_id = $(this).val();
					var city = <?php echo json_encode($cities); ?> ;	
					var temp_sub_cat=new Array();

					$.each(city , function(i,sc){
						if(check_id==sc.activity_country_id)
						{
							temp_sub_cat.push(sc);
						}
					});

					if(temp_sub_cat.length)
					{
						$('#city_id option').remove();
						$('#city_id').html('<option value ="">Activity City</option>');
						$('#city_id').removeAttr('disabled');
						for(var i=0;i<temp_sub_cat.length;i++)
						{
							$('#city_id').append('<option value='+temp_sub_cat[i].activity_city_id + '>'+ temp_sub_cat[i].activity_city_name + '</option>');

						}
					}
					if(temp_sub_cat.length==0)
					{
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
