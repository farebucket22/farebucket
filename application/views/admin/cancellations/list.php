<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">    
    <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css" />
    
      
    <link href=<?php // echo base_url('bootstrap/css/bootstrap-responsive.css');?> rel="stylesheet" type="text/css">
    <!-- HTML5 shim for IE backwards compatibility -->
        <!--[if lt IE 9]>
        <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src=<?php echo base_url('js/vendor/bootstrap.min.js'); ?> ></script>
    <script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>

    <style>
    .user-dropdown{
          width:90px;
      }
</style>
</head>
<body>
    <div class="container content">
        <h2 style="margin-top:18px;">Categories</h2>
      
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>
      
        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
        <?php endif; ?>
      
        <legend>Search Journey date</legend>
        <div class="span6 create-form">
            <input type="text" name="datepicker" id="journeyDateSearch" placeholder="Search Date" />
        </div>

        <div class="span12 lists">
            <h3>Cancellations</h3>

            <table id="cancel-table" class="table table-hover cust-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>status</th>
                        <th>pnr(or confirmation number)</th>
                        <th>Payu ID</th>
                        <th>Farebucket ID</th>
                        <th>Date of Journey</th>
                        <th>Date of Issue</th>
                        <th>user email</th>
                        <th>Total Fare</th>
                        <th>Amount Refunded</th>
                        <th>Cancellation Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($buses) && !empty($buses)):
                        foreach( $buses as $bus ): $bus->all_details = json_decode($bus->all_details);
                            $total_fare = 0;
                            if( is_array($bus->all_details->inventoryItems) ){
                                foreach($bus->all_details->inventoryItems as $ia){
                                    $total_fare += $ia->fare;
                                }
                            }else{
                                $total_fare = $bus->all_details->inventoryItems->fare;
                            }
                        ?>
                        <tr>
                            <td>bus</td>
                            <td><?php echo $bus->source;?></td>
                            <td><?php echo $bus->destination;?></td>
                            <td><?php echo $bus->status;?></td>
                            <td><?php echo $bus->all_details->pnr;?></td>
                            <td><?php echo $bus->payu_id;?></td>
                            <td><?php echo $bus->fb_bookingId;?></td>
                            <td><?php echo date('D, jS M Y', strtotime($bus->all_details->doj));?></td>
                            <td><?php echo date('D, jS M Y', strtotime($bus->all_details->dateOfIssue));?></td>
                            <?php foreach( $users as $user ):?>
                                <?php if( $user->user_id == $bus->user_id ):?>
                                    <td><?php echo $user->user_email;?></td>
                                <?php endif;?>
                            <?php endforeach;?>
                            <td><?php echo $total_fare;?>
                            <?php if( $bus->refundAmt === "No" ):?>
                                <td><button type="button" class="refund btn btn-default" data-tableid="<?php echo $bus->id . " - BUS";?>">Refund</button></td>
                            <?php else:?>
                                <td><button type="button" class="refund btn btn-default" disabled="true">Refunded</button></td>
                            <?php endif;?>
                            <td><?php echo date('D, jS M Y', strtotime($bus->cancellationDate));?></td>
                        </tr>
                    <?php endforeach;endif;?>
                    <?php if(isset($flights) && !empty($flights)):
                        foreach( $flights as $flight ):
                            if( empty($flight->OriginCityName) ){
                                $journeyArr = explode(",", $flight->ConnectingCityName);
                                $length = count($journeyArr) - 1;
                                $src = $journeyArr[0];
                                $dest = $journeyArr[$length];
                            }else{
                                $src = $flight->OriginCityName;
                                $dest = $flight->DestinationCityName;
                            }
                        ?>
                        <tr>
                            <td>flight</td>
                            <td><?php echo $src;?></td>
                            <td><?php echo $dest;?></td>
                            <td><?php echo $flight->status;?></td>
                            <td><?php echo $flight->pnr;?></td>
                            <td><?php echo $flight->PayuId;?></td>
                            <td><?php echo $flight->fb_bookingId;?></td>
                            <td><?php echo date('D, jS M Y', strtotime($flight->date));?></td>
                            <td><?php echo date('D, jS M Y', strtotime($flight->IssueDate));?></td>
                            <?php foreach( $users as $user ):?>
                                <?php if( $user->user_id == $flight->user_id ):?>
                                    <td><?php echo $user->user_email;?></td>
                                <?php endif;?>
                            <?php endforeach;?>
                            <td><?php echo $flight->total_fare?>
                            <?php if( $flight->AmountRefunded == "No" ):?>
                                <td><button type="button" class="refund btn btn-default" data-tableid="<?php echo $flight->id . " - FLIGHT";?>">Refund</button></td>
                            <?php else:?>
                                <td><button type="button" class="refund btn btn-default" disabled="true">Refunded</button></td>
                            <?php endif;?>
                            <td><?php echo date('D, jS M Y', strtotime($flight->cancellationDate));?></td>
                        </tr>
                    <?php endforeach;endif;?>
                    <?php if(isset($cabs) && !empty($cabs)):
                        foreach( $cabs as $cab ):?>
                        <tr>
                            <td>cab</td>
                            <td><?php echo $cab->cab_src;?></td>
                            <?php if(is_numeric($cab->cab_dest)):?>
                                <td><?php echo 'local';?></td>
                            <?php else:?>
                                <td><?php echo $cab->cab_dest;?></td>
                            <?php endif;?>
                            <td><?php echo $cab->booking_status;?></td>
                            <td><?php echo $cab->confirm_ref_id;?></td>
                            <td><?php echo $cab->payu_id;?></td>
                            <td><?php echo $cab->fb_bookingId;?></td>
                            <td><?php echo date('D, jS M Y', strtotime($cab->journey_date));?></td>
                            <td><?php echo date('D, jS M Y', strtotime($cab->booking_date));?></td>
                            <?php foreach( $users as $user ):?>
                                <?php if( $user->user_id == $cab->user_id ):?>
                                    <td><?php echo $user->user_email;?></td>
                                <?php endif;?>
                            <?php endforeach;?>
                            <td>100</td>
                            <?php if( $cab->AmountRefunded == "No" ):?>
                                <td><button type="button" class="refund btn btn-default" data-tabelid="<?php echo $cab->id . " - CAB";?>">Refund</button></td>
                            <?php else:?>
                                <td><button type="button" class="refund btn btn-default" disabled="true">Refunded</button></td>
                            <?php endif;?>
                            <td><?php echo date('D, jS M Y', strtotime($cab->cancellationDate));?></td>
                        </tr>
                    <?php endforeach;endif;?>
                    <?php if(isset($hotels) && !empty($hotels)):
                        foreach( $hotels as $hotel ):?>
                        <tr>
                            <td>hotel</td>
                            <td><?php echo $hotel->status;?></td>
                            <td><?php echo $hotel->payu_id;?></td>
                            <td><?php echo $hotel->fb_bookingId;?></td>
                            <?php foreach( $users as $user ):?>
                                <?php if( $user->user_id == $hotel->user_id ):?>
                                    <td><?php echo $user->user_email;?></td>
                                <?php endif;?>
                            <?php endforeach;?>
                            <td><?php echo $hotel->activity_booking_amount;?></td>
                            <?php if( $hotel->AmountRefunded == "No" ):?>
                                <td><button type="button" class="refund btn btn-default" data-tableid="<?php echo $hotel->id . " - HOTEL";?>">Refund</button></td>
                            <?php else:?>
                                <td><button type="button" class="refund btn btn-default" disabled="true">Refunded</button></td>
                            <?php endif;?>
                            <td><?php echo date('D, jS M Y', strtotime($hotel->cancellationDate));?></td>
                        </tr>
                    <?php endforeach;endif;?>
                    <?php if(isset($activity) && !empty($activity)):
                        foreach( $activity as $activity ):?>
                        <tr>
                            <td>Activity</td>
                            <td>ID:<?php echo $activity->activity_id;?></td>
                            <td>BookingID:<?php echo $activity->booking_id;?></td>
                            <td><?php echo $activity->booking_status;?></td>
                            <td></td>
                            <td><?php echo $activity->payu_id;?></td>
                            <td><?php echo $activity->fb_bookingId;?></td>
                            <td><?php echo date('D, jS M Y', strtotime($activity->activity_booking_date));?></td>
                            <td><?php echo date('D, jS M Y', strtotime($activity->booking_creation_date_time));?></td>
                            <?php foreach( $users as $user ):?>
                                <?php if( $user->user_id == $activity->user_id ):?>
                                    <td><?php echo $user->user_email;?></td>
                                <?php endif;?>
                            <?php endforeach;?>
                            <td><?php echo $activity->activity_booking_amount;?></td>
                            <?php if( $activity->AmountRefunded == "No" || $activity->AmountRefunded == "" ):?>
                                <td><button type="button" class="refund btn btn-default" data-tableid="<?php echo $activity->booking_id . " - ACTIVITY";?>">Refund</button></td>
                            <?php else:?>
                                <td><button type="button" class="refund btn btn-default" disabled="true">Refunded</button></td>
                            <?php endif;?>
                            <td><?php echo date('D, jS M Y', strtotime($activity->booking_update_date_time));?></td>
                        </tr>
                    <?php endforeach;endif;?>
                </tbody>
            </table>
        </div>
    </div>
