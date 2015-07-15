    var oTable = $('#search').DataTable({
        "bDeferRender": false,
        "aoColumnDefs": [
            { "aDataSort": [ 6, 7 ], "aTargets": [ 7 ] },
            { "targets": [ 0, 2, 6, 8, 9 ], "visible": false },
            { "targets": 10, "orderable": false }
        ],
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('id', 'row-'+iDataIndex);
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
                                    +'<div class="col-lg-12 right-text">'+'&#x20B9; '+parseFloat(return_data.fare_breakdown[ind].taxes).toFixed(2)+'</div>'
                                +'</div>'
                                +'<div class="row">'
                                    +'<div class="col-lg-12 left-text">Base Fare</div>'
                                    +'<div class="col-lg-12 right-text">'+'&#x20B9; '+parseFloat(return_data.fare_breakdown[ind].base_fare).toFixed(2)+'</div>'
                                +'</div>'
                                +'<div class="row">'
                                    +'<div class="col-lg-12 left-text">Total Fare</div>'
                                    +'<div class="col-lg-12 right-text">'+'&#x20B9; '+parseFloat(return_data.fare_breakdown[ind].tot_fare).toFixed(2)+'</div>'
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
                                +'<div class="col-lg-12 right-text">'+'&#x20B9; '+parseFloat(return_data.multi_fare_breakdown[ind].taxes).toFixed(2)+'</div>'
                            +'</div>'
                            +'<div class="row">'
                                +'<div class="col-lg-12 left-text">Base Fare</div>'
                                +'<div class="col-lg-12 right-text">'+'&#x20B9; '+parseFloat(return_data.multi_fare_breakdown[ind].base_fare).toFixed(2)+'</div>'
                            +'</div>'
                            +'<div class="row">'
                                +'<div class="col-lg-12 left-text">Total Fare</div>'
                                +'<div class="col-lg-12 right-text">'+'&#x20B9; '+parseFloat(return_data.multi_fare_breakdown[ind].tot_fare).toFixed(2)+'</div>'
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
            var iColumn = 6;
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