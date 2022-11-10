<?php require_once '../navigation/header_lite.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>


<?php
require_once '../serverscripts/Classes/Patient.php';
require_once '../serverscripts/Classes/OPD.php';
require_once '../serverscripts/Classes/Services.php';
require_once '../serverscripts/Classes/Tests.php';
require_once '../serverscripts/Classes/Payments.php';
require_once '../serverscripts/Classes/Surgeries.php';
require_once '../serverscripts/Classes/Wards.php';
require_once '../serverscripts/Classes/Drugs.php';
require_once '../serverscripts/Classes/Pharmacy.php';
require_once '../serverscripts/Classes/Staff.php';
?>

<style media="screen">
	/* .card-body .list-group-item{
		border:none !important;
	} */
</style>
<?php
// ob_clean();
$visit_id = clean_string($_GET['visit_id']);

// Check  visit id is empty
if ($visit_id == "") {
?>
	<script type="text/javascript">
		window.location == 'index.php';
	</script>
	<?php
} else {


	// check if visit id is valid
	$validate = "SELECT * FROM visits WHERE visit_id='" . $visit_id . "' AND subscriber_id='" . $active_subscriber . "'";
	$r = $mysqli->query($validate);
	if ($r->num_rows != 1) {
	?>
		<script type="text/javascript">
			window.location == "index.php";
		</script>
<?php
	}
}






$visit = new Visit();
$visit->VisitInfo($visit_id);



$patient_id = $visit->patient_id;

$p = new Patient();
$p->patient_id = $patient_id;
$p->PatientInfo();

$visit->VisitBilling($patient_id, $visit_id);
$visit->VisitPayment($patient_id, $visit_id);
$visit->VisitBalance($patient_id, $visit_id);

// $doc=new Doctor();



// $get_vitals=mysqli_query($db,"SELECT * FROM vitals WHERE visit_id='".$visit_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
// $vitals=mysqli_fetch_array($get_vitals);

$drug = new Drug();
// $surg=new Surgery();
$opd = new Visit();
$ward = new Ward();
// $admission=new Admission();
$service = new Service();
$billing = new Billing();
$test = new Test();
$payment = new Payment();


$opd->VisitInfo($visit_id);
?>


<a href="#" class="float" data-toggle="modal" data-target="#hidden_menu_modal">
	<i class="fas fa-heartbeat my-float"></i>
</a>

