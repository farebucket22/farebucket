<?php
$userId = $user_details[0]->user_id;
$dobArr = explode("-", $user_details[0]->user_dob);
$date = $dobArr[1]."/".$dobArr[2]."/".$dobArr[0];
?>
<style>

    .nav-tabs>li>a:hover{
        border-color: none;
    }

    .nav-tabs>li.active>a:focus{
        border: none;
        border-top: 2px solid #27ae60;
        box-shadow: none;
    }

    .nav-tabs li a, .nav-tabs li.active a {
        border: none;
        border-top: 2px solid #27ae60;
    }

    .nav-tabs li a:hover, .nav-tabs li.active a:hover {
        border: none;
        border-top: 2px solid #27ae60;
    }

    ul.userDashboardTabs{
        margin-top: 42px;
        margin-bottom: 100px;
        margin-right: 10px;
        float: left;
        width: 100%;
        border-bottom: 2px solid #27ae60;
    }
    
    .userDashboardTabs li {
        margin-left: 0; 
    }

    .nav-tabs>li{
        float:none;
        margin-bottom: 0;
    }

    .nav-tabs>li>a{
        margin-right: 0;
    }

    .tab-content{
        height: auto;
        border: none;
    }

    .userDashboardContainer{
        position: fixed;
        height: 80%;
        border-right: 2px solid #27ae60;
    }

    .container{
        max-width: 1000px;
        width:auto;
    }
