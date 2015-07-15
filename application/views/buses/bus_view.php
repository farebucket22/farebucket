<?php
	if(isset($_SESSION['user_details'])){
		$user_details = $_SESSION['user_details'];
	}
	else{
		$user_details = "";
	}
	if(isset($_SESSION['currentUrl'])){
		$currentUrl = $_SESSION['currentUrl'];
	}
	if(isset($_SESSION['calling_controller_name'])){
		$calling_controller_name = $_SESSION['calling_controller_name'];
	}
	unset($_SESSION);
	$_SESSION['user_details'] = $user_details;
	$_SESSION['currentUrl'] = $currentUrl;
	$_SESSION['calling_controller_name'] = $calling_controller_name;
?>
<head>
<title>Farebucket: Cheap Cabs In Chennai | Cheap Domestic Air Tickets | Book Cheap Air Tickets India </title>

<meta name="Subject" content="Cheap Cabs In Chennai" />

<meta name="description" content="Farebucket gives you the ultimate travel experience as we have professionals who have the inside knowledge of the destinations, Cheap Cabs In Chennai , Cheap Domestic Air Tickets , Book Cheap Air Tickets India" />

<meta name="keywords" content="Cheap Cabs In Chennai, Cheap Domestic Air Tickets, Book Cheap Air Tickets India, Cheap Flight Tickets Booking, Cheap Air Tickets India, Lowest Airfare In India, Cheap Cab Services In Chennai, Cheap Cab Rates In Chennai, Cheap Call Taxi In Chennai, Cheap Call Taxi Tariff Chennai, Cheap Hotel Packages, Book Cheap Hotels In Chennai,Cheap Cabs In Bangalore" />

<meta name="Language" content="English" />

<meta name="Distribution" content="Global" />

<meta name="Robots" content="All" />

<meta name="Revisit-After" content="7 Days" />
</head>
<style>
    .specialFlightArea{
        color:#FFF;
    }
    .specialFlightMessage{
        cursor:default;
    }
</style>
<div class="wrap">
    <div class="container flights-wrapper clear-top">
    <div class="row"><div class="col-lg-24 center-align-text"><h1 class='marketingMessage'>Search for Buses</h1></div></div>
        <div class="tab-content change-height">
            <form action="<?php echo site_url('bus_api/buses/getAvailableTrips');?>" method="get" id="form-submit-1" enctype="multipart/form-data">
                <div class="form-padding row">
                    <div class="form-group">
                        <div class="col-lg-8 control-label">
                        	<input autocomplete="off" name="source_city_name" onClick="this.select();" type="text" placeholder="Source" id="source_auto" class="form-control" />
                            <small class="select-error" style="display:none;">Please select the Source from the autocomplete menu first.</small>
                            <input type="text" onClick="this.select();" name="source_id" value="" id="sourceid" style="display:none;" readonly />
                        </div>
                    </div>
                    <div class="col-lg-1 center-align-text">                            
                        <div class="swap" title="Swap From and To fields"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-8 control-label">
                            <input autocomplete="off" name="destination_city_name" onClick="this.select();" type="text" placeholder="Destination" id="destination_auto" class="form-control" />
                            <input type="text" onClick="this.select();" name="destination_id" value="" id="destinationid" style="display:none;" readonly />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-7">
                            <div class="inner-addon right-addon">
                                <i class="glyphicon"></i>
                                <input name="journey_date" type="text" readonly placeholder="Travel Date" id="journey_date" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row autocompleteErrorBlock" style="display:none;">
                    <center><small>Please Select the Source/Destination from the Autocomplete dropdown</small></center>
                </div>
                <div class="option-bottom-tab search-padding row">
                    <div class="pull-right col-lg-5 remove-padding">
                        <button id="form-submit-button" type="submit"><span class="glyphicon glyphicon-search"></span> SEARCH</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="slideLeftBtn"></div>
    <div class="slideRightBtn"></div>
    <div class="specialFlightArea">
        <p class="homeBGLink"><div class="col-xs-24 col-sm-7 col-sm-offset-17 specialFlightMessage"></div></p>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('js/vendor/vegas/jquery.vegas.js'); ?>"></script>
<script type="text/javascript">

