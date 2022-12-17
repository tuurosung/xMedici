<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$p=new Patient();
	$visit=new Visit();
 ?>


<style media="screen">
	.opd-bg{
		background-color: #4285f4;
		height: 200px;
	}
</style>



				<div class="row">
				  <div class="col-md-8">
						<h4 class="titles montserrat">Out Patients Department</h4>
						<p>Patients in queue for out patient consultation</p>
				  </div>
				  <div class="col-md-2 text-right mb-5">
						<input type="text" name="search_term" class="form-control" placeholder="search for patient" value="">
				  </div>
				</div>

				<section class="my-5 <?php if($_SESSION['access_level'] !='administrator'){ echo 'd-none'; } ?>">
					<h4 class="montserrat font-weight-bold">Overview</h4>
					<div class="row my-3">
					  <div class="col-2">

							<div class="card">
								<div class="card-body">
									<p class="text-muted mb-4">Active Cases</p>
									<h3 class="" style="font-weight:500"><?php echo $visit->active_opd_cases; ?></h3>
								</div>
							</div>
					  </div>
					  <div class="col-2">
							<div class="card">
								<div class="card-body">
									<p class="text-muted mb-4">Discharged</p>
									<h3 class="" style="font-weight:500"><?php echo $visit->discharged; ?></h3>

								</div>
							</div>
					  </div>
					  <div class="col-2">
							<div class="card">
								<div class="card-body">
									<p class="text-muted mb-4">Total Cases</p>
									<h3 class="" style="font-weight:500"><?php echo $visit->total_visit_count; ?></h3>
								</div>
							</div>
					  </div>
					</div>
				</section>

				<section>
					<div class="card">
						<div class="card-body">
							<h5 class="mb-5">Active OPD Visits</h5>

							<table class="table table-condensed datatables">
								<thead>
									<tr>
										<th>#</th>
										<th>Date</th>
										<th>Folder #</th>
										<th>Visit ID</th>
										<th>Patient Name</th>
										<th>Major Complaint</th>
										<th class="text-right">Option</th>
									</tr>
								</thead>
								<tbody>

									<div class="row">

									<?php

									$all_visits=$visit->AllVisits();

									$i=1;
									foreach ($all_visits as $rows) {
										$p->patient_id=$rows['patient_id'];
										$p->PatientInfo();

										$patient_id=$rows['patient_id'];
										$visit_id=$rows['visit_id'];
										// $visit->VisitBalance()
										?>
										<tr>
											<td><?php echo $i++; ?></td>
											<td>
												<?php
													if($rows['visit_date']==$today){
														echo 'Today';
													}else {
														echo $rows['visit_date'];
													}

												?>
											</td>
											<td><?php echo $rows['patient_id']; ?></td>
											<td>
												<u>
													<a href="activate_visit.php?visit_id=<?php echo $rows['visit_id']; ?>&patient_id=<?php echo $rows['patient_id']; ?>">
														<?php echo $rows['visit_id']; ?>
													</a>
												</u>
											</td>
											<td><?php echo $p->patient_fullname; ?></td>
											<td><?php echo $rows['major_complaint']; ?></td>
											<td class="text-right">
												<button class="btn m-0 mt-4 btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													Options
												</button>
												<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
													<ul class="list-group">
														<a href="singlevisit.php?visit_id=<?php echo $rows['visit_id']; ?>" class="list-group-item">
															<i class="fas fa-arrow-alt-circle-right mr-2" aria-hidden></i>
															Open Portal
														</a>
														<li class="list-group-item discharge" data-patient_id="<?php echo $rows['patient_id']; ?>" data-visit_id="<?php echo $rows['visit_id']; ?>">
															<i class="fas fa-arrow-alt-circle-left mr-2" aria-hidden></i>
															Discharge
														</li>
														<li class="list-group-item"><i class="fas fa-ban mr-2" aria-hidden></i> Absconded</li>
														<li  class="list-group-item"><i class="fas fa-bed mr-2" aria-hidden></i> Expired</li>
													</ul>
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
				</section>






			</div>
		</main>




<div id="new_patient_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
			<form id="new_patient_frm">
      <div class="modal-body">
					<h5 class="font-weight-bold montserrat">New Patient Registration</h5>
					<hr class="hr">

					<div class="row">
					  <div class="col-md-6 offset-md-6">
					    	<div class="form-group">
									<label for="">Billing</label>
									<select class="custom-select browser-default" name="service_id">
										<?php
											$get_services=$query=mysqli_query($db,"SELECT * FROM services WHERE subscriber_id='".$active_subscriber."' AND billing_point='registration' AND status='active'") or die(mysqli_error($db));
											while ($services=mysqli_fetch_array($get_services)) {
												?>
													<option value="<?php echo $services['service_id']; ?>"><?php echo $services['description']; ?></option>
												<?php
											}
										 ?>
									</select>
					    	</div>
					  </div>
					</div>

						<h6 class="primary-text montserrat font-weight-bold mb-3">Basic Information</h6>

						<div class="row poppins">
						  <div class="col-md-6">
						    <div class="form-group">
									<label for="">Surname</label>
									<input type="text" class="form-control" name="surname" id="surname" value="">
						    </div>
						  </div>
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Othernames</label>
									<input type="text" class="form-control" name="othernames" id="othernames" value="">
						    </div>
						  </div>
						</div>

						<div class="row">
						  <div class="col-md-6">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Date Of Birth</label>
											<input type="text" class="form-control" name="date_of_birth" id="date_of_birth" value="">
								    </div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Sex</label>
											<select class="custom-select browser-default" name="sex">
													<option value="male">Male</option>
													<option value="female">Female</option>
											</select>
								    </div>
									</div>
								</div>

						  </div>
						  <div class="col-md-6">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Religion</label>
											<select class="custom-select browser-default" name="religion" id="religion">
												<option value="Christian">Christian</option>
												<option value="Muslim">Muslim</option>
												<option value="Jew">Jew</option>
												<option value="Atheist">Atheist</option>
												<option value="Other">Other</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Marital Status</label>
											<select class="custom-select browser-default" name="marital_status" id="marital_status">
												<option value="single">Single</option>
												<option value="married">Married</option>
												<option value="divorced">Divorced</option>
												<option value="widowed">Widowed</option>
											</select>
										</div>
									</div>
								</div>
						  </div>
						</div>

						<h6 class="primary-text montserrat font-weight-bold mb-3 mt-4">Contact Information</h6>

						<div class="row poppins">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">House Address</label>
									<input type="text" class="form-control" name="hse_address" id="hse_address" value="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label for="">Town</label>
											<input type="text" class="form-control" name="town" id="town" value="">
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label for="">Region</label>
											<input type="text" class="form-control" name="region" id="region" value="">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row poppins">
							<div class="col-md-6">
								<div class="row">
								  <div class="col-6">
										<div class="form-group">
											<label for="">Primary Phone Number</label>
											<input type="text" class="form-control" name="phone_number" id="phone_number" value="">
										</div>
								  </div>
								  <div class="col-6">
										<div class="form-group">
											<label for="">Secondary Phone Number</label>
											<input type="text" class="form-control" name="phone_number2" id="phone_number2" value="">
										</div>
								  </div>
								</div>

							</div>
							<div class="col-md-6">
								<div class="row poppins">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Hometown</label>
											<input type="text" class="form-control" name="hometown" id="hometown" value="">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Ethnicity</label>
											<input type="text" class="form-control" name="ethnicity" id="ethnicity" value="">
										</div>
									</div>
								</div>
							</div>
						</div>



						<h6 class="primary-text montserrat font-weight-bold mb-3 mt-4">Relative Information</h6>

						<div class="row poppins">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Name Of Nearest Relative</label>
									<input type="text" class="form-control" name="nearest_relative" id="nearest_relative" value="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Relative's Phone</label>
									<input type="text" class="form-control" name="relative_phone" id="relative_phone" value="">
								</div>
							</div>
						</div>


						<h6 class="primary-text montserrat font-weight-bold mb-3 mt-4">NHIS Info</h6>

						<div class="row poppins">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Payment Mode</label>
									<select class="custom-select browser-default" name="payment_mode" id="payment_mode" value="">
										<option value="cash">Cash</option>
										<option value="nhis">NHIS</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">NHIS Number</label>
									<input type="text" class="form-control" name="nhis_number" id="nhis_number" value="">
								</div>
							</div>
						</div>

      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn primary-color-dark">
					<i class="fas fa-file-alt mr-3"></i>
					Create Folder</button>
      </div>
			</form>
    </div>
  </div>
</div>


<div id="modal_holder">

</div>

</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">
		$('.sidebar-fixed .list-group-item').removeClass('active')
		$('#patients_nav').addClass('active')
		$('#patients_submenu').addClass('show')
		$('#opd_li').addClass('font-weight-bold')

		$('.discharge').on('click', function(event) {
			event.preventDefault();
			var patient_id=$(this).data('patient_id')
			var visit_id=$(this).data('visit_id')
			// alert(patient_id)
			bootbox.confirm('Discharge patient? This action is not reversible',function(r){
				if(r===true){
					$.get('../serverscripts/admin/OPD/discharge_patient.php?visit_id='+visit_id+'&patient_id='+patient_id,function(msg){
						if(msg==='discharge_successful'){
							bootbox.alert('Patient Discharged Successfully',function(){
								window.location.reload()
							})
						}else {
							bootbox.alert(msg)
						}
					})
				}
			})
		});


	</script>


</html>
