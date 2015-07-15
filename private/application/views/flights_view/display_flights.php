<form action="<?php echo site_url('api/flights/traveller_details');?>" id="booking_details_form" method="post" style="display:none;">
    <input class="form-control" id="airline_name_field" name="airline_name_field" type="text" />
    <input class="form-control" id="from_field" name="from_field" type="text" />
    <input class="form-control" id="to_field" name="to_field" type="text" />
    <input class="form-control" id="from_field_str" name="from_field_str" value="<?php echo $_GET['from']?>" type="text" />
    <input class="form-control" id="to_field_str" name="to_field_str" value="<?php echo $_GET['to']?>" type="text" />
    <input class="form-control" id="flight_duration_field" name="flight_duration_field" type="text" />
    <input class="form-control" name="total_fare_field" type="text" />
    <input class="form-control" name="adult_count_field" type="text" value="<?php echo $_GET['adult_count'];?>"/>
    <input class="form-control" name="youth_count_field" type="text" value="<?php echo $_GET['youth_count'];?>"/>
    <input class="form-control" name="kids_count_field" type="text" value="<?php echo $_GET['kids_count'];?>"/>
    <input class="form-control" name="total_count_field" type="text" value="<?php echo $_GET['total_count'];?>"/>
    <input class="form-control" name="travel_date" type="text" value="<?php echo $_GET['oneway_date'];?>"/>
    <input class="form-control" type="text" name="booking_details" />
    <input class="form-control" type="text" name="flight_type" />
    <input class="form-control" type="text" name="layover" />
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
                                                <div class="col-lg-21 col-lg-offset-3 TravelDate center-align-text"></div>
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
                            <?php
                                $this->load->view("flights/search_view.php");
                            ?>
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
                        
                            <div class="compare-unit select niceform">
                                <table class="table table-hover-custom select-list smaller hide" id="search">
                                    <thead>
                                        <tr>
                                            <th class="center-align-text"><h4>Airline_hidden</h4></th>
                                            <th class="center-align-text"><h4>Airline</h4></th>
                                            <th class="center-align-text"><h4>from_hidden</h4></th>
                                            <th class="center-align-text"><h4>Departure</h4></th>
                                            <th class="center-align-text"><h4>Arrival</h4></th>
                                            <th class="center-align-text"><h4>Duration</h4></th>
                                            <!--th>Lcc</th-->
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
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    var oTable = $('#search').DataTable({
        "bDeferRender": false,
        "aoColumnDefs": [
            { "aDataSort": [ 6, 7 ], "aTargets": [ 7 ] },
            { "targets": [ 0, 2, 6, 8, 9 ], "visible": false },
            { "targets": 10, "orderable": false }
        ],
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('id', 'row-'+iDataIndex);
            $(nRow).children('td').addClass('flTableData');
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
    var return_data = [];

(function(){
// navbar modifiedsearch alter
    $('#collapseSearch').on('show.bs.collapse', function(e){
        if( e.target.id === 'collapseSearch' ){
            $('.searchIcon').html(' <div class="row center-align-text"><span class="glyphicon glyphicon-remove"></span></div><div class="row"><span>Close</span></div>');
            $('.searchIcon').css({
                "padding-right": "20px",
                "padding-top": "6px"
            });
        }
    });

    $('#collapseSearch').on('hide.bs.collapse', function(e){
        if( e.target.id === 'collapseSearch' ){
            $('.searchIcon').html(' <div class="row center-align-text"><span class="glyphicon glyphicon-search"></span></div><div class="row"><span>Modify <br/>Search</span></div>');
            $('.searchIcon').css({
                "padding-right": "16px"
            });
        }
    });
// navbar modifiedsearch alter end

    $('.accord-content #oneway_date').hide();
    $('.accord-content #passenger_count').hide();
    $('.accord-content .fl_btn1').hide();

    // submit the booking details
    $('#search tbody').on('click', '.book_btn', function(){
        $('.book_btn').attr('disabled', 'disabled');
        var btn_id = $(this).attr('id');
        var temp_arr = btn_id.split('-');
        var index = temp_arr[1];

        $('#booking_details_form input[name=airline_name_field]').val($('#row-'+index+' td').eq(0).html());
        $('#booking_details_form input[name=from_field]').val($('#row-'+index+' td').eq(1).html());
        $('#booking_details_form input[name=to_field]').val($('#row-'+index+' td').eq(2).html());
        $('#booking_details_form input[name=flight_duration_field]').val($('#row-'+index+' td').eq(3).html());
        $('#booking_details_form input[name=total_fare_field]').val($('#row-'+index+' td #fare-'+index).html());
        $('#booking_details_form input[name=booking_details]').val(JSON.stringify(return_data.details[index]));
        $('#booking_details_form input[name=flight_type]').val(JSON.stringify(return_data.flight[index]));
        if( return_data.layover !== null){
            if(typeof return_data.layover[index] != 'undefined'){
                $('#booking_details_form input[name=layover]').val(return_data.layover[index].duration);
            }
        }
        $('#booking_details_form').submit();

    });

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
    
    var search_parameters = 
    {
        from: from_arr, 
        to: to_arr, 
        adult_count: "<?php echo $_GET['adult_count'];?>", 
        flight_type: "<?php echo $_GET['flight_type'];?>", 
        youth_count: "<?php echo $_GET['youth_count'];?>", 
        kids_count: "<?php echo $_GET['kids_count'];?>", 
        total_count: "<?php echo $_GET['total_count'];?>",
        oneway_date: "<?php echo $_GET['oneway_date'];?>",
        airline_preference: "<?php echo $_GET['airline_preference_1'];?>",
        travel_class: travel_class,
    };

    $(".filterCollapse").collapse('hide');
    $('.dummyImages').hide();
    $('.sortArea').hide();
    // $('#search').hide();
    $('.niceform').css('visibility', 'hidden');

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
            var search_base = "<?php echo site_url('cab_api/cabs/flights_to_cabs?source='.$_GET['from'].'&destination='.$_GET['to'].'&adult_count='.$_GET['adult_count'].'&youth_count='.$_GET['youth_count'].'&journey_date='.$_GET['oneway_date'].'&oneway_change_cab=true&total_count='.$_GET['total_count']);?>";
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

    var flightsAjax = $.ajax({
        url: "<?php echo site_url('api/flights/search_flights_oneway');?>",
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
    .done(function (retData) {
        var res = $.parseJSON(retData);

        if(res !== 'false')
        {
            $('#flight_spin').hide();
            $('.dummyImages').show();
            $(".filterCollapse").collapse('show');
            $('.niceform').css('visibility', 'visible');
            $('.sortArea').show();
            $('#search').removeClass('hide');
            $('.airlineShowAllLink').show();
            
            var result = $.parseJSON(retData);
            return_data = result;

            max_fare = parseFloat(result.fare_max);
            min_fare = parseFloat(result.fare_min);
            max_stops = parseInt(result.max_stops);
            max_round = max_fare/100;
            min_round = min_fare/100;
            max_duration = parseInt(result.max_duration) + 10;
            min_duration = parseInt(result.min_duration) - 10;

            max_round = (Math.ceil(max_round)*100) + 100;
            min_round = (Math.floor(min_round)*100) - 100;

            for (var i=0;i<=max_stops;i++) 
            {
                if( i === 0 ){
                    $('#stops-check').append('<div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="'+i+'"/>Non-stop</label></div>');    
                }else{
                    $('#stops-check').append('<div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="'+i+'"/>'+i+' stop(s)</label></div>');
                }
            };

            $('#ticket-check').append('<div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="true"/>Refundable</label></div><div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="false"/>Non-Refundable</label></div>');

            for (var i=0;i<result.airline_list.length;i++) 
            {
                $('#airlines-check').append('<div class="checkbox"><label><input type="checkbox" class="filterCheckbox" value="'+result.airline_list[i]+'"/>'+result.airline_list[i]+'</label></div>');
            };

            // Enable all airlines by default
            $('#airlines-check input').each(function() {
                $(this).attr('checked', true);  
            });

            // This should be after the airlines are enabled
            $('#airlines-check input:checked').each(function() {
                selectedAirlines.push($(this).attr('value'));
            });

            // Enable all stops by default
            $('#stops-check input').each(function() {
                $(this).attr('checked', true);  
            });

            // This should be after the stops are enabled
            $('#stops-check input:checked').each(function() {
                selectedStops.push($(this).attr('value'));
            });

            // Enable all ticket by default
            $('#ticket-check input').each(function() {
                $(this).attr('checked', true);  
            });

            // This should be after the ticket are enabled
            $('#ticket-check input:checked').each(function() {
                selectedTicket.push($(this).attr('value'));
            });

            initRangeSlider();
            initRangeSliderDuration();

            $('#range-bottom, #range-top').autoNumeric({
                aSep : ',',
                dGroup : 2
            });
            var flight_img = "this.src='<?php echo base_url('img/flightIcon.png');?>'";
            $.each(result.results, function(i,val){
                var bookBtn = '<button id="button-'+i+'" class="btn btn-change book_btn" type="button">BOOK</button>';
                oTable.row.add([
                    val.airline,
                    '<div class="row"><img width="32px" src="<?php echo base_url("img/AirlineLogo/'+val.airline_code+'.gif");?>" onError='+flight_img+' /></div><div class="row">'+val.airline+'</div>',
                    val.from_hidden,
                    val.from,
                    val.to,
                    val.duration,
                    val.fare,
                    '<i class="fa fa-inr"></i>&nbsp;<span id="fare-'+i+'" class="currency">'+parseFloat(val.fare).toFixed(2)+'</span>'+'\n<h6>Details</h6>',
                    val.stops,
                    val.ticket_type,
                    bookBtn
                ]).draw();
            });

            $('span.currency').autoNumeric('init', {
                aSep: ',',
                dGroup: 2
            });
        }else{
            $('.vam').show();
            $('.resultsFilters').hide();
        }
    });

    $('.busOptionView, .cabOptionView').on('click', function(){
        flightsAjax.abort();
        $(this).html('Loading...');
        $(this).css('font-size', '12px');
        $(this).attr('disabled', 'disabled');
    });

        $('.keywordSearch').on('keyup',function(){
            oTable.search($('.keywordSearch').val()).draw();
        });

        // this section is to add data within the details row on click of a row.
        $('#search tbody').on('click', 'td', function () {
            if( $(this).attr('class') === 'tr-drawer' || typeof $(this).attr('class') === 'undefined' ){
                return false;
            }else{
                var tr = $(this).closest('tr');
                var row = oTable.row( tr );
                var row_details_id = tr.attr('id');

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
                    row.child( format(return_data, row_details_id) ).show();
                    tr.addClass('shown');
                    tr.next('tr').addClass('tr-drawer');
                }
            }
        });

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

    //tooltip code
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
    //end tooltip code

    //    $(window).scroll(function(){
    //        var x = $(this).scrollTop();
    //        var topMargin = x - 80;
    //
    //        var headerH = $('#bs-example-navbar-collapse-1').outerHeight(true);
    //        if ( topMargin > headerH ) {
    //            $('.filtersContainer').css({'margin-top': topMargin+'px'});
    //        }
    //        else{
    //            $('.filtersContainer').css({'margin-top': "65px"});   
    //        }
    //    });

    //reset filters part
    $('.airlineShowAllLink').on('click resetAllFilters', function(e){
        e.preventDefault();
        var airlineContainer = $('#airlines-check');
        var airlineCheckboxes = airlineContainer.find(':input');
        var airlineCheckboxesChecked = airlineContainer.find(':input:checked');
        var Uncheck = 1;
        if( e.type === "resetAllFilters" && this.text === "Uncheck All" ){
            Uncheck = 0;
        }

        if( airlineCheckboxesChecked.length === airlineCheckboxes.length && Uncheck ){
            $.each(airlineCheckboxes, function(i, val){
                val.click();
            });
        }else{
            $.each(airlineCheckboxes, function(i, val){
                if( val.checked === false ){
                    val.click();
                }
            });
        }
    });

    $(document).on('resetAllFilters', function(){
        var airlineTicketContainer = $('#ticket-check');
        var airlineStopsContainer = $('#stops-check');
        var airlineTicketCheckboxes = airlineTicketContainer.find(':input');
        var airlineStopsCheckboxes = airlineStopsContainer.find(':input');

        $.each(airlineTicketCheckboxes, function(i, val){
            if( val.checked === false ){
                val.click();
            }
        });

        $.each(airlineStopsCheckboxes, function(i, val){
            if( val.checked === false ){
                val.click();
            }
        });

        resetSlider();
        initRangeSlider();
        initRangeSliderDuration();
        oTable.draw();
    });

    //reset filters part end


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

}) ();

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

    // var return_row_element = [];
    var max_fare = 0;
    var min_fare = 0;
    var max_round = 0;
    var min_round = 0;
    var max_duration = 0;
    var min_duration = 0;
    
    var selectedAirlines = [];
    var selectedStops = [];
    var selectedTicket = [];

    var rangeSlider = $( "#slider-range" );
    var rangeSliderDuration = $( "#slider-range-duration" );

    function resetSlider() {
        var $slider = $("#slider-range");
        var $sliderDuration = $("#slider-range-duration");
        $slider.slider("values", 0, min_round);
        $slider.slider("values", 1, max_round);
        $sliderDuration.slider("values", 0, min_duration);
        $sliderDuration.slider("values", 1, max_duration);
    }

    function format (d, row_details_id) {

        if( typeof row_details_id === 'undefined' ){
            return "<div></div>";
        }

        var temp_arr = row_details_id.split('-');
        var ind = temp_arr[1];
        var return_row_element = [];

        if(return_data.results[ind].stops == 0){
            return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'
                        +'<div class="col-lg-24">'
                            +'<div class="row">'
                                +'<div class="col-lg-16">'
                                    +'<div class="row">'
                                        +'<div class="col-lg-6 std-lineheight left-text">'
                                            +'<span class="inline-h4">'+return_data.travel[ind].origin+'</span> <span class="glyphicon glyphicon-arrow-right"></span> <span class="inline-h4">'+return_data.travel[ind].destination+'</span>'
                                        +'</div>'
                                        +'<div class="col-lg-7 right-text"><h4 class="h4-cng">'
                                            +'Departure'
                                        +'</h4></div>'
                                        +'<div class="col-lg-4 center-align-text">'
                                        +'</div>'
                                        +'<div class="col-lg-7 left-text"><h4 class="h4-cng">'
                                            +'Arrival'
                                        +'</h4></div>'
                                    +'</div>'
                                    +'<div class="row">'
                                        +'<div class="col-lg-6 left-text">'+return_data.flight_info[ind].name_of_airline+'</div>'
                                        +'<div class="col-lg-7 right-text"><span class="ctCode">'+return_data.travel[ind].origin+'</span> '+return_data.results[ind].from+' <div class="row sd-details-line-height">'+return_data.flight_info[ind].source_details+'</div><div class="row">'+return_data.flight_info[ind].dep_date+'</div></div>'
                                        +'<div class="col-lg-4 center-align-text duration-bg-color"><div class="row"><span class="glyphicon glyphicon-time"></span></div><div class="row">'+return_data.results[ind].duration+'</div></div>'
                                        +'<div class="col-lg-7 left-text">'+return_data.results[ind].to+' <span class="ctCode">'+return_data.travel[ind].destination+'</span><div class="row sd-details-line-height">'+return_data.flight_info[ind].destination_details+'</div><div class="row">'+return_data.flight_info[ind].arr_date+'</div></div>'
                                    +'</div>'
                                +'</div>'
                                +'<div class="col-lg-8 large-left-border">'
                                    +'<div class="row">'                                
                                        +'<div class="col-lg-24"><h4 class="h4-cng">Fare Breakdown</h4></div>'
                                    +'</div>'
                                    +'<div class="row">'                                
                                        +'<div class="col-lg-12 left-text">Taxes</div>'
                                        +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+parseFloat(return_data.fare_breakdown[ind].taxes).toFixed(2)+'</div>'
                                    +'</div>'
                                    +'<div class="row">'
                                        +'<div class="col-lg-12 left-text">Base Fare</div>'
                                        +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+parseFloat(return_data.fare_breakdown[ind].base_fare).toFixed(2)+'</div>'
                                    +'</div>'
                                    +'<div class="row">'
                                        +'<div class="col-lg-12 left-text">Total Fare</div>'
                                        +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+parseFloat(return_data.fare_breakdown[ind].tot_fare).toFixed(2)+'</div>'
                                    +'</div>'
                                    +'<div class="row">'                                
                                        +'<div class="col-lg-24"><h4 class="h4-cng">Airline refund policy</h4></div>'
                                    +'</div>'
                                    +'<div class="row">'
                                        +'<div class="col-lg-12 left-text">Ticket Type</div>'
                                        +'<div class="col-lg-12 right-text">'+return_data.fare_breakdown[ind].ticket_type+'</div>'
                                    +'</div>'
                                +'</div>'   
                            +'</div>'
                        +'</div>'
                    +'</table>';
            }else{
                return_row_element[0] = 
                    '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'
                        +'<div class="col-lg-24">'
                            +'<div class="row">'
                                +'<div class="col-lg-16">'
                                    +'<div class="row">'
                                        +'<div class="col-lg-6 left-text std-lineheight">'
                                            +'<span class="inline-h4">'+return_data.travel_connecting[ind].origin+'</span> <span class="glyphicon glyphicon-arrow-right"></span> <span class="inline-h4">'+return_data.travel_connecting[ind].destination+'</span>'
                                        +'</div>'
                                        +'<div class="col-lg-7 right-text"><h4 class="h4-cng">'
                                            +'Departure'
                                        +'</h4></div>'
                                        +'<div class="col-lg-4 center-align-text">'
                                        +'</div>'
                                        +'<div class="col-lg-7 left-text"><h4 class="h4-cng">'
                                            +'Arrival'
                                        +'</h4></div>'
                                    +'</div>';
                for (var i=1  ; i <= return_data.multi_flight_info[ind].length ; i++) {
                    if( return_data.layover[ind].length > 1 ){
                        if( i === return_data.multi_flight_info[ind].length ){
                            return_row_element[i] = 
                            '<div class="row">'
                                +'<div class="col-lg-6 left-text">'+return_data.multi_flight_info[ind][i-1].name_of_airline+'</div>'
                                +'<div class="col-lg-7 right-text"><div class="row">'+return_data.results[ind].multi[i-1].from+' <span class="ctCode">'+return_data.travel_connecting[ind].connecting[i-1].origin+'</span></div><div class="row sd-details-line-height">'+return_data.multi_flight_info[ind][i-1].source_details+'</div><div class="row">'+return_data.multi_flight_info[ind][i-1].dep_date+'</div></div>'
                                +'<div class="col-lg-4 center-align-text duration-bg-color"><div class="row"><span class="glyphicon glyphicon-time"></span></div><div class="row">'+return_data.results[ind].multi[i-1].duration+'</div></div>'
                                +'<div class="col-lg-7 left-text"><div class="row"><span class="ctCode">'+return_data.travel_connecting[ind].connecting[i-1].destination+'</span> '+return_data.results[ind].multi[i-1].to+'</div><div class="row sd-details-line-height">'+return_data.multi_flight_info[ind][i-1].destination_details+'</div><div class="row">'+return_data.multi_flight_info[ind][i-1].arr_date+'</div></div>'
                            +'</div>'
                        }else{
                            return_row_element[i] = 
                            '<div class="row">'
                                +'<div class="col-lg-6 left-text">'+return_data.multi_flight_info[ind][i-1].name_of_airline+'</div>'
                                +'<div class="col-lg-7 right-text"><div class="row">'+return_data.results[ind].multi[i-1].from+' <span class="ctCode">'+return_data.travel_connecting[ind].connecting[i-1].origin+'</span></div><div class="row sd-details-line-height">'+return_data.multi_flight_info[ind][i-1].source_details+'</div><div class="row">'+return_data.multi_flight_info[ind][i-1].dep_date+'</div></div>'
                                +'<div class="col-lg-4 center-align-text duration-bg-color"><div class="row"><span class="glyphicon glyphicon-time"></span></div><div class="row">'+return_data.results[ind].multi[i-1].duration+'</div></div>'
                                +'<div class="col-lg-7 left-text"><div class="row"><span class="ctCode">'+return_data.travel_connecting[ind].connecting[i-1].destination+'</span> '+return_data.results[ind].multi[i-1].to+'</div><div class="row sd-details-line-height">'+return_data.multi_flight_info[ind][i-1].destination_details+'</div><div class="row">'+return_data.multi_flight_info[ind][i-1].arr_date+'</div></div>'
                                +'<div class="col-lg-12"><div class="sr_sptr"></div></div>'
                                +'<div class="col-lg-6"><div class="center-align-text"> Layover - '+return_data.layover[ind][i-1]+'</div></div>'
                                +'<div class="col-lg-6"><div class="sr_sptr"></div></div>'
                            +'</div>'
                        }
                    }else{
                        if( i === return_data.multi_flight_info[ind].length ){
                            return_row_element[i] = 
                            '<div class="row">'
                                +'<div class="col-lg-6 left-text">'+return_data.multi_flight_info[ind][i-1].name_of_airline+'</div>'
                                +'<div class="col-lg-7 right-text"><div class="row">'+return_data.results[ind].multi[i-1].from+' <span class="ctCode">'+return_data.travel_connecting[ind].connecting[i-1].origin+'</span></div><div class="row sd-details-line-height">'+return_data.multi_flight_info[ind][i-1].source_details+'</div><div class="row">'+return_data.multi_flight_info[ind][i-1].dep_date+'</div></div>'
                                +'<div class="col-lg-4 center-align-text duration-bg-color"><div class="row"><span class="glyphicon glyphicon-time"></span></div><div class="row">'+return_data.results[ind].multi[i-1].duration+'</div></div>'
                                +'<div class="col-lg-7 left-text"><div class="row"><span class="ctCode">'+return_data.travel_connecting[ind].connecting[i-1].destination+'</span> '+return_data.results[ind].multi[i-1].to+'</div><div class="row sd-details-line-height">'+return_data.multi_flight_info[ind][i-1].destination_details+'</div><div class="row">'+return_data.multi_flight_info[ind][i-1].arr_date+'</div></div>'
                            +'</div>'
                        }else{
                            return_row_element[i] = 
                            '<div class="row">'
                                +'<div class="col-lg-6 left-text">'+return_data.multi_flight_info[ind][i-1].name_of_airline+'</div>'
                                +'<div class="col-lg-7 right-text"><div class="row">'+return_data.results[ind].multi[i-1].from+' <span class="ctCode">'+return_data.travel_connecting[ind].connecting[i-1].origin+'</span></div><div class="row sd-details-line-height">'+return_data.multi_flight_info[ind][i-1].source_details+'</div><div class="row">'+return_data.multi_flight_info[ind][i-1].dep_date+'</div></div>'
                                +'<div class="col-lg-4 center-align-text duration-bg-color"><div class="row"><span class="glyphicon glyphicon-time"></span></div><div class="row">'+return_data.results[ind].multi[i-1].duration+'</div></div>'
                                +'<div class="col-lg-7 left-text"><div class="row"><span class="ctCode">'+return_data.travel_connecting[ind].connecting[i-1].destination+'</span> '+return_data.results[ind].multi[i-1].to+'</div><div class="row sd-details-line-height">'+return_data.multi_flight_info[ind][i-1].destination_details+'</div><div class="row">'+return_data.multi_flight_info[ind][i-1].arr_date+'</div></div>'
                                +'<div class="col-lg-12"><div class="sr_sptr"></div></div>'
                                +'<div class="col-lg-6"><div class="center-align-text"> Layover - '+return_data.layover[ind]+'</div></div>'
                                +'<div class="col-lg-6"><div class="sr_sptr"></div></div>'
                            +'</div>'
                        }
                    }
                }
                return_row_element[i] = 
                        '</div>'
                            +'<div class="col-lg-8 large-left-border">'
                                +'<div class="row">'                                
                                    +'<div class="col-lg-24"><h4 class="h4-cng">Fare Breakdown</h4></div>'
                                +'</div>'
                                +'<div class="row">'                                
                                    +'<div class="col-lg-12 left-text">Taxes</div>'
                                    +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+parseFloat(return_data.multi_fare_breakdown[ind].taxes).toFixed(2)+'</div>'
                                +'</div>'
                                +'<div class="row">'
                                    +'<div class="col-lg-12 left-text">Base Fare</div>'
                                    +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+parseFloat(return_data.multi_fare_breakdown[ind].base_fare).toFixed(2)+'</div>'
                                +'</div>'
                                +'<div class="row">'
                                    +'<div class="col-lg-12 left-text">Total Fare</div>'
                                    +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+parseFloat(return_data.multi_fare_breakdown[ind].tot_fare).toFixed(2)+'</div>'
                                +'</div>'
                                +'<div class="row">'                                
                                    +'<div class="col-lg-24"><h4 class="h4-cng">Airline refund policy</h4></div>'
                                +'</div>'
                                +'<div class="row">'
                                    +'<div class="col-lg-12 left-text">Ticket Type</div>'
                                    +'<div class="col-lg-12 right-text">'+return_data.multi_fare_breakdown[ind].ticket_type+'</div>'
                                +'</div>'
                            +'</div>'   
                        +'</div>'
                    +'</div>'
                +'</table>';

            var return_element = '';
            for (var j = 0 ; j < return_row_element.length ; j++) {
                return_element += return_row_element[j];
            };
            return return_element;
        }
    }

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

    var initRangeSlider =  function(){
        rangeSlider.slider({
           range: true,
           min: min_round,
           max: max_round,
           step: 100,
           values: [ min_round, max_round ],
           slide: function( event, ui ) {
                var priceTextBtm = parseFloat(ui.values[ 0 ]).toFixed(2);
                var priceTextTop = parseFloat(ui.values[ 1 ]).toFixed(2);

                $('#range-bottom').html(priceTextBtm);
                $('#range-top').html(priceTextTop);
                $('#range-bottom').autoNumeric('set', priceTextBtm);
                $('#range-top').autoNumeric('set', priceTextTop);
           },
           stop: function(event, ui) {
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
           min: min_duration,
           max: max_duration,
           step: 10,
           values: [ min_duration , max_duration ],
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

    $('#range-bottom-duration').html(min_duration.convertToTime());
    $('#range-top-duration').html(max_duration.convertToTime());

    }

    Number.prototype.convertToTime = function( ){
        var hours = Math.floor( this / 60 );
        var minutes = this - (hours * 60);

        if(hours < 10) hours = '0' + hours;
        if(minutes < 10) minutes = '0' + minutes;

        if(minutes == 0) minutes = '00';

        return hours+':'+minutes;
    }

    function flashTable(){
        $('#search').hide();
        $('#search').fadeIn(200);
    };

</script>