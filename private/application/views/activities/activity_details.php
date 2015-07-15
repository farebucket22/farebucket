<div class="wrap">
    <div class="container-fluid clear-top">    
        <div class="row resultsSearchContainer navbar-fixed-top">
        <?php
            $this->load->view("common/search.php");
        ?>
        </div>
    </div>
    <div class="container-fluid main clear-top">
        <div class="row detailsAndBookingArea">
            <?php
            foreach ($activity_details as $ad){
                $imgPath[] = 'img/activities/'.$ad->file_name;
                $activityName = $ad->activity_name;
                $activityLocationName = $ad->activity_location_name;
                $activityAvgRating = $ad->activity_rating_average_value;
                $activityOverview = $ad->activity_description;
                $activityDetails = $ad->activity_details;
                $activityLocationLat = $ad->activity_location_lat;
                $activityLocationLong = $ad->activity_location_long;
            }

            $count=0;
            foreach ($activity_sub_type_details as $astd){
                $activitySubTypeName[] = $astd->activity_sub_type_name;
                $activitySubTypeId[] = $astd->activity_sub_type_id;
                $activitySubTypeDescription[] = $astd->activity_sub_type_description;
                $activityAdultPrice[] = $astd->activity_sub_type_adult_price;
                $activityMaxTickets[] = $astd->activity_sub_type_max_tickets;
                $activityChildStatus[] = $astd->activity_sub_type_kid_status;
                if($activityChildStatus[$count]==="on"){
                    $activityChildPrice[] = $astd->activity_sub_type_kid_price;
                } else{
                    $activityChildPrice[] = null;
                }
                $count++;
            }
            
            //print_r($activity_leave_details);die;
            if(!empty($activity_leave_details)){
                $activityLeaves = $activity_leave_details[0]->activity_leave_dates;
            } else{
                $activityLeaves = "";
            }
            ?>
            <div class="col-xs-24 col-sm-16 col-sm-offset-1 activityDetails">
                <div class="row">
                    <div class="col-xs-24 col-sm-12 activityDetailsImagesArea">
                        <div class="row">
                            <div class="col-xs-24 activityDetailsImage" style="background-image:url('<?php echo base_url($imgPath[0]); ?>')"></div>
                        </div>
                        
                    </div>
                    <div class="col-xs-24 col-sm-11 col-sm-offset-1 activityDetailsImagesArea">
                        <div class="row activityDetailsName"><h2><?php echo $activityName; ?></h2></div>
                        <div class="row activityDetailsLocationName"><h4><?php echo $activityLocationName; ?></h4></div>
                        <div class="row activityDetailsAvgRating">
                            Rating:
                            <ul class="avgRatingsList">
                                <?php
                                for($i=0;$i<$activityAvgRating;$i++){
                                    echo '<li><span class="glyphicon glyphicon-star"></span></li>';
                                }
                                for($i=0;$i<(5-$activityAvgRating);$i++){
                                    echo '<li><span class="glyphicon glyphicon-star-empty"></span></li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="row activityDetailsThumbsArea">
                            Images:
                            <div class="col-xs-24 activityDetailsThumbs">
                                <div class="row">
                                    <?php
                                    for($i=0;$i<sizeof($imgPath);$i++){
                                        echo '<div class="col-xs-4 activityDetailsThumb" id="thumb'.$i.'" style="background-image:url('.base_url($imgPath[$i]).')"></div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-24 col-sm-5 col-sm-offset-1 bookingDetails">
                <form class="row bookingOptionsForm" method="POST" action="save_booking_details">
                    <?php
                    for($i=0;$i<sizeof($activityAdultPrice);$i++){
                        echo '<input type="hidden" class="adultPrice-'.$i.'" name="adultPrice" value="'.$activityAdultPrice[$i].'" />';
                        echo '<input type="hidden" class="childPrice-'.$i.'" name="adultPrice" value="'.$activityChildPrice[$i].'" />';
                    }
                    ?>
                    <div class="col-xs-24">
                        <select class="activitySubTypeSelect bookingFormField" name="activitySubTypeSelect" id="not_selected">
                            <option value="Please Select from options">Please Select from options</option>
                            <?php
                            for($i=0;$i<sizeof($activitySubTypeName);$i++){
                                echo '<option value="'.($activitySubTypeId[$i]).'">'.$activitySubTypeName[$i].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <blockquote class="col-xs-22 col-xs-offset-1 activitySubTypeDescription bookingFormField" >Activity Sub-Type Description</blockquote>
                    <div class="col-xs-24">
                        <input type="text" class="col-xs-24 bookingDate bookingFormField" name="bookingDate" id="datepicker" disabled='true' placeholder="BOOKING DATE">
                    </div>
                    <div class="col-xs-24">
                        <select class="adultCountSelect bookingFormField" name="adultCountSelect">
                            <option value="Adults">No. Of Adults</option>
                        </select>
                    </div>
                    <div class="col-xs-24">
                        <select class="kidCountSelect bookingFormField" name="kidCountSelect">
                            <option value="kids">No. of Kids</option>
                        </select>
                    </div>
                    <div class="col-xs-22 col-xs-offset-1 loadingGif bookingFormField" style="display: none;"></div>
                    <div class="row"><div class="col-xs-22 col-xs-offset-1 ticketsRemaining"></div></div>
                    <div class="col-xs-22 col-xs-offset-1 bookingErrorMessage"></div>
                    <ul class="col-xs-22 col-xs-offset-1 bookingAmountContainer">                        
                        <li>&#x20B9;</li>
                        <li class="bookingAmount">0</li>                        
                    </ul>
                    <input type="hidden" name="bookingAmount" class="bookingAmountHidden" />
                    <input type="hidden" name="activityName" class="activityName" value="<?php echo $activityName; ?>" />
                    <input type="hidden" name="activityLocationName" class="activityLocaionName" value="<?php echo $activityLocationName; ?>" />
                    <input type="hidden" name="activityAvgRating" class="activityAvgRating" value="<?php echo $activityAvgRating; ?>" />
                    <button type="submit" class="col-xs-24 bookingBtn bookingFormField"><span class="glyphicon glyphicon-circle-arrow-right"></span>&nbsp;Book</button>
                </form>
            </div>
        </div>
        <div class="row tabsWeatherContainer">
            <div class="col-xs-24 col-sm-17 col-sm-offset-1 activityTabsContainer">
                <div class="row">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#overview" role="tab" data-toggle="tab">Overview</a></li>
                        <li><a href="#details" role="tab" data-toggle="tab">Details</a></li>
                        <li><a href="#reviews" role="tab" data-toggle="tab">Reviews</a></li>
                        <li><a href="#map" id="map_link" role="tab" data-toggle="tab">Map</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="overview"><div class="activityOverviewArea"><?php echo $activityOverview; ?></div></div>
                        <div class="tab-pane" id="details"><div class="activityDetailsArea"><?php echo $activityDetails; ?></div></div>
                        <div class="tab-pane" id="reviews">
                            <div class="row reviewsBtnInfo">
                            <?php
                            if($_SESSION['login_status']===0){
                                echo '<div class="col-xs-24 col-sm-24 notLoggedInReviewsText">Please Login to submit a review</div>';
                            } else{
                                echo '<button class="col-xs-24 col-sm-24 addReviewsBtn"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;Add Review</button>';
                            }
                            ?>
                            </div>
                            <div class="row viewReviewArea">
                                <?php
                                foreach($activity_ratings_user_details as $rating){
                                    $activityRating = $rating->activity_rating_value;
                                ?>
                                    <div class="col-xs-22 col-xs-offset-1 reviewsList">
                                        <div class="row">
                                            <div class="col-xs-8 col-sm-4 nameRatingContainer">
                                                <div class="row">
                                                    <div class="col-xs-24 userReviewName"><?php echo $rating->user_first_name; ?></div>
                                                </div>
                                                <div class="row">
                                                    <ul class="userReviewsRatingsList col-xs-24">
                                                    <?php
                                                    for($i=0;$i<$activityRating;$i++){
                                                        echo '<li><span class="glyphicon glyphicon-star"></span></li>';
                                                    }
                                                    for($i=0;$i<(5-$activityRating);$i++){
                                                        echo '<li><span class="glyphicon glyphicon-star-empty"></span></li>';
                                                    }
                                                    ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-xs-16 col-sm-20"><?php echo $rating->activity_individual_comment; ?></div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="submitReviewArea" style="display:none">
                                <button class="col-xs-24 col-sm-24 viewReviewsBtn"><span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp;View Reviews</button>
                                <form class="col-xs-22 col-xs-offset-1 submitRatingForm" method="POST" action="submit_review">
                                    <div class="row">
                                        <select name="reviewRating" class="ratingSelect col-xs-8 col-sm-5">
                                            <option value="0">Select Rating...</option>
                                            <option value="1">1 star</option>
                                            <option value="2">2 stars</option>
                                            <option value="3">3 stars</option>
                                            <option value="4">4 stars</option>
                                            <option value="5">5 stars</option>
                                        </select>
                                        <ul class="reviewRatingsList col-xs-14 col-xs-offset-1">
                                            <li><span id="star0" class="glyphicon glyphicon-star-empty"></span></li>
                                            <li><span id="star1" class="glyphicon glyphicon-star-empty"></span></li>
                                            <li><span id="star2" class="glyphicon glyphicon-star-empty"></span></li>
                                            <li><span id="star3" class="glyphicon glyphicon-star-empty"></span></li>
                                            <li><span id="star4" class="glyphicon glyphicon-star-empty"></span></li>
                                        </ul>
                                    </div>
                                    <div class="row"><textarea class="col-xs-22 col-sm-14 commentArea" name="reviewComment" placeholder="Leave a Comment"></textarea></div>
                                    <div class="row"><button class="col-xs-22 col-sm-6 submitReviewBtn" type="submit"><span class="glyphicon glyphicon glyphicon-ok-circle"></span>&nbsp;Submit</button></div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="map"><div id="map-canvas"></div></div>
                    </div>
                </div>
            </div>
            
            <div class="col-xs-24 col-sm-4 col-sm-offset-1 searchResultsWeatherDetails">
                <div class="weatherDetails row center-align-text">
                    <div class="col-xs-24 weather_spinner" style="display:none;">
                        <div id="spinner" class="spinner">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                            <div class="rect4"></div>
                            <div class="rect5"></div>
                        </div>
                    </div>
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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    var adultSelected = 0;
    $(document).ready(function(){
        /*****autoNumeric*****/
        $('li.bookingAmount').autoNumeric({
            aSep: ',',
            dGroup: 2
        });
        /*****autoNumeric end*****/

        /***select or die*******/

        $('.activitySubTypeSelect').selectOrDie({
            placeholderOption: true,
            customClass: 'activitySubTypeSod',
            onChange: function(){
                index = this.selectedIndex;
                this.id = '';
                $('.bookingDate').prop('disabled', false);
                $(".activitySubTypeSod").css('border-color', '#27ae60');
            }
        });

        $('.kidCountSelect').selectOrDie({
            placeholderOption: true,
            customClass: 'kidCountSelectOrDie',
            size: 5,
        });

        $('.adultCountSelect').selectOrDie({
            placeholderOption: true,
            customClass: 'adultCountSelectOrDie',
            size: 5,
            onChange: function(){
                $(".adultCountSelectOrDie").css('border-color', '#27ae60');
            }
        });
        /***select or die end*******/

        var childHideable = 0;

        $(".citySelect").removeAttr('disabled');
        
        $(".activityDetailsThumb:first").addClass("activeThumb");
        
        $(".viewReviewArea").mCustomScrollbar({
            theme:"inset-2-dark"
        });
        
        $(".addReviewsBtn").click(function(){
            $(this).fadeOut("fast");
            $(".viewReviewArea").fadeOut("fast", function(){
                $(".submitReviewArea").fadeIn("fast"); 
            });
        });
        
        $(".viewReviewsBtn").click(function(){
            $(".submitReviewArea").fadeOut("fast", function(){
                $(".viewReviewArea").fadeIn("fast");
                $(".addReviewsBtn").fadeIn("fast");
            });
        });
        
        $(".bookingBtn").click(function(e){
            e.preventDefault();

            if($(".adultCountSelect").val()==="0" || $(".bookingDate").val()==="" || $(".activitySubTypeSelect").val()==="0" || adultSelected === 0 ){

                if( $(".adultCountSelect").val() === "0" || adultSelected === 0 ){
                    $(".adultCountSelectOrDie").css('border-color', '#f00');
                    $(".bookingErrorMessage").append("<small class='text-small'>Please do not leave 'No. of Adults' field empty.</small><br />");
                }

                if( $(".bookingDate").val() === "" ){
                    $(".bookingDate").css('border-color', '#f00');
                    $(".bookingErrorMessage").append("<small class='text-small'>Please do not leave 'Booking Date' field empty.</small><br />");
                }

                if( $(".activitySubTypeSelect").attr('id') === "not_selected" ){
                    $(".activitySubTypeSod").css('border-color', '#f00');
                    $(".bookingErrorMessage").append("<small class='text-small'>Please do not leave 'Activity Sub-Type' field empty.</small><br />");
                }

            } else{
                $(".bookingOptionsForm").submit();
            }
        });
        
        $(".bookingFormField").focus(function(){
            $(".bookingErrorMessage").html("");
        });

        $(".ratingSelect").change(function(){
            var rating = $(this).val();
            for(var i=0; i<=parseInt(rating); i++){
                $("#star"+i).removeClass("glyphicon-star-empty");
                $("#star"+i).addClass("glyphicon-star");
            }
            for(var i=parseInt(rating); i<5; i++){
                $("#star"+i).removeClass("glyphicon-star");
                $("#star"+i).addClass("glyphicon-star-empty");
            }
        });

        $(".submitReviewBtn").click(function(e){
        	e.preventDefault();
        	alert("Thank you for submitting your review");
        	$(".submitRatingForm").submit();
        });

        /***** Global declaration of max tickets for an activity sub-type, amount related and sub-type selected index variables *****/
        var maxTickets = <?php echo json_encode($activityMaxTickets); ?>;
        var adultCount = 0;
        var kidCount = 0;
        var total = 0;
        var adultPrice = "";
        var childPrice = "";
        var index;
        var childStatus = <?php echo json_encode($activityChildStatus); ?>;
        var remTcktsAdult = 0;
        var remTcktsChild = 0;
        var remTcktsAdultTemp = 0;
        var remTcktsChildTemp = 0;


        /***** Function to reset the amount related variables *****/
        function resetAmount(){
            adultTotal=0;
            kidTotal=0;
            adultCount=0;
            kidCount=0;
            total=0;
            adultPrice="";
            childPrice="";
            $(".bookingAmountContainer li.bookingAmount").text("0");
            $(".bookingAmountHidden").val("0");
        }

        /***** Function to refresh the adult and child count selects *****/
        function refreshSelects(){
            $(".adultCountSelect").selectOrDie("disable");
            $(".kidCountSelect").selectOrDie("disable");
            $('.adultCountSelect')
                .find('option')
                .remove()
                .end()
                .val('0');
            $('.kidCountSelect')
                .find('option')
                .remove()
                .end()
                .val('0');
        }

        $.datepicker.setDefaults({
            dateFormat: "dd-mm-yy"
        });

        $( "#datepicker" ).datepicker({
            minDate: 1,
            beforeShowDay: checkBadDates,
            onSelect: function (dateText, inst) {

                $(".bookingDate").css('border-color', '#27ae60');

                $('.weather_spinner').show();

                var searchedDate = $('#datepicker').datepicker('getDate');
                var currentDate = new Date();
                var isPredictableWeather = 0;

                if( searchedDate.getDate() >= (currentDate.getDate() + 7) ){
                    isPredictableWeather = 0;
                    var year = searchedDate.getFullYear();
                    searchedDate.setFullYear(year - 1);
                }else{
                    isPredictableWeather = 1;
                }

                var required_date = searchedDate.getDate() + '-' + searchedDate.getMonth() + '-' + searchedDate.getFullYear();
                searchedDate.setFullYear(year);
                var dt = searchedDate.toString();
                var display_date = dt.substr(8, 2) + " " + (dt.substr(4, 3)) + " " + dt.substr(11, 4);  
                var country = ($('span.countrySelect .sod_list .selected').html());
                var countrycode = country;
                var city = ($('.citySelect option:selected').html());
                var city_name = city;
                var city_lat;
                var city_long;

                var geocoder =  new google.maps.Geocoder();
                geocoder.geocode( { 'address': city_name}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        city_lat = results[0].geometry.location.lat();
                        city_long = results[0].geometry.location.lng(); 
                        $.ajax({
                            type: "POST",
                            url : "<?php echo site_url('weather');?>",
                            data : { choosen_date: required_date, city_lat : city_lat , city_long : city_long, is_predictable_weather: isPredictableWeather}
                        })
                        .done(function(weatherdata){
                            $('.weather_spinner').hide();
                            var wData = $.parseJSON(weatherdata);
                            if( wData.isHistoric ){
                                // historic weather
                                $('.weatherDetails .date').html('<div class="title">Historical Weather for:</div><span>'+display_date+'</span>');
                                $('.weatherDetails .icon').html(getWeatherIcon(wData));
                                $('.weatherDetails .summary').html(wData.summary);
                                $('.weatherDetails .temperature').html(' <div class="row actual"> <div class="col-xs-12"> <div class="title">Min</div><span class="h4 min">'+wData.minTemperature +'&deg;C</span> </div><div class="col-xs-12"> <div class="title">Max</div><span class="h4 max">'+wData.maxTemperature +'&deg;C</span> </div></div>');
                            }else{
                                // current weather
                                $('.weatherDetails .date').html('<div class="title">Weather for:</div><span>'+display_date+'</span>');
                                $('.weatherDetails .icon').html(getWeatherIcon(wData));
                                $('.weatherDetails .summary').html(wData.summary);
                                $('.weatherDetails .temperature').html(' <div class="row actual"> <div class="col-xs-24"> <div class="title">Temperature</div> <span class="h4 min">'+wData.temperature +'&deg;C</span> </div>');
                            }
                        });
                    } else {
                        alert("Something went wrong:" + status);
                    }
                }); 

                $(".bookingErrorMessage").html("");
                $(".bookingErrorMessage").hide();
                $(".loadingGif").show();

                /***** Refresh the count select boxes when date is changed *****/
                refreshSelects();
                /***** Reset the amount related variables when date is changed *****/
                resetAmount();

                adultPrice = $(".adultPrice-"+index).val();
                childPrice = $(".childPrice-"+index).val();

                var activitySubTypeId = $(".activitySubTypeSelect option:selected").val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('activity/get_activity_sub_type_booking') ?>",
                    data: { bookingDate: dateText, activitySubTypeId: activitySubTypeId }
                })
                    .done(function( data ) {
                        $(".loadingGif").hide();
                        $(".bookingErrorMessage").show();
                        var tempArr = data.split("-");
                        var adultCount = tempArr[0];
                        var childCount = tempArr[1];
                        var adultSelectOptionsString = '<option value="Adults">No. Of Adults</option>';
                        var childSelectOptionsString = '<option value="Kids">No. Of Kids</option>';
                        var j=0;
                        for(var i=0;i<(maxTickets[index-1]-adultCount);i++){
                            j=i+1;
                            if(j===1){
                                var adultOptionAppend = " Adult - Rs."+adultPrice+"/adult";
                            } else {
                                var adultOptionAppend = " Adults - Rs."+adultPrice+"/adult";
                            }
                            adultSelectOptionsString += '<option value="'+j+'">'+j+adultOptionAppend+'</option>';
                        }
                        for(var i=0;i<(maxTickets[index-1]-childCount);i++){
                            j=i+1;
                            if(j===1){
                                var childOptionAppend = " Child - Rs."+childPrice+"/child";
                            } else {
                                var childOptionAppend = " Children - Rs."+childPrice+"/child";
                            }
                            childSelectOptionsString += '<option value="'+j+'">'+j+childOptionAppend+'</option>';
                        }

                        remTcktsAdult = maxTickets[index-1]-adultCount;
                        remTcktsChild = maxTickets[index-1]-childCount;
                        remTcktsAdultTemp = remTcktsAdult;
                        remTcktsChildTemp = remTcktsChild;

                        $('.remTckts').show();

                        $(".adultCountSelect").append(adultSelectOptionsString);
                        $(".kidCountSelect").append(childSelectOptionsString);

                        $('.kidCountSelect').selectOrDie('enable');
                        $('.adultCountSelect').selectOrDie('enable');

                        $('.kidCountSelect').selectOrDie('destroy');
                        $('.adultCountSelect').selectOrDie('destroy');
                        $('.kidCountSelect').selectOrDie({
                            placeholderOption: true,
                            customClass: 'kidCountSelectOrDie',
                            size: 5,
                            onChange: function(){
                                kidCount = parseInt($(this).val());
                                remTcktsChildTemp = remTcktsChild - kidCount;
                                total = (adultPrice*adultCount)+(childPrice*kidCount);
                                if( childHideable === 0 ){
                                    $('.ticketsRemaining').html( '<div class="remTckts"><div>*'+remTcktsAdultTemp+' Adult tickets remain</div><div id="childHideable">*'+remTcktsChildTemp+' Child tickets remain</div></div>' );
                                } else {
                                    $('.ticketsRemaining').html( '<div class="remTckts"><div>*'+remTcktsAdultTemp+' Adult tickets remain</div></div>' );
                                }
                                $('.bookingAmountContainer li.bookingAmount').autoNumeric('set', total);
                                // $(".bookingAmountContainer li.bookingAmount").html(parseFloat(total).toFixed(2));
                                $(".bookingAmountHidden").val(parseFloat(total).toFixed(2));
                            }
                        });
                        $('.adultCountSelect').selectOrDie({
                            placeholderOption: true,
                            customClass: 'adultCountSelectOrDie',
                            size: 5,
                            onChange: function(){
                                adultSelected = 1;
                                adultCount = parseInt($(this).val());
                                remTcktsAdultTemp = remTcktsAdult - adultCount;
                                total = (adultPrice*adultCount)+(childPrice*kidCount);
                                if( childHideable === 0 ){
                                    $('.ticketsRemaining').html( '<div class="remTckts"><div>*'+remTcktsAdultTemp+' Adult tickets remain</div><div id="childHideable">*'+remTcktsChildTemp+' Child tickets remain</div></div>' );
                                } else {
                                    $('.ticketsRemaining').html( '<div class="remTckts"><div>*'+remTcktsAdultTemp+' Adult tickets remain</div></div>' );
                                }
                                $('.bookingAmountContainer li.bookingAmount').autoNumeric('set', total);
                                // $(".bookingAmountContainer li.bookingAmount").html(parseFloat(total).toFixed(2));
                                $(".bookingAmountHidden").val(parseFloat(total).toFixed(2));
                                $(".adultCountSelectOrDie").css('border-color', '#27ae60');
                            }
                        });

                        if(childStatus[index]==="on"){
                            $(".kidCountSelect").selectOrDie("enable");
                            if( childHideable != 0 ){
                                childHideable--;
                            }
                        }else{
                            $(".kidCountSelect").selectOrDie("disable");
                            childHideable++;
                        }

                        if( childHideable === 0 ){
                            $('.ticketsRemaining').html( '<div class="remTckts"><div>*'+remTcktsAdult+' Adult tickets remain</div><div id="childHideable">*'+remTcktsChild+' Child tickets remain</div></div>' );
                        } else {
                            $('.ticketsRemaining').html( '<div class="remTckts"><div>*'+remTcktsAdult+' Adult tickets remain</div></div>' );
                        }

                    });
            }
        });

        /***** Weather information for the corresponding dates *****/
        
        var temp = "<?php echo $activityLeaves; ?>";
        var $myBadDates = temp.split(",");
        //var $myBadDates = new Array("10","15");

        function checkBadDates(mydate){
        var $return=true;
        var $returnclass ="available";
        $checkdate = $.datepicker.formatDate('dd', mydate);
        for(var i = 0; i < $myBadDates.length; i++)
            {    
               if($myBadDates[i] === $checkdate)
                  {
                $return = false;
                $returnclass= "unavailable";
                }
            }
        return [$return,$returnclass];
        }

        $(".activitySubTypeSelect").change(function(){
            $('.remTckts').hide();
            $('#datepicker').datepicker('setDate', null);
            if($(".activitySubTypeSelect option:selected").val()==="0"){
                /***** Refresh the count select boxes when activity sub-type is changed *****/
                refreshSelects();
                /***** Reset the amount related variables activity sub-type is changed *****/
                resetAmount();

                $(".kidCountSelect").selectOrDie("disable");
                $(".bookingDate").prop('disabled', true);
                $(".activitySubTypeDescription").val("");
            }else{
                /***** Refresh the count select boxes when activity sub-type is changed *****/
                refreshSelects();
                /***** Reset the amount related variables activity sub-type is changed *****/
                resetAmount();

                var activitySubTypeDescription = <?php echo json_encode($activitySubTypeDescription); ?>;
                $(".activitySubTypeDescription").html(activitySubTypeDescription[index]);
                $(".bookingDate").prop('disabled', false);
            }
        });

        $('#myTab a:last').tab('show');
    });
    
    var prevActiveThumb = $("#thumb0");
    var curActiveThumb = $("#thumb0");
    $(".activityDetailsThumb").click(function(){
        curActiveThumb = $(this);
        if(curActiveThumb !== prevActiveThumb){
            curActiveThumb.addClass("activeThumb");
            prevActiveThumb.removeClass("activeThumb");
            prevActiveThumb = curActiveThumb;
        }
        var bg = $(this).css('background-image');
        if($(".activityDetailsImage").css('background-image')!==bg){
            $(".activityDetailsImage").animate({opacity: 0}, 'fast', function() {
                $(this).css('background-image',bg)
                       .animate({opacity: 1});
           });
        }
        
    });
