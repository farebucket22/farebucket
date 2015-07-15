<style type="text/css">
	.wrap{
		margin-top: 50px;
	}
</style>
<div class="wrap">
	<div class="container-fluid main clear-top">
    	<center><h2>Sorry, your transaction could not be completed. Please contact Customer Support for further assistance.</h2></center>
    	<?php if( isset($_SESSION['calling_controller_name']) && $_SESSION['calling_controller_name'] == 'flights' ):?>
      	<center><h4><a href="<?php echo site_url('flights');?>">Reset Search</a></h4></center>
      	<?php elseif( isset($_SESSION['currentUrl']) && $_SESSION['calling_controller_name'] == 'cabs' ):?>
      	<center><h4><a href="<?php echo site_url('cabs');?>">Reset Search</a></h4></center>
      	<?php endif;?>
	</div>
</div>