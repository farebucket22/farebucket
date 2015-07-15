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
            var oTable = $('#activity-table').dataTable({ 
                "aaSorting": [],
                "bJQueryUI": true,
                "sPaginationType": "full_numbers" 
            });
            
            $('.delete').click(function(e){
            e.preventDefault;
                if(confirm("Are you sure you want to remove this activity?"))
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
            <h2>Activities</h2>
            <div class="span12 lists">
                <h3 style="margin-top:0%";>Available Activities</h3>
                <?php if($activities) { ?>
                <table id="activity-table" style="">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description Short</th>
                            <th>Details</th>
                            <th>Images</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            <th>View Images</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($activities as $activity) {
                            echo '<tr>';
                            echo '<td>'.$activity->activity_name.'</td>';
                            echo '<td>'.$activity->activity_description_short.'</td>';
                            echo '<td>'.$activity->activity_details.'</td>';
                            echo '<td>'.$activity->activity_main_image.'</td>';
                            echo '<td><a href="'.site_url('admin/activity/edit?activity_id='.$activity->activity_id).'" title="Edit"><span class="glyphicon glyphicon-edit"></i></a></td>';
                            echo '<td><a href="'.site_url('admin/activity/delete?activity_id='.$activity->activity_id).'" class="delete" title="Delete"><span class="glyphicon glyphicon-remove"></i></a></td>';
                            echo '<td><a href="'.site_url('admin/image?activity_id='.$activity->activity_id).'" title="Images"><span class="glyphicon glyphicon-eye-open"></i></a></td>';
                            echo '</tr>';
                        } ?>
                    </tbody>
                </table>
            <?php }
            else
              echo "No Activities available.";
            ?>
          </div>
        </div>
</body>
</html> 