<style>
    #accordion1, #accordion2, .inner-addon{
            margin-top:5px; 
    }
    .sod_placeholder{
        text-transform: none;
        height:13px;
    }
</style>
<div class="wrap">
    <div class="container-fluid flights-wrapper clear-top">
    <div class="row"><div class="col-lg-24 center-align-text"><h1 class='marketingMessage'>Search for Cabs</h1></div></div>
        <!-- tabs -->
        <ul class="nav nav-tabs flights-nav cab-nav" role="tablist">
            <li class="active"><a href="#outStation" role="tab" data-toggle="tab">OutStation</a></li>
            <li><a href="#local" role="tab" data-toggle="tab">Local</a></li>
        </ul>
        <!-- tabs end -->
        <!-- tab contents, insert within tab-content -->
        <div class="tab-content change-height">
            <div class="tab-pane pane-height fade in active" id="outStation">
                <form action="<?php echo site_url('cab_api/cabs/search_cabs');?>" method="get" id="form-submit-2" enctype="multipart/form-data">
                    <div class="form-padding row">
                        <div class="form-group">
                            <div class="col-lg-8 control-label">
                                <select name="out_cab_src" id="outstat_cab_src">
                                    <option value="loading">Loading...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 control-label has-error outstation_destination">
                                <select name="out_cab_dest" id="outstat_cab_dest">
                                    <option value="Select Source First">Select Source First</option>
                                </select>
                                <small class="help-block" style="display:none;">Please choose a source and destination</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 control-label">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="to_date" id="date-2" readonly class="form-control" type="text" placeholder="Depart Date" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-padding row">
                        <div class="form-group">
                            <div class="pull-right col-lg-6 control-label">
                                <select name="out_duration" id="outstat_travel_time">
                                    <option value="sameday">Loading...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="option-bottom-tab search-padding row">
                        <div class="pull-right col-lg-5 remove-padding">
                            <button id="form-submit-button" class="form-submit-button-outstation" type="submit"><span class="glyphicon glyphicon-search"></span> SEARCH</button>
                        </div>
                    </div>
                    <input type="text" name="flight_type" value="outstation" style="display:none;" readonly />
                </form>
            </div>
            <div class="tab-pane pane-height fade in" id="local">
                <form action="<?php echo site_url('cab_api/cabs/search_cabs');?>" method="get" id="form-submit-1" enctype="multipart/form-data">
                    <div class="form-padding row">
                        <div class="form-group">
                            <div class="col-lg-8 control-label">
                                <select name="local_cab_src" id="local_cab_src">
                                    <option value="loading">Loading...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 control-label">
                                <select name="local_cab_dest" id="local_cab_dest">
                                    <option value="5" selected>5Hrs / 50Kms</option>
                                    <option value="6">6Hrs / 60Kms</option>
                                    <option value="7">7Hrs / 70Kms</option>
                                    <option value="8">8Hrs / 80Kms</option>
                                    <option value="9">9Hrs / 90Kms</option>
                                    <option value="10">10Hrs / 100Kms</option>
                                    <option value="11">11Hrs / 110Kms</option>
                                    <option value="12">12Hrs / 120Kms</option>
                                    <option value="13">13Hrs / 130Kms</option>
                                    <option value="14">14Hrs / 140Kms</option>
                                    <option value="15">15Hrs / 150Kms</option>
                                    <option value="16">16Hrs / 160Kms</option>
                                    <option value="17">17Hrs / 170Kms</option>
                                    <option value="18">18Hrs / 180Kms</option>
                                    <option value="19">19Hrs / 190Kms</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="to_date" id="date-1" readonly class="form-control" type="text" placeholder="Depart Date" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="option-bottom-tab search-padding row">
                        <div class="pull-right col-lg-5 remove-padding">
                            <button id="form-submit-button" type="submit"><span class="glyphicon glyphicon-search"></span> SEARCH</button>
                        </div>
                    </div>
                    <input type="text" name="flight_type" value="local" style="display:none;" readonly />
                </form>
            </div>
        </div>        
    </div>
    <div class="slideLeftBtn"></div>
    <div class="slideRightBtn"></div>
    <div class="specialFlightArea">
        <a class="homeBGLink" href="#"><div class="col-xs-24 col-sm-7 col-sm-offset-17 specialFlightMessage"></div></a>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('js/vendor/vegas/jquery.vegas.js'); ?>"></script>
