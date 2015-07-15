<div class="wrap">
	<div class="clear-top">
		<form id="myForm">
			<div class="clonedInput">
				
			</div>
			<div>
				<a href="#" id="btnAdd"><span class="glyphicon glyphicon-plus-sign"></span>Add A Flight</a>
			</div>
		</form>		
	</div>
</div>

<script>
	$(document).ready(function() {
		var clicks = 1;
		var c = $('.clonedInput');

		$('#btnAdd').click(function(e) {
			e.preventDefault();
			
			if( clicks == 1 ) {
				c.append( '<div class="clone"><div class="row form-padding" id="flight_form_2"><div class="col-lg-1 col-padding destination-text"><a href="#" class="btnDel"><span class="glyphicon glyphicon-minus-sign"></span></a></div><div class="col-lg-5 col-padding destination-text"><span>Destination 3</span></div><div class="form-group"><div class="col-lg-6 col-padding control-label"><input type="text" class="form-control searchBox" name="city_from_three" placeholder="From" readonly></div></div><div class="form-group"><div class="col-lg-6 col-padding control-label"><input type="text" class="form-control searchBox" name="city_to_three" placeholder="To"></div></div><div class="form-group"><div class="col-lg-6 col-padding control-label"><div class="inner-addon right-addon"><i class="glyphicon glyphicon-calendar"></i><input name="multi_date_three" id="date-6" class="form-control" type="text" placeholder="Depart Date"></div></div></div></div></div>' );
				clicks++;
			} else if( clicks == 2 ){
				c.find('.btnDel').removeClass().addClass('non-clickable');
				c.append( '<div class="clone"><div class="row form-padding" id="flight_form_3"><div class="col-lg-1 col-padding destination-text"><a href="#" class="btnDel"><span class="glyphicon glyphicon-minus-sign"></span></a></div><div class="col-lg-5 col-padding destination-text"><span>Destination 4</span></div><div class="form-group"><div class="col-lg-6 col-padding control-label"><input type="text" class="form-control searchBox" name="city_from_four" placeholder="From" readonly></div></div><div class="form-group"><div class="col-lg-6 col-padding control-label"><input type="text" class="form-control searchBox" name="city_to_four" placeholder="To"></div></div><div class="form-group"><div class="col-lg-6 col-padding control-label"><div class="inner-addon right-addon"><i class="glyphicon glyphicon-calendar"></i><input name="multi_date_four" id="date-7" class="form-control" type="text" placeholder="Depart Date"></div></div></div></div></div>' )
				clicks++;
			} else {
				return false;
			}
		});


		c.on( 'click', '.non-clickable', function( e ) {
			e.preventDefault();
			alert('Cannot delete! Delete last entry first.');
		});

		c.on('click', '.btnDel', function(e){
			e.preventDefault();
			--clicks;
			console.log(clicks);
			if( clicks == 2 ){
				c.find('.non-clickable').removeClass().addClass('btnDel');
			}

			$(this).parents(':eq(2)').remove();
		});

	});


</script>