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
 	var return_data = <?php echo json_encode($buses);?>;

        $(function(){
            var oTable = $('#flight-table').DataTable({ 
                "aaSorting": [],
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
		            $(nRow).attr('id', 'row-'+iDataIndex);
		        },
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
<style type="text/css">
    .user-dropdown{
          width:90px;
      }
</style>
<?php
    if($buses)
        foreach ($buses as $bus) {
        $bus->all_details = json_decode($bus->all_details);
    }
?>
<body>
        <div class="container content">
            <h2>Buses</h2>
            <div class="span12 lists">
                <h3 style="margin-top:0%";>Booked Buses</h3>
                <?php if($buses) { ?>
                <table id="flight-table" class="table table-hover cust-table">
                    <thead>
                        <tr>
                            <th>BookingId</th>
                            <th>User E-Mail ID</th>
                            <th>Date of issue</th>
                            <th>Date of journey</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Convenience Charge</th>
                            <th>Published Price</th>
                            <th>Offered Price</th>
                            <th>Total Fare</th>
                            <th>Status</th>
                            <th>Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $current_date = date('c', strtotime('now'));
                    ?>
                        <?php foreach($buses as $bus) {
                            echo '<tr>';
	                            echo '<td>'.$bus->fb_bookingId.'</td>';
                                echo '<td>'.$bus->user_email.'</td>';
                                echo '<td>'.date('jS M Y', strtotime($bus->all_details->dateOfIssue)).'</td>';
                                echo '<td>'.date('jS M Y', strtotime($bus->all_details->doj)).'</td>';
                                echo '<td>'.$bus->source.'</td>';
                                echo '<td>'.$bus->destination.'</td>';
	                            echo '<td>'.$bus->convenience_charge.'</td>';
                                echo '<td>'.$bus->published_price.'</td>';
                                echo '<td>'.$bus->offered_price.'</td>';
                                echo '<td>'.$bus->total_fare.'</td>';
	                            echo '<td>'.$bus->status.'</td>';
                                if( $current_date > date('c', strtotime($bus->all_details->doj)) ){
                                    $book_btn = "<td>closed</td>";
                                }else{
                                    if( $bus->status == "success" ){
                                        $book_btn = '<td><a href="'.site_url('bus_api/buses/cancelTicket?booking_no='.$bus->id).'" class="cancel" title="Cancel"><span class="glyphicon glyphicon-remove"></i></a></td>';
                                    }else{
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