</style>
<div class="wrap">
    <div class="container-fluid main clear-top userRegisterContainer">
        <div class="row">
            <div class="col-xs-4 userDashboardContainer remove-padding">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs userDashboardTabs" role="tablist">
                    <li class="active"><a href="#profile" role="tab" data-toggle="tab">Profile</a></li>
                    <li><a href="#password" role="tab" data-toggle="tab">Password</a></li>
                    <li><a href="#flights" id="flightsTab" role="tab" data-toggle="tab">Flights</a></li>
                    <li><a href="#buses" id="busesTab" role="tab" data-toggle="tab">Buses</a></li>
                    <li><a href="#cabs" id="cabsTab" role="tab" data-toggle="tab">Cabs</a></li>
                    <li><a href="#hotels" id="hotelsTab" role="tab" data-toggle="tab">Hotels</a></li>
                    <li><a href="#activities" id="activitiesTab" role="tab" data-toggle="tab">Activities</a></li>
                </ul>
            </div>

            <div class="col-xs-offset-4 col-xs-20">
                <!-- Tab panes -->
                <div class="tab-content userDashboardContentArea">
                    <div class="tab-pane active" id="profile">
                        <form class="row userProfileForm" method="POST" action="<?php echo site_url('user/update_profile'); ?>">
                            <div class="userProfileArea col-xs-16 col-xs-offset-4">
                                <div class="row profileContentHeading">User Profile</div>
                                <div class="row profileEditErrorMsg"></div>
                                <input name="userIdField" type="hidden" value="<?php echo $user_details[0]->user_id; ?>" />
                                <div class="row">
                                    <select class="userProfileField userTitleField" name ="userTitleField" disabled="disabled">
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Mrs">Ms</option>
                                    </select>
                                    <input type="text" class="userProfileField userFirstNameField" name="userFirstNameField" value="<?php echo $user_details[0]->user_first_name; ?>" disabled="disabled" />
                                    <input type="text" class="userProfileField userLastNameField" name="userLastNameField" value="<?php echo $user_details[0]->user_last_name; ?>" disabled="disabled" />
                                </div>
                                <div class="row">
                                    <input type="text" class="userProfileField userMobileField" name="userMobileField" value="<?php echo $user_details[0]->user_mobile; ?>" disabled="disabled" />
                                </div>
                                <div class="row">
                                    <input type="text" class="userProfileField userDobField" name="userDobField" id="datepicker" disabled="disabled" />
                                </div>
                                <div class="row">
                                    <textarea class="userProfileField userAddressField" name="userAddressField" placeholder="Address" disabled="disabled"><?php echo $user_details[0]->user_address; ?></textarea>
                                </div>
                                <div class="row">
                                    <button type="button" class="profileEditBtn"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;EDIT PROFILE</button>
                                    <button type="submit" class="profileSaveBtn" style="display: none;"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;SAVE PROFILE</button>
                                    <button type="button" class="profileEditCancelBtn" style="display: none;"><span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp;&nbsp;CANCEL</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="password">
                        <form class="row userPasswordChangeForm" method="POST" action="<?php echo site_url('user/update_password'); ?>">
                            <div class="userPasswordChangeArea col-xs-16 col-xs-offset-4">
                                <div class="row profileContentHeading">Change Password</div>
                                <div class="row passwordEditErrorMsg"></div>
                                <input name="userIdField" type="hidden" value="<?php echo $user_details[0]->user_id; ?>" />
                                <div class="row">
                                    <input type="password" class="userPasswordChangeField userOldPasswordField" name="userOldPasswordField" value="" placeholder="Old Password" />
                                </div>
                                <div class="row">
                                    <input type="password" class="userPasswordChangeField userNewPasswordField" name="userNewPasswordField" value="" placeholder="New Password" />
                                </div>
                                <div class="row">
                                    <input type="password" class="userPasswordChangeField userConfirmPasswordField" name="userConfirmPasswordField" value="" placeholder="Confirm New Password" />
                                </div>
                                <div class="row">
                                    <button type="button" class="profilePasswordEditBtn"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;CHANGE PASSWORD</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="flights">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-24 center-align-text">
                                    <label for="flightsTableSearch" class="flightsTableSearch">
                                        Search: <span class="glyphicon glyphicon-question-sign searchHelp"></span>
                                        <input type="text" id="flightsTableSearch" />
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-24">
                                    <table class="table table-hover" id="flightsTable">
                                        <thead>
                                            <th>Booking Id</th>
                                            <th>Source</th>
                                            <th>Destination</th>
                                            <th>Airline</th>
                                            <th>Departure</th>
                                            <th>Arrival</th>
                                            <th>Date Of journey</th>
                                            <th>Price</th>
                                            <th>Ticket Status</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="buses">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-24 center-align-text">
                                    <label for="busesTableSearch" class="busesTableSearch">
                                        Search: <span class="glyphicon glyphicon-question-sign searchHelp"></span>
                                        <input type="text" id="busesTableSearch" />
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-24">
                                    <table class="table table-hover" id="busesTable">
                                        <thead>
                                            <th>Booking Id</th>
                                            <th>Travels</th>
                                            <th>Issued On</th>
                                            <th>Journey On</th>
                                            <th>Seats</th>
                                            <th>Price</th>
                                            <th>Ticket Status</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="cabs">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-24 center-align-text">
                                    <label for="cabsTableSearch" class="cabsTableSearch">
                                        Search: <span class="glyphicon glyphicon-question-sign searchHelp"></span>
                                        <input type="text" id="cabsTableSearch" />
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-24">
                                    <table class="table table-hover" id="cabsTable">
                                        <thead>
                                            <th>Booking Id</th>
                                            <th>Car Type</th>
                                            <th>Issued On</th>
                                            <th>Journey On</th>
                                            <th>Pickup address</th>
                                            <th>Drop address</th>
                                            <th></th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="hotels">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-24 center-align-text">
                                    <label for="hotelsTableSearch" class="hotelsTableSearch">
                                        Search: <span class="glyphicon glyphicon-question-sign searchHelp"></span>
                                        <input type="text" id="hotelsTableSearch" />
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-24">
                                    <table class="table table-hover" id="hotelsTable">
                                        <thead>
                                            <th>Booking Id</th>
                                            <th>Hotel Name</th>
                                            <th>Booking Reference</th>
                                            <th>Confirmation</th>
                                            <th>Check In</th>
                                            <th>destination</th>
                                            <th>No. of Rooms</th>
                                            <th>Status</th>
                                            <th></th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="activities">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-24 center-align-text">
                                    <label for="activitiesTableSearch" class="activitiesTableSearch">
                                        Search: <span class="glyphicon glyphicon-question-sign searchHelp"></span>
                                        <input type="text" id="activitiesTableSearch" />
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-24">
                                    <table class="table table-hover" id="activitiesTable">
                                        <thead>
                                            <th>Booking Id</th>
                                            <th>Amount</th>
                                            <th>Time</th>
                                            <th>Date</th>
                                            <th>Count</th>
                                            <th>Status</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

