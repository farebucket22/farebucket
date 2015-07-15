<?php 
    if( isset($_GET['flight_num']) ){
          if( !isset($_SESSION['details']['dp_dates']) ){
            foreach( $_SESSION['details']['dates'] as $da ){
                $_SESSION['details']['dp_dates'][] = date('c', strtotime($da));
            }
        }
    }
	if( isset($_GET['class_of_travel_1']) ){
        $class = $_GET['class_of_travel_1'];
    }
	else if( isset($_GET['class_of_travel_2']) ){
		$class = $_GET['class_of_travel_2'];
	}
	else if( isset($_GET['class_of_travel_3']) ){
		$class = $_GET['class_of_travel_3'];
	}
    else{
        $class = "";
    }
?>
<?php $total_count = intval($_GET['adult_count']) + intval($_GET['youth_count']) + intval($_GET['kids_count']);?>
<div class="search-wrapper container">
        <!-- tabs -->
        <ul class="nav nav-tabs" role="tablist" id="flightsTab">
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
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="1" name="from" placeholder="From" value="">
                                <input type="hidden" name="utf_from" class="utf" value="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 control-label">
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="2" name="to" placeholder="To" value="">
                                <input type="hidden" name="utf_to" class="utf" value="0" />
                            </div> 
                        </div>
                        <div class="col-lg-6">
                            <div class="panel-group" id="accordion1">
                                <div class="panel panel-default">
                                    <a class="btn-custom passenger-target-1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
                                        <div class="panel-heading-custom">
                                            <?php if( $total_count  == 1){ 
                                                    echo $total_count." Passenger"; } 
                                                else { 
                                                    echo $total_count." Passengers"; } ?>  
                                            <span class="caret"></span>
                                        </div>
                                    </a>
                                    <div id="collapseOne" class="panel-collapse panel-collapse-custom collapse">
                                        <div class="panel-body panel-dropdown">
                                            <div class="group-adult-1 center-text row">
                                                <div class="col-lg-10">
                                                    <span>No. of adults</span>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="adult-minus-1"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="adult_count" id="adult-text-1" value="<?php echo $_GET['adult_count'];?>" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="adult-plus-1"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-youth-1 center-text row">
                                                <div class="col-lg-10">
                                                    <span>No. of children</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>2 years - 12 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="youth-minus-1"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="youth_count" id="youth-text-1" value="<?php echo $_GET['youth_count'];?>" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="youth-plus-1"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-kids-1 center-text row">
                                                <div class="col-lg-10">
                                                    <span>No. of infants</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>under 2 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="kids-minus-1"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="kids_count" id="kids-text-1" value="<?php echo $_GET['kids_count'];?>" readonly>
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
                            <div class="col-lg-6">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="oneway_date" id="date-1" readonly class="form-control" type="text" placeholder="Depart Date" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="option-bottom-tab search-padding row">
                        <div class="col-lg-18 more-options-link-1">
                            <a href="#" id="more-options-1"><span class="glyphicon glyphicon-plus-sign"></span> More Options</a>
                        </div>
                        <div class="col-lg-6 more-options-1" style="display:none;">
                            <input type="text" class="form-control airlinePreference" name="airline_preference_1" placeholder="Airline Preference" value="<?php if(isset($_GET['airline_preference_1'])){ echo $_GET['airline_preference_1']; } else { echo ""; }  ?>" />
                        </div>
                        <div class="col-lg-6 more-options-1" style="display:none;">
                            <select name="class_of_travel_1" id="class-of-travel"  >
                                <option value="">Class of Travel</option>
                                <option value="All"  <?php if($class === "All"){ echo "selected"; } ?>>All</option>
                                <option value="Economy"  <?php if($class === "Economy"){ echo "selected"; } ?>>Economy</option>
                                <option value="Business"  <?php if($class === "Business"){ echo "selected"; } ?>>Business</option>
                                <option value="First"  <?php if($class === "First"){ echo "selected"; } ?>>First</option>
                            </select>
                        </div>
                        <div class="col-lg-6 more-options-1 less-options-link-1" style="display:none;">
                            <a href="#" id="less-options-1"><span class="glyphicon glyphicon-minus-sign"></span> Less Options</a>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="col-lg-5 remove-padding">
                            <button id="form-submit-button" type="button"><span class="glyphicon glyphicon-search"></span>SEARCH</button>
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
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="3" name="from" placeholder="From">
                                <input type="hidden" name="utf_from" class="utf" value="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 control-label">
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="4" name="to" placeholder="To">
                                <input type="hidden" name="utf_to" class="utf" value="0" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel-group" id="accordion2">
                                <div class="panel panel-default">
                                    <a class="btn-custom passenger-target-2" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                                        <div class="panel-heading-custom">
                                                <?php echo $total_count;?> Passengers <span class="caret"></span>
                                        </div>
                                    </a>
                                    <div id="collapseTwo" class="panel-collapse panel-collapse-custom collapse">
                                        <div class="panel-body panel-dropdown">
                                            <div class="group-adult-2 center-text row">
                                                <div class="col-lg-10">
                                                    <span>No. of adults</span>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="adult-minus-2"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="adult_count" id="adult-text-2" value="<?php echo $_GET['adult_count'];?>" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="adult-plus-2"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-youth-2 center-text row">
                                                <div class="col-lg-10">
                                                    <span>No. of children</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>2 years - 12 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="youth-minus-2"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="youth_count" id="youth-text-2" value="<?php echo $_GET['youth_count'];?>" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="youth-plus-2"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-kids-2 center-text row">
                                                <div class="col-lg-10">
                                                    <span>No. of infants</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>under 2 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="kids-minus-2"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="kids_count" id="kids-text-2" value="<?php echo $_GET['kids_count'];?>" readonly>
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
                            <div class="col-lg-6 control-label">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="to_date" id="date-2" readonly class="form-control" type="text" placeholder="Depart Date" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-padding row">
                        <div class="form-group">
                            <div class="pull-right col-lg-6 control-label">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="from_date" id="date-3" readonly class="form-control" type="text" placeholder="Depart Date" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="option-bottom-tab search-padding row">
                        <div class="col-lg-18 more-options-link-2">
                            <a href="#" id="more-options-2"><span class="glyphicon glyphicon-plus-sign"></span> More Options</a>
                        </div>
                        <div class="col-lg-6 more-options-2" style="display:none;">
                            <input type="text" class="form-control airlinePreference" name="airline_preference_2" placeholder="Airline Preference" value="<?php if(isset($_GET['airline_preference_2'])){ echo $_GET['airline_preference_2']; } else { echo ""; }  ?>" />
                        </div>
                        <div class="col-lg-6 more-options-2" style="display:none;">
                            <select name="class_of_travel_2" id="class-of-travel"  >
                                <option value="">Class of Travel</option>
                                <option value="All"  <?php if($class === "All"){ echo "selected"; } ?>>All</option>
                                <option value="Economy"  <?php if($class === "Economy"){ echo "selected"; } ?>>Economy</option>
                                <option value="Business"  <?php if($class === "Business"){ echo "selected"; } ?>>Business</option>
                                <option value="First"  <?php if($class === "First"){ echo "selected"; } ?>>First</option>
                            </select>
                        </div>
                        <div class="col-lg-6 more-options-2 less-options-link-2" style="display:none;">
                            <a href="#" id="less-options-2"><span class="glyphicon glyphicon-minus-sign"></span> Less Options</a>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="pull-right col-lg-5 remove-padding">
                            <button id="form-submit-button" type="button"><span class="glyphicon glyphicon-search"></span>SEARCH</button>
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
                    <input type="text"name="ismod" value="true" style="display:none;"/>
                    <div class="row form-padding">
                        <div class="col-lg-1 col-padding destination-text"></div>
                        <div class="col-lg-5 col-padding destination-text">
                            <span>Destination 1</span>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="5" name="city_from_one" placeholder="From">
                                <input type="hidden" name="utf_from_one" class="utf" value="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="6" name="city_to_one" placeholder="To">
                                <input type="hidden" name="utf_to_one" class="utf" value="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="multi_date_one" id="date-4" readonly class="form-control" type="text" placeholder="Depart Date">
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
                                <input type="text" onClick="this.select();" class="form-control searchBox" data-num="7" name="city_from_two" placeholder="From" readonly>
                                <input type="hidden" name="utf_from_two" class="utf" value="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">
                                    <input type="text" onClick="this.select();" class="form-control searchBox" data-num="8" name="city_to_two" placeholder="To">
                                    <input type="hidden" name="utf_to_two" class="utf" value="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-padding control-label">
                                <div class="inner-addon right-addon">
                                    <i class="glyphicon"></i>
                                    <input name="multi_date_two" id="date-5" readonly class="form-control" type="text" placeholder="Depart Date">
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
                                        <input type="text" onClick="this.select();" class="form-control searchBox" data-num="9" name="city_from_three" placeholder="From" readonly>
                                        <input type="hidden" name="utf_from_three" class="utf" value="0" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-padding control-label">
                                        <input type="text" onClick="this.select();" class="form-control searchBox" data-num="10" name="city_to_three" placeholder="To">
                                        <input type="hidden" name="utf_to_three" class="utf" value="0" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-padding control-label">
                                        <div class="inner-addon right-addon"><i class="glyphicon"></i>
                                            <input name="multi_date_three" id="date-6" readonly class="form-control" type="text" placeholder="Depart Date">
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
                                        <input type="text" onClick="this.select();" class="form-control searchBox" data-num="11" name="city_from_four" placeholder="From" readonly>
                                        <input type="hidden" name="utf_from_four" class="utf" value="0" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-padding control-label">
                                        <input type="text" onClick="this.select();" class="form-control searchBox" data-num="12" name="city_to_four" placeholder="To">
                                        <input type="hidden" name="utf_to_four" class="utf" value="0" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-padding control-label">
                                        <div class="inner-addon right-addon"><i class="glyphicon"></i>
                                            <input name="multi_date_four" id="date-7" readonly class="form-control" type="text" placeholder="Depart Date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 add-flight-link"><a href="#" id="btnAdd"><span class="glyphicon glyphicon-plus-sign"></span> Add A Flight</a></div>
                        <div class="col-lg-18"></div>
                    </div>
                    <div class="row form-padding">
                        <div class="pull-right col-lg-6 col-padding">
                            <div class="panel-group" id="accordion3">
                                <div class="panel panel-default">
                                    <a class="btn-custom passenger-target-3" data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">
                                        <div class="panel-heading-custom">
                                                <?php echo $total_count;?> Passengers  <span class="caret"></span>
                                        </div>
                                    </a>
                                    <div id="collapseThree" class="panel-collapse panel-collapse-custom collapse">
                                        <div class="panel-body panel-dropdown">
                                            <div class="group-adult-3 center-text row">
                                                <div class="col-lg-10">
                                                    <span>No. of adults</span>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="adult-minus-3"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="adult_count" id="adult-text-3" value="<?php echo $_GET['adult_count'];?>" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="adult-plus-3"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-youth-3 center-text row">
                                                <div class="col-lg-10">
                                                    <span>No. of children</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>2 years - 12 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="youth-minus-3"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="youth_count" id="youth-text-3" value="<?php echo $_GET['youth_count'];?>" readonly>
                                                    <button class="btn-custom-calc plusBtn" id="youth-plus-3"><span class="glyphicon glyphicon-plus"></span></button>
                                                </div>
                                            </div>
                                            <div class="group-kids-3 center-text row">
                                                <div class="col-lg-10">
                                                    <span>No. of infants</span>
                                                    <div class="col-lg-24 small-text">
                                                        <span>under 2 years</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-14">
                                                    <button class="btn-custom-calc minusBtn" id="kids-minus-3"><span class="glyphicon glyphicon-minus"></span></button>
                                                    <input type="text" name="kids_count" id="kids-text-3" value="<?php echo $_GET['kids_count'];?>" readonly>
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
                    <div class="option-bottom-tab search-padding row">
                        <div class="col-lg-18 more-options-link-3">
                            <a href="#" id="more-options-3"><span class="glyphicon glyphicon-plus-sign"></span> More Options</a>
                        </div>
                        <div class="col-lg-6 more-options-3" style="display:none;">
                            <input type="text" class="form-control airlinePreference" name="airline_preference_3" placeholder="Airline Preference" value="<?php if(isset($_GET['airline_preference_3'])){ echo $_GET['airline_preference_3']; } else { echo ""; }  ?>">
                        </div>
                        <div class="col-lg-6 more-options-3" style="display:none;">
                            <select name="class_of_travel_3" id="class-of-travel"  >
                                <option value="">Class of Travel</option>
                                <option value="All"  <?php if($class === "All"){ echo "selected"; } ?>>All</option>
                                <option value="Economy"  <?php if($class === "Economy"){ echo "selected"; } ?>>Economy</option>
                                <option value="Business"  <?php if($class === "Business"){ echo "selected"; } ?>>Business</option>
                                <option value="First"  <?php if($class === "First"){ echo "selected"; } ?>>First</option>
                            </select>
                        </div>
                        <div class="col-lg-6 more-options-3 less-options-link-3" style="display:none;">
                            <a href="#" id="less-options-3"><span class="glyphicon glyphicon-minus-sign"></span> Less Options</a>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="col-lg-5 remove-padding">
                            <button id="form-submit-button" type="button"><span class="glyphicon glyphicon-search"></span>SEARCH</button>
                        </div>
                    </div>
                    <input name="count_flights" value="1" style="display:none;" readonly />
                    <input type="text" name="flight_type" value="OneWay" style="display:none;" readonly />
                </form>
            </div>
        </div>
    </div>
