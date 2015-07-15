<?php
    $presentCityNameArr = explode(',', $_GET['typed-string-single']);
    $len = count($presentCityNameArr);
    $presentCityName = $presentCityNameArr[0].', '.$presentCityNameArr[$len - 1];
    $var1 = strtotime( $_GET['checkin_time'] );
    $var2 = strtotime( $_GET['checkout_time'] );
    $var3 = $var2 - $var1;
    $n = $var3/(60*60*24);
    $days = floor($n);
    $totalAdults = 0;
    $totalKids = 0;

    if( isset($_GET['single_rooms']) ){
        for( $i = 1 ; $i < intval($_GET['single_rooms'])+1 ; $i++ ){
            $totalAdults += intval( $_GET['adult_count_single-'.$i] );
            $totalKids += intval( $_GET['child_count_single-'.$i] );
        }        
    }
?>
<style>
	#overlay {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: #000;
		filter:alpha(opacity=50);
		-moz-opacity:0.5;
		-khtml-opacity: 0.5;
		opacity: 0.5;
		z-index: 10000;
		display: block;
	}
	#cancellation_policy{
		z-index:100000;
		font-size:20px;
	}
    .mix{
        display: none;
    }
	#total_fare{
		margin-bottom:30px;
		margin-left: 10px;
	}

    .img-disp{
        width: 300px;
        margin: 20px auto;
    }
    
    .img-disp img{
        width: auto;
        height: 300px;
    }

    #result-area .img-responsive{
        margin: 0 auto;
        height:178px;
    }
    .filtersLabel label{
        font-size: 12px;
    }
    .otherInfo{
        padding-left: 30px;
        font-size: 14px;
    }
    .info{
        font-size: 12px;
    }
    .hotelLocationName,.activityName{
        padding-left: 8px;
    }
    .hotelPrice{
        padding-right: 13px;
    }
	#hotel_details_modal{
		z-index:100000;
	}
	.errorBlockEmptyData{
		font-size:20px;
		font-weight:bold;
	}

</style>
<script type="text/javascript"> 

function stopRKey(evt) { 
    var evt = (evt) ? evt : ((event) ? event : null); 
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
    if ((evt.keyCode == 13)) {return false;} 
} 

document.onkeypress = stopRKey; 

</script>
<form action="<?php echo site_url('new_request/selected_hotel');?>" method="post" id="book_hotel_form" style="display:none;">
    <input type="text" name="typed-string-single" value="<?php echo $_GET['typed-string-single'];?>" style="display:none;"/>
    <input type="text" name="search-string-single" value="<?php echo $_GET['search-string-single'];?>" style="display:none;"/>
    <input type="text" name="city_name" value="<?php echo $_GET['city_name'];?>" style="display:none;"/>
    <input type="text" name="single_rooms" value="<?php echo $_GET['single_rooms'];?>" style="display:none;"/>
    <input type="text" name="adult_count_single-1" value="<?php echo $_GET['adult_count_single-1'];?>" style="display:none;"/>
    <input type="text" name="child_count_single-1" value="<?php echo $_GET['child_count_single-1'];?>" style="display:none;"/>
    <input type="text" name="adult_count_single-2" value="<?php echo $_GET['adult_count_single-2'];?>" style="display:none;"/>
    <input type="text" name="child_count_single-2" value="<?php echo $_GET['child_count_single-2'];?>" style="display:none;"/>
    <input type="text" name="adult_count_single-3" value="<?php echo $_GET['adult_count_single-3'];?>" style="display:none;"/>
    <input type="text" name="child_count_single-3" value="<?php echo $_GET['child_count_single-3'];?>" style="display:none;"/>
    <input type="text" name="adult_count_single-4" value="<?php echo $_GET['adult_count_single-4'];?>" style="display:none;"/>
    <input type="text" name="child_count_single-4" value="<?php echo $_GET['child_count_single-4'];?>" style="display:none;"/>
    <input type="text" name="checkin_time" value="<?php echo $_GET['checkin_time'];?>" style="display:none;"/>
    <input type="text" name="checkout_time" value="<?php echo $_GET['checkout_time'];?>" style="display:none;"/>
    <input type="text" name="flight_type" value="<?php echo $_GET['flight_type'];?>" style="display:none;"/>
    <input type="text" name="hotel_extra_info" id="hotel_extra_info_str" style="display:none;">
    <input type="text" name="trace_id" id="trace_id" style="display:none;">
    <input type="text" name="hotel_info" id="hotel_info_str" style="display:none;">
    <input type="text" name="room_details" id="room_hotel_info" value="somethin" style="display:none;">
    <input type="text" name="room_type" id="room_type" style="display:none;">
    <input type="text" name="cancelPolicy" id="cancelPolicy" style="display:none;">
    <input type="text" name="checkTime" id="checkTime" style="display:none;">
</form>
    
<div class="steps hide">
    <div id="step1"></div>
    <div id="step2"></div>
    <div id="step3"></div>
    <div id="step4"></div>
    <div id="step5"></div>
    <div id="step6"></div>
    <div id="step7"></div>
    <div id="step8"></div>
    <div id="step9"></div>
    <div id="step10"></div>
    <div id="step11"></div>
    <div id="step12"></div>
    <div id="step13"></div>
    <div id="step14"></div>
    <div id="step15"></div>
    <div id="step16"></div>
    <div id="room_price_disp"></div>
    <div id="room_price_per_night_disp"></div>
</div>

<!--modal screen-->

