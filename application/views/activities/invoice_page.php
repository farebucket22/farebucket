<?php
@session_start();
$cust_support_data = cust_support_helper();
?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Farebucket | Activity</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('img/favicon.ico')?>">
		<link rel="stylesheet" href="<?php echo base_url('css/bootstrap24.min.css'); ?>" media="all">
		<link rel="stylesheet" href="<?php echo base_url('css/bootstrap-theme.min.css'); ?>" media="all">
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300|Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo base_url('css/main.css'); ?>" media="all">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="<?php echo base_url();?>js/vendor/bootstrap.min.js"></script>
</head>
<style>
    .disclaimer{
        list-style-type: none;
        margin-top: 8px;
    }
	.terms-container{
		font-size:12px;
	}
	h2{
		font-size:13px;
	}
	.etckt-container{
		text-align: right;
	}

	.intin-container{
		border-top: 2px solid #27ae60;
	}

	.intin-container h3{
		float:left;
	}

	.tckt-dep{
		font-style: italic;
	}

	.passenger-list{
		padding: 25px;
		outline: 4px solid #27ae60;
	}

	.passenger-headers h6{
		font-size: 13px;
		font-weight: bold;
	}
	.customerSupportInfo{
		margin-top:50px;
		margin-bottom: 30px;
	}

	body {
		margin: 0;
		padding: 0;
		background-color: #FAFAFA;
		font: 12pt "Tahoma";
	}

	*{
		box-sizing: border-box;
		-moz-box-sizing: border-box;
	}

	.wrap {
		padding: 1cm;
		height: 237mm;
	}

	@page {
		size: A4;
		margin: 0;
	}

	@media print {

		.etckt-container{
			text-align: right;
		}

		.intin-container{
			border-top: 2px solid #27ae60;
		}

		.intin-container h3{
			float:left;
		}

		.tckt-dep{
			font-style: italic;
		}

		.passenger-list{
			padding: 25px;
			outline: 4px solid #27ae60;
		}

		.passenger-headers h6{
			font-size: 13px;
			font-weight: bold;
		}

		.col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-13, .col-lg-14, .col-lg-15, .col-lg-16, .col-lg-17, .col-lg-18, .col-lg-19, .col-lg-20, .col-lg-21, .col-lg-22, .col-lg-23, .col-lg-24 {
		float: left;
		}
		.col-lg-24 {
		width: 100%;
		}
		.col-lg-23 {
		width: 95.83333333333333%;
		}
		.col-lg-22 {
		width: 91.66666666666666%;
		}
		.col-lg-21 {
		width: 87.5%;
		}
		.col-lg-20 {
		width: 83.33333333333334%;
		}
		.col-lg-18 {
		width: 75%;
		}
		.col-lg-16 {
		width: 66.66666666666666%;
		}
		.col-lg-14 {
		width: 58.333333333333336%;
		}
		.col-lg-13 {
		width: 51.66666666666667%;
		}
		.col-lg-12 {
		width: 50%;
		}
		.col-lg-11 {
		width: 45.833333333333333%;
		}
		.col-lg-10 {
		width: 41.66666666666667%;
		}
		.col-lg-9 {
		width: 37.5%;
		}
		.col-lg-8 {
		width: 33.33333333333333%;
		}
		.col-lg-7 {
		width: 29.16666666666667%;
		}
		.col-lg-6 {
		width: 25%;
		}
		.col-lg-5 {
		width: 20.833333333333333%;
		}
		.col-lg-4 {
		width: 16.666666666666664%;
		}
		.col-lg-3 {
		width: 12.5%;
		}
		.col-lg-2 {
		width: 8.333333333333332%;
		}
		.col-lg-1 {
		width: 4.166666666666666%;
		}

	}