</script>
<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQqOagUmrpd6UhW7soovt_DACNduvAj_E">
</script>
<script type="text/javascript">
    $(function() {
        var lat = <?php echo $activityLocationLat; ?>;
        var long = <?php echo $activityLocationLong; ?>;
        
        var mapOptions = {
            center: new google.maps.LatLng(lat, long),
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map;

        $('#map_link').on('shown.bs.tab', function(e) {
            if( map === undefined) {
                map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
                var marker = new google.maps.Marker({
                    position:new google.maps.LatLng(lat, long),
                    map: map,
                });
            }
        });
    });

    //creates a weather icon
    function getWeatherIcon(wData){
        var weatherIcon = '';
        switch(wData.icon){
            case 'clear-day' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/clear.png" />';
            break;
            case 'clear-night' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/clear.png" />';
            break;
            case 'rain' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/rain.png" />';
            break;
            case 'snow' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/snow.png" />';
            break;
            case 'sleet' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/sleet.png" />';
            break;
            case 'wind' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/wind.png" />';
            break;
            case 'fog' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/fog.png" />';
            break;
            case 'cloudy' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/cloudy.png" />';
            break;
            case 'partly-cloudy-day' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/partly_cloudy.png" />';
            break;
            case 'partly-cloudy-night' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/partly_cloudy.png" />';
            break;
            default :
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../img/weather_icons/clear.png" />';
            break;
        }
        return weatherIcon;

    }
    //creates a weather icon end
</script>