<script>

    function reset_date( html_object, selected_date, min_date ){
        if( min_date > selected_date ){
            html_object.datepicker('option', 'minDate', min_date);
            html_object.datepicker('option', 'maxDate', '+1Y');
            html_object.datepicker('setDate', min_date);
            return;
        }
        if( min_date < selected_date ){
            html_object.datepicker('option', 'minDate', min_date);
            html_object.datepicker('option', 'maxDate', '+1Y');
            return;
        }
        else{
            return;
        }
    }

    var default_param = [];

    $(document).ready(function(){
//set input values
        var locArr = window.location.pathname.split('/');
        var nth = locArr.length;
        var fl_type = locArr[nth-1];
        if( fl_type === "oneway" ){
            $('#flightsTab a[href="#oneWay"]').tab('show');
            default_param = [<?php echo json_encode($_GET);?>];
            $('#oneWay input[name=from]').val(default_param[0].from);
            $('#oneWay input[name=to]').val(default_param[0].to);
            $('#oneWay input[name=utf_from]').val(default_param[0].utf_from);
            $('#oneWay input[name=utf_to]').val(default_param[0].utf_to);
            $('#oneWay input[name=oneway_date]').val(default_param[0].oneway_date);
            $('#roundTrip input[name=from]').val(default_param[0].from);
            $('#roundTrip input[name=to]').val(default_param[0].to);
        } else if( fl_type === "return_parameters" ){
            $('#flightsTab a[href="#roundTrip"]').tab('show');
            default_param = [<?php echo json_encode($_GET);?>];
            $('#roundTrip input[name=from]').val(default_param[0].from);
            $('#roundTrip input[name=to]').val(default_param[0].to);
            $('#roundTrip input[name=utf_from]').val(default_param[0].utf_from);
            $('#roundTrip input[name=utf_to]').val(default_param[0].utf_to);
            $('#roundTrip input[name=to_date]').val(default_param[0].to_date);
            $('#roundTrip input[name=from_date]').val(default_param[0].from_date);
            $('#oneWay input[name=from]').val(default_param[0].from);
            $('#oneWay input[name=to]').val(default_param[0].to);  
            $('#oneWay input[name=oneway_date]').val(default_param[0].to_date);          
        } else {
            $('#flightsTab a[href="#multiCity"]').tab('show');
            <?php if( isset($_SESSION['details']) ):?>
            default_param = [<?php echo json_encode($_SESSION['details']);?>];
            $('#multiCity input[name=city_from_one]').val(default_param[0].from[0]);
            $('#multiCity input[name=city_to_one]').val(default_param[0].to[0]);
            $('#multiCity input[name=utf_from_one]').val(default_param[0].utf_from[0]);
            $('#multiCity input[name=utf_to_one]').val(default_param[0].utf_to[0]);
            $('#multiCity input[name=city_from_two]').val(default_param[0].from[1]);
            $('#multiCity input[name=city_to_two]').val(default_param[0].to[1]);
            $('#multiCity input[name=utf_from_two]').val(default_param[0].utf_from[1]);
            $('#multiCity input[name=utf_to_two]').val(default_param[0].utf_to[1]);
            $('#multiCity input[name=city_from_three]').val(default_param[0].from[2]);
            $('#multiCity input[name=city_to_three]').val(default_param[0].to[2]);
            $('#multiCity input[name=utf_from_three]').val(default_param[0].utf_from[2]);
            $('#multiCity input[name=utf_to_three]').val(default_param[0].utf_to[2]);
            $('#multiCity input[name=city_from_four]').val(default_param[0].from[3]);
            $('#multiCity input[name=city_to_four]').val(default_param[0].to[3]);
            $('#multiCity input[name=utf_from_four]').val(default_param[0].utf_from[3]);
            $('#multiCity input[name=utf_to_four]').val(default_param[0].utf_to[3]);
            <?php endif;?>
        }
//set input values end

        var myArr = [];
        var mainArr = [];
        var temp = {};
        var cityCodes = [];
        var airports = [];
        var utf = [];
        var myArr1 = [];
        var mainArr1 = [];
        var temp1 = {};
        var airline = [];

        $.ajax({
            type: "GET",
            url: "<?php echo base_url('scripts/airport-codes-test.xml');?>",
            dataType: "xml",
            success: function(xml){
                temp = xmlToJson(xml);
                myArr = temp.Document.Row;
            }
        });

        $.ajax({
            type: "GET",
            url: "<?php echo base_url();?>scripts/Airline.xml",
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
                'margin-top': '120px'
            }, 300, "linear");

            $('input[name=count_flights]').val(clicks_index+1);
            
            if( clicks_index == 1 ) {
                c.find('.clone:first').slideDown('300');
				$('input[name=city_from_three]').val($('input[name=city_to_two]').val());
                clicks_index++;
            } else if( clicks_index == 2 ){
                c.find('.btnDel:first').removeClass().addClass('non-clickable');
                c.find('.clone:last').slideDown('300');
                clicks_index++;
                $('input[name = city_from_four]').val($('input[name=city_to_three]').val());				
                $('#btnAdd').hide();
            } else {
                return false;
            }
        });

        c.on( 'click', '.non-clickable', function( e ) {
            e.preventDefault();
            alert('Cannot Delete! Delete last entry first.');
        });

        c.on('click', '.btnDel', function(e){
            e.preventDefault();
            --clicks_index;

            if( clicks_index == 1 ){
                $('.flights-wrapper').animate({
                    'margin-top': '190px'
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

        var opd = 0;
        var search_ele = 0;
        var flag_index1 = 0;
        var opd1 = 0;

        $(".searchBox").on('keyup', function( e ){
            
            var searchText = $(this).val().toLowerCase();
            airports = [];
            utf = [];

            if( searchText.length >= 3 )
            {
                $.each( myArr, function( i, ar )
                {  
                    if((ar.Airport.valueText.toLowerCase().indexOf(searchText) == -1) || (ar.City.valueText.toLowerCase().indexOf(searchText) == -1) || (ar.AirportCode.valueText.toLowerCase().indexOf(searchText) == -1 ))
                    {
                        airports.push(ar.City.valueText + ', ' + ar.Airport.valueText + ', ' + ar.Country.valueText +' ('+ ar.AirportCode.valueText + ')');
                        utf[ar.AirportCode.valueText] = ar.TimeZone.valueText;
                    }
                });
            }

            if( e.keyCode === 8 || e.keyCode === 46){
                search_ele = 0;
            }

            if( search_ele !== $(this).data('num') ){
                if( $(this).data('num') == 1 || $(this).data('num') == 2 || $(this).data('num') == 3 || $(this).data('num') == 4){
                    if( $(this).attr('id') !== 'done' && $(this).val() !== default_param[0].to ){
                        $(this).attr('id', 'flaged');
                    }
                }else{
                    if( $(this).attr('id') !== 'done' && ($(this).val() !== default_param[0].to[0] || $(this).val() !== default_param[0].to[1] || $(this).val() !== default_param[0].to[2] || $(this).val() !== default_param[0].to[3]) ){
                        $(this).attr('id', 'flaged');
                    }
                }
                search_ele = $(this).data('num');
            }

            availableTags = airports;
            var NoResultsLabel = 'No results found.';
               
            $(this).autocomplete({
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
                    , containsMatcher = new RegExp(term, "i")
                    , contains = $.grep(availableTags, function (value) {
                        return $.inArray(value, startsWith) < 0 && containsMatcher.test(value.label || value.value || value);
                    });

                    var searchResults = startsWith;
                    $.each(contains, function(i, val){
                        searchResults.push(val);
                    });

                    if (!searchResults.length) {
                        searchResults = [NoResultsLabel];
                    }

                    response(searchResults);
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
                                        val.value = ui.item.value;
                                        $(all_ios[next_ele]).focus();
                                        all_ios[next_ele].select();
                                        $(val).attr("id", "");
                                    }else{
                                        $(val).focus();
                                        val.select();
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
                    var p = ui.item.value.lastIndexOf('(');
                    var q = ui.item.value.lastIndexOf(')');
                    var key = ui.item.value.slice( p+1 , q );
                    $(this).next().val(utf[key]);

                    if( opd === 1 ){
                        opd = 0;
                        $(this).attr('id', 'done');
                        $(this).siblings('.glyphicon').addClass('glyphicon-remove');
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
                        $(this).attr('id', 'done');
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

//select or die
    $('select').selectOrDie({
        placeholderOption: true,
    });

// datepicker

    $.datepicker.setDefaults({
            dateFormat: "dd-mm-yy"
    });

    $('#date-1').datepicker({
        minDate: 0,
        maxDate: '+1Y',
        setDate: default_param[0].oneway_date,
        onSelect: function(){
            var sm = $('#form-submit-1').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
        }
    });

    $('#date-2').datepicker({
        minDate: 0,
        maxDate: '+1Y',
        setDate: default_param[0].to_date || new Date(),
        onSelect: function(dateText, inst){
            var min = $(this).datepicker('getDate') || new Date();
            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
            $('#date-3').datepicker('option', 'minDate', min);
            $('#date-3').datepicker('option', 'maxDate', '+1Y');
        }
    });

    $('#date-3').datepicker({
        minDate: default_param[0].to_date || new Date(),
        maxDate: '+1Y',
        setDate: default_param[0].to_date || new Date(),
        onSelect: function (dateText, inst) {
            var sm = $('#form-submit-2').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
        }
    });

    <?php if(isset($_SESSION['details']['dp_dates'])):?>
    var dp_dates_arr = [<?php echo json_encode($_SESSION['details']['dp_dates']);?>];

    var dp_dates = [];
    $.each(dp_dates_arr[0], function(i, val){
        dp_dates[i] = new Date(val);
    });
    <?php endif;?>

    $('#date-4').datepicker({
        minDate:0,  
        maxDate: '+1Y',
        onSelect: function(){
            var sm = $('#form-submit-3').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
            var min = $(this).datepicker('getDate') || new Date();
            reset_date( $('#date-5'), $('#date-5').datepicker('getDate'), min );
            reset_date( $('#date-6'), $('#date-6').datepicker('getDate'), min );
            reset_date( $('#date-7'), $('#date-7').datepicker('getDate'), min );
        }
    });
    <?php if(isset($_SESSION['details']['dp_dates'])):?>
    $('#date-4').datepicker('setDate', dp_dates[0])
    <?php endif;?>

    $('#date-5').datepicker({
        minDate:$('#date-4').val(),
        maxDate: '+1Y',
        onSelect: function(){
            var sm = $('#form-submit-3').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
            var min = $(this).datepicker('getDate') || new Date();
            reset_date( $('#date-6'), $('#date-6').datepicker('getDate'), min );
            reset_date( $('#date-7'), $('#date-7').datepicker('getDate'), min );
        }
    });
    <?php if(isset($_SESSION['details']['dp_dates'])):?>
    $('#date-5').datepicker('setDate', dp_dates[1])
    <?php endif;?>

    $('#date-6').datepicker({
        minDate:$('#date-5').val(),
        maxDate: '+1Y',
        onSelect: function(){
            var sm = $('#form-submit-3').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
            var min = $(this).datepicker('getDate') || new Date();
            reset_date( $('#date-7'), $('#date-7').datepicker('getDate'), min );
        }
    });
    <?php if(isset($_SESSION['details']['dp_dates'])):?>
    $('#date-6').datepicker('setDate', dp_dates[2])
    <?php endif;?>

    $('#date-7').datepicker({
        minDate:$('#date-6').val(),
        maxDate: '+1Y',
        onSelect: function(){
            var sm = $('#form-submit-3').data('bootstrapValidator');
            sm.updateStatus($(this), 'VALID');
        }
    });
    <?php if(isset($_SESSION['details']['dp_dates'])):?>
    $('#date-7').datepicker('setDate', dp_dates[3]);
    <?php endif;?>

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

    var edit_chk = 0;
    var all_ios = $(document).find('.searchBox');
    var all_aps = $(document).find('.airlinePreference');

    $(document).on( 'click', '#form-submit-button', function( e ){
        e.preventDefault();
        var form_id = $(this).closest('form').attr('id');
        $.each( all_ios, function( i, ios ){
            if( ios.id === "flaged" ){
                edit_chk++;
            }
        });
        $.each( all_aps, function( i, aps ){
            if( aps.id === "flaged" ){
                edit_chk++;
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

    $('#accordion1').on('click', function(){
        var panel_width = $(this).width();
        $('#collapseOne, #collapseTwo, #collapseThree').css('width', panel_width+'px');

        $(window).on('resize', function(){
             panel_width = $('#accordion1').width();
            $('#collapseOne, #collapseTwo, #collapseThree').css('width', panel_width+'px');
        });
    });

//multi flight auto select
    $('input[name = city_to_one]').on('blur', function(){
        $('input[name = city_from_two]').val($(this).val());
        var sm = $('#form-submit-3').data('bootstrapValidator');
        sm.updateStatus($('input[name = city_from_two]'), 'VALID');
    });

    $('input[name = city_to_two]').on('blur', function(){
        $('input[name = city_from_three]').val($(this).val());
        var sm = $('#form-submit-3').data('bootstrapValidator');
        sm.updateStatus($('input[name = city_from_three]'), 'VALID');
    });

    $('.clonedInput').on('blur', 'input[name = city_to_three]' , function(){
        $('input[name = city_from_four]').val($(this).val());
        var sm = $('#form-submit-3').data('bootstrapValidator');
        sm.updateStatus($('input[name = city_from_four]'), 'VALID');
    });

//passenger calculation
    var inputArray = { 
        adult1: <?php echo intval($_GET['adult_count']);?>,
        adult2: <?php echo intval($_GET['adult_count']);?>,
        adult3: <?php echo intval($_GET['adult_count']);?>,
        youth1: <?php echo intval($_GET['youth_count']);?>,
        youth2: <?php echo intval($_GET['youth_count']);?>,
        youth3: <?php echo intval($_GET['youth_count']);?>,
        kids1: <?php echo intval($_GET['kids_count']);?>,
        kids2: <?php echo intval($_GET['kids_count']);?>,
        kids3: <?php echo intval($_GET['kids_count']);?>,
        total1: <?php echo intval($total_count);?>,
        total2: <?php echo intval($total_count);?>,
        total3: <?php echo intval($total_count);?>
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