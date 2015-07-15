<?php $url = explode("/",$_SERVER['REQUEST_URI']);?>



<!-- /container --> 
        <div class="container-fluid">      
            <div class="row footer">
                <ul class="col-xs-24 col-sm-19 footerNav">
                    <li class="col-xs-24 col-sm-2 col-sm-offset-1 <?php if( $url[count($url)-1] == 'about')echo 'active_tab';?>"><a href="<?php echo site_url('common/about');?>">About</a></li>
                    <li class="col-xs-24 col-sm-2 <?php if( $url[count($url)-1] == 'contact')echo 'active_tab';?>"><a href="<?php echo site_url('common/contact');?>">Contact</a></li>
                    <li class="col-xs-24 col-sm-2 <?php if( $url[count($url)-1] == 'privacy')echo 'active_tab';?>"><a href="<?php echo site_url('common/privacy');?>">Privacy</a></li>
                    <li class="col-xs-24 col-sm-2 <?php if( $url[count($url)-1] == 'terms')echo 'active_tab';?>"><a href="<?php echo site_url('common/terms');?>">Terms</a></li>
                    <li class="col-xs-24 col-sm-2"><a href="#" data-toggle="modal" data-target="#app_modal">Mobile</a></li>
                </ul>
                <div class="logoContainerFooter col-xs-24 col-sm-5">
                    <!-- Go to www.addthis.com/dashboard to customize your tools -->
                    <div class="right-text addthis_sharing_toolbox"></div>
                </div>
            </div>
            <div class="row center-align-text copyright"><div class="col-xs-24">© 2013–2015 Reddytrip LLP.</div></div>
        </div>

        <!--App-Modal screen-->
        <div class="modal fade" id="app_modal">
            <div class="modal-dialog">
                <div class="modal-content">   
                    <div class="modal-header">
                        <center>Farebucket Mobile Apps</center>
                    </div>         
                    <div class="modal-body">
                        <div class="images">
                            <img src="<?php echo base_url();?>img/btn-android.png" alt="android">
                            <img src="<?php echo base_url();?>img/btn-apple.png" alt="IOS">
                            <center> <span class="h4">Coming Soon...</span> </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end App-Modal screen-->

        <!-- Large modal -->

       <!--  <div class="modal fade privacy" tabindex="-1" role="dialog" aria-labelledby="Privacy" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-custom-height">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="Privacy">Privacy<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <?php //$this->load->view('common/privacy.php');?>
                    </div>
                </div>
            </div>
        </div>
 -->
        <!-- Large modal -->

        <!-- <div class="modal fade termsAndConditions" tabindex="-1" role="dialog" aria-labelledby="Terms" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-custom-height">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="Terms">Terms and Conditions<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <?php //$this->load->view('common/termsncond.php');?>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X');ga('send','pageview');
        </script>
        <script type="text/javascript">
            $(".headerNav li").each(function(){
                $(this).removeClass("activated");
            });
            if(window.location.pathname.indexOf("activity") >= 0){
                $(".activitiesLink").addClass("activated");
            }else if(window.location.pathname.indexOf("flights") >= 0 || window.location.pathname.indexOf("Flights") >= 0){
                $(".flightsLink").addClass("activated");
            }else if(window.location.pathname.indexOf("login") >= 0 || window.location.pathname.indexOf("Flights") >= 0){
                $(".loginLink").addClass("activated");
            }else if(window.location.pathname.indexOf("user") >= 0 || window.location.pathname.indexOf("Flights") >= 0){
                $(".userLink").addClass("activated");
            }
        </script>
    </body>
</html>