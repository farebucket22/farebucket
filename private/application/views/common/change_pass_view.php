<div class="wrap">
	<div class="container clear-top main">
		<div class="row">
			<label for="email">Please enter the registered Email Id.</label>
			<div class="form-group">
				<div class="col-xs-8 control-label remove-padding">
					<input type="text" name="email" id="email" class="form-control" />
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<div class="col-xs-8 control-label remove-padding form-padding">
					<button type="button" class="btn btn-change" id="chk_email_btn">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
(function(){

	var pattern = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;

	$('#chk_email_btn').on('click', function(){

		if( $('input[name=email]').val() === '' || $('input[name=email]').val() === null ){
			$('#email').popover({
				content: 'Please enter a valid E-Mail ID first.',
				container: 'body',
				placement: 'right',
				trigger: 'click'
			}).click();
			return false;
		}
		if( !pattern.test($('input[name=email]').val()) ){
			$('#email').popover({
				content: 'Please enter a valid E-Mail ID first.',
				container: 'body',
				placement: 'right',
				trigger: 'click'
			}).click();
			return false;
		}
		var chk = $('input[name=email]').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('login/check_user');?>",
			data: { email : chk }
		})
		.done(function( retDat ){
			retDat = $.parseJSON(retDat);
			$('#email').popover({
				content: retDat,
				container: 'body',
				placement: 'right',
				trigger: 'click'
			}).click();
		});
	});

})();
</script>