//global variables
var currentDate = new Date();

    // var tab = "<?php //echo $tab_name; ?>";
    // $('.nav-tabs a[href="#' + tab + '"]').tab('show');
    
    $(".userTitleField option").each(function(){
        if($(this).val() === "<?php echo $user_details[0]->user_title; ?>"){
            $(".userTitleField").val("<?php echo $user_details[0]->user_title; ?>");
            return false;
        }
    });
    
    $( "#datepicker").datepicker({
        changeYear: true,
        changeMonth: true,
        maxDate: 0
    });
    $( "#datepicker").datepicker("setDate", "<?php echo $date; ?>");
    
    $(".profileEditBtn").click(function(){
        $(".profileEditErrorMsg").html("");
        $(".userProfileField").prop("disabled",false);
        $(".profileEditBtn").hide();
        $(".profileSaveBtn").show();
        $(".profileEditCancelBtn").show();
    });
    
    $(".profileEditCancelBtn").click(function(){
        $(".profileEditErrorMsg").html("");
        $(".profileSaveBtn").hide();
        $(".profileEditCancelBtn").hide();
        $(".userProfileField").prop("disabled",true);
        $(".profileEditBtn").show();
    });
    
    $(".profileSaveBtn").click(function(e){
        e.preventDefault();
        if($(".userFirstNameField").val()==="" || $(".userLastNameField").val()==="" || $(".userMobileField").val()===""){
            $(".profileEditErrorMsg").html("Please do not leave the Name or Mobile Number fields empty.");
        }else {
            $(".userProfileForm").submit();
        }
    });
    
    $(".profilePasswordEditBtn").click(function(e){
        e.preventDefault();
        if($(".userOldPasswordField").val()==="" || $(".userNewPasswordField").val()==="" || $(".userConfirmPasswordField").val()===""){
            $(".passwordEditErrorMsg").html("Please do not leave any of the fields empty.");
        }else if($(".userNewPasswordField").val() !== $(".userConfirmPasswordField").val()){
            $(".passwordEditErrorMsg").html("The new passwords do not match.");
        }else{
            $(".userPasswordChangeForm").submit();
        }
    });


// activity table initialisation
    var oTableActivities = $('#activitiesTable').DataTable({
        "bDeferRender": false,
        // // to hide columns in datatables
        // "columnDefs": [
        // {
        //     "targets": [ 4, 6 ],
        //     "visible": false
        // }],
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('id', 'row-'+iDataIndex);
        },
        "bDestroy": true,
        "aaSorting": [[0, 'asc']],
        "bPaginate": false,
        "bInfo": false,
        "bFilter": true,
        "bScrollCollapse": true,
        "fnInitComplete": function() {
            this.fnAdjustColumnSizing(true);
        }
    });
