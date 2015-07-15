<style>
    .modSearch{
        padding-right: 25px;
        font-size: 12px;
    }
</style>
<form action="<?php echo site_url('api/flights/traveller_details');?>" id="booking_details_form" method="post" style="display:none;">
    <input class="form-control" id="airline_name_field" name="airline_name_field" type="hidden" />
    <input class="form-control" id="from_field" name="from_field" type="hidden" />
    <input class="form-control" id="to_field" name="to_field" type="hidden" />
    <input class="form-control" id="from_field_str" name="from_field_str" value="<?php echo $_GET['from']?>" type="text" />
    <input class="form-control" id="to_field_str" name="to_field_str" value="<?php echo $_GET['to']?>" type="text" />
    <input class="form-control" name="origin" value="" type="hidden" />
    <input class="form-control" name="destination" value="" type="hidden" />
    <input class="form-control" id="flight_duration_field" name="flight_duration_field" type="hidden" />
    <input class="form-control" name="total_fare_field" type="hidden" />
    <input class="form-control" name="adult_count_field" type="hidden" value="<?php echo $_GET['adult_count'];?>"/>
    <input class="form-control" name="youth_count_field" type="hidden" value="<?php echo $_GET['youth_count'];?>"/>
    <input class="form-control" name="kids_count_field" type="hidden" value="<?php echo $_GET['kids_count'];?>"/>
    <input class="form-control" name="total_count_field" type="hidden" value="<?php echo $total_count;?>"/>
    <input class="form-control" name="travel_date" type="hidden" value="<?php echo $_GET['oneway_date'];?>"/>
    <input class="form-control" type="hidden" name="booking_details" />
    <input class="form-control" type="hidden" name="fare" />
    <input class="form-control" type="hidden" name="fare_breakdown" />
    <input class="form-control" type="hidden" name="fare_rule" />
    <input class="form-control" type="hidden" name="flight_type" />
    <input class="form-control" type="hidden" name="non_refundable" />
    <input class="form-control" type="hidden" name="segment_key" />
    <input class="form-control" type="hidden" name="Source" />
    <input class="form-control" type="hidden" name="TripIndicator" />
    <input class="form-control" type="hidden" name="IbSegCount" />
    <input class="form-control" type="hidden" name="ObSegCount" />
    <input class="form-control" type="hidden" name="PromotionalPlanType" />
    <input class="form-control" type="hidden" name="layover" />
