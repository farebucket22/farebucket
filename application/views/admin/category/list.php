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
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src=<?php echo base_url('js/vendor/bootstrap.min.js'); ?> ></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    
    <script type="text/javascript">
        $(function(){
            var oTable = $('#category-table').dataTable({ 
                "aaSorting": [],
                "bJQueryUI": true,
                "sPaginationType": "full_numbers" 
            });
            
            $('.delete').click(function(e){
            e.preventDefault;
                if(confirm("Are you sure you want to remove this category?"))
                    return true;
                else
                    return false;
            });
        });
    </script>
</head>
<body>    
    <div class="container content">
      <h2 style="margin-top:18px;">Categories</h2>
      
       <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
       <?php endif; ?>
      
      <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
       <?php endif; ?>
      
      <legend>Add a Category</legend>
      <div class="span6 create-form">
      <form action="<?php echo site_url('admin/category/add'); ?>" method="post" enctype="multipart/form-data">          
          <input type="text" class="input-block-level" placeholder="Category Name" id="name" name="name" value="<?php echo set_value('name'); ?>">
          <button class="btn btn-success span12" type="submit">Create</button>
      </form>
      </div>
      
      <div class="span12 lists">
      <h3>Available Categories</h3>
      <?php if($categories) { ?>
      <table id="category-table">
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Edit</th>
                  <th>Delete</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach($categories as $category) {
                  echo '<tr>';
                  echo '<td>'.$category->activity_category_name.'</td>';
                  echo '<td><a href="'.site_url('admin/category/edit?id='.$category->activity_category_id).'" title="Edit"><span class="glyphicon glyphicon-edit"></i></a></td>';
                  echo '<td><a href="'.site_url('admin/category/delete?id='.$category->activity_category_id).'" class="delete" title="Delete"><span class="glyphicon glyphicon-remove"></i></a></td>';
                  echo '</tr>';
              } ?>
      </table>
      <?php }
      else
        echo "No Categories available.";
      ?>
        </div>
      </div>
</body>
</html>