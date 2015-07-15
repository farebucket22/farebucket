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

    function resetSlider() {
        var $slider = $("#slider-range");
        var $sliderDuration = $("#slider-range-duration");
        $slider.slider("values", 0, min_round);
        $slider.slider("values", 1, max_round);
        $sliderDuration.slider("values", 0, min_duration);
        $sliderDuration.slider("values", 1, max_duration);
    }

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 8;
             
            var iVersion = aData[iColumn];

            for (i = 0; i < selectedBusType.length; i++) { 
                if(iVersion == selectedBusType[i].trim()){
                    return true;
                }
            }
            return false;

        }
    );  

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 0;

            var iVersion = aData[iColumn];

            for (i = 0; i < selectedBuses.length; i++) { 
                if(iVersion == selectedBuses[i].trim()){
                    return true;
                }
            }
            return false;

        }
    ); 

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 10;
             
            var iVersion = aData[iColumn];

            for (i = 0; i < selectedSeats.length; i++) { 
                if(iVersion == selectedSeats[i].trim()){
                    return true;
                }
            }
            return false;

        }
    ); 

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 3;
            var iMin = rangeSliderDuration.slider("values", 0);
            var iMax = rangeSliderDuration.slider("values", 1);

            aData[iColumn] = parseInt(aData[iColumn]);
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
            else if ( iVersion > iMin && iVersion < iMax )
            {
                return true;
            }
            return false;
        }
    ); 

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iColumn = 9;
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

    function format (row_details_id) {
        var index = parseInt(row_details_id);
        var from_city = "<?php echo $_GET['source_city_name'];?>";
        var to_city = "<?php echo $_GET['destination_city_name'];?>";

        if( isNaN(index) ){
            return "<div></div>";
        }

        var base_fare_disp = (typeof return_data.availableTrips[index].fareDetails.baseFare === 'undefined') ? return_data.availableTrips[index].fareDetails[0].baseFare : return_data.availableTrips[index].fareDetails.baseFare;
        var service_tax_absolute_disp = (typeof return_data.availableTrips[index].fareDetails.serviceTaxAbsolute === 'undefined') ? return_data.availableTrips[index].fareDetails[0].serviceTaxAbsolute : return_data.availableTrips[index].fareDetails.serviceTaxAbsolute;
        var total_fare_disp = (typeof return_data.availableTrips[index].fareDetails.totalFare === 'undefined') ? return_data.availableTrips[index].fareDetails[0].totalFare : return_data.availableTrips[index].fareDetails.totalFare;

        return  '<div class="col-lg-24 details-row">'
                +'<div class="row busDetailHeaders">'
                    +'<div class="col-lg-4">Boarding Points</div>'
                    +'<div class="col-lg-4">Drop-Off Points</div>'
                    +'<div class="col-lg-11"><div class="col-lg-20">Cancellation Time</div><div class="col-lg-4">Cancellation Charge</div></div>'
                    +'<div class="col-lg-5">Fare Breakdown</div>'
                +'</div>'

                +'<div class="row">'
                    +'<div class="col-lg-4 bus-stop-pnts">'+boarding(index)+'</div>'
                    +'<div class="col-lg-4 bus-stop-pnts">'+dropping(index)+'</div>'
                    +'<div class="col-lg-11 bus-cancel-text">'+cancellation(index)+'</div>'
                    +'<div class="col-lg-5">'
                        +'<div class="row">'
                            +'<div class="col-xs-10 bus-stop-pnts left-text">Base Fare</div>'
                            +'<div class="col-xs-14 right-text base_fare">&#x20B9; <span class="strong-text">'+base_fare_disp+'</span></div>'
                        +'</div>'
                        +'<div class="row">'
                            +'<div class="col-xs-10 bus-stop-pnts left-text">Service Tax</div>'
                            +'<div class="col-xs-14 right-text service_fare">&#x20B9; <span class="strong-text">'+service_tax_absolute_disp+'</span></div>'
                        +'</div>'
                        +'<div class="row">'
                            +'<div class="col-xs-10 bus-stop-pnts left-text">Total Fare</div>'
                            +'<div class="col-xs-14 right-text total_fare_det">&#x20B9; <span class="strong-text">'+total_fare_disp+'</span></div>'
                        +'</div>'
                    +'</div>'
                +'</div>'
                +'</div>'
    }

    function boarding(index){

        var ret_str = "";
        if( typeof return_data.availableTrips[index].boardingTimes.length === 'undefined' ){
            var disp_time = parseInt(return_data.availableTrips[index].boardingTimes.time).convertToTime();
            ret_str += "<div class='row'><div class='col-lg-6'>"+disp_time+"</div><div class='col-lg-18 left-text wordBreak'>"+return_data.availableTrips[index].boardingTimes.location+"</div></div>"
        }else{
            $.each(return_data.availableTrips[index].boardingTimes, function(i, val){
                var disp_time = parseInt(val.time).convertToTime();
                ret_str += "<div class='row'><div class='col-lg-6'>"+disp_time+"</div><div class='col-lg-18 left-text wordBreak'>"+val.location+"</div></div>"
            });
        }
        return ret_str;
    }

    function dropping(index){
        var ret_str = "";

        if( typeof return_data.availableTrips[index].droppingTimes !== 'undefined' ){
            if( typeof return_data.availableTrips[index].droppingTimes.length === 'undefined' ){
                var temp_time = parseInt(return_data.availableTrips[index].droppingTimes.time) % 1440;
                var disp_time = temp_time.convertToTime();
                ret_str += "<div class='row'><div class='col-lg-6'>"+disp_time+"</div><div class='col-lg-18 left-text wordBreak'>"+return_data.availableTrips[index].droppingTimes.location+"</div></div>"
            }else{
                $.each(return_data.availableTrips[index].droppingTimes, function(i, val){
                    var temp_time = parseInt(val.time) % 1440;
                    var disp_time = temp_time.convertToTime();
                    ret_str += "<div class='row'><div class='col-lg-6'>"+disp_time+"</div><div class='col-lg-18 left-text wordBreak'>"+val.location+"</div></div>"
                });
            }
            return ret_str;
        }
    }

    function cancellation(index){
        var ret_str_can = "";
        var can_str = return_data.availableTrips[index].cancellationPolicy.split(';');
        var can_dep_time = parseInt(return_data.availableTrips[index].departureTime);
        var d = new Date(return_data.availableTrips[index].doj);
        var can_fare = parseInt(return_data.availableTrips[index].fares);
        var can_len = can_str.length;
        var rem_date = new Date(d);
        var rem_date_0 = new Date(d);
        var rem_date_1 = new Date(d);
        var rem_total = 0;
        var rem_time = 0;
        var rem_time_0 = 0;
        var rem_time_1 = 0;
        var mins_before = 0;
        var days_before = 0;

        var month = new Array();
        month[0] = "Jan";
        month[1] = "Feb";
        month[2] = "Mar";
        month[3] = "Apr";
        month[4] = "May";
        month[5] = "Jun";
        month[6] = "Jul";
        month[7] = "Aug";
        month[8] = "Sep";
        month[9] = "Oct";
        month[10] = "Nov";
        month[11] = "Dec";

        for( var i = 0 ; i < can_len ; i++ ){
            ret_str_can += '<div class="row">';
            if( i === 0 && can_str[i] === "0:-1:100:0" ){
                ret_str_can += '<div class="col-lg-20">No Refund</div><div class="col-lg-4 can_fare">&#x20B9; <span>0</span></div>'
            }else if( can_str[i] !== "" ){
                var info_arr = can_str[i].split(':');
                if( info_arr[1] === "-1" ){
                    mins_before = parseInt(info_arr[0])*60;
                    days_before = Math.floor(mins_before/1440);
                    rem_date.setDate(d.getDate() - days_before);
                    rem_time = can_dep_time - (mins_before % 1440);
                    ret_str_can += '<div class="col-lg-20">'+'Till <b>'+rem_time.convertToTime()+'</b> on <b>'+rem_date.getDate()+'-'+month[rem_date.getMonth()]+'-'+rem_date.getFullYear()+'</b></div>';
                }else{
                    mins_before = parseInt(info_arr[1])*60;
                    days_before = Math.floor(mins_before/1440);
                    rem_date_0.setDate(d.getDate() - days_before);
                    rem_time_0 = can_dep_time - (mins_before % 1440);
                    mins_before = parseInt(info_arr[0])*60;
                    days_before = Math.floor(mins_before/1440);
                    rem_date_1.setDate(d.getDate() - days_before);
                    rem_time_1 = can_dep_time - (mins_before % 1440);
                    if( info_arr[0] === "0" ){
                        ret_str_can += '<div class="col-lg-20">'+'After <b>'+rem_time_0.convertToTime()+'</b> on <b>'+rem_date_0.getDate()+'-'+month[rem_date_0.getMonth()]+'-'+rem_date_0.getFullYear()+'</b></div>';
                    }else{
                        ret_str_can += '<div class="col-lg-20">'+'between <b>'+rem_time_0.convertToTime()+'</b> on <b>'+rem_date_0.getDate()+'-'+month[rem_date_0.getMonth()]+'-'+rem_date_1.getFullYear()+'</b> - <b>'+ rem_time_1.convertToTime()+'</b> on <b>'+rem_date_1.getDate()+'-'+month[rem_date_1.getMonth()]+'-'+rem_date_1.getFullYear()+ '</b></div>';
                    }
                }
                //amount section
                if( !parseInt(info_arr[3]) ){
                    //percentage
                    var percentage = parseInt(info_arr[2])/100;
                    var total_fare =  parseInt(return_data.availableTrips[index].fares)
                    var per_total = percentage*total_fare;
                    rem_total = total_fare-per_total;
                    var cancellation_charge = total_fare - rem_total;

                    $('.can_fare_hidden').autoNumeric('set', cancellation_charge);
                    ret_str_can += '<div class="col-lg-4 can_fare">&#x20B9; <span class="strong-text">'+$('.can_fare_hidden').html()+'</span></div>';
                }else{
                    //amount
                    var a1 = parseInt(return_data.availableTrips[index].fares);
                    var a2 = parseInt(info_arr[2]);
                    rem_total = a1-a2;
                    var cancellation_charge = a1 - rem_total;

                    $('.can_fare_hidden').autoNumeric('set', cancellation_charge);
                    ret_str_can += '<div class="col-lg-4 can_fare">&#x20B9; <span class="strong-text">'+$('.can_fare_hidden').html()+'</span></div>';
                }
            }
            ret_str_can += '</div>';
            cancelPolicy[index] = (ret_str_can).toString();
        }
        return ret_str_can;
    }

    function flashTable(){
        $('#search').hide();
        $('#search').fadeIn(200);
    }