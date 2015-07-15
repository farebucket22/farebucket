<?php
    $cust_support_data = cust_support_helper();
?>
<div class="wrap">
	<div class="container-fluid main clear-top">
    
    <?php if( isset($_GET) ):?>
    	<center><h2>An unexpected error occurred. Please contact customer care at <?php echo $cust_support_data->phone_number; ?> for further assistance.</h2></center>
    <?php elseif( isset($_SESSION['currentSearchUrl']) ):?>
      <center><h4><a href="<?php echo site_url($_SESSION['currentSearchUrl']);?>">Search Again</a></h4></center>
    <?php else:?>
    	<center><h2>Booking Failed! Please Try Again</h2></center>
	<?php endif;?>
	</div>
</div>