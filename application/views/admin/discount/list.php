<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">    
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css" />
    
      
    <link href=<?php // echo base_url('bootstrap/css/bootstrap-responsive.css');?> rel="stylesheet" type="text/css">
    <!-- HTML5 shim for IE backwards compatibility -->
        <!--[if lt IE 9]>
        <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    
    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src="<?php echo base_url('js/vendor/bootstrap.min.js');?>"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function(){
            var oTable = $('#sub-cat-table').dataTable({ 
                "aaSorting": [],
                "bJQueryUI": true,
                "sPaginationType": "full_numbers" 
            });
            
            $('.delete').click(function(e){
            e.preventDefault;
                if(confirm("Are you sure you want to remove this discount?"))
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
      <h2 style="">Discounts</h2>
      
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>
          
        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
        <?php endif; ?>
      
      <legend>Add a Discount</legend>
      <div class="span6 create-form">
      <form action="<?php echo site_url('admin/discount/add'); ?>" method="post" enctype="multipart/form-data">
         <input type="text" class="input-block-level" placeholder="Discount Name" id="dis_name" name="discount_code_name" value="<?php echo set_value('dicount_code_name'); ?>">
         <input type="number" class="input-block-level" placeholder="Discount Value" id="dis_amt" name="discount_code_value" value="<?php echo set_value('discount_code_value'); ?>">
          <select name="discount_code_type" class="span6">
              <option value="">Select Discount Type</option>
              <option value="amount">Amount</option>
              <option value="percent">Percent</option>
          </select>
          <select name="discount_code_status" class="span6">
              <option value="">Select Discount Status</option>
              <option value="active">Active</option>
              <option value="inactive">In Active</option>
          </select>
          <select name="discount_code_module" class="span6">
              <option value="">Select Discount Module</option>
              <option value="all">All</option>
              <option value="flights">Flight</option>
              <option value="activities">Activity</option>
              <option value="cabs">Cab</option>
              <option value="buses">Bus</option>
              <option value="hotels">Hotel</option>
          </select>
          <select name="display_on_site" class="span6">
            <option value="">Display on Site?</option>
            <option>Yes</option>
            <option>No</option>
          </select>
          <button class="btn btn-success span12" type="submit">Create</button>
      </form>
      </div>
      
      <div class="span12 lists">
      <h3>Available Discounts</h3>
      <?php if($discounts) { ?>
      <table id="sub-cat-table">
          <thead>
              <tr>
                  <th>Discount Code Name</th>
                  <th>Discount Code Value</th>
                  <th>Discount Code Type</th>
                  <th>Discount Code Status</th>
                  <th>Discount Code Module</th>
                  <th>Display on site</th>
                  <th>Edit</th>
                  <th>Delete</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach($discounts as $discount) {
                  echo '<tr>';
                  echo '<td>'.$discount->discount_code_name.'</td>';
                  echo '<td>'.$discount->discount_code_value.'</td>';
                  echo '<td>'.$discount->discount_code_type.'</td>';
                  if($discount->discount_code_status == 1)
                    echo '<td>Active</td>';
                  else
                    echo '<td>In Active</td>';
                  echo '<td>'.$discount->discount_code_module.'</td>';
                  echo '<td>'.$discount->display_on_site.'</td>';
                  echo '<td><a href="'.site_url('admin/discount/edit?discount_code_id='.$discount->discount_code_id).'" title="Edit"><span class="glyphicon glyphicon-edit"></i></a></td>';
                  echo '<td><a href="'.site_url('admin/discount/delete?discount_code_id='.$discount->discount_code_id).'" class="delete" title="Delete"><span class="glyphicon glyphicon-remove"></i></a></td>';
                  echo '</tr>';
              } ?>
      </table>
      <?php }
      else
        echo "No discounts available.";
      ?>
    </div>
    </div>
</body>
</html>