<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css" />

    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src=<?php echo base_url('js/vendor/bootstrap.min.js'); ?> ></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    <script src=<?php echo base_url('bootstrap/js/custom.js'); ?> ></script>
 	<script type="text/javascript">
 	var return_data = <?php echo json_encode($flights);?>;

        $(function(){
            var oTable = $('#flight-table').DataTable({ 
                "aaSorting": [],
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
		            $(nRow).attr('id', 'row-'+iDataIndex);
		        },
            });

            // row info
            $('#flight-table tbody').on('click', 'td', function () {
				var tr = $(this).closest('tr');
				var row = oTable.row( tr );
				var row_details_id = tr.attr('id');

				if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
				}
				else {
				// Open this row
				row.child( format( row_details_id ) ).show();
				tr.addClass('shown');
				}
			});

            $('.cancel').click(function(e){
            e.preventDefault;
                if(confirm("Are you sure you want to cancel this booking?"))
                    return true;
                else
                    return false;
            });
        });

	

    </script>
</head>
<body>
		 <div class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container">
                  <div class="navbar-header">
                    <a class="navbar-brand logo_image" href="#"><img src="<?php echo base_url(); ?>img/logo.png"></a>
                  </div>
                  <div class="navbar-collapse collapse header_container">
                  <ul class="nav navbar-nav headers">
                      <li class="dropdown user-dropdown " style="width:107px;">
                           <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px;">
                                <i class="fa fa-user" ></i>
                                Activity
                                <b class="caret caret_custom"></b>
                           </a>
                            <ul class="dropdown-menu" style="width:130px; min-width:0;">
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo site_url('admin/activity/add')?>" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Activity_Add
                                    </a>
                                </li>
                                <li style=" text-align: center; "><a href="<?php echo site_url('admin/activity/list_activity_booking');?>" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Booking_List
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo site_url('admin/activity')?>" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Activity_List
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo site_url('admin/sub_activity/add')?>" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        SubActivityAdd
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo site_url('admin/sub_activity')?>" >
                                          <i class="fa fa-power-off">
                                          </i>
                                          SubActivityList
                                      </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo base_url();?>index.php/admin/category/" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Category
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo base_url();?>index.php/admin/country/" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Country
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo base_url();?>index.php/admin/city/" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        City
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo base_url();?>index.php/admin/leave/" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Leave
                                    </a>
                                </li>
                                <li style=" text-align: center; padding-right: 10px;"><a href="<?php echo base_url();?>index.php/admin/vendor/" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Vendor
                                    </a>
                                </li>
                            </ul>
                      </li>
                       <li class="dropdown user-dropdown active" style="width:107px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user" ></i>
                            Flight
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  "><a href="<?php echo site_url('admin/flight')?>" >
                                    <i class="fa fa-power-off">
                                    </i>
                                    Booking List
                                </a>
                            </li>
                            <li style=" text-align: center; "><a href="<?php echo site_url('admin/flight/deduct');?>" >
                                        <i class="fa fa-power-off">
                                        </i>
                                        Service Charge
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:107px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user" ></i>
                            Hotel
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  "><a href="<?php echo site_url('admin/hotel')?>" >
                                    <i class="fa fa-power-off">
                                    </i>
                                    Booking List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:107px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user" ></i>
                            Cab
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  "><a href="<?php echo site_url('admin/cabs')?>" >
                                    <i class="fa fa-power-off">
                                    </i>
                                    Booking List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:107px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user" ></i>
                            Bus
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  "><a href="<?php echo site_url('admin/bus')?>" >
                                    <i class="fa fa-power-off">
                                    </i>
                                    Booking List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:107px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user" ></i>
                            Common
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">
                          <li style="text-align: center;">
                                <a href="<?php echo site_url('admin/flight/list_background');?>" >
                                    <i class="fa fa-power-off"></i>
                                    Backgrounds
                                </a>
                          </li>
                          <li style="text-align: center;">
                                <a href="<?php echo base_url();?>index.php/admin/discount/" >
                                    <i class="fa fa-power-off"></i>
                                    Discount
                                </a>
                          </li>
                           <li style="text-align: center;">
                                <a href="<?php echo site_url('admin/convenience_charge/');?>" >
                                    <i class="fa fa-power-off"></i>
                                    Convenience_charge
                                </a>
                          </li>
                          <li style="text-align: center;">
                                <a href="<?php echo site_url('admin/default_page');?>" >
                                    <i class="fa fa-power-off"></i>
                                    Default_Page
                                </a>
                           </li>
                        </ul>
                    </li>
                      <li class="dropdown user-dropdown" style="width:150px;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; margin-right: 15px; text-align:center;">
                                <i class="fa fa-user" ></i>
                                <span class="glyphicon glyphicon-user"></span>
                                <?php echo($this->session->userdata('admin_name'));?>
                                <b class="caret caret_custom" style="margin-top:-12%;"></b>
                            </a>
                            <ul class="dropdown-menu" style="width:135px; min-width:0;">

                                <li style=" text-align:center; padding-right:0px;"><a href="<?php echo site_url('admin/login')?>" >
                                        <i class="fa fa-power-off">

                                        </i>
                                        LogOut
                                    </a>
                                </li>
                            </ul>
                      </li>
                    </ul>
                  </div>
                </div>
    </div>
        <div class="container content">
            <h2>Flights</h2>
            <div class="span12 lists">
                <h3 style="margin-top:0%";>Booked Flights</h3>
                <?php if($flights) { ?>
                <table id="flight-table" class="table table-hover cust-table">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Booking ID</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Arrival</th>
                            <th>Departure</th>
                            <th>Airline Name</th>
                            <th>Booking Date</th>
                            <th>Fare</th>
                            <th>Booking Status</th>
                            <th>Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $current_date = date('c', strtotime('now'));?>
                        <?php foreach($flights as $flight) {
                            echo '<tr>';
	                            echo '<td>'.$flight->ticket_id.'</td>';
	                            echo '<td>'.$flight->booking_id.'</td>';
	                            echo '<td>'.$flight->source.'</td>';
	                            echo '<td>'.$flight->destination.'</td>';
                                echo '<td>'.$flight->arrival_time.'</td>';
                                echo '<td>'.$flight->departure_time.'</td>';
	                        	echo '<td>'.$flight->airline_name.'</td>';
	                            echo '<td>'.$flight->date.'</td>';
	                            echo '<td>'.$flight->total_fare.'</td>';
	                            echo '<td>'.$flight->status.'</td>';
                                if( $current_date > date('ç', strtotime('now')) ){
                                    $book_btn = "<td></td>";
                                }else{
                                    if( $flight->status == "Successful" ) {
                                        $book_btn = '<td><a href="'.site_url('api/flights/cancel_ticket_admin?ticket_id='.$flight->booking_id).'" class="cancel" title="Cancel"><span class="glyphicon glyphicon-remove"></i></a></td>';
                                    }
                                    else {
                                        $book_btn = "<td></td>";
                                    }
                                }
                                echo $book_btn;
                            echo '</tr>';
                        } ?>
                    </tbody>
                </table>
            <?php }
            else
              echo "No booked tickets available.";
            ?>
          </div>
        </div>
</body>
</html>

/*<!--?php echo base_url();?>index.php/admin/activity/*/ 