$(document).ready(function(){
    var edit_chk = 0;
    var opd = 0;
    var opd1 = 0;
    var source_flag = '';
    var destination_flag = '';

    var source_cities = [];var source_id = [];var destination_cities = [];var destination_id = [];var label,selected_source;
    var listSourceCitiesById = [];
    var listDestinationCitiesById = [];
    $.ajax({
      type: "POST",
      url: "<?php echo site_url('bus_api/buses/getAllSources');?>",  
    })
    .done(function (data){ 
        var json = JSON.parse(JSON.parse(data));
        
        for(var i = 0 ; i < json.cities.length ; i++)
        {
            var cityName = json.cities[i].name.toLowerCase();
            var cityId = json.cities[i].id;
            listSourceCitiesById[cityName] = cityId;
            source_cities[i] = cityName.charAt(0).toUpperCase() + cityName.slice(1);
        }
        source_cities = source_cities.sort();
    });
    var availableTags = source_cities;

    $('#source_auto').on('paste cut keyup', function(e){
        if( e.keyCode !== 13 ){
            if( source_flag !== 'done' ){
                source_flag = 'flagged';
            }
        }
    });

    $('#destination_auto').on('paste cut keyup', function(e){
        if( e.keyCode !== 13 ){
            if( destination_flag !== 'done' ){
                destination_flag = 'flagged';
            }            
        }
    });

    var NoResultsLabel = 'No results found.';

    $( "#source_auto" ).autocomplete({
        selectFirst: true,
        autoFocus: true,
        delay: 500,
        source: function(request, response) {
            var term = $.ui.autocomplete.escapeRegex(request.term)
            , startsWithMatcher = new RegExp("^" + term, "i")
            , startsWith = $.grep(availableTags, function(value) {
                return startsWithMatcher.test(value.label || value.value || value);
            })

            if (!startsWith.length) {
                startsWith = [NoResultsLabel];
            }
            response(startsWith);
        },
        open: function( event, ui ) {
            opd = 1;
            source_flag = 'flagged';
        },
        focus: function (event, ui) {
            if (ui.item.label === NoResultsLabel) {
                event.preventDefault();
                return false;
            }
        },
        select: function (event,ui){

            // When the enterkey is pressed, the autocomplete propogation is stopped, 
            // all search boxes are found and the search box which is next to the current search box is focused.
            // The next search box is chosen with the jquery object attribute.
            var originalEvent = event;
            while (originalEvent) {
                if (originalEvent.keyCode == 13){
                    originalEvent.stopPropagation();
                    $( "#destination_auto" ).focus().select();
                }
                if (originalEvent == event.originalEvent)
                    break;
                originalEvent = event.originalEvent;
            }

            if (ui.item.label === NoResultsLabel) {
                event.preventDefault();
                return false;
            }

            if( opd === 1 ){
                opd = 0;
                source_flag = 'done';
            }

            label = ui.item.value.toLowerCase();
            selected_source = listSourceCitiesById[label];
            $('#sourceid').val(selected_source);

            var data = {source_id : selected_source};
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('bus_api/buses/getAllDestinations');?>",
                data : data
            })
            .done(function (data){
                var json = JSON.parse(JSON.parse(data));
                for(var i = 0 ; i < json.cities.length ; i++)
                {
                    var cityName = json.cities[i].name.toLowerCase();
                    var cityId = json.cities[i].id;
                    listDestinationCitiesById[cityName] = cityId;
                    destination_cities[i] = cityName.charAt(0).toUpperCase() + cityName.slice(1);
                }
                destination_cities = destination_cities.sort();
            });
            var availableTags = destination_cities;
            $( "#destination_auto" ).autocomplete({
                selectFirst: true,
                autoFocus: true,
                delay: 500,
                source: function(request, response) {
                    var term = $.ui.autocomplete.escapeRegex(request.term)
                    , startsWithMatcher = new RegExp("^" + term, "i")
                    , startsWith = $.grep(availableTags, function(value) {
                        return startsWithMatcher.test(value.label || value.value || value);
                    })
                    // code below Matches *term* format. the above matches term*. (substrings also match.)
                    // , containsMatcher = new RegExp(term, "i")
                    // , contains = $.grep(availableTags, function (value) {
                    //     return $.inArray(value, startsWith) < 0 &&
                    //         containsMatcher.test(value.label || value.value || value);
                    // });

                    if (!startsWith.length) {
                        startsWith = [NoResultsLabel];
                    }
                    response(startsWith);
                },
                open: function( event, ui ) {
                    opd1 = 1;
                    destination_flag = 'flagged';
                },
                focus: function (event, ui) {
                    if (ui.item.label === NoResultsLabel) {
                        event.preventDefault();
                        return false;
                    }
                },
                select:function(event,ui){

                if (ui.item.label === NoResultsLabel) {
                    event.preventDefault();
                    return false;
                }

                if( opd1 === 1 ){
                    opd1 = 0;
                    destination_flag = 'done';
                }   
                
                label = ui.item.value.toLowerCase();
                selected_destination = listDestinationCitiesById[label];
                $('#destinationid').val(selected_destination); 
                }
            });
        }
    });
    $.datepicker.setDefaults({
            dateFormat: "yy-mm-dd"
    });

    $('#journey_date').datepicker({
        minDate: 0,
        maxDate: '+1Y',
        onSelect: function(){
            var sm = $('#form-submit-1').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
        }
    });
            
        var backgrounds_images = <?php echo json_encode($data);?>;
        $.vegas('slideshow', {
               backgrounds:[
               <?php foreach($data as $dat):?>
                    {     
                        src:'<?php echo base_url("img/activities/".$dat->image); ?>', fade:1000,
                        load:function() {
                            $(".homeBGLink").attr('href', "<?php echo $dat->image_url;?>");
                            $(".specialFlightMessage").html("<?php echo $dat->image_text;?>");
                        }
                    },
                <?php endforeach;?>
               ]
        });

    $(".slideLeftBtn").click(function(){
        $.vegas('previous');
    });
    
    $(".slideRightBtn").click(function(){
        $.vegas('next');
    });
    
    var mid = ($(window).height())/2;

    $('.flights-wrapper').css({
        'margin-top': 7+'%'
    });

    var panel_width = $('#accordion1').width();
    $('.panel-collapse').css('width', panel_width+'px');

    $(window).on('resize', function(){
        panel_width = $('#accordion1').width();
        $('.panel-collapse').css('width', panel_width+'px');
    });

    //select or die

    //prevent random searches.
    $(document).on( 'click', '#form-submit-button', function( e ){
        e.preventDefault();
        var all_ios = [];

        all_ios.push(source_flag);
        all_ios.push(destination_flag);

        $.each( all_ios, function( i, ios ){
            if( ios === "flagged" ){
                edit_chk++;
            }
        });

        if( edit_chk > 0 ){
            edit_chk = 0;
            $('input[name=source_city_name]').animate({'border-color': '#f00'}, 100);
            $('.autocompleteErrorBlock').fadeIn('100');
            setTimeout(function(){
                $('input[name=source_city_name]').animate({'border-color': '#27ae60'}, 100);
                $('.autocompleteErrorBlock').fadeOut('100');
            }, 4000);
            return false;
        }else{
            $(this).closest('form').submit();
        }
    });

    //bootstrap validator
    $('#form-submit-1').bootstrapValidator({
        live: 'disabled',
        fields: {
            source_city_name: {
                validators: {
                    notEmpty: {
                        message: 'Source is required'
                    },
                    different: {
                        field: 'destination_city_name',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            destination_city_name: {
                validators: {
                    notEmpty: {
                        message: 'Destination is required'
                    },
                    different: {
                        field: 'source_city_name',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            journey_date: {
                validators: {
                    notEmpty: {
                        message: 'Date is required'
                    },
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'Please check the Date Format.'
                    }
                }
            }
        }
    });
    
//swap from and to field oneway and return
        $('.swap').on('click', function(){
            $(this).addClass('rotate-easeOutQuad');
        });
        $(".swap").on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', 
            function() {
            $(this).removeClass('rotate-easeOutQuad');
            var temp = $('input[name=source_city_name]').val();
            $('input[name=source_city_name]').val($('input[name=destination_city_name]').val());
            $('input[name=destination_city_name]').val(temp);
        });
//swap from and to field oneway and return end

});
</script>