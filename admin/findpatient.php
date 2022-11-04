<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$p=new Patient();
	// $s=new Service();
 ?>

		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row mb-5">
				  <div class="col-6">
						<h4 class="montserrat titles" >Patient Folders</h4>
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
						<button  type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#new_patient_modal"><i class="fas fa-user-plus mr-2" aria-hidden></i> New Patient</button>
				  </div>
				</div>




				<div class="card">
					<div class="card-body py-5">

						<div class="row mb-5">
						  <div class="col-4">
						    <h6 class="montserrat font-weight-bold">List Of Patient Folders</h6>
						  </div>
						  <div class="col-4">

						  </div>
						</div>

						<table class="table datatables table-condensed">
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
								$patients=$p->All();

								$i=1;
								foreach ($patients as $rows) {
										$p->patient_id=$rows['patient_id'];
										$p->PatientInfo();
										$othernames=$rows['othernames'];
										?>
										<tr>
								      <td><?php echo $i++; ?></td>
								      <td>
												<a  href="patient_folder.php?patient_id=<?php echo $p->patient_id; ?>" class="font-weight-bold">
													<?php echo $p->patient_id; ?></a>
											</td>
								      <td><?php echo $p->patient_fullname; ?></td>
								      <td><?php echo $p->age; ?></td>
								      <td><?php echo ucfirst($p->sex); ?></td>
								      <td><?php echo $p->phone_number; ?></td>
								      <td><?php echo $p->last_visit; ?></td>
								      <td class="text-right">
												<a class="btn btn-primary btn-sm" href="patient_folder.php?patient_id=<?php echo $p->patient_id; ?>">
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
		</main>



<div id="modal_holder">

</div>



<div id="new_patient_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header px-3">
				<h4 class="modal-title montserrat" id="myModalLabel" style="font-weight:600">New Patient Registration</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
			<form id="new_patient_frm" autocomplete="off">
      <div class="modal-body">


					<section class="px-3 mt-2">


								<div class="row poppins">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Surname</label>
											<input type="text" class="form-control" name="surname" id="surname" value="" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Othernames</label>
											<input type="text" class="form-control" name="othernames" id="othernames" value="" required>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="">Date Of Birth</label>
													<input type="text" class="form-control" name="date_of_birth" id="date_of_birth" value="" required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="">Sex</label>
													<select class="custom-select browser-default" name="sex" required>
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
													<select class="custom-select browser-default" name="religion" id="religion" required>
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
													<select class="custom-select browser-default" name="marital_status" id="marital_status" required>
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

					</section>

					<section class="mt-5 px-3">


								<hr >
								<div class="row poppins mt-5">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">House Address</label>
											<input type="text" class="form-control" name="hse_address" id="hse_address" value="" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-6">
												<div class="form-group">
													<label for="">Town</label>
													<input type="text" class="form-control" name="town" id="town" value="" required>
												</div>
											</div>
											<div class="col-6">
												<div class="form-group">
													<label for="">Region</label>
													<select class="custom-select browser-default" name="region" required>
														<option value="Northern Region">Northern Region</option>
														<option value="Upper East Region">Upper East Region</option>
														<option value="Upper West Region">Upper West Region</option>
														<option value="North East Region">North East Region</option>
														<option value="Savannah Region">Savannah Region</option>
														<option value="Bono East Region">Bono East Region</option>
														<option value="Ahafo Region">Ahafo Region</option>
														<option value="Ashanti Region">Ashanti Region</option>
														<option value="Central Region">Central Region</option>
														<option value="Greater Accra Region">Greater Accra Region</option>
														<option value="Volta Region">Volta Region</option>
														<option value="Western Region">Western Region</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row poppins">
									<div class="col-md-6">
										<div class="row">
											<div class="col-12">
												<div class="form-group">
													<label for="">Primary Phone Number</label>
													<input type="text" class="form-control" name="phone_number" id="phone_number" value="" required>
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
													<label for="">Occupation</label>
													<select class="custom-select browser-default" name="occupation">
														<?php
															$occupation_array=[
																'Nurse',
																'Teacher',
																'Trader',
																'Doctor',
																'Pharmacists',
																'Administrator',
																'Civil Servant',
																'Engineer',
																'Other',
															];
															foreach ($occupation_array as $occupation) {
																?>
																<option value="<?php echo $occupation; ?>"><?php echo $occupation; ?></option>
																<?php
															}
														 ?>

													</select>
												</div>
											</div>
										</div>
									</div>
								</div>




					</section>


					<section class="mt-5 px-3">
								<hr>
								<div class="row poppins">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Name Of Nearest Relative</label>
											<input type="text" class="form-control" name="nearest_relative" id="nearest_relative" value="" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Relative's Phone</label>
											<input type="text" class="form-control" name="relative_phone" id="relative_phone" value="" required>
										</div>
									</div>
								</div>

								<h6 class="primary-text montserrat font-weight-bold mb-3 mt-4">NHIS Info</h6>

								<div class="row poppins">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Payment Mode</label>
											<select class="custom-select browser-default" name="payment_mode" id="payment_mode" value="" required>
												<option value="cash">Cash</option>
												<option value="nhis">NHIS</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">NHIS Number</label>
											<input type="text" class="form-control" name="nhis_number" id="nhis_number" value="N/A" required>
										</div>
									</div>
								</div>

					</section>




      </div>
      <div class="modal-footer px-3">
				<button type="button" class="btn btn-white" data-dismiss="modal"><i class="fas fa-times mr-2"></i> Cancel</button>
				<button type="submit" class="btn primary-color-dark">
					<i class="fas fa-file-alt mr-3"></i>
					Create Folder</button>
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

		$('#findpatientsearch').on('keyup', function(event) {
			event.preventDefault();
			var search_term=$(this).val();
			$.get('../serverscripts/admin/Patients/findpatientsearch.php?search_term='+search_term,function(msg){
				$('#data_holder').html(msg)
			})
		})

		$('#new_patient_modal').on('shown.bs.modal', function(event) {
			event.preventDefault();
			$('#surname').focus();
		});

		if($('#payment_mode').val()=='cash'){
			$('#nhis_number').val('N/A')
			$('#nhis_number').attr('readonly','readonly')
		}else {
			$('#nhis_number').val('')
			$('#nhis_number').attr('readonly',false)
		}



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
								bootbox.alert("Success. Folder Created Successfully",function(){
									window.location='patient_folder.php'
								})
							}
							else {
								bootbox.alert(msg)
							}
						}
					})
				}
			})

		});//end submit

	</script>

</html>
