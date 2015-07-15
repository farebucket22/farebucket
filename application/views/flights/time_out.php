<?php
    $cust_support_data = cust_support_helper();
?>
<div class="wrap">
	<div class="container-fluid main clear-top">    
	<center><h2>Your session has timed out due to inactivity on the page. Please search again.</h2></center>
    <?php if( isset($_SESSION['currentSearchUrl']) ):?>
      <center><h4><a href="<?php echo site_url($_SESSION['currentSearchUrl']);?>">Search Again</a></h4></center>
    <?php else:?>
    	<center><h2>Booking Failed! Please Try Again</h2></center>
	<?php endif;?>
	</div>
</div>