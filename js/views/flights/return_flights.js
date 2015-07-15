    var oTable = $('#search').DataTable({
        "bDeferRender": false,
        "aoColumnDefs": [
            { "aDataSort": [ 7, 8 ], "aTargets": [ 8 ] },
            { "targets": [ 0, 2, 3, 7, 9, 10 ], "visible": false },
            { "targets": 11, "orderable": false }
        ],
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('id', 'row-'+iDataIndex);
            $(nRow).children('td').addClass('flTableData');
        },
        "language": {
            "zeroRecords": '<div> <div class="row"> <div class="col-xs-24 center-align-text"> <h2>Sorry, No matching records found for your search</h2> <span class="h4 mod_search_error" data-toggle="collapse" data-target="#collapseSearch" >Modify Search</span> <span>|</span> <span onclick="javascript: $('+ "'.airlineShowAllLink'" +').trigger('+"'resetAllFilters'"+');" class="h4 reset_search_error">Reset Filters</span> </center> </div></div></div>'
        },
        "bDestroy": true,
        "aaSorting": [[7, 'asc']],
        "bPaginate": false,
        "bInfo": false,
        "bFilter": true,
        "bScrollCollapse": true,
        "fnInitComplete": function() {
            this.fnAdjustColumnSizing(true);
        }
    });

    var return_data = [];

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
    
    var return_row_element = [];
    var max_fare = 0;
    var min_fare = 0;
    var max_round = 0;
    var min_round = 0;
    var max_duration = 0;
    var min_duration = 0;
    
    var selectedAirlines = [];
    var selectedStops = [];
    var selectedSource = [];
    var selectedTicket = [];
    var selectedTime = [];

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

    function details (row_details_id){
        var temp_arr = row_details_id.split('-');
        var ind = temp_arr[1];
        return_row_element = [];

        if( return_data.results[ind].stops === 0 ){
            $('#popoverHiddenContent').html(
                '<div class="row center-align-text fl_row">'
                    +'<!-- first flight -->'
                    +'<div class="col-lg-6">'
                        +'<div class="row date-text center-align-text">Flight Number</div>'
                            +'<div class="row date-text">'+return_data.details[ind].rest.Segment.WSSegment.OperatingCarrier+' - '+return_data.details[ind].rest.Segment.WSSegment.Craft+'</div>'
                        +'</div>'
                    +'<div class="col-lg-12">'
                        +'<div class="row travel-text">'
                            +'<div class="col-lg-11 remove-padding" id="originPop">'+return_data.details[ind].rest.Segment.WSSegment.Origin.CityName+'<div class="row center-align-text time_text" id="from">'+return_data.results[ind].from+'</div></div>'
                            +'<span class="col-lg-2 gly-ctm glyphicon glyphicon-play"></span>'
                            +'<div class="col-lg-11 remove-padding" id="destinationPop">'+return_data.details[ind].rest.Segment.WSSegment.Destination.CityName+'<div class="row center-align-text time_text" id="to">'+return_data.results[ind].to+'</div></div>'
                        +'</div>'
                    +'</div>'
                    +'<div class="col-lg-6">'
                        +'<div class="row dur-text">'+return_data.flight_info[ind].arr_date+'</div>'
                        +'<div class="row dur-text center-align-text">'+return_data.results[ind].duration+'</div>'
                    +'</div>'
                +'</div>'
            );
            $('#popoverHiddenTitle').html(
                '<div class="row">'
                    +'<div class="col-lg-12 pull-left">'
                        +'<div class="col-lg-4">'
                            +'<img src="<?php echo base_url("img/AirlineLogo/");?>/'+return_data.results[ind].airline_code+'.gif" onError="this.src="<?php echo base_url("img/flightIcon.png");?>" alt="jA" width="30px" />'
                        +'</div>'
                        +'<div class="col-lg-offset-2 col-lg-18 pop-title-text">'+return_data.flight_info[ind].name_of_airline+'</div>'
                    +'</div>'
                    +'<div class="col-lg-12 pull-right">'
                        +'<div class="col-lg-4 img-cal">'
                            +'<img src="<?php echo base_url("img/calendar_icon.png");?>" alt="jA" width="18px" />'
                        +'</div>'
                        +'<div class="col-lg-18 pop-title-text">'+return_data.flight_info[ind].arr_date+'</div>'
                    +'</div>'
                +'</div>'
            );
        }else{
            return_row_element[0] = 
                '<div class="row center-align-text fl_row">'
                    +'<!-- first flight -->'
                    +'<div class="col-lg-8">'
                        +'<div class="row date-text center-align-text">Flight Number</div>'
                            +'<div class="row date-text">'+return_data.details[ind].rest.Segment.WSSegment[0].OperatingCarrier+' - '+return_data.details[ind].rest.Segment.WSSegment[0].Craft+'</div>'
                        +'</div>'
                    +'<div class="col-lg-8">'
                        +'<div class="row travel-text">'
                            +'<div class="col-lg-11 remove-padding" id="originPop">'+return_data.details[ind].rest.Segment.WSSegment[0].Origin.CityCode+'<div class="row center-align-text time_text" id="from">'+return_data.results[ind].multi[0].from+'</div></div>'
                            +'<span class="col-lg-2 gly-ctm glyphicon glyphicon-play"></span>'
                            +'<div class="col-lg-11 remove-padding" id="destinationPop">'+return_data.details[ind].rest.Segment.WSSegment[0].Destination.CityCode+'<div class="row center-align-text time_text" id="to">'+return_data.results[ind].multi[0].to+'</div></div>'
                        +'</div>'
                    +'</div>'
                    +'<div class="col-lg-7">'
                        +'<div class="row dur-text">'+return_data.multi_flight_info[ind][0].arr_date+'</div>'
                        +'<div class="row dur-text center-align-text">'+return_data.results[ind].multi[0].duration+'</div>'
                    +'</div>'
                +'</div>';
            for( var i = 1 ; i <= return_data.results[ind].stops ; i++ ){
                return_row_element[i] = 
                '<div class="row center-align-text margin-center">'
                    +'<!-- layover -->'
                    +'<div class="col-lg-6 remove-padding grey-line"></div>'
                    +'<div class="col-lg-12 remove-padding"> Layover - '+ return_data.layover[ind][i-1]+' </div>'
                    +'<div class="col-lg-6 remove-padding grey-line"></div>'
                +'</div>'
                +'<div class="row center-align-text fl_row">'
                    +'<!-- nth flight -->'
                    +'<div class="col-lg-8">'
                        +'<div class="row date-text center-align-text">Flight Number</div>'
                            +'<div class="row date-text">'+return_data.details[ind].rest.Segment.WSSegment[i].OperatingCarrier+' - '+return_data.details[ind].rest.Segment.WSSegment[i].Craft+'</div>'
                        +'</div>'
                    +'<div class="col-lg-8">'
                        +'<div class="row travel-text">'
                            +'<div class="col-lg-11 remove-padding" id="originPop">'+return_data.details[ind].rest.Segment.WSSegment[i].Origin.CityCode+'<div class="row center-align-text time_text" id="from">'+return_data.results[ind].multi[i].from+'</div></div>'
                            +'<span class="col-lg-2 gly-ctm glyphicon glyphicon-play"></span>'
                            +'<div class="col-lg-11 remove-padding" id="destinationPop">'+return_data.details[ind].rest.Segment.WSSegment[i].Destination.CityCode+'<div class="row center-align-text time_text" id="to">'+return_data.results[ind].multi[i].to+'</div></div>'
                        +'</div>'
                    +'</div>'
                    +'<div class="col-lg-7">'
                        +'<div class="row dur-text">'+return_data.multi_flight_info[ind][i].arr_date+'</div>'
                        +'<div class="row dur-text center-align-text">'+return_data.results[ind].multi[i].duration+'</div>'
                    +'</div>'
                +'</div>';
            }

            var return_element = '';
            for (var j = 0 ; j < return_row_element.length ; j++) {
                return_element += return_row_element[j];
            };
            $('#popoverHiddenContent').html(return_element);

            $('#popoverHiddenTitle').html(
                '<div class="row">'
                    +'<div class="col-lg-12 pull-left">'
                        +'<div class="col-lg-4">'
                            +'<img src="<?php echo base_url("img/AirlineLogo/");?>/'+return_data.details[ind].rest.Segment.WSSegment[0].Airline.AirlineCode+'.gif" onError="this.src="<?php echo base_url("img/flightIcon.png");?>" alt="jA" width="30px" />'
                        +'</div>'
                        +'<div class="col-lg-offset-2 col-lg-18 pop-title-text">'+return_data.multi_flight_info[ind][0].name_of_airline+'</div>'
                    +'</div>'
                    +'<div class="col-lg-12 pull-right">'
                        +'<div class="col-lg-4 img-cal">'
                            +'<img src="<?php echo base_url("img/calendar_icon.png");?>" alt="jA" width="18px" />'
                        +'</div>'
                        +'<div class="col-lg-18 pop-title-text">'+return_data.multi_flight_info[ind][0].arr_date+'</div>'
                    +'</div>'
                +'</div>'
            );
        }
    }

    function format (d, row_details_id) {

        if( typeof row_details_id === 'undefined' ){
            return "<div></div>";
        }

        var temp_arr = row_details_id.split('-');
        var ind = temp_arr[1];
        return_row_element = [];

        if(return_data.results[ind].stops == 0){
            return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'
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
                        +'</div>'+
                    '</table>';
            }else{
                return_row_element[0] = 
                    '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'
                        +'<div class="row">'
                            +'<div class="col-lg-16">'
                                +'<div class="row">'
                                    +'<div class="col-lg-6 std-lineheight left-text">'
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
                    +'</div>'+
                '</table>';

            var return_element = '';
            for (var j = 0 ; j < return_row_element.length ; j++) {
                return_element += return_row_element[j];
            };
            return return_element;
        }
    }

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 7;
            var iMin = rangeSlider.slider("values", 0);
            var iMax = rangeSlider.slider("values", 1);
             
            var iVersion = aData[iColumn] == "-" ? 0 : aData[iColumn]*1;
            if ( iMin == "" && iMax == "" )
            {
                return true;
            }
            else if ( iMin == "" && iVersion <= iMax )
            {
                return true;
            }
            else if ( iMin <= iVersion && "" == iMax )
            {
                return true;
            }
            else if ( iMin <= iVersion && iVersion <= iMax )
            {
                return true;
            }
            return false;
        }
    ); 

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 3;
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
            var iColumn = 9;
             
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
            var iColumn = 2;
             
            var iVersion = aData[iColumn];
                if(iVersion == selectedSource){
                    return true;
                }
                else{
                    return false;
                }
        }
    );

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 10;
             
            var iVersion = aData[iColumn];

            for (i = 0; i < selectedTicket.length; i++) { 
                if(iVersion == selectedTicket[i]){
                    return true;
                }
            }
            return false;

        }
    ); 

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 3;
             
            var iVersion = aData[iColumn];

            for (i = 0; i < selectedTime.length; i++) { 
                if(iVersion > selectedTime[i]){
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
           values: [ min_duration, max_duration ],
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