</form>
<div class="wrap">
    <div class="container-fluid clear-top">
        <div class="row resultsSearchContainer navbar-fixed-top">
            <div class="panel-group" id="accordionSearch">
                <div class="panel panel-default">
                    <div class="grey-bottom-separator center-align-text">
                        <span class="glyphicon glyphicon-user hulk-class"></span>
                        <?php if($_GET['adult_count'] == 1):?>
                            <span><?php echo $_GET['adult_count'];?> Adult, </span>
                        <?php else:?>
                            <span><?php echo $_GET['adult_count'];?> Adults, </span>
                        <?php endif;?>
                        <?php if($_GET['youth_count'] == 1):?>
                            <span><?php echo $_GET['youth_count'];?> Child, </span>
                        <?php else:?>
                            <span><?php echo $_GET['youth_count'];?> Children, </span>
                        <?php endif;?>
                        <?php if($_GET['kids_count'] == 1):?>
                            <span><?php echo $_GET['kids_count'];?> Infant</span>
                        <?php else:?>
                            <span><?php echo $_GET['kids_count'];?> Infants</span>
                        <?php endif;?>
                        <!-- <span class="pull-right center-align-text">
                            <h4 class="panel-title">
                                <a class="accordion-toggle modSearch" data-toggle="collapse" data-target="#collapseSearch">
                                    <span class="glyphicon glyphicon-search"></span>
                                    <span>Modify Search</span>
                                </a>
                            </h4>
                        </span> -->
                    </div>
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle modSearch" data-toggle="collapse" data-target="#collapseSearch">
                                <div class="pull-right searchIcon">
                                    <div class="row center-align-text">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </div>
                                    <div class="row">
                                        <span>Modify <br/>Search</span>
                                    </div>
                                </div>
                            </a>
                            <div class="accord-content container-fluid">
                                <div class="row fl_overwiew hulk-class">
                                    <div class="col-lg-5 fl_septr1">
                                        <div class="col-lg-2 fl_no">1</div>
                                        <div class="col-lg-3 fl_bg_nav sr_only"></div>
                                        <div class="col-lg-14 fl_info">
                                            <div class="row">
                                                <div class="col-lg-offset-4 col-lg-18 center-align-text travel-text">
                                                    <div class="row center-align-text">
                                                        <div class="col-lg-11 remove-padding" id="origin"></div>
                                                        <span class='col-lg-2 gly-ctm glyphicon glyphicon-play'></span>
                                                        <div class="col-lg-11 remove-padding" id="destination"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"><?php echo date('D, jS M Y', strtotime($_GET['oneway_date']));?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-23 col-lg-offset-1 center-align-text fare-padding TotFare1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div id="collapseSearch" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php include(APPPATH . "views/flights/search_view.php");?>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid main clear-top">  
        <div class="vam" style="display:none;">
            <div class="row">
                <div class="col-xs-24 center-align-text">
                    <h2>Sorry, No matching records found for your search</h2>
                    <span class="h4 mod_search_error" data-toggle="collapse" data-target="#collapseSearch" >Modify Search</span> <span class="h4">|</span> <span class="h4 reset_search_error" onclick="javascript:location.href = '<?php echo site_url('flights');?>'">Reset Search</span></center>
                </div>
            </div>
        </div>
        <div class="row resultsFilters">
            <div class="col-xs-24 col-sm-4 col-sm-offset-cust-2 filterWeatherContainer">
                <div class="row">
                    <div class="col-xs-24 filtersContainer filtersLabel">
                        <div class="row">
                            <div class="col-xs-24 filterByArea filter"><h4>Filter By:</h4></div>
                        </div>
                        <div class="row">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#amountRangeAccordion">
                                                Price
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="amountRangeAccordion" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body">
                                            <div class="simple">
                                                <div class="slider-unit align-c">
                                                    <div id="slider-range" class="slider-control"></div>
                                                    <div>
                                                        <i class="fa fa-inr"></i>&nbsp;<span id="range-bottom" class="range-value"></span> to <i class="fa fa-inr"></i>&nbsp;<span id="range-top" class="range-value"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseFive">
                                                Departure Time
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body">
                                            <div class="simple">
                                                <div class="range-slide" id="slider-range-duration"></div>
                                                <span id="range-bottom-duration" class="range-value"></span> to <span id="range-top-duration" class="range-value"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#airlineAccordion">
                                                Airlines
                                            </a>
                                            <a href="#" class="airlineShowAllLink" style="display:none;">Uncheck All</a>
                                        </h4>
                                    </div>
                                    <div id="airlineAccordion" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body" id = "airlines-check"></div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseFour">
                                                Stops
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body" id = "stops-check"></div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseSix">
                                                Ticket Type
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSix" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body" id = "ticket-check"></div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#keywordAccordion">
                                                Keyword
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="keywordAccordion" class="panel-collapse filterCollapse collapse in">
                                        <div class="panel-body">
                                            <input type="text" class="keywordSearch" id="keywordSearchFullWidth" placeholder="Enter Keyword" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                
                    </div>
                </div>
                <div class="row">
                    <div class="searchResultsWeatherDetails">
                        <div class="weatherDetails row center-align-text">
                            <div class="col-xs-24 weather_spinner">
                                <div id="spinner" class="spinner">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                    <div class="rect4"></div>
                                    <div class="rect5"></div>
                                </div>
                            </div>
                            <div class="col-xs-12 to">
                                <div class="row">
                                    <div class="col-xs-24 date"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 icon"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 summary h4"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 temperature"></div>
                                </div>
                            </div>
                            <div class="col-xs-12 from">
                                <div class="row">
                                    <div class="col-xs-24 date"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 icon"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 summary h4"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-24 temperature"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-24 col-sm-17 col-sm-offset-1 resultsArea">
                <div class="row">
                    <div  class="col-xs-9">
                        <div class="busOptionContainer row">
                            <div class="col-xs-4 busOptionLabel"></div>
                            <div class="col-xs-18 noBusesLabel center-align-text" style="display:none;">No Buses available</div>
                            <div class="col-xs-18" id="bus_spin">
                                <div id="bus_spinner" class="spinner">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                    <div class="rect4"></div>
                                    <div class="rect5"></div>
                                </div>
                            </div>
                            <div class="col-xs-16 busOptionDetails">
                                <div class="row busOptionDetailsLine1">Buses to Chennai from:</div>
                                <div class="row busOptionDetailsLine2">&#x20B9; 3999.00</div>
                            </div>
                            <a href="#" class="col-xs-4 busOptionView" style="display:none;">VIEW</a>
                        </div>
                    </div>
                    <div class="col-xs-9 col-xs-offset-1">
                        <div class="cabOptionContainer row">
                            <div class="col-xs-4 cabOptionLabel"></div>
                            <div class="col-xs-18 noCabsLabel">No Cabs available</div>
                            <div class="col-xs-18" id="cab_spin">
                                <div id="cab_spinner" class="spinner">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                    <div class="rect4"></div>
                                    <div class="rect5"></div>
                                </div>
                            </div>
                            <div class="col-xs-16 cabOptionDetails">
                                <div class="row cabOptionDetailsLine1">Cabs to Chennai from:</div>
                                <div class="row cabOptionDetailsLine2">&#x20B9; 3999.00</div>
                            </div>
                            <a href="#" class="col-xs-4 cabOptionView">VIEW</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-24 col-sm-24 results">
                        <div id="flight_spin" class="spinner">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                            <div class="rect4"></div>
                            <div class="rect5"></div>
                        </div>
                        <div class="row resultsRow">
                            <table class="table table-hover-custom" id="search" style="display:none;">
                                <thead>
                                    <tr>
                                        <th class="center-align-text"><h4>Airline_hidden</h4></th>
                                        <th class="center-align-text"><h4>Airline</h4></th>
                                        <th class="center-align-text"><h4>from_hidden</h4></th>
                                        <th class="center-align-text"><h4>Departure</h4></th>
                                        <th class="center-align-text"><h4>Arrival</h4></th>
                                        <th class="center-align-text"><h4>Duration</h4></th>
                                        <th class="center-align-text">Price_hidden</th>
                                        <th class="center-align-text price_disp"><h4>Price</h4></th>
                                        <th class="center-align-text">Stop</th>
                                        <th class="center-align-text">ticket_type_hidden</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="center-align-text"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?php echo base_url('js/table.details.js'); ?>"></script>
