<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	// ob_clean();

	if(isset($_SESSION['new_patient_id'])){
		$patient_id=clean_string($_SESSION['new_patient_id']);
	}elseif (isset($_GET['patient_id'])) {
		$patient_id=clean_string($_GET['patient_id']);
	}else {
		?>
		<script type="text/javascript">
			window.location='findpatient.php';
		</script>
		<?php
	}



	$p=new Patient();
	$doctor=new Doctor();
	$visit=new Visit();
	$p->patient_id=$patient_id;



	$status=$p->PatientStatus();
	if($status=='deleted'){
		header('location: patients.php');
	}else {
		$p->PatientInfo();
	}
 ?>

 <style media="screen">
 	#topbar{
		background: rgb(12,5,143);
		background: linear-gradient(87deg, rgba(12,5,143,1) 0%, rgba(26,26,198,1) 35%, rgba(0,212,255,1) 100%);
	}
 </style>



		<main class="py-1 mx-lg-5 main" style="">
			<div class="container-fluid pt-4">

				<?php
					if ($status=='deceased') {
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

				<div class="row">
					<div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Patient Folder </h4>
					</div>
					<div class="col-md-6 text-right mb-5 <?php if($status=='deceased'){ echo 'd-none'; } ?>">
						<div class="btn-group">
							<div class="dropdown ope n">
								<button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Request Service
								</button>
								<div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
									<ul class="list-group">
										<li class="list-group-item" data-toggle="modal" data-target="#new_visit_modal">Out-Patient Service</li>
										<li class="list-group-item" data-toggle="modal" data-target="#">Walk-In Service</li>
										<li class="list-group-item" data-toggle="modal" data-target="#">Antenatal Service</li>
										<li class="list-group-item" data-toggle="modal" data-target="#">Dental Service</li>
										<li class="list-group-item" data-toggle="modal" data-target="#">Book Specialist</li>
										<li class="list-group-item" id="">Emergency Service</li>
									</ul>
								</div>
							</div>
						</div>

						<button type="button" class="btn btn-primary btn-rounded" data-toggle='modal' data-target="#edit_patient_modal"><i class="fas fa-pencil-alt mr-2" aria-hidden></i>Edit Info</button>

						<button type="button" class="btn danger-color-dark btn-rounded" id="delete_patient_btn" ><i class="far fa-trash-alt mr-2" aria-hidden></i>Delete</button>


					</div>
				</div>


				<div class="row">
				  <div class="col-3" style="border-right:solid 1px #000">
						<h4 class="montserrat font-weight-bold"><?php echo $p->patient_fullname; ?></h4>
						<p>Phone : <span style="font-weight:600"><?php echo $p->phone_number; ?></span></p>
				  </div>
					<div class="col-2" style="border-right:solid 1px #000">
						<p>Sex : <span style="font-weight:600"><?php echo $p->sex_description; ?></span></p>
						<p>Age :  <span style="font-weight:600"><?php echo $p->age; ?> </span></p>
					</div>
					<div class="col-2" style="border-right:solid 1px #000">
						<p><span style="font-weight:600"><?php echo $p->hse_address; ?></span></p>
						<p><span style="font-weight:600"><?php echo $p->town; ?>, <?php echo $p->region; ?> </span></p>
					</div>
				</div>



				<div class="mt-5">
						<h6 class="font-weight-bold poppins mb-4">Appointment History</h6>

						<div class="mt-3 font-weight-bold">

							<div class="row mb-3 text-muted" style="font-size:13px">
								<div class="col-md-2">
									Date
								</div>
								<div class="col-md-3">
									Doctor
								</div>
								<div class="col-md-2">
									Major Complaint
								</div>
								<div class="col-md-3">
									Diagnosis
								</div>
								<div class="col-md-2 text-right">
									Option
								</div>
							</div>
							<?php
								$i=1;
								$get_visits=mysqli_query($db,"SELECT *
																																	FROM visits
																																	WHERE
																																		patient_id='".$patient_id."' AND
																																		subscriber_id='".$active_subscriber."' AND
																																		(status='active' OR status='discharged')
																																	ORDER BY
																																		visit_date DESC
																												") or die(mysqli_error($db));
								while ($rows=mysqli_fetch_array($get_visits)) {
									switch ($rows['visit_type']) {
										case 'new_visit':
											$visit_type='New Visit';
											break;
										case 'review':
											$visit_type='Review';
											break;

										default:
											// code...
											break;
									}
									$doctor_id=$rows['doctor_id'];
									$doctor->doctor_id=$doctor_id;
									$doctor->DoctorInfo();

									$visit_id=$rows['visit_id'];
									$visit->VisitInfo($visit_id);
									$primary_diagnosis=$visit->primary_diagnosis;
									?>
									<div class="card mb-3" style="border-left:solid 4px <?php if($rows['status']=='active'){ echo '#0d47a1';}elseif($rows['status']=='discharged'){echo '#cc0000';} ?>">
										<div class="card-body">
											<div class="row" style="font-size:13px; font-weight:500">
												<div class="col-md-2">
													<?php echo $rows['visit_date']; ?>
												</div>
												<div class="col-md-3">
													<?php echo $doctor->doctor_fullname; ?>
													<p class="text-muted poppins" style="font-size:11px"><?php echo $doctor->specialisation; ?></p>
												</div>
												<div class="col-md-2">
													<?php echo $rows['major_complaint']; ?>
												</div>
												<div class="col-md-4">
													<p><?php echo $visit->Diagnosis($primary_diagnosis); ?> <?php echo $visit->primary_diagnosis; ?></p>
													<p class="text-muted poppins" style="font-size:11px"><?php echo $visit->secondary_diagnosis; ?></p>

												</div>
												<div class="col-md-1 text-right">
													<a href="singlevisit.php?visit_id=<?php echo $rows['visit_id']; ?>" class="">
															<!-- <i class="fas fa-chevron-right text-primary fa-2x" aria-hidden></i> -->
															<img src="../images/chevronright.svg" alt=""  style="width:20px; color:#f4f5f7">
													</a>
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
		</main>





<div id="modal_holder">

</div>

<div id="edit_patient_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="">
    <div class="modal-content">
			<form id="edit_patient_frm">
      <div class="modal-body">
					<h5 class="font-weight-bold montserrat">Edit Patient Information</h5>
					<hr class="hr">


					<!-- Pills navs -->
							<ul class="nav nav-pills mb-3 xmedici_pills" id="" role="tablist">
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link active"
							      id="basic_info_tab"
							      data-toggle="pill"
							      href="#basic_info"
							      role="tab"
							      aria-controls="basic_info"
							      aria-selected="true"
							      >Basic Information</a
							    >
							  </li>
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link"
							      id="contactinfo_tab"
							      data-toggle="pill"
							      href="#contactinfo"
							      role="tab"
							      aria-controls="contactinfo"
							      aria-selected="false"
							      >Contact Info</a
							    >
							  </li>
							  <li class="nav-item" role="presentation">
							    <a
							      class="nav-link"
							      id="relativeinfo_tab"
							      data-toggle="pill"
							      href="#relativeinfo"
							      role="tab"
							      aria-controls="relativeinfo"
							      aria-selected="false"
							      >Relatives & Payment </a
							    >
							  </li>
							</ul>
							<!-- Pills navs -->

							<!-- Pills content -->
							<div class="tab-content" id="ex1-content">
							  <div  class="tab-pane fade show active pt-4"   id="basic_info"    role="tabpanel"    aria-labelledby="basic_info_tab" >

									<div class="d-none">
										<input type="text" name="patient_id" value="<?php echo $patient_id; ?>">
									</div>
									<div class="row poppins">
									  <div class="col-md-6">
									    <div class="form-group">
												<label for="">Surname</label>
												<input type="text" class="form-control" name="surname" id="surname" value="<?php echo $p->surname; ?>">
									    </div>
									  </div>
									  <div class="col-md-6">
											<div class="form-group">
												<label for="">Othernames</label>
												<input type="text" class="form-control" name="othernames" id="othernames" value="<?php echo $p->othernames; ?>">
									    </div>
									  </div>
									</div>

									<div class="row">
									  <div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="">Date Of Birth</label>
														<input type="text" class="form-control" name="date_of_birth" id="date_of_birth" value="<?php echo $p->date_of_birth; ?>">
											    </div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="">Sex</label>
														<select class="custom-select browser-default" name="sex">
																<option value="male" <?php if($p->sex=='male'){ echo 'selected' ;} ?>>Male</option>
																<option value="female" <?php if($p->sex=='female'){ echo 'selected' ;} ?>>Female</option>
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
															<option value="Christian" <?php if($p->religion=='Christian'){ echo 'selected' ;} ?>>Christian</option>
															<option value="Muslim" <?php if($p->religion=='Muslim'){ echo 'selected' ;} ?>>Muslim</option>
															<option value="Jew" <?php if($p->religion	=='Jew'){ echo 'selected' ;} ?>>Jew</option>
															<option value="Atheist" <?php if($p->religion=='Atheist'){ echo 'selected' ;} ?>>Atheist</option>
															<option value="Other" <?php if($p->religion=='Other'){ echo 'selected' ;} ?>>Other</option>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="">Marital Status</label>
														<select class="custom-select browser-default" name="marital_status" id="marital_status">
															<option value="single" <?php if($p->marital_status=='single'){ echo 'selected' ;} ?>>Single</option>
															<option value="married" <?php if($p->marital_status=='married'){ echo 'selected' ;} ?>>Married</option>
															<option value="divorced" <?php if($p->marital_status=='divorced'){ echo 'selected' ;} ?>>Divorced</option>
															<option value="widowed" <?php if($p->marital_status=='widowed'){ echo 'selected' ;} ?>>Widowed</option>
														</select>
													</div>
												</div>
											</div>
									  </div>
									</div>

							  </div>
							  <div class="tab-pane fade pt-4" id="contactinfo" role="tabpanel" aria-labelledby="contactinfo_tab">

									<div class="row poppins">
										<div class="col-md-6">
											<div class="form-group">
												<label for="">House Address</label>
												<input type="text" class="form-control" name="hse_address" id="hse_address" value="<?php echo $p->hse_address; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-6">
													<div class="form-group">
														<label for="">Town</label>
														<input type="text" class="form-control" name="town" id="town" value="<?php echo $p->town; ?>">
													</div>
												</div>
												<div class="col-6">
													<div class="form-group">
														<label for="">Region</label>
														<input type="text" class="form-control" name="region" id="region" value="<?php echo $p->region; ?>">
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
														<input type="text" class="form-control" name="phone_number" id="phone_number" value="<?php echo $p->phone_number; ?>">
													</div>
												</div>
												<div class="col-6">
													<div class="form-group">
														<label for="">Secondary Phone Number</label>
														<input type="text" class="form-control" name="phone_number2" id="phone_number2" value="<?php echo $p->secondary_phone; ?>">
													</div>
												</div>
											</div>

										</div>
										<div class="col-md-6">
											<div class="row poppins">
												<div class="col-md-6">
													<div class="form-group">
														<label for="">Hometown</label>
														<input type="text" class="form-control" name="hometown" id="hometown" value="<?php echo $p->hometown; ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="">Occupation</label>
														<input type="text" class="form-control" name="ethnicity" id="ethnicity" value="<?php echo $p->occupation; ?>">
													</div>
												</div>
											</div>
										</div>
									</div>

							  </div>
							  <div class="tab-pane fade pt-4" id="relativeinfo" role="tabpanel" aria-labelledby="relativeinfo_tab">

									<div class="row poppins">
										<div class="col-md-6">
											<div class="form-group">
												<label for="">Name Of Nearest Relative</label>
												<input type="text" class="form-control" name="nearest_relative" id="nearest_relative" value="<?php echo $p->nearest_relative; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="">Relative's Phone</label>
												<input type="text" class="form-control" name="relative_phone" id="relative_phone" value="<?php echo $p->relative_phone; ?>">
											</div>
										</div>
									</div>

									<div class="row poppins">
										<div class="col-md-6">
											<div class="form-group">
												<label for="">Payment Mode</label>
												<select class="custom-select browser-default" name="payment_mode" id="payment_mode" value="">
													<option value="cash" <?php if($p->payment_mode=='cash'){ echo 'selected' ;} ?>>Cash</option>
													<option value="nhis" <?php if($p->payment_mode=='nhis'){ echo 'selected' ;} ?>>NHIS</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="">NHIS Number</label>
												<input type="text" class="form-control" name="nhis_number" id="nhis_number" value="<?php echo $p->nhis_number; ?>">
											</div>
										</div>
									</div>

							  </div>
							</div>
							<!-- Pills content -->







      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn primary-color-dark">
					<i class="fas fa-check mr-3"></i>
					Update Patient</button>
      </div>
			</form>
    </div>
  </div>
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
						$get_visits=mysqli_query($db,"SELECT * FROM visits WHERE patient_id='".$patient_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
						while ($visits=mysqli_fetch_array($get_visits)) {
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
						$get_departments=mysqli_query($db,"SELECT * FROM syst_departments") or die(mysqli_error($db));
						while ($departments=mysqli_fetch_array($get_departments)) {
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


<div id="new_visit_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
			<form id="new_visit_frm">
	      <div class="modal-body">
	        <h6>New Patient Visit</h6>
					<hr class="hr">

					<div class="row d-none">
					  <div class="col-md-6">
					    	<input type="text" name="patient_id" value="<?php echo $patient_id; ?>">
					  </div>
					  <div class="col-md-6">

					  </div>
					</div>

					<div class="spacer"></div>
					<div class="row mb-3">
					  <div class="col-md-6">
							<label for="">Visit Type</label>
							<select class="custom-select browser-default" name="visit_type">
								<option value="new_visit">New Visit</option>
								<option value="review">Review</option>
							</select>
					  </div>
					  <div class="col-md-6">
							<label for="">Billing</label>
							<select class="custom-select browser-default" name="service_id">
								<?php
									$get_services=$query=mysqli_query($db,"SELECT * FROM services WHERE subscriber_id='".$active_subscriber."' AND billing_point='appointment' AND status='active'") or die(mysqli_error($db));
									while ($services=mysqli_fetch_array($get_services)) {
										?>
											<option value="<?php echo $services['service_id']; ?>"><?php echo $services['description']; ?></option>
										<?php
									}
								 ?>
							</select>
					  </div>
					</div>

					<div class="form-group">
						<label for="">Major Complaint</label>
						<textarea name="major_complaint" class="form-control"></textarea>
					</div>


	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Create Visit</button>
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

		$('.select2').select2({
        dropdownParent: $('#hidden_menu_modal')
    });




		$('#edit_patient_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm('Update patient info?',function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Patients/edit_patient_frm	.php',
						type: 'GET',
						data:$('#edit_patient_frm').serialize(),
						success:function(msg){
							if(msg==='update_successful'){
								bootbox.alert('Patient info updated successfully',function(){
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

		$('#delete_patient_btn').on('click', function(event) {
			event.preventDefault();
			var patient_id='<?php echo $patient_id; ?>';
			bootbox.confirm('Permanently delete this patient?',function(r){
				if(r===true){
					$.get('../serverscripts/admin/Patients/delete_patient.php?patient_id='+patient_id,function(msg){
						if(msg==='delete_successful'){
							window.location.reload()
						}else {
							bootbox.alert(msg)
						}
					})
				}
			})
		});



		$('#new_visit_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm('Create new OPD Visit?',function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Patients/new_visit_frm.php',
						type: 'GET',
						data:$('#new_visit_frm').serialize(),
						success:function(msg){
							if(msg==='save_successful'){
								bootbox.alert('Visit created successfully',function(){
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



		$('.view_report').on('click', function(event) {
			event.preventDefault();
			var patient_id=$(this).data('patient_id')
			var visit_id=$(this).data('visit_id')
			$.get('../serverscripts/admin/OPD/visit_report.php?patient_id='+patient_id+'&visit_id='+visit_id,function(msg){
				$('#modal_holder').html(msg)
				$('#visit_report_modal').modal('show')
			})
		});





	</script>

</html>