<main class="py-1 mx-lg-5 main" style="min-height:100vh;">
	<div class="container-fluid py-4">



		<?php

		if ($visit->visit_status == 'discharged') {
		?>
			<div class="card primary-color-dark white-text m-0 mb-5">
				<div class="card-body">
					<h4 class="montserrat font-weight-bold">Notice</h4>
					---------
					<p>Patient has been discharged. This is not an active visit.</p>
				</div>
			</div>
		<?php

		} elseif ($visit->visit_status == 'deceased') {
		?>
			<div class="card danger-color-dark white-text m-0 mb-5">
				<div class="card-body">
					<h4 class="montserrat font-weight-bold">Notice</h4>
					---------
					<p>Sorry, Patient is Deceased.</p>
				</div>
			</div>
		<?php
		}

		?>

		<?php
		$check_admission_info = mysqli_query($db, "SELECT * FROM admissions WHERE visit_id='" . $visit_id . "' AND patient_id='" . $patient_id . "' AND subscriber_id='" . $active_subscriber . "'") or die(mysqli_error($db));
		if (mysqli_num_rows($check_admission_info) == 1) {
			$result = mysqli_fetch_array($check_admission_info);
			$admission->admission_id = $result['admission_id'];
			$admission->AdmissionInfo();
		?>
			<div class="card">
				<div class="card-body">
					<p>Patient Was Admitted On Admission For <?php echo $admission->days_on_admission; ?> Days. <a href="inpatientmanagement.php?admission_id=<?php echo $result['admission_id']; ?>">Click Here</a> To View Patient Report. </p>
				</div>
			</div>
		<?php
		}
		?>
		<div class="row mb-5">
			<div class="col-4">
				<h4 class="titles">OPD Information</h4>
			</div>
			<div class="col-8 text-right d-flex flex-row-reverse
						<?php
						if ($visit->visit_status == 'discharged') {
							echo 'd-none';
						}
						?>
				">

				<button type="button" name="button" class="btn btn-primary btn-rounded py-2
						<?php if ($visit->visit_status != 'active') {
							echo 'd-none';
						} ?>
					" data-toggle="modal" data-target="#new_ultrasound_modal">
					<i class="fas fa-search mr-2"></i>
					Radiology
				</button>


				<button type="button" name="button" style="border-radius:10px" class="btn btn-primary py-2
						<?php if ($visit->visit_status != 'active') {
							echo 'd-none';
						} ?>
					" data-toggle="modal" data-target="#new_lab_request_modal">
					<i class="fas fa-plus mr-2"></i>
					Request Labs
				</button>

				<div class="dropdown open <?php if ($visit->visit_status != 'active') {
												echo 'd-none';
											} ?>">
					<button class="btn btn-primary py-2 dropdown-toggle" style="border-radius:10px" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-pen mr-3"></i>
						Prescribe
					</button>
					<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
						<ul class="list-group">
							<li class="list-group-item" data-toggle="modal" data-target="#prescription_modal">Internal Prescription</li>
							<li class="list-group-item">External Prescription</li>
						</ul>
					</div>
				</div>



				<div class="dropdown open <?php if ($visit->visit_status != 'active') {
												echo 'd-none';
											} ?>">
					<button class="btn btn-primary btn-rounded py-2  dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-user-circle mr-2" aria-hidden></i>
						Options
					</button>
					<div class="dropdown-menu b-0 p-0 " aria-labelledby="dropdownMenu1">
						<ul class="list-group minioptions">
							<li class="list-group-item " data-toggle="modal" data-target="#admission_modal"><i class="fas fa-arrow-circle-right mr-2 text-success" aria-hidden></i> Request Admission</li>
							<li class="list-group-item" id="discharge"><i class="fas fa-arrow-circle-left mr-2 text-danger " aria-hidden></i> Discharge</li>
							<li class="list-group-item" id="status_deceased" data-toggle="modal" data-target="#deceased_modal"><i class="fas fa-times mr-2 text-danger " aria-hidden></i> Deceased </li>
						</ul>
					</div>
				</div>
			</div>
		</div>





		<div class="row">
			<div class="col-3 bg-white p-0">

				<?php
				if ($_SESSION['access_level'] == 'administrator') {
					require_once '../navigation/admin_sidebar.php';
				} elseif ($_SESSION['access_level'] == 'doctor') {
					require_once '../navigation/doctor_sidebar.php';
				} elseif ($_SESSION['access_level'] == 'nurse') {
					require_once '../navigation/nurse_sidebar.php';
				} elseif ($_SESSION['access_level'] == 'pharmacist') {
					// header("location: inventory.php");
				} elseif ($_SESSION['access_level'] == 'labtist') {
					// header("location: queued_tests.php");
				}
				?>


			</div>

			<div class="col-9 pr-0">
				<!-- Tab content -->
				<div class="tab-content" id="v-pills-tabContent">

					<!-- Patient Outstanding Bills / break if there is an outstanding bill -->
					<div class="tab-pane fade show active px-4" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
						<section>

							<?php
							$visit_balance = $opd->VisitBalance($patient_id, $visit_id);
							if ($visit_balance > 0) {
							?>
								<div class="card mb-5">
									<div class="card-body">
										<p class="montserrat font-weight-bold"><span class="text-primary"><?php echo $p->patient_fullname; ?></span> | Unpaid Invoices</p>
										<hr class="hr">

										<table class="table table-condensed datatable">
											<thead class="grey lighten-3 font-weight-bold">
												<tr>
													<th>#</th>
													<th>Date</th>
													<th>Bill #</th>
													<th>Narration</th>
													<th class="text-right">Bill Amount</th>
													<th class="text-right">Paid</th>
													<th class="text-right">Balance</th>
												</tr>
											</thead>
											<tbody>

												<ul class="list-group">


													<?php
													$i = 1;
													$total_bill = 0;
													$get_bills = mysqli_query($db, "SELECT *
																																							FROM billing
																																							WHERE
																																								subscriber_id='" . $active_subscriber . "'  AND
																																								status='active' AND
																																								patient_id='" . $patient_id . "'
																																	") or die(mysqli_error($db));

													while ($bills = mysqli_fetch_array($get_bills)) {
														$p->patient_id = $bills['patient_id'];
														$p->PatientInfo();
														$othernames = $p->othernames;

														$billing = new Billing();
														$billing->bill_id = $bills['bill_id'];
														$billing->BillInfo();

														if ($billing->payment_status == 'PAID') {
															continue;
														}
													?>
														<tr class="" style="font-size:11px">
															<td><?php echo $i++; ?></td>
															<td><?php echo $bills['date']; ?></td>
															<td><?php echo $bills['bill_id']; ?></td>
															<td><?php echo $bills['narration']; ?></td>
															<td class="text-right"><?php echo $bills['bill_amount']; ?></td>
															<td class="text-right"><?php echo $billing->total_bill_payment; ?></td>
															<td class="text-right"><?php echo $billing->balance_remaining; ?></td>
														</tr>

													<?php
														$total_bill += $billing->balance_remaining;
													}
													?>
													<tr style="border-top:solid 2px #000">
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td class="text-right font-weight-bold">GHS <?php echo number_format($total_bill, 2); ?></td>
													</tr>
											</tbody>
										</table>

										<p>* Patient must clear all outstanding bills before folder is reactivated.</p>
									</div>
								</div>

							<?php
								die();
							}
							?>


							<!-- Patient Profile -->
							<div class="row mb-5">
								<div class="col-md-5">
									<div class="card br-1">
										<div class="card-body">
											<h6 class="montserrat font-weight-bold">Patient Profile</h6>
											<hr>



											<p class="font-weight-bold montserrat text-uppercase primary-text mt-4" style="font-size:16px"><?php echo $p->patient_fullname; ?></p>

											<div class="row">
												<div class="col-6">
													<p class="font-weight-bold text-uppercase montserrat"><?php echo $p->sex; ?> | <?php echo $p->age; ?></p>
												</div>
												<div class="col-6">
													<p class="font-weight-bold text-uppercase montserrat"></p>
												</div>
											</div>

											<div class="mt-4">
												<p>Major Complaint</p>
												<p class="montserrat font-weight-bold"><?php echo $visit->major_complaint; ?></p>
											</div>


										</div>
									</div>
								</div>
								<div class="col-md-7">
									<div class="row mb-3">
										<div class="col-6">
											<div class="card br-1">
												<div class="card-body">
													<div class="row">
														<div class="col-3">
															<div class="icon-box primary-color d-flex align-items-center justify-content-center">
																<i class="fas fa-heartbeat" aria-hidden></i>
															</div>
														</div>
														<div class="col-9">
															<p class="montserrat font-weight-bold" style="font-size:18px"><?php echo $opd->systolic; ?> / <?php echo $opd->diastolic; ?></p>
															<p>Blood Pressure</p>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-6">
											<div class="card br-1">
												<div class="card-body">
													<div class="row">
														<div class="col-3">
															<div class="icon-box warning-color d-flex align-items-center justify-content-center">
																<i class="fas fa-weight" aria-hidden></i>
															</div>
														</div>
														<div class="col-9">
															<p class="montserrat font-weight-bold" style="font-size:18px"><?php echo $opd->weight; ?></p>
															<p>Weight</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-6">
											<div class="card br-1">
												<div class="card-body">
													<div class="row">
														<div class="col-3">
															<div class="icon-box green d-flex align-items-center justify-content-center">
																<i class="fas fa-temperature-high" aria-hidden></i>
															</div>
														</div>
														<div class="col-9">
															<p class="montserrat font-weight-bold" style="font-size:18px"><?php echo $opd->temperature; ?></p>
															<p>Temperature</p>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-6">
											<div class="card br-1">
												<div class="card-body">
													<div class="row">
														<div class="col-3">
															<div class="icon-box purple d-flex align-items-center justify-content-center">
																<i class="fas fa-stethoscope" aria-hidden></i>
															</div>
														</div>
														<div class="col-9">
															<p class="montserrat font-weight-bold" style="font-size:18px"><?php echo $opd->pulse; ?> BPM</p>
															<p>Pulse</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>



								</div>
							</div>


							<!-- Prescriptions History -->
							<div class="card br-1 my-5">
								<div class="card-body">
									<h6>Diagnosis</h6>
									<hr>
									<div class="">

										<?php
										$get_dates = mysqli_query($db, "SELECT date FROM prescriptions WHERE patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND subscriber_id='" . $active_subscriber . "' GROUP BY date ORDER BY date desc") or die(mysqli_error($db));
										while ($dates = mysqli_fetch_array($get_dates)) {
											$date = $dates['date'];
										?>
											<p class="montserrat font-weight-bold mb-3"><?php echo $dates['date']; ?></p>

											<table class="table">
												<thead class="grey lighten-4">
													<tr>
														<th>Medication</th>
														<th>Dosage</th>
														<th>Duration</th>
														<th>Frequency</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$get_current_meds = mysqli_query($db, "SELECT *
																																																				FROM prescriptions
																																																				WHERE
																																																					patient_id='" . $patient_id . "' AND
																																																					visit_id='" . $visit_id . "' AND
																																																					subscriber_id='" . $active_subscriber . "' AND
																																																					date='" . $date . "'
																																															") or die(mysqli_error($db));
													while ($meds = mysqli_fetch_array($get_current_meds)) {
														$drug->drug_id = $meds['drug_id'];
														$drug->DrugInfo();
													?>
														<tr>
															<td>
																<p><?php echo $drug->drug_name; ?></p>

															</td>
															<td><?php echo $meds['dosage']; ?></td>
															<td><?php echo $meds['duration']; ?></td>
															<td><?php echo $meds['frequency']; ?></td>

														</tr>
													<?php
													}
													?>


												</tbody>
											</table>
										<?php
										}
										?>

									</div>
								</div>
							</div>


							<!-- Prescriptions History -->
							<div class="card br-1 my-5">
								<div class="card-body">
									<h6>Prescriptions</h6>
									<hr>
									<div class="">

										<?php
										$get_dates = mysqli_query($db, "SELECT date FROM prescriptions WHERE patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND subscriber_id='" . $active_subscriber . "' GROUP BY date ORDER BY date desc") or die(mysqli_error($db));
										while ($dates = mysqli_fetch_array($get_dates)) {
											$date = $dates['date'];
										?>
											<p class="montserrat font-weight-bold mb-3"><?php echo $dates['date']; ?></p>

											<table class="table">
												<thead class="grey lighten-4">
													<tr>
														<th>Medication</th>
														<th>Dosage</th>
														<th>Duration</th>
														<th>Frequency</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$get_current_meds = mysqli_query($db, "SELECT *
																																																				FROM prescriptions
																																																				WHERE
																																																					patient_id='" . $patient_id . "' AND
																																																					visit_id='" . $visit_id . "' AND
																																																					subscriber_id='" . $active_subscriber . "' AND
																																																					date='" . $date . "'
																																															") or die(mysqli_error($db));
													while ($meds = mysqli_fetch_array($get_current_meds)) {
														$drug->drug_id = $meds['drug_id'];
														$drug->DrugInfo();
													?>
														<tr>
															<td>
																<p><?php echo $drug->drug_name; ?></p>

															</td>
															<td><?php echo $meds['dosage']; ?></td>
															<td><?php echo $meds['duration']; ?></td>
															<td><?php echo $meds['frequency']; ?></td>

														</tr>
													<?php
													}
													?>


												</tbody>
											</table>
										<?php
										}
										?>

									</div>
								</div>
							</div>


							<div class="card mb-5 d-none">
								<div class="card-body">

									<div class="text-right mb-4">
										<div class="dropdown open">
											<button class="btn btn-primary btn-rounded py-2  dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fas fa-user-circle mr-2" aria-hidden></i>
												Options
											</button>
											<div class="dropdown-menu b-0 p-0 " aria-labelledby="dropdownMenu1">
												<ul class="list-group minioptions">
													<li class="list-group-item" data-toggle="modal" data-target="#admission_modal"><i class="fas fa-arrow-circle-right mr-2 text-success" aria-hidden></i> Request Admission</li>
													<li class="list-group-item" id="discharge"><i class="fas fa-arrow-circle-left mr-2 text-danger " aria-hidden></i> Discharge</li>
													<li class="list-group-item"><i class="fas fa-times mr-2 text-danger " aria-hidden></i> Dead </li>
												</ul>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-4">

											<?php
											// if($visit->admission_status=='admitted'){
											// 	
											?>
											<!-- // 	<p class="text-center mb-3 montserrat font-weight-bold">ADMITTED</p>
														// 	<hr> -->
											<?php
											// }
											?>

											<div class="text-center mb-3">
												<?php
												switch ($p->sex) {
													case 'male':
														echo '<img src="../images/dummy_male.png" alt="" class="img-fluid" style="width:100px">';
														break;
													case 'female':
														echo '<img src="../images/dummy_female.png" alt="" class="img-fluid mx-auto" style="width:100px">';
														break;

													default:
														// code...
														break;
												}
												?>
											</div>



										</div>
										<div class="col-md-8">

											<p class="font-weight-bold montserrat text-uppercase primary-text" style="font-size:16px"><?php echo $p->patient_fullname; ?></p>

											<div class="row">
												<div class="col-6">
													<p class="font-weight-bold text-uppercase montserrat"><?php echo $p->sex; ?> | <?php echo $p->age; ?></p>
												</div>
												<div class="col-6">
													<p class="font-weight-bold text-uppercase montserrat"></p>
												</div>
											</div>

											<div class="row mt-4">
												<div class="col-md-4">
													<p class="poppins" style="font-size:10px">Visit Type</p>
													<p class="montserrat font-weight-bold">
														<?php
														switch ($visit->visit_type) {
															case 'new_visit':
																echo 'New Visit';
																break;
															case 'review':
																echo 'Review';
																break;

															default:
																// code...
																break;
														}
														?>
													</p>
												</div>
												<div class="col-md-8">
													<p class="poppins" style="font-size:10px">Major Complaint</p>
													<p class="montserrat font-weight-bold"><?php echo $visit->major_complaint; ?></p>
												</div>
											</div>
											<!-- End row -->




										</div>
									</div>

									<!-- Nurses Vitals Section -->
									<section style="border-top:solid 1px #eaeaea" class="mt-5 py-4">
										<p class="montserrat" style="font-size:20px; font-weight:800">Vital Signs</p>
										<hr class="mt-1 mb-4" style="border-top:solid 2px #000; width:100px; margin-left:0px">

										<div class="row poppins">
											<div class="col-md-2">
												<p class="" style="font-size:11px">Blood Pressure</p>
												<p class="montserrat font-weight-bold"><?php echo $opd->systolic; ?> / <?php echo $opd->diastolic; ?></p>
											</div>
											<div class="col-md-2">
												<p class="" style="font-size:11px">Weight</p>
												<p class="montserrat font-weight-bold"> <?php echo $opd->weight; ?> <sub>kg</sub></p>
											</div>
											<div class="col-md-2">
												<p class="" style="font-size:11px">Temperature</p>
												<p class="montserrat font-weight-bold"><?php echo $opd->temperature; ?> <sup>o</sup></p>
											</div>
											<div class="col-md-2">
												<p class="" style="font-size:11px">Pulse</p>
												<p class="montserrat font-weight-bold"><?php echo $opd->pulse; ?> BPM</p>
											</div>

										</div>
									</section>

									<section style="border-top:solid 1px #eaeaea" class="py-4">
										<p class="montserrat" style="font-size:20px; font-weight:800">Flow Map</p>
										<hr class="mt-1 mb-4" style="border-top:solid 2px #000; width:100px; margin-left:0px">

										<?php
										$get_log = mysqli_query($db, "SELECT * FROM visit_log WHERE visit_id='" . $visit_id . "' AND patient_id='" . $patient_id . "' AND subscriber_id='" . $active_subscriber . "' ") or die(mysqli_error($db));

										while ($rows = mysqli_fetch_array($get_log)) {
										?>

											<div class="shadow1 my-3 p-2" style="border-radius:8px">
												<div class="row poppins" style="font-size:11px">
													<div class="col-6">
														<i class="fas fa-check-circle text-primary mr-2" aria-hidden></i> <?php echo $rows['notes']; ?>
													</div>
													<div class="col-6 text-right">
														<?php echo $rows['date']; ?> , <?php echo $rows['time']; ?>
													</div>
												</div>
											</div>
											<div class="pl-1">
												<div class="" style="border-left:solid 1px; height:30px">

												</div>
											</div>
										<?php
										}
										?>
									</section>

								</div>
							</div>
							<!-- end card -->

						</section>

					</div>

					<!-- Vital Signs content-->
					<div class="tab-pane fade" id="vitals_content" role="tabpanel" aria-labelledby="vitals_tab">
						<section>

							<div class="row">
								<div class="col-md-6">
									<div class="card">
										<div class="card-body">
											<h6 class="montserrat font-weight-bold">Patient Vitals Signs</h6>



											<form id="patient_vitals_frm" autocomplete="off">



												<div class="form-group d-none">
													<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
												</div>

												<div class="form-group d-none">
													<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
												</div>

												<div class="row">
													<div class="col-md-5">
														<div class="form-group">
															<label for="">Systolic B.P</label>
															<input type="text" name="systolic" class="form-control" placeholder="" required value="<?php echo $opd->systolic; ?>">
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<div style="margin-top:30px; font-size:20px">
																/
															</div>
														</div>

													</div>
													<div class="col-md-5">
														<div class="form-group">
															<label for="">Diastolic B.P</label>
															<input type="text" name="diastolic" class="form-control" placeholder="" required value="<?php echo $opd->diastolic; ?>">
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="">Pulse</label>
													<input type="text" name="pulse" class="form-control" placeholder="" value="<?php echo $opd->pulse; ?>">
												</div>

												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="">Temperature</label>
															<input type="text" name="temperature" class="form-control" placeholder="" value="<?php echo $opd->temperature; ?>">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="">Weight</label>
															<input type="text" name="weight" class="form-control" value="<?php echo $opd->weight; ?>">
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="">Refer to Doctor</label>
													<select class="custom-select browser-default" name="doctor_id">
														<?php
														$get_doctors = mysqli_query($db, "SELECT * FROM staff WHERE role='doctor' AND  subscriber_id='" . $active_subscriber . "' AND status='active'") or die(mysqli_error($db));
														while ($doctors = mysqli_fetch_array($get_doctors)) {
														?>
															<option value="<?php echo $doctors['staff_id']; ?>"><?php echo $doctors['title'] . ' ' . $doctors['othernames']; ?></option>
														<?php
														}
														?>

													</select>
												</div>


												<button type="submit" class="btn btn-primary wide b-0 <?php if ($visit->visit_status != 'active') {
																											echo 'd-none';
																										} ?>">Record Vitals</button>

											</form>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card mb-3">
										<div class="card-body px-1">
											<p class="montserrat font-weight-bold mb-3 mx-3">Vital Signs </p>
											<div class="grey lighten-3 p-3">

												<p class="font-weight-bold m-0 montserrat"><?php echo $opd->systolic; ?> / <?php echo $opd->diastolic; ?></p>
												Blood Pressure

												<hr>

												<p class="font-weight-bold m-0 montserrat"><?php echo $opd->weight; ?><sub>kg</sub></p>
												Weight

												<hr>

												<p class="font-weight-bold m-0 montserrat"><?php echo $opd->temperature; ?><sup>o</sub></p>
												Temperature
												<hr>

												<p class="font-weight-bold m-0 montserrat"><?php echo $opd->pulse; ?> BPM</p>
												Pulse

											</div>
										</div>
									</div>
								</div>
							</div>





						</section>
					</div>

					<!-- Consultation content -->
					<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">

						<section>

							<div class="card">
								<div class="card-body p-0" style="min-height:80vh">
									<div class="row">
										<div class="col-md-4">
											<ul class=" list-group xmedici_pills2 nav nav-pills" id="ex1" role="tablist">
												<li class="xmedici-lists nav-item p-0 b-0" role"presentation">
													<a class="nav-link active" id="complains_tab" data-toggle="pill" href="#complains_content" role="tab" aria-controls="complains_content" aria-selected="true">

														<p class="m-0  montserrat font-weight-bold">Presenting Complains</p>
														<p class="m-0" style="font-weight:400; font-size:11px">Patients Complains</p>
													</a>
												</li>
												<li class="xmedici-lists montserrat font-weight-bold nav-item p-0 b-0" role"presentation">
													<a class="nav-link" id="odq_tab" data-toggle="pill" href="#odq_content" role="tab" aria-controls="odq_content" aria-selected="true">

														<p class="m-0  montserrat font-weight-bold">ODQ</p>
														<p class="m-0" style="font-weight:400; font-size:11px">Out-Direct Questioning</p>



													</a>
												</li>
												<li class="xmedici-lists montserrat font-weight-bold nav-item p-0 b-0" role"presentation">
													<a class="nav-link" id="hpc_tab" data-toggle="pill" href="#hpc_content" role="tab" aria-controls="hpc_content" aria-selected="true">

														<p class="m-0  montserrat font-weight-bold">2. HPC</p>
														<p class="m-0" style="font-weight:400; font-size:11px">Hist. Of Pres. Complains</p>



													</a>
												</li>
												<li class="xmedici-lists montserrat font-weight-bold nav-item p-0 b-0" role"presentation">
													<a class="nav-link" id="examination_tab" data-toggle="pill" href="#examination_content" role="tab" aria-controls="examination_content" aria-selected="true">
														<p class="m-0  montserrat font-weight-bold">3. Clinical Examination</p>
														<p class="m-0" style="font-weight:400; font-size:11px">Doctor's Examination & Observation</p>
													</a>
												</li>
												<li class="xmedici-lists montserrat font-weight-bold nav-item p-0 b-0" role"presentation">
													<a class="nav-link" id="diagnosis_tab" data-toggle="pill" href="#diagnosis_content" role="tab" aria-controls="diagnosis_content" aria-selected="true">
														4. Diagnosis
													</a>
												</li>
												<li class="xmedici-lists montserrat font-weight-bold nav-item p-0 b-0" role"presentation">
													<a class="nav-link" id="secondary_diagnosis_tab" data-toggle="pill" href="#secondary_diagnosis_content" role="tab" aria-controls="secondary_diagnosis_content" aria-selected="true">
														5. Secondary Diagnosis
													</a>
												</li>
												<li class="xmedici-lists montserrat font-weight-bold nav-item p-0 b-0" role"presentation">
													<a class="nav-link" id="notes_tab" data-toggle="pill" href="#notes_content" role="tab" aria-controls="notes_content" aria-selected="true">
														6. Notes
													</a>
												</li>
											</ul>
										</div>
										<div class="col-md-8">
											<div class="tab-content">
												<div class="tab-pane fade show active pt-3" id="complains_content" role="tabpanel" aria-labelledby="complains_tab">

													<h6 class="montserrat font-weight-bold mb-3">Presenting Complains</h6>
													<hr class="hr" style="width:30%; margin-left:0px">

													<section class="pr-4 mt-5">
														<form id="record_complain_frm" autocomplete="off">

															<div class="form-group d-none">
																<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
															</div>

															<div class="form-group d-none">
																<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
															</div>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label for="">Complain</label>
																		<input type="text" name="complain" class="form-control" value="">
																		<!-- <select class="custom-select browser-default select2" name="complain" id="complains_dropdown">
																							<?php
																							// $get_complains=mysqli_query($db,"SELECT * FROM syst_complains") or die(mysqli_error($db));
																							// while ($rows=mysqli_fetch_array($get_complains)) {
																							?>
																									<option value="<?php //echo $rows['description'] 
																													?>"><?php //echo $rows['description']; 
																																							?></option>
																									<?php
																									//}
																									?>
																						</select> -->
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label for="">Severity</label>
																		<select class="custom-select browser-default " name="complain_status">
																			<option value="Mild">Mild</option>
																			<option value="Moderate">Moderate</option>
																			<option value="Severe">Severe</option>
																		</select>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label for="">Duration</label>
																		<input type="text" name="complain_duration" class="form-control" value="">
																	</div>
																</div>
															</div>


															<button type="submit" class="btn btn-primary wide m-0 <?php if ($visit->visit_status != 'active') {
																														echo 'd-none';
																													} ?>">Add Complain</button>
														</form>


														<div class="grey lighten-4 px-4 py-2 mt-4" id="complains_holder">
															<?php

															$query = mysqli_query($db, "SELECT * FROM patient_complains WHERE subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND status='active'") or die(mysqli_error($db));

															if (mysqli_num_rows($query) == 0) {
																echo 'No Complains';
															} else {
																while ($complains = mysqli_fetch_array($query)) {
															?>
																	<div class="card mt-4">
																		<div class="card-body">
																			<div class="row" style="font-size:11px">
																				<div class="col-8">
																					<p class="m-0 montserrat font-weight-bold"><?php echo $complains['complain']; ?></p>
																					<p class="m-0 montserrat">Duration : <?php echo $complains['complain_duration']; ?></p>
																				</div>
																				<div class="col-4">
																					<a href="#" class="btn btn-danger btn-sm remove_complain" id="<?php echo $complains['sn']; ?>">
																						<i class="fas fa-trash-alt mr-2" aria-hidden></i> Remove
																					</a>
																				</div>
																			</div>

																		</div>
																	</div>



																	<div class="mb-3">

																	</div>
															<?php
																}
															}

															?>
														</div>

													</section>
												</div>
												<div class="tab-pane fade pt-3" id="odq_content" role="tabpanel" aria-labelledby="odq_tab">
													<h6 class="montserrat font-weight-bold">Out - Direct Questioning</h6>
													<hr class="hr" style="width:30%; margin-left:0px">

													<section class="pr-4 mt-5">
														<form id="record_odq_frm" autocomplete="off">

															<div class="form-group d-none">
																<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
															</div>

															<div class="form-group d-none">
																<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
															</div>

															<div class="row">
																<div class="col-md-9">
																	<div class="form-group">
																		<label for="">Question</label>
																		<input type="text" name="question" class="form-control" value="">
																		<select class="custom-select browser-default select2" name="question" id="question_dropdown"> -->
																			<?php
																			$get_odq = mysqli_query($db, "SELECT * FROM syst_odq") or die(mysqli_error($db));
																			while ($rows = mysqli_fetch_array($get_odq)) {
																			?>
																				<option value="<?php echo $rows['question'] ?>"><?php echo $rows['question']; ?></option>
																			<?php
																			}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label for="">Response</label>
																		<select class="custom-select browser-default " name="response">
																			<option value="+">+</option>
																			<option value="-">-</option>
																		</select>
																	</div>
																</div>
															</div>



															<button type="submit" class="btn btn-primary wide m-0 <?php if ($visit->visit_status != 'active') {
																														echo 'd-none';
																													} ?>">Save ODQ</button>
														</form>

														<div class="grey lighten-4 px-4 py-2 mt-5" id="odq_holder">
															<?php

															$query = mysqli_query($db, "SELECT *
																																								FROM patient_odq
																																								WHERE
																																									subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "'
																																			") or die(mysqli_error($db));

															if (mysqli_num_rows($query) == 0) {
																echo 'No ODQ';
															} else {
																// $hpc_count=1;
																while ($odq = mysqli_fetch_array($query)) {
															?>
																	<div class="card">
																		<div class="card-body py-2">
																			<p class="m-0 poppins font-weight-bold"><?php echo $odq['question']; ?> <?php echo $odq['response']; ?></p>
																			<button type="button" class="btn btn-danger btn-sm remove_odq" id="<?php echo $odq['sn']; ?>">Remove</button>
																		</div>
																	</div>


																	<div class="mb-3">

																	</div>
															<?php
																}
															}

															?>

														</div>
													</section>




												</div>
												<div class="tab-pane fade pt-3" id="hpc_content" role="tabpanel" aria-labelledby="hpc_tab">

													<h6 class="montserrat font-weight-bold">History Of Presenting Complain</h6>
													<hr class="hr" style="width:30%; margin-left:0px">

													<section class="pr-4 mt-5">
														<form id="hpc_frm" autocomplete="off">

															<div class="form-group d-none">
																<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
															</div>

															<div class="form-group d-none">
																<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
															</div>

															<div class="form-group">
																<textarea name="hpc" id="hpc" class="form-control" required> </textarea>
															</div>


															<button type="submit" class="btn btn-primary wide m-0 <?php if ($visit->visit_status != 'active') {
																														echo 'd-none';
																													} ?>">Record HPC</button>
														</form>

														<div class="mt-3">
															<p class="poppins font-weight-bold mb-5">History Of Presenting Complains</p>
															<?php

															$query = mysqli_query($db, "SELECT * FROM patient_hpc WHERE subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "'") or die(mysqli_error($db));

															if (mysqli_num_rows($query) == 0) {
																echo 'No HPC';
															} else {
																$hpc_count = 1;
																while ($hpc = mysqli_fetch_array($query)) {
															?>
																	<div class="row mb-2">
																		<div class="col-9">
																			<p class="m-0 poppins mb-3 "><?php echo  $hpc_count++; ?> .<?php echo $hpc['history']; ?></p>
																		</div>
																		<div class="col-32">
																			<div class="dropdown open <?php if ($visit->visit_status != 'active') {
																											echo 'd-none';
																										} ?>">
																				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					Opt
																				</button>
																				<div class="dropdown-menu" aria-labelledby="dropdownMenu1">
																					<ul class="list-group">
																						<li class="list-group-item">Edit</li>
																						<li class="list-group-item">Delete</li>
																					</ul>
																				</div>
																			</div>
																		</div>
																	</div>


																	<div class="">

																	</div>
																	<div class="mb-3">

																	</div>
															<?php
																}
															}

															?>

														</div>
													</section>





												</div>
												<div class="tab-pane fade pt-3" id="examination_content" role="tabpanel" aria-labelledby="examination_tab">

													<h6 class="montserrat font-weight-bold mb-3">Clinical Examination</h6>
													<hr class="hr" style="width:30%; margin-left:0px">

													<div class="pr-4 mt-5">
														<form id="clinical_examination_frm">
															<div class="form-group d-none">
																<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
															</div>

															<div class="form-group d-none">
																<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
															</div>
															<textarea name="clinical_examination" id="clinical_examination"></textarea>
															<button type="submit" class="btn btn-primary m-0 mt-4"><i class="fas fa-check-circle mr-3" aria-hidden></i> Save</button>
														</form>
													</div>

													<div class=" mt-5" id="examination_holder">
														<p class="poppins font-weight-bold mb-3">Clinical Examination</p>
														<?php

														$query = mysqli_query($db, "SELECT *
																																							FROM patient_examination
																																							WHERE
																																								subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "'
																																		") or die(mysqli_error($db));

														if (mysqli_num_rows($query) == 0) {
															echo 'No Clinical Examination';
														} else {
															// $hpc_count=1;
															while ($odq = mysqli_fetch_array($query)) {
														?>
																<div class="row mb-2">
																	<div class="col-9">
																		<p><?php echo $odq['observation']; ?></p>
																	</div>
																	<div class="col-3">
																		<div class="dropdown open">
																			<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																				Opt
																			</button>
																			<div class="dropdown-menu" aria-labelledby="dropdownMenu1">
																				<ul class="list-group">
																					<li class="list-group-item">Edit</li>
																					<li class="list-group-item">Delete</li>
																				</ul>
																			</div>
																		</div>
																	</div>
																</div>

														<?php
															}
														}

														?>
													</div>


												</div>
												<div class="tab-pane fade pt-3" id="diagnosis_content" role="tabpanel" aria-labelledby="diagnosis_tab">



													<h6 class="montserrat font-weight-bold mb-3">Diagnosis</h6>
													<hr class="hr" style="width:30%; margin-left:0px">

													<div class="row">
														<div class="col-md-8">
															<div class="form-group">
																<label for="">Diagnosis</label>
																<input type="text" name="diagnosis" id="diagnosis_search_term" class="form-control" value="" placeholder="Type Diagnosis or ICD10 Code">
															</div>
														</div>
													</div>

													<div class="" id="diagnosis_holder">

													</div>

													<?php

													$query = mysqli_query($db, "SELECT *
																																									FROM patient_diagnosis
																																									WHERE
																																										subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND status='active'
																																				") or die(mysqli_error($db));

													if (mysqli_num_rows($query) == 0) {
														echo 'No Diagnosis';
													} else {
														while ($diagnosis = mysqli_fetch_array($query)) {
													?>
															<p class="montserrat font-weight-bold m-0"> <?php echo $diagnosis['diagnosis_id']; ?></p>
															<p class="m-0 montserrat"><?php echo $opd->Diagnosis($diagnosis['diagnosis_id']); ?></p>
															<a href="" class="text-danger remove_diagnosis" id="<?php echo $diagnosis['sn']; ?>">Click to Remove</a>
															<div class="mb-4">

															</div>
													<?php

														}
													}

													?>

												</div>
												<div class="tab-pane fade pt-3" id="secondary_diagnosis_content" role="tabpanel" aria-labelledby="secondary_diagnosis_tab">



													<h6 class="montserrat font-weight-bold mb-3">Secondary Diagnosis</h6>
													<hr class="hr" style="width:30%; margin-left:0px">

													<form id="secondary_diagnosis_frm" class="p-3">

														<div class="form-group">
															<label for="">Type In Secondary Diagnosis</label>
															<textarea name="secondary_diagnosis" id="secondary_diagnosis" class="form-control" value=""></textarea>
														</div>
														<button type="submit" class="btn btn-primary wide">Add Diagnosis</button>


													</form>

													<?php

													$query = mysqli_query($db, "SELECT *
																																									FROM patient_secondary_diagnosis
																																									WHERE
																																										subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND status='active'
																																				") or die(mysqli_error($db));

													if (mysqli_num_rows($query) == 0) {
														echo 'No Diagnosis';
													} else {
														while ($diagnosis = mysqli_fetch_array($query)) {
													?>
															<div class="">
																<?php echo $diagnosis['diagnosis']; ?>
															</div>
															<p class="montserrat font-weight-bold m-0"> </p>
															<p class="m-0 montserrat"><?php echo $opd->Diagnosis($diagnosis['diagnosis_id']); ?></p>
															<a href="" class="text-danger remove_secondary_diagnosis" id="<?php echo $diagnosis['sn']; ?>">Click to Remove</a>
															<div class="mb-4">

															</div>
													<?php

														}
													}

													?>

												</div>
												<div class="tab-pane fade pt-3" id="notes_content" role="tabpanel" aria-labelledby="notes_tab">

													<section class="p-4">
														<form id="doctors_notes_frm">
															<h6 class="font-weight-bold montserrat">Doctor's Notes</h6>
															<textarea name="doctors_notes" id="doctors_notes" class="form-control"></textarea>
															<button type="submit" class="btn btn-primary">Record Doctors Notes</button>
														</form>
													</section>


													<?php

													$query = mysqli_query($db, "SELECT *
																																						FROM patient_doctors_notes
																																						WHERE
																																							subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND status='active'
																																	") or die(mysqli_error($db));

													if (mysqli_num_rows($query) == 0) {
														echo 'No Notes Written';
													} else {
														while ($notes = mysqli_fetch_array($query)) {
													?>
															<div class="">
																<?php echo $notes['doctors_notes']; ?>
															</div>
															<a href="" class="text-danger remove_doctors_notes" id="<?php echo $notes['sn']; ?>">Click to Remove</a>
															<div class="mb-4">

															</div>
													<?php

														}
													}

													?>


												</div>

											</div>

										</div>
									</div>
								</div>
							</div>

						</section>

					</div>

					<!-- Investigations content-->
					<div class="tab-pane fade" id="investigations_content" role="tabpanel" aria-labelledby="investigations_tab">

						<section>
							<div class="card">
								<div class="card-body">


									<div class="row mb-4">
										<div class="col-md-6">
											<h6 class="montserrat font-weight-bold m-0">Requested Investigations</h6>
											<p class="poppins">List of laboratory and medical imaging requests</p>
										</div>
										<div class="col-md-6 text-right">
											<button type="button" name="button" style="border-radius:10px" class="btn btn-primary py-2" data-toggle="modal" data-target="#new_lab_request_modal">
												<i class="fas fa-plus mr-2"></i>
												Request Labs
											</button>
										</div>
									</div>

									<div class="text-right mb-3">

									</div>


									<div class="grey lighten-4 p-3">
										<li class="list-group-item custom-list-item">
											<div class="row">
												<div class="col-md-2">
													Request ID
												</div>
												<div class="col-md-6">
													Test Name
												</div>
												<div class="col-md-2">
													Result Status
												</div>
												<div class="col-md-2 text-right">
													Option
												</div>
											</div>
										</li>
										<?php
										$get_lab_requests = mysqli_query($db, "SELECT * FROM lab_requests_tests WHERE patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND status='active'") or die(mysqli_error($db));
										while ($requests = mysqli_fetch_array($get_lab_requests)) {

											$test->test_id = $requests['test_id'];
											$test->TestInfo();
										?>
											<li class="list-group-item">
												<div class="row">
													<div class="col-md-2">
														<?php echo $requests['request_id']; ?>
													</div>
													<div class="col-md-6">
														<?php echo $test->description; ?>
													</div>
													<div class="col-md-2">
														<?php echo $requests['results_status']; ?>
													</div>
													<div class="col-md-2 text-right">
														<button type="button" class="btn btn-primary btn-sm view_test_result" data-test_id="<?php echo $requests['test_id']; ?>" data-request_id="<?php echo $requests['request_id'] ?>"><i class="fa fa-file-alt mr-2"></i> Results</button>
													</div>
												</div>
											</li>
										<?php
										}
										?>

									</div>


								</div>
							</div>
						</section>

					</div>

					<!-- Medications content-->
					<div class="tab-pane fade" id="medication_content" role="tabpanel" aria-labelledby="medication_tab">

						<section>
							<div class="card">
								<div class="card-body">

									<div class="row mb-4">
										<div class="col-md-6">
											<h6 class="montserrat font-weight-bold m-0">Prescribed Meds</h6>
											<p class="poppins">Patient Medications</p>
										</div>
										<div class="col-md-6 text-right">


											<div class="dropdown open">
												<button class="btn btn-primary py-2 dropdown-toggle" style="border-radius:10px" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="fas fa-pen mr-3"></i>
													Prescribe
												</button>
												<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
													<ul class="list-group">
														<li class="list-group-item" data-toggle="modal" data-target="#prescription_modal">Internal Prescription</li>
														<li class="list-group-item">External Prescription</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									<div class="">

										<?php
										$get_dates = mysqli_query($db, "SELECT date
																																														FROM prescriptions
																																														WHERE
																																															patient_id='" . $patient_id . "' AND
																																															visit_id='" . $visit_id . "' AND
																																															subscriber_id='" . $active_subscriber . "'
																																															GROUP BY date
																																															ORDER BY date desc
																																									") or die(mysqli_error($db));
										while ($dates = mysqli_fetch_array($get_dates)) {
											$date = $dates['date'];
										?>
											<p class="montserrat font-weight-bold mb-3"><?php echo $dates['date']; ?></p>

											<table class="table">
												<thead class="grey lighten-4">
													<tr>
														<th>Medication</th>
														<th>Dosage</th>
														<th>Duration</th>
														<th>Frequency</th>
													</tr>
												</thead>
												<tbody>
													<?php

													$get_current_meds = mysqli_query($db, "SELECT *
																																																				FROM prescriptions
																																																				WHERE
																																																					patient_id='" . $patient_id . "' AND
																																																					visit_id='" . $visit_id . "' AND
																																																					subscriber_id='" . $active_subscriber . "' AND
																																																					date='" . $date . "'
																																															") or die(mysqli_error($db));
													while ($meds = mysqli_fetch_array($get_current_meds)) {
														$drug->drug_id = $meds['drug_id'];
														$drug->DrugInfo();
													?>
														<tr>
															<td>
																<p><?php echo $drug->drug_name; ?></p>

															</td>
															<td><?php echo $meds['dosage']; ?></td>
															<td><?php echo $meds['duration']; ?></td>
															<td><?php echo $meds['frequency']; ?></td>

														</tr>
													<?php
													}
													?>


												</tbody>
											</table>
										<?php
										}
										?>

									</div>



								</div>
							</div>
						</section>

					</div>

					<!-- Surgeries content-->
					<div class="tab-pane fade" id="surgery_content" role="tabpanel" aria-labelledby="surgery_tab">
						<section>

							<div class="card">
								<div class="card-body">

									<div class="row mb-4">
										<div class="col-md-6">
											<h6 class="montserrat font-weight-bold m-0">Surgeries</h6>
											<p class="poppins">Surgical Procedures and Operations</p>
										</div>
										<div class="col-md-6 text-right">
											<button type="button" name="button" style="border-radius:10px" class="btn btn-primary py-2" data-toggle="modal" data-target="#new_surgery_modal">
												<i class="fas fa-bed mr-3"></i>
												Surgery
											</button>
										</div>
									</div>

									<div class="grey lighten-4 p-4">
										<li class="list-group-item custom-list-item">
											<div class="row">
												<div class="col-md-1">
													#
												</div>
												<div class="col-md-3">
													Procedure
												</div>
												<div class="col-md-2">
													Type
												</div>
												<div class="col-md-2">
													Cost
												</div>
												<div class="col-md-2">
													Date
												</div>
											</div>
										</li>
										<?php
										$i = 1;
										$get_surgeries = mysqli_query($db, "SELECT * FROM surgeries WHERE patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND subscriber_id='" . $active_subscriber . "' AND status='active'") or die(mysqli_error($db));
										while ($rows = mysqli_fetch_array($get_surgeries)) {
											$surg->surgery_id = $rows['surgery_id'];
											$surg->SurgeryInfo();
										?>
											<li class="list-group-item hover">
												<div class="row">
													<div class="col-md-1">
														<?php echo $i++; ?>
													</div>
													<div class="col-md-3">
														<?php echo $rows['surgical_procedure']; ?>
													</div>
													<div class="col-md-2">
														<?php echo $rows['procedure_type']; ?>
													</div>
													<div class="col-md-2">
														<?php echo number_format($surg->surgery_cost, 2); ?>
													</div>
													<div class="col-md-2">
														<?php echo  $rows['date']; ?>
													</div>
													<div class="col-md-2 text-right">
														<a href="surgery.php?surgery_id=<?php echo $rows['surgery_id']; ?>" class="btn btn-info btn-sm">Manage</a>
													</div>
												</div>
											</li>
										<?php
										}
										?>
									</div>

								</div>

							</div>
						</section>
					</div>


					<!-- Files content-->
					<div class="tab-pane fade" id="files_content" role="tabpanel" aria-labelledby="files_tab">
						<section>
							<div class="card mb-3">
								<div class="card-body px-1">
									<p class="montserrat font-weight-bold mx-3 mb-3"><i class="far fa-file-alt mr-2" aria-hidden></i> Uploaded Files</p>
									<div class="px-3 py-3 grey lighten-4">

										<form action="../serverscripts/admin/Patients/file_upload.php?visit_id=<?php echo $visit_id; ?>&patient_id=<?php echo $patient_id; ?>" class="dropzone" id="my-awesome-dropzone"></form>

									</div>
								</div>
							</div>
						</section>

						<div class="card mt-5">
							<div class="card-body">
								<h6 class="montserrat font-weight-bold mb-3">Uploaded Files</h6>

								<div class="row">
									<?php
									$get_uploads = mysqli_query($db, "SELECT * FROM file_uploads WHERE visit_id='" . $visit_id . "' AND subscriber_id='" . $active_subscriber . "'") or die(mysqli_error($db));
									while ($rows = mysqli_fetch_array($get_uploads)) {
									?>
										<div class="col-md-6">
											<img src="../FileUploads/<?php echo $rows['file_name']; ?>" alt="" class="img-fluid">
											<p class="mt-3">Uploaded On <?php echo $rows['date']; ?></p>
										</div>
									<?php
									}
									?>
								</div>

							</div>
						</div>
					</div>

					<!-- Billing content-->
					<div class="tab-pane fade" id="billing_content" role="tabpanel" aria-labelledby="billing_tab">
						<section>
							<div class="card mb-3">
								<div class="card-body px-1">

									<div class="row mb-3">
										<div class="col-8 d-flex align-items-center">
											<p class="montserrat font-weight-bold   mx-3"><i class="fas fa-shopping-basket mr-2" aria-hidden></i> Billing</p>
										</div>
										<div class="col-4 d-flex align-items-center">
											<button type="button" class="btn btn-primary py-2" data-toggle="modal" data-target="#billing_modal">Add Bill</button>
										</div>
									</div>

									<div class="px-3 py-3 grey lighten-4">
										<?php
										$get_bills = mysqli_query($db, "SELECT * FROM billing WHERE subscriber_id='" . $active_subscriber . "' AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND status='active'");
										while ($bills = mysqli_fetch_array($get_bills)) {
											// $service=new Service();
											// $service->service_id=$bills['service_id'];
											// $service->ServiceInfo();

											// $billing=new Billing();
											$billing->bill_id = $bills['bill_id'];
											$billing->BillInfo();
										?>
											<div class="card mb-1 cursor <?php if ($billing->payment_status != 'PAID') {
																				echo 'payment_btn';
																			} ?>" id="<?php echo $bills['bill_id']; ?>">
												<div class="card-body py-2">
													<div class="row poppins" style="font-size:11px">
														<div class="col-8">
															<?php echo $bills['narration']; ?>
															<?php
															if ($billing->payment_status == 'PAID') {
															?>
																<i class="fas fa-check text-success" aria-hidden></i>
															<?php
															}
															?>
														</div>
														<div class="col-4 text-right font-weight-bold">
															<?php echo $bills['bill_amount']; ?>
														</div>
													</div>
													<div class="">

													</div>
												</div>
											</div>

										<?php
										}
										?>
									</div>
								</div>
							</div>
						</section>
					</div>

					<!-- Payments content-->
					<div class="tab-pane fade" id="payments_content" role="tabpanel" aria-labelledby="payments_tab">
						<section>
							<div class="card mb-3">
								<div class="card-body">
									<h6 class="montserrat mb-5"><i class="fas fa-credit-card mr-2" aria-hidden></i> Payments</h6>

									<table class="table table-condensed">
										<thead>
											<tr>
												<th>#</th>
												<th>ID</th>
												<th class="text-right">Amount Paid</th>
												<th class="text-right"></th>
											</tr>
										</thead>
										<tbody>


											<?php
											// $get_payments=mysqli_query($db,"SELECT * FROM payments WHERE subscriber_id='".$active_subscriber."' AND patient_id='".$patient_id."' AND visit_id='".$visit_id."'  AND status='active'") or die(mysqli_error($db));
											// while ($payments=mysqli_fetch_array($get_payments)) {
											$payment->visit_id = $visit_id;
											$rows = $payment->VisitPayments();
											$i = 1;
											if (is_array($rows)) {
												foreach ($rows as $payments) {

											?>
													<tr>
														<td><?php echo $i++; ?></td>
														<td><?php echo $payments['payment_id']; ?></td>
														<td class="text-right"><?php echo $payments['amount_paid']; ?></td>
														<td class="text-right"><button type="button" class="btn btn-primary btn-sm print_btn px-2" id="<?php echo $bills['bill_id']; ?>"><i class="fas fa-print mr-2"></i> </button></td>
													</tr>

											<?php
												}
											}
											?>
										</tbody>
									</table>

								</div>
							</div>
						</section>
					</div>

				</div>
				<!-- Tab content -->
			</div>
		</div>



		<?php
		// $doc->doctor_id=$visit->doctor_id;
		// $doc->DoctorInfo();
		?>
		<?php //echo $doc->doctor_fullname; 
		?>



	</div>
</main>





<div id="modal_holder">

</div>






<div id="transfer_patient_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-side modal-top-right">
		<div class="modal-content">

			<form id="transfer_patient_frm">
				<div class="modal-body">
					<h6 class="font-weight-bold montserrat">Transfer Patient Folder</h6>
					<hr class="hr">

					<div class="form-group d-none">
						<input type="text" class="form-control" id="visit_patient_id" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
					</div>

					<div class="form-group">
						<label for="">Visit</label>
						<select class="browser-default custom-select" name="visit_id">
							<?php
							$get_visits = mysqli_query($db, "SELECT * FROM visits WHERE patient_id='" . $patient_id . "' AND subscriber_id='" . $active_subscriber . "' AND status='active'") or die(mysqli_error($db));
							while ($visits = mysqli_fetch_array($get_visits)) {
							?>
								<option value="<?php echo $visits['visit_id']; ?>"><?php echo $visits['visit_id']; ?></option>
							<?php
							}
							?>

						</select>
					</div>

					<div class="form-group">
						<label for="">Transfer to</label>
						<select class="browser-default custom-select" name="department_id">
							<?php
							$get_departments = mysqli_query($db, "SELECT * FROM syst_departments") or die(mysqli_error($db));
							while ($departments = mysqli_fetch_array($get_departments)) {
							?>
								<option value="<?php echo $departments['department_id']; ?>"><?php echo $departments['department_name']; ?></option>
							<?php
							}
							?>

						</select>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Transfer Patient</button>
				</div>
			</form>
		</div>
	</div>
</div>



<div id="hidden_menu_modal" class="modal fade slow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-side modal-top-right animated">
		<div class="modal-content">
			<div class="modal-body p-0">
				<ul class="list-group">

					<li class="list-group-item" data-toggle="collapse" data-target="#vital_signs_sub" aria-expanded="true" aria-controls="vital_signs_sub">
						Record Vital Signs
						<i class="fas fa-chevron-down float-right"></i>
					</li>
					<div class="collapse p-3" id="vital_signs_sub">
						<h6 class="montserrat font-weight-bold">Patient Vitals Signs</h6>



						<form id="patient_vitals_frm" autocomplete="off">



							<div class="form-group d-none">
								<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
							</div>

							<div class="form-group d-none">
								<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
							</div>

							<div class="row">
								<div class="col-md-5">
									<div class="form-group">
										<label for="">Systolic B.P</label>
										<input type="text" name="systolic" class="form-control" placeholder="" required value="<?php echo $opd->systolic; ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<div style="margin-top:30px; font-size:20px">
											/
										</div>
									</div>

								</div>
								<div class="col-md-5">
									<div class="form-group">
										<label for="">Diastolic B.P</label>
										<input type="text" name="diastolic" class="form-control" placeholder="" required value="<?php echo $opd->diastolic; ?>">
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for="">Pulse</label>
								<input type="text" name="pulse" class="form-control" placeholder="" value="<?php echo $opd->pulse; ?>">
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="">Temperature</label>
										<input type="text" name="temperature" class="form-control" placeholder="" value="<?php echo $opd->temperature; ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="">Weight</label>
										<input type="text" name="weight" class="form-control" value="<?php echo $opd->weight; ?>">
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for="">Refer to Doctor</label>
								<select class="custom-select browser-default" name="doctor_id">
									<?php
									$get_doctors = mysqli_query($db, "SELECT * FROM doctors WHERE subscriber_id='" . $active_subscriber . "' AND status='active'") or die(mysqli_error($db));
									while ($doctors = mysqli_fetch_array($get_doctors)) {
									?>
										<option value="<?php echo $doctors['doctor_id']; ?>"><?php echo $doctors['title'] . ' ' . $doctors['othernames']; ?></option>
									<?php
									}
									?>

								</select>
							</div>


							<button type="submit" class="btn btn-primary wide b-0">Record Vitals</button>

						</form>
					</div>



					<li class="list-group-item" data-toggle="collapse" data-target="#complains_sub" aria-expanded="false" aria-controls="complains_sub">
						Complains <i class="fas fa-chevron-right float-right"></i></li>
					<div class="collapse p-3" id="complains_sub">



					</div>

					<li class="list-group-item" data-toggle="collapse" data-target="#hpc_sub" aria-expanded="false" aria-controls="hpc_sub">
						History Of Presenting Complains <i class="fas fa-chevron-right float-right"></i></li>
					<div class="collapse p-3" id="hpc_sub">



					</div>


				</ul>
			</div>
		</div>
	</div>
</div>

<div id="new_ultrasound_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="radiology_request_frm" autocomplete="">
				<div class="modal-body px-4 pb-5">


					<h5 class="poppins" style="font-weight:500">New Radiology Request</h5>
					<hr class="hr">

					<div class="form-group d-none">
						<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
					</div>

					<div class="form-group d-none">
						<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
					</div>



					<div class="form-group">
						<label for="">Address / Ward</label>
						<input type="text" class="form-control" name="address" id="address" value="OPD - Out-Patient Department">
					</div>

					<div class="form-group">
						<label for="">Clinical History</label>
						<textarea name="clinical_history" class="form-control"></textarea>
					</div>


					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<label for="">Investigation Requested</label>
								<select class="custom-select browser-default" name="service_id" id="ultrasound_service_id">
									<option value="">--------</option>
									<?php
									$rows = $service->servicesFilter('radiology');
									if (is_array($rows)) {
										foreach ($rows as $row) {
									?>
											<option value="<?php echo $row['service_id']; ?>" data-service_cost="<?php echo $row['service_cost']; ?>"><?php echo $row['description']; ?></option>
									<?php
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="">Service Cost.</label>
								<input type="text" class="form-control" name="service_cost" id="ultrasound_service_cost" value="" readonly>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<label for="">Medical Officer / Dr.</label>
								<input type="text" class="form-control" name="doctor" id="doctor" value="">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="">Station Address</label>
								<input type="text" class="form-control" name="station_address" id="station_address" value="">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<label for="">X-Ray Serial Number</label>
								<input type="text" class="form-control" name="serial_number" id="serial_number" value="">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="">Station Address</label>
								<input type="text" class="form-control" name="previous_serial_number" id="previous_serial_number" value="">
							</div>
						</div>
					</div>

					<div class="d-block float-right">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary mr-0"><i class="fas fa-check mr-3" aria-hidden></i> Submit Request</button>
					</div>

					<div class="mb-4">

					</div>

				</div>
			</form>
		</div>
	</div>
</div>


<div id="new_lab_request_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="request_labs_frm">
				<div class="modal-body">
					<h6>Request Labs</h6>
					<hr class="hr">
					<div class="spacer"> </div>
					<div class="form-group d-none">
						<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
					</div>

					<div class="form-group d-none">
						<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
					</div>

					<ul class="list-group">
						<?php
						$get_tests = mysqli_query($db, "SELECT * FROM lab_tests WHERE subscriber_id='" . $active_subscriber . "' AND status='active'") or die(mysqli_error($db));
						while ($tests = mysqli_fetch_array($get_tests)) {
						?>

							<li class="list-group-item">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="test_id[]" value="<?php echo $tests['test_id']; ?>" id="<?php echo $tests['test_id']; ?>" />
									<label class="form-check-label" for="<?php echo $tests['test_id']; ?>">
										<?php echo $tests['description']; ?>
									</label>
								</div>
							</li>
						<?php
						}
						?>
					</ul>
					<div class="spacer">

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2" aria-hidden></i>Submit Request</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="prescription_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="prescription_frm">
				<div class="modal-body">

					<h6 class="montserrat font-weight-bold">Prescription Pad</h6>
					<hr class="hr">

					<div class="spacer"></div>

					<div class="row d-none">
						<div class="col-md-6">
							<input type="text" class="form-control" name="patient_id" value="<?php echo $patient_id; ?>">
						</div>
						<div class="col-md-6">
							<input type="text" class="form-control" name="visit_id" value="<?php echo $visit_id; ?>">
						</div>
					</div>

					<div class="form-group">
						<label for="">Medication</label>
						<select class="custom-select browser-default" name="drug_id" id="prescription_drug_id">
							<?php
							$get_drugs = mysqli_query($db, "SELECT *
																																	FROM
																																		pharm_inventory
																																	WHERE
																																		status='active' && subscriber_id='" . $active_subscriber . "'
																																	ORDER BY generic_name asc
																												")  or die(mysqli_error($db));
							while ($drugs = mysqli_fetch_array($get_drugs)) {
							?>
								<option value="<?php echo $drugs['drug_id']; ?>"><?php echo $drugs['generic_name'] . ' ' . $drugs['trade_name']; ?></option>
							<?php
							}
							?>
						</select>
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Dosage</label>
								<input type="text" name="dosage" id="dosage" class="form-control" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Duration</label>
								<input type="text" name="duration" id="duration" class="form-control" value="">
							</div>
						</div>
						<div class="col-md-3">
							<label for="">Route</label>
							<select name="route" id="route" class="custom-select browser-default">
								<option value="oral">Oral</option>
								<option value="im">Intra-Muscular (IM)</option>
								<option value="iv">Intra-Venous (IV)</option>
								<option value="supp">Suppository (supp)</option>
								<option value="OD">OD</option>
								<option value="OS">OS</option>
								<option value="OU">OU</option>
							</select>
						</div>
						<div class="col-md-3">
							<label for="">Freq</label>
							<select name="frequency" id="frequency" class="custom-select browser-default">
								<option value="QD">QD</option>
								<option value="BID">BID</option>
								<option value="TID">TID</option>
								<option value="QID">QID</option>
								<option value="QHS">QHS</option>
								<option value="Q4H">Q4H</option>
								<option value="Q6H">Q6H</option>
								<option value="QOH">QOH</option>
								<option value="PRN">PRN</option>
								<option value="QTT">QTT</option>
								<option value="AC">AC</option>
								<option value="PC">PC</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="">Doctors Notes</label>
						<textarea name="doctors_notes" class="form-control"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-signature mr-3" aria-hidden></i> Prescribe</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="new_surgery_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form id="new_surgery_frm">
					<h6 class="font-weight-bold montserrat">Book New Surgery</h6>
					<hr class="hr">

					<div class="row d-none">

						<div class="col-md-4">
							<div class="form-group">
								<label for="">Patient ID</label>
								<input type="text" class="form-control" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="">Visit ID</label>
								<input type="text" class="form-control" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-4">
							<label for="">Proceure</label>
							<select class="form-control" name="surgical_procedure" id="procedure">

							</select>
						</div>
						<div class="col-md-4">
							<label for="">Type Of Procedure</label>
							<select class="custom-select browser-default" name="procedure_type">
								<option value="Major">Major Procedure</option>
								<option value="Minor">Minor Procedure</option>
							</select>
						</div>
						<div class="col-md-4">
							<label for="">Date Scheduled</label>
							<input type="text" name="date" id="surgery_date" class="form-control" value="<?php echo $today; ?>">
						</div>
					</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Schedule Surgery</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div id="admission_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="admission_frm">
				<div class="modal-body">
					<h6 class="montserrat font-weight-bold">New Admission</h6>
					<hr class="hr">
					<div class="row d-none">

						<div class="col-md-4">
							<div class="form-group">
								<label for="">Patient ID</label>
								<input type="text" class="form-control" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="">Visit ID</label>
								<input type="text" class="form-control" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
							</div>
						</div>

					</div>

					<div class="form-group">
						<label for="">Ward</label>
						<select class="browser-default custom-select" name="ward_id">
							<?php
							$get_wards = mysqli_query($db, "SELECT * FROM wards WHERE subscriber_id='" . $active_subscriber . "' AND status='active'") or die(mysqli_error($db));
							while ($wards = mysqli_fetch_array($get_wards)) {
								$ward_id = $wards['ward_id'];
								$beds = $ward->CheckBeds($ward_id);
							?>
								<option value="<?php echo $wards['ward_id']; ?>"><?php echo $wards['description']; ?> ( <?php echo $beds; ?> Beds Available )</option>
							<?php
							}
							?>
						</select>
					</div>

					<div class="form-group">
						<label for="">Admission Notes</label>
						<textarea name="admission_notes" class="form-control"></textarea>
					</div>


				</div>


				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-bed mr-3" aria-hidden></i> Admit Patient</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div id="billing_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">

				<h6 class="montserrat font-weight-bold">Bill Patient</h6>
				<hr class="hr">

				<form id="bill_patient_frm">

					<div class="form-group d-none">
						<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
					</div>

					<div class="form-group d-none">
						<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
					</div>


					<div class="form-group">
						<label for="">Service Name</label>
						<select class="custom-select browser-default" name="service_id" id="billing_service_id" required>
							<option value="">Select Service</option>
							<?php
							$get_billing_points = mysqli_query($db, "SELECT * FROM billing_points") or die(mysqli_error($db));
							while ($billing_points = mysqli_fetch_array($get_billing_points)) {
							?>
								<optgroup label="<?php echo $billing_points['point_name']; ?>">
									<?php
									$get_services = mysqli_query($db, "SELECT * FROM services WHERE billing_point='" . $billing_points['billing_point'] . "' AND subscriber_id='" . $active_subscriber . "' AND status='active'") or die(mysqli_error($db));
									while ($rows = mysqli_fetch_array($get_services)) {
									?>
										<option class="poppins" value="<?php echo $rows['service_id'] ?>" data-service_cost="<?php echo $rows['service_cost']; ?>"><?php echo $rows['description']; ?></option>
									<?php
									}
									?>
								</optgroup>
							<?php
							}
							?>


						</select>
					</div>

					<div class="form-group">
						<label for="">Service Cost</label>
						<input type="text" class="form-control" name="billing_service_cost" value="" id="billing_service_cost" required>
					</div>

					<div class="form-group">
						<label for="">Narration</label>
						<input type="text" class="form-control" name="narration" value="" required>
					</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Add Bill To Patient</button>
			</div>
			</form>
		</div>
	</div>
</div>



<div id="deceased_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="deceased_frm" autocomplete="off">
				<div class="modal-body">
					<h5 class="montserrat">Deceased Patient</h5>
					<hr class="hr">

					<div class="row d-none">

						<div class="col-md-4">
							<div class="form-group">
								<label for="">Patient ID</label>
								<input type="text" class="form-control" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="">Visit ID</label>
								<input type="text" class="form-control" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
							</div>
						</div>

					</div>


					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<label for="">Date</label>
								<input type="text" class="form-control" name="death_date" id="death_date" value="<?php echo $today; ?>" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="">Time Of Death</label>
								<input type="time" class="form-control" name="time_of_death" id="time_of_death" value="" required>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="">Doctor's Full Name</label>
						<input type="text" class="form-control" name="doctor" id="doctor" value="" required>
					</div>


					<div class="form-group">
						<label for="">Death Notes</label>
						<textarea name="death_notes" id="death_notes" class="form-control" required></textarea>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-check" aria-hidden></i> Mark Patient As Deceased</button>
				</div>
			</form>
		</div>
	</div>
</div>
</body>

<!--   Core JS Files   -->
<?php require_once '../navigation/footer.php'; ?>

<script type="text/javascript">
	$('.sidebar-fixed .list-group-item').removeClass('active')
	$('#patients_nav').addClass('active')
	$('#patients_submenu').addClass('show')
	$('#patients_li').addClass('font-weight-bold')

	$('#death_date').datepicker()
	$('#death_date').on('changeDate', function(event) {
		event.preventDefault();
		$(this).datepicker('hide')
	});


	// Radiology Request JS==========================================
	$('#ultrasound_service_id').on('change', function(event) {
		event.preventDefault();
		let service_cost = $(this).find(":selected").data('service_cost');
		$('#ultrasound_service_cost').val(service_cost)
	});

	// $('#radiology_request_frm').on('submit', function(event) {
	//   event.preventDefault();
	//   $.ajax({
	//     url: '../serverscripts/admin/OPD/Radiology/new_request.php?patient_type=opd',
	//     type: 'GET',
	//     data:$('#radiology_request_frm').serialize(),
	//     success:function(msg){
	//       if(msg==='save_successful'){
	//         bootbox.alert("Request successful",function(){
	//           window.location.reload()
	//         })
	//       }else {
	//         bootbox.alert(msg)
	//       }
	//     }
	//   })
	//
	// });


	// ===========================================================================


	$('#billing_service_id').select2({
		dropdownParent: $('#billing_modal')
	});

	$('#billing_service_id').on('change', function(event) {
		event.preventDefault();
		var billing_service_cost = $(this).find(':selected').data('service_cost')
		$('#billing_service_cost').val(billing_service_cost)
	});

	$('#bill_patient_frm').on('submit', function(event) {
		event.preventDefault();
		bootbox.confirm("Add bill to patients cost?", function(r) {
			if (r === true) {
				$.ajax({
					url: '../serverscripts/admin/OPD/bill_patient_frm.php',
					type: 'GET',
					data: $('#bill_patient_frm').serialize(),
					success: function(msg) {
						if (msg === 'billing_successful') {
							bootbox.alert("Bill added to patient", function() {
								window.location.reload()
							})
						} else {
							bootbox.alert(msg)
						}
					}
				})
			}
		})
	});


	$('#discharge').on('click', function(event) {
		event.preventDefault();
		var patient_id = '<?php echo $patient_id; ?>'
		var visit_id = '<?php echo $visit_id; ?>'
		bootbox.confirm('Discharge patient? This action is not reversible', function(r) {
			if (r === true) {
				$.get('../serverscripts/admin/OPD/discharge_patient.php?visit_id=' + visit_id + '&patient_id=' + patient_id, function(msg) {
					if (msg === 'discharge_successful') {
						bootbox.alert('Patient Discharged Successfully', function() {
							window.location = 'opd.php';
						})
					} else {
						bootbox.alert(msg)
					}
				})
			}
		})
	});


	$('#deceased_frm').on('submit', function(event) {
		event.preventDefault();
		var patient_id = '<?php echo $patient_id; ?>'
		var visit_id = '<?php echo $visit_id; ?>'
		bootbox.confirm('Confirm Patient As Deceased? This action is not reversible', function(r) {
			if (r === true) {
				$.ajax({
					url: '../serverscripts/admin/OPD/patient_deceased.php',
					type: 'GET',
					data: $('#deceased_frm').serialize(),
					success: function(msg) {
						if (msg === 'save_successful') {
							bootbox.alert('Patient Status Updated', function() {
								window.location.reload();
							})
						} else {
							bootbox.alert(msg)
						}
					}
				})
			}
		})
	});

	tinymce.init({
		selector: '#hpc,#doctors_notes,#clinical_examination,#secondary_diagnosis,#death_notes',
		force_br_newlines: true,
		force_p_newlines: false,
		forced_root_block: '', // Needed for 3.x
		setup: function(editor) {
			editor.on('change', function() {
				editor.save();
			});
		}
	});

	$('.remove_hpc').on('click', function(event) {
		event.preventDefault();
		var hpc_id = $(this).attr('ID')
		$.get('../serverscripts/admin/OPD/remove_hpc.php?hpc_id=' + hpc_id, function(msg) {
			if (msg === 'delete_successful') {
				bootbox.alert('HPC removed successfully', function() {
					window.location.reload();
				})
			} else {
				bootbox.alert(msg)
			}
		})
	});

	$('.remove_complain').on('click', function(event) {
		event.preventDefault();
		var complain_id = $(this).attr('ID')
		$.get('../serverscripts/admin/OPD/remove_complain.php?complain_id=' + complain_id, function(msg) {
			if (msg === 'delete_successful') {
				bootbox.alert('Complain removed successfully', function() {
					window.location.reload();
				})
			} else {
				bootbox.alert(msg)
			}
		})
	});

	$('.remove_odq').on('click', function(event) {
		event.preventDefault();
		var sn = $(this).attr('ID')
		$.get('../serverscripts/admin/OPD/remove_odq.php?sn=' + sn, function(msg) {
			if (msg === 'delete_successful') {
				bootbox.alert('ODQ removed successfully', function() {
					LoadODQ()
				})
			} else {
				bootbox.alert(msg)
			}
		})
	});

	$('#admission_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/OPD/admit_patient.php',
			type: 'GET',
			data: $('#admission_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					bootbox.alert('Admission Request Successful', function() {
						window.location.reload()
					})
				} else {
					bootbox.alert(msg)
				}
			}
		})
	});

	$('.remove_diagnosis').on('click', function(event) {
		event.preventDefault();
		var removethis = $(this).attr('ID')
		$.get('../serverscripts/admin/OPD/remove_diagnosis.php?removethis=' + removethis, function(msg) {
			if (msg === 'delete_successful') {
				bootbox.alert('Diagnosis removed', function() {
					window.location.reload()
				})
			} else {
				bootbox.alert(msg)
			}
		})
	});

	$('#diagnosis_search_term').on('keyup', function(event) {
		event.preventDefault();
		var search_term = $(this).val()
		var patient_id = "<?php echo $patient_id; ?>"
		var visit_id = "<?php echo $visit_id; ?>"
		$.get('../serverscripts/admin/OPD/filter_diagnosis.php?search_term=' + search_term + '&patient_id=' + patient_id + '&visit_id=' + visit_id, function(msg) {
			$('#diagnosis_holder').html(msg)

			$('.diagnose_btn').on('click', function(event) {
				event.preventDefault();
				var diagnosis_id = $(this).data('diagnosis_id')
				var patient_id = $(this).data('patient_id')
				var visit_id = $(this).data('visit_id')
				$.get('../../serverscripts/admin/OPD/record_diagnosis.php?diagnosis_id=' + diagnosis_id + '&visit_id=' + visit_id + '&patient_id=' + patient_id, function(msg) {
					if (msg == 'save_successful') {
						bootbox.alert('Diagnosis Made', function() {
							window.location.reload()
						})
					} else {
						bootbox.alert(msg)
					}
				})
			});
		})
	});


	$('#secondary_diagnosis_frm').on('submit', function(event) {
		event.preventDefault();
		var patient_id = "<?php echo $patient_id; ?>"
		var visit_id = "<?php echo $visit_id; ?>"
		$.ajax({
			url: '../serverscripts/admin/OPD/record_secondary_diagnosis.php?patient_id=' + patient_id + '&visit_id=' + visit_id,
			type: 'GET',
			data: $('#secondary_diagnosis_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					bootbox.alert("Secondary Diagnosis Recorded", function() {
						window.location.reload()
					})
				} else {
					bootbox.alert(msg)
				}
			}
		})

	});


	$('#doctors_notes_frm').on('submit', function(event) {
		event.preventDefault();
		var patient_id = "<?php echo $patient_id; ?>"
		var visit_id = "<?php echo $visit_id; ?>"
		$.ajax({
			url: '../serverscripts/admin/OPD/record_doctors_notes.php?patient_id=' + patient_id + '&visit_id=' + visit_id,
			type: 'GET',
			data: $('#doctors_notes_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					bootbox.alert("Notes Recorded", function() {
						window.location.reload()
					})
				} else {
					bootbox.alert(msg)
				}
			}
		})

	});


	$('#patient_vitals_frm').on('submit', function(event) {
		event.preventDefault();
		bootbox.confirm('Record patients vitals?', function(r) {
			if (r === true) {
				$.ajax({
					url: '../serverscripts/admin/OPD/record_vitals.php',
					type: 'GET',
					data: $('#patient_vitals_frm').serialize(),
					success: function(msg) {
						if (msg === 'save_successful') {
							bootbox.alert('Patient Vitals Recorded successfully', function() {
								window.location.reload()
							})
						} else {
							bootbox.alert(msg)
						}
					}
				})
			}
		})
	});


	$('#record_complain_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/OPD/record_complain.php',
			type: 'GET',
			data: $('#record_complain_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					LoadComplains()
				} else {
					bootbox.alert(msg)
				}
			}
		})
	});

	$('#record_odq_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/OPD/record_odq.php',
			type: 'GET',
			data: $('#record_odq_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					LoadODQ()
				} else {
					bootbox.alert(msg)
				}
			}
		})
	});

	$('#hpc_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/OPD/record_hpc.php',
			type: 'GET',
			data: $('#hpc_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					window.location.reload()
					$('#hpc_tab').show()
				} else {
					bootbox.alert(msg)
				}
			}
		})
	});

	$('#clinical_examination_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/OPD/record_clinical_examination.php',
			type: 'GET',
			data: $('#clinical_examination_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					window.location.reload()
					$('#hpc_tab').show()
				} else {
					bootbox.alert(msg)
				}
			}
		})
	});

	$('#record_diagnosis_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/OPD/record_diagnosis.php',
			type: 'GET',
			data: $('#record_diagnosis_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					LoadDiagnosis()
				} else {
					bootbox.alert(msg)
				}
			}
		})
	});


	$('#request_labs_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/OPD/request_labs_frm.php',
			type: 'GET',
			data: $('#request_labs_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					bootbox.alert("Labs requests queued at laboratory", function() {
						window.location.reload();
					})
				} else {
					bootbox.alert(msg)
				}
			}
		})
	});

	$('.view_test_result').on('click', function(event) {
		event.preventDefault();
		var test_id = $(this).data('test_id')
		var request_id = $(this).data('request_id')
		$.get('../serverscripts/admin/OPD/view_test_result_modal.php?request_id=' + request_id + '&test_id=' + test_id, function(msg) {
			$('#modal_holder').html(msg)
			$('#view_test_result_modal').modal('show')
		})
	});

	$('#prescription_modal').on('shown.bs.modal', function(event) {
		event.preventDefault();
		$('#ex1-tab-3').tab('show')
		// $('#prescription_drug_id').select2();
		$('#prescription_drug_id').select2({
			dropdownParent: $('#prescription_modal')
		});
	});

	$('#prescription_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/OPD/prescription_frm.php',
			type: 'GET',
			data: $('#prescription_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					bootbox.alert('Prescription updated successfully', function() {
						LoadPrescription();
					})
				} else {
					bootbox.alert(msg)
				}
			}
		})
	});


	$('#new_surgery_modal').on('shown.bs.modal', function(event) {
		event.preventDefault();
		$('#surgery_date').datepicker();
		// $('#procedure').select2()

		$('#procedure').select2({
			placeholder: 'Select a procedure',
			dropdownParent: $('#new_surgery_modal'),
			ajax: {
				url: '../serverscripts/admin/Surgery/filter_procedures.php',
				dataType: 'json',
				delay: 100,
				data: function(data) {
					return {
						searchTerm: data.term // search term
					};
				},
				processResults: function(response) {
					return {
						results: response
					};
				},
				cache: true
			}
		});
	});

	$('#new_surgery_frm').on('submit', function(event) {
		event.preventDefault();
		bootbox.confirm("Book this patient for surgery?", function(r) {
			if (r === true) {
				$.ajax({
					url: '../../serverscripts/admin/OPD/new_surgery_frm.php',
					type: 'GET',
					data: $('#new_surgery_frm').serialize(),
					success: function(msg) {
						if (msg === 'save_successful') {
							bootbox.alert('Surgery Scheduled Successfully', function() {
								window.location.reload()
							})
						} else {
							bootbox.alert(msg)
						}
					}
				}) //end ajax
			}
		})
	});


	$('#transfer_patient_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/Patients/transfer_patient_frm.php',
			type: 'GET',
			data: $('#transfer_patient_frm').serialize(),
			success: function(msg) {
				if (msg === 'save_successful') {
					bootbox.alert('Patient transfer successful', function() {
						window.location.reload()
					})
				} else {
					bootbox.alert(msg)
				}
			}
		})
	});


	function LoadComplains() {
		var patient_id = '<?php echo $patient_id; ?>'
		var visit_id = '<?php echo $visit_id; ?>'
		$.get('../serverscripts/admin/OPD/load_complains.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function(msg) {
			$('#complains_holder').html(msg)
			$('.remove_complain').on('click', function(event) {
				event.preventDefault();
				var complain_id = $(this).attr('ID')
				$.get('../serverscripts/admin/OPD/remove_complain.php?complain_id=' + complain_id, function(msg) {
					if (msg === 'delete_successful') {
						bootbox.alert('Complain removed successfully', function() {
							window.location.reload();
						})
					} else {
						bootbox.alert(msg)
					}
				})
			});
		})
	}

	function LoadODQ() {
		var patient_id = '<?php echo $patient_id; ?>'
		var visit_id = '<?php echo $visit_id; ?>'
		$.get('../serverscripts/admin/OPD/load_odq.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function(msg) {
			$('#odq_holder').html(msg)
		})
	}

	function LoadDiagnosis() {
		var patient_id = '<?php echo $patient_id; ?>'
		var visit_id = '<?php echo $visit_id; ?>'
		$.get('../serverscripts/admin/OPD/load_diagnosis.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function(msg) {
			$('#diagnosis_holder').html(msg)
		})
	}

	function LoadPrescription() {
		var patient_id = '<?php echo $patient_id; ?>'
		var visit_id = '<?php echo $visit_id; ?>'
		$.get('../serverscripts/admin/OPD/load_prescriptions.php?patient_id=' + patient_id + '&visit_id=' + visit_id, function(msg) {
			$('#prescription_holder').html(msg)
		})
	}







	$('#new_patient_modal').on('shown.bs.modal', function(event) {
		event.preventDefault();
		if ($(this).val() == 'cash') {
			$('#nhis_number').val('N/A')
			$('#nhis_number').attr('readonly', 'readonly')
		} else {
			$('#nhis_number').val('')
			$('#nhis_number').attr('readonly', false)
		}
	});

	$('#date_of_birth').datepicker()
	$('#date_of_birth').on('changeDate', function(event) {
		event.preventDefault();
		$(this).datepicker('hide')
	});

	$('#payment_mode').on('change', function(event) {
		event.preventDefault();
		if ($(this).val() == 'cash') {
			$('#nhis_number').val('N/A')
			$('#nhis_number').attr('readonly', 'readonly')
		} else {
			$('#nhis_number').val('')
			$('#nhis_number').attr('readonly', false)
		}
	});




	$('.payment_btn').on('click', function(event) {
		event.preventDefault();
		var bill_id = $(this).attr('ID')
		var patient_id = '<?php echo $patient_id; ?>'
		var visit_id = '<?php echo $visit_id; ?>'
		bootbox.confirm('Accept payment?', function(r) {
			if (r === true) {
				$.get('../../serverscripts/admin/Patients/payment_modal.php?patient_id=' + patient_id + '&visit_id=' + visit_id + '&bill_id=' + bill_id, function(msg) {
					$('#modal_holder').html(msg)
					$('#new_payment_modal').modal('show')

					$('#amount_paid').on('keyup', function(event) {
						event.preventDefault();
						var amount_payable = $('#amount_payable').val()
						var amount_paid = $('#amount_paid').val()
						$('#balance').val((parseFloat(amount_payable) - parseFloat(amount_paid)).toFixed(2))
					});
					// End keyup

					$('#payment_frm').on('submit', function(event) {
						event.preventDefault();
						$.ajax({
							url: '../serverscripts/admin/Patients/payment_frm.php',
							type: 'GET',
							data: $('#payment_frm').serialize(),
							success: function(msg) {
								if (msg === 'save_successful') {
									bootbox.alert('Payment successful', function() {
										window.location.reload()
									})
								} else {
									bootbox.alert(msg)
								}
							}
						})
					}); //end submit
				})
			}
		})
	}); //end click

	// $(document).on('keyup',function(e){
	// 	if(e.keyCode=='78'){
	// 		$('#new_drug_modal').modal('show')
	// 	}
	// })


	$(document).ready(function() {

		$('#print').on('click', function(event) {
			event.preventDefault();
			print_popup('print_inventory.php');
		});


		$('#new_drug_modal').on('shown.bs.modal', function(event) {
			event.preventDefault();
			$('#drug_name').focus()
		}); //end modal shown

		$('#new_patient_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm("Create new folder?", function(r) {
				if (r === true) {
					$.ajax({
						url: '../serverscripts/admin/Patients/new_patient_frm.php',
						type: 'GET',
						data: $('#new_patient_frm').serialize(),
						success: function(msg) {
							if (msg === 'save_successful') {
								bootbox.alert("Folder Created Successfully", function() {
									window.location = 'patient_folder.php'
								})
							} else {
								bootbox.alert(msg, function() {
									window.location.reload()
								})
							}
						}
					})
				}
			})

		}); //end submit


		$('.table tbody').on('click', '.edit', function(event) {
			event.preventDefault();
			var drug_id = $(this).attr('ID')

			$.ajax({
				url: '../serverscripts/admin/inventory_edit.php?drug_id=' + drug_id,
				type: 'GET',
				success: function(msg) {
					$('#modal_holder').html(msg)

					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove()

					$('#edit_drug_modal').modal('show')

					$('#edit_drug_frm').on('submit', function(event) {
						event.preventDefault();

						$.ajax({
							url: '../serverscripts/admin/edit_item_frm.php',
							type: 'GET',
							data: $(this).serialize(),
							success: function(msg) {
								if (msg === 'save_successful') {
									bootbox.alert('Drug Information Updated Successfully', function() {
										window.location.reload()
									})
								} else {
									bootbox.alert(msg)
								}
							}
						}) //end ajax
					}); //end edit form
				}
			})

		}); //end edit function


		$('.table tbody').on('click', '.delete', function(event) {
			event.preventDefault();

			var drug_id = $(this).attr('ID')
			bootbox.confirm("Are you sure you want to delete this item?", function(r) {
				if (r === true) {
					$.ajax({
						url: '../serverscripts/admin/inventory_delete.php?drug_id=' + drug_id,
						type: 'GET',
						success: function(msg) {
							if (msg === 'delete_successful') {
								bootbox.alert("Drug deleted successfully", function() {
									window.location.reload()
								})
							} else {
								bootbox.alert(msg)
							}
						}
					})
				}
			})
		}); //end delete



	});
</script>

</html>