<script>
/*==========  Global Variables  ==========*/
var flightSegment;
var fare;
var fareBreakdown;
var fareRule;
var generalInfo;
var layover;
var segmentInfo;
var MaxFare = 0;
var MinFare = 0;
var MaxRound = 0;
var MinRound = 0;
var MaxDepTime = 0;
var MinDepTime = 0;

var selectedAirlines = [];
var selectedStops = [];
var selectedTicket = [];

var rangeSlider = $( "#slider-range" );
var rangeSliderDuration = $( "#slider-range-duration" );

/*==========  prototypes  ==========*/
Number.prototype.convertToTime = function(){
    var hours = Math.floor( this / 60 );
    var minutes = this - (hours * 60);
    if(hours < 10) hours = '0' + hours;
    if(minutes < 10) minutes = '0' + minutes;
    if(minutes == 0) minutes = '00';
    return hours+':'+minutes;
}


/*==========  range slider global initialization  ==========*/
var initRangeSlider =  function(){
    rangeSlider.slider({
        range: true,
        min: MinRound,
        max: MaxRound,
        step: 100,
        values: [ MinRound, MaxRound ],
        slide: function( event, ui ) {
            var priceTextBtm = parseFloat(ui.values[ 0 ]).toFixed(2);
            var priceTextTop = parseFloat(ui.values[ 1 ]).toFixed(2);
            $('#range-bottom').html(priceTextBtm);
            $('#range-top').html(priceTextTop);
            $('#range-bottom').autoNumeric('set', priceTextBtm);
            $('#range-top').autoNumeric('set', priceTextTop);
        },
        stop: function( event, ui ) {
            flashTable();
            oTable.draw();
        }
    });
    var rangeBottom = rangeSlider.slider("values", 0);
    var rangeTop = rangeSlider.slider("values", 1);
    rangeBottom = parseFloat(rangeBottom).toFixed(2);
    rangeTop = parseFloat(rangeTop).toFixed(2);
    $('#range-bottom').html(rangeBottom);
    $('#range-top').html(rangeTop);
}

var initRangeSliderDuration =  function(){
    rangeSliderDuration.slider({
        range: true,
        min: MinDepTime,
        max: MaxDepTime,
        step: 10,
        values: [ MinDepTime , MaxDepTime ],
        slide: function( event, ui ) {
            //to insert a colon inbetween the text
            var rangeBottomDurationStr = ui.values[ 0 ].convertToTime();
            var rangeTopDurationStr = ui.values[ 1 ].convertToTime();

            $('#range-bottom-duration').html(rangeBottomDurationStr);
            $('#range-top-duration').html(rangeTopDurationStr);
        },
        stop: function(event, ui) {
            flashTable();
            oTable.draw();
        }
    });
    var rangeBottomDuration = rangeSliderDuration.slider("values", 0);
    var rangeTopDuration = rangeSliderDuration.slider("values", 1);
    $('#range-bottom-duration').html(MinDepTime.convertToTime());
    $('#range-top-duration').html(MaxDepTime.convertToTime());
}


/*==========  Initialize DataTables. oTable is the object reference used.  ==========*/
var oTable = $('#search').DataTable({
    "bDeferRender": false,
    "aoColumnDefs": [
        { "aDataSort": [ 6, 7 ], "aTargets": [ 7 ] },
        { "targets": [ 0, 2, 6, 8, 9 ], "visible": false },
        { "targets": 10, "orderable": false }
    ],
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
        $(nRow).attr('data-rowid', iDataIndex);
    },
    "language": {
        "zeroRecords": '<div> <div class="row"> <div class="col-xs-24 center-align-text"> <h2>Sorry, No matching records found for your search</h2> <span class="h4 mod_search_error" data-toggle="collapse" data-target="#collapseSearch" >Modify Search</span> <span>|</span> <span onclick="javascript: $('+ "'.airlineShowAllLink'" +').trigger('+"'resetAllFilters'"+');" class="h4 reset_search_error">Reset Filters</span> </center> </div></div></div>'
    },
    "bDestroy": true,
    "aaSorting": [[6, 'asc']],
    "bPaginate": false,
    "bInfo": false,
    "bFilter": true,
    "bScrollCollapse": true,
    "fnInitComplete": function() {
        this.fnAdjustColumnSizing(true);
    }
});

