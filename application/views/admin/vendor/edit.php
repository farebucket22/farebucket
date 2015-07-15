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
    
</head>
<style>
    .user-dropdown{
          width:90px;
      }
</style>
<body>
    <div class="container content">
      <h2 style="margin-top:60px;">Edit Vendor</h2>
      
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>
          
        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
        <?php endif; ?>
      
      <div class="edit-form span6">
      <form action="<?php echo site_url('admin/vendor/update?id='.$vendor->id); ?>" method="post" enctype="multipart/form-data">
          <label class="control-label">Vendor Name</label>
          <input type="text" class="input-block-level" placeholder="Vendor Name" id="name" name="vendor_name" value="<?php echo $vendor->vendor_name; ?>">
          <label class="control-label">Vendor Email</label>
          <input type="text" class="input-block-level" placeholder="Vendor Email" id="email" name="vendor_email" value="<?php echo $vendor->vendor_email; ?>">
          <label class="control-label">Vendor Phone Number</label>
          <input type="text" class="input-block-level" placeholder="Vendor Phone Number" id="phone_number" name="vendor_number" value="<?php echo $vendor->vendor_number; ?>">
          <button class="btn btn-info span12" type="submit">Update</button>
          <a href="<?php echo site_url('admin/vendor'); ?>" class="btn btn-danger span12 upd-cancel">Cancel</a>
      </form>
      </div>
    </div>
</body>
</html>