<div class="wrap">
	<div class="container-fluid main clear-top center-align-text">
		<h2>Booking Failed!</h2> 
		<h2>Please Contact customer support on</h2>
		<h2>support@farebucket.com Or +91-1234567890</h2>
    <?php if( isset($_SESSION['currentSearchUrl']) ):?>
      <center><h4><a href="<?php echo site_url($_SESSION['currentSearchUrl']);?>">Search Again</a></h4></center>
    <?php endif;?>
	</div>
</div>