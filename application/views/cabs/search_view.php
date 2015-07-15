<style>
    #accordion1, #accordion2, .inner-addon{
            margin-top:5px; 
    }
    .sod_select{
        text-transform: none;
    }
</style>
<div class="container cab-mod-search">
    <!-- tabs -->
    <ul class="nav nav-tabs flights-nav cab-nav" role="tablist" id="cabsTab">
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
                        <div class="col-lg-8 control-label outstation_destination">
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
                                <input name="to_date" readonly id="date-2" class="form-control" type="text" placeholder="DEPART DATE" value="<?php if( isset($_GET['to_date']) ){echo $_GET['to_date'];}else{echo '';}?>" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-padding row">
                    <div class="form-group">
                        <div class="pull-right col-lg-8 control-label">
                            <select name="out_duration" id="outstat_travel_time">
                                <option value="loading">Loading...</option>
								<option value="1">1</option>
                            </select>
                            <input name="selected_duration" id="sel_duration" value="" style="display:none;" />
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
        <div class="tab-pane pane-height fade" id="local">
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
                                <option value="5">5Hrs / 50Kms</option>
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
                                <input name="to_date" id="date-1" readonly class="form-control" type="text" placeholder="Depart Date" value="<?php if( isset($_GET['to_date']) ){echo $_GET['to_date'];}else{echo '';}?>">
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
<script type="text/javascript">

$(document).ready(function(){

    <?php if( isset($_GET['flight_type']) && $_GET['flight_type'] == 'outstation' ):?>
        $('#cabsTab a[href="#outStation"]').tab('show');
    <?php else:?>
        $('#cabsTab a[href="#local"]').tab('show');
    <?php endif;?>

//global variables
    var outStationSourceClick = 0;
    var outStationDestinationClick = 0;
    var localSourceClick = 0;
    var click_outstation = 0;

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
        onSelect: function(){
            var return_date = $(this).datepicker('getDate') || new Date();
            $('#date-3').datepicker({
                minDate: new Date(return_date),
                maxDate: '+1Y',
                onSelect: function (dateText, inst) {
                    var sm = $('#form-submit-2').data('bootstrapValidator');
                    sm.updateStatus($(this), 'VALID');
                }
            });
        }
    });
            
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
            out_cab_src: {
                validators: {
                    notEmpty: {
                        message: 'From is required'
                    }
                }
            },
            out_cab_dest: {
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

    <?php if( isset($_GET['flight_type']) && $_GET['flight_type'] == 'outstation' ):?>
        var getDestination = "<?php echo $_GET['out_cab_src'];?>";
        var destionationArr = getDestination.split('-');
        var outstat_dest_sel = destionationArr[0];
    <?php endif;?>

    $.ajax({
        type: "POST",
        url: "<?php echo site_url('cab_api/cabs/source_cities');?>",
        data : {type:2}
    })
    .done(function(retDat){
        retDat = $.parseJSON(retDat);
        var sel_opt = $('#outstat_cab_src');

        <?php if( isset($_GET['out_cab_src']) ):?>
            var get_source_raw = "<?php echo $_GET['out_cab_src'];?>";
            var get_source_arr = get_source_raw.split('-');
            var get_source = get_source_arr[1];
        <?php else:?>
            var get_source = '';
        <?php endif;?>

        sel_opt.html('<option value="loading">Select Source</option>');
        $.each(retDat.statename, function(i, val){
            if( get_source === val ){
                sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
            }else{
                sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('cab_api/cabs/available_days')?>",
        })
        .done(function(ret){
            ret = $.parseJSON(ret);
            var sel_dur = $('#outstat_travel_time');

            <?php if( isset($_GET['out_cab_src']) ):?>
                var get_outdur = "<?php echo $_GET['out_duration'];?>";
            <?php else:?>
                var get_outdur = '';
            <?php endif;?>

            sel_dur.html('');
            $.each(ret.duration, function(i, val){
                if( get_outdur === ret.duration[i] ){
                    sel_dur.append("<option id='"+ret.day_name[i]+"' value='"+ret.duration[i]+"' selected='selected'>"+ret.day_name[i]+"</option>");
                }else{
                    sel_dur.append("<option id='"+ret.day_name[i]+"' value='"+ret.duration[i]+"'>"+ret.day_name[i]+"</option>");
                }
            });

            sel_dur.selectOrDie({
				placeholderOption: 'true',
			});
        });

        sel_opt.selectOrDie('destroy');
        sel_opt.selectOrDie({
            placeholderOption: 'true',
            onChange: function(){
                $('#outstat_cab_dest').html('<option value="loading" disabled>Loading...</option>');
                $('#outstat_cab_dest').selectOrDie('update');
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
                        $('#outstat_cab_dest').selectOrDie('update');

                        <?php if( isset($_GET['out_cab_src']) ):?>
                            var get_destination_raw = "<?php echo $_GET['out_cab_dest'];?>";
                            var get_destination_arr = get_destination_raw.split('-');
                            var get_destination = get_destination_arr[1];
                        <?php else:?>
                            var get_destination = '';
                        <?php endif;?>

                        $.each(retDat.statename, function(i, val){
                            if( get_destination === val ){
                                sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
                            }else{
                                sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
                            }
                        });
                        sel_opt.selectOrDie('destroy');
                        sel_opt.selectOrDie({
                            placeholderOption: 'true',
                            size: 6,
                            onChange: function(){
                                $('.outstation_destination small.help-block').hide();
                                outStationDestinationClick++;
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
                        sel_opt.html('<option value="loading">Select Destination</option>');
                        $('#outstat_cab_dest').selectOrDie('update');

                        <?php if( isset($_GET['out_cab_src']) ):?>
                            var get_destination_raw = "<?php echo $_GET['out_cab_dest'];?>";
                            var get_destination_arr = get_destination_raw.split('-');
                            var get_destination = get_destination_arr[1];
                        <?php else:?>
                            var get_destination = '';
                        <?php endif;?>

                        $.each(retDat.statename, function(i, val){
                            if( get_destination === val ){
                                sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
                            }else{
                                sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
                            }
                        });

                        sel_opt.selectOrDie('destroy');
                        sel_opt.selectOrDie({
                            placeholderOption: 'true',
                            size: 6,
                            onChange: function(){
                                $('.outstation_destination small.help-block').hide();
                                outStationDestinationClick++;
                            }
                        });
                    });
                    outStationSourceClick++;
                }
            }
        });
    });    

<?php if( isset($_GET['flight_type']) && $_GET['flight_type'] == 'outstation' ):?>
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('cab_api/cabs/destination_cities');?>",
        data: {source_id : outstat_dest_sel, type : 2}
    })
    .done(function(retDat){
        retDat = $.parseJSON(retDat);
        var sel_opt = $('#outstat_cab_dest');
        sel_opt.html('<option value="loading">Select Destination</option>');

        <?php if( isset($_GET['out_cab_src']) ):?>
            var get_destination_raw = "<?php echo $_GET['out_cab_dest'];?>";
            var get_destination_arr = get_destination_raw.split('-');
            var get_destination = get_destination_arr[1];
        <?php else:?>
            var get_destination = '';
        <?php endif;?>

        $.each(retDat.statename, function(i, val){
            if( get_destination.trim() === val.trim() ){
                sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
            }else{
                sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
            }
        });
        sel_opt.selectOrDie('destroy');
        sel_opt.selectOrDie({
            placeholderOption: 'true',
            size: 6,
            onChange: function(){
                $('.outstation_destination small.help-block').hide();
            }
        });
    });
