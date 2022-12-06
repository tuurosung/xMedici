<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
$p = new Patient();
$ward = new Ward();
$admission = new Admission();
?>

<div class="row">
	<div class="col-md-6 ">
		<h4 class="titles montserrat mb-5">Admission Requests</h4>
	</div>
	<div class="col-md-6 text-right mb-5">


	</div>
</div>


<div class="card">
	<div class="card-body">
		<h5 class="card-title">Title</h5>


		<table class="table table-condensed	 datatables">
			<thead>
				<tr>
					<th>#</th>
					<th>Full Name</th>
					<th>Age</th>
					<th>Sex</th>
					<th>Ward</th>
					<th>Ref. Dr.</th>
					<th class="text-right">Option</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 1;
				$list = $admission->PendingAdmissionRequests();
				foreach ($list as $rows) {
					$p->patient_id = $rows['patient_id'];
					$p->PatientInfo();
					$othernames = $p->othernames;

					$ward->ward_id = $rows['ward_id'];
					$ward->WardInfo();

					// $doctor->doctor_id = $rows['admission_requested_by'];
					// $doctor->DoctorInfo();

				?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td>
							<p class="montserrat p-0" style="font-weight:500; font-size:11px"><?php echo $p->patient_fullname; ?> </p>
						</td>
						<td>
							<p> <span class="mr-2"><?php echo $p->age; ?> </span></p>
						</td>
						<td>
							<p> <?php echo $p->sex; ?> </p>
						</td>
						<td><?php echo $ward->description; ?></td>
						<td><?php echo $staff->full_name; ?></td>
						<td class="text-right">
							<div class="dropdown open">
								<a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Options
								</a>
								<div class="dropdown-menu minioptions b-0 p-0" aria-labelledby="dropdownMenu1">
									<ul class="list-group">
										<li class="list-group-item accept_admission" data-admission_id="<?php echo $rows['admission_id']; ?>"><i class="fas fa-check-circle text-success mr-2" aria-hidden></i> Accept</li>
										<li class="list-group-item reject_admission" data-admission_id="<?php echo $rows['admission_id']; ?>"><i class="fas fa-times-circle text-danger mr-2" aria-hidden></i> Reject</li>
									</ul>
								</div>
							</div>
						</td>
					</tr>
				<?php
				}
				?>

			</tbody>
		</table>

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

	$('.accept_admission').on('click', function(event) {
		event.preventDefault();
		var admission_id = $(this).data('admission_id');
		$.get('../serverscripts/admin/Wards/accept_admission_modal.php?admission_id=' + admission_id, function(msg) {
			$('#modal_holder').html(msg)
			$('#accept_admission_modal').modal('show')

			$('#accept_admission_frm').on('submit', function(event) {
				event.preventDefault();
				bootbox.confirm("Confirm Patient Admission?", function(r) {
					if (r === true) {
						$.ajax({
							url: '../serverscripts/admin/Wards/accept_admission_frm.php',
							type: 'GET',
							data: $('#accept_admission_frm').serialize(),
							success: function(msg) {
								if (msg === 'save_successful') {
									bootbox.alert('Patient admission successful', function() {
										window.location.reload();
									})
								} else {
									bootbox.alert(msg)
								}
							}
						}) //end ajax
					} //end if
				})
			});
		})
	});
</script>

</html>