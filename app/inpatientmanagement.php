<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
// ob_clean();

if ($_GET['admission_id'] == "") {
	echo "<script>window.location='inpatients.php'</script>";
} else {
	$admission_id = clean_string($_GET['admission_id']);
}

$get_admission_info = mysqli_query($db, "SELECT * FROM admissions WHERE admission_id='" . $admission_id . "' AND subscriber_id='" . $active_subscriber . "'") or die(mysqli_error($db));
$admission_info = mysqli_fetch_array($get_admission_info);

$visit_id = $admission_info['visit_id'];
// $patient_id=$admission_info['patient_id'];

$visit = new Visit();
$visit->VisitInfo($visit_id);


$patient_id = $visit->patient_id;

$p = new Patient();
$p->patient_id = $patient_id;
$p->PatientInfo();

$visit->VisitBilling($patient_id, $visit_id);
$visit->VisitPayment($patient_id, $visit_id);
$visit->VisitBalance($patient_id, $visit_id);



$get_vitals = mysqli_query($db, "SELECT * FROM vitals WHERE visit_id='" . $visit_id . "' AND subscriber_id='" . $active_subscriber . "' AND status='active'") or die(mysqli_error($db));
$vitals = mysqli_fetch_array($get_vitals);

$drug = new Drug();
$surg = new Surgery();
$opd = new Visit();
$ward = new Ward();
$billing = new Billing();
?>


<a href="#" class="float" data-toggle="modal" data-target="#hidden_menu_modal">
	<i class="fas fa-heartbeat my-float"></i>
</a>


<div class="row mb-4">
	<div class="col-6">
		<h4 class="titles">In-Patient Management</h4>
	</div>
	<div class="col-6 text-right">


		<div class="dropdown open <?php if ($admission_info['admission_status'] != 'admitted') {
										echo 'd-none';
									} ?>">
			<button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Actions
			</button>
			<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
				<ul class="list-group">
					<li class="list-group-item" data-toggle="modal" data-target="#vitals_modal"><i class="fas fa-plus mr-2" aria-hidden></i> Record Vitals</li>
					<li class="list-group-item" data-toggle="modal" data-target="#reviews_modal"><i class="far fa-file-alt mr-2" aria-hidden></i> Doctor Review</li>
					<li class="list-group-item" data-toggle="modal" data-target="#new_lab_request_modal"><i class="fas fa-signature mr-2" aria-hidden></i> Request Labs</li>
					<li class="list-group-item" data-toggle="modal" data-target="#prescription_modal"><i class="fas fa-signature mr-2" aria-hidden></i> Prescribe Meds</li>
					<li class="list-group-item" data-toggle="modal" data-target="#nurses_notes_modal"><i class="far fa-file-alt mr-2" aria-hidden></i> Nurses Notes</li>
					<li class="list-group-item" data-toggle="modal" data-target="#serve_meds_modal"><i class="fas fa-first-aid mr-2" aria-hidden></i> Serve Medication</li>
					<li class="list-group-item" data-toggle="modal" data-target="#billing_modal"><i class="fas fa-cart-arrow-down mr-2" aria-hidden></i> Add Bill</li>
					<li class="list-group-item"><i class="fas fa-arrow-alt-circle-left mr-2" aria-hidden></i> Discharge Patient</li>
					<li class="list-group-item"><i class="fas fa-times-circle mr-2" aria-hidden></i> Expired Case</li>
				</ul>
			</div>
		</div>
	</div>
</div>


<?php

if ($admission_info['admission_status'] == 'discharged') {
?>
	<div class="card info-color-dark white-text mb-5">
		<div class="card-body">
			<p class="montserrat font-weight-bold"><i class="fas fa-exclamation mr-2" aria-hidden></i> Patient Has Been Discharged. You are in Read-Only Mode.</p>
		</div>
	</div>
<?php
} elseif ($admission_info['admission_status'] == 'deceased') {
?>
	<div class="card danger-color-dark white-text mb-5">
		<div class="card-body">
			<p class="montserrat font-weight-bold"><i class="fas fa-exclamation mr-2" aria-hidden></i> Sorry, patient is deceased. You are in Read-Only Mode.</p>
		</div>
	</div>
<?php
}

