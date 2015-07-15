<?php if(isset($_GET['activity_status']) && $_GET['activity_status'] == 0):?>
	<script>
		alert('Sorry, Cancel is only possible 11 hours after booking.');
	</script>
<?php endif;?>

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
 	var return_data = <?php echo json_encode($activities);?>;

        $(function(){
            var oTable = $('#activity-table').DataTable({ 
                "aaSorting": [],
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
		            $(nRow).attr('id', 'row-'+iDataIndex);
		        },
            });

            // row info
            $('#activity-table tbody').on('click', 'td', function () {
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

            $('.delete').click(function(e){
            e.preventDefault;
                if(confirm("Are you sure you want to cancel this booking?"))
                    return true;
                else
                    return false;
            });
        });

	function format ( row_details_id ) {

        var temp_arr = row_details_id.split('-');
        var ind = temp_arr[1];

        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'
			+'<div class="row">'
				+'<div class="col-lg-4 ca-text">'
					+'<div>'+return_data[ind].lead_guest_title+'. '+return_data[ind].lead_guest_first_name+' '+return_data[ind].lead_guest_last_name+'</div>'
					+'<div>'+return_data[ind].lead_guest_email+'</div>'
					+'<div>'+return_data[ind].lead_guest_mobile+'</div>'
				+'</div>'
				+'<div class="col-lg-4 ca-text">'
					+'<div>'+return_data[ind].activity_description_short+'</div>'
				+'</div>'
				+'<div class="col-lg-4 ca-text">'
					+'<div>'+return_data[ind].activity_booking_amount+'</div>'
				+'</div>'
			+'</div>'
			+'<div class="row">'
				+'<div class="col-lg-6 ca-text">Created On: '+return_data[ind].booking_creation_date_time+'</div>'
				+'<div class="col-lg-6 ca-text">Last Updated On: '+return_data[ind].booking_update_date_time+'</div>'
			+'</div>'
		+'</table>';
	}
    </script>
</head>
<style>
    .user-dropdown{
          width:90px;
      }
</style>
<body>
        <div class="container content">
            <h2>Activities</h2>
            <div class="span12 lists">
                <h3 style="margin-top:0%";>Available Activities</h3>
                <?php if($activities) { ?>
                <table id="activity-table" class="table table-hover cust-table">
                    <thead>
                        <tr>
                            <th>User First Name</th>
                            <th>User Last Name</th>
                            <th>User Email</th>
                            <th>User Mobile</th>
                            <th>Activity Name</th>
                            <th>Activity Location Name</th>
                            <th>Activity Booking Date</th>
                            <th>Booking Status</th>
                            <th>Booking ID</th>
                            <th>Vendor Price</th>
                            <th>Convenience charge</th>
                            <th>Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($activities as $activity) {
                            echo '<tr>';
	                            echo '<td>'.$activity->user_first_name.'</td>';
	                            echo '<td>'.$activity->user_last_name.'</td>';
	                            echo '<td>'.$activity->user_email.'</td>';
                                echo '<td>'.$activity->user_mobile.'</td>';
	                            echo '<td>'.$activity->activity_name.'</td>';
	                        	echo '<td>'.$activity->activity_location_name.'</td>';
	                            echo '<td>'.$activity->activity_booking_date.'</td>';
	                            echo '<td>'.$activity->booking_status.'</td>';
                                echo '<td>'.$activity->fb_bookingId.'</td>';
                                echo '<td>'.$activity->totalVendorPrice.'</td>';
                                echo '<td>'.$activity->convenience_charge.'</td>';
	                            if( $activity->booking_status == "Active" ) {
	                            	echo '<td><a href="'.site_url('admin/activity/update_booking_status?booking_id='.$activity->booking_id).'" class="delete" title="Delete"><span class="glyphicon glyphicon-remove"></i></a></td>';
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
              echo "No Activities available.";
            ?>
          </div>
        </div>
</body>
</html>