<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
$p = new Patient();
?>



<div class="row mb-5">
	<div class="col-6">
		<h4 class="montserrat titles">Patient Folders</h4>
	</div>
	<div class="col-3 d-flex align-items-center">
		<!-- <input type="text" name="search_term" id="findpatientsearch" class="form-control" value="" placeholder="search here" style="background-color:#fff !important; border-radius:5px !important"> -->
	</div>
	<div class="col-3 text-right d-flex flex-row-reverse">

		<div class="dropdown open">
			<button class="btn btn-primary btn-rounded" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-filter mr-2" aria-hidden></i>
				Filter
			</button>
			<div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
				<ul class="list-group">
					<li class="list-group-item">Active Folders</li>
					<li class="list-group-item">Discharged Folders</li>
					<li class="list-group-item">Deceased Patients</li>
			</div>
		</div>
		<button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#new_patient_modal"><i class="fas fa-user-plus mr-2" aria-hidden></i> New Patient</button>
	</div>
</div>




<div class="card" style="height:80vh">
	<div class="card-body py-5">

		<div class="row mb-5">
			<div class="col-4">
				<h6 class="montserrat font-weight-bold">List Of Patient Folders</h6>
			</div>
			<div class="col-4">

			</div>
		</div>

		<div class="row mb-5">
			<div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
				<div class="form-group">
					<label for="">Search for patients</label>
					<input type="text" name="search_term" id="findpatientsearch" class="form-control" placeholder="patient name or phone" aria-describedby="helpId">
				</div>
			</div>
			<div class="col-lg-3 col-md-6 mb-4 mb-lg-0">

			</div>
			<div class="col-lg-3 col-md-6 mb-4 mb-lg-0">

			</div>
			<div class="col-lg-3 col-md-6 mb-4 mb-lg-0">

			</div>
		</div>

		<div id="data_holder">
			<table class="table table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th>Folder #</th>
						<th>Patient Name</th>
						<th>Age</th>
						<th>Sex</th>
						<th>Phone Number</th>
						<th>Last Visit</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$patients = $p->All(20);

					$i = 1;
					foreach ($patients as $rows) {
						$p->patient_id = $rows['patient_id'];
						$p->PatientInfo();
						$othernames = $rows['othernames'];
					?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td>
								<a href="patient_folder.php?patient_id=<?php echo $p->patient_id; ?>" class="font-weight-bold">
									<?php echo $p->patient_id; ?></a>
							</td>
							<td><?php echo $p->patient_fullname; ?></td>
							<td><?php echo $p->age; ?></td>
							<td><?php echo ucfirst($p->sex); ?></td>
							<td><?php echo $p->phone_number; ?></td>
							<td><?php echo $p->last_visit; ?></td>
							<td class="text-right">
								<a class="" href="patient_folder.php?patient_id=<?php echo $p->patient_id; ?>">
									Open Folder
									<i class="fas fa-arrow-right ml-2" aria-hidden></i>
								</a>
							</td>
						</tr>


					<?php
					}
					?>
		</div>

		</tbody>
		</table>
	</div>


</div>
</div>








</div>
</main>



<div id="modal_holder">

</div>







<?php
require_once('../Includes/modals/patients/newPatient.php');
?>

</body>

<!--   Core JS Files   -->
<?php require_once '../navigation/footer.php'; ?>

<script type="text/javascript" src="../Includes/js/patients/findPatient.js"></script>

</html>