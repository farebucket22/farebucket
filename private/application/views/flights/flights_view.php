<style>
    .specialFlightArea{
        color:#000;
    }
</style>
<div class="wrap">
    <div class="container-fluid flights-wrapper clear-top">
        <div class="row"><div class="col-lg-24 center-align-text"><h1 class='marketingMessage'>Search for Flights</h1></div></div>
        <!-- tabs -->
        <ul class="nav nav-tabs flights-nav" role="tablist">
            <li class="active"><a href="#oneWay" role="tab" data-toggle="tab">One Way</a></li>
            <li><a href="#roundTrip" role="tab" data-toggle="tab">Round Trip</a></li>
            <li><a href="#multiCity" role="tab" data-toggle="tab">Multi-City</a></li>
        </ul>
        <!-- tabs end -->
        <!-- tab contents, insert within tab-content -->
        <div class="tab-content change-height">
            <div class="tab-pane pane-height fade in active" id="oneWay">
                <div class="row autocompleteErrorBlock" style="display:none;">
                    <center><small>Please Select the Source/Destination from the Autocomplete dropdown</small></center>
                </div>
                <form action="<?php echo site_url('flights/oneway');?>" method="get" id="form-submit-1" enctype="multipart/form-data">
                    <div class="form-padding row">
                        <div class="form-group">
                            <div class="col-lg-6 control-label">
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="1" autocomplete="off" name="from" placeholder="Source">
                            </div>
                        </div>
                        <div class="col-lg-1 center-align-text">                            
                            <div class="swap" title="Swap From and To fields"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 control-label">
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="2" autocomplete="off" name="to" placeholder="Destination">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel-group" id="accordion1">
                                <div class="panel panel-default">
                                    <a class="btn-custom passenger-target-1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
                                        <div class="panel-heading-custom">
                                            1 Adult <span class="caret"></span>
                                        </div>
                                    </a>
                                    <div id="collapseOne" class="panel-collapse panel-collapse-custom collapse">
                                        <div class="panel-body panel-dropdown">
                                            <div class="group-adult-1 center-text row">
                                                <div class="col-lg-10">
                                                    <span>Adults</span>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="adult-minus-1"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="adult_count" id="adult-text-1" value="1" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="adult-plus-1"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-youth-1 center-text row">
                                                <div    class="col-lg-10">
                                                    <span>Children</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>2 years - 12 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="youth-minus-1"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="youth_count" id="youth-text-1" value="0" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="youth-plus-1"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-kids-1 center-text row">
                                                <div class="col-lg-10">
                                                    <span>Infants</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>under 2 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="kids-minus-1"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="kids_count" id="kids-text-1" value="0" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="kids-plus-1"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                                <a class="btn btn-change-cls" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">close</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-5">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="oneway_date" id="date-1" readonly class="form-control" type="text" placeholder="Depart Date" autocomplete="off" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="option-bottom-tab search-padding row">
                        <div class="col-lg-18 more-options-link-1">
                            <a href="#" id="more-options-1"><span class="glyphicon glyphicon-plus-sign"></span> More Options</a>
                        </div>
                        <div class="col-lg-6 more-options-1" style="display:none;">
                            <input type="text" class="form-control airlinePreference" name="airline_preference_1" placeholder="Airline Preference" />
                        </div>
                        <div class="col-lg-6 more-options-1" style="display:none;">
                            <select name="class_of_travel_1" id="class-of-travel">
                                <option value="All">Class of Travel</option>
                                <option value="All">All</option>
                                <option value="Economy">Economy</option>
                                <option value="PremiumEconomy">Premium Economy</option>
                                <option value="Business">Business</option>
                                <option value="PremiumBusiness">Premium Business</option>
                                <option value="First">First</option>
                            </select>
                        </div>
                        <div class="col-lg-6 more-options-1 less-options-link-1" style="display:none;">
                            <a href="#" id="less-options-1"><span class="glyphicon glyphicon-minus-sign"></span> Less Options</a>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="col-lg-5 remove-padding">
                            <button id="form-submit-button" type="button"><span class="glyphicon glyphicon-search"></span> SEARCH</button>
                        </div>
                    </div>
                    <input type="text" name="flight_type" value="OneWay" style="display:none;" readonly />
                </form>
            </div>
            <div class="tab-pane pane-height fade" id="roundTrip">
                <div class="row autocompleteErrorBlock" style="display:none;">
                    <center><small>Please Select the Source/Destination from the Autocomplete dropdown</small></center>
                </div>
                <form action="<?php echo site_url('api/flights/return_parameters');?>" method="get" id="form-submit-2" enctype="multipart/form-data">
                    <div class="form-padding row">
                        <div class="form-group">
                            <div class="col-lg-6 control-label">
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="3" autocomplete="off" name="from" placeholder="Source">
                            </div>
                        </div>
                        <div class="col-lg-1 center-align-text">                            
                            <div class="swap" title="Swap From and To fields"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 control-label">
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="4" autocomplete="off" name="to" placeholder="Destination">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel-group" id="accordion2">
                                <div class="panel panel-default">
                                    <a class="btn-custom passenger-target-2" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                                        <div class="panel-heading-custom">
                                            1 Adult <span class="caret"></span>
                                        </div>
                                    </a>
                                    <div id="collapseTwo" class="panel-collapse panel-collapse-custom collapse">
                                        <div class="panel-body panel-dropdown">
                                            <div class="group-adult-2 center-text row">
                                                <div class="col-lg-10">
                                                    <span>Adults</span>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="adult-minus-2"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="adult_count" id="adult-text-2" value="1" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="adult-plus-2"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-youth-2 center-text row">
                                                <div class="col-lg-10">
                                                    <span>Children</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>2 years - 12 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="youth-minus-2"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="youth_count" id="youth-text-2" value="0" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="youth-plus-2"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-kids-2 center-text row">
                                                <div class="col-lg-10">
                                                    <span>Infants</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>under 2 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="kids-minus-2"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="kids_count" id="kids-text-2" value="0" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="kids-plus-2"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <a class="btn btn-change-cls" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">close</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-5 control-label">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="to_date" id="date-2" readonly class="form-control" type="text" autocomplete="off" placeholder="Depart Date" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-padding row">
                        <div class="form-group">
                            <div class="pull-right col-lg-5 control-label">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="from_date" id="date-3" readonly class="form-control" type="text"  autocomplete="off" placeholder="Return Date" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="option-bottom-tab search-padding row">
                        <div class="col-lg-18 more-options-link-2">
                            <a href="#" id="more-options-2"><span class="glyphicon glyphicon-plus-sign"></span> More Options</a>
                        </div>
                        <div class="col-lg-6 more-options-2" style="display:none;">
                            <input type="text" class="form-control airlinePreference" name="airline_preference_2" placeholder="Airline Preference" />
                        </div>
                        <div class="col-lg-6 more-options-2" style="display:none;">
                            <select name="class_of_travel_2" id="class-of-travel">
                                <option value="All">Class of Travel</option>
                                <option value="All">All</option>
                                <option value="Economy">Economy</option>
                                <option value="PremiumEconomy">Premium Economy</option>
                                <option value="Business">Business</option>
                                <option value="PremiumBusiness">Premium Business</option>
                                <option value="First">First</option>
                            </select>
                        </div>
                        <div class="col-lg-6 more-options-2 less-options-link-2" style="display:none;">
                            <a href="#" id="less-options-2"><span class="glyphicon glyphicon-minus-sign"></span> Less Options</a>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="pull-right col-lg-5 remove-padding">
                            <button id="form-submit-button" type="button"><span class="glyphicon glyphicon-search"></span> SEARCH</button>
                        </div>
                    </div>
                    <input type="text" name="flight_type" value="Return" style="display:none;" readonly />
                </form>
            </div>
            <div class="tab-pane pane-height fade" id="multiCity">
                <div class="row autocompleteErrorBlock" style="display:none;">
                    <center><small>Please Select the Source/Destination from the Autocomplete dropdown</small></center>
                </div>
                <form action="<?php echo site_url('api/flights/test_multi');?>" method="get" id="form-submit-3" enctype="multipart/form-data">
                    <div class="row form-padding">
                        <div class="col-lg-1 col-padding destination-text"></div>
                        <div class="col-lg-5 col-padding destination-text">
                            <span>Destination 1</span>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">

                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="5" autocomplete="off" name="city_from_one" placeholder="Source">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">

                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="6" autocomplete="off" name="city_to_one" placeholder="Destination">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="multi_date_one" id="date-4" readonly class="form-control" type="text" autocomplete="off" placeholder="Depart Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-padding">
                        <div class="col-lg-1 col-padding destination-text"></div>
                        <div class="col-lg-5 col-padding destination-text">
                            <span>Destination 2</span>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">

                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="7" autocomplete="off" name="city_from_two" placeholder="Source">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">

                                    <input type="text" onClick="this.select();" class="form-control searchBox" data-num="8" autocomplete="off" name="city_to_two" placeholder="Destination">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="multi_date_two" id="date-5" readonly class="form-control" type="text" autocomplete="off" placeholder="Depart Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clonedInput">
                    <!--this section adds a new flight destination into the form-->
                        <div class="clone">
                            <div class="row form-padding" id="flight_form_2">
                                <div class="col-lg-1 col-padding destination-text">
                                    <a href="#" class="btnDel"><span class="glyphicon glyphicon-minus-sign"></span>
                                    </a>
                                </div>
                                <div class="col-lg-5 col-padding destination-text"><span>Destination 3</span>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-padding control-label">

                                        <input type="text" onClick="this.select();" class="form-control searchBox" data-num="9" autocomplete="off" name="city_from_three"
                                        placeholder="Source">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-padding control-label">

                                        <input type="text" onClick="this.select();" class="form-control searchBox" data-num="10" autocomplete="off" name="city_to_three"
                                        placeholder="Destination">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-padding control-label">
                                        <div class="inner-addon right-addon"><i class="glyphicon"></i>
                                            <input name="multi_date_three" id="date-6" readonly class="form-control" type="text" autocomplete="off" placeholder="Depart Date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clone">
                            <div class="row form-padding" id="flight_form_3">
                                <div class="col-lg-1 col-padding destination-text">
                                    <a href="#" class="btnDel"><span class="glyphicon glyphicon-minus-sign"></span>
                                    </a>
                                </div>
                                <div class="col-lg-5 col-padding destination-text"><span>Destination 4</span>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-padding control-label">

                                        <input type="text" onClick="this.select();" class="form-control searchBox" data-num="11" autocomplete="off" name="city_from_four"
                                        placeholder="Source">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-padding control-label">

                                        <input type="text" onClick="this.select();" class="form-control searchBox" data-num="12" autocomplete="off" name="city_to_four"
                                        placeholder="Destination">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-padding control-label">
                                        <div class="inner-addon right-addon"><i class="glyphicon"></i>
                                            <input name="multi_date_four" id="date-7" readonly class="form-control" type="text" autocomplete="off" placeholder="Depart Date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 add-flight-link"><a href="#" id="btnAdd"><span class="glyphicon glyphicon-plus-sign"></span> Add Destination</a></div>
                        <div class="col-lg-18"></div>
                    </div>
                    <div class="row form-padding">
                        <div class="pull-right col-lg-6 col-padding">
                            <div class="panel-group" id="accordion3">
                                <div class="panel panel-default">
                                    <a class="btn-custom passenger-target-3" data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">
                                        <div class="panel-heading-custom">
                                            1 Adult <span class="caret"></span>
                                        </div>
                                    </a>
                                    <div id="collapseThree" class="panel-collapse panel-collapse-custom collapse">
                                        <div class="panel-body panel-dropdown">
                                            <div class="group-adult-3 center-text row">
                                                <div class="col-lg-10">
                                                    <span>Adults</span>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="adult-minus-3"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="adult_count" id="adult-text-3" value="1" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="adult-plus-3"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-youth-3 center-text row">
                                                <div class="col-lg-10">
                                                    <span>Children</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>2 years - 12 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="youth-minus-3"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="youth_count" id="youth-text-3" value="0" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="youth-plus-3"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-kids-3 center-text row">
                                                <div class="col-lg-10">
                                                    <span>Infants</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>under 2 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="kids-minus-3"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="kids_count" id="kids-text-3" value="0" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="kids-plus-3"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <a class="btn btn-change-cls" data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">close</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row multiwayFlightDeleteErrorBlock" style="display:none;">
                        <center><small>Cannot delete! Please delete the last entry first</small></center>
                    </div>
                    <div class="option-bottom-tab search-padding row">
                        <div class="col-lg-18 more-options-link-3">
                            <a href="#" id="more-options-3"><span class="glyphicon glyphicon-plus-sign"></span> More Options</a>
                        </div>
                        <div class="col-lg-6 more-options-3" style="display:none;">
                            <input type="text" class="form-control airlinePreference" name="airline_preference_3" placeholder="Airline Preference">
                        </div>
                        <div class="col-lg-6 more-options-3" style="display:none;">
                            <select name="class_of_travel_3" id="class-of-travel">
                                <option value="All">Class of Travel</option>
                                <option value="All">All</option>
                                <option value="Economy">Economy</option>
                                <option value="PremiumEconomy">Premium Economy</option>
                                <option value="Business">Business</option>
                                <option value="PremiumBusiness">Premium Business</option>
                                <option value="First">First</option>
                            </select>
                        </div>
                        <div class="col-lg-6 more-options-3 less-options-link-3" style="display:none;">
                            <a href="#" id="less-options-3"><span class="glyphicon glyphicon-minus-sign"></span> Less Options</a>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="col-lg-5 remove-padding">
                            <button id="form-submit-button" type="button"><span class="glyphicon glyphicon-search"></span> SEARCH</button>
                        </div>
                    </div>
                    <input name="count_flights" value="1" style="display:none;" readonly />
                    <input type="text" name="flight_type" value="OneWay" style="display:none;" readonly />
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
<script type="text/javascript" src="<?php echo base_url('js/vendor/vegas/jquery.vegas.js'); ?>"></script>
<script type="text/javascript" >
    function reset_date( html_object, selected_date, min_date ){
        if( min_date > selected_date ){
            html_object.datepicker('option', 'minDate', min_date);
            html_object.datepicker('option', 'maxDate', '+1Y');
            html_object.datepicker('setDate', min_date);
            return;
        }else{
            return;
        }
    }

