<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css" />
    <link href=<?php echo base_url('css/uploadpreview/bootstrap-fileupload.min.css'); ?> rel="stylesheet" type="text/css">

    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src="<?php echo base_url('js/vendor/bootstrap.min.js'); ?>" ></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url('bootstrap/js/custom.js'); ?>" ></script>
    <script src="<?php echo base_url('scripts/uploadpreview/bootstrap-fileupload.min.js'); ?>"></script>

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
	    <div class="row">
		    <div class="col-lg-6">
		    	<form action="<?php echo site_url('admin/flight/add_new_background?id='.$data)?>" method="post">
					<div class="form-group">
						<input type="text" name="image_url" class="form-control" placeholder="Image URL"/>
					</div>
					<div class="form-group">
						<input type="text" name="image_text" class="form-control" placeholder="Image Text"/>
					</div>
                    <div class="form-group">
                        <select name="background_module" class="form-control">
                          <option value="">Select Discount Module</option>
                          <option value="flights">Flight</option>
                          <option value="activities">Activity</option>
                          <option value="cabs">Cab</option>
                          <option value="buses">Bus</option>
                          <option value="hotels">Hotel</option>
                        </select>
                    </div>
					<button type="submit" class="btn btn-default">submit</button>
		    	</form>
	    	</div>
    		<div class="col-lg-6">
		        <?php if($this->session->flashdata('returnMsg')) : ?>
		            <?php echo $this->session->flashdata('returnMsg')?>
		        <?php endif; ?>
				<?php if (validation_errors()) : ?>
					<div class="alert alert-danger" style="margin-top:8%;">
						<h6><?php echo validation_errors(); ?></h6>
					</div>
				<?php endif; ?>
			</div>
		</div>
    </div>
</body>
<script>
	(function(){
		$('#image').on('change', function(){
			$.ajax({
				type: "post",
				url: "<?php echo site_url('admin/image/add_background_image');?>"
				
			})
		})
	})();
</script>