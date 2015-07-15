<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">    
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css"/>
         
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
                if(confirm("Are you sure you want to remove this leave?"))
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
      <h2>Leaves</h2>
      
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>
          
        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
        <?php endif; ?>
      
      <legend>Add a Leave </legend>
      <div class="span6 create-form">
          <form action="<?php echo site_url('admin/leave/add'); ?>" method="post" enctype="multipart/form-data">
              <input type="text" class="input-block-level" placeholder="Leave Name" id="name" name="activity_leave_name" value="<?php echo set_value('name'); ?>">
                <input type="text" class="input-block-level" placeholder="Leave Dates" id="leave_date" name="activity_leave_dates" value="<?php echo set_value('leave_date'); ?>">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Select Weekly Holidays: </label><br>
                        <label><input type="checkbox" name = "activity_leave_days[]" value="0" /> Sunday </label>
                        <label><input type="checkbox" name = "activity_leave_days[]" value="1" /> Monday </label>
                        <label><input type="checkbox" name = "activity_leave_days[]" value="2" /> Tuesday </label>
                        <label><input type="checkbox" name = "activity_leave_days[]" value="3" /> Wednesday </label>
                        <label><input type="checkbox" name = "activity_leave_days[]" value="4" /> Thursday </label>
                        <label><input type="checkbox" name = "activity_leave_days[]" value="5" /> Friday </label>
                        <label><input type="checkbox" name = "activity_leave_days[]" value="6" /> Saturday </label>
                    </div>
                </div>

                <button class="btn btn-success span12" type="submit">Create</button>
          </form>
      </div>
      
      <div class="span12 lists">
      <h3>Available leaves</h3>
      <?php if($leaves) { ?>
      <table id="sub-cat-table">
          <thead>
              <tr>
                  <th>Leave Name</th>
                  <th>Activity Name</th>
                  <th>Leave Dates</th>
                  <th>Leave Days</th>
                  <th>Edit</th>
                  <th>Delete</th>
              </tr>
          </thead>
          <tbody>
              <?php 
                $noActivityFlag = 0;
                foreach($leaves as $leave) {
                  echo '<tr>';
                  echo '<td>'.$leave->activity_leave_name.'</td>';
                  foreach($activities as $activity){
                    if($activity->activity_id == $leave->activity_id){
                      echo '<td>'.$activity->activity_name.'</td>';
                    }
				  }
					if( $leave->activity_id == 0 && $noActivityFlag == 0 ){
                      echo '<td>No Activity</td>';
                    }
                  echo '<td>'.$leave->activity_leave_dates.'</td>';
                  echo '<td>'.$leave->activity_leave_days.'</td>';
                  echo '<td><a href="'.site_url('admin/leave/edit?activity_leave_id='.$leave->activity_leave_id).'" title="Edit"><span class="glyphicon glyphicon-edit"></i></a></td>';
                  echo '<td><a href="'.site_url('admin/leave/delete?activity_leave_id='.$leave->activity_leave_id).'" class="delete" title="Delete"><span class="glyphicon glyphicon-remove"></i></a></td>';
                  echo '</tr>';
                } 
              ?>
      </table>
      <?php }
      else
        echo "No Leaves available.";
      ?>
    </div>
    </div>
</body>
</html>