// activity table initialisation end
// populating activity table
    var activity_click = 0;
    
    $('#activitiesTab').on('shown.bs.tab', function(e) {
        $('#activitiesTable_filter').hide();
        ++activity_click;
        var userId = "<?php echo $userId;?>";
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('user/get_activity_bookings'); ?>",
            data: {user_id: userId}
        })
        .done(function( data ) {
            var retData = $.parseJSON(data);
            if(activity_click == 1){
               $.each(retData, function(i, val){
                    oTableActivities.row.add([
                        val.fb_bookingId,
                        val.activity_booking_amount,
                        val.booking_creation_date_time,
                        val.activity_booking_date,
                        "Adults- "+val.adult_count+" Children- "+val.child_count,
                        "<div class='btn-group user_activity'><span id='book-"+i+"'>"+val.booking_status+"</span></div>"
                            
                    ]).draw();
                    if(val.booking_status == "Active")
                        $('#book-'+i).append("<button id='"+val.booking_id+"' data-actid = '"+val.activity_id+"' type='button' class='btn btn-default'>Cancel</button>");
                });
            }else{
                return false;
            }

          $(".user_activity").on("click", "button" , function(e){
                        e.preventDefault();
                        var some_val = this.id;
                        var actId = $(this).data(actid);
                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url('user/update_status');?>",
                            data: {booking_id: some_val, activity_id: actId}
                        })
                        .done( function( retVal ){
                            
                            var retVal = $.parseJSON( retVal );
                            if(retVal.act_stat == 0)
                            {
                                alert('Sorry you can cancel ticket only before 11 hours of booking date');
                                return false;
                            }   
                            else 
                            {
                                location.reload(true);
                            }
                        });
                        
                    }); 
            
        });
// populating activity table end
});

// flights table initialisation
    var oTableFlights = $('#flightsTable').DataTable({
        "bDeferRender": false,
        // // to hide columns in datatables
        // "columnDefs": [
        // {
        //     "targets": [ 4, 6 ],
        //     "visible": false
        // }],
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('id', 'row-'+iDataIndex);
        },
        "bDestroy": true,
        "aaSorting": [[0, 'asc']],
        "bPaginate": false,
        "bInfo": false,
        "bFilter": true,
        "bScrollCollapse": true,
        "fnInitComplete": function() {
            this.fnAdjustColumnSizing(true);
        }
    });
// flights table initialisation end
// populating flights table
    var flight_click = 0;
    var retData = [];
    
    $('#flightsTab').on('shown.bs.tab', function(e) {
        $('#flightsTable_filter').hide();
        ++flight_click;
        var userId = "<?php echo $userId;?>";
        if(flight_click == 1){
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('user/get_ticket_bookings'); ?>",
                data: {user_id: userId}
            })
            .done(function( data ) {
                retData = $.parseJSON(data);
                $.each(retData, function(i, val){
                    var dateOfJourney = new Date(val.date);
                    if( dateOfJourney < currentDate ){
                        var butCancelBtn = "closed";
                    }else{
                        if(val.status == "Successful" || val.status == "Fare is not availabl"){
                            val.status = "Cancel";
                        }
                        butCancelBtn = "<button id='btn-"+i+"' class='cancel_ticket btn btn-change'>"+val.status+"</button>";
                        if( val.status !== 'Cancel' ){
                            butCancelBtn = "<button id='btn-"+i+"' class='cancel_ticket btn btn-change' disabled='disabled'>"+val.status+"</button>";
                        }
                    }    
                    oTableFlights.row.add([
                        val.booking_id,
                        val.airline_name,
                        val.source,
                        val.destination,
                        val.arrival_time,
                        val.departure_time,
                        dateOfJourney.getFullYear() + '-' + (dateOfJourney.getMonth() + 1) + '-' + dateOfJourney.getDate(),
                        val.total_fare,
                        butCancelBtn,
                        "Adults- "+val.num_of_adults+" Children- "+val.num_of_children
                    ]).draw();
                });
            });
        }else{
            return false;
        }
    });

    $('.searchHelp').on('mouseenter', function(){
        $(this).tooltip({
            placement: 'bottom',
            trigger: 'hover',
            title: "Search through your booking history with the Booking ID, Travel Date or any other matching keyword"
        });
        $(this).tooltip('show');
    });
