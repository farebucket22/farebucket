<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>FareBucket-admin</title>
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css" />
    <link href="<?php echo base_url('css/uploadpreview/bootstrap-fileupload.min.css'); ?>" rel="stylesheet" type="text/css">
    
    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src="<?php echo base_url('js/vendor/bootstrap.min.js');?>"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url('scripts/uploadpreview/bootstrap-fileupload.min.js'); ?>"></script>
    <script type="text/javascript">
        $(function(){
            var oTable = $('#image-table').dataTable({ 
                "aaSorting": [],
                "bJQueryUI": true,
                "sPaginationType": "full_numbers" 
            });
            
            $('.delete').on('click',function(e){
				e.preventDefault;
                if(confirm("Are you sure you want to delete this image?"))
                    return true;
                else
                    return false;
            });
            
        });
    </script>
    <style>
    .user-dropdown{
          width:90px;
      }
</style>
</head>
<body>
    
    
    <div class="container content">
      <div class="row">
        <div class="col-lg-6">
          <h2 style="margin-top:10px";>Add an Image</h2>
        </div>
        <div class="col-lg-6" >
          <div class="pull-right" style="margin-top:2%;"><a href="<?php echo site_url('admin/activity'); ?>" class="btn btn-default span12 settings-cancel">Back</a></div>
        </div>
      </div>
      <legend></legend>
      
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>
          
        <?php if (validation_errors()) : ?>
            <div class="alert alert-error">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
        <?php endif; ?>
      
        <form action="<?php echo site_url('admin/image/add_image?activity_id='.$id); ?>" method="post" enctype="multipart/form-data">
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
                <div class="span12" style="margin-left:1.5%; ">
                    <button class="btn btn-success span12 settings-upd" type="submit">Upload</button>
                    <a href="<?php echo site_url('admin/activity'); ?>" class="btn btn-danger span12 settings-cancel">Cancel</a>
                </div>
            </div>
        </form>

      
        <div class="span12 lists">
      <?php if($images) { ?>
      <table id="image-table">
          <thead>
              <tr>
                  <th>Image</th>
                  <th>Main Image</th>
                  <th>delete</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach($images as $image) {
                  echo '<tr>';
                  echo '<td>'.$image->file_name.'</td>';
                  if(!($image->is_main))
                  {
                  echo '<td><a href="'.site_url('admin/image/mainimage?filename='.$image->file_name.'&id='.$image->id).'" ><span class="glyphicon glyphicon-picture"></i></a></td>';
                  }
                  else
                  {
                   echo '<td><span class="glyphicon glyphicon-ok"></i></td>';    
                  }    
                  echo '<td><a href="'.site_url('admin/image/delete?id='.$image->id).'" class="delete" title="Delete"><span class="glyphicon glyphicon-remove"></i></a></td>';
                  echo '</tr>';
              } ?>
      </table>
      <?php }
      else
        echo "You can add up to 10 images. Currently there are none in your profile. Please ad images to increase visibility in the app.";
      ?>
    </div>
	
</body>
</html>