?>


<div class="card mb-5">
	<div class="card-body p-0 poppins">
		<!-- Pills navs -->
		<ul class="nav nav-pills xmedici_pills poppins" id="ex1" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" id="ex1-tab-1" data-toggle="pill" href="#ex1-pills-1" role="tab" aria-controls="ex1-pills-1" aria-selected="true">Dashboard</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" id="ex1-tab-2" data-toggle="pill" href="#ex1-pills-2" role="tab" aria-controls="ex1-pills-2" aria-selected="false">In-Patient Management</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" id="opd_summary_tab" data-toggle="pill" href="#opd_summary_content" role="tab" aria-controls="opd_summary_content" aria-selected="false">OPD Summary</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" id="reviews_tab" data-toggle="pill" href="#reviews_content" role="tab" aria-controls="reviews_content" aria-selected="false">Reviews</a>
			<li class="nav-item" role="presentation">
				<a class="nav-link" id="nurses_notes_tab" data-toggle="pill" href="#nurses_notes_content" role="tab" aria-controls="nurses_notes_content" aria-selected="false">Nurse's Notes</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" id="medications_tab" data-toggle="pill" href="#medications_content" role="tab" aria-controls="medications_content" aria-selected="false">Medications</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" id="labs_tab" data-toggle="pill" href="#labs_content" role="tab" aria-controls="labs_content" aria-selected="false">Lab Requests</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" id="surgery_tab" data-toggle="pill" href="#surgery_content" role="tab" aria-controls="surgery_content" aria-selected="false">Surgery</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" id="files_tab" data-toggle="pill" href="#files_content" role="tab" aria-controls="files_content" aria-selected="false">Uploaded Docs</a>
			</li>
		</ul>
		<!-- Pills navs -->
	</div>
</div>


