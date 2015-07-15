<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css" />

    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src=<?php echo base_url('js/vendor/bootstrap.min.js'); ?> ></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    <script src=<?php echo base_url('bootstrap/js/custom.js'); ?> ></script>
 	<script type="text/javascript">
 	var return_data = <?php echo json_encode($cabs);?>;

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
    <style>
        .user-dropdown{
              width:90px;
          }
    </style>
</head>
<body>
        <div class="container content">
            <h2>Cabs</h2>
            <div class="span12 lists">
                <h3 style="margin-top:0%";>Booked Cabs</h3>
                <?php if($cabs) { ?>
                <table id="flight-table" class="table table-hover cust-table">
                    <thead>
                        <tr>
                            <th>BookingNo</th>
                            <th>BookingId</th>
                            <th>ConfiramtionNo</th>
                            <th>User ID</th>
                            <th>User E-Mail ID</th>
                            <th>Source</th>
                            <th>Destination/Km</th>
                            <th>Travel Date</th>
                            <th>Car Type</th>   
                            <th>Convenience Charge</th>
                            <th>Published Price</th>
                            <th>Offered Price</th>
                            <th>Total Fare</th>
                            <th>Booking Date</th>
                            <th>Booking Status</th>
                            <th>Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $current_date = date('c', strtotime('now'));?>
                        <?php foreach($cabs as $cab) {
                            echo '<tr>';
	                            echo '<td>'.$cab->booking_ref_id.'</td>';
                                echo '<td>'.$cab->fb_bookingId.'</td>';
	                            echo '<td>'.$cab->confirm_ref_id.'</td>';
	                            echo '<td>'.$cab->user_id.'</td>';
                                echo '<td>'.$cab->user_email.'</td>';
	                            echo '<td>'.$cab->cab_src.'</td>';
                                echo '<td>'.$cab->cab_dest.'</td>';
	                        	echo '<td>'.date('d-m-Y', strtotime($cab->journey_date)).'</td>';
	                            echo '<td>'.$cab->car_type.'</td>';
                                echo '<td>'.$cab->convenience_charge.'</td>';
                                echo '<td>'.$cab->published_price.'</td>';
                                echo '<td>'.$cab->offered_price.'</td>';
                                echo '<td>'.$cab->total_fare.'</td>';
	                            echo '<td>'.date('d-m-Y', strtotime($cab->booking_date)).'</td>';
	                            echo '<td>'.$cab->booking_status.'</td>';
                                if( $current_date > $cab->booking_date ){
                                    $book_btn = '<td>Closed</td>';
                                }else{
                                    if( $cab->booking_status == "success" ) {
                                        $book_btn = '<td><a href="'.site_url('cab_api/cabs/cancel_booking?ref_id='.$cab->confirm_ref_id.'&booking_id='.$cab->booking_ref_id).'" class="cancel" title="Cancel"><span class="glyphicon glyphicon-remove"></i></a></td>';
                                    }else{
                                        $book_btn = '<td></td>';
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