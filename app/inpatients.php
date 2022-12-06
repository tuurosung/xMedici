<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
require_once '../serverscripts/Classes/Admissions.php';
?>
<?php
$p = new Patient();
$ward = new Ward();
$admission = new Admission();
?>


<div class="row">
	<div class="col-md-6 ">
		<h4 class="titles montserrat mb-5">In Patients</h4>
	</div>
	<div class="col-md-6 text-right mb-5">
		<button type="button" class="btn btn-primary btn-rounded">
			<i class="fas fa-print mr-2" aria-hidden></i>
			Print
		</button>
	</div>
</div>

<?php
if ($admission->pending_discharge > 0) {
?>
	<div class="card primary-color white-text">
		<div class="card-body">
			<p><i class="far fa-bell mr-3" aria-hidden></i> <?php echo $admission->pending_discharge; ?> patients are pending discharged. Notify Nurses to accept discharge request.</p>
		</div>
	</div>
<?php
}
?>

<div class="row my-5">
	<div class="col-3">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-4">
						<i class="fas fa-bed" aria-hidden style="font-size:30px"></i>
					</div>
					<div class="col-8">
						<p class="big-text"><?php echo $admission->active_admissions; ?></p>
						On-Admission
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-3">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-4">
						<i class="fas fa-sign-out-alt" aria-hidden style="font-size:30px"></i>
					</div>
					<div class="col-8">
						<p class="big-text"><?php echo $admission->discharged_admissions; ?></p>
						Discharged
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-3">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-4">
						<i class="fas fa-copy" aria-hidden style="font-size:30px"></i>
					</div>
					<div class="col-8">
						<p class="big-text"><?php echo $admission->total_admissions; ?></p>
						Total
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="card">
	<div class="card-body">
		<h5 class="card-title">Patients Admission</h5>

		<table class="table datatables">
			<thead>
				<tr>
					<th>#</th>
					<th>Date</th>
					<th>Full Name</th>
					<th>Ward</th>
					<th>Bed</th>
					<th>Days</th>
					<th class="text-right">Options</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$list = $admission->All();
				$i = 1;
				foreach ($list as $rows) {


					$p->patient_id = $rows['patient_id'];


					$p->PatientInfo();
					$othernames = $p->othernames;

					$ward->ward_id = $rows['ward_id'];
					$ward->WardInfo();



					$bed_id = $rows['bed_id'];
					$ward->BedInfo($bed_id);

					$admission->admission_id = $rows['admission_id'];
					$admission->AdmissionInfo();
				?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?php echo $rows['admission_date']; ?></td>
						<td>
							<p class="montserrat p-0" style="font-weight:600; font-size:12px"><?php echo $p->patient_fullname; ?> </p>
							<p class="d-none" style="font-size:11px"> <span class="mr-2"><?php echo $p->age; ?> </span> | <span class="poppins ml-2" style="text-decoration:italic"><?php echo $p->sex; ?></span></p>
						</td>
						<td><?php echo $ward->description; ?></td>
						<td><?php echo $ward->bed_description; ?></td>
						<td><?php echo $admission->days_on_admission; ?> Days</td>
						<td class="text-right"><a href="inpatientmanagement.php?admission_id=<?php echo $rows['admission_id']; ?>" class="btn btn-primary btn-sm">Ward Mgt.</a></td>
					</tr>

				<?php
				}
				?>
				<tr>
					<td scope="row"></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>







<div id="modal_holder">

</div>

</body>

<!--   Core JS Files   -->
<?php require_once '../navigation/footer.php'; ?>

<script type="text/javascript">
	$('.sidebar-fixed .list-group-item').removeClass('active')
	$('#inpatients_nav').addClass('active')
	$('#inpatients_submenu').addClass('show')
	$('#onadmission_li').addClass('font-weight-bold')
</script>

</html>