// populating flights table end
// ticket cancellation for flights
        $('tbody').on('click', '.cancel_ticket', function(e){
                if($(this).html() == "Cancel")
                {
                    if( confirm('Are you sure you want to cancel this ticket?') ){
                        $(this).html('loading...');
                        var clicked_btn = $(this).attr('id');
                        console.log(clicked_btn);
                        var btn_id_arr = clicked_btn.split('-');
                        var btn_id = btn_id_arr[1];
                        $.ajax({
                            type:"POST",
                            url: "<?php echo site_url('api/flights/cancel_request'); ?>",
                            data: {ticket_id: retData[btn_id].booking_id}
                        })
                        .done(function( retVal ){
                            var retChk = $.parseJSON(retVal);
                            if( retChk === "Successfull" ){
                                alert('Your ticket has been cancelled successfully.')
                                $('#'+clicked_btn).removeClass('cancel_ticket');
                                $('#'+clicked_btn).attr('disabled', 'disabled');
                                $('#'+clicked_btn).html('Cancelled');
                            }else{
                                alert('Ticket is already cancelled.')
                                $('#'+clicked_btn).removeClass('cancel_ticket');
                                $('#'+clicked_btn).attr('disabled', 'disabled');
                                $('#'+clicked_btn).html('Cancelled');
                            }
                        });
                    }else{
                        return false;
                    }
                }
                else
                {
                    alert('You cannot cancel a '+$(this).html()+' ticket.');
                }
        });
// ticket cancellation for flights end

// buses table initialisation
    var oTableBuses = $('#busesTable').DataTable({
        "bDeferRender": false,
        // // to hide columns in datatables
        // "columnDefs": [
        // {
        //     "targets": [ 4, 6 ],
        //     "visible": false
        // }],
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('id', 'row-'+iDataIndex);
        },
        "bDestroy": true,
        "aaSorting": [[0, 'asc']],
        "bPaginate": false,
        "bInfo": false,
        "bFilter": true,
        "bScrollCollapse": true,
        "fnInitComplete": function() {
            this.fnAdjustColumnSizing(true);
        }
    });
// buses table initialisation end

// populating buses table
    var bus_click = 0;
    var retBusData = [];
    
    $('#busesTab').on('shown.bs.tab', function(e) {
        $('#busesTable_filter').hide();
        ++bus_click;
        var userId = "<?php echo $userId;?>";
        if(bus_click == 1){
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('user/get_bus_bookings'); ?>",
                data: {user_id: userId}
            })
            .done(function( data ) {
                retBusData = $.parseJSON(data);
                // console.log(retData);return false;
                $.each(retBusData, function(i, val){
                    var dateOfJourney = new Date(val.doj);
                    if( dateOfJourney < currentDate ){
                        var butCancelBtn = "closed";
                    }else{
                        butCancelBtn = "<button data-busbookingnum='"+i+"' class='cancel_bus_ticket btn btn-change' data-cancellationpolicy='"+val.cancellationPolicy+"'>Cancel</button>";
                        if( val.db_status === 'cancelled' ){
                            butCancelBtn = "<button data-busbookingnum='"+i+"' class='btn btn-change' disabled='disabled'>Cancelled</button>";
                        }
                    }
                    oTableBuses.row.add([
                        val.fb_bookingId,
                        val.travels,
                        val.dateOfIssue,
                        dateOfJourney.getFullYear() + '-' + (dateOfJourney.getMonth() + 1) + '-' + dateOfJourney.getDate(),
                        val.seatCSV,
                        val.totalFare,
                        butCancelBtn,
                    ]).draw(); 
                });
            });
        }else{
            return false;
        }
    });
