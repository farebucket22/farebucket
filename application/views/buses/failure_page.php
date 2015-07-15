<div class="wrap">
    <div class="container main clear-top center-align-text">
        <?php if( isset($data) || isset($_GET) ):?>
            <?php if( isset($data) ): ?>
                <div class="vam">
                    <h2><?php echo $data;?></h2>
                    <?php $url = $_SESSION['currentUrlBus'];?>
                    <span class="h4 reset_search_error" onclick="javascript:location.href = '<?php echo site_url('buses');?>'">Reset Search</span></center>
                </div>
            <?php elseif( isset($_GET) && !empty($_GET) ): ?>
                <div class="vam">
                    <h2><?php echo "Sorry, The selected seat is no longer available";?></h2>
                    <?php $url = $_SESSION['currentUrlBus'];?>
                    <span class="h4 mod_search_error" onclick="javascript:location.href = '<?php echo $url;?>'">Modify Search</span> <span class="h4">|</span> <span class="h4 reset_search_error" onclick="javascript:location.href = '<?php echo site_url('buses');?>'">Reset Search</span></center>
                </div>
            <?php else:?>
                <div class="vam">
                    <h2>Sorry, your transaction has failed. Please try again.</h2>
                    <span class="h4 reset_search_error" onclick="javascript:location.href = '<?php echo site_url('buses');?>'">Reset Search</span></center>
                </div>
            <?php endif;?>
        <?php else:?>
            <h2>Booking Failed! Please Contact customer support on - support@farebucket.com Or +91-1234567890</h2>
            <?php if( isset($_SESSION['currentSearchUrl']) ):?>
                <center><h4><a href="<?php echo site_url($_SESSION['currentSearchUrl']);?>">Search Again</a></h4></center>
            <?php endif;?>
        <?php endif;?>
    </div>
</div>