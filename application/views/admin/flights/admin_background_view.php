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
</head>
<style>
    .main-st{
        margin-top: 100px;
    }
</style>
<style>
    .user-dropdown{
          width:90px;
      }
</style>
<body>
    <div class="container main-st">
    <?php if( !empty($backgrounds) ):?>
        <table id="bg-table" class="table table-hover">
            <thead>
                <th>Image</th>
                <th>Image URL</th>
                <th>Image Text</th>
                <th>Module</th>
            </thead>
            <tbody>
                <?php foreach( $backgrounds as $bg ):?>
                    <tr>
                        <td><?php echo $bg->image;?></td>
                        <td><?php echo $bg->image_url;?></td>
                        <td><?php echo $bg->image_text;?></td>
                        <td><?php echo $bg->background_module;?></td>
                        <td><a href="<?php echo site_url('admin/flight/del_background?del_id='.$bg->id);?>" class="btn btn-default">Delete</a></td>
                        <td><a href="<?php echo site_url('admin/flight/edit?id='.$bg->id);?>" class="btn btn-default">Edit</a></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    <?php else:?>
        <?php echo "No booked tickets available.";?>
    <?php endif;?>
    <a class="btn btn-default" href="<?php echo site_url('admin/flight/add_background');?>">Add background</a>
    </div>
</body>
<script>
    (function(){
        var oTable = $('#flight-table').DataTable({ 
            "aaSorting": [],
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('id', 'row-'+iDataIndex);
            },
        });
    })();
</script>