<div class="modal fade" id="hotel_details_modal">
    <div class="col-xs-24 hotels_spinner">
        <div id="spinner" class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

            </div>
            <div class="modal-body hotelInfoModalBody">
                <div class="container-fluid">
                    <div class="row">
                    <!-- tabs -->
                        <ul class="nav nav-tabs hotels-nav" role="tablist">
                            <li class="center-align-text active"><a href="#desc" id="desc_link" role="tab" data-toggle="tab">Description</a></li>
                            <li class="center-align-text "><a href="#pics" role="tab" id="pics_link" data-toggle="tab">Photos</a></li>
                            <li class="center-align-text "><a href="#map" id="map_link" role="tab" data-toggle="tab">Map</a></li>
                            <li class="center-align-text "><a href="#book" role="tab" id="book_link" data-toggle="tab">Book</a></li>
                        </ul>
                    <!-- tabs end -->
                    <!-- tab contents, insert within tab-content -->
                        <div class="tab-content hotels-content">
                            <div class="tab-pane fade in active" id="desc"></div>
                            <div class="tab-pane fade in active" id="pics">
                                <div class="row">
                                    <div class="fotorama images-container" data-width="100%" data-height="50%" data-auto="false" data-nav="thumbs"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade in active" id="map"><div id="map-canvas"></div></div>
                            <div class="tab-pane fade in active" id="book"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end modal screen-->
    <div class="container-fluid clear-top">    
        <div class="row resultsSearchContainer navbar-fixed-top">
            <div class="panel-group" id="accordionSearch">
                <div class="panel panel-default">
                    <div class="grey-bottom-separator center-align-text">
                        <span class="glyphicon glyphicon-user hulk-class"></span>
                        <?php if($totalAdults == 1):?>
                            <span><?php echo $totalAdults;?> Adult, </span>
                        <?php else:?>
                            <span><?php echo $totalAdults;?> Adults, </span>
                        <?php endif;?>
                        <?php if($totalKids == 1):?>
                            <span><?php echo $totalKids;?> Child</span>
                        <?php else:?>
                            <span><?php echo $totalKids;?> Children</span>
                        <?php endif;?>
                        <span>&nbsp;|</span>
                        <span class="glyphicon glyphicon-calendar hulk-class"></span>
                        <span class="TravelDate"></span>Check-In: <?php echo date('D, jS M Y', strtotime($_GET['checkin_time']));?>, Check-Out: <?php echo date('D, jS M Y', strtotime($_GET['checkout_time']));?>
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
                                    <div class="col-lg-5 fl_septr1 hulk-class">
                                        <div class="col-lg-2 fl_no">1</div>
                                        <div class="col-lg-3 hotel_bg_nav sr_only"></div>
                                        <div class="col-lg-15 fl_info">
                                            <div class="row">
                                                <div class="col-lg-offset-4 col-lg-18 travel-text">
                                                    <div class="col-lg-24 remove-padding center-align-text" id="presentCityName"><?php echo $presentCityName;?></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-17 col-lg-offset-5 center-align-text"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 pull-right hide">
                                            <div class="row fl_btn1 presentBtnGrp">
                                                <div class="link-btn-ed">
                                                    <a href="#" class='btn-ed' tabindex="0" id="edit">EDIT</a>
                                                </div>
                                                <div class="link-btn-de">
                                                    <a href="#" class='btn-de' tabindex="0" id='popover-toggle' data-trigger="focus" data-toggle="popover" data-placement="bottom">DETAILS</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div id="collapseSearch" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php $this->load->view('hotels/search_view.php');?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="wrap">
    <div class="container-fluid clear-top main clear-ov">
        <div class="row">
            <div class="col-xs-24 col-sm-4 col-sm-offset-cust-2 filterWeatherContainer" id="Filters">
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
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#collapsePrice">
                                                Price
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapsePrice" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <fieldset>
                                            <div class="checkbox">
                                              <label class="priceLabel-0">
                                                <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-0" value=".priceRange-0"/>
                                              </label>
                                            </div>
                                            <div class="checkbox">
                                              <label class="priceLabel-1">
                                                <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-1" value=".priceRange-1"/>
                                              </label>
                                            </div>
                                            <div class="checkbox">
                                              <label class="priceLabel-2">
                                                <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-2" value=".priceRange-2"/>
                                              </label>
                                            </div>
                                            <div class="checkbox">
                                              <label class="priceLabel-3">
                                                <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-3" value=".priceRange-3"/>
                                              </label>
                                            </div>
                                            <div class="checkbox">
                                              <label class="priceLabel-4">
                                                <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-4" value=".priceRange-4"/>
                                              </label>
                                            </div>
                                            <div class="checkbox">
                                              <label class="priceLabel-5">
                                                <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-5" value=".priceRange-5"/>
                                              </label>
                                            </div>
                                            <div class="checkbox">
                                              <label class="priceLabel-6">
                                                <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-6" value=".priceRange-6"/>
                                              </label>
                                            </div>
                                            <div class="checkbox">
                                              <label class="priceLabel-7">
                                                <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-7" value=".priceRange-7"/>
                                              </label>
                                            </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseStar">
                                                Hotel Star Rating
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseStar" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <fieldset>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox ratingFilter filter" data-rating="1" id="ratingFilter-0" value=".rating-0" />
                                                        No Rating
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox ratingFilter filter" data-rating="1" id="ratingFilter-0" value=".rating-1" />
                                                        <span class="glyphicon glyphicon-star green-star"></span>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox ratingFilter filter" data-rating="2" id="ratingFilter-1" value=".rating-2" />
                                                        <span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox ratingFilter filter" data-rating="3" id="ratingFilter-2" value=".rating-3" />
                                                        <span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox ratingFilter filter" data-rating="4" id="ratingFilter-3" value=".rating-4" />
                                                        <span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox ratingFilter filter" data-rating="5" id="ratingFilter-4" value=".rating-5" />
                                                        <span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span>
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-24 searchResultsWeatherDetails">
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
                            <div class="row">
                                <div class="col-xs-24 date"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-24 icon"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-24 temperature"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-24 summary h4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-24 col-sm-19 col-sm-offset-cust-2 resultsArea">
                <div class="errorBlockEmptyData" style="display:none;">
                    <center><h2>Sorry, No matching records found for your search</h2><span class="h4 mod_search_error" data-toggle="collapse" data-target="#collapseSearch">Modify Search</span> <span class="h4">|</span> <span class="h4 reset_search_error" onclick="javascript:location.href = '<?php echo site_url('hotels');?>'">Reset Search</span></center>
                </div>
                <div class="row sortBy sortByHotel">
                    <div class="col-xs-24 col-sm-8 col-sm-offset-16">
                        <h4 class="col-xs-6 sortByLabel">Sort By:</h4>
                        <div class="col-xs-18">
                            <select class="sortBySelect">
                                <option value="pricesort:asc">Price - Low to High</option>
                                <option value="pricesort:desc">Price - High to Low</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row hotel-results" id="Container">
                    <div class="errorBlock" style="display:none;">
                        <center><h2>Sorry, No matching records found for your search</h2><span class="h4 mod_search_error" data-toggle="collapse" data-target="#collapseSearch">Modify Search</span>  </center>
                    </div>
                    <div class="col-xs-24 col-sm-24 results" id="result-area">
                        <div id="flight_spin" class="spinner">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                            <div class="rect4"></div>
                            <div class="rect5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQqOagUmrpd6UhW7soovt_DACNduvAj_E&v=3.exp&libraries=places"></script>