<!-- Pills content -->
<div class="tab-content" id="ex1-content">
	<div class="tab-pane fade show active" id="ex1-pills-1" role="tabpanel" aria-labelledby="ex1-tab-1">

		<div class="row">
			<div class="col-md-8">
				<section>

					<div class="card mb-3">
						<div class="card-body">

							<div class="row">
								<div class="col-md-4">



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


						</div>
					</div>
					<!-- end card -->

				</section>

				<div class="card mb-5">
					<div class="card-body">
						<h6 class="montserrat font-weight-bold">Temperature Chart</h6>

						<canvas id="temperature_chart"></canvas>
					</div>
				</div>

				<div class="card">
					<div class="card-body">
						<h6 class="montserrat font-weight-bold">Prescription History</h6>
						<hr class="hr">
						<?php
						$get_dates = "SELECT date as dates FROM prescriptions WHERE patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND subscriber_id='" . $active_subscriber . "' GROUP BY date ORDER BY date desc ";
						$r = $mysqli->query($get_dates);
						$dates = $r->fetch_assoc();
						if (is_array($dates)) {

							foreach ($dates as $date) {

						?>
								<h5><?php echo $date; ?></h5>
								<table class="table">
									<thead class="grey lighten-4">
										<tr>
											<th>Medication</th>
											<th>Dosage</th>
											<th>Duration</th>
											<th>Frequency</th>
											<th>Status</th>
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
												<td>
													<?php
													if ($meds['treatment_status'] == 'ongoing') {
													?>
														<div class="dropdown open">
															<button class="btn btn-primary btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																Options
															</button>
															<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
																<ul class="list-group">
																	<li class="list-group-item mark_complete"><i class="fas fa-check mr-2" aria-hidden></i> Treatment complete</li>
																	<li class="list-group-item pause_treatment"><i class="fas fa-pause mr-2" aria-hidden></i> Pause Treatment</li>
																</ul>
															</div>
														</div>
													<?php
													}
													?>
												</td>
											</tr>
									<?php
										}
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
			<div class="col-md-4">

				<div class="card primary-color-dark white-text mb-3">
					<div class="card-body">
						Outstanding Bills
						<p class="big-text">GHS <?php echo number_format($visit->VisitBalance($patient_id, $visit_id), 2); ?></p>
					</div>
				</div>

				<div class="card mb-3">
					<div class="card-body">
						<h6 class="montserrat font-weight-bold">Recent Vitals</h6>
						<hr class="hr">
						<ul class="list-group poppins">
							<?php
							$get_vitals = "SELECT * FROM admissions_vitals WHERE admission_id='" . $admission_id . "' AND subscriber_id='" . $active_subscriber . "' AND status='active' 	ORDER BY sn DESC ";
							$r = $mysqli->query($get_vitals);

							if ($r->num_rows > 0) {
								$vitals = $r->fetch_assoc();
							?>
								<li class="list-group-item">Blood Pressure <span class="float-right"><?php echo $vitals['systolic']; ?> / <?php echo $vitals['diastolic']; ?></span> </li>
								<li class="list-group-item">Temperature <span class="float-right"><?php echo $vitals['temperature']; ?> </span> </li>
								<li class="list-group-item">Pulse <span class="float-right"><?php echo $vitals['pulse']; ?> </span> </li>
								<li class="list-group-item">Weight <span class="float-right"><?php echo $vitals['weight']; ?> </span> </li>
							<?php
							}
							?>

						</ul>

					</div>
				</div>
				<!-- End Card -->

				<div class="card mb-5">
					<div class="card-body">
						<h6 class="font-weight-bold montserrat">Admission Billing</h6>
						<hr class="hr">
						<?php
						$service = new Service();
						$total_admission_bill = 0;
						$get_admission_billing = mysqli_query($db, "SELECT *
																																														FROM admission_billing
																																														WHERE admission_id='" . $admission_id . "' AND subscriber_id='" . $active_subscriber . "'
																																									")  or die(mysqli_error($db));
						while ($rows = mysqli_fetch_array($get_admission_billing)) {
							$service->service_id = $rows['service_id'];
							$service->ServiceInfo();

							// $get_admission_date=mysqli_query($db,"SELECT * FROM admissions
							// 																																	WHERE
							// 																																		admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
							// $admission_info=mysqli_fetch_array($get_admission_info);

							$admission_date = new DateTime($admission_info['admission_date']);
							$todays_date = new DateTime(date('Y-m-d'));
							$diff = date_diff($admission_date, $todays_date);
							$days_on_admission = $diff->format('%d') + 1;


						?>

							<div class="card mb-3" style="border-left:4px solid; border-radius:0px">
								<div class="card-body">

									<p class="poppins"><?php echo $service->description; ?></p>
									<div class="row" style="font-size:10px">
										<div class="col-4">
											<?php echo $rows['unit_cost']; ?>
										</div>
										<div class="col-4">
											<?php echo $days_on_admission; ?> Days
										</div>
										<div class="col-4 text-right font-weight-bold">
											GHS <?php echo number_format($rows['unit_cost'] * $days_on_admission, 2); ?>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col-12 text-right">
											<a href="#" class="text-danger delete_admission_billing" data-sn="<?php echo $rows['sn']; ?>"><i class="fas fa-trash-alt mr-2" aria-hidden></i> Delete</a>
										</div>
									</div>
								</div>
							</div>

						<?php
							$total_admission_bill += $rows['unit_cost'] * $days_on_admission;
						}

						?>
						<div class="card mb-3" style="border-left:4px solid; border-radius:0px">
							<div class="card-body">
								<div class="row">
									<div class="col-6">
										Total Bill
									</div>
									<div class="col-6 text-right font-weight-bold">
										GHS <?php echo number_format($total_admission_bill, 2); ?>
									</div>
								</div>
							</div>
						</div>


						<button type="button" class="btn btn-primary wide m-0" data-toggle='modal' data-target="#request_discharge_modal">Request Discharge</button>

					</div>
				</div>

				<div class="card mb-3">
					<div class="card-body">
						<h6 class="montserrat font-weight-bold">Current Medications</h6>
						<hr class="hr">
						<ul class="list-group">
							<?php

							$get_current_meds = mysqli_query($db, "SELECT *
																																														FROM prescriptions
																																														WHERE
																																															patient_id='" . $patient_id . "' AND
																																															visit_id='" . $visit_id . "' AND
																																															subscriber_id='" . $active_subscriber . "'
																																									") or die(mysqli_error($db));
							while ($meds = mysqli_fetch_array($get_current_meds)) {
								$drug->drug_id = $meds['drug_id'];
								$drug->DrugInfo();
							?>
								<li class="list-group-item"><?php echo $drug->drug_name; ?></li>
							<?php
							}
							?>

						</ul>
					</div>
				</div>


			</div>
		</div>


		<div class="row">
			<div class="col-md-8">


			</div>
			<div class="col-md-4">



				<div class="card">
					<div class="card-body">
						<h6 class="montserrat font-weight-bold">Recent Reviews</h6>
						<hr class="hr">



					</div>
				</div>


			</div>
		</div>

	</div>
	<div class="tab-pane fade" id="ex1-pills-2" role="tabpanel" aria-labelledby="ex1-tab-2">

		<div class="card">
			<div class="card-body">
				<h6 class="montserrat font-weight-bold">Vitals History</h6>
				<hr class="hr">

				<?php
				$vitalhistory = mysqli_query($db, "SELECT * FROM admissions_vitals WHERE admission_id='" . $admission_id . "' AND subscriber_id='" . $active_subscriber . "' AND status='active'") or die(mysqli_error($db));
				while ($rows = mysqli_fetch_array($vitalhistory)) {
					$nurse->nurse_id = $rows['nurse_id'];
					$nurse->NurseInfo();
				?>
					<div class="card mb-2">
						<div class="card-body">
							<div class="row">
								<div class="col-2">
									<?php echo $rows['date_time']; ?>
								</div>
								<div class="col-2">
									<?php echo $rows['temperature']; ?> <sup>o</sup>
								</div>
								<div class="col-2">
									<?php echo $rows['pulse']; ?>
								</div>
								<div class="col-2">
									<?php echo $rows['systolic']; ?> / <?php echo $rows['diastolic']; ?>
								</div>
								<div class="col-2">
									<?php echo $nurse->nurse_fullname; ?>
								</div>
								<div class="col-2 text-right">
									<button type="button" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt mr-2"></i>Edit</button>
									<button type="button" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt mr-2"></i>Delete</button>
								</div>
							</div>
						</div>
					</div>
				<?php
				}
				?>

			</div>
		</div>

	</div>
	<div class="tab-pane fade" id="ex1-pills-3" role="tabpanel" aria-labelledby="ex1-tab-3">
		Tab 3 content
	</div>
	<div class="tab-pane fade" id="opd_summary_content" role="tabpanel" aria-labelledby="opd_summary_tab">

		<div class="card">
			<div class="card-body" style="min-height:300px">

				<h6 class="montserrat font-weight-bold">OPD Summary</h6>
				<hr class="hr">


				<section class="mb-5">
					<p class="montserrat font-weight-bold">1. Presenting Complains</p>

					<div class="grey lighten-4 px-4 py-2 mt-4">
						<?php

						$query = mysqli_query($db, "SELECT *
																																		FROM patient_complains
																																		WHERE
																																			subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND status='active'
																													") or die(mysqli_error($db));

						if (mysqli_num_rows($query) == 0) {
							echo 'No Complains';
						} else {
							while ($complains = mysqli_fetch_array($query)) {
						?>
								<p class="mb-2"><?php echo $complains['complain']; ?>, <?php echo $complains['complain_duration']; ?></p>

						<?php
							}
						}

						?>
					</div>
				</section>

				<section class="mb-5">
					<p class="montserrat font-weight-bold mb-3">2. Out - Direct Questioning</p>

					<div class="grey lighten-4 px-4 py-2" id="odq_holder">
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
								<p><?php echo $odq['question']; ?> <?php echo $odq['response']; ?></p>

								<div class="mb-3">

								</div>
						<?php
							}
						}

						?>

					</div>
				</section>


				<section class="mb-5">
					<p class="montserrat font-weight-bold mb-3">3. History Of Presenting Complaints</p>

					<div class="grey lighten-4  px-4 py-2 mt-4">
						<?php

						$query = mysqli_query($db, "SELECT *
																																		FROM patient_hpc
																																		WHERE
																																			subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "'
																													") or die(mysqli_error($db));

						if (mysqli_num_rows($query) == 0) {
							echo 'No HPC';
						} else {
							$hpc_count = 1;
							while ($hpc = mysqli_fetch_array($query)) {
						?>
								<p><?php echo $hpc['history']; ?></p>

								<div class="mb-3">

								</div>
						<?php
							}
						}

						?>

					</div>
				</section>

				<section class="mb-5">
					<p class="montserrat font-weight-bold mb-3">4. Clinical Examination</p>
					<div class="grey lighten-4  px-4 py-2 mt-4" id="examination_holder">
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
								<p><?php echo $odq['observation']; ?></p>

						<?php
							}
						}

						?>
					</div>
				</section>

				<section class="mb-5">
					<p class="mb-3 font-weight-bold montserrat">5. Diagnosis</p>

					<div class="grey lighten-4 px-4 py-2">
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
								<div class="mb-4">

								</div>
						<?php

							}
						}

						?>
					</div>
				</section>

				<section>
					<h6 class="montserrat font-weight-bold">Investigations</h6>

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
							$test = new Test();
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
				</section>
			</div>
		</div>

	</div>
	<div class="tab-pane fade" id="reviews_content" role="tabpanel" aria-labelledby="reviews_tab">

		<div class="card mb-5">
			<div class="card-body" style="min-height:300px">

				<h6 class="montserrat font-weight-bold">Doctor's Reviews And Notes</h6>
				<hr class="hr">

				<?php

				$get_reviews = mysqli_query($db, "SELECT *
																																							FROM admissions_reviews
																																							WHERE
																																								admission_id='" . $admission_id . "' AND subscriber_id='" . $active_subscriber . "' AND status='active'
																																		") or die(mysqli_error($db));


				while ($rows = mysqli_fetch_array($get_reviews)) {
					$pref = substr($rows['doctor_id'], 0, 2);

					// $doctor->doctor_id=$rows['doctor_id'];
				?>
					<div class="card mb-5">
						<div class="card-body">
							<div class="row mb-3">
								<div class="col-md-6">
									<h6 class="montserrat font-weight-bold"><?php echo $reviewer; ?></h6>
								</div>
								<div class="col-md-6 poppins text-right" style="font-weight:500">
									<?php echo $rows['date_time']; ?>
								</div>
							</div>

							<p><?php echo $rows['review_notes']; ?></p>
						</div>
					</div>
				<?php
				}

				?>

			</div>
		</div>

	</div>
	<div class="tab-pane fade" id="nurses_notes_content" role="tabpanel" aria-labelledby="nurses_notes_tab">

		<div class="card mb-5">
			<div class="card-body " style="min-height:300px">

				<h6 class="montserrat font-weight-bold">Nurses Notes</h6>
				<hr class="hr">

				<?php

				$get_notes = mysqli_query($db, "SELECT *
																																							FROM admissions_nursesnotes
																																							WHERE
																																								admission_id='" . $admission_id . "' AND subscriber_id='" . $active_subscriber . "' AND status='active'
																																		") or die(mysqli_error($db));


				while ($rows = mysqli_fetch_array($get_notes)) {
					$pref = substr($rows['doctor_id'], 0, 2);

					// $doctor->doctor_id=$rows['doctor_id'];
				?>
					<div class="card mb-5">
						<div class="card-body">
							<div class="row mb-3">
								<div class="col-md-6">
									<h6 class="montserrat font-weight-bold"><?php echo $reviewer; ?></h6>
								</div>
								<div class="col-md-6 poppins text-right" style="font-weight:500">
									<?php echo $rows['date_time']; ?>
								</div>
							</div>

							<p><?php echo $rows['nurses_notes']; ?></p>
						</div>
					</div>
				<?php
				}

				?>

			</div>
		</div>

	</div>
	<div class="tab-pane fade" id="medications_content" role="tabpanel" aria-labelledby="medications_tab">

		<div class="card">
			<div class="card-body">

				<h6 class="montserrat font-weight-bold">Medications Served</h6>
				<hr class="hr">

				<?php

				$get_medications_served = "SELECT drug_id FROM admissions_serve_meds WHERE admission_id='" . $admission_id . "' AND subscriber_id='" . $active_subscriber . "' AND status='active' GROUP BY drug_id ";
				$r = $mysqli->query($get_medications_served);
				while ($rows = $r->fetch_assoc()) {
					$drug->drug_id = $rows['drug_id'];
					$drug->DrugInfo();
				?>

					<div class="card mb-3">
						<div class="card-body p">
							<div class="row">
								<div class="col-md-6">
									<?php echo $drug->drug_name; ?>
								</div>
								<div class="col-md-6">
									<?php
									$get_times = mysqli_query($db, "SELECT *
																																														FROM admissions_serve_meds
																																														WHERE
																																															admission_id='" . $admission_id . "' AND subscriber_id='" . $active_subscriber . "' AND drug_id='" . $rows['drug_id'] . "' AND status='active'

																																										") or die(mysqli_error($db));
									while ($times = mysqli_fetch_array($get_times)) {
										$nurse->nurse_id = $times['nurse_id'];
										$nurse->NurseInfo();
									?>
										<div class="row poppins mb-2">
											<div class="col-md-6">
												<?php echo $times['date_time'] ?>
											</div>
											<div class="col-md-6">
												<?php echo $nurse->nurse_fullname; ?>
											</div>
										</div>
										<hr class="hr">
									<?php
									}
									?>
								</div>
							</div>
						</div>
					</div>

				<?php
				}
				?>

			</div>
		</div>

	</div>
	<div class="tab-pane fade" id="labs_content" role="tabpanel" aria-labelledby="labs_tab">

		<div class="card">
			<div class="card-body">

				<h6 class="montserrat font-weight-bold">Laboratory Requests</h6>
				<hr class="hr">

				<section class="pt-4">

					<table class="table table-condensed">
						<thead class="grey lighten-4">
							<tr>
								<th>Date/Time</th>
								<th>Test Requested</th>
								<th>Sample</th>
								<th>Doctor</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$get_lab_requests = mysqli_query($db, "SELECT * FROM lab_requests_tests WHERE patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND status='active'") or die(mysqli_error($db));
							while ($requests = mysqli_fetch_array($get_lab_requests)) {
								$test = new Test();
								$test->test_id = $requests['test_id'];
								$test->TestInfo();
							?>
								<tr>
									<td><?php echo $requests['date']; ?></td>
									<td><?php echo $test->description; ?></td>
									<td><?php echo $test->specimen; ?></td>
									<td class="text-right">
										<a href="test_results_entry.php?test_id=<?php echo $rows['test_id']; ?>&request_id=<?php echo $rows['request_id']; ?>" type="button" class="btn btn-primary btn-sm"><i class="fas fa-file-alt mr-1" aria-hidden></i> Enter Results</a>
									</td>
								</tr>
							<?php
							}
							?>

						</tbody>
					</table>
				</section>

			</div>
		</div>

	</div>
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

		<div class="card">
			<div class="card-body">

				<h6 class="montserrat font-weight-bold">Uploaded Documents</h6>
				<hr class="hr">

				<section class="pt-4">

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


				</section>

			</div>
		</div>

	</div>
</div>
<!-- Pills content -->





</div>
</main>





<div id="modal_holder">

</div>


<?php
include('../Includes/modals/inpatient/newLabRequestModal.php');
include('../Includes/modals/inpatient/prescriptionsModal.php');
include('../Includes/modals/inpatient/newSurgeryModal.php');
include('../Includes/modals/inpatient/vitalsModal.php');
include('../Includes/modals/inpatient/serveMedsModal.php');
include('../Includes/modals/inpatient/reviewsModal.php');
include('../Includes/modals/inpatient/nursesNotesModal.php');
include('../Includes/modals/inpatient/billingModal.php');
include('../Includes/modals/inpatient/requestDischageModal.php');
?>

</body>

<!--   Core JS Files   -->
<?php require_once '../navigation/footer.php'; ?>

<script type="text/javascript" src="../Includes/js/inpatient/inpatient.js"></script>

</html>