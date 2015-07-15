<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">    
    <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css" />
    
      
    <link href=<?php // echo base_url('bootstrap/css/bootstrap-responsive.css');?> rel="stylesheet" type="text/css">
    <!-- HTML5 shim for IE backwards compatibility -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src=<?php echo base_url('js/vendor/bootstrap.min.js'); ?> ></script>
    <script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand logo_image" href="#"><img src="<?php echo base_url(); ?>img/logo.png"></a>
            </div>
            <div class="navbar-collapse collapse header_container">
                <ul class="nav navbar-nav headers">
                    <li class="dropdown user-dropdown active" style="width:90px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px;">
                            <i class="fa fa-user"></i> Activity
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">
                            <li style=" text-align: center; padding-right: 10px;">
                                <a href="<?php echo site_url('admin/activity/add')?>">
                                    <i class="fa fa-power-off">
                                            </i> Activity_Add
                                </a>
                            </li>
                            <li style=" text-align: center; ">
                                <a href="<?php echo site_url('admin/activity/list_activity_booking');?>">
                                    <i class="fa fa-power-off">
                                            </i> Booking_List
                                </a>
                            </li>
                            <li style=" text-align: center; padding-right: 10px;">
                                <a href="<?php echo site_url('admin/activity')?>">
                                    <i class="fa fa-power-off">
                                            </i> Activity_List
                                </a>
                            </li>
                            <li style=" text-align: center; padding-right: 10px;">
                                <a href="<?php echo site_url('admin/sub_activity/add')?>">
                                    <i class="fa fa-power-off">
                                            </i> SubActivityAdd
                                </a>
                            </li>
                            <li style=" text-align: center; padding-right: 10px;">
                                <a href="<?php echo site_url('admin/sub_activity')?>">
                                    <i class="fa fa-power-off">
                                              </i> SubActivityList
                                </a>
                            </li>
                            <li style=" text-align: center; padding-right: 10px;">
                                <a href="<?php echo base_url();?>index.php/admin/category/">
                                    <i class="fa fa-power-off">
                                            </i> Category
                                </a>
                            </li>
                            <li style=" text-align: center; padding-right: 10px;">
                                <a href="<?php echo base_url();?>index.php/admin/country/">
                                    <i class="fa fa-power-off">
                                            </i> Country
                                </a>
                            </li>
                            <li style=" text-align: center; padding-right: 10px;">
                                <a href="<?php echo base_url();?>index.php/admin/city/">
                                    <i class="fa fa-power-off">
                                            </i> City
                                </a>
                            </li>
                            <li style=" text-align: center; padding-right: 10px;">
                                <a href="<?php echo base_url();?>index.php/admin/leave/">
                                    <i class="fa fa-power-off">
                                            </i> Leave
                                </a>
                            </li>
                            <li style=" text-align: center; padding-right: 10px;">
                                <a href="<?php echo base_url();?>index.php/admin/vendor/">
                                    <i class="fa fa-power-off">
                                            </i> Vendor
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown " style="width:90px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user"></i> Flight
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  ">
                                <a href="<?php echo site_url('admin/flight')?>">
                                    <i class="fa fa-power-off">
                                        </i> Booking List
                                </a>
                            </li>
                            <li style=" text-align: center; ">
                                <a href="<?php echo site_url('admin/flight/deduct');?>">
                                    <i class="fa fa-power-off">
                                    </i> Service Charge
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:90px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user"></i> Hotel
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  ">
                                <a href="<?php echo site_url('admin/hotel')?>">
                                    <i class="fa fa-power-off">
                                    </i> Booking List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:90px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user"></i> Cab
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  ">
                                <a href="<?php echo site_url('admin/cabs')?>">
                                    <i class="fa fa-power-off">
                                    </i> Booking List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:80px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user"></i> Bus
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">

                            <li style=" text-align: center;  ">
                                <a href="<?php echo site_url('admin/bus')?>">
                                    <i class="fa fa-power-off">
                                    </i> Booking List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown " style="width:110px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user"></i> Common
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">
                            <li style="text-align: center;">
                                <a href="<?php echo site_url('admin/flight/list_background');?>">
                                    <i class="fa fa-power-off"></i> Backgrounds
                                </a>
                            </li>
                            <li style="text-align: center;">
                                <a href="<?php echo base_url();?>index.php/admin/discount/">
                                    <i class="fa fa-power-off"></i> Discount
                                </a>
                            </li>
                            <li style="text-align: center;">
                                <a href="<?php echo site_url('admin/convenience_charge/');?>">
                                    <i class="fa fa-power-off"></i> Convenience_charge
                                </a>
                            </li>
                            <li style="text-align: center;">
                                <a href="<?php echo site_url('admin/default_page');?>">
                                    <i class="fa fa-power-off"></i> Default_Page
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown " style="width:140px;">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; ">
                            <i class="fa fa-user"></i> Cancellations
                            <b class="caret caret_custom"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:130px; min-width:0;">
                            <li style="text-align: center;">
                                <a href="<?php echo site_url('admin/cancellation');?>">
                                    <i class="fa fa-power-off"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user-dropdown" style="width:150px;">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 25px; margin-right: 15px; text-align:center;">
                            <i class="fa fa-user"></i>
                            <span class="glyphicon glyphicon-user"></span>
                            <?php echo($this->session->userdata('admin_name'));?>
                            <b class="caret caret_custom" style="margin-top:-12%;"></b>
                        </a>
                        <ul class="dropdown-menu" style="width:135px; min-width:0;">

                            <li style=" text-align:center; padding-right:0px;">
                                <a href="<?php echo site_url('admin/login')?>">
                                    <i class="fa fa-power-off">
                                    </i> LogOut
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
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
                            <td><?php echo $bus->id;?></td>
                            <td><?php echo $bus->fb_bookingId;?></td>
                            <td><?php echo date('D, jS M Y', strtotime($bus->all_details->doj));?></td>
                            <td><?php echo date('D, jS M Y', strtotime($bus->all_details->dateOfIssue));?></td>
                            <?php foreach( $users as $user ):?>
                                <?php if( $user->user_id == $bus->user_id ):?>
                                    <td><?php echo $user->user_email;?></td>
                                <?php endif;?>
                            <?php endforeach;?>
                            <td><?php echo $total_fare;?>
                            <?php if( $bus->AmountRefunded == "No" ):?>
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
                            <td><?php echo $flight->payu_id;?></td>
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
                            <td><?php echo $cab->booking_ref_id;?></td>
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
                            <td><?php echo $hotel->id;?></td>
                            <td><?php echo $hotel->fb_bookingId;?></td>
                            <?php foreach( $users as $user ):?>
                                <?php if( $user->user_id == $hotel->user_id ):?>
                                    <td><?php echo $user->user_email;?></td>
                                <?php endif;?>
                            <?php endforeach;?>
                            <?php if( $hotel->AmountRefunded == "No" ):?>
                                <td><button type="button" class="refund btn btn-default" data-tableid="<?php echo $hotel->id . " - HOTEL";?>">Refund</button></td>
                            <?php else:?>
                                <td><button type="button" class="refund btn btn-default" disabled="true">Refunded</button></td>
                            <?php endif;?>
                            <td><?php echo date('D, jS M Y', strtotime($hotel->cancellationDate));?></td>
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
                    console.log(dt);
                    var searchStr = dt.substr(0, 3) + ', ' + dt.substr(4, 3) + " " + dt.substr(8, 2) + " " + selectedDate.getFullYear();
                    console.log(searchStr);
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