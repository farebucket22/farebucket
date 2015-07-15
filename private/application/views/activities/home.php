<style>
    .extra-margin{
        margin-top:7px;
    }
    .sod_placeholder{
        text-transform: none;
        height:13px;
    }
</style>
<div class="wrap">
    <div class="container-fluid main clear-top">
        <div class="row">
            <div class="col-xs-24 col-sm-12 col-sm-offset-6 marketingMessage">200+ Activities from 40+ regions around the world!</div>
        </div>
        
        <div class="row">
            <?php
              $include_path = APPPATH."views/common/search.php";
              include ($include_path);?>
        </div>
        
        <div class="row specialActivityArea">
            <a class="homeBGLink" href="#"><div class="col-xs-24 col-sm-7 col-sm-offset-17 specialActivityMessage"></div></a>
        </div>
    </div>
    
    <div class="slideLeftBtn"></div>
    <div class="slideRightBtn"></div>
</div>

<script type="text/javascript" src="<?php echo base_url('js/vendor/vegas/jquery.vegas.js'); ?>"></script>
<script type="text/javascript">

(function(){
    var backgrounds_images = <?php echo json_encode($data);?>;
    $.vegas('slideshow', {
           backgrounds:[
               {     
                src:'<?php echo base_url("img/activities/'+backgrounds_images[0].image+'"); ?>', fade:1000,
                   load:function() {
                        $(".homeBGLink").attr('href', backgrounds_images[0].image_url);
                        $(".specialActivityMessage").html(backgrounds_images[0].image_text);
                   }
               },
               { src:'<?php echo base_url("img/activities/'+backgrounds_images[1].image+'"); ?>', fade:1000,
                   load:function() {
                    $(".homeBGLink").attr('href', backgrounds_images[1].image_url);
                       $(".specialActivityMessage").html(backgrounds_images[1].image_text);
                   }
               },
               { src:'<?php echo base_url("img/activities/'+backgrounds_images[2].image+'"); ?>', fade:1000,
                   load:function() {
                    $(".homeBGLink").attr('href', backgrounds_images[2].image_url);
                       $(".specialActivityMessage").html(backgrounds_images[2].image_text);
                   }
               }
           ]
    });
    
    $(".slideLeftBtn").click(function(){
        $.vegas('previous');
    });
    
    $(".slideRightBtn").click(function(){
        $.vegas('next');
    });

    $(".homeBGLink").hover(function(){
      //$.vegas('pause');
    });
    
})();
</script>