</style>
<body>
	<div class="wrap">
		<div class="container clear-top">
			<div class="row">
				<div class="col-lg-12 col-xs-24 img-container">
					<img class="img-responsive logo" src="<?php echo base_url();?>/img/logo.png" alt="FareBucket Logo" />
				</div>
				<div class="col-lg-12 col-xs-24 etckt-container">
					<h2>E-Ticket</h2>
					<h3>Farebucket Booking ID - <span> <?php if( $data->booking_id != 0 )  {echo $data->fb_bookingId;} else {echo "failure";}; ?></span></h3>
					<h5>Booking Date - <span><?php echo date("l, d-m-y H:i:s");?> hrs</span></h5>
				</div>
			</div>
			<div class="row">
				<h4>Itinerary</h4>
				<div class="row intin-container">
					<h3 class="activity_name"><?php echo $data->activity_name;?></h3>
					<h3 class="nbsp">&nbsp;-&nbsp;</h3>
					<h3 class="subact_name"><?php echo $data->sub_activity_name;?></h3>
					<h3 class="nbsps">&nbsp;|&nbsp;</h3>
					<h3 class="act_date"><?php echo date("l, d-m-y", strtotime($data->activity_booking_date));?></h3>
				</div>
			</div>
			<div class="row passenger-list">

				<?php 
					if( $data->adult_title_string != NULL ) {
						$adult_title = explode(",",($data->adult_title_string));
						$adult_first = explode(",",($data->adult_first_name_string));
						$adult_last  = explode(",",($data->adult_last_name_string));
					} else {
						$adult_title = $adult_first = $adult_last = NULL;
					}
				?>


				<?php 
					if( $data->child_title_string != NULL ) {
						$child_title = explode(",",($data->child_title_string));
						$child_first = explode(",",($data->child_first_name_string));
						$child_last  = explode(",",($data->child_last_name_string));
					} else {
						$child_title = $child_first = $child_last = NULL;
					}
				?>
				<?php
					$adult_count = 0;
					$child_count = 0;
					if( $adult_title ){ $adult_count = count($adult_title); } else { $adult_count = 0;};
					if( $child_title ){ $child_count = count($child_title); } else { $child_count = 0;};
				?>
				
				<div class="col-lg-12 col-xs-12 passenger-headers">
					<h6>Passenger Names</h6>
					<?php for( $i=0 ; $i < $adult_count ; $i++ ){?>
						<p class="pass_details"><?php echo $adult_title[$i];?> <?php echo $adult_first[$i];?> <?php echo $adult_last[$i];}?></p>
					<?php for( $i=0 ; $i < $child_count ; $i++ ){?>
						<p class="pass_details"><?php echo $child_title[$i];?> <?php echo $child_first[$i];?> <?php echo $child_last[$i];}?></p>
				</div>
				<div class="col-lg-6 col-xs-6 passenger-headers">
				
					<h6>Type</h6>
					<?php for($i = 0 ; $i < $adult_count ; $i++) {
						echo "<p class='pass_details'>Adult</p>";
					};?>

					<?php for($i = 0 ; $i < $child_count ; $i++) {
						echo "<p class='pass_details'>Child</p>";
					};?>
				</div>
				<div class="col-lg-6 col-xs-6 passenger-headers">
					<h6>Amount</h6>
					<p class="pass_details">&#x20B9; <?php echo $data->activity_booking_amount?></p>
				</div>
			</div>
			<div class="row center-align-text form-padding">
				<button id="cmd" class="btn btn-change">Print Ticket</button>
			</div>
			<div class="row">
				<div class="col-lg-24 col-xs-24 passenger-headers form-padding baggageInfo">
					<h3>Activity Details</h3>
					<?php echo $data->activity_details;?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-24 passenger-headers form-padding baggageInfo">
					<h3>Activity Address</h3>
					<p><?php echo $data->activity_address?></p>
					<p><span class="h5">Phone Num: </span><?php echo $data->activity_phone_num?></p>
				</div>
			</div>
			<div class="row customerSupportInfo">
				<div class="col-lg-offset-8  col-lg-8 addnInfoBorder">
					<center><h6>For any queries or clarifications, contact Customer Support:</h6></center>
					<table class="table table-default">
						<tr>
							<td class="h4 left-text">Phone:</td>
							<td class="h4 right-text"><?php echo $cust_support_data->phone_number;?></td>
						</tr>
						<tr>
							<td class="h4 left-text">Email ID:</td>
							<td class="h4 right-text"><?php echo $cust_support_data->email;?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	<!--terms and conditions-->
	<?php if( isset($data) && $data == 'footer_link'):?>
		<div class="wrap">
			<div class="container main clear-top">
	<?php endif;?>
	<div class="modal-wrap container terms-container">
		<div class="center-align-text"><h3>FareBucket Terms and Conditions</h3></div>
		<div class="modal-section">
			<h2>INTRODUCTION</h2>
			<p>         
				<a href="<?php echo site_url();?>" target="_blank">www.farebucket.com</a> is a website operated by Reddy Trip LLP hereinafter referred to as ‘REDDY TRIP’. This page sets forth the terms and conditions under which REDDY TRIP provides the information on this Website, as well as the terms and conditions governing users use of this site. By making use of this site, users accept the disclaimer outlined below in full; accordingly, If users do not accept these terms and conditions, do not continue to use or access this site. <a href="<?php echo site_url();?>" target="_blank">www.farebucket.com</a> website uses cookies; by using our website or agreeing to this disclaimer, users consent to our use of cookies in accordance with the terms of our privacy policy.
			</p>
		</div>
		<div class="modal-section">
			<h2>ACCURACY OF INFORMATION</h2>
			<p>
				REDDY TRIP has taken reasonable care to ensure that the information posted on the website is accurate. However, REDDY TRIP does not warrant or guarantee the accuracy or completeness of the information provided on this Website. Under no circumstances will REDDY TRIP be liable for any loss or direct, indirect, incidental, special or consequential damages caused by reliance on this information. The information on this Website <a href="<?php echo site_url();?>" target="_blank">www.farebucket.com</a>/terms and conditions may be changed or updated without notice. Users are deemed to be apprised of and bound by such changes. REDDY TRIP may also make improvements and/or changes in the products, services and/or programs described on this site at any time without notice. REDDY TRIP hereby disclaims all warranties and conditions with regard to this information, software, products, services and related graphics, including all implied warranties and conditions of merchantability, fitness for a particular purpose, title and non-infringement. In the event that users wish to book any of the services online, users may use the hyperlinks provided on the website. When users book the services online at www.farebucket.com, please ensure that all the information provided by users such as names, dates of travel, passport numbers etc is accurate. REDDY TRIP cannot be liable for any erroneous information given by users. REDDY TRIP will not in any way be liable for any loss or inconvenience suffered by users as a result of erroneous information provided by users. Users would have to make payment for the services booked by them by using users credit / debit card or Internet banking. Please note that REDDY TRIP does not store users credit/debit card numbers or credit / debit card information nor does REDDY TRIP store users bank account information with us. Users credit/debit card transaction or Internet banking transaction is carried out at the payment gateway which is owned by the respective bank.
			</p>
		</div>
		<div class="modal-section">
			<h2>CANCELLATION POLICY</h2>
			<p>
				The cancellation charges depend on the airline, sector, class booking, and time of cancellation. To know what’s applicable in the users case, check the fare rules mentioned on the booking page when users are making your reservation (in case users missed it then, users can always go back to their farebucket account and check out your trips pages). With farebucket, users can cancel your flight no later than 3 hours before the time of departure. If it’s later than 3 hours, users need to contact the airline directly for cancellation. Cancellation can be initiated through My trips or Login page of farebucket. After logging in, the booking can be selected from the upcoming trips and selected for cancellation.
			</p>
		</div>
		<div class="modal-section">
			<h2>REFUND POLICY</h2>
			<p>
				REDDY TRIP will credit the money back to the same account users used while making the booking. For example, if users used your credit card, we will make an appropriate charge reversal. If users used their debit card, we will credit the money back to the debit card. REDDY TRIP usually process the refund within 4 working days of cancellation. However, it may take slightly longer to reflect in the users account statement depending upon their bank. We’ve noticed that it takes about 14 working days for most refunds to hit their respective accounts.
			</p>
		</div>
		<div class="modal-section">
			<h2>SHIPPING/DELIVERY POLICY</h2>
			<p>
				Products and services sold in www.farebucket.com by REDDY TRIP are delivered electronically via email and no physical items are delivered to the customer.
			</p>
		</div>
		<div class="modal-section">
			<h2>THIRD PARTY PRODUCTS</h2>
			<p>
				In case of third party products displayed on our website, REDDY TRIP take no responsibility for the contents, quality or safety of the product in any way. REDDY TRIP would not be in any way liable or responsible for any loss, damage or injury sustained by the User as a result of availing such products and services advertised by third parties on the website. REDDY TRIP LLP shall be in no way responsible for the content of the advertisers. It may be noted that REDDY TRIP does not in any way or in any manner endorse the products or services advertised on its website by third party advertisers. Users would be responsible for verifying the contents and information provided in such advertisements before making any decisions based on the same.
			</p>
		</div>
		<div class="modal-section">
			<h2>CONFIDENTIALITY</h2>
			<p>
				Information concerning REDDY TRIP or any of its subsidiaries, their employees, users, agents, or others on whom data is collected, stored, or processed, is the property of REDDY TRIP and is confidential except for the necessary disclosures required by law. Users may register for receiving the newsletter by submitting your e-mail address. We may use your e-mail address for sending users information and updates about travel related offers. In the event that users wish to unsubscribe from our mailing list, users can do so anytime by clicking on the link provided for the same.
			</p>
		</div>
		<div class="modal-section">
			<h2>COPYRIGHT POLICY</h2>
			<p>
				REDDY TRIP LLP is the copyright owner of the content of this website. Users may cite or refer to the information on this site and make copies of the information for your own non-commercial use. Some photographs may be copyrighted by their respective owners and may not be used without appropriate permissions.
			</p>
		</div>
		<div class="modal-section">
			<h2>INDEMNITY</h2>
			<p>
				The User shall hold REDDY TRIP fully indemnified and harmless incase of any claim, demand, damages, compensation, fines, penalties, expenses, costs incurred towards any suit, legal action, application, revision, writ petition, execution proceedings, or any other legal proceedings are initiated against REDDY TRIP due to any action /s of the User in using the website.
			</p>
		</div>
		<div class="modal-section">
			<h2>ENFORCEABILITY OF THESE PROVISIONS</h2>
			<p> 
				Should any of these terms and conditions be held invalid, that invalid provision shall be construed to be consistent with the applicable law, and in manner so as to remain consistent with the original intent of REDDY TRIP. Provisions not otherwise held invalid shall remain in force.
			</p>
		</div>
		<div class="modal-section">
			<h2>SECURITY WARNINGS</h2>
			<p>
				It is up to users to take precautions to ensure that whatever users select for your use is free of such items as viruses, worms, Trojan horses, malicious codes and other items of a destructive nature. IN NO EVENT WILL REDDY TRIP BE LIABLE TO ANY PARTY FOR ANY LOSS OR DIRECT, INDIRECT, INCIDENTAL, SPECIAL OR CONSEQUENTIAL DAMAGES CAUSED BY USE OF THIS WEBSITE, OR ANY OTHER HYPERLINKED WEBSITE.
			</p>
		</div>
		<div class="modal-section">
			<h2>PRODUCTS &amp; SERVICES</h2>
			<p>
				Please note that none of the information contained in this Website namely <a href="<?php echo site_url();?>">WWW.FAREBUCKET.COM</a> should be viewed or construed as an offer to sell or as a solicitation to purchase any of our products or services. Rather, the information on our products and services is provided to users so that users can learn what products REDDY TRIP generally offers. The products and service statements on this Website are for general description purposes only. It must not be construed that by advertising itineraries and packages to various destinations, any warranty or representation is made by the website to the User as regards the suitability and safety of any particular destination.
			</p>
		</div>
		<div class="modal-section">
			<h2>LIMITATION OF LIABILITY</h2>
			<p>
				The Services and Products made available on <a href="<?php echo site_url();?>">www.farebucket.com</a> are subject to conditions imposed by the respective Service Providers (Airline, Hotels, Car Rentals, Cruises, etc), including but not limited to tariffs, conditions of carriage, national &amp; international conventions and arrangements, and government regulations. REDDY TRIP has no control over these Independent service providers and will not be liable for any direct, indirect or consequential damages arising out of the usage of these services provided on this website. Furthermore, REDDY TRIP absolves itself of any such claims arising out of the usage of Hyperlinked URL’s, unauthorized Access to or use or record or any Acts of Omissions, Negligence, Interruption, Cancellation, Delay, Technical and Mechanical Failures, Deficiency in services of the Independent Service Providers or usage of Services provided by the independent service providers through this website, including, without any limitation the use of or inability to use any component of this site for Reservations or Ticketing. In any case, no liability on the part of REDDY TRIP arising in any way in respect of any ticket, tour, holiday, excursion facility shall not exceed the total amount paid for the ticket, tour, holiday, service and shall in no case include any consequential loss or additional expense whatsoever. REDDY TRIP will not be liable to users in respect of any losses arising out of any event or events beyond our reasonable control REDDY TRIP will not be liable to users in respect of any business losses, including (without limitation) loss of or damage to profits, income, revenue, use, production, anticipated savings, business, contracts, commercial opportunities or goodwill arising either directly or indirectly.
			</p>
		</div>
		<div class="modal-section">
			<h2>VARIATION</h2>
			<p> 
				REDDY TRIP may revise this disclaimer from time to time. The revised disclaimer shall apply to the use of our website from the time of publication of the revised disclaimer on the website.
			</p>
		</div>
		<div class="modal-section">
			<h2>PROFESSIONAL ADVICE</h2>
			<p>
				The information provided on this site is distributed with the understanding that REDDY TRIP is not providing professional advice of any type. If users have a question requiring professional advice, such as question relating to law, tax or travel arrangements, please seek the advice of a qualified professional in the relevant field.
			</p>
		</div>
		<div class="modal-section">
			<h2>WARRANTIES</h2>
			<p>
				REDDY TRIP specifically disclaims all warranties with respect to this Web site or your use thereof, express, implied, or otherwise, including without limitation, all warranties of merchantability and fitness for a particular purpose. REDDY TRIP shall not be liable for any damages resulting from the use or misuse of this site or the information on this site. This disclaimer, limitation of liability and exclusions shall apply irrespective of whether the damages arise from (a) Breach of Warranty (b) Negligence (c) Breach of Contract and (d) any other cause of action to the extent such limitation and exclusion are not rendered invalid by applicable law.
			</p>
		</div>
		<div class="modal-section">
			<h2>APPLICABLE LAW, JURISDICTION, ARBITRATION &amp; CONSENT</h2>
			<p>
				The user consents that this Agreement will be interpreted and enforced in only accordance with the laws of India exclusively and any action to enforce this agreement shall be brought only in the courts at Chennai who shall have sole and exclusive jurisdiction. The user consents that this agreement shall be governed by and construed in accordance with the provisions of The Arbitration and Conciliation Act, 1996 or its statutory modifications in force for the time being. Any dispute or difference(s) whatsoever that may arise between the user and REDDY TRIP shall be referred to Arbitration by a Sole Arbitrator to be appointed by REDDY TRIP LLP. The venue and seat of Arbitration shall be at Chennai, India. The award of the sole arbitrator shall be final and binding on the parties and each party shall bear the cost of arbitration equally unless awarded otherwise by the Sole Arbitrator.
			</p>
		</div>
		<div class="modal-section">
			<h2>Our Distribution Partners and Suppliers</h2>
			<p>
				Our products are made available through our third-party distribution partners and suppliers. Our distribution partners and suppliers may have privacy policies that differ from Farebucket's Privacy Policy. You should also be aware that some product or tour suppliers and some of our distribution partners may need additional Personal or Other Information (such as health or physical fitness information) to determine whether you can participate in an activity. You should read and rely upon the relevant supplier's or partner's Privacy Policy only (and not Farebucket's Privacy Policy), if Farebucket does not collect this information from you directly. Some suppliers or distribution partners may operate in countries or states that do not have laws or regulations for the protection and safeguarding of Personal Information. Farebucket seeks to take reasonable steps to persuade all its suppliers and distribution partners to adopt and adhere to privacy policies that are substantially similar to this one. However, please be aware that Farebucket is not in a position to force adherence, so we will rely on you and our other Customers to inform us if your privacy is not being adequately protected by our suppliers or distribution partners. Farebucket may then seek to take steps (including severing its relationship with offending suppliers or distribution partners) to help prevent other occurrences.
			</p>
		</div>
		<div class="modal-section">
			<h2>Disclaimers &amp; Limitations of Liability</h2>
			<ul class="remove-padding">
				<li class="disclaimer">Under no circumstances will Farebucket or its agents, affiliates, service providers, suppliers, and/or distributors be liable for any of the following losses or damage (whether such losses where foreseen, foreseeable, known or otherwise): (a) loss of data; (b) loss of revenue or anticipated profits; (c) loss of business; (d) loss of opportunity; (e) loss of goodwill or injury to reputation; (f) losses suffered by third parties; or (g) any indirect, consequential, special or exemplary damages arising from the use of Farebucket.com regardless of the form of action.</li>
				<li class="disclaimer">Farebucket and its agents and suppliers, in making arrangements for hotels, tours, transportation or any service in connection with the itineraries of individual customers, shall not be liable for injury, damage, loss, accident, delay or irregularity, liability or expense to person or property due to act of default by any hotel, carrier or other company or person providing services included in the tours.</li>
				<li class="disclaimer">Furthermore, Farebucket and its agents and suppliers accept no responsibility for any sickness, pilferage, labor disputes, machinery breakdown, government restrains, acts of war and/or terrorism, weather conditions, defect in any vehicle of transportation or for any misadventure or casualty or any other causes beyond their control.</li>
				<li class="disclaimer">Farebucket’s content - including the information, names, images, pictures, logos, prices, dates, and availability regarding or relating to Farebucket, Farebucket.com and/or to a Farebucket-affiliated website, service provider, operator and/or distribution partner is provided “AS IS” and on an “AS AVAILABLE” basis without any representations or any kind of warranty made (whether express or implied by law) to the extent permitted by law, including the implied warranties of satisfactory quality, fitness for a particular purpose, non-infringement, compatibility, security and accuracy.</li>
				<li class="disclaimer">At Farebucket we check and recheck the details about all the products and services we offer for accuracy. However, Farebucket does not warrant that functionality, content or information contained on Farebucket.com or any Farebucket-affiliated website will be uninterrupted or error free, that defects will be corrected, or that Farebucket.com or the servers that make it available are free of viruses or bugs.</li>
				<li class="disclaimer">If any of these terms are determined to be illegal, invalid or otherwise unenforceable by reason of the laws of any state or country in which these terms are intended to be effective, then to the extent and within the jurisdiction in which that term is illegal, invalid or unenforceable, it shall be severed and deleted from these terms and the remaining terms shall survive, remain in full force and effect and continue to be binding and enforceable.</li>
			</ul>
		</div>
		<div class="modal-section">
			<h2>Restrictions on Use</h2>
			<ul class="remove-padding">
				<li class="disclaimer">
					You agree that you will not, and will not assist or enable others to:
					<ul class="remove-padding">
						<li class="disclaimer">use the site to threaten, stalk, defraud, incite, harass, or advocate the harassment of another person, or otherwise interfere with another user's use of the site;</li>
						<li class="disclaimer">use the site to submit or transmit spam, chain letters, contests, junk email, pyramid schemes, surveys, or other mass messaging, whether commercial in nature or not;</li>
						<li class="disclaimer">use the site in a manner that may create a conflict of interest, such as trading reviews with other business owners or writing or soliciting reviews;</li>
						<li class="disclaimer">use the site to promote bigotry or discrimination against protected classes;</li>
						<li class="disclaimer">use the site to violate any third-party right, including any breach of confidence, copyright, trademark, patent, trade secret, moral right, privacy right, right of publicity, or any other intellectual property or proprietary right;</li>
						<li class="disclaimer">use the site to submit or transmit pornography or illegal content;</li>
						<li class="disclaimer">use the site to solicit personal information from minors or to harm or threaten to cause harm to minors;</li>
						<li class="disclaimer">use any robot, spider, site search/retrieval application, or other automated device, process or means to access, retrieve, scrape, or index the site or any site Content;</li>
						<li class="disclaimer">attempt to gain unauthorized access to the site, user accounts, computer systems or networks connected to the Site through hacking, password mining or any other means; use the Site or any Site Content to transmit any computer viruses, worms, defects, Trojan horses or other items of a destructive nature (collectively, "Viruses"); use any device, software or routine that interferes with the proper working of the Site, or otherwise attempt to interfere with the proper working of the Site; make excessive traffic demands; use the Site to violate the security of any computer network, crack passwords or security encryption codes; disrupt or interfere with the security of, or otherwise cause harm to, the site or site Content; remove, circumvent, disable, damage or otherwise interfere with any security-related features of the Site, features that prevent or restrict the use or copying of Site Content, or features that enforce limitations on the use of the Site.</li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="modal-section">
			<h2>CONTACT</h2>
			<p>
				Contact us at info@farebucket.com with any questions or problems with this website.
			</p>
		</div>
	</div>
	<?php if( isset($data) && $data == 'footer_link'):?>
	</div>
	</div>
	<?php endif;?>
	<!--terms and conditions end-->
	</div>
</body>
<script>
	$(document).ready(function(){
		$('#cmd').on('click', function(){
			$(this).hide();
			window.print();
		});
	});
</script>
</html>