// populating buses table end
// ticket cancellation for buses
    $('tbody').on('click', '.cancel_bus_ticket', function(e){
        var btn_id = $(this).data('busbookingnum');
        var originalBtn = $(this);
        var cancellationPolicy = $(this).data('cancellationpolicy');
        var cancellation_charge = busCancellation(btn_id);
        var refundAmt = parseInt(retBusData[btn_id].totalFare) - cancellation_charge;

        if( confirm('Are you sure you want to cancel this ticket? An amount of Rs.'+cancellation_charge+' will be deducted at cancellation charge.') ){
            cancellation_charge = busCancellation(btn_id);
            refundAmt = parseInt(retBusData[btn_id].totalFare) - cancellation_charge;
            $.ajax({
                type:"POST",
                url: "<?php echo site_url('bus_api/buses/cancelTicket'); ?>",
                data: {tin: retBusData[btn_id].tin, seat_name_csv: retBusData[btn_id].seatCSV, title: retBusData[btn_id].userDetails.user_title, first_name: retBusData[btn_id].userDetails.user_first_name, last_name: retBusData[btn_id].userDetails.user_last_name, email_id: retBusData[btn_id].userDetails.user_email, fb_bookingId: retBusData[btn_id].fb_bookingId, canlAmt: cancellation_charge, refundAmt: refundAmt}
            })
            .done(function( retVal ){
                var retChk = $.parseJSON(retVal);
                if( retChk === "Ticket has been cancelled successfully." ){
                    alert('Your booking has been cancelled successfully');
                    originalBtn.removeClass('cancel_ticket');
                    originalBtn.attr('disabled', 'disabled');
                    originalBtn.html('Cancelled');
                }else{
                    if( retChk.trim() === 'Ticket is already cancelled.' ){
                        alert(retChk);
                        originalBtn.attr('disabled', 'disabled');
                        originalBtn.html('Cancelled');
                    }
                    return false;
                }
            });
        }else{
            return false;
        }
    });
// ticket cancellation for buses end

// cabs table initialisation
    var oTableCabs = $('#cabsTable').DataTable({
        "bDeferRender": false,
        // // to hide columns in datatables
        // "columnDefs": [
        // {
        //     "targets": [ 4, 6 ],
        //     "visible": false
        // }],
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('id', 'row-'+iDataIndex);
        },
        "bDestroy": true,
        "aaSorting": [[0, 'asc']],
        "bPaginate": false,
        "bInfo": false,
        "bFilter": true,
        "bScrollCollapse": true,
        "fnInitComplete": function() {
            this.fnAdjustColumnSizing(true);
        }
    });
// cabs table initialisation end

// populating cabs table
    var cab_click = 0;
    var retCabData = [];
    
    $('#cabsTab').on('shown.bs.tab', function(e) {
        $('#cabsTable_filter').hide();
        ++cab_click;
        var userId = "<?php echo $userId;?>";
        if(cab_click == 1){
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('user/get_cab_bookings'); ?>",
                data: {user_id: userId}
            })
            .done(function( data ) {
                retCabData = $.parseJSON(data);
                $.each(retCabData.cabBookingDetails, function(i, val){
                    // 1.0365 is (3.65(service charge) / 100) + 1 to get back the original fare.
                    val.total_fare = (val.total_fare / 1.0365);
                    var dateOfJourney = new Date(val.journey_date);
                    var dateOfBooking = new Date(val.booking_date);
                    if( dateOfJourney < currentDate ){
                        butCancelBtn = "closed"
                    }else{
                        var butCancelBtn = "<button data-cabbookingnum='"+i+"' class='cancel_cab_ticket btn btn-change'>Cancel</button>";
                        if( val.booking_status === 'Cancelled' ){
                            butCancelBtn = "<button data-cabbookingnum='"+i+"' class='btn btn-change' disabled='disabled'>Cancelled</button>";
                        }
                    }
                    oTableCabs.row.add([
                        val.fb_bookingId,
                        val.car_type,
                        dateOfBooking.getFullYear() + '-' + (dateOfBooking.getMonth() + 1) + '-' + dateOfBooking.getDate(),
                        dateOfJourney.getFullYear() + '-' + (dateOfJourney.getMonth() + 1) + '-' + dateOfJourney.getDate(),
                        val.pickup_addr,
                        val.drop_addr,
                        butCancelBtn
                    ]).draw();    
                });
            });
        }else{
            return false;
        }
    });
