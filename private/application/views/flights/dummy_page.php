<!DOCTYPE html>
<html>
	<body>
		<div class="wrap">
			<div class="container-fluid clear-top">
				<form action="payment_status?ticket_id=<?php echo $data['ticket_id'];?>" method="post">
					<div class="row">
						<div class="col-lg-8">
							<select class="form-control form-padding" name="stat" id="ticket_status">
								<option value="Success">Success</option>
								<option value="Failure">Failure</option>
							</select>
						</div>
					</div>
					<button class="btn btn-change form-padding" type="submit">Submit</button>
				</form>
			</div>
		</div>
	</body>
</html>