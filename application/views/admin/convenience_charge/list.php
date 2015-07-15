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
                if(confirm("Are you sure you want to remove this city?"))
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
      
      <div class="span6 create-form">
      </div>
      
      <div class="span12 lists">
      <h3>Current Convenience Charge</h3>
      <?php if($charge) { ?>
      <table id="sub-cat-table">
          <thead>
              <tr>
                  <th>module</th>
                  <th>message</th>
                  <th>Charge in Percent</th>
                  <th>Edit</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach($charge as $charge) {
                  echo '<tr>';
                  echo '<td>'.$charge->module.'</td>';
                  echo '<td>'.$charge->convenience_charge_msg.'</td>';
                  echo '<td>'.$charge->convenience_charge.'</td>';
                  echo '<td><a href="'.site_url('admin/convenience_charge/edit?id='.$charge->id).'" title="Edit"><span class="glyphicon glyphicon-edit"></i></a></td>';
                  echo '</tr>';
              } ?>
      </table>
      <?php }
      else
        echo "No charges are created.";
      ?>
    </div>
    </div>
</body>
</html>