<script>

var ext_arr = [];
var globalRoomDetails;
var cancellationPolicy;
var checkTime;

var checkboxFilter = {
  
    // Declare any variables we will need as properties of the object

    $filters: null,
    // $reset: null,
    groups: [],
    outputArray: [],
    outputString: '',
  
    // The "init" method will run on document ready and cache any jQuery objects we will need.
  
    init: function(){
        var self = this; // As a best practice, in each method we will asign "this" to the variable "self" so that it remains scope-agnostic. We will use it to refer to the parent "checkboxFilter" object so that we can share methods and properties between all parts of the object.

        self.$filters = $('#Filters');
        self.$container = $('#Container .results');

        self.$filters.find('fieldset').each(function(){
            self.groups.push({
                $inputs: $(this).find('input'),
                active: [],
                tracker: false
            });
        });

        self.bindHandlers();
        return true;
    },
  
  // The "bindHandlers" method will listen for whenever a form value changes. 
  
    bindHandlers: function(){
        var self = this;

        self.$filters.on('change', function(){
            self.parseFilters();
        });
    },
  
  // The parseFilters method checks which filters are active in each group:
  
  parseFilters: function(){
    var self = this;
    // loop through each filter group and add active filters to arrays
    for(var i = 0, group; group = self.groups[i]; i++){
        group.active = []; // reset arrays
        group.$inputs.each(function(){
            $(this).is(':checked') && group.active.push(this.value);
        });
        group.active.length && (group.tracker = 0);
    }
    
    self.concatenate();
  },
  
  // The "concatenate" method will crawl through each group, concatenating filters as desired:
  
  concatenate: function(){
    var self = this,
        cache = '',
        crawled = false,
        checkTrackers = function(){
        var done = 0;

        for(var i = 0, group; group = self.groups[i]; i++){
            (group.tracker === false) && done++;
        }

        return (done < self.groups.length);
        },
        crawl = function(){
            for(var i = 0, group; group = self.groups[i]; i++){
                group.active[group.tracker] && (cache += group.active[group.tracker]);

                if(i === self.groups.length - 1){
                    self.outputArray.push(cache);
                    cache = '';
                    updateTrackers();
                }
            }
        },
        updateTrackers = function(){
            for(var i = self.groups.length - 1; i > -1; i--){
                var group = self.groups[i];

                if(group.active[group.tracker + 1]){
                    group.tracker++; 
                break;
                } else if(i > 0){
                    group.tracker && (group.tracker = 0);
                } else {
                    crawled = true;
                }
            }
        };
    
    self.outputArray = []; // reset output array
    do{
        crawl();
    }
    while(!crawled && checkTrackers());

    self.outputString = self.outputArray.join();
    
    // If the output string is empty, show all rather than none:
    
    !self.outputString.length && (self.outputString = 'all'); 
    
    // console.log(self.outputString); 
    
    // ^ we can check the console here to take a look at the filter string that is produced
    
    // Send the output string to MixItUp via the 'filter' method:
    
        if(self.$container.mixItUp('isLoaded')){
            self.$container.mixItUp('filter', self.outputString);
        }
    }
};