(function(){
/*====================================================================
= OneWay Flight search With an Ajax call, Params = search_parameters =
======================================================================*/
    var from_raw = "<?php echo $_GET['from'];?>";
    var to_raw = "<?php echo $_GET['to'];?>";
    var p1 = from_raw.lastIndexOf('(');
    var q1 = from_raw.lastIndexOf(')');
    var p2 = to_raw.lastIndexOf('(');
    var q2 = to_raw.lastIndexOf(')');
    var from_arr = from_raw.slice( p1+1 , q1 );
    var to_arr = to_raw.slice( p2+1 , q2 );

    $('#origin').html(from_arr);
    $('#destination').html(to_arr);

    var travel_class = "<?php if( isset($_GET['class_of_travel_1']) ){ echo $_GET['class_of_travel_1']; }else{ echo ""; }?>";

    var search_parameters = {
        from: from_arr, 
        to: to_arr, 
        adult_count: "<?php echo $_GET['adult_count'];?>", 
        flight_type: "<?php echo $_GET['flight_type'];?>", 
        youth_count: "<?php echo $_GET['youth_count'];?>", 
        kids_count: "<?php echo $_GET['kids_count'];?>", 
        total_count: "<?php echo $_GET['adult_count'] + $_GET['youth_count'] + $_GET['kids_count'];?>",
        oneway_date: "<?php echo $_GET['oneway_date'];?>",
        airline_preference: "<?php echo $_GET['airline_preference_1'];?>",
        travel_class: travel_class,
    };
    /*==================================================================
        Alternate Buttons function
    ====================================================================*/
    var source_split = "<?php echo $_GET['from'];?>";  
    var src = source_split.split(",");
        if(src[0] === "Delhi")
        {
            src[0] = "Delhi /NCR";
        }
    var destination_split = "<?php echo $_GET['to'];?>";
    var dest = destination_split.split(",");
    var cab_search_parameters = 
    {
        source : src[0],
        destination : dest[0],
        is_ajax : 1,
        adult_count: "<?php echo $_GET['adult_count'];?>", 
        youth_count: "<?php echo $_GET['youth_count'];?>",
        journey_date: "<?php echo $_GET['oneway_date'];?>",
    }
    $('#cab_spinner').show();
    $.ajax({
        url: "<?php echo site_url('cab_api/cabs/flights_to_cabs');?>",
        type: "POST",
        data: {data : cab_search_parameters},
    })
    .done(function (cabData){
        var result = $.parseJSON(cabData);
        $('#cab_spin').hide();
        if(result !== false)
        {
            var search_base = "<?php echo site_url('cab_api/cabs/flights_to_cabs?source='.$_GET['from'].'&destination='.$_GET['to'].'&adult_count='.$_GET['adult_count'].'&youth_count='.$_GET['youth_count'].'&journey_date='.$_GET['oneway_date'].'&oneway_change_cab=true&total_count='+'total_count');?>";
            $('.cabOptionView').attr('href', search_base);
            $('.cabOptionDetails').show();
            $('.cabOptionDetailsLine1').html('Cabs to '+ dest[0]+' From:');
            $('.cabOptionDetailsLine2').html('&#x20B9; ' +result);
            $('.cabOptionView').show();
        }
        else
        {
            $('.busOptionView').attr('href', "#");
            $('.cabOptionDetails').hide();
            $('.cabOptionView').hide();
            $('.noCabsLabel').show();
        }
    });

    $('#bus_spinner').show();
    $('.busOptionDetails').hide();
    $('.busOptionView').hide();
    $('.noBusesLabel').hide();
    $.ajax({
        url: "<?php echo site_url('bus_api/buses/flights_to_buses');?>",
        type: "POST",
        data: {data : cab_search_parameters}
    })
    .done(function (busData){
        var result = $.parseJSON(busData);
        var search_base = "<?php echo site_url();?>";

        $('#bus_spin').hide();
        if( result.err === false ){
            var search_str = '/bus_api/buses/getAvailableTrips?source_city_name='+result.source+'&source_id='+result.source_id+'&destination_city_name='+result.destination+'&destination_id='+result.destination_id+'&journey_date='+result.date+'&flight_type=local';
            search_base += search_str;
            $('.busOptionView').attr('href', search_base);
            $('.busOptionDetails').show();
            $('.busOptionDetailsLine1').html('Buses to '+result.destination+' From:');
            $('.busOptionDetailsLine2').html('&#x20B9; '+result.min_price);
            $('.busOptionView').show();
        }else{
            $('.busOptionView').attr('href', "#");
            $('.busOptionDetails').hide();
            $('.busOptionView').hide();
            $('.noBusesLabel').show();
        }
    });

    /*==========  show Loading icon and hide filter fields  ==========*/

    $(".filterCollapse").collapse('hide');
    $('.sortArea').hide();
    $('.niceform').css('visibility', 'hidden');

    var flightsAjax = $.ajax({
        url: "<?php echo site_url('flights_api/api/oneway_search');?>",
        type: "POST",
        data: { data : search_parameters },
        // xhr: function(){
        //     var xhr = new window.XMLHttpRequest();
        //     // //Upload progress
        //     // xhr.upload.addEventListener("progress", function(evt){
        //     //     if (evt.lengthComputable) {
        //     //         var percentComplete = evt.loaded / evt.total;
        //     //         //Do something with upload progress
        //     //         console.log(percentComplete);
        //     //     }
        //     // }, false);
        //     // //Download progress
        //     // xhr.addEventListener("progress", function(evt){
        //     //     console.log(evt);
        //     //     if (evt.lengthComputable) {
        //     //         var percentComplete = evt.loaded / evt.total;
        //     //         //Do something with download progress
        //     //         console.log(percentComplete);
        //     //     }
        //     // }, false);
        //     return xhr;
        // },
    })
    .done(function (flightData) {
        var result = $.parseJSON(flightData);
        $('#search').fadeIn(300);
        flightSegment = result.segment;
        fare = result.fare;
        fareBreakdown = result.fareBreakdown;
        fareRule = result.fareRule;
        generalInfo = result.general;
        layover = result.layoverInfo;
        segmentInfo = result.segmentInfo;
        if( result !== false ){
            /*==========  hide Loading icon and show filter fields  ==========*/

            $('#flight_spin').hide();
            $(".filterCollapse").collapse('show');
            $('.sortArea').show();
            $('.niceform').css('visibility', 'visible');

            /*==========  setting up filter variables  ==========*/
            
            MaxFare = parseFloat(result.filterValues.MaxFare);
            MinFare = parseFloat(result.filterValues.MinFare);
            MaxStops = parseInt(result.filterValues.MaxStops);
            MaxRound = MaxFare/100;
            MinRound = MinFare/100;
            MaxDepTime = parseInt(result.filterValues.MaxDepTime) + 10;
            MinDepTime = parseInt(result.filterValues.MinDepTime) - 10;

            MaxRound = (Math.ceil(MaxRound)*100) + 100;
            MinRound = (Math.floor(MinRound)*100) - 100;

            /*==========  setup checkboxes, and sliders  ==========*/
            
            // stops

            for (var i = 0 ; i <= MaxStops ; i++) {
                if( i === 0 ){
                    $('#stops-check').append('<div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="'+i+'"/>Non-stop</label></div>');    
                }else{
                    $('#stops-check').append('<div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="'+i+'"/>'+i+' stop(s)</label></div>');
                }
            };

            // Enable all stops by default
            $('#stops-check input').each(function() {
                $(this).attr('checked', true);  
            });

            // This should be after the stops are enabled
            $('#stops-check input:checked').each(function() {
                selectedStops.push($(this).attr('value'));
            });

            // stops end

            // ticket type (refundable or not)

            $('#ticket-check').append('<div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="true"/>Refundable</label></div><div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="false"/>Non-Refundable</label></div>');

            // Enable all ticket by default
            $('#ticket-check input').each(function() {
                $(this).attr('checked', true);  
            });

            // This should be after the ticket are enabled
            $('#ticket-check input:checked').each(function() {
                selectedTicket.push($(this).attr('value'));
            });

            // ticket type end

            // initialize range sliders
                initRangeSlider();
                initRangeSliderDuration();
            // initialize range sliders end

            for (var i = 0 ; i < result.filterValues.AirlineNames.length ; i++) 
            {
                $('#airlines-check').append('<div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="'+result.filterValues.AirlineNames[i]+'"/>'+result.filterValues.AirlineNames[i]+'</label></div>');
            };

            // Enable all airlines by default
            $('#airlines-check input').each(function() {
                $(this).attr('checked', true);  
            });

            // This should be after the airlines are enabled
            $('#airlines-check input:checked').each(function() {
                selectedAirlines.push($(this).attr('value'));
            });

            $('#range-bottom, #range-top').autoNumeric({
                aSep : ',',
                dGroup : 2
            });

            var flight_img = "this.src='<?php echo base_url('img/flightIcon.png');?>'";

            $.each(result.general, function(key, val){
                var bookBtn = '<button data-btnid="'+key+'" class="btn btn-change book_btn" type="button">BOOK</button>';
                oTable.row.add([
                    val.AirlineName,
                    '<div class="row"><img width="32px" src="<?php echo base_url("img/AirlineLogo/'+val.AirlineImage+'.gif");?>" onError='+flight_img+' /></div><div class="row">'+val.AirlineName+'</div>',
                    val.DepartureTimeMins,
                    val.DepartureTime,
                    val.ArrivalTime,
                    val.Duration,
                    val.TotalFare,
                    '<i class="fa fa-inr"></i>&nbsp;<span id="fare-'+key+'" class="currency">'+parseFloat(val.TotalFare).toFixed(2)+'</span>'+'\n<h6>Details</h6>',
                    val.StopsCount,
                    val.NonRefundable,
                    bookBtn
                ]).draw();
            });

            $('#search').show();

            $('span.currency').autoNumeric('init', {
                aSep: ',',
                dGroup: 2
            });

        }else{
            $('.vam').show();
            $('.resultsFilters').hide();
        }

    });
/*-----  End of OneWay Flight search With an Ajax call, Params = search_parameters  ------*/

/*==========================================================
=            filter section based on checkboxes            =
==========================================================*/

        $('#stops-check').on('click',"input",function(){
            flashTable();
            $('#stops-check input').each(function() {
                var isChecked = this.checked;
                if(isChecked) {
                    var index = selectedStops.indexOf(this.value);
                    if(index == -1) {
                        selectedStops.push(this.value);    
                    }                
                } else {
                    var index = selectedStops.indexOf(this.value);
                    if(index > -1) {
                        selectedStops.splice(index,1);
                    }
                }
            });
            oTable.draw();
        });

        $('#ticket-check').on('click',"input",function(){
            flashTable();
            $('#ticket-check input').each(function() {                
                var isChecked = this.checked;
                if( isChecked ){
                    var index = selectedTicket.indexOf(this.value);
                    if(index == -1){
                        selectedTicket.push(this.value);
                    }
                } else {
                    var index = selectedTicket.indexOf(this.value);
                    if( index > -1){
                        selectedTicket.splice(index, 1);  
                    }
                }
            });
            oTable.draw();
        });

        $('#airlines-check').on('click', "input", function(){
            flashTable();
            $('.airlineShowAllLink').html('Check All');
            var airlineContainer =  $('#airlines-check');
            var airlineCheckboxes = airlineContainer.find(':input');
            var airlineCheckboxesChecked = airlineContainer.find(':input:checked');
            if( airlineCheckboxesChecked.length === airlineCheckboxes.length ){
                $('.airlineShowAllLink').html('Uncheck All');
            }
            $('#airlines-check input').each(function() {
                var isChecked = this.checked;
                if(isChecked) {
                    var index = selectedAirlines.indexOf(this.value);
                    if(index == -1) {
                        selectedAirlines.push(this.value);    
                    }                
                } else {
                    var index = selectedAirlines.indexOf(this.value);
                    if(index > -1) {
                        selectedAirlines.splice(index,1);
                    }
                }
            });
            oTable.draw();
        });

/*-----  End of filter section based on checkboxes  ------*/

/*=======================================================
=            Row Details for selected flight            =
=======================================================*/

    // this section is to add data within the details row on click of a row.
    $('#search tbody').on('click', 'td', function () {

            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var selectedRowId = tr.data('rowid');

            if( $(this).children().attr('class') == 'btn btn-change book_btn' ){
                return true;
            }

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                tr.next('tr').removeClass('tr-drawer');
            }
            else {
                // Open this row
                row.child( format(flightSegment[selectedRowId], fare[selectedRowId], generalInfo[selectedRowId], layover[selectedRowId], segmentInfo[selectedRowId], selectedRowId) ).show();
                tr.addClass('shown');
                tr.next('tr').addClass('tr-drawer');
            }

    });

/*-----  End of Row Details for selected flight  ------*/


/*===================================================================
=            Submit details Of chosen files to next page            =
===================================================================*/

    $('#search tbody').on('click', '.book_btn', function(){
        $('.book_btn').attr('disabled', 'disabled');
        var index = $(this).data('btnid');
        $('#booking_details_form input[name=airline_name_field]').val(generalInfo[index].AirlineName);
        $('#booking_details_form input[name=from_field]').val(generalInfo[index].DepartureTime);
        $('#booking_details_form input[name=to_field]').val(generalInfo[index].ArrivalTime);
        $('#booking_details_form input[name=origin]').val(generalInfo[index].Origin);
        $('#booking_details_form input[name=destination]').val(generalInfo[index].Destination);
        $('#booking_details_form input[name=flight_duration_field]').val(generalInfo[index].Duration);
        $('#booking_details_form input[name=total_fare_field]').val(generalInfo[index].TotalFare);
        $('#booking_details_form input[name=booking_details]').val(JSON.stringify(flightSegment[index]));
        $('#booking_details_form input[name=fare]').val(JSON.stringify(fare[index]));
        $('#booking_details_form input[name=fare_breakdown]').val(JSON.stringify(fareBreakdown[index]));
        $('#booking_details_form input[name=fare_rule]').val(JSON.stringify(fareRule[index]));
        $('#booking_details_form input[name=flight_type]').val(generalInfo[index].IsLCC);
        $('#booking_details_form input[name=non_refundable]').val(generalInfo[index].NonRefundable);
        $('#booking_details_form input[name=segment_key]').val(generalInfo[index].SegmentKey);
        $('#booking_details_form input[name=Source]').val(generalInfo[index].Source);
        $('#booking_details_form input[name=TripIndicator]').val(generalInfo[index].TripIndicator);
        $('#booking_details_form input[name=IbSegCount]').val(generalInfo[index].IbSegCount);
        $('#booking_details_form input[name=ObSegCount]').val(generalInfo[index].ObSegCount);
        $('#booking_details_form input[name=PromotionalPlanType]').val(generalInfo[index].PromotionalPlanType);


        if( layover[index] !== null){
            if(typeof layover[index] != 'undefined'){
                $('#booking_details_form input[name=layover]').val(layover[index]);
            }
        }
        $('#booking_details_form').submit();
    });


/*-----  End of Submit details Of chosen files to next page  ------*/


})();

