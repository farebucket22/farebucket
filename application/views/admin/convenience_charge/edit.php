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
      <h2 style="margin-top:60px;">Edit Convenience Charge</h2>
      
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>
          
        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
        <?php endif; ?>
      
      <div class="edit-form span6">
      <form action="<?php echo site_url('admin/convenience_charge/update'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $charge[0]->id;?>" />
            <input type="text" class="input-block-level" placeholder="Change Convenience Charge" id="con_name" name="convenience_charge" value="<?php echo $charge[0]->convenience_charge; ?>">
            <input type="text" class="input-block-level" placeholder="Change Convenience Charge Message" id="con_name" name="convenience_charge_msg" value="<?php echo $charge[0]->convenience_charge_msg; ?>">
            <input type="text" class="input-block-level" placeholder="Change Convenience Module" id="con_name" name="module" value="<?php echo $charge[0]->module; ?>">
            <button class="btn btn-info span12" type="submit">Update</button>
            <a href="<?php echo site_url('admin/convenience_charge'); ?>" class="btn btn-danger span12 upd-cancel">Cancel</a>
      </form>
      </div>
    </div>
</body>
</html>
