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
          <select name="display_on_site" class="span6">
            <option value="">Display on Site?</option>
            <option <?php if($discount->display_on_site == 'Yes')echo 'selected';?>>Yes</option>
            <option <?php if($discount->display_on_site == 'No')echo 'selected';?>>No</option>
          </select>
          <button class="btn btn-info span12" type="submit">Update</button>
          <a href="<?php echo site_url('admin/discount'); ?>" class="btn btn-danger span12 upd-cancel">Cancel</a>
      </form>
      </div>
    </div>
</body>
</html>