$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var iColumn = 6;
        var iMin = rangeSlider.slider("values", 0);
        var iMax = rangeSlider.slider("values", 1);
         
        var iVersion = aData[iColumn] == "-" ? 0 : aData[iColumn]*1;
        if ( iMin == "" && iMax == "" )
        {
            return true;
        }
        else if ( iMin == "" && iVersion < iMax )
        {
            return true;
        }
        else if ( iMin < iVersion && "" == iMax )
        {
            return true;
        }
        else if ( iMin < iVersion && iVersion < iMax )
        {
            return true;
        }
        return false;
    }
); 

$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var iColumn = 2;
        var iMin = rangeSliderDuration.slider("values", 0);
        var iMax = rangeSliderDuration.slider("values", 1);
         
        var iVersion = aData[iColumn] == "-" ? 0 : aData[iColumn]*1;
        if ( iMin == "" && iMax == "" )
        {
            return true;
        }
        else if ( iMin == "" && iVersion < iMax )
        {
            return true;
        }
        else if ( iMin < iVersion && "" == iMax )
        {
            return true;
        }
        else if ( iMin < iVersion && iVersion < iMax )
        {
            return true;
        }
        return false;
    }
); 

$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var iColumn = 0;
         
        var iVersion = aData[iColumn];

        for (i = 0; i < selectedAirlines.length; i++) { 
            if(iVersion == selectedAirlines[i]){
                return true;
            }
        }
        return false;

    }
); 