// populating cabs table end
// ticket cancellation for cabs
    $('tbody').on('click', '.cancel_cab_ticket', function(e){
        var btn_id = $(this).data('cabbookingnum');
        var originalBtn = $(this);
        var cancellationCharge = cabCancellation(btn_id);
        var refundAmt = retCabData.cabBookingDetails[btn_id].total_fare - cancellationCharge;
        console.log(retCabData.cabBookingDetails[btn_id]);

        if( confirm('Are you sure you want to cancel this ticket? An amount of Rs.'+cancellationCharge+' will be deducted at cancellation charge.') ){
            cancellationCharge = cabCancellation(btn_id);
            refundAmt = retCabData.cabBookingDetails[btn_id].total_fare - cancellationCharge;
            $.ajax({
                type:"POST",
                url: "<?php echo site_url('cab_api/cabs/cancel_booking'); ?>",
                data: {confirm_ref_id: retCabData.cabBookingDetails[btn_id].confirm_ref_id, booking_id: retCabData.cabBookingDetails[btn_id].id, email_id: retCabData.cabBookingDetails[btn_id].Email, canlAmt: cancellationCharge, refundAmt: refundAmt}
            })
            .done(function( retVal ){
                var retChk = $.parseJSON(retVal);
                if( retChk === "successful" ){
                    alert('Your booking has been cancelled successfully.');
                    originalBtn.removeClass('cancel_cab_ticket');
                    originalBtn.attr('disabled', 'disabled');
                    originalBtn.html('Cancelled');
                    return true;
                }else if( retChk === "failed" ){
                    alert('Error, please try later.');
                    return false;
                }else{
                    alert(retChk);
                    originalBtn.removeClass('cancel_cab_ticket');
                    originalBtn.attr('disabled', 'disabled');
                    originalBtn.html('Cancelled');
                    return false;
                }
            });
        }else{
            return false;
        }
    });
// ticket cancellation for cabs end



// hotels table initialisation
    var oTableHotels = $('#hotelsTable').DataTable({
        "bDeferRender": false,
        // // to hide columns in datatables
        // "columnDefs": [
        // {
        //     "targets": [ 4, 6 ],
        //     "visible": false
        // }],
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('id', 'row-'+iDataIndex);
        },
        "bDestroy": true,
        "aaSorting": [[0, 'asc']],
        "bPaginate": false,
        "bInfo": false,
        "bFilter": true,
        "bScrollCollapse": true,
        "fnInitComplete": function() {
            this.fnAdjustColumnSizing(true);
        }
    });
// hotels table initialisation end

// populating hotel table
    var hotel_click = 0;
    var retHotelData = [];
    
    $('#hotelsTab').on('shown.bs.tab', function(e) {
        $('#hotelsTable_filter').hide();
        ++hotel_click;
        var userId = "<?php echo $userId;?>";
        if(hotel_click == 1){
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('user/get_hotel_bookings'); ?>",
                data: {user_id: userId}
            })
            .done(function( data ) {
                retHotelData = $.parseJSON(data);
                $.each(retHotelData.hotelBookingDetails, function(i, val){
                    var dateOfCheckIn = new Date(val.check_in);
                    if( dateOfCheckIn < currentDate ){
                        butCancelBtn = "closed"
                    }else{
                        var butCancelBtn = "<button data-hotelbookingnum='"+i+"' class='cancel_hotel_ticket btn btn-change'>Cancel</button>";
                        if( val.booking_status === 'Cancelled' ){
                            butCancelBtn = "<button data-hotelbookingnum='"+i+"' class='btn btn-change' disabled='disabled'>Cancelled</button>";
                        }
                    }
                    oTableHotels.row.add([
                        val.fb_bookingId,
                        val.hotel_name,
                        val.BookingRefNo,
                        val.ConfirmationNo,
                        dateOfCheckIn.getFullYear() + '-' + (dateOfCheckIn.getMonth() + 1) + '-' + dateOfCheckIn.getDate(),
                        val.destination,
                        val.room_count,
                        val.status,
                        butCancelBtn
                    ]).draw();     
                });  
            });
        }else{
            return false;
        }
    });
// populating hotel table end
// ticket cancellation for hotel
    $('tbody').on('click', '.cancel_hotel_ticket', function(e){
        var btn_id = $(this).data('hotelbookingnum');
        var originalBtn = $(this);
        if( confirm('Are you sure you want to cancel this ticket?') ){
            $.ajax({
                type:"POST",
                url: "<?php echo site_url('new_request/hotel_cancel'); ?>",
                data: {data : 1, retHotelData : retHotelData.hotelBookingDetails[btn_id]}
            })
            .done(function( retVal ){
                alert();
            });
        }else{
            return false;
        }
    });
