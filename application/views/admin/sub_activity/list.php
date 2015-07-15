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
    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src=<?php echo base_url('js/vendor/bootstrap.min.js'); ?> ></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    <script src=<?php echo base_url('bootstrap/js/custom.js'); ?> ></script>
     <script type="text/javascript">
        $(function(){
            var oTable = $('#sub-activity-table').dataTable({ 
                "aaSorting": [],
                "bJQueryUI": true,
                "sPaginationType": "full_numbers" 
            });
            
            $('.delete').click(function(e){
            e.preventDefault;
                if(confirm("Are you sure you want to remove this sub activity?"))
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
            <h2>Sub Activities</h2>
            <div class="span12 lists">
                <h3 style="margin-top:0%;">Available Sub Activities</h3>
                <?php if($sub_activities) { ?>
                <table id="sub-activity-table" style="">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Activity_Name</th>
                            <th>Max No of Tickets</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sub_activities as $subactivity) {
                            echo '<tr>';
                            echo '<td>'.$subactivity->activity_sub_type_name.'</td>';
                            echo '<td>'.$subactivity->activity_sub_type_description.'</td>';
                            foreach($activities as $activity){
                                if($activity->activity_id == $subactivity->activity_id)
                                {
                                echo '<td>'.$activity->activity_name.'</td>';
                                }
                              }   
                            echo '<td>'.$subactivity->activity_sub_type_max_tickets.'</td>';
                            
                            echo '<td><a href="'.site_url('admin/sub_activity/edit?activity_sub_type_id='.$subactivity->activity_sub_type_id).'" title="Edit"><span class="glyphicon glyphicon-edit"></i></a></td>';
                            echo '<td><a href="'.site_url('admin/sub_activity/delete?activity_sub_type_id='.$subactivity->activity_sub_type_id).'" class="delete" title="Delete"><span class="glyphicon glyphicon-remove"></i></a></td>';
                            echo '</tr>';
                        } ?>
                    </tbody>
                </table>
            <?php }
            else
              echo "No Sub Activities available.";
            ?>
          </div>
        </div>
</body>
</html>