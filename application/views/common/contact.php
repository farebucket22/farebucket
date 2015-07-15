<?php
	$cust_support_data = cust_support_helper();
?>
<head>
<title>Farebucket:Cheap Call Taxi Tariff Chennai | Cheap Hotel Packages | Book Cheap Hotels In Chennai</title>

<meta name="Subject" content="Cheap Call Taxi Tariff Chennai" />

<meta name="description" content="Farebucket often announce offers and these special offers help you create a perfect package. New schemes and new ideas are at galore here to make reservations more interesting and adventurous.Cheap Call Taxi Tariff Chennai  Cheap Hotel Packages , Book Cheap Hotels In Chennai" />

<meta name="keywords" content="Cheap Cab Rates In Chennai, Cheap Call Taxi In Chennai, Cheap Call Taxi Tariff Chennai, Cheap Hotel Packages, Book Cheap Hotels In Chennai, Cheap Cabs In Bangalore, 
Cheap Cabs In Chennai, Cheap Domestic Air Tickets, Book Cheap Air Tickets India,  Cheap Flight Tickets Booking, Cheap Air Tickets India, Lowest Airfare In India, Cheap Cab Services In Chennai" />

<meta name="Language" content="English" />

<meta name="Distribution" content="Global" />

<meta name="Robots" content="All" />

<meta name="Revisit-After" content="7 Days" />
</head>	
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
						<div class="col-lg-12 remove-padding">
							<h4>Customer Support:</h4>
							<span class="h5">For booking, cancellation quries and support, write to <br /><?php echo $cust_support_data->email;?></span>
						</div>
						<div class="col-lg-12 remove-padding">
							<h4>Business Enquiries:</h4>
							<span class="h5">For business enquiries, write to <br />admin@farebucket.com</span>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 remove-padding">
							<h4>Registered Address</h4>
							<span class='h5'>No. 5, 1st Cross <br />Vivekananda Nagar Pondicherry - 605006 <br />India <br />Phone: (0413) 2201458 - <br /><span class="h6 grey-text">please dont call this number for customer support</span></span>
						</div>
						<div class="col-lg-12 remove-padding">
							<h4>Operating Address</h4>
							<span class='h5'>47, Whites Road, Ground Floor<br />Desabhandu Plaza Whites Rd, Express Estate Thousand Lights<br /> Chennai, Tamil Nadu 600014 <br />Phone: 8144 096 096<br /><span class="h6 grey-text">please dont call this number for customer support</span></span>
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
								<div class="col-lg-24">
									<div id="recap-widget"></div>
									<input type="text" class="form-control" name="captcha" style="display:none;" id="captcha"/>
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
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
	(function(){
		$('#siteTitle').html('Farebucket | Contact');
		$('#contact_form').bootstrapValidator({
	        live: 'disabled',
	        excluded: ':disabled',
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
	            },
	            captcha: {
	                validators: {
	                    notEmpty: {
	                        message: "The captcha cannot be empty"
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
	    	contact_form.captcha = $('#captcha').val();

	    	var axn = $("#contact_form").attr('action');

	    	$.ajax({
	    		url: axn, 
	    		type: "post",
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
				if( clicked === "feedback" ){
					onloadCallback();
				}
			}
		});

		//recaptcha

		var verifyCallback = function(response) {
			$('#captcha').val(grecaptcha.getResponse(widgetId1));
			if( $('#captcha').val() !== "" ){
	            var sm = $('#contact_form').data('bootstrapValidator');
	            sm.updateStatus($('#captcha'), 'VALID');
			}

		};
		var widgetId1;
		var onloadCallback = function() {
			// Renders the HTML element with id 'recap-widget' as a reCAPTCHA widget.
			// The id of the reCAPTCHA widget is assigned to 'widgetId1'.
			widgetId1 = grecaptcha.render('recap-widget', {
				'sitekey' : '6Lc6mwQTAAAAAE-OnCrX64Pmg5WZDbzdFziZV_Qn',
				'theme' : 'white',
				'callback' : verifyCallback
			});
		};

	})();
</script>