<?php endif;?>

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

                    <?php if( isset($_GET['local_cab_src']) ):?>
                        var get_source_raw = "<?php echo $_GET['local_cab_src'];?>";
                        var get_source_arr = get_source_raw.split('-');
                        var get_source = get_source_arr[1];
                    <?php else:?>
                        var get_source = '';
                    <?php endif;?>

                    sel_opt.html("");
                    $.each(retDat.statename, function(i, val){
                        if( get_source === val ){
                            sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
                        }else{
                            sel_opt.append("<option id='"+retDat.stateid[i]+"' value='"+retDat.stateid[i]+"-"+retDat.statename[i]+"'>"+val+"</option>");
                        }
                    });
                    sel_opt.selectOrDie('destroy');
                    sel_opt.selectOrDie({});

                    <?php if( isset($_GET['local_cab_dest']) ):?>
                        var get_dest_id = "<?php echo $_GET['local_cab_dest'];?>";
                    <?php else:?>
                        var get_dest_id = '';
                    <?php endif;?>
                   
                    $('#local_cab_dest').children().each(function(i, val){
                        if( get_dest_id === val.value ){
                            val.selected = 'selected';
                        }
                    });

                    $('#local_cab_dest').selectOrDie('destroy');
                    $('#local_cab_dest').selectOrDie({
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
        var sel = $('#outstat_travel_time').find('option:selected').attr('id');
        $('#sel_duration').val(sel);
        if( outStationSourceClick > 0 && outStationDestinationClick > 0){
            if( outStationDestinationClick <  outStationSourceClick ){
                $('.outstation_destination small.help-block').show();
                $('select#outstat_cab_dest').parent('.sod_select').css('border-color', '#f00');
                return false;
            }
            else{
                return true;
            }
        }else{
            $('.outstation_destination small.help-block').show();
            $('.outstat_cab_src').css('border-color', '#f00');
            $('.outstat_cab_dest').css('border-color', '#f00');
            setTimeout(function(){
                $('.outstat_cab_src').css('border-color', '#27ae60');
                $('.outstat_cab_dest').css('border-color', '#27ae60');
                $('.outstation_destination small.help-block').hide();
            }, 5000);
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