$(document).ready(function(){
        var myArr = [];
        var mainArr = [];
        var temp = {};
        var cityCodes = [];
        var airports = [];
        var myArr1 = [];
        var mainArr1 = [];
        var temp1 = {};
        var airline = [];

        $.ajax({
            type: "GET",
            url: "../scripts/airport-codes.xml", // change to full path of file on server
            dataType: "xml",
            success: function(xml){
                temp = xmlToJson(xml);
                myArr = temp.iata.iata_airport_codes;
            }
        });

        $.ajax({
            type: "GET",
            url: "../scripts/Airline.xml", // change to full path of file on server
            dataType: "xml",
            success: function(xml){
                temp1 = xmlToJson(xml);
                myArr1 = temp1.Records.Record;
            }
        });
// add a city in multi city part

        var clicks_index = 1;
        var c = $('.clonedInput');
        c.find('.clone').hide();

        $('#btnAdd').click(function(e) {
            e.preventDefault();

            $('.flights-wrapper').animate({
                'margin-top': (mid-315)+'px'
            }, 300, "linear");

            $('input[name=count_flights]').val(clicks_index+1);
            
            if( clicks_index == 1 ) {
                c.find('.clone:first').slideDown('300');
                clicks_index++;
            } else if( clicks_index == 2 ){
                c.find('.btnDel:first').removeClass().addClass('non-clickable');
                c.find('.clone:last').slideDown('300');
                clicks_index++;
                $('#btnAdd').hide();
            } else {
                return false;
            }
        });


        c.on( 'click', '.non-clickable', function( e ) {
            e.preventDefault();
            $('.multiwayFlightDeleteErrorBlock').fadeIn('100');
            setTimeout(function(){
                $('.multiwayFlightDeleteErrorBlock').fadeOut('100');
            }, 4000);
            return false;
        });

        c.on('click', '.btnDel', function(e){
            e.preventDefault();
            --clicks_index;

            if( clicks_index == 1 ){
                $('#btnAdd').show();
                $('.flights-wrapper').animate({
                    'margin-top': (mid-305)+'px'
                }, 300, "easeOutCirc");  
            }

            $('input[name=count_flights]').val(clicks_index);
            if( clicks_index == 2 ){
                $('#btnAdd').show();
                c.find('.non-clickable:first').removeClass().addClass('btnDel');
            }

            $(this).closest('.clone').slideUp('300');
        });

//end multi city add

//swap from and to field oneway and return
        $('.swap').on('click', function(){
            $(this).addClass('rotate-easeOutQuad');
        });
        $(".swap").on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', 
            function() {
            $(this).removeClass('rotate-easeOutQuad');
            var ref = $('.flights-nav li.active a').attr('href');
            var temp = $(ref+' input[name=from]').val();
            $(ref+' input[name=from]').val($(ref+' input[name=to]').val());
            $(ref+' input[name=to]').val(temp);
        });
//swap from and to field oneway and return end

        var opd = 0;
        var search_ele = 0;
        var flag_index1 = 0;
        var opd1 = 0;

        $(".searchBox").on('keyup', function( e ){
            var searchText = $(this).val().toLowerCase();
            
            airports = [];

            if( searchText.length >= 3 )
            {
                $.each( myArr, function( i, ar )
                {  
                    if((ar.airport.valueText.toLowerCase().indexOf(searchText) > -1) || (ar.codes.valueText.toLowerCase().indexOf(searchText) > -1 ))
                    {
                        airports.push(ar.airport.valueText+' ('+ar.codes.valueText+')');
                        selected_city = ar.codes.valueText;
                    }
                    
                });
            }

            if( e.keyCode === 8 || e.keyCode === 46){
                search_ele = 0;
            }

            if( search_ele !== $(this).data('num') ){
                $(this).attr('id', 'flaged');
                search_ele = $(this).data('num');
            }

            availableTags = airports;
            var NoResultsLabel = 'No results found.';
               
            $(this).autocomplete({
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
                minLength: 3,
                open: function( event, ui ) {
                    opd = 1;
                },
                select: function( event, ui ) {

                    // When the enterkey is pressed, the autocomplete propogation is stopped, 
                    // all search boxes are found and the search box which is next to the current search box is focused.
                    // The next search box is chosen with the data-num attribute.
                    var originalEvent = event;
                    var next_ele = search_ele + 1;
                    while (originalEvent) {
                        if (originalEvent.keyCode == 13){
                            originalEvent.stopPropagation();
                            $.each(all_ios, function(i, val){
                                if( next_ele == $(val).data('num') ){
                                    if( next_ele === 7 || next_ele === 9 || next_ele === 11 ){
                                        $(all_ios[next_ele]).focus();
                                        $(val).attr("id", "");
                                    }else{
                                        $(val).focus();
                                    }
                                }
                            });
                        }

                        if (originalEvent == event.originalEvent)
                            break;

                        originalEvent = event.originalEvent;
                    }

                    if (ui.item.label === NoResultsLabel) {
                        event.preventDefault();
                        return false;
                    }

                    if( event.target.name === 'from' || event.target.name === 'city_from_one' ){
                        $('input.searchBox[name=from]').each(function(i, val){
                            val.value = ui.item.value;
                        });
                    }

                    if( event.target.name === 'to' || event.target.name === 'city_to_one'){
                        $('input.searchBox[name=to]').each(function(i, val){
                            val.value = ui.item.value;
                        });
                    }

                    $(this).val(ui.item.value);
                    if( opd === 1 ){
                        opd = 0;
                        $(this).attr('id', '');
                        return true;
                    }
                },
                focus: function (event, ui) {
                    if (ui.item.label === NoResultsLabel) {
                        event.preventDefault();
                        return false;
                    }
                }
            });
        });

        $('.searchBox').on('paste cut', function(){
            $(this).attr('id', 'flaged');
        });

        $(".airlinePreference").on('keyup', function( e ){
            var searchText1 = $(this).val().toLowerCase();
            
            airlines = [];

            if( searchText1.length >= 2 )
            {
                $.each( myArr1, function( i, ar )
                {  
                    if((ar.AirlineCode.valueText.toLowerCase().indexOf(searchText1) > -1) || (ar.AirlineName.valueText.toLowerCase().indexOf(searchText1) > -1 ))
                    {
                        airlines.push(ar.AirlineName.valueText + '-' + ar.AirlineCode.valueText);
                        selected_airline = ar.AirlineName.valueText;
                    }
                    
                });
            }

            if( flag_index1 === 0 ){
                $(this).attr('id', 'flaged');
                flag_index1++;
            }

            availableTags = airlines;
            var NoResultsLabel = 'No results found.';
                                 
            $(this).autocomplete({
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
                minLength: 2,
                open: function( event, ui ) {
                    opd1 = 1;
                },
                select: function( event, ui ) {

                    if (ui.item.label === NoResultsLabel) {
                        event.preventDefault();
                        return false;
                    }

                    $(this).val(ui.item.value);
                    if( opd1 === 1 ){
                        $(this).attr('id', '');
                        return true;
                    }
                },
                focus: function (event, ui) {
                    if (ui.item.label === NoResultsLabel) {
                        event.preventDefault();
                        return false;
                    }
                }
            });
        });


        // Changes XML to JSON
        function xmlToJson(xml) 
        {
            // Create the return object
            var obj = {};

            if (xml.nodeType == 1)
            { // element
                // do attributes
                if (xml.attributes.length > 0) 
                {
                    obj["@attributes"] = {};
                    for (var j = 0; j < xml.attributes.length; j++) 
                    {
                        var attribute = xml.attributes.item(j);
                        obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
                    }
                }
            } 
            else if (xml.nodeType == 3) 
            { // text
                obj = xml.nodeValue;
            }

            // do children
            if (xml.hasChildNodes()) 
            {
                for(var i = 0; i < xml.childNodes.length; i++) 
                {
                    var item = xml.childNodes.item(i);
                    var nodeName = item.nodeName;
                    if(nodeName === '#text' || nodeName === '')
                    {
                        nodeName = 'valueText';
                    }
                    if (typeof(obj[nodeName]) == "undefined") 
                    {
                        obj[nodeName] = xmlToJson(item);
                    } else {
                        if (typeof(obj[nodeName].push) == "undefined") 
                        {
                            var old = obj[nodeName];
                            obj[nodeName] = [];
                            obj[nodeName].push(old);
                        }
                        obj[nodeName].push(xmlToJson(item));
                    }
                }
            }
            return obj;
        }

    var backgrounds_images = <?php echo json_encode($data);?>;
    $.vegas('slideshow', {
           backgrounds:[
               {     
                src:'<?php echo base_url("img/activities/'+backgrounds_images[0].image+'"); ?>', fade:1000,
                   load:function() {
                        $(".homeBGLink").attr('href', backgrounds_images[0].image_url);
                        $(".specialFlightMessage").html(backgrounds_images[0].image_text);
                   }
               },
               { src:'<?php echo base_url("img/activities/'+backgrounds_images[1].image+'"); ?>', fade:1000,
                   load:function() {
                    $(".homeBGLink").attr('href', backgrounds_images[1].image_url);
                       $(".specialFlightMessage").html(backgrounds_images[1].image_text);
                   }
               },
               { src:'<?php echo base_url("img/activities/'+backgrounds_images[2].image+'"); ?>', fade:1000,
                   load:function() {
                    $(".homeBGLink").attr('href', backgrounds_images[2].image_url);
                       $(".specialFlightMessage").html(backgrounds_images[2].image_text);
                   }
               }
           ]
    });

    $('.homeBGLink').on('click', function(e){
        e.preventDefault();
        var targ = $('.flights-nav li.active a').attr('href');
        var toField = $(targ).find('.searchBox:last');
        toField.val($(this).attr('href'));
    });
    
    $(".slideLeftBtn").click(function(){
        $.vegas('previous');
    });
    
    $(".slideRightBtn").click(function(){
        $.vegas('next');
    });

    // var page_url = document.URL;
    // var n = page_url.lastIndexOf("/");
    // var len = page_url.lastIndexOf("s");
    // var active_page = page_url.slice(n+1, len+1);
    // $("li#"+active_page).addClass('activated').siblings().removeClass('activated');
    var edit_chk = 0;
    var all_ios = $(document).find('.searchBox');
    var all_aps = $(document).find('.airlinePreference');

    $(document).on( 'click', '#form-submit-button', function( e ){
        e.preventDefault();

        $('this').attr('disabled', true);
        $.each( all_ios, function( i, ios ){
            if( ios.id === "flaged" ){
                edit_chk++;
            }

            switch( ios.value ){
                case 'Madras, India  (MAA)':
                    ios.value = 'Chennai, India  (MAA)';
                    break;
                case 'Bombay, India  (BOM)':
                    ios.value = 'Mumbai, India  (BOM)';
                    break;
                case 'Calcutta, India  (CCU)':
                    ios.value = 'Kolkata, India  (CCU)';
                    break;
                default: 
                    ios.value = ios.value;
                    break;
            }

        });
        $.each( all_aps, function( i, aps ){
            if( aps.id === "flaged" ){
                aps.value = "";
            }else{
                return true;
            }
        });
        if( edit_chk > 0 ){
            edit_chk = 0;
            var form_object = $(this).closest('form');
            form_object.data('bootstrapValidator').validate();
            $('.autocompleteErrorBlock').fadeIn('100');
            setTimeout(function(){
                $('.autocompleteErrorBlock').fadeOut('100');
            }, 4000);
            return false;
        }else{
            var form_object = $(this).closest('form');
            form_object.data('bootstrapValidator').validate();
            if( form_object.data('bootstrapValidator').isValid() ){
                form_object.get(0).submit();
            }else{
                return false;
            }
        }
    });

//select or die
    $('select').selectOrDie({
        placeholderOption: true,
    });

// datepicker

    var return_date3 = 0;
    var return_date4 = 0;
    var return_date5 = 0;
    var set_date2 = 0;

    $.datepicker.setDefaults({
        dateFormat: "dd-mm-yy"
    });

    $('#date-1').datepicker({
        minDate: 0,
        maxDate: '+1Y',
        onSelect: function(){
            set_date2 = $(this).datepicker('getDate') || new Date();
            var sm = $('#form-submit-1').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
            $('#date-2').datepicker('setDate', set_date2);
        }
    });

    $('#date-2').datepicker({
        minDate: 0,
        maxDate: '+1Y',
        onSelect: function(dateText, inst){
            var min = $(this).datepicker('getDate') || new Date();
            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
            $('#date-1').datepicker('setDate', min);
            $('#date-3').datepicker('option', 'minDate', min);
        }
    });

    $('#date-3').datepicker({
        minDate: 0,
        maxDate: '+1Y',
        onSelect: function (dateText, inst) {
            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
        }
    });


    $('#date-4').datepicker({
        minDate:0,  
        maxDate: '+1Y',
        onSelect: function(){
            var sm = $('#form-submit-3').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
            var min = $(this).datepicker('getDate') || new Date();
            reset_date( $('#date-1'), $('#date-1').datepicker('getDate'), min );
            reset_date( $('#date-2'), $('#date-2').datepicker('getDate'), min );
            reset_date( $('#date-5'), $('#date-5').datepicker('getDate'), min );
            reset_date( $('#date-6'), $('#date-6').datepicker('getDate'), min );
            reset_date( $('#date-7'), $('#date-7').datepicker('getDate'), min );
        }
    });

    $('#date-5').datepicker({
        minDate:0,
        maxDate: '+1Y',
        onSelect: function(){
            var sm = $('#form-submit-3').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
            var min = $(this).datepicker('getDate') || new Date();
            reset_date( $('#date-6'), $('#date-6').datepicker('getDate'), min );
            reset_date( $('#date-7'), $('#date-7').datepicker('getDate'), min );
        }
    });

    $('#date-6').datepicker({
        minDate:0,
        maxDate: '+1Y',
        onSelect: function(){
            var sm = $('#form-submit-3').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
            var min = $(this).datepicker('getDate') || new Date();
            reset_date( $('#date-7'), $('#date-7').datepicker('getDate'), min );
        }
    });

    $('#date-7').datepicker({
        minDate:0,
        maxDate: '+1Y',
        onSelect: function(){
            var sm = $('#form-submit-3').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
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
                    },
                    different: {
                        field: 'to',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            to: {
                validators: {
                    notEmpty: {
                        message: 'To is required'
                    },
                    different: {
                        field: 'from',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            oneway_date: {
                validators: {
                    notEmpty: {
                        message: 'Date is required'
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
            from: {
                validators: {
                    notEmpty: {
                        message: 'From is required'
                    },
                    different: {
                        field: 'to',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            to: {
                validators: {
                    notEmpty: {
                        message: 'To is required'
                    },
                    different: {
                        field: 'from',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            from_date: {
                validators: {
                    notEmpty: {
                        message: 'From Date is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            },
            to_date: {
                validators: {
                    notEmpty: {
                        message: 'To Date is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            }
        }
    });

    $('#form-submit-3').bootstrapValidator({
        live: 'disabled',
        fields: {
            city_from_one: {
                validators: {
                    notEmpty: {
                        message: 'From is required'
                    },
                    different: {
                        field: 'city_to_one',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            city_to_one: {
                validators: {
                    notEmpty: {
                        message: 'To is required'
                    },
                    different: {
                        field: 'city_from_one',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            city_from_two: {
                validators: {
                    notEmpty: {
                        message: 'From is required'
                    },
                    different: {
                        field: 'city_to_two',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            city_to_two: {
                validators: {
                    notEmpty: {
                        message: 'To is required'
                    },
                    different: {
                        field: 'city_from_two',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            city_from_three: {
                validators: {
                    notEmpty: {
                        message: 'From is required'
                    },
                    different: {
                        field: 'city_to_three',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            city_to_three: {
                validators: {
                    notEmpty: {
                        message: 'To is required'
                    },
                    different: {
                        field: 'city_from_three',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            city_from_four: {
                validators: {
                    notEmpty: {
                        message: 'From is required'
                    },
                    different: {
                        field: 'city_to_four',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            city_to_four: {
                validators: {
                    notEmpty: {
                        message: 'To is required'
                    },
                    different: {
                        field: 'city_from_four',
                        message: 'Please choose different Sources and Destinations.'
                    }
                }
            },
            multi_date_one: {
                validators: {
                    notEmpty: {
                        message: 'First Date is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            },
            multi_date_two: {
                validators: {
                    notEmpty: {
                        message: 'Second Date is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            },
            multi_date_three: {
                validators: {
                    notEmpty: {
                        message: 'Third Date is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            },
            multi_date_four: {
                validators: {
                    notEmpty: {
                        message: 'Fourth Date is required'
                    },
                    date: {
                        format: 'DD-MM-YYYY',
                        message: 'Please check the Date Format.'
                    }
                }
            }
        }
    });
// validation part end
    
    var mid = ($(window).height())/2;

    $('.flights-wrapper').css({
        'margin-top': (mid-260)+'px'
    });

// mutliway tabclick check
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

            //change margin top for tabs
            if( e.target.text === "Round Trip"){
                $('.specialFlightArea').css('visibility', 'visible');
                $('.flights-wrapper').animate({
                    'margin-top': (mid-280)+'px'
                }, 300, "linear");
            }

            if( e.target.text === "One Way"){
                $('.specialFlightArea').css('visibility', 'visible');
                $('.flights-wrapper').animate({
                    'margin-top': (mid-260)+'px'
                }, 300, "linear");
            }

            //check if activated tab is multi city.
            if( e.target.text === "Multi-City" ){
                $('.specialFlightArea').css('visibility', 'hidden');
                $('.flights-wrapper').animate({
                    'margin-top': (mid-290)+'px'
                }, 300, "linear");
            }
        });
// mutliway tabclick check end

    $('#more-options-1').on('click', function(e){
        e.preventDefault();
        $('.more-options-link-1').hide();
        $('.more-options-1').show();
    });

    $('#less-options-1').on('click', function(e){
        e.preventDefault();
        $('.more-options-link-1').show();
        $('.more-options-1').hide();
    });

    $('#more-options-2').on('click', function(e){
        e.preventDefault();
        $('.more-options-link-2').hide();
        $('.more-options-2').show();
    });

    $('#less-options-2').on('click', function(e){
        e.preventDefault();
        $('.more-options-link-2').show();
        $('.more-options-2').hide();
    });

    $('#more-options-3').on('click', function(e){
        e.preventDefault();
        $('.more-options-link-3').hide();
        $('.more-options-3').show();
    });

    $('#less-options-3').on('click', function(e){
        e.preventDefault();
        $('.more-options-link-3').show();
        $('.more-options-3').hide();
    });

    var check_input = $('input[type=text]');

    if(check_input.val() === 0){
        check_input.next('button').attr('disabled', 'disabled');
    }

    var panel_width = $('#accordion1').width();
    $('.panel-collapse').css('width', panel_width+'px');

    $(window).on('resize', function(){
        panel_width = $('#accordion1').width();
        $('.panel-collapse').css('width', panel_width+'px');
    });

//multi flight auto select
    $('input[name = city_to_one]').on('blur', function(){
        $('input[name = city_from_two]').val($(this).val());
    });

    $('input[name = city_to_two]').on('blur', function(){
        $('input[name = city_from_three]').val($(this).val());
    });

    $('.clonedInput').on('blur', 'input[name = city_to_three]' , function(){
        $('input[name = city_from_four]').val($(this).val());
    });


//passenger calculation

    var inputArray = { 
        adult1: 1,
        adult2: 1,
        adult3: 1,
        youth1: 0,
        youth2: 0,
        youth3: 0,
        kids1: 0,
        kids2: 0,
        kids3: 0,
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

        if( inputArray['adult'+buttonArray[2]] + inputArray['youth'+buttonArray[2]]  === 9 ){
            if( buttonArray[0] != 'kids'){
                return false;
            }
        }

        $(this).siblings('button').removeAttr('disabled');

        if( inputArray['adult'+buttonArray[2]] <= inputArray['kids'+buttonArray[2]] ){
            if( buttonArray[0] == 'kids' ){
                return false;
            }
        }

        $("#"+target+"-text-"+index).val(++inputArray[i]);
        $("#total"+"-"+buttonArray[2]).val(++inputArray[j]);
        $(".passenger-target-"+buttonArray[2]+" .panel-heading-custom").html(""+inputArray[j]+" Passengers <span class='caret'></span>");
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
            $(this).attr("disabled", "disabled");
            return false;
        }

        $("#"+target+"-text-"+index).val(--inputArray[i]);
        $("#total"+"-"+buttonArray[2]).val(--inputArray[j]);

        if( inputArray['adult'+buttonArray[2]] <= inputArray['kids'+buttonArray[2]] ){
            if( buttonArray[0] === 'adult' ){
                $('#kids-text-'+buttonArray[2]).val($('#adult-text-'+buttonArray[2]).val());
                inputArray['kids'+buttonArray[2]] = inputArray['adult'+buttonArray[2]];
                inputArray[j] = inputArray['kids'+buttonArray[2]] + inputArray['adult'+buttonArray[2]] + inputArray['youth'+buttonArray[2]];
            } else if( buttonArray[0] === 'youth' ){
                $(".passenger-target-"+buttonArray[2]+" .panel-heading-custom").html(""+inputArray[j]+" Passengers <span class='caret'></span>");
            } else {
                return false;
            }
        }

        $(".passenger-target-"+buttonArray[2]+" .panel-heading-custom").html(""+inputArray[j]+" Passengers <span class='caret'></span>");
    });
});
</script>