<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
$p = new Patient();
$ward = new Ward();
$visit = new Visit();
// $s=new Service();
$admission=new Admission();
?>


<div class="row">
	<div class="col-md-6 ">
		<h4 class="titles montserrat mb-5">Discharge Requests</h4>
	</div>
	<div class="col-md-6 text-right mb-5">
		<div class="btn-group">



		</div>


	</div>
</div>


<div class="card">
	<div class="card-body">
		<h5 class="card-title mb-5">Pending Discharge Requests</h5>


		<table class="table table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>Patient Name</th>
					<th>Ward</th>
					<th>Bed</th>
					<th>Discharge Notes</th>
					<th>Bill</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$get_patients = mysqli_query($db, "SELECT * FROM admission_discharges WHERE discharge_status='PENDING' AND  status='active' && subscriber_id='" . $active_subscriber . "'")  or die(mysqli_error($db));
				$list = $admission->PendingDischargeRequests();
				$i = 1;
				foreach ($list as $rows) {
					$p->patient_id = $rows['patient_id'];
					$p->PatientInfo();
					$othernames = $p->othernames;

					$ward->ward_id = $rows['ward_id'];
					$ward->WardInfo();

					$staff->staff_id = $rows['admission_requested_by'];
					$staff->StaffInfo();

					$patient_id = $rows['patient_id'];
					$visit_id = $rows['visit_id'];

					$bill_to_pay = $visit->VisitBalance($patient_id, $visit_id);
				?>

					<tr>
						<td>
							<p class="montserrat p-0" style="font-weight:500; font-size:11px"><?php echo $p->patient_fullname; ?> </p>
							<p> <span class="mr-2"><?php echo $p->age; ?> </span> | <span class="poppins ml-2" style="text-decoration:italic"><?php echo $p->sex; ?></span></p>
						</td>
						<td>
							<?php echo $rows['discharge_notes']; ?>
						</td>
						<td>
							<?php echo number_format($bill_to_pay, 2); ?>
						</td>
						<td>
							<div class="dropdown open <?php if ($bill_to_pay > 0) {
															echo 'd-none';
														} ?>">
								<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Options
								</button>
								<div class="dropdown-menu minioptions b-0 p-0" aria-labelledby="dropdownMenu1">
									<ul class="list-group">
										<li class="list-group-item accept_discharge" data-admission_id="<?php echo $rows['admission_id']; ?>"><i class="fas fa-check-circle text-success mr-2" aria-hidden></i> Accept</li>
										<li class="list-group-item reject_admission" data-admission_id="<?php echo $rows['admission_id']; ?>"><i class="fas fa-times-circle text-danger mr-2" aria-hidden></i> Reject</li>
									</ul>
								</div>
							</div>
						</td>
						<td></td>
					</tr>
					
				<?php
				}
				?>

			</tbody>
		</table>

	</div>
</div>




<div class="card mb-3">
	<div class="card-body p-0" style="min-height:500px">


	</div>
</div>


</div>
</main>






<div id="modal_holder">

</div>

</body>

<!--   Core JS Files   -->
<?php require_once '../navigation/footer.php'; ?>

<script type="text/javascript">
	$('.sidebar-fixed .list-group-item').removeClass('active')
	$('#patients_nav').addClass('active')
	$('#patients_submenu').addClass('show')
	$('#patients_li').addClass('font-weight-bold')

	$('.accept_discharge').on('click', function(event) {
		event.preventDefault();
		var admission_id = $(this).data('admission_id');
		$.get('../serverscripts/admin/Wards/accept_discharge.php?admission_id=' + admission_id, function(msg) {
			if (msg === 'discharge_successful') {
				bootbox.alert("Discharge Successful", function() {
					window.location.reload();
				})
			} else {
				bootbox.alert(msg)
			}

		})
	});
</script>

</html>