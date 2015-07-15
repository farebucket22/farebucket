<?php if(isset($_SESSION['cancelResult'])){
        echo '<script language="javascript">';
        echo 'alert("Only Non LCC booking can be cancelled")';
        echo '</script>';
    }
    unset($_SESSION['cancelResult']);
?>
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
    <style>
    .user-dropdown{
          width:90px;
      }
</style>
</head>
<body>
        <div class="container content">
            <div class="span12 lists">
                <h3 style="margin-top:6%";>Booked Flights</h3>
                <?php if($flights) { ?>
                <table id="flight-table" class="table table-hover cust-table">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Booking ID</th>
                            <th>FB Booking ID</th>
                            <th>User E-Mail ID</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Arrival</th>
                            <th>Departure</th>
                            <th>Airline Name</th>
                            <th>Booking Date</th>
                            <th>Convenience Charge</th>
                            <th>Published Price</th>
                            <th>Offered Price</th>
                            <th>Total Fare</th>
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
                                echo '<td>'.$flight->fbBooking_id.'</td>';
                                echo '<td>'.$flight->lead_traveller_email.'</td>';
                                echo '<td>'.$flight->source.'</td>';
                                echo '<td>'.$flight->destination.'</td>';
                                echo '<td>'.$flight->arrival_time.'</td>';
                                echo '<td>'.$flight->departure_time.'</td>';
                                echo '<td>'.$flight->airline_name.'</td>';
                                echo '<td>'.$flight->date.'</td>';
                                echo '<td>'.$flight->ConvenienceCharge.'</td>';
                                echo '<td>'.$flight->PublishedPrice.'</td>';
                                echo '<td>'.$flight->OfferedPrice.'</td>';
                                echo '<td>'.$flight->TotalFare.'</td>';
                                echo '<td>'.$flight->status.'</td>';
                                if( $current_date > date('รง', strtotime('now')) ){
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