// ticket cancellation for hotel end



//search functionality

    //activities
    $('#activitiesTableSearch').on('keyup', function(){
        oTableActivities.search($(this).val()).draw();
    });
    
    //flights
    $('#flightsTableSearch').on('keyup', function(){
        oTableFlights.search($(this).val()).draw();
    });

    //buses
    $('#busesTableSearch').on('keyup', function(){
        oTableBuses.search($(this).val()).draw();
    });

    //cabs
    $('#cabsTableSearch').on('keyup', function(){
        oTableCabs.search($(this).val()).draw();
    });

//search functionality end

    function busCancellation(index){
        var can_str = retBusData[index].cancellationPolicy.split(';');
        var can_len = can_str.length;
        var pickupTime = retBusData[index].pickupTime;
        var d = new Date(retBusData[index].doj);
        var today = new Date();
        var hours = Math.floor( pickupTime / 60 );
        var minutes = pickupTime - (hours * 60);
        if(hours < 10) hours = '0' + hours;
        if(minutes < 10) minutes = '0' + minutes;
        if(minutes == 0) minutes = '00';
        d.setHours(hours);
        d.setMinutes(minutes);
        var date1 = today;
        var date2 = d;
        var timeDiff = date2.getTime() - date1.getTime();
        var diffHours = Math.ceil(timeDiff / (1000 * 3600)); 
        var can_fare = parseInt(retBusData[index].totalFare);
        var cancellation_charge = 0;
        var canlAmountRange = [];

        if( diffHours < 0 ){
            return 0;
        }else{
            for( var i = 0 ; i < can_len ; i++ ){
                if( i === 0 && can_str[i] === "0:-1:100:0" ){
                    cancellation_charge = 0;
                }else if( can_str[i] !== "" ){
                    var info_arr = can_str[i].split(':');
                    if( diffHours >= info_arr[0] && diffHours <= info_arr[1] ){
                        canlAmountRange = info_arr;
                        break;
                    }
                }else{
                    //this is done to calculate amount. the Date of journey doesnt fall under any cancellation policy
                    //no money will be cancelled.
                    canlAmountRange = [0, 0, 0, 1];
                }
            }
        }

        if( !parseInt(canlAmountRange[3]) ){
            //percentage
            var percentage = parseInt(canlAmountRange[2])/100;
            var total_fare =  parseInt(retBusData[index].totalFare)
            var per_total = percentage*total_fare;
            rem_total = total_fare-per_total;
            cancellation_charge = total_fare - rem_total;
        }else{
            //amount
            var a1 = parseInt(retBusData[index].totalFare);
            var a2 = parseInt(canlAmountRange[2]);
            rem_total = a1-a2;
            cancellation_charge = a1 - rem_total;
        }

        return cancellation_charge;
    }

    function cabCancellation(index){
        var cabData = retCabData.cabBookingDetails[index];
        var d = new Date(cabData.journey_date);
        var today = new Date();
        var date1 = today;
        var date2 = d;
        var timeDiff = date2.getTime() - date1.getTime();
        var diffHours = Math.ceil(timeDiff / (1000 * 3600)); 
        var cabMinSlab = $.parseJSON(cabData.cab_min_slab);

        if( diffHours >= 4 ){
            cancellation_charge = 0;
        }else if( diffHours >= 1 && diffHours < 4 ){
            cancellation_charge = 0;
            if( cabData.car_type == "compact" ){
                cancellation_charge += cabMinSlab.compactMinSlab;
            }
            if( cabData.car_type == "sedan" ){
                cancellation_charge += cabMinSlab.sedanMinSlab;
            }
            if( cabData.car_type == "suv" ){
                cancellation_charge += cabMinSlab.suvMinSlab;
            }
        }else{
            cancellation_charge = cabData.total_fare;
        }
        return cancellation_charge;
    }   

</script>