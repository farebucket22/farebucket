<!DOCTYPE html>
<html>
	<body>
		<div class="wrap">
			<div class="container-fluid clear-top">
				<table class="table table-default" id="tickets_display">
					<thead>
						<tr>
							<th>Airline</th>
							<th>Departure</th>
							<th>Arrival</th>
							<th>Duration</th>
							<th>Price</th>
							<th>Ticket Status</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $dat) {
							echo "<tr>";
								echo "<td>".$dat->airline_name."</td>";
								echo "<td>".$dat->departure_time."</td>";
								echo "<td>".$dat->arrival_time."</td>";
								echo "<td>".$dat->flight_duration."</td>";
								echo "<td>".$dat->total_fare."</td>";
								echo "<td><span class='status".$dat->status."'>".$dat->status."</span></td>";
								echo "<td class='center-align-text'><button class='btn btn-change' id='Cancel-".$dat->ticket_id."'>Cancel</button></td>";
							echo "</tr>";
						}?>
					</tbody>
				</table>
				<form action="ticket_confirmation/update_flight_status" method="post" id="update_stat" style="display:none;">
					<input type="text" name="ticket_id" />
					<input type="text" name="status" />
				</form>
			</div>
		</div>
	</body>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/vendor/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script src="http://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url('js/vendor/jquery-ui.min.js'); ?>"></script>

<script type="text/javascript">
	(function(){
		$('#tickets_display').DataTable({
			"bDeferRender": false,
			"fnCreatedRow": function( nRow, aData, iDataIndex ) {
				$(nRow).attr('id', 'row-'+iDataIndex);
			},
			"bDestroy": true,
			"bPaginate": false,
			"bInfo": false,
			"bFilter": true,
			"bScrollCollapse": true,
			"fnInitComplete": function() {
				this.fnAdjustColumnSizing(true);
			}
		});

		$('button').on('click', function(e){
			e.preventDefault();
			var status = $(this).attr('id');
			var status_arr = status.split('-');
			var statusVal = status_arr[0];
			var statusID = status_arr[1];

			$('#update_stat input[name=status]').val(statusVal);
			$('#update_stat input[name=ticket_id]').val(statusID);
			$('#update_stat').submit();

			// var rowID = $(this).closest("tr").attr('id');
			// var row_arr = rowID.split('-');
			// var rowNum = row_arr[1];
		});
	})();
</script>
</html>