<?php
	if(isset($_SESSION['user_details'])){
		$user_details = $_SESSION['user_details'];
	}
	else{
		$user_details = "";
	}
	if(isset($_SESSION['currentUrlHotel'])){
		$currentUrlHotel = $_SESSION['currentUrlHotel'];
	}
	if(isset($_SESSION['calling_controller_name'])){
		$calling_controller_name = $_SESSION['calling_controller_name'];
	}
	unset($_SESSION);
	$_SESSION['user_details'] = $user_details;
	$_SESSION['currentUrlHotel'] = $currentUrlHotel;
	$_SESSION['calling_controller_name'] = $calling_controller_name;
?>
<head>
<title>Farebucket: Book Cheap Air Tickets India | Cheap Flight Tickets Booking | Cheap Air Tickets India </title>

<meta name="Subject" content="Book Cheap Air Tickets India" />

<meta name="description" content="Farebucket delivers you safely and with joy! Could not have wished for better!You are welcome to write to the Management with feedback about our services. Book Cheap Air Tickets India , Cheap Flight Tickets Booking , Cheap Air Tickets India" />

<meta name="keywords" content="Book Cheap Air Tickets India, Cheap Flight Tickets Booking, Cheap Air Tickets India, Lowest Airfare In India, Cheap Cab Services In Chennai, Cheap Cab Rates In Chennai, Cheap Call Taxi In Chennai, Cheap Call Taxi Tariff Chennai, Cheap Hotel Packages, Book Cheap Hotels In ChennaiCheap Cabs In Bangalore, Cheap Cabs In Chennai, Cheap Domestic Air Tickets" />

<meta name="Language" content="English" />

<meta name="Distribution" content="Global" />

<meta name="Robots" content="All" />

<meta name="Revisit-After" content="7 Days" />
</head>
<style>
    .specialFlightArea{
        color:#000;
    }
    .sod_placeholder{
        text-transform: none;
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
<script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete, autocomplete_1, autocomplete_2, autocomplete_3, autocomplete_4;
var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
};

function initialize() {
    // Create the autocomplete object, restricting the search
    // to geographical location types.
    var input_ele = document.getElementById('autocomplete');
    var input_ele_1 = document.getElementById('autocomplete_1');
    var input_ele_2 = document.getElementById('autocomplete_2');
    var input_ele_3 = document.getElementById('autocomplete_3');
    var input_ele_4 = document.getElementById('autocomplete_4');
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(input_ele),
        { types: ['(cities)'] });
    autocomplete_1 = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(input_ele_1),
        { types: ['(cities)'] });
    autocomplete_2 = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(input_ele_2),
        { types: ['(cities)'] });
    autocomplete_3 = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(input_ele_3),
        { types: ['(cities)'] });
    autocomplete_4 = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(input_ele_4),
        { types: ['(cities)'] });
    // When the user selects an address from the dropdown,
    // populate the address fields in the form.
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        fillInAddress(input_ele, '0');
    });
    google.maps.event.addListener(autocomplete_1, 'place_changed', function() {
        fillInAddress(input_ele_1, '1');
    });
    google.maps.event.addListener(autocomplete_2, 'place_changed', function() {
        fillInAddress(input_ele_2, '2');
    });
    google.maps.event.addListener(autocomplete_3, 'place_changed', function() {
        fillInAddress(input_ele_3, '3');
    });
    google.maps.event.addListener(autocomplete_4, 'place_changed', function() {
        fillInAddress(input_ele_4, '4');
    });
}

// [START region_fillform]
function fillInAddress(input_ele, AC_Num) {

    var place, multi_flag;

    switch( AC_Num ){
        case "1":
            // Get the place details from the autocomplete object.
            place = autocomplete_1.getPlace();
            multi_flag = true;
            break;
        case "2":
            place = autocomplete_2.getPlace();
            multi_flag = true;
            break;
        case "3":
            place = autocomplete_3.getPlace();
            multi_flag = true;
            break;
        case "4":
            place = autocomplete_4.getPlace();
            multi_flag = true;
            break;
        default:
            place = autocomplete.getPlace();
            multi_flag = false;
            break;
    }
    var search_str = '';
    // Get each component of the address from the place details
    // and fill the corresponding field on the form.

    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            if( addressType === 'country' ){
                var val = place.address_components[i]['short_name'];
                search_str = search_str + val;
                search_str = search_str + ',';
            }
            var val = place.address_components[i][componentForm[addressType]];
            search_str = search_str + val;
            if( i !== place.address_components.length - 1 ){
                search_str = search_str + ',';
            }
        }
    }

    if( multi_flag === true ){
        document.getElementById('search-string-multi_'+AC_Num).value = search_str;
        document.getElementById('typed-string-multi_'+AC_Num).value = input_ele.value;        
    }else{
        document.getElementById('search-string-single').value = search_str;
        document.getElementById('typed-string-single').value = input_ele.value;
    }

}
// [END region_fillform]