<script type="text/javascript">

$(document).ready(function(){


//global variables
    var outStationSourceClick = 0;
    var outStationDestinationClick = 0;
    var localSourceClick = 0;
    var click_outstation = 0;

    var backgrounds_images = <?php echo json_encode($data);?>;

    $.vegas('slideshow', {
           backgrounds:[
               {     
                src:'<?php echo base_url("img/activities/'+backgrounds_images[0].image+'"); ?>', fade:1000,
                   load:function() {
                        $(".homeBGLink").attr('href', backgrounds_images[0].image_url);
                        $(".specialFlightMessage").html(backgrounds_images[0].image_text);
                   }
               },
               { src:'<?php echo base_url("img/activities/'+backgrounds_images[1].image+'"); ?>', fade:1000,
                   load:function() {
                    $(".homeBGLink").attr('href', backgrounds_images[1].image_url);
                       $(".specialFlightMessage").html(backgrounds_images[1].image_text);
                   }
               },
               { src:'<?php echo base_url("img/activities/'+backgrounds_images[2].image+'"); ?>', fade:1000,
                   load:function() {
                    $(".homeBGLink").attr('href', backgrounds_images[2].image_url);
                       $(".specialFlightMessage").html(backgrounds_images[2].image_text);
                   }
               }
           ]
    });
    
    $('.homeBGLink').on('click',function(e){
        e.preventDefault();
        var targ = $('.flights-nav li.active a').attr('href');
        var toField = $(targ).find('#outstat_cab_dest');
        toField.val($(this).attr('href'));
    });

    // var page_url = document.URL;
    // var n = page_url.lastIndexOf("/");
    // var len = page_url.lastIndexOf("s");
    // var active_page = page_url.slice(n+1, len+1);
    // $("li#"+active_page).addClass('activated').siblings().removeClass('activated');

    //select or die

    $('#local_cab_src').selectOrDie({});

    $('#outstat_cab_src').selectOrDie({
        placeholderOption: 'true',
        customClass: 'outstat_cab_src'
    });

    $('#local_cab_dest').selectOrDie({});

    $('#outstat_cab_dest').selectOrDie({
        placeholderOption: 'true',
        customClass: 'outstat_cab_dest'
    });

    $('#outstat_travel_time').selectOrDie({
        size: 6
    });

// datepicker

    $.datepicker.setDefaults({
            dateFormat: "yy-mm-dd"
    });

    $('#date-1').datepicker({
        minDate: 0,
        maxDate: '+1Y',
        onSelect: function (dateText, inst) {
            var sm = $('#form-submit-1').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
        }
    });

    $('#date-2').datepicker({
        minDate: 0,
        maxDate: '+1Y',
        onSelect: function (dateText, inst) {
            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
        }
    });

    //popover begin
    $("#popover-toggle-1").popover({
        html: true,
        container: 'body',
        content: function() {
            return $('#popoverHiddenContent-1').html();
        },
        title: function() {
            return $('#popoverHiddenTitle-1').html();
        },
    });

    $("#popover-toggle-2").popover({
        html: true,
        container: 'body',
        content: function() {
            return $('#popoverHiddenContent-2').html();
        },
        title: function() {
            return $('#popoverHiddenTitle-2').html();
        },
    });

    $("#popover-toggle-3").popover({
        html: true,
        container: 'body',
        content: function() {
            return $('#popoverHiddenContent-3').html();
        },
        title: function() {
            return $('#popoverHiddenTitle-3').html();
        },
    });

    $("#popover-toggle-4").popover({
        html: true,
        container: 'body',
        content: function() {
            return $('#popoverHiddenContent-4').html();
        },
        title: function() {
            return $('#popoverHiddenTitle-4').html();
        },
    });

    $('.btn-ed').on('click', function(){
        var fl_id = $(this).attr('id');
        var ed_arr = fl_id.split('-');
        var ed_num = ed_arr[1];
        var loc = "<?php echo site_url('api/flights/edit_fl?ind=')?>"+ed_num;
        window.location.href = loc;
    });
    //popover end




            
// validation part

    $('#form-submit-1').bootstrapValidator({
        live: 'disabled',
        fields: {
            from: {
                validators: {
                    notEmpty: {
                        message: 'From is required'
                    }
                }
            },
            to: {
                validators: {
                    notEmpty: {
                        message: 'To is required'
                    }
                }
            },
            to_date: {
                validators: {
                    notEmpty: {
                        message: 'Date is required'
                    },
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'Please check Date Format.'
                    }
                }
            }
        }
    });

    $('#form-submit-2').bootstrapValidator({
        fields: {
            from: {
                validators: {
                    notEmpty: {
                        message: 'From is required'
                    }
                }
            },
            to: {
                validators: {
                    notEmpty: {
                        message: 'To is required'
                    }
                }
            },
            out_duration: {
                validators: {
                    notEmpty: {
                        message: 'Travel Duration is required'
                    }
                }
            },
            to_date: {
                validators: {
                    notEmpty: {
                        message: 'To Date is required'
                    },
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'Please check Date Format.'
                    }
                }
            }
        }
    });
    
    var mid = ($(window).height())/2;

    $('.flights-wrapper').css({
        'margin-top': (mid-260)+'px'
    });

    var panel_width = $('#accordion1').width();
    $('.panel-collapse').css('width', panel_width+'px');

    $(window).on('resize', function(){
        panel_width = $('#accordion1').width();
        $('.panel-collapse').css('width', panel_width+'px');
    });

    $.ajax({
        type: "POST",
        url: "<?php echo site_url('cab_api/cabs/source_cities');?>",
        data : {type:2}
    })
    .done(function(retDat){
        retDat = $.parseJSON(retDat);
        var sel_opt = $('#outstat_cab_src');
        sel_opt.html('<option value="loading">Select Source</option>');
        $.each(retDat.statename, function(i, val){
            sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
        });
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('cab_api/cabs/available_days')?>",
        })
        .done(function(ret){
            ret = $.parseJSON(ret);
            var sel_dur = $('#outstat_travel_time');
            sel_dur.html('');
            $.each(ret.duration, function(i, val){
                sel_dur.append("<option id='"+ret.day_name[i]+"' value='"+ret.duration[i]+"'>"+ret.day_name[i]+"</option>");
            });
            $('#outstat_travel_time').selectOrDie('destroy');
            $('#outstat_travel_time').selectOrDie({
                size: 4
            });
        });

        sel_opt.selectOrDie('destroy');
        sel_opt.selectOrDie({
            placeholderOption: 'true',
            onChange: function(){
                $('select#outstat_cab_src').parent('.sod_select').css('border-color', '#27ae60');
                $('#outstat_cab_dest option').html('Loading...');
                var outstat_dest_sel = $(this).children('option:selected').attr('id');
                if( outStationSourceClick === 0 ){
                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('cab_api/cabs/destination_cities');?>",
                        data: {source_id : outstat_dest_sel, type : 2}
                    })
                    .done(function(retDat){
                        retDat = $.parseJSON(retDat);
                        var sel_opt = $('#outstat_cab_dest');
                        sel_opt.html('<option value="loading">Select Destination</option>');
                        $.each(retDat.statename, function(i, val){
                            sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
                        });
                        $('#outstat_cab_dest').selectOrDie('destroy');
                        $('#outstat_cab_dest').selectOrDie({
                            placeholderOption: 'true',
                            size: 6,
                            onChange: function(){
                                $('select#outstat_cab_dest').parent('.sod_select').css('border-color', '#27ae60');
                                $('.outstation_destination small.help-block').hide();
                                outStationDestinationClick++
                            }
                        });
                    });
                    outStationSourceClick++;
                }else{
                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('cab_api/cabs/destination_cities');?>",
                        data: {source_id : outstat_dest_sel, type : 2}
                    })
                    .done(function(retDat){
                        retDat = $.parseJSON(retDat);
                        var sel_opt = $('#outstat_cab_dest');
                        sel_opt.html('<option value="-1" id="sel_def_dest_outStat">Select Destination</option>');
                        $.each(retDat.statename, function(i, val){
                            sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
                        });
                        sel_opt.selectOrDie('destroy');
                        sel_opt.selectOrDie({
                            placeholderOption: 'true',
                            size: 6,
                            onChange: function(){
                                $('.outstat_cab_src').css('border-color', '#27ae60');
                                $('.outstat_cab_dest').css('border-color', '#27ae60');
                                $('.outstation_destination small.help-block').hide();
                                outStationDestinationClick++
                            }
                        });
                    });
                }
            }
        });
    }); 

