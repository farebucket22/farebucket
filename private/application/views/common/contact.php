<?php
	$cust_support_data = cust_support_helper();
?>
<div class="wrap">
	<div class="container-fluid">
		<div id="SidebarWrapper">
			<!-- Sidebar -->
			<div id="sidebar-wrapper">
				<ul class="sidebar-nav">
					<li><a href="#" data-select="contact" class="h4">Contact Us</a></li>
					<li><a href="#" data-select="feedback" class="h4">Feedback</a></li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-offset-5 col-lg-19" id="contact">
				<div class="col-lg-offset-2 col-lg-17">
					<div class="row">
						<h4>Customer Support</h4>
						<div class="col-lg-10 remove-padding">
							<h5><?php echo $cust_support_data->phone_number;?></h5>
							<span class="h5 grey-text">Standard STD / Local rates apply</span>
						</div>
					</div>
					<div class="row btm-border">
						<h4>Email</h4>
						<div class="col-lg-16 remove-padding">
							<h4>Customer Support:</h4>
							<span class="h5">For booking, cancellation quries and support, write to <br /><?php echo $cust_support_data->email;?></span>
						</div>
						<div class="col-lg-8 remove-padding">
							<h4>Business Enquiries:</h4>
							<span class="h5">For business enquiries, write to <br />info@farebucket.com</span>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 remove-padding">
							<h4>Registered Address</h4>
							<span class='h5'>No. 5, 1st Cross <br />Vivekananda Nagar Pondicherry - 605006 <br />India <br />Phone: (0413) 2201458 - <br /><span class="h6 grey-text">please dont call this number for customer support</span></span>
						</div>
						<div class="col-lg-12 remove-padding">
							<h4>Operating Address</h4>
							<span class='h5'>47, Whites Roas, 1st Floor <br />Desabhandu Plaza Royapettah Chennai - 600014 <br />India <br />Phone: (044) 42124457 - <br /><span class="h6 grey-text">please dont call this number for customer support</span></span>
						</div>
					</div>
				</div>
				<div class="col-lg-5"></div>
			</div>
			<div class="col-lg-offset-5 col-lg-19" id="feedback">
				<div class="col-lg-offset-9 col-lg-10"><h4>Tell us what you think</h4><h6>At Farebucket we LOVE hearing from our customers. <br />We appreciate your feedback.</h6></div>
				<div class="col-lg-offset-2 col-lg-17">
					<form action="<?php echo site_url('common/send_mail_contact')?>" method="POST" id="contact_form" class="contact_form_width margin-center">
						<div class="row">
							<div class="form-group">
								<div class="col-lg-12 control-label">
									<input type="text" class="form-control" name="name" placeholder="Name" id="name" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-12 control-label">
									<input type="text" class="form-control" name="email" placeholder="E-Mail" id="email_id"/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group form-padding">
								<div class="col-lg-24 control-label">
									<textarea name="query" class="form-control" id="query" cols="25" rows="4" placeholder="Message"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-padding">
								<div class="col-lg-20">
									<h5 class="disp_msg"></h5>
								</div>
								<div class="col-lg-4 right-text">
									<button type="submit" class="btn btn-change" id="contact_form_btn">Submit</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="col-lg-5"></div>
			</div>
		</div>
	</div>
</div>
<script>
	(function(){
		$('#siteTitle').html('Farebucket | Contact');
		$('#contact_form').bootstrapValidator({
	        live: 'disabled',
	        fields: {
	            name: {
	                validators: {
	                    notEmpty: {
	                        message: 'The Name is required'
	                    }
	                }
	            },
	            email: {
	                validators: {
	                    notEmpty: {
	                        message: 'The E-Mail is required'
	                    },
                        emailAddress: {
                            message: 'A valid E-Mail address is required'
                        }
	                }
	            },
	            query: {
	                validators: {
	                    notEmpty: {
	                        message: 'Please Enter a Message'
	                    }
	                }
	            }
	        }
	    }).on('success.form.bv', function(e){
	    	e.preventDefault();

	    	contact_form = new Object;
	    	contact_form.name = $('#name').val();
	    	contact_form.email = $('#email_id').val();
	    	contact_form.query = $('#query').val();

	    	var axn = $("#contact_form").attr('action');
	    	$.ajax({
	    		url: axn, 
	    		type: "POST",
	    		data: {data : contact_form} 
	    	}).done(function(retData){
	    		retData = $.parseJSON(retData);
	    		if( retData ){
	    			$('h5.disp_msg').addClass('hulk-class');
	    			$('h5.disp_msg').html('Mail has been sent successfully!');
					setTimeout(function(){
						$('h5.disp_msg').fadeOut();
					}, 3000);
	    			$('#name').val('');
	    			$('#email_id').val('');
					$('#query').val('');
	    		}else{
	    			$('h5.disp_msg').addClass('has-error');
	    			$('h5.disp_msg').html('Unable to send mail, please try again.')
					setTimeout(function(){
						$('h5.disp_msg').fadeOut();
					}, 3000);
		    		$('#name').val('');
	    			$('#email_id').val('');
					$('#query').val('');
	    		}
	    	});
	    });
	    
		$('#feedback').hide();
		$('ul.sidebar-nav a').on('click', function(e){
			e.preventDefault();
			var clicked = $(this).data('select');
			if( clicked ){
				$('#'+clicked).show().siblings().hide();
			}
		});
	})();
</script>