$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var iColumn = 8;
         
        var iVersion = aData[iColumn];

        for (i = 0; i < selectedStops.length; i++) { 
            if(iVersion == selectedStops[i]){
                return true;
            }
        }
        return false;

    }
); 

$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var iColumn = 9;
         
        var iVersion = aData[iColumn];

        for (i = 0; i < selectedTicket.length; i++) { 
            if(iVersion == selectedTicket[i]){
                return true;
            }
        }
        return false;

    }
); 

function flashTable(){
    $('#search').hide();
    $('#search').fadeIn(200);
}

/*=============================================
=            ToolTips And PopOvers            =
=============================================*/

/*==========  ToolTip Code  ==========*/
    $('#origin').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: "<?php echo $_GET['from'];?>"
        });
        $(this).tooltip('show');
    });

    $('#destination').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: "<?php echo $_GET['to'];?>"
        });
        $(this).tooltip('show');
    });

/*-----  End of ToolTips And PopOvers  ------*/

/*=============================================
    Weather Information
==============================================*/
//weather

        var searchedDate = new Date("<?php echo date('c', strtotime($_GET['oneway_date']));?>");
        var currentDate = new Date();
        var isPredictableWeather = 0;

        var timeDiff = Math.abs(searchedDate.getTime() - currentDate.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 

        if( diffDays >= 7 ){
            isPredictableWeather = 0;
        }else{
            isPredictableWeather = 1;
        }

        var required_date = searchedDate.getDate() + '-' + (searchedDate.getMonth() + 1) + '-' + searchedDate.getFullYear();
        var dt = searchedDate.toString();
        var display_date = dt.substr(8, 2) + " " + (dt.substr(4, 3)) + " " + dt.substr(11, 4);
        var sourceGetCity = "<?php echo $_GET['from']?>";
        var destinationGetCity = "<?php echo $_GET['to']?>";
        var cityArray = sourceGetCity.split(',');
        var source_city_name = cityArray[0];
        cityArray = destinationGetCity.split(',');
        var destination_city_name = cityArray[0];
        var source_city_lat;
        var source_city_long;
        var destination_city_lat;
        var destination_city_long;

        var geocoder =  new google.maps.Geocoder();
        geocoder.geocode( { 'address': source_city_name }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                source_city_lat = results[0].geometry.location.lat();
                source_city_long = results[0].geometry.location.lng(); 
                $(document).trigger('secondAddress');
            } else {
                return;
            }
        });

        $(document).on('secondAddress', function(){
            geocoder.geocode( { 'address': destination_city_name }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    destination_city_lat = results[0].geometry.location.lat();
                    destination_city_long = results[0].geometry.location.lng();
                    $.ajax({
                        type: "POST",
                        url : "<?php echo site_url('weather');?>",
                        data : { choosen_date: required_date, source_city_lat : source_city_lat , source_city_long : source_city_long, destination_city_lat : destination_city_lat , destination_city_long : destination_city_long, is_predictable_weather: isPredictableWeather}
                    })
                    .done(function(weatherdata){
                        $('.weather_spinner').hide();
                        var wData = $.parseJSON(weatherdata);
                        if( wData.isHistoric ){
                            // historic weather
                            $('.weatherDetails .from .date').html('<div class="title">Weather for:</div><span>'+destination_city_name+' (Today)</span>');
                            $('.weatherDetails .from .icon').html(getWeatherIcon(wData.weatherResponseDestination));
                            $('.weatherDetails .from .summary').html(wData.weatherResponseDestination.summary);
                            $('.weatherDetails .from .temperature').html(' <div class="row actual"> <div class="col-xs-24"> <div class="title">Temperature</div> <span class="h4 min">'+ Math.round(wData.weatherResponseDestination.temperature) +'&deg;C</span> </div>');

                            $('.weatherDetails .to .date').html('<div class="title">Weather for:</div><span>'+source_city_name+' (Today)</span>');
                            $('.weatherDetails .to .icon').html(getWeatherIcon(wData.weatherResponseSource));
                            $('.weatherDetails .to .summary').html(wData.weatherResponseSource.summary);
                            $('.weatherDetails .to .temperature').html(' <div class="row actual"> <div class="col-xs-24"> <div class="title">Temperature</div> <span class="h4 min">'+ Math.round(wData.weatherResponseSource.temperature) +'&deg;C</span> </div>');
                        }else{
                            // current weather
                            $('.weatherDetails .from .date').html('<div class="title">Weather for:</div><span>'+destination_city_name+'<br /> ('+display_date+')</span>');
                            $('.weatherDetails .from .icon').html(getWeatherIcon(wData.weatherResponseDestination));
                            $('.weatherDetails .from .summary').html(wData.weatherResponseDestination.summary);
                            $('.weatherDetails .from .temperature').html(' <div class="row actual"> <div class="col-xs-12"> <div class="title">Min</div><span class="h4 min">'+ Math.round(wData.weatherResponseDestination.minTemperature) +'&deg;C</span> </div><div class="col-xs-12"> <div class="title">Max</div><span class="h4 max">'+ Math.round(wData.weatherResponseDestination.maxTemperature) +'&deg;C</span> </div></div>');

                            $('.weatherDetails .to .date').html('<div class="title">Weather for:</div><span>'+source_city_name+'<br /> ('+display_date+')</span>');
                            $('.weatherDetails .to .icon').html(getWeatherIcon(wData.weatherResponseSource));
                            $('.weatherDetails .to .summary').html(wData.weatherResponseSource.summary);
                            $('.weatherDetails .to .temperature').html(' <div class="row actual"> <div class="col-xs-12"> <div class="title">Min</div><span class="h4 min">'+ Math.round(wData.weatherResponseSource.minTemperature) +'&deg;C</span> </div><div class="col-xs-12"> <div class="title">Max</div><span class="h4 max">'+ Math.round(wData.weatherResponseSource.maxTemperature) +'&deg;C</span> </div></div>');
                        }
                    });
                } else {
                    return;
                }
            });
        });

    //weather end

//}) ();

    //creates a weather icon
    function getWeatherIcon(wData){
        var weatherIcon = '';
        switch(wData.icon){
            case 'clear-day' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/clear.png" />';
            break;
            case 'clear-night' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/clear.png" />';
            break;
            case 'rain' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/rain.png" />';
            break;
            case 'snow' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/snow.png" />';
            break;
            case 'sleet' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/sleet.png" />';
            break;
            case 'wind' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/wind.png" />';
            break;
            case 'fog' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/fog.png" />';
            break;
            case 'cloudy' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/cloudy.png" />';
            break;
            case 'partly-cloudy-day' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/partly_cloudy.png" />';
            break;
            case 'partly-cloudy-night' : 
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/partly_cloudy.png" />';
            break;
            default :
            weatherIcon = '<img height="75" class="weatherIcon" onload="javascript:$(this).show()" style="display:none;" src="../../../img/weather_icons/clear.png" />';
            break;
        }
        return weatherIcon;

    }
    //creates a weather icon end

</script>