// mutliway tabclick check
    $('a[data-toggle="tab"]').on('shown.bs.tab', function ( e ) {
        //change margin top for tabs
        if( e.target.text === "Local" ){
            if( click_outstation === 0 ){
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('cab_api/cabs/source_cities');?>",
                    data : {type:1}
                })
                .done(function(retDat){
                    retDat = $.parseJSON(retDat);
                    var sel_opt = $('#local_cab_src');
                    var sel_opt1 = $('#local_cab_dest');
                    sel_opt.html("");
                    $.each(retDat.statename, function(i, val){
                        sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
                    });
                    sel_opt.selectOrDie('destroy');
                    sel_opt.selectOrDie({});
                    sel_opt1.selectOrDie('destroy');
                    sel_opt1.selectOrDie({
                        size:4
                    });
                });
            }else{
                return false;
            }
            click_outstation++;
        }
        if( e.target.text === "OutStation" ){
            return true;
        }
    });
// mutliway tabclick check end



//destination ajax call verification
    $('.form-submit-button-outstation').on('click', function(){
        if( outStationSourceClick > 0 && outStationDestinationClick > 0 ){
            return true;
        }else{
            if( outStationSourceClick == 0 ){
                $('.outstation_destination small.help-block').show();
                $('select#outstat_cab_src').parent('.sod_select').css('border-color', '#f00');
            }
            if( outStationDestinationClick == 0 ){
                $('.outstation_destination small.help-block').show();
                $('select#outstat_cab_dest').parent('.sod_select').css('border-color', '#f00');
            }
            return false;
        }
    });
