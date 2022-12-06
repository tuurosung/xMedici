<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<style media="screen">
	/* .card-body .list-group-item{
		border:none !important;
	} */
</style>
<?php
	// ob_clean();
	if($_GET['admission_id']==""){
		header('Location: inpatients.php');
	}else {
		$admission_id=clean_string($_GET['admission_id']);
	}

	$get_admission_info=mysqli_query($db,"SELECT * FROM admissions WHERE admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
	$admission_info=mysqli_fetch_array($get_admission_info);

	$visit_id=$admission_info['visit_id'];
	// $patient_id=$admission_info['patient_id'];

	$visit=new Visit();
	$visit->VisitInfo($visit_id);


	$patient_id=$visit->patient_id;

	$p=new Patient();
	$p->patient_id=$patient_id;
	$p->PatientInfo();

	$visit->VisitBilling($patient_id,$visit_id);
	$visit->VisitPayment($patient_id,$visit_id);
	$visit->VisitBalance($patient_id,$visit_id);



	$get_vitals=mysqli_query($db,"SELECT * FROM vitals WHERE visit_id='".$visit_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
	$vitals=mysqli_fetch_array($get_vitals);

	$drug=new Drug();
	$surg=new Surgery();
	$opd=new Visit();
	$ward=new Ward();
 ?>


		<a href="#" class="float" data-toggle="modal" data-target="#hidden_menu_modal">
		<i class="fas fa-heartbeat my-float"></i>
		</a>

	

				<div class="row mb-4">
				  <div class="col-6">
				    <h4 class="titles">In-Patient Management</h4>
				  </div>
				  <div class="col-6 text-right">


						<div class="dropdown open <?php if($admission_info['admission_status'] != 'admitted'){ echo 'd-none';} ?>">
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

						if($admission_info['admission_status']=='discharged'){
							?>
							<div class="card info-color-dark white-text mb-5">
								<div class="card-body">
									<p class="montserrat font-weight-bold"><i class="fas fa-exclamation mr-2" aria-hidden></i> Patient Has Been Discharged. You are in Read-Only Mode.</p>
								</div>
							</div>
							<?php
						}elseif ($admission_info['admission_status']=='deceased') {
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
							    <a
							      class="nav-link active"
							      id="ex1-tab-1"
							      data-toggle="pill"
							      href="#ex1-pills-1"
							      role="tab"
							      aria-controls="ex1-pills-1"
							      aria-selected="true"
							      >Dashboard</a
							    >
							  </li>
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link"
							      id="ex1-tab-2"
							      data-toggle="pill"
							      href="#ex1-pills-2"
							      role="tab"
							      aria-controls="ex1-pills-2"
							      aria-selected="false"
							      >In-Patient Management</a
							    >
							  </li>
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link"
							      id="opd_summary_tab"
							      data-toggle="pill"
							      href="#opd_summary_content"
							      role="tab"
							      aria-controls="opd_summary_content"
							      aria-selected="false"
							      >OPD Summary</a
							    >
							  </li>
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link"
							      id="reviews_tab"
							      data-toggle="pill"
							      href="#reviews_content"
							      role="tab"
							      aria-controls="reviews_content"
							      aria-selected="false"
							      >Reviews</a
							    >
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link"
							      id="nurses_notes_tab"
							      data-toggle="pill"
							      href="#nurses_notes_content"
							      role="tab"
							      aria-controls="nurses_notes_content"
							      aria-selected="false"
							      >Nurse's Notes</a
							    >
							  </li>
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link"
							      id="medications_tab"
							      data-toggle="pill"
							      href="#medications_content"
							      role="tab"
							      aria-controls="medications_content"
							      aria-selected="false"
							      >Medications</a
							    >
							  </li>
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link"
							      id="labs_tab"
							      data-toggle="pill"
							      href="#labs_content"
							      role="tab"
							      aria-controls="labs_content"
							      aria-selected="false"
							      >Lab Requests</a
							    >
							  </li>
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link"
							      id="surgery_tab"
							      data-toggle="pill"
							      href="#surgery_content"
							      role="tab"
							      aria-controls="surgery_content"
							      aria-selected="false"
							      >Surgery</a
							    >
							  </li>
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link"
							      id="files_tab"
							      data-toggle="pill"
							      href="#files_content"
							      role="tab"
							      aria-controls="files_content"
							      aria-selected="false"
							      >Uploaded Docs</a
							    >
							  </li>
							</ul>
							<!-- Pills navs -->
						</div>
					</div>


					<!-- Pills content -->
					<div class="tab-content" id="ex1-content">
					  <div   class="tab-pane fade show active"  id="ex1-pills-1"   role="tabpanel"  aria-labelledby="ex1-tab-1">

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
													$get_dates=mysqli_query($db,"SELECT *
																																													FROM prescriptions
																																													WHERE
																																														patient_id='".$patient_id."' AND
																																														visit_id='".$visit_id."' AND
																																														subscriber_id='".$active_subscriber."'
																																														GROUP BY date
																																														ORDER BY date desc
																																								") or die(mysqli_error($db));
														while ($dates=mysqli_fetch_array($get_dates)) {
															$date=$dates['date'];
															?>
															<p class="montserrat font-weight-bold mb-3"><?php echo $dates['date']; ?></p>

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

																			$get_current_meds=mysqli_query($db,"SELECT *
																																																			FROM prescriptions
																																																			WHERE
																																																				patient_id='".$patient_id."' AND
																																																				visit_id='".$visit_id."' AND
																																																				subscriber_id='".$active_subscriber."' AND
																																																				date='".$date."'
																																														") or die(mysqli_error($db));
																		while ($meds=mysqli_fetch_array($get_current_meds)) {
																			$drug->drug_id=$meds['drug_id'];
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
																						if ($meds['treatment_status']=='ongoing') {
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
												<p class="big-text">GHS <?php echo number_format($visit->VisitBalance($patient_id,$visit_id),2); ?></p>
										</div>
									</div>

									<div class="card mb-3">
											<div class="card-body">
												<h6 class="montserrat font-weight-bold">Recent Vitals</h6>
												<hr class="hr">
												<ul class="list-group poppins">
												<?php
													$get_vitals=mysqli_query($db,"SELECT *
																																						FROM admissions_vitals
																																						WHERE
																																								admission_id='".$admission_id."' AND
																																								subscriber_id='".$active_subscriber."' AND
																																								status='active'
																																							ORDER BY sn DESC
																																							LIMIT 1
																																			") or die(mysqli_error($db));

													$vitals=mysqli_fetch_array($get_vitals)
												 ?>
												  <li class="list-group-item">Blood Pressure <span class="float-right"><?php echo $vitals['systolic']; ?> / <?php echo $vitals['diastolic']; ?></span> </li>
												  <li class="list-group-item">Temperature <span class="float-right"><?php echo $vitals['temperature']; ?> </span> </li>
												  <li class="list-group-item">Pulse <span class="float-right"><?php echo $vitals['pulse']; ?> </span> </li>
												  <li class="list-group-item">Weight <span class="float-right"><?php echo $vitals['weight']; ?> </span> </li>
												</ul>

											</div>
									</div>
									<!-- End Card -->

									<div class="card mb-5">
										<div class="card-body">
											<h6 class="font-weight-bold montserrat">Admission Billing</h6>
											<hr class="hr">
											<?php
												$service=new Service();
												$total_admission_bill=0;
												$get_admission_billing=mysqli_query($db,"SELECT *
																																														FROM admission_billing
																																														WHERE admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."'
																																									")  or die(mysqli_error($db));
												while ($rows=mysqli_fetch_array($get_admission_billing)) {
													$service->service_id=$rows['service_id'];
													$service->ServiceInfo();

													// $get_admission_date=mysqli_query($db,"SELECT * FROM admissions
													// 																																	WHERE
													// 																																		admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
														// $admission_info=mysqli_fetch_array($get_admission_info);

														$admission_date=new DateTime($admission_info['admission_date']);
														$todays_date=new DateTime(date('Y-m-d'));
														$diff=date_diff($admission_date,$todays_date);
														$days_on_admission=$diff->format('%d') +1;


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
																	GHS <?php echo number_format($rows['unit_cost']*$days_on_admission,2); ?>
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
													$total_admission_bill+=$rows['unit_cost']*$days_on_admission;
												}

											 ?>
											 <div class="card mb-3" style="border-left:4px solid; border-radius:0px">
											 	<div class="card-body">
													<div class="row">
													  <div class="col-6">
															Total Bill
													  </div>
													  <div class="col-6 text-right font-weight-bold">
															GHS <?php echo number_format($total_admission_bill,2); ?>
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

														$get_current_meds=mysqli_query($db,"SELECT *
																																														FROM prescriptions
																																														WHERE
																																															patient_id='".$patient_id."' AND
																																															visit_id='".$visit_id."' AND
																																															subscriber_id='".$active_subscriber."'
																																									") or die(mysqli_error($db));
													while ($meds=mysqli_fetch_array($get_current_meds)) {
														$drug->drug_id=$meds['drug_id'];
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
												$vitalhistory=mysqli_query($db,"SELECT * FROM admissions_vitals WHERE admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
												while ($rows=mysqli_fetch_array($vitalhistory)) {
													$nurse->nurse_id=$rows['nurse_id'];
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

												$query=mysqli_query($db,"SELECT *
																																		FROM patient_complains
																																		WHERE
																																			subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'
																													") or die(mysqli_error($db));

												if(mysqli_num_rows($query)==0){
													echo 'No Complains';
												}else {
													while ($complains=mysqli_fetch_array($query)) {
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

												$query=mysqli_query($db,"SELECT *
																																		FROM patient_odq
																																		WHERE
																																			subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."'
																													") or die(mysqli_error($db));

												if(mysqli_num_rows($query)==0){
													echo 'No ODQ';
												}else {
													// $hpc_count=1;
													while ($odq=mysqli_fetch_array($query)) {
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

												$query=mysqli_query($db,"SELECT *
																																		FROM patient_hpc
																																		WHERE
																																			subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."'
																													") or die(mysqli_error($db));

												if(mysqli_num_rows($query)==0){
													echo 'No HPC';
												}else {
													$hpc_count=1;
													while ($hpc=mysqli_fetch_array($query)) {
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

												$query=mysqli_query($db,"SELECT *
																																		FROM patient_examination
																																		WHERE
																																			subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."'
																													") or die(mysqli_error($db));

												if(mysqli_num_rows($query)==0){
													echo 'No Clinical Examination';
												}else {
													// $hpc_count=1;
													while ($odq=mysqli_fetch_array($query)) {
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

												$query=mysqli_query($db,"SELECT *
																																		FROM patient_diagnosis
																																		WHERE
																																			subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'
																													") or die(mysqli_error($db));

												if(mysqli_num_rows($query)==0){
													echo 'No Diagnosis';
												}else {
													while ($diagnosis=mysqli_fetch_array($query)) {
														?>
														<p class="montserrat font-weight-bold m-0">	<?php echo $diagnosis['diagnosis_id']; ?></p>
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
														$get_lab_requests=mysqli_query($db,"SELECT * FROM lab_requests_tests WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'") or die(mysqli_error($db));
														while ($requests=mysqli_fetch_array($get_lab_requests)) {
															$test=new Test();
															$test->test_id=$requests['test_id'];
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

												$get_reviews=mysqli_query($db,"SELECT *
																																							FROM admissions_reviews
																																							WHERE
																																								admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."' AND status='active'
																																		") or die(mysqli_error($db));


													while ($rows=mysqli_fetch_array($get_reviews)) {
														$pref=substr($rows['doctor_id'],0,2);
													
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

												$get_notes=mysqli_query($db,"SELECT *
																																							FROM admissions_nursesnotes
																																							WHERE
																																								admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."' AND status='active'
																																		") or die(mysqli_error($db));


													while ($rows=mysqli_fetch_array($get_notes)) {
														$pref=substr($rows['doctor_id'],0,2);
														
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

											$get_medications_served=mysqli_query($db,"SELECT *
																																															FROM admissions_serve_meds
																																															WHERE
																																																admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."' AND status='active'
																																															GROUP BY
																																															drug_id
																																											") or die(mysqli_error($db));
													while ($rows=mysqli_fetch_array($get_medications_served)) {
														$drug->drug_id=$rows['drug_id'];
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
																					$get_times=mysqli_query($db,"SELECT *
																																														FROM admissions_serve_meds
																																														WHERE
																																															admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."' AND drug_id='".$rows['drug_id']."' AND status='active'

																																										") or die(mysqli_error($db));
																						while ($times=mysqli_fetch_array($get_times)) {
																							$nurse->nurse_id=$times['nurse_id'];
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
											$get_lab_requests=mysqli_query($db,"SELECT * FROM lab_requests_tests WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'") or die(mysqli_error($db));
											while ($requests=mysqli_fetch_array($get_lab_requests)) {
												$test=new Test();
												$test->test_id=$requests['test_id'];
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
												$i=1;
												$get_surgeries=mysqli_query($db,"SELECT * FROM surgeries WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
												while ($rows=mysqli_fetch_array($get_surgeries)) {
														$surg->surgery_id=$rows['surgery_id'];
														$surg->SurgeryInfo();
													?>
													<li class="list-group-item hover" >
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
																<?php echo number_format($surg->surgery_cost,2); ?>
															</div>
															<div class="col-md-2">
																<?php echo  $rows['date']; ?>
															</div>
															<div class="col-md-2 text-right">
																<a href="surgery.php?surgery_id=<?php echo $rows['surgery_id']; ?>"  class="btn btn-info btn-sm">Manage</a>
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

											<form action="../serverscripts/admin/Patients/file_upload.php?visit_id=<?php echo $visit_id; ?>&patient_id=<?php echo $patient_id; ?>"
											class="dropzone"
											id="my-awesome-dropzone"></form>

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
													$get_uploads=mysqli_query($db,"SELECT * FROM file_uploads WHERE visit_id='".$visit_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
													while ($rows=mysqli_fetch_array($get_uploads)) {
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





<div id="new_lab_request_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
			<form id="request_labs_frm">
      <div class="modal-body">
        <h6>Request Labs</h6>
				<hr class="hr">
				<div class="spacer">	</div>
				<div class="form-group d-none">
					<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
				</div>

				<div class="form-group d-none">
					<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
				</div>

				<ul class="list-group">
				<?php
					$get_tests=mysqli_query($db,"SELECT * FROM lab_tests WHERE subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
					while ($tests=mysqli_fetch_array($get_tests)) {
						?>

						<li class="list-group-item">
							<div class="form-check">
									<input	class="form-check-input"	type="checkbox" name="test_id[]" 	value="<?php echo $tests['test_id']; ?>" id="<?php echo $tests['test_id']; ?>"/>
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
								$get_drugs=mysqli_query($db,"SELECT *
																																	FROM
																																		pharm_inventory
																																	WHERE
																																		status='active' && subscriber_id='".$active_subscriber."'
																																	ORDER BY generic_name asc
																												")  or die(mysqli_error($db));
								while ($drugs=mysqli_fetch_array($get_drugs)) {
									?>
									<option value="<?php echo $drugs['drug_id']; ?>"><?php echo $drugs['generic_name'] .' '.$drugs['trade_name']; ?></option>
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
						<select  name="route" id="route" class="custom-select browser-default">
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
						<select  name="frequency" id="frequency" class="custom-select browser-default">
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
							<input type="text" class="form-control" name="visit_id"  value="<?php echo $visit_id; ?>" readonly>
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
        <button type="submit" class="btn btn-primary" >Schedule Surgery</button>
      </div>
			</form>
    </div>
  </div>
</div>



<div id="vitals_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
			<form id="patient_vitals_frm" autocomplete="off">
      <div class="modal-body">
        <h6 class="montserrat font-weight-bold">Patient Vitals</h6>
				<hr class="hr">

					<div class="form-group d-none">
						<input type="text" class="form-control" id="" name="admission_id" value="<?php echo $admission_id; ?>" readonly>
					</div>

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
								<input type="text" name="systolic" class="form-control" placeholder="" required  value="">
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
								<input type="text" name="diastolic" class="form-control" placeholder="" required  value="">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="">Pulse</label>
						<input type="text" name="pulse" class="form-control" placeholder=""  value="">
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Temperature</label>
								<input type="text" name="temperature" class="form-control" placeholder=""  value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Weight</label>
								<input type="text" name="weight" class="form-control" value="">
							</div>
						</div>
					</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Vitals</button>
      </div>
			</form>
    </div>
  </div>
</div>



<div id="serve_meds_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
			<form id="serve_meds_frm">
      <div class="modal-body">
        <h6 class="font-weight-bold montserrat">Serve Medications</h6>
				<hr class="hr">

				<div class="form-group d-none">
					<input type="text" class="form-control" id="" name="admission_id" value="<?php echo $admission_id; ?>" readonly>
				</div>

				<div class="row">
				  <div class="col-md-6">
						<label for="">Medication</label>
						<select class="custom-select browser-default" name="serve_meds_drug_id">
							<?php

									$get_current_meds=mysqli_query($db,"SELECT *
																																									FROM prescriptions
																																									WHERE
																																										patient_id='".$patient_id."' AND
																																										visit_id='".$visit_id."' AND
																																										subscriber_id='".$active_subscriber."'
																																				") or die(mysqli_error($db));
								while ($meds=mysqli_fetch_array($get_current_meds)) {
									$drug->drug_id=$meds['drug_id'];
									$drug->DrugInfo();
									?>
									<option value="<?php echo $meds['drug_id']; ?>"><?php echo $drug->drug_name; ?></option>
									<?php
								}
							 ?>
						</select>
				  </div>
					<div class="col-md-3">
						<label for="">Time</label>
						<input type="text" class="form-control" name="serve_meds_time" value="<?php echo date('H:i:s'); ?>">
					</div>
					<div class="col-md-3">
						<label for="">Nurse</label>
						<select class="custom-select browser-default" name="nurse_id">
							<option value="<?php echo $active_user; ?>"><?php echo $user_fullname; ?></option>
						</select>
					</div>
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white">Close</button>
        <button type="submit" class="btn btn-primary">Serve Meds</button>
      </div>
			</form>
    </div>
  </div>
</div>


<div id="reviews_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
			<form id="reviews_frm">
      <div class="modal-body">
        <h6 class="montserrat font-weight-bold">Doctor's Reviews</h6>
				<hr class="hr">

				<div class="form-group d-none">
					<input type="text" class="form-control" id="" name="admission_id" value="<?php echo $admission_id; ?>" readonly>
				</div>

				<div class="form-group">
					<label for="">Notes</label>
					<textarea name="review_notes" id="review_notes"></textarea>
				</div>

      </div>
      <div class="modal-footer">
				<button type="button" class="btn btn-white">Close</button>
        <button type="submit" class="btn btn-primary">Save Review</button>
      </div>
			</form>
    </div>
  </div>
</div>


<div id="nurses_notes_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
			<form id="nurses_notes_frm">
      <div class="modal-body">
        <h6 class="montserrat font-weight-bold">Nurse's Notes</h6>
				<hr class="hr">

				<div class="form-group d-none">
					<input type="text" class="form-control" id="" name="admission_id" value="<?php echo $admission_id; ?>" readonly>
				</div>

				<div class="form-group">
					<label for="">Notes</label>
					<textarea  name="nurses_notes" id="nurses_notes" ></textarea>
				</div>

      </div>
      <div class="modal-footer">
				<button type="button" class="btn btn-white">Close</button>
        <button type="submit" class="btn btn-primary">Save Notes</button>
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
								$get_billing_points=mysqli_query($db,"SELECT * FROM billing_points") or die(mysqli_error($db));
								while ($billing_points=mysqli_fetch_array($get_billing_points)) {
									?>
									<optgroup label="<?php echo $billing_points['point_name']; ?>">
										<?php
												$get_services=mysqli_query($db,"SELECT * FROM services WHERE billing_point='".$billing_points['billing_point']."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
												while ($rows=mysqli_fetch_array($get_services)) {
													?>
													<option class="poppins" value="<?php echo $rows['service_id'] ?>" data-service_cost="<?php echo $rows['service_cost']; ?>"><?php echo $rows['description']; ?></option>
													<?php
												}
										 ?>
									</optgroup>
									<?
								}
							 ?>


						</select>
					</div>

					<div class="form-group">
						<label for="">Service Cost</label>
						<input type="text" class="form-control" name="billing_service_cost" value="" id="billing_service_cost"  required>
					</div>

					<div class="form-group">
						<label for="">Narration</label>
						<input type="text" class="form-control" name="narration" value="" required>
					</div>




      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add Bill  To Patient</button>
      </div>
			</form>
    </div>
  </div>
</div>

<div id="request_discharge_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
			<form id="request_discharge_frm">
      <div class="modal-body">

        <h6 class="montserrat font-weight-bold">Request Discharge</h6>
				<hr class="hr">

				<p class="mb-4">*Requesting discharge by the doctor will move the disable patient's in-patient folder. No prescriptions, review or vitals can be recorded after this.</p>



					<div class="form-group d-none">
						<input type="text" class="form-control" id="" name="admission_id" value="<?php echo $admission_id; ?>" readonly>
					</div>

					<div class="form-group d-none">
						<input type="text" class="form-control" id="" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
					</div>

					<div class="form-group d-none">
						<input type="text" class="form-control" id="" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
					</div>

					<div class="form-group">
						<label for="">Admission Billing</label>
						<input type="text" class="form-control" name="admission_bill_narration"
						value="Total Admission And Reviews Charge For <?php echo $days_on_admission; ?> Days" id="admission_bill_narration"  required>
					</div>

					<div class="form-group">
						<label for="">Admission Bill</label>
						<input type="text" class="form-control" name="admission_bill"
						value="<?php echo $total_admission_bill; ?>" id="admission_bill"  required>
					</div>


					<div class="form-group">
						<label for="">Discharge Notes (Doctor)</label>
						<textarea name="discharge_notes" class="form-control"></textarea>
					</div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Request Patient Discharge</button>
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

		var admission_id='<?php echo $admission_id ?>'

		// $('.select2').select2({
    //     dropdownParent: $('#hidden_menu_modal')
    // });

		$('#request_discharge_frm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/Admissions/request_discharge_frm.php',
				type: 'GET',
				data:$('#request_discharge_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						bootbox.alert('Discharge Request Successful',function(){
							window.location='inpatients.php';
						})
					}else {
						bootbox.alert(msg)
					}
				}
			})
		});


		$('#billing_service_id').on('change', function(event) {
			event.preventDefault();
			var billing_service_cost=$(this).find(':selected').data('service_cost')
			$('#billing_service_cost').val(billing_service_cost)
		});

		$('#bill_patient_frm').on('submit',  function(event) {
			event.preventDefault();
			bootbox.confirm("Add bill to patients cost?",function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/OPD/bill_patient_frm.php',
						type: 'GET',
						data:$('#bill_patient_frm').serialize(),
						success:function(msg){
							if(msg==='billing_successful'){
								bootbox.alert("Bill added to patient",function(){
									window.location.reload()
								})
							}else {
								bootbox.alert(msg)
							}
						}
					})
				}
			})
		});



		$('.delete_admission_billing').on('click', function(event) {
			event.preventDefault();
			var sn=$(this).data('sn')
			bootbox.confirm("Confirm. Delete this billing item?",function(r){
				if(r===true){
					$.get('../serverscripts/admin/Admissions/delete_admission_billing.php?sn='+sn,function(msg){
						if(msg==='delete_successful'){
							bootbox.alert('Bill Deleted Successfully',function(){
								window.location.reload()
							})
						}
					})
				}//
			})
		});//End Admission Billing


		TempGraph();
		function TempGraph(){
		      {
		          $.post("../serverscripts/admin/Admissions/temperature_chart.php?admission_id="+admission_id,
		          function (data){
		              console.log(data);
		              data=$.parseJSON(data)

		               var time = [];
		              var temp = [];

		              for (var i in data) {
		                  time.push(data[i].times);
		                  temp.push(data[i].temp);
		                  // alert(i)
		              }
		              // alert(sales)

		              var chartdata = {
		                  labels: time,
		                  datasets: [
		                      {
		                          label: 'Temperature',
		                          borderColor: 'rgb(0, 13, 126)',
		                          pointBackgroundColor: 'rgb(0, 13, 126)',
		                          backgroundColor: 'rgba(250, 250, 250, 0)',
		                          data: temp
		                      }
		                  ]
		              };

		              var graphTarget = $("#temperature_chart");

		              var barGraph = new Chart(graphTarget, {
		                  type: 'line',
		                  data: chartdata
		              });
		          });
		      }
		  }



		tinymce.init({
      selector: '#hpc,#doctors_notes,#clinical_examination,#review_notes,#nurses_notes',
			force_br_newlines : true,
		  force_p_newlines : false,
		  forced_root_block : '', // Needed for 3.x
				setup: function (editor) {
	        editor.on('change', function () {
	            editor.save();
	        });
	    }
    });

		$('.remove_hpc').on('click', function(event) {
			event.preventDefault();
			var hpc_id=$(this).attr('ID')
			$.get('../serverscripts/admin/OPD/remove_hpc.php?hpc_id='+hpc_id,function(msg){
				if(msg==='delete_successful'){
					bootbox.alert('HPC removed successfully',function(){
						window.location.reload();
					})
				}else {
					bootbox.alert(msg)
				}
			})
		});

		$('.remove_complain').on('click', function(event) {
			event.preventDefault();
			var complain_id=$(this).attr('ID')
			$.get('../serverscripts/admin/OPD/remove_complain.php?complain_id='+complain_id,function(msg){
				if(msg==='delete_successful'){
					bootbox.alert('Complain removed successfully',function(){
						window.location.reload();
					})
				}else {
					bootbox.alert(msg)
				}
			})
		});

		$('#admission_frm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/OPD/admit_patient.php',
				type: 'GET',
				data:$('#admission_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						bootbox.alert('Admission Request Successful',function(){
							window.location.reload()
						})
					}else {
						bootbox.alert(msg)
					}
				}
			})
		});

		$('.remove_diagnosis').on('click', function(event) {
			event.preventDefault();
			var removethis=$(this).attr('ID')
			$.get('../serverscripts/admin/OPD/remove_diagnosis.php?removethis='+removethis,function(msg){
				if(msg==='delete_successful'){
					bootbox.alert('Diagnosis removed',function(){
						window.location.reload()
					})
				}else {
					bootbox.alert(msg)
				}
			})
		});

		$('#diagnosis_search_term').on('keyup', function(event) {
			event.preventDefault();
			var search_term=$(this).val()
			var patient_id="<?php echo $patient_id; ?>"
			var visit_id="<?php echo $visit_id; ?>"
			$.get('../serverscripts/admin/OPD/filter_diagnosis.php?search_term='+search_term+'&patient_id='+patient_id+'&visit_id='+visit_id,function(msg){
				$('#diagnosis_holder').html(msg)

				$('.diagnose_btn').on('click', function(event) {
					event.preventDefault();
					var diagnosis_id=$(this).data('diagnosis_id')
					var patient_id=$(this).data('patient_id')
					var visit_id=$(this).data('visit_id')
					$.get('../../serverscripts/admin/OPD/record_diagnosis.php?diagnosis_id='+diagnosis_id+'&visit_id='+visit_id+'&patient_id='+patient_id,function(msg){
							if(msg=='save_successful'){
								bootbox.alert('Diagnosis Made',function(){
									window.location.reload()
								})
							}else {
								bootbox.alert(msg)
							}
					})
				});
			})
		});


		$('#patient_vitals_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm('Record patients vitals?',function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Admissions/record_vitals.php',
						type: 'GET',
						data:$('#patient_vitals_frm').serialize(),
						success:function(msg){
							if(msg==='save_successful'){
								bootbox.alert('Patient Vitals Recorded successfully',function(){
									window.location.reload()
								})
							}else {
								bootbox.alert(msg)
							}
						}
					})
				}
			})
		});


		$('#serve_meds_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm('Serve Medication?',function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Admissions/serve_meds_frm.php',
						type: 'GET',
						data:$('#serve_meds_frm').serialize(),
						success:function(msg){
							if(msg==='save_successful'){
								bootbox.alert('Medication Served successfully',function(){
									window.location.reload()
								})
							}else {
								bootbox.alert(msg)
							}
						}
					})
				}
			})
		});


		$('#reviews_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm('Record Review?',function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Admissions/reviews_frm.php',
						type: 'GET',
						data:$('#reviews_frm').serialize(),
						success:function(msg){
							if(msg==='save_successful'){
								bootbox.alert('Review Saved successfully',function(){
									window.location.reload()
								})
							}else {
								bootbox.alert(msg)
							}
						}
					})
				}
			})
		});

		$('#nurses_notes_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm('Record Notes?',function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Admissions/nurses_notes_frm.php',
						type: 'GET',
						data:$('#nurses_notes_frm').serialize(),
						success:function(msg){
							if(msg==='save_successful'){
								bootbox.alert('Notes Saved successfully',function(){
									window.location.reload()
								})
							}else {
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
				data:$('#record_complain_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						LoadComplains()
					}else {
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
				data:$('#hpc_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						window.location.reload()
						$('#hpc_tab').show()
					}else {
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
				data:$('#record_diagnosis_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						LoadDiagnosis()
					}else {
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
				data:$('#request_labs_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						bootbox.alert("Labs requests queued at laboratory",function(){
							window.location.reload();
						})
					}else {
						bootbox.alert(msg)
					}
				}
			})
		});

		$('.view_test_result').on('click', function(event) {
			event.preventDefault();
			var test_id=$(this).data('test_id')
			var request_id=$(this).data('request_id')
			$.get('../serverscripts/admin/OPD/view_test_result_modal.php?request_id='+request_id+'&test_id='+test_id,function(msg){
				$('#modal_holder').html(msg)
				$('#view_test_result_modal').modal('show')
			})
		});

		$('#prescription_modal').on('shown.bs.modal', function(event) {
			event.preventDefault();
			$('#ex1-tab-3').tab('show')
			$('#prescription_drug_id').select2({
			    dropdownParent: $('#prescription_modal')
			});
		});

		$('#prescription_frm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/OPD/prescription_frm.php',
				type: 'GET',
				data:$('#prescription_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						bootbox.alert('Prescription updated successfully',function(){
							LoadPrescription();
						})
					}else {
						bootbox.alert(msg)
					}
				}
			})
		});


		$('#new_surgery_modal').on('shown.bs.modal',  function(event) {
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
								data: function (data) {
										return {
												searchTerm: data.term // search term
										};
								},
								processResults: function (response) {
										return {
												results:response
										};
								},
								cache: true
						}
				});
		});

		$('#new_surgery_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm("Book this patient for surgery?",function(r){
				if(r===true){
					$.ajax({
						url: '../../serverscripts/admin/OPD/new_surgery_frm.php',
						type: 'GET',
						data:$('#new_surgery_frm').serialize(),
						success:function(msg){
							if(msg==='save_successful'){
								bootbox.alert('Surgery Scheduled Successfully',function(){
									window.location.reload()
								})
							}else {
								bootbox.alert(msg)
							}
						}
					})//end ajax
				}
			})
		});


		$('#transfer_patient_frm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/Patients/transfer_patient_frm.php',
				type: 'GET',
				data:$('#transfer_patient_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						bootbox.alert('Patient transfer successful',function(){
							window.location.reload()
						})
					}else {
						bootbox.alert(msg)
					}
				}
			})
		});


		function LoadComplains(){
			var patient_id='<?php echo $patient_id; ?>'
			var visit_id='<?php echo $visit_id; ?>'
			$.get('../serverscripts/admin/OPD/load_complains.php?patient_id='+patient_id+'&visit_id='+visit_id,function(msg){
				$('#complains_holder').html(msg)
			})
		}

		function LoadDiagnosis(){
			var patient_id='<?php echo $patient_id; ?>'
			var visit_id='<?php echo $visit_id; ?>'
			$.get('../serverscripts/admin/OPD/load_diagnosis.php?patient_id='+patient_id+'&visit_id='+visit_id,function(msg){
				$('#diagnosis_holder').html(msg)
			})
		}

		function LoadPrescription(){
			var patient_id='<?php echo $patient_id; ?>'
			var visit_id='<?php echo $visit_id; ?>'
			$.get('../serverscripts/admin/OPD/load_prescriptions.php?patient_id='+patient_id+'&visit_id='+visit_id,function(msg){
				$('#prescription_holder').html(msg)
			})
		}







		$('#new_patient_modal').on('shown.bs.modal', function(event) {
			event.preventDefault();
			if($(this).val()=='cash'){
				$('#nhis_number').val('N/A')
				$('#nhis_number').attr('readonly','readonly')
			}else {
				$('#nhis_number').val('')
				$('#nhis_number').attr('readonly',false)
			}
		});

		$('#date_of_birth').datepicker()
		$('#date_of_birth').on('changeDate', function(event) {
			event.preventDefault();
			$(this).datepicker('hide')
		});

		$('#payment_mode').on('change',  function(event) {
			event.preventDefault();
			if($(this).val()=='cash'){
				$('#nhis_number').val('N/A')
				$('#nhis_number').attr('readonly','readonly')
			}else {
				$('#nhis_number').val('')
				$('#nhis_number').attr('readonly',false)
			}
		});




		$('.payment_btn').on('click', function(event) {
			event.preventDefault();
			var bill_id=$(this).attr('ID')
			var patient_id='<?php echo $patient_id; ?>'
			var visit_id='<?php echo $visit_id; ?>'
			bootbox.confirm('Accept payment?',function(r){
				if(r===true){
					$.get('../../serverscripts/admin/Patients/payment_modal.php?patient_id='+patient_id+'&visit_id='+visit_id+'&bill_id='+bill_id,function(msg){
						$('#modal_holder').html(msg)
						$('#new_payment_modal').modal('show')

						$('#amount_paid').on('keyup', function(event) {
							event.preventDefault();
							var amount_payable=$('#amount_payable').val()
							var amount_paid=$('#amount_paid').val()
							$('#balance').val((parseFloat(amount_payable) - parseFloat(amount_paid)).toFixed(2))
						});
						// End keyup

						$('#payment_frm').on('submit', function(event) {
							event.preventDefault();
							$.ajax({
								url: '../serverscripts/admin/Patients/payment_frm.php',
								type: 'GET',
								data:$('#payment_frm').serialize(),
								success:function(msg){
									if(msg==='save_successful'){
										bootbox.alert('Payment successful',function(){
											window.location.reload()
										})
									}else {
										bootbox.alert(msg)
									}
								}
							})
						});//end submit
					})
				}
			})
		}); //end click

		// $(document).on('keyup',function(e){
		// 	if(e.keyCode=='78'){
		// 		$('#new_drug_modal').modal('show')
		// 	}
		// })


    	$(document).ready(function(){

					$('#print').on('click', function(event) {
						event.preventDefault();
						print_popup('print_inventory.php');
					});


					$('#new_drug_modal').on('shown.bs.modal', function(event) {
						event.preventDefault();
						$('#drug_name').focus()
					});//end modal shown

					$('#new_patient_frm').on('submit', function(event) {
						event.preventDefault();
						bootbox.confirm("Create new folder?",function(r){
							if(r===true){
								$.ajax({
									url: '../serverscripts/admin/Patients/new_patient_frm.php',
									type: 'GET',
									data: $('#new_patient_frm').serialize(),
									success: function(msg){
										if(msg==='save_successful'){
											bootbox.alert("Folder Created Successfully",function(){
												window.location='patient_folder.php'
											})
										}
										else {
											bootbox.alert(msg,function(){
												window.location.reload()
											})
										}
									}
								})
							}
						})

					});//end submit


					$('.table tbody').on('click','.edit', function(event) {
						event.preventDefault();
						 var drug_id=$(this).attr('ID')

						 $.ajax({
							url: '../serverscripts/admin/inventory_edit.php?drug_id='+drug_id,
							type: 'GET',
							success: function(msg){
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
										success: function(msg){
											if(msg==='save_successful'){
												bootbox.alert('Drug Information Updated Successfully',function(){
													window.location.reload()
												})
											}
											else {
												bootbox.alert(msg)
											}
										}
									})//end ajax
								});//end edit form
							}
						 })

					});//end edit function


					$('.table tbody').on('click','.delete', function(event) {
						event.preventDefault();

						var drug_id=$(this).attr('ID')
						bootbox.confirm("Are you sure you want to delete this item?",function(r){
							if(r===true){
								$.ajax({
									url: '../serverscripts/admin/inventory_delete.php?drug_id='+drug_id,
									type: 'GET',
									success: function(msg){
										if(msg==='delete_successful'){
											bootbox.alert("Drug deleted successfully",function(){
												window.location.reload()
											})
										}
										else {
											bootbox.alert(msg)
										}
									}
								})
							}
						})
					});//end delete



    	});


	</script>

</html>
