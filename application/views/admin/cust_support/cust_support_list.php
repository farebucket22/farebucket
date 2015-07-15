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
    <style>
    .user-dropdown{
          width:90px;
      }
</style>
</head>
<body>
    <div class="container content">
        <h2 style="margin-top:60px;">Categories</h2>
      
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>

        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('admin/cust_support/update_cust_support_data')?>" id="cust_support_form" method="post">
            <input type="text" style="display:none;" name="id" value="1" />
            <div class="row">
                <div class="col-lg-5 form-group">
                    <label for="phone_number">Phone Number:</label><br />
                    <input type="text" name="phone_number" class="form-control" disabled="true" value="<?php echo $cust_details->phone_number;?>" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 form-group">
                <label for="email" class="control-label">Email:</label><br />
                    <input type="text" name="email" class="form-control" disabled="true" value="<?php echo $cust_details->email;?>" />
                </div>
            </div>
            <div class="row update-btns" style="margin-top: 10px; display:none;">
                <div class="col-lg-5 form-group">
                    <button type="button" class="btn btn-primary" id="save_button">Save</button>
                    <button type="button" class="btn btn-primary" id="cancel_button">Cancel</button>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-lg-5 form-group">
                    <button type="button" id="update_button" class="btn btn-info">Update</button>
                </div>
            </div>
        </form>

    </div>
</body>

<script>
    (function(){
        $('#update_button').on('click', function(){
            $(this).hide();
            $('.update-btns').slideDown();
            $('#cust_support_form input').each(function(i, val){
                $(val).removeAttr('disabled');
            });
        });

        $('#cancel_button').on('click', function(){
            $('.update-btns').slideUp(300, function(){
                $('#update_button').show();
            });
            $('#cust_support_form input').each(function(i, val){
                $(val).attr('disabled', true);
            });
        });

        $('#save_button').on('click', function(){
            $('#cust_support_form').submit();
        });
    })();
</script>
</html>