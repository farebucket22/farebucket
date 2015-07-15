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
 	var return_data = <?php echo json_encode($hotels);?>;

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
            <h2>Hotels</h2>
            <div class="span12 lists">
                <h3 style="margin-top:0%";>Booked Hotels</h3>
                <?php if($hotels) { ?>
                <table id="flight-table" class="table table-hover cust-table">
                    <thead>
                        <tr>
                            <th>BookingNo</th>
                            <th>ConfiramtionNo</th>
                            <th>User ID</th>
                            <th>User E-Mail ID</th>
                            <th>Destination</th>
                            <th>Hotel Name</th>
                            <th>CheckIn Date</th>
                            <th>CheckOut Name</th>   
                            <th>Fare</th>
                            <th>Booking Status</th>
                            <th>Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($hotels as $hotel) {
                            echo '<tr>';
	                            echo '<td>'.$hotel->BookingRefNo.'</td>';
	                            echo '<td>'.$hotel->ConfirmationNo.'</td>';
	                            echo '<td>'.$hotel->user_id.'</td>';
                                echo '<td>'.$hotel->user_email.'</td>';
	                            echo '<td>'.$hotel->destination.'</td>';
                                echo '<td>'.$hotel->hotel_name.'</td>';
	                        	echo '<td>'.$hotel->check_in.'</td>';
	                            echo '<td>'.$hotel->check_out.'</td>';
	                            echo '<td>'.$hotel->hotel_price.'</td>';
	                            echo '<td>'.$hotel->status.'</td>';
	                            if( $hotel->status == "Success" ) {
	                            	echo '<td><a href="'.site_url('new_request/hotel_cancel?booking_no='.$hotel->BookingRefNo).'" class="cancel" title="Cancel"><span class="glyphicon glyphicon-remove"></i></a></td>';
	                            }
	                            else {
	                            	echo "<td></td>";
	                            }
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