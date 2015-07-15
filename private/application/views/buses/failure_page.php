<div class="wrap">
    <div class="container main clear-top center-align-text">
        <?php if( isset($data) || isset($_GET) ):?>
            <?php if( isset($_GET) && !empty($_GET) ): ?>
                <div class="vam">
                    <h2><?php echo "Sorry, The selected seat is no longer available";?></h2>
                    <?php $url = site_url('bus_api/buses/getAvailableTrips?').$_SESSION['busSearchQueryString'];?>
                    <span class="h4 mod_search_error" onclick="javascript:location.href = '<?php echo $url;?>'">Modify Search</span> <span class="h4">|</span> <span class="h4 reset_search_error" onclick="javascript:location.href = '<?php echo site_url('buses');?>'">Reset Search</span></center>
                </div>
            <?php else:?>
                <div class="vam">
                    <h2><?php echo $data;?></h2>
                    <span class="h4 mod_search_error" onclick="javascript:history.back(2)">Modify Search</span> <span class="h4">|</span> <span class="h4 reset_search_error" onclick="javascript:location.href = '<?php echo site_url('buses');?>'">Reset Search</span></center>
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