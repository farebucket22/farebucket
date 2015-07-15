<style>
    .sod_placeholder,.sod_label{
        text-transform: none;
        height:13px;
    }
</style>
<?php $url = explode("/",$_SERVER['REQUEST_URI']);?>
<div class="col-xs-20 col-xs-offset-2 col-sm-16 col-sm-offset-4 activitySearchErrorMessage"></div>
<div class="searchAreaContainer col-xs-24 col-sm-16 col-sm-offset-4">
    <form class="row wrapper activitySearchForm" action="<?php echo site_url('activity/get_search_results'); ?>" method="GET" >
        <div class="col-xs-20 col-sm-9">
            <select class="countrySelect extra-margin" name="countrySelect">
                <option value="Select Country...">Select Country...</option>
                <?php
                foreach($country_data as $cd1) {
                    if(isset($_SESSION['country_id'])){
                        if($cd1->activity_country_id==$_SESSION['country_id']){
                            echo "<option id='".$cd1->activity_country_id."' value='".$cd1->activity_country_id."-".$cd1->activity_country_name."' selected='selected'>".$cd1->activity_country_name."</option>";
                        } else{
                            echo "<option id='".$cd1->activity_country_id."' value='".$cd1->activity_country_id."-".$cd1->activity_country_name."'>".$cd1->activity_country_name."</option>";
                        }
                    } else{
                        echo "<option id='".$cd1->activity_country_id."' value='".$cd1->activity_country_id."-".$cd1->activity_country_name."'>".$cd1->activity_country_name."</option>";
                    }
                }
                
                ?>
            </select>
        </div>      
        <div class="col-xs-20 col-sm-9">
            <select class="citySelect extra-margin" name="citySelect">
                <option value="Select Country first.">Select Country first.</option>
                <?php
                foreach($city_data as $cd){
                    $city[] = $cd->activity_city_name;
                }
                sort($city);
                foreach($city_data as $cd){
                    foreach($city as $val => $c){
                        if( $cd->activity_city_name == $c ){
                            $city_id[$val] = $cd->activity_city_id;
                        }
                    }
                }

                foreach($city as $val => $cd1) {
                    if(isset($_SESSION['city_id'])){
                        if($city_id[$val]==$_SESSION['city_id']){
                            echo "<option id='".$city_id[$val]."' value='".$city_id[$val]."-".$cd1."' selected='selected'>".$cd1."</option>";
                        } else{
                            echo "<option id='".$city_id[$val]."' value='".$city_id[$val]."-".$cd1."'>".$cd1."</option>";
                        }
                    } else{
                        echo "<option id='0'>Select City...</option>";
                    }
                }
                ?>
            </select>
        </div>
        
        <button type="submit" class="searchBtn col-xs-24 col-sm-5 pull-right"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;SEARCH</button>
    </form>                
</div>

<script type="text/javascript">

$(document).ready(function(){
    $(".countrySelect").change(function(){
        var countryId = $(this).children(":selected").attr("id");
        $("select.citySelect").text("");
        if(countryId!="0"){
            $.ajax({
            url: '<?php echo site_url();?>activity/get_cities',
                data: {country_id:countryId},
                type: 'post',
                success: function(data) {
                    var citiesData = JSON.parse(data);
                    var cities=[];
                    var city_id=[];
                    $.each(citiesData,function(i,val){
                        cities[i] = val.activity_city_name;
                    });
                    cities.sort();
                    $.each(citiesData,function(j,value){
                        $.each(cities,function(i,val){
                            if(value.activity_city_name === val){
                                city_id[i] = value.activity_city_id;
                            }
                        });
                    });
                    $("select.citySelect").append('<option></option>')
                    $.each(cities,function(i,val){
                        $("select.citySelect").removeAttr('disabled');
                        $('.citySelect').selectOrDie("enable");
                        $("select.citySelect").append($('<option id="'+city_id[i]+'" value="'+city_id[i]+'-'+cities[i]+'">', { value : city_id[i] })
                                        .text(cities[i]));
                        $('.citySelect').selectOrDie("update");
                        $('.citySelect').selectOrDie("destroy");
                        $('.citySelect option:first-child').val('Select city...');
                        $('.citySelect option:first-child').html('Select city...');
                        $('.citySelect').selectOrDie({
							size:5,
                            placeholderOption: true,
                            customClass: 'citySelect extra-margin',
                            onChange: function(){
                                citySelected = 1;
                                countryWasChanged = 0;
                                $('span.citySelect').animate({'border-color' : '#1e884b'}, 200);
                                $(".activitySearchErrorMessage").html("");
                            }
                        });
                    });
                }
            });
        } else{
            $("select.citySelect").append($('<option id="0">', { value : "0" }).text("Select City..."));
            $("select.citySelect").attr('disabled', 'disabled');
        }       
    });

    /***select or die*******/
    <?php if($url[count($url)-1] == 'activity'):?>
        var citySelected = 0;
        var countrySelected = 0;
        $('.countrySelect').selectOrDie({
			size: 2,
            placeholderOption: true,
            customClass: 'countrySelect extra-margin',
            onChange: function(){
                countrySelected = 1;
                $('span.countrySelect').animate({'border-color' : '#1e884b'}, 200);
                $(".activitySearchErrorMessage").html("");
            }
        });

        $('.citySelect').selectOrDie({
            placeholderOption: true,
			size: 5,
            disabled: true,
            customClass: 'citySelect extra-margin',
        });

        $(".searchBtn").click(function(e){
            e.preventDefault();
            if( countrySelected == 0 || citySelected == 0 ){
                if( countrySelected == 0 ){
                    $('span.countrySelect').animate({'border-color' : '#f00'}, 200);
                    $(".activitySearchErrorMessage").html("Please select a Country before proceeding.");
                }else if( citySelected == 0 ){
                    $('span.citySelect').animate({'border-color' : '#f00'}, 200);
                    $(".activitySearchErrorMessage").html("Please select a City before proceeding.");
                }
            }else{
                countrySelected = 0;
                citySelected = 0;
                $(".activitySearchForm").submit();
            }
        });
    <?php else:?>
        var countryWasChanged = 0;
        $('.countrySelect').selectOrDie({
			size:2,
            placeholderOption: true,
            customClass: 'countrySelect extra-margin',
            onChange: function(){
                countryWasChanged = 1;
            }
        });

        $('.citySelect').selectOrDie({
			size:5,
            placeholderOption: true,
            customClass: 'citySelect extra-margin',
            onChange: function(){
                countryWasChanged = 0;
                $('span.citySelect').animate({'border-color' : '#1e884b'}, 200);
                $(".activitySearchErrorMessage").html("");
            }
        });

        $(".searchBtn").click(function(e){
            e.preventDefault();
            if( countryWasChanged === 1 ){
                $('span.citySelect').animate({'border-color' : '#f00'}, 200);
                $(".activitySearchErrorMessage").html("Please select a City before proceeding.");
            }else{
                $(".activitySearchForm").submit();
            }
        });
    <?php endif;?>

});
</script>