</body>
    <script type="text/javascript">
        $(function(){
            var oTable = $('#cancel-table').DataTable({ 
                "aaSorting": [],
                "bJQueryUI": true,
                "sPaginationType": "full_numbers"
            });

            $('#journeyDateSearch').datepicker({
                changeYear: true,
                changeMonth: true,
                onSelect: function(){
                    var selectedDate = $(this).datepicker('getDate');
                    var dt = selectedDate.toString();
                    var searchStr = dt.substr(0, 3) + ', ' + dt.substr(4, 3) + " " + dt.substr(8, 2) + " " + selectedDate.getFullYear();
                    oTable.search(searchStr).draw();
                }
            });

            $('.refund').on('click', function(){
                var tableData = $(this).data('tableid');
                var tableArr = tableData.split('-');
                var tableId = tableArr[0];
                var tableModule = tableArr[1];
                var originalButton = $(this);

                $.ajax({
                    url: "<?php echo site_url('admin/cancellation/refund');?>",
                    type: "POST",
                    data: {tableId : tableId, module: tableModule},
                })
                .done(function (retData){
                    var retFlag = $.parseJSON(retData);
                    if(retFlag){
                        originalButton.html('Refunded');
                        originalButton.attr('disabled', 'disabled');
                    }else{
                        alert('An error occured Please try again.');
                    }
                });

            });

        });
    </script>
</html>