function stopRKey(evt) { 
    var evt = (evt) ? evt : ((event) ? event : null); 
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
    if ((evt.keyCode == 13)) {return false;} 
} 

document.onkeypress = stopRKey; 
</script>
<body onload="initialize()">
<div class="wrap">
    <div class="container-fluid flights-wrapper clear-top">
    <div class="row"><div class="col-lg-24 center-align-text"><h1 class='marketingMessage'>Search for Hotels</h1></div></div>
        <!-- tabs -->
        <ul class="nav nav-tabs flights-nav" role="tablist">
            <li class="active"><a href="#single" role="tab" data-toggle="tab">Single</a></li>
            <li><a href="#multi" role="tab" data-toggle="tab">Multi</a></li>
        </ul>
        <!-- tabs end -->
        <!-- tab contents, insert within tab-content -->
        <div class="tab-content change-height">
            <div class="tab-pane pane-height fade in active" id="single">
                <form action="<?php echo site_url('hotel_api/hotels/hotel_search');?>" method="get" id="form-submit-1" enctype="multipart/form-data">
                <input type="text" name="typed-string-single" id="typed-string-single" style="display: none;"/>
                <input type="text" name="search-string-single" id="search-string-single" style="display: none;"/>
                    <div class="form-padding row">
                        <div class="form-group">
                            <div class="col-lg-6 control-label">
                            	<input name="city_name" onClick="this.select();" type="text" placeholder="Destination" id="autocomplete" autocomplete="false" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-5">
    	                        <div class="inner-addon right-addon">
    	                            <i class="glyphicon"></i>
    	                            <input name="checkin_time" type="text" placeholder="Check In" readonly id="checkin_time" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-5">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="checkout_time" id="checkout_time" class="form-control" readonly title="Please Choose a Check-in Date First" type="text" placeholder="Check Out" value="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 control-label">
                                <div class="panel-group" id="accordionOccupants">
                                    <div class="panel panel-default">
                                        <a class="btn-custom passenger-target-1" data-toggle="collapse" data-parent="#accordionOccupants" href="#collapseOne">
                                            <div class="panel-heading-custom">
                                               1 Occupant <span class="caret"></span>
                                            </div>
                                        </a>
                                        <div id="collapseOne" class="panel-collapse panel-collapse-custom collapse">
                                            <div class="panel-body panel-dropdown">
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <div class="row">
                                                            <div class="col-lg-24 select-box-label">No. of Rooms</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-24">
                                                                <select name="single_rooms" id="single_rooms">
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-16">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24 select-box-label">No. of adults</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-24">
                                                                    <select name="adult_count_single-1" id="adult_count_single-1">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="row select-box-label">
                                                                <div class="col-lg-24">No. of kids</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-24 sod_list_wrapper_res_more">
                                                                    <select name="child_count_single-1" id="child_count_single-1">
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-offset-8 col-lg-16 adult-child-row-single">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24">
                                                                    <select name="adult_count_single-2" id="adult_count_single-2">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24 sod_list_wrapper_res_more">
                                                                    <select name="child_count_single-2" id="child_count_single-2">
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-offset-8 col-lg-16 adult-child-row-single">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24">
                                                                    <select name="adult_count_single-3" id="adult_count_single-3">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24 sod_list_wrapper_res_more">
                                                                    <select name="child_count_single-3" id="child_count_single-3">
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-offset-8 col-lg-16 adult-child-row-single">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24">
                                                                    <select name="adult_count_single-4" id="adult_count_single-4">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24 sod_list_wrapper_res_more">
                                                                    <select name="child_count_single-4" id="child_count_single-4">
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row form-padding">
                                                    <div class="col-lg-24">
                                                        <a class="btn btn-change-cls pull-right" data-toggle="collapse" data-parent="#accordionOccupants" href="#collapseOne">close</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row userMessageBlock">
                        <center><small class="successMessage"></small></center>
                        <center><small class="errorMessage"></small></center>
                    </div>
                    <div class="option-bottom-tab search-padding row">
                        <div class="pull-right col-lg-5 remove-padding">
                            <button id="form-submit-button" type="submit"><span class="glyphicon glyphicon-search"></span> SEARCH</button>
                        </div>
                    </div>
                    <input type="text" name="flight_type" value="local" style="display:none;" readonly />
                </form>
            </div>
            <div class="tab-pane pane-height fade" id="multi">
                <form action="<?php echo site_url('hotel_api/hotels/set_hotel_search_multi_url');?>" method="get" id="form-submit-2" enctype="multipart/form-data">
                <input type="text" name="typed-string-multi_1" id="typed-string-multi_1" style="display: none;"/>
                <input type="text" name="search-string-multi_1" id="search-string-multi_1" style="display: none;"/>
                <input type="text" name="typed-string-multi_2" id="typed-string-multi_2" style="display: none;"/>
                <input type="text" name="search-string-multi_2" id="search-string-multi_2" style="display: none;"/>
                <input type="text" name="typed-string-multi_3" id="typed-string-multi_3" style="display: none;"/>
                <input type="text" name="search-string-multi_3" id="search-string-multi_3" style="display: none;"/>
                <input type="text" name="typed-string-multi_4" id="typed-string-multi_4" style="display: none;"/>
                <input type="text" name="search-string-multi_4" id="search-string-multi_4" style="display: none;"/>
                <input type="text" name="count_hotels" value="1" style="display: none;"/>
                    <div class="form-padding row">
                        <div class="form-group">
                            <div class="col-lg-6 control-label">
                                <input name="city_name[]" onClick="this.select();" type="text" placeholder="Destination" id="autocomplete_1" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-5">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="checkin_time[]" type="text" placeholder="Check In" readonly id="checkin_time_1" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-5">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="checkout_time[]" id="checkout_time_1" class="form-control" readonly type="text" placeholder="Check Out" value="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 control-label">
                                <div class="panel-group" id="accordionOccupants">
                                    <div class="panel panel-default">
                                        <a class="btn-custom passenger-target-2" data-toggle="collapse" data-parent="#accordionOccupants" href="#collapseTwo">
                                            <div class="panel-heading-custom">
                                               1 Occupant <span class="caret"></span>
                                            </div>
                                        </a>
                                        <div id="collapseTwo" class="panel-collapse panel-collapse-custom collapse">
                                            <div class="panel-body panel-dropdown">
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <div class="row">
                                                            <div class="col-lg-24 select-box-label">No. of Rooms</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-24">
                                                                <select name="multi_rooms" id="multi_rooms">
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-16">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24 select-box-label">No. of adults</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-24">
                                                                    <select name="adult_count_multi-1" id="adult_count_multi-1">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="row select-box-label">
                                                                <div class="col-lg-24 sod_list_wrapper_res_more">No. of kids</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-24">
                                                                    <select name="child_count_multi-1" id="child_count_multi-1">
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-offset-8 col-lg-16 adult-child-row-multi">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24">
                                                                    <select name="adult_count_multi-2" id="adult_count_multi-2">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24 sod_list_wrapper_res_more">
                                                                    <select name="child_count_multi-2" id="child_count_multi-2">
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-offset-8 col-lg-16 adult-child-row-multi">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24">
                                                                    <select name="adult_count_multi-3" id="adult_count_multi-3">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24 sod_list_wrapper_res_more">
                                                                    <select name="child_count_multi-3" id="child_count_multi-3">
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-offset-8 col-lg-16 adult-child-row-multi">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24">
                                                                    <select name="adult_count_multi-4" id="adult_count_multi-4">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-24 sod_list_wrapper_res_more">
                                                                    <select name="child_count_multi-4" id="child_count_multi-4">
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row form-padding">
                                                    <div class="col-lg-24">
                                                        <a class="btn btn-change-cls pull-right" data-toggle="collapse" data-parent="#accordionOccupants" href="#collapseTwo">close</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-padding row">
                        <div class="form-group">
                            <div class="col-lg-6 control-label">
                                <input name="city_name[]" onClick="this.select();" type="text" placeholder="Destination" id="autocomplete_2" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-5">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="checkin_time[]" type="text" placeholder="Check In" readonly id="checkin_time_2" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-5">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="checkout_time[]" id="checkout_time_2" class="form-control" readonly type="text" placeholder="Check Out" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clonedInput form-padding">
                        <div class="clone">
                            <div class="form-padding row">
                                <div class="form-group">
                                    <div class="col-lg-6 control-label">
                                        <input name="city_name[]" onClick="this.select();" type="text" placeholder="Destination" id="autocomplete_3" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <div class="inner-addon right-addon">
                                            <i class="glyphicon"></i>
                                            <input name="checkin_time[]" type="text" placeholder="Check In" readonly id="checkin_time_3" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <div class="inner-addon right-addon">
                                            <i class="glyphicon"></i>
                                            <input name="checkout_time[]" id="checkout_time_3" class="form-control" readonly type="text" placeholder="Check Out" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-padding destination-text">
                                    <a href="#" class="btnDel"><span class="glyphicon glyphicon-minus-sign"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="clone">
                            <div class="form-padding row">
                                <div class="form-group">
                                    <div class="col-lg-6 control-label">
                                        <input name="city_name[]" onClick="this.select();" type="text" placeholder="Destination" id="autocomplete_4" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <div class="inner-addon right-addon">
                                            <i class="glyphicon"></i>
                                            <input name="checkin_time[]" type="text" placeholder="Check In" readonly id="checkin_time_4" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <div class="inner-addon right-addon">
                                            <i class="glyphicon"></i>
                                            <input name="checkout_time[]" id="checkout_time_4" class="form-control" readonly type="text" placeholder="Check Out" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-padding destination-text">
                                    <a href="#" class="btnDel"><span class="glyphicon glyphicon-minus-sign"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-padding">
                        <div class="form-group">
                            <div class="col-lg-6 add-flight-link"><a href="#" id="btnAdd"><span class="glyphicon glyphicon-plus-sign"></span> Add Destination</a></div>
                        </div>
                    </div>
                    <div class="option-bottom-tab search-padding row">
                        <div class="pull-right col-lg-5 remove-padding">
                            <button id="form-submit-button" type="submit"><span class="glyphicon glyphicon-search"></span> SEARCH</button>
                        </div>
                    </div>
                    <input type="text" name="flight_type" value="multi" style="display:none;" readonly />
                    <input type="text" name="hotel_num" value="1" style="display:none;" readonly />
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
</body>
<script type="text/javascript" src="<?php echo base_url('js/vendor/vegas/jquery.vegas.js'); ?>"></script>
<script type="text/javascript">

    function reset_date( html_element, selected_date, min_date ){
        if( min_date > selected_date ){
            $(html_element).datepicker('setDate', min_date);
            $(html_element).datepicker('option', 'minDate', min_date);
            $(html_element).datepicker('option', 'maxDate', '+1Y');
            return;
        }else{
            return;
        }
    }

