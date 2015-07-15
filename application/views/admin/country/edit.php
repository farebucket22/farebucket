<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">
    
        
    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src="<?php echo base_url('js/vendor/bootstrap.min.js');?>"></script>

    <style>
    .user-dropdown{
          width:90px;
      }
</style>
   
</head>
<body>
    <div class="container content">
      <h2 style="margin-top:60px;">Edit Country</h2>
      
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>
          
        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
        <?php endif; ?>
      
      <div class="edit-form span6">
      <form action="<?php echo site_url('admin/country/update?id='.$country->activity_country_id); ?>" method="post" enctype="multipart/form-data">          
          <label class="control-label">Country Name</label>
          <input type="text" class="input-block-level" placeholder="Country Name" name="name" value="<?php echo $country->activity_country_name; ?>">
          <button class="btn btn-info span12" type="submit">Update</button>
          <a href="<?php echo site_url('admin/country'); ?>" class="btn btn-danger span12 upd-cancel">Cancel</a>
      </form>
      </div>
    </div>
</body>
</html>