(function(){

    var query = <?php echo json_encode($query);?>;
    var return_array = [];
    var hotel_index = 0;
    var selected_hotel = [];

    $('.sortBySelect').selectOrDie({
        onChange: function(){
            var sort_type = $(this).val();
            if( sort_type === 'pricesort:asc' ){
                $('#Container .results').mixItUp('sort', 'pricesort:asc');
            }else{
                $('#Container .results').mixItUp('sort', 'pricesort:desc');
            }
        }
    });

    $('#collapseKeyword').collapse('hide');
    $('#collapseStar').collapse('hide');
    $('#collapsePrice').collapse('hide');

//filters section end
    $.ajax({
        type: 'POST',
        url: '<?php echo site_url("new_request/citySearch");?>',
        data: query
    }).done(function(retData){
		if( retData == "No hotels found" ){
			$('.resultsArea .errorBlockEmptyData').show();
			$('.sortBy').hide();
            $('#collapseKeyword').hide();
            $('#collapseStar').hide();
            $('#collapsePrice').hide();
		}
        $( '#flight_spin' ).hide();

        $('#collapseKeyword').collapse('show');
        $('#collapseStar').collapse('show');
        $('#collapsePrice').collapse('show');

        retData = $.parseJSON( retData );
        if( typeof retData.flag != 'undefined' ){
            $('.resultsArea .errorBlockEmptyData').show();
            $('.sortBy').hide();
            $('#collapseKeyword').hide();
            $('#collapseStar').hide();
            $('#collapsePrice').hide();
        }
        
        $('#Container .results').on('mixEnd', function(e, state){
            if( state.totalShow === 0 ){
                $('.hotel-results .errorBlock').show();
            }
        });

        $('#Container .results').on('mixStart', function(e, state){
            $('.hotel-results .errorBlock').hide();
        });
        
        return_array = retData;

        //generates steps for price filter check boxes.
        //three sections are possible x<=8000, 8000<x<=80000, x>80000
        // for the first $increment = 1000 (16 values)
        // for the second $increment = 1000 (8 values) then $increment = remaining price / 4 (8 values)
        // for the third $increment = max price / 8 (16 values)
        // switch is used to monitor price.
        var maxPrice = retData.high;
        var index = 0;
        var steps = [];
        var step = 0;
        var i = 0;
        var remMaxPrice = maxPrice - 4000;
        var rangeTrigger = 0;
        var tempMaxPrice = maxPrice;
        var hotelIndexMap;

        if( maxPrice <= 8000 ){
            rangeTrigger = 0;
            maxPrice = 8000;
        }else if( maxPrice > 8000 && maxPrice <= 999999 ){
            rangeTrigger = 1;
        }else if( maxPrice > 999999 ){
            rangeTrigger = 2;
        }else{
            return;
        }

        switch( rangeTrigger ){
            case 0:
                var increment = 1000;
                while( step < maxPrice ){
                    if( step > 0 ){
                        steps[i] = step+1;
                    }else{
                        steps[i] = step;
                    }
                    step += increment;
                    steps[i+1] = step;
                    i+=2;
                }
                break;
            case 1:
                var increment = Math.ceil((remMaxPrice/4) / 100) * 100;
                while( step < maxPrice ){
                    if( i < 8 ){
                        if( step > 0 ){
                            steps[i] = step+1;
                        }else{
                            steps[i] = step;
                        }
                        step += 1000;
                        steps[i+1] = step;
                    }else{
                        if( step > 8 ){
                            steps[i] = step+1;
                        }else{
                            steps[i] = step;
                        }
                        step += increment;
                        steps[i+1] = step;
                    }
                    i+=2;
                }
                break;
            case 2:
                var increment = Math.ceil((maxPrice/8) / 100) * 100;
                while( step < maxPrice ){
                    if( step > 0 ){
                        steps[i] = step+1;
                    }else{
                        steps[i] = step;
                    }
                    step += increment;
                    steps[i+1] = step;
                    i+=2;
                }
                break;
        }
        priceFilterSteps = steps;
        // steps generation code end.

        $('#step1, #step2, #step3, #step4, #step5, #step6, #step7, #step8, #step9, #step10, #step11, #step12, #step13, #step14, #step15, #step16, #room_price_disp, #room_price_per_night_disp').autoNumeric('init', {
            dGroup: '2',
            aSep: ','
        });

        $('#step1').autoNumeric('set', steps[0]);
        $('#step2').autoNumeric('set', steps[1]);
        $('#step3').autoNumeric('set', steps[2]);
        $('#step4').autoNumeric('set', steps[3]);
        $('#step5').autoNumeric('set', steps[4]);
        $('#step6').autoNumeric('set', steps[5]);
        $('#step7').autoNumeric('set', steps[6]);
        $('#step8').autoNumeric('set', steps[7]);
        $('#step9').autoNumeric('set', steps[8]);
        $('#step10').autoNumeric('set', steps[9]);
        $('#step11').autoNumeric('set', steps[10]);
        $('#step12').autoNumeric('set', steps[11]);
        $('#step13').autoNumeric('set', steps[12]);
        $('#step14').autoNumeric('set', steps[13]);
        $('#step15').autoNumeric('set', steps[14]);
        $('#step16').autoNumeric('set', steps[15]);

        $('label.priceLabel-0').append( '< ' + '<i class="fa fa-inr"></i>&nbsp; ' + $('#step2').html() );
        $('label.priceLabel-1').append( '<i class="fa fa-inr"></i>&nbsp; ' + $('#step3').html() + ' - ' + '<i class="fa fa-inr"></i>&nbsp; ' + $('#step4').html() );
        $('label.priceLabel-2').append( '<i class="fa fa-inr"></i>&nbsp; ' + $('#step5').html() + ' - ' + '<i class="fa fa-inr"></i>&nbsp; ' + $('#step6').html() );
        $('label.priceLabel-3').append( '<i class="fa fa-inr"></i>&nbsp;' + $('#step7').html() + ' - ' + '<i class="fa fa-inr"></i>&nbsp; ' + $('#step8').html() );
        $('label.priceLabel-4').append( '<i class="fa fa-inr"></i>&nbsp; ' + $('#step9').html() + ' - ' + '<i class="fa fa-inr"></i>&nbsp; ' + $('#step10').html() );
        $('label.priceLabel-5').append( '<i class="fa fa-inr"></i>&nbsp; ' + $('#step11').html() + ' - ' + '<i class="fa fa-inr"></i>&nbsp; ' + $('#step12').html() );
        $('label.priceLabel-6').append( '<i class="fa fa-inr"></i>&nbsp; ' + $('#step13').html() + ' - ' + '<i class="fa fa-inr"></i>&nbsp; ' + $('#step14').html() );
        $('label.priceLabel-7').append( '<i class="fa fa-inr"></i>&nbsp; ' + $('#step15').html() + ' - ' + '<i class="fa fa-inr"></i>&nbsp; ' + $('#step16').html() );

        //location filter

        $.each(retData.hotelLocationList, function(i, val){
            $('#collapseLocation fieldset').append('<div class="checkbox"><label><input type="checkbox" class="filterCheckbox locationFilter filter" data-location="'+val+'" id="locationFilter-'+val+'" value=".location-'+val+'" />'+val+'</label></div>');
        });

        $.each(retData.HotelSearchResult.HotelResults, function(i, val){
            var hotelLocation = '<h5 class="hotelLocationName ellipse remove-margin" >'+val.HotelLocation+'</h5>';
            if( val.HotelLocation == "null" || val.HotelLocation == null || val.HotelLocation == "" ){
                hotelLocation = '<h5 class="hotelLocationName ellipse remove-margin" ></h5>';
            }
            for( var r = 0 ; r < priceFilterSteps.length ; r++ ){
                if(val.Price.PublishedPriceRoundedOff >= priceFilterSteps[2*r] && val.Price.PublishedPriceRoundedOff <= priceFilterSteps[2*r+1]){
					var hotel_picture = val.HotelPicture;
					hotelPicture = hotel_picture.replace('http:','');
                    $('#result-area').append(
                        '<div class="col-xs-24 col-sm-6 sort result mix priceRange-'+r+' rating-'+val.StarRating+' location-'+val.HotelLocation+' "  id="'+i+'" data-pricesort="'+val.Price.PublishedPriceRoundedOff+'" data-hotelindex="'+i+'">'
                            +'<div class="custThumbnailContainer" style="background: url('+val.HotelPicture+') center center no-repeat; background-size:cover;">'
                                +'<div class="custOverlay"></div>'
                                +'<div class="custCaption hotelCaption left-text">'
                                    +'<div class="row">'
                                        +'<h4 class="activityName hotelName ellipse remove-margin" id="'+i+'">'+val.HotelName+'</h4>'
                                        +hotelLocation
                                    +'</div>'
                                    +'<div class="row">'
                                        +'<ul class="activityAvgRating col-xs-12">'+star_rating(val.StarRating)+'</ul>'
                                        +'<div class="col-xs-12 hotelPrice right-text"><i class="fa fa-inr"></i>&nbsp; <span>'+val.Price.PublishedPriceRoundedOff+'</span><div class="onwardsLabel">onwards</div></div>'
                                        +'<input type="text" style="display:none" class="activityPriceIO" value="'+val.Price.PublishedPriceRoundedOff+'"/>'
                                    +'</div>'
                                +'</div>'
                            +'</div>'
                        +'</div>'
                    );
                }
            }
        });
    
        $('.hotelPrice span').autoNumeric({
            aSep: ',',
            dGroup: 2
        });

        var some = checkboxFilter.init();

        if( some ){
            
            checkboxFilter.parseFilters();
        }


    });
    
    $('#Container .results').mixItUp({
        controls: {
            enable: false        
		},
        animation: {
            easing: 'cubic-bezier(0.86, 0, 0.07, 1)',
            duration: 300
        }
    });

// Ajax call for more details on a hotel
   
    $( '.results' ).on('click', '.result', function(){
        $('#cancellation_policy').html('');

        $('.hotels-nav a:first').tab('show');
        $('.selected-room-info-text').html('');

        hotel_index = $(this).data('hotelindex');
        $('#room_selected').selectOrDie({
            placeholder: 'select room',
            onChange: function(){
                $('#select_room').html('');
                var pt = $(this).val();
                var price_arr = pt.split('~s~');
                var len = price_arr.length - 1;
                var checkin = "<?php echo $_GET['checkin_time'];?>";
                var checkout = "<?php echo $_GET['checkout_time'];?>";
                var total_price = price_arr[len];
                $('#room_price_disp').autoNumeric('set', total_price);
                $('#room_price_per_night_disp').autoNumeric('set', price_arr[len])
                $('.selected-room-info-text').html('');
                $('.selected-room-info-text').append("<h5 class='osw-text'><span class='open-text'>Check-In:</span> "+checkin+"</h5>");
                $('.selected-room-info-text').append("<h5 class='osw-text'><span class='open-text'>Check-Out:</span> "+checkout+"</h5>");
                $('#room_type').val($('#room_selected').val());
            }
        });
        
        $(document).on("click", ".result", function () {
            $('#hotel_details_modal').modal('show');
			$('.hotels_spinner').show();
            $('#hotel_details_modal .modal-content').addClass('hide');
        });

        $.ajax({
            type: 'post',
            url: '<?php echo site_url("new_request/room_info");?>',
            data: {hotel_code: return_array.HotelSearchResult.HotelResults[hotel_index].HotelCode, result_index: return_array.HotelSearchResult.HotelResults[hotel_index].ResultIndex, trace_id: return_array.HotelSearchResult.TraceId}
        }).done(function(resultdata){
            resultdata = $.parseJSON(resultdata);
            globalRoomDetails = resultdata;
            hotelIndexMap = createIndexMap(globalRoomDetails);
        });

        $.ajax({
            type: 'post',
            url: '<?php echo site_url("new_request/hotel_info");?>',
            data: {hotel_code: return_array.HotelSearchResult.HotelResults[hotel_index].HotelCode, result_index: return_array.HotelSearchResult.HotelResults[hotel_index].ResultIndex, trace_id: return_array.HotelSearchResult.TraceId},
        })
        .done(function(retdata){
            retdata = $.parseJSON(retdata);
            $('#trace_id').val(JSON.stringify(retdata.HotelInfoResult.TraceId));
            $('#hotel_extra_info_str').val(JSON.stringify(retdata));
            $('#hotel_details_modal .modal-header').html('<span class="inline-h4">'+retdata.HotelInfoResult.HotelDetails.HotelName+'</span> - <span class="h6">'+retdata.HotelInfoResult.HotelDetails.Address+'</span><div class="pull-right"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>')
            
            //geoLocation
            var lat = parseFloat(retdata.HotelInfoResult.HotelDetails.Latitude);
            var long = parseFloat(retdata.HotelInfoResult.HotelDetails.Longitude);
            
            var mapOptions = {
                center: new google.maps.LatLng(lat, long),
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            $('#desc_link').on('shown.bs.tab', function(e) {
                if( e.target.id === 'desc_link' ){
                    $('.hotels-content').animate({'background-color': '#fff'}, 300, 'easeInOutQuad');
                }
            });

            var map;
            $('#map_link').on('shown.bs.tab', function(e) {
                if( e.target.id === 'map_link' ){
                    $('.hotels-content').animate({'background-color': '#fff'}, 300, 'easeInOutQuad');
                    if( map === undefined) {
                        map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
                        var marker = new google.maps.Marker({
                            position:new google.maps.LatLng(lat, long),
                            map: map,
                        });
                    }
                }
            });

            //hotel description
            $('.hotels-content #desc').html(hotelDescription(retdata.HotelInfoResult.HotelDetails.Description, retdata.HotelInfoResult.HotelDetails.HotelFacilities, retdata.HotelInfoResult.HotelDetails.Attractions));

            //pics
            $('#pics_link').on('shown.bs.tab', function(e) {
                if( e.target.id === 'pics_link' ){
                    $('.hotels-content').animate({'background-color': '#000'}, 300, 'easeInOutQuad');
                    //fotorama

                    // 1. Initialize fotorama manually.
                    var $fotoramaDiv = $('.fotorama').fotorama();

                    // 2. Get the API object.
                    var fotorama = $fotoramaDiv.data('fotorama');

                    // $('.hotels-content #pics .images-container').html("");

                    var images = [];
                    $.each(retdata.HotelInfoResult.HotelDetails.Images, function(i, val){
                        images.push({img:val});
                    });
                    fotorama.load(images);
                }
            });

            //reviews
            $('#book_link').on('shown.bs.tab', function(e) {
                if( e.target.id === 'book_link' ){
                    $('.hotels-content').animate({'background-color': '#fff'}, 300, 'easeInOutQuad');
                    <?php if( isset($_GET['single_rooms']) ):?>
                        var roomCount = parseInt("<?php echo $_GET['single_rooms']?>");
                    <?php else:?>
                        var roomCount = 1;
                    <?php endif;?>
                    // setup dropdowns
                    $('#book').html('<div class="row"> <div class="col-lg-10" id="room_dropdowns"></div><div class="col-lg-14"> <div class="room_details"><div class="row" id="checkInOutTime"></div></div> <div class="row selected-room-info-text"></div><div class="row"> <button type="button" class="btn btn-change fix-to-bottom" id="book_hotel_btn" disabled>BOOK</button> </div></div></div>');
                    for( var i = 0 ; i < roomCount ; i++ ){
                        $('#book').append('<div id="loading_section-'+i+'"></div>');
                        if( i === 0 ){
                            $('#book #room_dropdowns').html(' <div class="row newRoomSelect"> <select name="room_selected-'+i+'" id="room_selected-'+i+'"></select> </div><div class="row" id="different_room-'+i+'" style="display:none;"> <center> <h3 id="select_room-'+i+'"></h3> </center> </div>');
                        }else{
                            $('#book #room_dropdowns').append(' <div class="row newRoomSelect"> <select name="room_selected-'+i+'" id="room_selected-'+i+'"></select> </div><div class="row" id="different_room-'+i+'" style="display:none;"> <center> <h3 id="select_room-'+i+'"></h3> </center> </div>');
							$('#book .room_details').append('<div class="row" id="checkInOutTime-'+i+'"></div>');
						}
                    }
					var count_one = 0;
					var count_two = 0;
					var count_three = 0;
					var count_four = 0;
					var comboRoomIndex = 1;

                    for( var i = 0 ; i < roomCount ; i++ ){
                        // setup dropdown options
                        var resultdata = globalRoomDetails;                    

                        $('#room_hotel_info').val(JSON.stringify(resultdata));
                        $('#room_selected-'+i).html('<option>select room</option>');
                        $('#room_selected-'+i).append(room_options(resultdata, i));
                        $('#room_selected-'+i).selectOrDie({
                            placeholderOption: true,
                            onChange: function(){
                                $("#loading_section-"+(i-1)).html("<div class='center-align-text'>Loading...</div>");
                                $('#cancellation_policy').html('<div class="col-lg-24 center-align-text h3"> Loading... </div>');
								$('.modal-content').append('<div id="overlay"></div>');
								$('#book_hotel_btn').attr('disabled', true);
                                $('#select_room').html('');
                                var pt = $(this).val();
                                var price_arr = pt.split('~s~');
                                var roomIndex = parseInt(price_arr[0]);
                                var selectIdArr =  this.id.split('-');
							
                                if(resultdata.GetHotelRoomResult.RoomCombinations.InfoSource === "FixedCombination"){
                                    $.each(resultdata.GetHotelRoomResult.RoomCombinations.RoomCombination, function(i, val){
										$.each(val.RoomIndex, function(j, jVal){
											if( jVal === roomIndex ){
												comboRoomIndex = val.RoomIndex[j + 1];
											}
										})										
                                    });        
                                
                                    if( typeof $('#'+selectIdArr[0] +'-'+ (parseInt(selectIdArr[1])+1)) != 'undefined' ){
                                        var options = $('#'+selectIdArr[0] +'-'+ (parseInt(selectIdArr[1])+1)).children('option');
                                        $.each(options, function(i, val){
                                            var price_arr = val.value.split('~s~');
                                            var roomIndex = parseInt(price_arr[0]);
    										
                                            if( roomIndex === comboRoomIndex ){
                                                $(val).attr('id', 'picked');
                                            }
                                        });
                                        $.each(options, function(i, val){
                                            if( $(val).attr('id') !== 'picked' && $(val).html() !== 'select room' ){
                                                $('#'+selectIdArr[0] +'-'+ (parseInt(selectIdArr[1])+1)).selectOrDie('disable', $(val).val());
                                            }else{
                                                $(val).removeAttr('id');
												$('#'+selectIdArr[0] +'-'+ (parseInt(selectIdArr[1])+1)).selectOrDie('enable', $(val).val());
                                            }
                                        });
                                    }
                                }

                                var len = price_arr.length - 1;
                                var checkin = "<?php echo $_GET['checkin_time'];?>";
                                var checkout = "<?php echo $_GET['checkout_time'];?>";
                                var multiplier = <?php echo $days;?>;
                                var total_price = price_arr[len];
								var roomindex = this.id;
								
								if( this.id === "room_selected-0" ){
                                    if(roomCount == 1){
                                        count_one = 1;
                                        $('#room_selected-1').selectOrDie('disable');
                                    }
                                    if( count_one == 1 ){
                                        $('.room_selected-0').remove();
                                        $('.room_selected-1').remove();
                                        $('.room_selected-2').remove();
                                        $('.room_selected-3').remove();
                                        var dpd1 = document.getElementById('room_selected-1');
                                        var dpd2 = document.getElementById('room_selected-2');

                                        $(dpd1).selectOrDie('update');
                                        $(dpd1).selectOrDie({
                                            placeholder: 'select room'
                                        });
										$(dpd2).selectOrDie('update');
										
                                        $('#room_selected-2').selectOrDie('disable');
                                        $('#room_selected-3').selectOrDie('disable');
                                        count_two = 0;
                                        count_three = 0;
                                        count_four = 0;
                                    }
                                    else{
                                        count_one = 1;
                                        $('#room_selected-1').selectOrDie('enable');
                                    }   
                                }
                                if( this.id === "room_selected-1" ){    
                                    if( count_two == 1 ){
                                        $('.room_selected-1').remove();
                                        $('.room_selected-2').remove();
                                        $('.room_selected-3').remove();
                                        $('#room_selected-2').val('');
                                        $('#room_selected-3').val('');
                                        $('#room_selected-3').selectOrDie('disable');
                                        count_three=0;
                                        count_four=0;
                                    }
                                    else{
                                        count_two = 1;
                                        $('#room_selected-2').selectOrDie('enable');
                                    }   
                                }
                                if( this.id === "room_selected-2" ){
                                    if( count_three == 1 ){
                                        $('.room_selected-2').remove();
                                        $('.room_selected-3').remove();
                                        count_four=0;
                                    }
                                    else{
                                        count_three = 1;
                                        $('#room_selected-3').selectOrDie('enable');
                                    }   
                                }
                                if( this.id === "room_selected-3" ){
                                    console.log(this.name);
                                    if( count_four == 1 ){
                                        $('.room_selected-3').remove();
                                        $('#room_selected-4').val('');
                                    }
                                    else{count_four = 1;
                                        count_one=1;
                                        count_two=1;
                                        count_three=1;
                                    }   
                                }
                                var price_one_night = total_price / multiplier;
								var tax_total = resultdata.GetHotelRoomResult.HotelRoomsDetails[this.selectedIndex-1].Price.Tax;
                                var tax_one_night = tax_total/multiplier;
                                var total_price_one_night = price_one_night;
								
                                $('#room_price_disp').autoNumeric('set', total_price);
                                $('#room_price_per_night_disp').autoNumeric('set', total_price_one_night);
                                $('.selected-room-info-text').html('');
                                $('.selected-room-info-text').append("<h5 class='osw-text'><span class='open-text'>Check-In:</span> "+checkin+"</h5>");
                                $('.selected-room-info-text').append("<h5 class='osw-text'><span class='open-text'>Check-Out:</span> "+checkout+"</h5>");
                                $('.room_details #checkInOutTime').append("<h4 id='total_fare' class="+this.id+">Room Price: <i class='fa fa-inr'></i>&nbsp; "+$('#room_price_disp').html()+" <span class='h5 osw-text'>(<i class='fa fa-inr'></i>&nbsp; "+$('#room_price_per_night_disp').html()+" / Per Night)</span></h4>");
								$('#room_type').val(pt);
                                $('#hotel_info_str').val(JSON.stringify(return_array.HotelSearchResult.HotelResults[hotel_index]));
                                var room_type = $('input[name=room_type]').val();
                                var hotel_info = $('input[name=hotel_info]').val();
                                var single_rooms = $('input[name=single_rooms]').val();
                                var trace_id = $('input[name=trace_id]').val();
								var room = room_type.split("~s~");
								room[0] = room[0]-1;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo site_url('new_request/store_block_room_info');?>",
                                    data: { room_type : room_type, hotel_info : hotel_info, single_rooms : single_rooms, trace_id : trace_id, priceDetails: resultdata.GetHotelRoomResult.HotelRoomsDetails[room[0]], index: roomindex}
                                })
                                .done(function( msg ) {
                                    $('#book_hotel_btn').html('BOOK');
									$('#overlay').remove();
									
                                    var response = $.parseJSON(msg);
                                    if(response['message'] === "Not available"){
                                        $('#different_room-'+(i-1)).show();
                                        $('#select_room-'+(i-1)).html('This room is not available. Please select another room.');
                                        $('#book_hotel_btn').attr('disabled', true);
                                    }
                                    else if( response['message'] === 'stored' ){
                                        $('#different_room').show();
                                        $('#book_hotel_btn').attr('disabled', true);
                                        $('#cancellation_policy').html('');
										$('.selected-room-info-text').append("<h4>Total Fare: <i class='fa fa-inr'></i>&nbsp;"+response['fare']+"</h4>"); 
                                        $("#loading_section-"+(i-1)).html("Please choose the next room");
										
                                    }
									else if( response === 'Error' ){
										$('.selected-room-info-text').append("");
										$('.room_selected-0').remove();
										$('.room_selected-1').remove();
										$('.room_selected-2').remove();
										$('.room_selected-3').remove();
										$('#room_selected-0').val('');
										$('#room_selected-1').val('');
										$('#room_selected-2').val('');
										$('#room_selected-3').val('');
										$("#loading_section-"+(i-1)).html("Sorry this room combination is not available. Please choose another room");		
										$('#book_hotel_btn').attr('disabled', true);
									}
									else{
                                        $("#loading_section-"+(i-1)).html("");
										$('.selected-room-info-text').append("<h4>Total Fare: <i class='fa fa-inr'></i>&nbsp;"+response['fare']+"</h4>");
										$('#book_hotel_btn').removeAttr('disabled');
                                        setRoomPreferences(response['res'].BlockRoomResult);
                                    }
                                });
                            }   
                        });

                    }
                }

            });

            $('.hotels_spinner').hide();
            $('#hotel_details_modal .modal-content').removeClass('hide');
            $('.activityBtn').removeAttr('disabled');
            $('.activityBtn').html('VIEW');
        });
    });

    $('#hotel_details_modal').on('show.bs.modal', function(){
        $('#select_room').html('');
    });

    $('#hotel_details_modal').on('click', '#book_hotel_btn', function(){
        $(this).html('loading...');
        $('#hotel_info_str').val(JSON.stringify(return_array.HotelSearchResult.HotelResults[hotel_index]));
        $('input[name=cancelPolicy]').val(cancellationPolicy);
        $('input[name=checkTime]').val(checkTime);
        var room_type = $('input[name=room_type]').val();
        var hotel_info = $('input[name=hotel_info]').val();
        var single_rooms = $('input[name=single_rooms]').val();
        var trace_id = $('input[name=trace_id]').val();
        $('#book_hotel_form').submit();
    });

    var priceFilterSteps = ext_arr;

    $('#collapseOne').on('show.bs.collapse', function(e){
        if( e.target.id === 'collapseOne' ){
            var panel_width = $('#accordionOccupants').width();
            $('#accordionOccupants .panel-collapse').css('width', panel_width+'px');
        }
    });

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

    //weather

        var searchedDate = new Date("<?php echo date('c', strtotime($_GET['checkin_time']));?>");
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
        var getCity = "<?php echo $_GET['typed-string-single']?>";
        var cityArray = getCity.split(',');
        var city_name = cityArray[0];
        var city_lat;
        var city_long;

        var geocoder =  new google.maps.Geocoder();
        geocoder.geocode( { 'address': city_name}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                city_lat = results[0].geometry.location.lat();
                city_long = results[0].geometry.location.lng(); 
                $.ajax({
                    type: "POST",
                    url : "<?php echo site_url('weather/single_city');?>",
                    data : { choosen_date: required_date, city_lat : city_lat , city_long : city_long, is_predictable_weather: isPredictableWeather}
                })
                .done(function(weatherdata){
                    $('.weather_spinner').hide();
                    var wData = $.parseJSON(weatherdata);
                    if( wData.isHistoric ){
                        // historic weather
                        $('.weatherDetails .date').html('<div class="title">Weather for:</div><span>'+city_name+' (Today)</span>');
                        $('.weatherDetails .icon').html(getWeatherIcon(wData));
                        $('.weatherDetails .summary').html(wData.summary);
                        $('.weatherDetails .temperature').html(' <div class="row actual"> <div class="col-xs-24"> <div class="title">Temperature</div> <span class="h4 min">'+ Math.round(wData.temperature) +'&deg;C</span> </div>');
                    }else{
                        // current weather
                        $('.weatherDetails .date').html('<div class="title">Weather for:</div><span>'+city_name+'<br /> ('+display_date+')</span>');
                        $('.weatherDetails .icon').html(getWeatherIcon(wData));
                        $('.weatherDetails .summary').html(wData.summary);
                        $('.weatherDetails .temperature').html(' <div class="row actual"> <div class="col-xs-12"> <div class="title">Min</div><span class="h4 min">'+ Math.round(wData.minTemperature) +'&deg;C</span> </div><div class="col-xs-12"> <div class="title">Max</div><span class="h4 max">'+ Math.round(wData.maxTemperature) +'&deg;C</span> </div></div>');
                    }
                });
            } else {
                return;
            }
        });

    //weather end

})();

    //creates a weather icon
    function getWeatherIcon(wData){
        var weatherIcon = '';
        switch(wData.icon){
            case 'clear-day' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/clear.png" />';
            break;
            case 'clear-night' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/clear.png" />';
            break;
            case 'rain' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/rain.png" />';
            break;
            case 'snow' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/snow.png" />';
            break;
            case 'sleet' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/sleet.png" />';
            break;
            case 'wind' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/wind.png" />';
            break;
            case 'fog' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/fog.png" />';
            break;
            case 'cloudy' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/cloudy.png" />';
            break;
            case 'partly-cloudy-day' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/partly_cloudy.png" />';
            break;
            case 'partly-cloudy-night' : 
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/partly_cloudy.png" />';
            break;
            default :
            weatherIcon = '<img height="150" onload="javascript:$(this).show()" style="display:none;" class="weatherIcon" src="../../../img/weather_icons/clear.png" />';
            break;
        }
        return weatherIcon;

    }
    //creates a weather icon end

    function room_options(room_selected, dropdownIndex){
        var ret_str  = "";
        if( room_selected.GetHotelRoomResult.RoomCombinations.InfoSource === "FixedCombination" ){
            $.each(room_selected.GetHotelRoomResult.RoomCombinations.RoomCombination, function(i, val){
                var len = val.RoomIndex.length;
				ret_str += '<option value="'+room_selected.GetHotelRoomResult.HotelRoomsDetails[(len*i)+dropdownIndex].RoomIndex+'~s~'+room_selected.GetHotelRoomResult.HotelRoomsDetails[(len*i)+dropdownIndex].RoomTypeCode+'~s~'+room_selected.GetHotelRoomResult.HotelRoomsDetails[(len*i)+dropdownIndex].RoomTypeName+'~s~'+room_selected.GetHotelRoomResult.HotelRoomsDetails[(len*i)+dropdownIndex].RatePlanCode+'~s~'+room_selected.GetHotelRoomResult.HotelRoomsDetails[(len*i)+dropdownIndex].Price.PublishedPriceRoundedOff+'">'+room_selected.GetHotelRoomResult.HotelRoomsDetails[(len*i)+dropdownIndex].RoomTypeName+'</option>'
            });
        }else{
            $.each(room_selected.GetHotelRoomResult.RoomCombinations.RoomCombination[dropdownIndex].RoomIndex, function(i, value){                
				 var currentHotelRoom =hotelIndexMap[value];
				ret_str += '<option value="'+value+'~s~'+currentHotelRoom.RoomTypeCode+'~s~'+currentHotelRoom.RoomTypeName+'~s~'+currentHotelRoom.RatePlanCode+'~s~'+currentHotelRoom.Price.PublishedPriceRoundedOff+'">'+currentHotelRoom.RoomTypeName+'</option>'
            });            
        }
        return ret_str;
    }	

    function star_rating(rating){
        var stars = rating;
        var star_str = '';
        for( var i = 0 ; i < stars ; i++ ){
            star_str = star_str + '<li id="rating-'+stars+'"><span class="glyphicon glyphicon-star"></span></li>';
        }
        for( var i = 0 ; i < (5-stars) ; i++ ){
            star_str = star_str + '<li><span class="glyphicon glyphicon-star-empty"></span></li>';
        }

        return star_str;
    }

    function hotelDescription(description, ammenities, attractions){

        var list = '<ul>';
        if( ammenities != null){
            $.each(ammenities, function(i, val){
                list += '<li>'+val+'</li>';
            });
            list += '</ul>';
        }else{
            list += '<li>None</li></ul>';
        }

        var attrStr = '<ul>';
        if( attractions != null){
            $.each(attractions, function(i, val){
                attrStr += '<li>'+val.Value+'</li>';
            }); 
            attrStr += '</ul>';
        }else{
            attrStr += '<li>None</li></ul>';
        }
        return '<div class="row"><div class="col-lg-offset-1 col-lg-15 hotelDescription"> <span class="h4">Hotel Description:</span> <br /> <span class="hotelDescription">'+description+'</span> <hr /> <span class="h4">Attractions:</span> <br /> '+attrStr+' </div><div class="col-lg-offset-1 col-lg-7 hotelList"> <span class="h4">Amenities:</span> <br /> '+list+'</div></div>';
    }

    function setRoomPreferences( BlockRoomResult ){
        var str = BlockRoomResult.HotelRoomsDetails[0].CancellationPolicy.split('#^#');

        checkTime = BlockRoomResult.HotelRoomsDetails[0].SupplierSpecificData;

        if((BlockRoomResult.HotelRoomsDetails[0].SupplierSpecificData)){
            var checkInOut = BlockRoomResult.HotelRoomsDetails[0].SupplierSpecificData.split('|');
        }
        var roomType = str[0];
        var rawPolicy = str[1];
        cancellationPolicy = rawPolicy;
        var policy = rawPolicy.split('|');
        var occupancy = BlockRoomResult.HotelRoomsDetails[0].RoomTypeCode.split('|');
        var NoOfOccupants = occupancy[occupancy.length - 1];
    }
	
    function createIndexMap(resultdata){
        var returnMap = {};
        $.each(resultdata.GetHotelRoomResult.HotelRoomsDetails,function(index,val){
            var indexOfHotel = val.RoomIndex;
            returnMap[indexOfHotel] = val;
        });
        return returnMap;
    }

    $('#collapseSearch').css('width', '100%');
</script>