//destination ajax call verification end

//passenger calculation

    var inputArray = { 
        adult1: 1,
        adult2: 1,
        adult3: 1,
        youth1: 0,
        youth2: 0,
        youth3: 0,
        total1: 1,
        total2: 1,
        total3: 1
    };

    $(".plusBtn").click(function(e){
        e.preventDefault();
        var buttonID = $(this).attr("id");
        var buttonArray = buttonID.split("-");
        var target = buttonArray[0];
        var index = buttonArray[2];
        var i = buttonArray[0]+buttonArray[2];
        var j = "total"+buttonArray[2];
        $("#"+target+"-text-"+index).val(++inputArray[i]);
        $("#total"+"-"+buttonArray[2]).val(++inputArray[j]);
        $(".passenger-target-"+buttonArray[2]+' .panel-heading-custom').html(""+inputArray[j]+" PASSENGERS <span class='caret'></span>");
    });

    $(".minusBtn").click(function(e){
        e.preventDefault();
        var buttonID = $(this).attr("id");
        var buttonArray = buttonID.split("-");
        var target = buttonArray[0];
        var index = buttonArray[2];
        var i = buttonArray[0]+buttonArray[2];
        var j = "total"+buttonArray[2];
        if( buttonArray[0] === 'adult' ){
            if( $(this).siblings('input').val() === "1" ){
                return false;
            }
        }
        if( $(this).siblings('input').val() === "0" ){
            return false;
        }
        $("#"+target+"-text-"+index).val(--inputArray[i]);
        $("#total"+"-"+buttonArray[2]).val(--inputArray[j]);
        $(".passenger-target-"+buttonArray[2]+' .panel-heading-custom').html(""+inputArray[j]+" PASSENGERS <span class='caret'></span>");
    });
});
</script>