//global declaration
var occupants_arr = [0, 0, 0, 0];
var occupants = 0;

$(document).ready(function(){

// add a city in multi city part

        var clicks_index = 1;
        var c = $('.clonedInput');
        c.find('.clone').hide();

        $('#btnAdd').click(function(e) {
            e.preventDefault();

            $('.flights-wrapper').animate({
                'margin-top': (mid-280)+'px'
            }, 300, "linear");
            
            if( clicks_index == 1 ) {
                c.find('.clone:first').slideDown('300');
                clicks_index++;
                $('input[name=count_hotels]').val(clicks_index);
            } else if( clicks_index == 2 ){
                c.find('.btnDel:first').removeClass().addClass('non-clickable');
                c.find('.clone:last').slideDown('300');
                clicks_index++;
                $('input[name=count_hotels]').val(clicks_index);
                $('#btnAdd').hide();
            } else {
                return false;
            }
        });

        c.on( 'click', '.non-clickable', function( e ) {
            e.preventDefault();
            $('.userMessageBlock .successMessage').html('Cannot Delete! Delete last entry first').fadeIn(200);
            setTimeout(function(){
                $('.userMessageBlock .successMessage').fadeOut(200);
            }, 3000);
        });

        c.on('click', '.btnDel', function(e){
            e.preventDefault();
            --clicks_index;

            if( clicks_index == 1 ){
                $('#btnAdd').show();
                $('.flights-wrapper').animate({
                    'margin-top': (mid-260)+'px'
                }, 300, "easeOutCirc");  
                $('input[name=count_hotels]').val(clicks_index);
            }

            $('input[name=count_hotels]').val(clicks_index);
            if( clicks_index == 2 ){
                $('#btnAdd').show();
                c.find('.non-clickable:first').removeClass().addClass('btnDel');
                $('input[name=count_hotels]').val(clicks_index);
            }
            $(this).closest('.clone').slideUp('300');
        });

//end multi city add


// datepicker

    $.datepicker.setDefaults({
            dateFormat: "dd-mm-yy"
    });

    $('#checkin_time').datepicker({
        minDate: 0,
        maxDate: '+1Y',
        onSelect: function(){
            var sm = $('#form-submit-1').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
            var return_date = $(this).datepicker('getDate') || new Date();
            return_date.setDate(return_date.getDate() + 1);
            $('#checkout_time').attr('title', '');
            $('#checkout_time').datepicker('setDate', return_date);
            $('#checkout_time').datepicker('option', 'minDate', return_date);
            $('#checkout_time').datepicker('option', 'maxDate', '+1Y');
            $('#checkout_time').datepicker({
                minDate: new Date(return_date),
                maxDate: '+1Y',
                onSelect: function (dateText, inst) {
                    var sm = $('#form-submit-1').data('bootstrapValidator');
                    sm.updateStatus($(this), 'VALID');
                }
            });
        }
    });

    // this is only half finished, Do not use.
    var minDate1 = new Date();
    var minDate2 = new Date();
    var minDate3 = new Date();
    var minDate4 = new Date();
    var minDate5 = new Date();
    var minDate6 = new Date();
    var minDate7 = new Date();

    //multi city
    $('#checkin_time_1').datepicker({
        minDate:0,
        onSelect: function(){

            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');

            var min = $(this).datepicker('getDate');

            minDate1 = new Date(min.getTime() + 86400000);
            reset_date( document.getElementById('checkout_time_1'), $('#checkout_time_1').datepicker('getDate'), minDate1 );

            minDate2 = new Date(min.getTime() + (2*86400000));
            reset_date( document.getElementById('checkin_time_2'), $('#checkin_time_2').datepicker('getDate'), minDate2 );

            minDate3 = new Date(min.getTime() + (3*86400000));
            reset_date( document.getElementById('checkout_time_2'), $('#checkout_time_2').datepicker('getDate'), minDate3 );

            minDate4 = new Date(min.getTime() + (4*86400000));
            reset_date( document.getElementById('checkin_time_3'), $('#checkin_time_3').datepicker('getDate'), minDate4 );

            minDate5 = new Date(min.getTime() + (5*86400000));
            reset_date( document.getElementById('checkout_time_3'), $('#checkout_time_3').datepicker('getDate'), minDate5 );

            minDate6 = new Date(min.getTime() + (6*86400000));
            reset_date( document.getElementById('checkin_time_4'), $('#checkin_time_4').datepicker('getDate'), minDate6 );

            minDate7 = new Date(min.getTime() + (7*86400000));
            reset_date( document.getElementById('checkout_time_4'), $('#checkout_time_4').datepicker('getDate'), minDate7 );

        }
    });
	
	$('input[name="city_name"]').attr('autocomplete','false');

    $('#checkout_time_1').datepicker({
        minDate:0,
        onSelect: function(){

            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');

            var min = $(this).datepicker('getDate');

            minDate1 = new Date(min.getTime() + 86400000);
            reset_date( document.getElementById('checkin_time_2'), $('#checkin_time_2').datepicker('getDate'), minDate1 );

            minDate2 = new Date(min.getTime() + (2*86400000));
            reset_date( document.getElementById('checkout_time_2'), $('#checkout_time_2').datepicker('getDate'), minDate2 );

            minDate3 = new Date(min.getTime() + (3*86400000));
            reset_date( document.getElementById('checkin_time_3'), $('#checkin_time_3').datepicker('getDate'), minDate3 );

            minDate4 = new Date(min.getTime() + (4*86400000));
            reset_date( document.getElementById('checkout_time_3'), $('#checkout_time_3').datepicker('getDate'), minDate4 );

            minDate5 = new Date(min.getTime() + (5*86400000));
            reset_date( document.getElementById('checkin_time_4'), $('#checkin_time_4').datepicker('getDate'), minDate5 );

            minDate6 = new Date(min.getTime() + (6*86400000));
            reset_date( document.getElementById('checkout_time_4'), $('#checkout_time_4').datepicker('getDate'), minDate6 );
        }
    });
    
    $('#checkin_time_2').datepicker({
        minDate:0,
        onSelect: function(){

            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');

            var min = $(this).datepicker('getDate') || new Date();

            minDate1 = new Date(min.getTime() + 86400000);
            reset_date( document.getElementById('checkout_time_2'), $('#checkout_time_2').datepicker('getDate'), minDate1 );

            minDate2 = new Date(min.getTime() + (2*86400000));
            reset_date( document.getElementById('checkin_time_3'), $('#checkin_time_3').datepicker('getDate'), minDate2 );
            
            minDate3 = new Date(min.getTime() + (3*86400000));
            reset_date( document.getElementById('checkout_time_3'), $('#checkout_time_3').datepicker('getDate'), minDate3 );

            minDate4 = new Date(min.getTime() + (4*86400000));
            reset_date( document.getElementById('checkin_time_4'), $('#checkin_time_4').datepicker('getDate'), minDate4 );

            minDate5 = new Date(min.getTime() + (5*86400000));
            reset_date( document.getElementById('checkout_time_4'), $('#checkout_time_4').datepicker('getDate'), minDate5 );
        }
    });
            
    $('#checkout_time_2').datepicker({
        minDate:0,
        onSelect: function(){

            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');

            var min = $(this).datepicker('getDate') || new Date();  

            minDate1= new Date(min.getTime() + 86400000);
            reset_date( document.getElementById('checkin_time_3'), $('#checkin_time_3').datepicker('getDate'), minDate1 );

            minDate2= new Date(min.getTime() + (2*86400000));
            reset_date( document.getElementById('checkout_time_3'), $('#checkout_time_3').datepicker('getDate'), minDate2 );

            minDate3 = new Date(min.getTime() + (3*86400000));
            reset_date( document.getElementById('checkin_time_4'), $('#checkin_time_4').datepicker('getDate'), minDate3 );

            minDate4 = new Date(min.getTime() + (4*86400000));
            reset_date( document.getElementById('checkout_time_4'), $('#checkout_time_4').datepicker('getDate'), minDate4 );
        }
    });
                
    $('#checkin_time_3').datepicker({
        minDate:0,
        onSelect: function(){

            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');

            var min = $(this).datepicker('getDate') || new Date();

            minDate1 = new Date(min.getTime() + 86400000);
            reset_date( document.getElementById('checkout_time_3'), $('#checkout_time_3').datepicker('getDate'), minDate1 );

            minDate2 = new Date(min.getTime() + (2*86400000));
            reset_date( document.getElementById('checkin_time_4'), $('#checkin_time_4').datepicker('getDate'), minDate2 );

            minDate3 = new Date(min.getTime() + (3*86400000));
            reset_date( document.getElementById('checkout_time_4'), $('#checkout_time_4').datepicker('getDate'), minDate3 );
        }
    });

    $('#checkout_time_3').datepicker({
        minDate:0,
        onSelect: function(){

            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');

            var min = $(this).datepicker('getDate') || new Date();

            minDate1 = new Date(min.getTime() + 86400000);
            reset_date( document.getElementById('checkin_time_4'), $('#checkin_time_4').datepicker('getDate'), minDate1 );

            minDate2 = new Date(min.getTime() + (2*86400000));
            reset_date( document.getElementById('checkout_time_4'), $('#checkout_time_4').datepicker('getDate'), minDate2 );
        }
    });
                        
    $('#checkin_time_4').datepicker({
        minDate:0,
        onSelect: function(){

            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');

            var min = $(this).datepicker('getDate') || new Date();

            minDate1 = new Date(min.getTime() + 86400000);
            reset_date( document.getElementById('checkout_time_4'), $('#checkout_time_4').datepicker('getDate'), minDate1 );
        }
    });

    $('#checkout_time_4').datepicker({
        minDate:0,
        onSelect: function(){
            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');            
        }
    });

            
// validation part

    $('#form-submit-1').bootstrapValidator({
        live: 'disabled',
        fields: {
            city_name: {
                validators: {
                    notEmpty: {
                        message: 'City Name is required'
                    }
                }
            },
            checkin_time: {
                validators: {
                    notEmpty: {
                        message: 'Check In Date is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            },
            checkout_time: {
                validators: {
                    notEmpty: {
                        message: 'Check In Date is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            }
        }
    });

    $('#form-submit-2').bootstrapValidator({
        live: 'disabled',
        fields: {
            'city_name[]': {
                validators: {
                    notEmpty: {
                        message: 'City Name is required'
                    }
                }
            },
            'checkin_time[]': {
                validators: {
                    notEmpty: {
                        message: 'Check In Date is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            },
            'checkout_time[]': {
                validators: {
                    notEmpty: {
                        message: 'Check In Date is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            }
        }
    });
    
    var mid = ($(window).height())/2;

    $('.flights-wrapper').css({
        'margin-top': 7+'%'
    });

    var panel_width = $('#accordionOccupants').width();
    $('.panel-collapse').css('width', panel_width+'px');

    $(window).on('resize', function(){
        panel_width = $('#accordionOccupants').width();
        $('.panel-collapse').css('width', panel_width+'px');
    });

    //select or die

    var ac_row_arr = $('.panel-dropdown').find('.adult-child-row-single');
    $('.adult-child-row-single').hide();

    var single_sel_ind = 1;
    var multi_sel_ind = 1;

    $('#single_rooms').selectOrDie({
        customClass: 'single_rooms',
        onChange: function(){
            occupants = 0;
            var sel_ind = parseInt($(this).val());
            single_sel_ind = sel_ind;
            $('.adult-child-row-single').hide();
            for( var j = 0 ; j < sel_ind ; j++ ){
                if( occupants_arr[j] === 0 ){
                    occupants_arr[j] = 1;
                }else{
                    occupants_arr[j] = occupants_arr[j];
                }
                occupants += occupants_arr[j];
            }
            for( var i = 1 ; i < sel_ind ; i++ ){
                ac_row_arr[i-1].style.display = 'block';
            }
            if( occupants === 1 ){
				$('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
			}else{
				$('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
			}
        }
    });

    var ac_row_arr_mul = $('.panel-dropdown').find('.adult-child-row-multi');
    $('.adult-child-row-multi').hide();

    $('#multi_rooms').selectOrDie({
        customClass: 'multi_rooms',
        onChange: function(){
            occupants = 0;
            var sel_ind = parseInt($(this).val());
            multi_sel_ind = sel_ind;
            $('.adult-child-row-multi').hide();
            for( var j = 0 ; j < sel_ind ; j++ ){
                if( occupants_arr[j] === 0 ){
                    occupants_arr[j] = 1;
                }else{
                    occupants_arr[j] = occupants_arr[j];
                }
                occupants += occupants_arr[j];
            }
            for( var i = 1 ; i < sel_ind ; i++ ){
                ac_row_arr_mul [i-1].style.display = 'block';
            }
            if( occupants === 1 ){
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });

    $('#adult_count_single-1').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < single_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#child_count_single-1').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < single_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#adult_count_single-2').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < single_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#child_count_single-2').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < single_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#adult_count_single-3').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < single_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#child_count_single-3').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < single_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#adult_count_single-4').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < single_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#child_count_single-4').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < single_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-1 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });

    $('#adult_count_multi-1').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < multi_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#child_count_multi-1').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < multi_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#adult_count_multi-2').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < multi_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#child_count_multi-2').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < multi_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#adult_count_multi-3').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < multi_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#child_count_multi-3').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < multi_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#adult_count_multi-4').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < multi_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
        }
    });
    $('#child_count_multi-4').selectOrDie({
        onChange: function(){
            var buff = parseInt($(this).val());
            occupants_calc( buff, this );
            occupants = 0;
            for( var i = 0 ; i < multi_sel_ind ; i++ ){
                occupants += occupants_arr[i];
            }
            if( occupants === 1 ){
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupant <span class="caret"></span>');
            }else{
                $('.passenger-target-2 .panel-heading-custom').html(occupants + ' Occupants <span class="caret"></span>');
            }
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

    $('.homeBGLink').on('click',function(e){
        e.preventDefault();
        var place = $(this).attr('href');
        var placeBr = place.split("~br~");
        var targ = $('.flights-nav li.active a').attr('href');
        var toField = $(targ).find('.form-control:first');
        toField.val(placeBr[0]);
        $('#typed-string-single').val(placeBr[0]);
        $('#search-string-single').val(placeBr[1]);
    });

    $(".slideLeftBtn").click(function(){
        $.vegas('previous');
    });
    
    $(".slideRightBtn").click(function(){
        $.vegas('next');
    });
});

function occupants_calc( input, element ){

    switch( element.id ){

        case 'adult_count_single-1':
            var a1 = document.getElementById('adult_count_single-1');
            var a2 = document.getElementById('child_count_single-1');
            occupants_arr[0] = parseInt(a1.options[a1.selectedIndex].value) + parseInt(a2.options[a2.selectedIndex].value);
            break;
        case 'child_count_single-1':
            var a1 = document.getElementById('adult_count_single-1');
            var a2 = document.getElementById('child_count_single-1');
            occupants_arr[0] = parseInt(a1.options[a1.selectedIndex].value) + parseInt(a2.options[a2.selectedIndex].value);
            break;
        case 'adult_count_single-2':
            var b1 = document.getElementById('adult_count_single-2');
            var b2 = document.getElementById('child_count_single-2');
            occupants_arr[1] = parseInt(b1.options[b1.selectedIndex].value) + parseInt(b2.options[b2.selectedIndex].value);
            break;
        case 'child_count_single-2':
            var b1 = document.getElementById('adult_count_single-2');
            var b2 = document.getElementById('child_count_single-2');
            occupants_arr[1] = parseInt(b1.options[b1.selectedIndex].value) + parseInt(b2.options[b2.selectedIndex].value);
            break;
        case 'adult_count_single-3':
            var c1 = document.getElementById('adult_count_single-3');
            var c2 = document.getElementById('child_count_single-3');
            occupants_arr[2] = parseInt(c1.options[c1.selectedIndex].value) + parseInt(c2.options[c2.selectedIndex].value);
            break;
        case 'child_count_single-3':
            var c1 = document.getElementById('adult_count_single-3');
            var c2 = document.getElementById('child_count_single-3');
            occupants_arr[2] = parseInt(c1.options[c1.selectedIndex].value) + parseInt(c2.options[c2.selectedIndex].value);
            break;
        case 'adult_count_single-4':
            var d1 = document.getElementById('adult_count_single-4');
            var d2 = document.getElementById('child_count_single-4');
            occupants_arr[3] = parseInt(d1.options[d1.selectedIndex].value) + parseInt(d2.options[d2.selectedIndex].value);
            break;
        case 'child_count_single-4':
            var d1 = document.getElementById('adult_count_single-4');
            var d2 = document.getElementById('child_count_single-4');
            occupants_arr[3] = parseInt(d1.options[d1.selectedIndex].value) + parseInt(d2.options[d2.selectedIndex].value);
            break;
        case 'adult_count_multi-1':
            var a1 = document.getElementById('adult_count_multi-1');
            var a2 = document.getElementById('child_count_multi-1');
            occupants_arr[0] = parseInt(a1.options[a1.selectedIndex].value) + parseInt(a2.options[a2.selectedIndex].value);
            break;
        case 'child_count_multi-1':
            var a1 = document.getElementById('adult_count_multi-1');
            var a2 = document.getElementById('child_count_multi-1');
            occupants_arr[0] = parseInt(a1.options[a1.selectedIndex].value) + parseInt(a2.options[a2.selectedIndex].value);
            break;
        case 'adult_count_multi-2':
            var b1 = document.getElementById('adult_count_multi-2');
            var b2 = document.getElementById('child_count_multi-2');
            occupants_arr[1] = parseInt(b1.options[b1.selectedIndex].value) + parseInt(b2.options[b2.selectedIndex].value);
            break;
        case 'child_count_multi-2':
            var b1 = document.getElementById('adult_count_multi-2');
            var b2 = document.getElementById('child_count_multi-2');
            occupants_arr[1] = parseInt(b1.options[b1.selectedIndex].value) + parseInt(b2.options[b2.selectedIndex].value);
            break;
        case 'adult_count_multi-3':
            var c1 = document.getElementById('adult_count_multi-3');
            var c2 = document.getElementById('child_count_multi-3');
            occupants_arr[2] = parseInt(c1.options[c1.selectedIndex].value) + parseInt(c2.options[c2.selectedIndex].value);
            break;
        case 'child_count_multi-3':
            var c1 = document.getElementById('adult_count_multi-3');
            var c2 = document.getElementById('child_count_multi-3');
            occupants_arr[2] = parseInt(c1.options[c1.selectedIndex].value) + parseInt(c2.options[c2.selectedIndex].value);
            break;
        case 'adult_count_multi-4':
            var d1 = document.getElementById('adult_count_multi-4');
            var d2 = document.getElementById('child_count_multi-4');
            occupants_arr[3] = parseInt(d1.options[d1.selectedIndex].value) + parseInt(d2.options[d2.selectedIndex].value);
            break;
        case 'child_count_multi-4':
            var d1 = document.getElementById('adult_count_multi-4');
            var d2 = document.getElementById('child_count_multi-4');
            occupants_arr[3] = parseInt(d1.options[d1.selectedIndex].value) + parseInt(d2.options[d2.selectedIndex].value);
            break;
        default:
            alert('invalid');
    }

    return true;
}
</script>