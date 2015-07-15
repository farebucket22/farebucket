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
    <script src=<?php echo base_url('js/vendor/bootstrap.min.js'); ?> ></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function(){
            var oTable = $('#vendor-table').dataTable({ 
                "aaSorting": [],
                "bJQueryUI": true,
                "sPaginationType": "full_numbers" 
            });
            
            $('.delete').click(function(e){
            e.preventDefault;
                if(confirm("Are you sure you want to remove this vendor?"))
                    return true;
                else
                    return false;
            });
        });
    </script>
</head>
<style>
    .user-dropdown{
          width:90px;
      }
</style>
<body>
    <div class="container content">
      <h2>Vendors</h2>
      
       <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
       <?php endif; ?>
      
      <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
       <?php endif; ?>
      
      <legend>Add a Activity Vendor</legend>
      <div class="span6 create-form">
      <form action="<?php echo site_url('admin/vendor/add'); ?>" method="post" enctype="multipart/form-data">          
          <input type="text" class="input-block-level" placeholder="Vendor Name" id="name" name="vendor_name" value="<?php echo set_value('name'); ?>">
          <input type="text" class="input-block-level" placeholder="Vendor Email" id="email" name="vendor_email" value="<?php echo set_value('email '); ?>" >
          <input type="text" class="input-block-level" placeholder="Vendor Phone Number" id="phone_number" name="vendor_number" value="<?php echo set_value('phone_number'); ?>" >
          <button class="btn btn-success span12" type="submit">Create</button>
      </form>
      </div>
      
      <div class="span12 lists">
      <h3>Available Activity Vendors</h3>
      <?php if($vendors) { ?>
      <table id="vendor-table">
          <thead>
              <tr>
                  <th>Vendor Name</th>
                  <th>Vendor Email</th>
                  <th>Vendor Phone Number</th>
                  <th>Edit</th>
                  <th>Delete</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach($vendors as $vendor) {
                  echo '<tr>';
                  echo '<td>'.$vendor->vendor_name.'</td>';
                  echo '<td>'.$vendor->vendor_email.'</td>';
                  echo '<td>'.$vendor->vendor_number.'</td>';
                  echo '<td><a href="'.site_url('admin/vendor/edit?id='.$vendor->id).'" title="Edit"><span class="glyphicon glyphicon-edit"></i></a></td>';
                  echo '<td><a href="'.site_url('admin/vendor/delete?id='.$vendor->id).'" class="delete" title="Delete"><span class="glyphicon glyphicon-remove"></i></a></td>';
                  echo '</tr>';
              } ?>
      </table>
      <?php }
      else
        echo "No Vendors available.";
      ?>
    </div>
    </div>

</body>
</html>