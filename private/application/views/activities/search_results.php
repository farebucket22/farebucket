<style>
.mix{
    display:none;
}
.filtersLabels label{
    font-size: 12px;
}
.sod_placeholder,.sod_label{
    text-transform: none;
    height:13px;
}
</style>
<div class="wrap">
    <div class="container-fluid clear-top">    
        <div class="row resultsSearchContainer navbar-fixed-top">
    	<?php
            $this->load->view("common/search.php");
    	?>
        </div>
    </div>
    <?php
    $categoryNameArr = array();
    $categoryIdArr = array();
    $i=0;
    $size = count($activity_results);
    if($size===0){
        $message = "No results found!";
        $steps=array();
    } else{
        $message = "";
        foreach($activity_results as $ar){
            if(!in_array($ar->activity_category_name, $categoryNameArr)){
                $categoryNameArr[] = $ar->activity_category_name;
                $categoryIdArr[] = $ar->activity_category_id;
            }
            if($i===0){
                $minPrice = $ar->activity_onwards_price;
            }else if ($i===($size - 1)) {
                $maxPrice = $ar->activity_onwards_price;
            }
            $i++;
        }
        if(empty($maxPrice))
        {
            $maxPrice = $minPrice;
            $step = ceil(($maxPrice/4) / 100) * 100;
            $steps = array(0,$step,$step+1,$step*2,$step*2+1,$step*3,$step*3+1,$step*4);
        }


        //generates steps for price filter check boxes.
        //three sections are possible x<=8000, 8000<x<=80000, x>80000
        // for the first $increment = 1000 (16 values)
        // for the second $increment = 1000 (8 values) then $increment = remaining price / 4 (8 values)
        // for the third $increment = max price / 8 (16 values)
        // switch is used to monitor price.
        $index = 0;
        $steps = [];
        $step = 0;
        $i = 0;
        $remMaxPrice = $maxPrice - 4000;

        switch( $maxPrice ){
            case ($maxPrice <= 8000):
                $increment = 1000;
                while( $step < $maxPrice ){
                    if( $step > 0 ){
                        $steps[$i] = $step+1;
                    }else{
                        $steps[$i] = $step;
                    }
                    $step += $increment;
                    $steps[$i+1] = $step;
                    $i+=2;
                }
                break;
            case ($maxPrice > 8000 && $maxPrice <= 80000):
                $increment = ceil(($remMaxPrice/4) / 100) * 100;
                while( $step < $maxPrice ){
                    if( $i < 8 ){
                        if( $step > 0 ){
                            $steps[$i] = $step+1;
                        }else{
                            $steps[$i] = $step;
                        }
                        $step += 1000;
                        $steps[$i+1] = $step;
                    }else{
                        if( $step > 8 ){
                            $steps[$i] = $step+1;
                        }else{
                            $steps[$i] = $step;
                        }
                        $step += $increment;
                        $steps[$i+1] = $step;
                    }
                    $i+=2;
                }
                break;
            case ($maxPrice > 80000):
                $increment = ceil(($maxPrice/8) / 100) * 100;
                while( $step < $maxPrice ){
                    if( $step > 0 ){
                        $steps[$i] = $step+1;
                    }else{
                        $steps[$i] = $step;
                    }
                    $step += $increment;
                    $steps[$i+1] = $step;
                    $i+=2;
                }
                break;
        }
        // steps generation code end.

        // $step = ceil(($maxPrice/4) / 100) * 100;
        // $steps = array(0,$step,$step+1,$step*2,$step*2+1,$step*3,$step*3+1,$step*4);
    }
    ?>
    <div class="container-fluid main clear-top">  
        <div class="row resultsFilters">
        	<div class="col-xs-24 col-sm-4 col-sm-offset-1 filterWeatherContainer" id="Filters">
        		<div class="row">
		            <div class="col-xs-24 filtersContainer filtersLabels">
		                <div class="row">
		                    <div class="col-xs-24 filterByArea filter"><h4>Filter By:</h4></div>
		                </div>
		                <div class="row">
		                    <div class="panel-group" id="accordion">
		                        <div class="panel panel-default">
		                            <div class="panel-heading">
		                                <h4 class="panel-title">
		                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseTwo">
		                                    Category
		                                    </a>
		                                </h4>
		                            </div>
		                            <div id="collapseTwo" class="panel-collapse collapse in">
		                                <div class="panel-body">
                                            <fieldset>
		                                    <?php
		                                    for($i=0;$i<sizeof($categoryNameArr);$i++){                 
		                                    ?>
		                                    <div class="checkbox">
		                                      <label>
		                                          <input type="checkbox" id="<?php echo $categoryIdArr[$i] ?>" class="filterCheckbox categoryFilter" value=".category-<?php echo $categoryIdArr[$i] ?>" />
		                                        	<?php
                                                        echo $categoryNameArr[$i];
		                                        	?>
		                                      </label>
		                                    </div>
		                                    <?php
		                                    }
		                                    ?>
                                            </fieldset>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="panel panel-default">
		                            <div class="panel-heading">
		                                <h4 class="panel-title">
		                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseThree">
		                                    Price
		                                    </a>
		                                </h4>
		                            </div>
		                            <div id="collapseThree" class="panel-collapse collapse in">
		                                <div class="panel-body">
                                            <fieldset>
                                                <?php
                                                if($size!==0){
                                                ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-0" value=".priceRange-0"/>
                                                        <?php echo("< Rs.".$steps[1].".00"); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-1" value=".priceRange-1"/>
                                                        <?php echo("Rs.".$steps[2].".00 - Rs.".$steps[3].".00"); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-2" value=".priceRange-2"/>
                                                        <?php echo("Rs.".$steps[4].".00 - Rs.".$steps[5].".00"); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-3" value=".priceRange-3"/>
                                                        <?php echo("Rs.".$steps[6].".00 - Rs.".$steps[7].".00"); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-4" value=".priceRange-4"/>
                                                        <?php echo("Rs.".$steps[8].".00 - Rs.".$steps[9].".00"); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-5" value=".priceRange-5"/>
                                                        <?php echo("Rs.".$steps[10].".00 - Rs.".$steps[11].".00"); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-6" value=".priceRange-6"/>
                                                        <?php echo("Rs.".$steps[12].".00 - Rs.".$steps[13].".00"); ?>
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="filterCheckbox priceFilter" id="priceFilter-7" value=".priceRange-7"/>
                                                        <?php echo("Rs.".$steps[14].".00 - Rs.".$steps[15].".00"); ?>
                                                    </label>
                                                </div>
                                                <?php
                                                }
                                                ?>
                                            </fieldset>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="panel panel-default">
		                            <div class="panel-heading">
		                                <h4 class="panel-title">
		                                    <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseFour">
		                                      Rating
		                                    </a>
		                                </h4>
		                            </div>
		                            <div id="collapseFour" class="panel-collapse collapse in">
		                                <div class="panel-body">
                                            <?php
                                            if($size!==0){
                                            ?>
                                            <fieldset>
		                                    <div class="checkbox">
		                                      <label>
		                                        <input type="checkbox" class="filterCheckbox ratingFilter filter" id="ratingFilter-0" value=".rating-2"/>
		                                        <span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span>
		                                      </label>
		                                    </div>
		                                    <div class="checkbox">
		                                      <label>
		                                        <input type="checkbox" class="filterCheckbox ratingFilter filter" id="ratingFilter-1" value=".rating-3"/>
		                                        <span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span>
		                                      </label>
		                                    </div>
		                                    <div class="checkbox">
		                                      <label>
		                                        <input type="checkbox" class="filterCheckbox ratingFilter filter" id="ratingFilter-2" value=".rating-4"/>
		                                        <span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span>
		                                      </label>
		                                    </div>
		                                    <div class="checkbox">
		                                      <label>
		                                        <input type="checkbox" class="filterCheckbox ratingFilter filter" id="ratingFilter-3" value=".rating-5"/>
		                                        <span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span><span class="glyphicon glyphicon-star green-star"></span>
		                                      </label>
		                                    </div>
                                            </fieldset>
                                            <?php
                                            }
                                            ?>
		                                </div>
		                            </div>
		                        </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseOne">
                                                Keyword
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <?php
                                            if($size!==0){
                                            ?>
                                            <input type="text" class="keywordSearch" placeholder="Enter Keyword" /><button class="keywordSearchBtn"><span class="glyphicon glyphicon-search"></span></button>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
		                    </div>
		                </div>                
		            </div>
		    	</div>
		    	<div class="row">
		    		<div class="col-xs-24 searchResultsWeatherDetails">
                
            		</div>
		    	</div>
	        </div>
            <div class="col-xs-24 col-sm-17 col-sm-offset-1 resultsArea">
                <div class="row">
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
                <div class="row" id="Container">
                    <div class="col-xs-24 col-sm-24 results">
                        <?php
                        echo '<div class="row resultsRow">';
                        echo '<div class="col-xs-24 noResultsMessage" style="display:none;"><center><h2>Sorry, No matching records found for your search</h2> </center></div>';
                        if($size===0){
                            //echo '<div class="col-xs-24 result noResultsMessage">'.$message.'</div>';
                        } else{
                            foreach ($activity_results as $ar){
                            $imgPath = 'img/activities/'.$ar->activity_main_image;
                            echo '<div class="col-xs-24 col-sm-6 result mix rating-'.$ar->activity_rating_average_value.' category-'.$ar->activity_category_id.'" data-pricesort="'.$ar->activity_onwards_price.'" data-redid="'.$ar->activity_id.'">'
                                    .'<div class="custThumbnailContainer" style="backgound: url("../../'.$imgPath.'") center center no-repeat; background-size:cover;">'
                                        .'<div class="custOverlay"></div>'
                                        .'<div class="custCaption left-text">'
                                            .'<h4>'.$ar->activity_name.'</h4>'
                                            .'<div class="row activityPriceArea">'
                                                .'<div class="col-xs-24">'
                                                .'<ul class="activityAvgRating col-xs-12" id=" rating-'.$ar->activity_rating_average_value.'">';
                                                for($i=0;$i<$ar->activity_rating_average_value;$i++){
                                                echo '<li><span class="glyphicon glyphicon-star"></span></li>';
                                                }
                                                for($i=0;$i<(5-$ar->activity_rating_average_value);$i++){
                                                echo '<li><span class="glyphicon glyphicon-star-empty"></span></li>';
                                                }
                                                echo '</ul>'
                                                .'<div class="col-xs-12 activityPrice right-text">&#x20B9;'.number_format($ar->activity_onwards_price, 2).'<br /><span class="onwardsLabel">onwards</span></div>'
                                                .'<input type="text" style="display:none" class="activityPriceIO" value="'.$ar->activity_onwards_price.'"/>'
                                                .'</div>'
                                            .'</div>'
                                        .'</div>'
                                    .'</div>'
                                .'</div>';
                            }
                        }
                        echo '</div>';
                        ?>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
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
        // self.$reset = $('#Reset');
        self.$container = $('#Container');

        self.$filters.find('fieldset').each(function(){
            self.groups.push({
                $inputs: $(this).find('input'),
                active: [],
                tracker: false
            });
        });

        self.bindHandlers();
    },
  
  // The "bindHandlers" method will listen for whenever a form value changes. 
  
    bindHandlers: function(){
        var self = this;

        self.$filters.on('change', function(){
            self.parseFilters();
        });

        // self.$reset.on('click', function(e){
        //   e.preventDefault();
        //   self.$filters[0].reset();
        //   self.parseFilters();
        // });
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


    $(document).ready(function(){

        checkboxFilter.init();

        $('.sortBySelect').selectOrDie({
            onChange: function(){
                var sort_type = $(this).val();
                if( sort_type === 'pricesort:asc' ){
                    $('#Container').mixItUp('sort', 'pricesort:asc');
                }else{
                    $('#Container').mixItUp('sort', 'pricesort:desc');
                }
                // var parent = document.getElementsByClassName('resultsRow')[0];
                // var divs = document.getElementsByClassName('result');
                // var i = divs.length - 1;
                // for (; i--;) {
                //     parent.appendChild(divs[i])
                // }
            }
        });

        $('#Container').mixItUp({
            controls: {
                enable: false // we won't be needing these
            },
            animation: {
                easing: 'cubic-bezier(0.86, 0, 0.07, 1)',
                duration: 300
            }
        });

        /***** variable init *****/
        var priceFilterSteps;
        var resultSize = <?php echo $size; ?>;
        if (resultSize!==0){
            priceFilterSteps = <?php echo(json_encode($steps)); ?>;
        } else{
            $(".sortBySelect").attr("disabled","disabled");
        }
        var ratingArr = ["2","3","4","5"];

        // /***** enable city select box *****/
        // $(".citySelect").removeAttr('disabled');
        // $(".citySelect").trigger("chosen:updated");
        
        // $(".categoryFilter").change(function(){
        //     var categoryId = $(this).attr("id");
        //     if($(this).is(":checked")) {
        //         $(".result").each(function(){
        //             var resultId = $(this).attr("id");
        //             var tempArr = resultId.split("-");
        //             var catId = tempArr[1];
        //             if(categoryId === catId){
        //                 $(this).fadeIn("fast");
        //             }
        //         });
        //     } else{
        //         $(".result").each(function(){
        //             var resultId = $(this).attr("id");
        //             var tempArr = resultId.split("-");
        //             var catId = tempArr[1];
        //             if(categoryId === catId){
        //                 $(this).fadeOut("fast");
        //             }
        //         });
        //     }
        //     setTimeout(function(){
        //         if($(".result").children(':visible').length === 0){
        //             $(".noResultsMessage").html("No results found!");
        //         } else{
        //             $(".noResultsMessage").html("");
        //         }
        //     }, 400);
        // });

        // $(".priceFilter").change(function(){
            // var priceFilterId = $(this).attr("id");
            // var temp = priceFilterId.split("-");
            // var priceFilterIndex = parseInt(temp[1]);
            // if($(this).is(":checked")) {
            $(".result").each(function(i){
                var resultPrice = $(this).find(".activityPriceIO").val();
                resultPrice = parseInt(resultPrice);
                // resultPrice = parseInt(resultPrice.slice(1,resultPrice.length-1));
                for( var r = 0 ; r < priceFilterSteps.length ; r++ ){
                    if(resultPrice>=priceFilterSteps[2*r] && resultPrice<=priceFilterSteps[2*r+1]){
                        $(this).addClass('priceRange-'+r);
                    }
                }

            });
            // } else{
                // $(".result").each(function(){
                //     var resultPrice = $(this).find(".activityPrice").html();
                //     resultPrice = parseInt(resultPrice.slice(1,resultPrice.length-1));
                //     if(resultPrice>=priceFilterSteps[2*priceFilterIndex] && resultPrice<=priceFilterSteps[2*priceFilterIndex+1]){
                //         console.log($(this).attr('class'));
                //     }
                // });
            // }
        //     setTimeout(function(){
        //         if($(".result").children(':visible').length === 0){
        //             $(".noResultsMessage").html("No results found!");
        //         } else{
        //             $(".noResultsMessage").html("");
        //         }
        //     }, 400);
        // });


        // $(".ratingFilter").change(function(){
        //     var id = $(this).attr("id");
        //     var temp = id.split("-");
        //     var index = temp[1];
        //     if($(this).is(":checked")) {
        //         $(".result").each(function(){
        //             var resultRatingId = $(this).find(".activityAvgRating").attr("id");
        //             var tempArr = resultRatingId.split("-");
        //             var resultRating = tempArr[1];
        //             if(resultRating===ratingArr[index]){
        //                 $(this).fadeIn("fast");
        //             }
        //         });
        //     } else{
        //         $(".result").each(function(){
        //             var resultRatingId = $(this).find(".activityAvgRating").attr("id");
        //             var tempArr = resultRatingId.split("-");
        //             var resultRating = tempArr[1];
        //             if(resultRating===ratingArr[index]){
        //                 $(this).fadeOut("fast");
        //             }
        //         });
        //     }
        //     setTimeout(function(){
        //         if($(".result").children(':visible').length === 0){
        //             $(".noResultsMessage").html("No results found!");
        //         } else{
        //             $(".noResultsMessage").html("");
        //         }
        //     }, 400);
        // });
    
        $('#Container').on('mixEnd', function(e, state){
            if( state.totalShow === 0 ){
                $('.results .noResultsMessage').show();
            }
        });

        $('#Container').on('mixStart', function(e, state){
            $('.results .noResultsMessage').hide();
        });


        $('.result').on('click', function(){
            var redId = $(this).data('redid');
            var sitePath = "<?php echo site_url('activity/get_activity');?>";
            window.location.href = sitePath + '?activity_id=' + redId;
        });

    });
</script>