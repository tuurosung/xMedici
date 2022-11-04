<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$p=new Patient();
	$ward=new Ward();
	$doctor=new Doctor();
	// $s=new Service();
 ?>

		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Admission Requests</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">
						<div class="btn-group">



						</div>


				  </div>
				</div>




					<div class="card mb-3">
						<div class="card-body p-0">
							<ul class="list-group">
								<li class="list-group-item custom-list-item">
									<div class="row" style="font-size:13px">
										<div class="col-md-1">
											Avatar
										</div>
										<div class="col-md-2">
											Patient ID
										</div>
										<div class="col-md-3">
											Full Name
										</div>
										<div class="col-md-2">
											Ward
										</div>
										<div class="col-md-2">
											Ref. Dr.
										</div>
										<div class="col-md-2 text-right">
											Option
										</div>
									</div>
								</li>
							</ul>
							<?php
							$get_patients=mysqli_query($db,"SELECT * FROM admissions WHERE admission_request_status='PENDING' AND  status='active' && subscriber_id='".$active_subscriber."'")  or die(mysqli_error($db));
							$i=1;
							while ($rows=mysqli_fetch_array($get_patients)) {
								$p->patient_id=$rows['patient_id'];
								$p->PatientInfo();
								$othernames=$p->othernames;

								$ward->ward_id=$rows['ward_id'];
								$ward->WardInfo();

								$doctor->doctor_id=$rows['admission_requested_by'];
								$doctor->DoctorInfo();
								?>
								<li class="list-group-item">
									<div class="row" style="font-size:12px">
										<div class="col-md-1">
											<div class="avatar d-flex align-items-center justify-content-center font-weight-bold text-white">
													<?php echo $othernames[0]; ?>
											</div>
										</div>
										<div class="col-md-2">
											<a class="primary-text" style="text-decoration:underline" href="patient_folder.php?patient_id=<?php echo $p->patient_id; ?>"><?php echo $p->patient_id; ?></a>
										</div>
										<div class="col-md-3">
											<p class="montserrat p-0" style="font-weight:500; font-size:11px"><?php echo $p->patient_fullname; ?> </p>
											<p> <span class="mr-2"><?php echo $p->age; ?> </span> | <span class="poppins ml-2" style="text-decoration:italic"><?php echo $p->sex; ?></span></p>
										</div>
										<div class="col-md-2">
											<?php echo $ward->description; ?>
										</div>
										<div class="col-md-2">
											<?php echo $doctor->doctor_fullname; ?>
										</div>
										<div class="col-md-2 text-right">
											<div class="dropdown open">
											  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											    Options
											  </button>
											  <div class="dropdown-menu minioptions b-0 p-0" aria-labelledby="dropdownMenu1">
													<ul class="list-group">
													  <li class="list-group-item accept_admission" data-admission_id="<?php echo $rows['admission_id']; ?>"><i class="fas fa-check-circle text-success mr-2" aria-hidden></i> Accept</li>
													  <li class="list-group-item reject_admission" data-admission_id="<?php echo $rows['admission_id']; ?>"><i class="fas fa-times-circle text-danger mr-2" aria-hidden></i> Reject</li>
													</ul>
											  </div>
											</div>

										</div>
									</div>
								</li>

									<?php
								}
								?>
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
			var admission_id=$(this).data('admission_id');
			$.get('../serverscripts/admin/Wards/accept_admission_modal.php?admission_id='+admission_id,function(msg){
				$('#modal_holder').html(msg)
				$('#accept_admission_modal').modal('show')

				$('#accept_admission_frm').on('submit', function(event) {
					event.preventDefault();
					bootbox.confirm("Confirm Patient Admission?",function(r){
						if(r===true){
							$.ajax({
								url: '../serverscripts/admin/Wards/accept_admission_frm.php',
								type: 'GET',
								data:$('#accept_admission_frm').serialize(),
								success:function(msg){
									if(msg==='save_successful'){
										bootbox.alert('Patient admission successful',function(){
											window.location.reload();
										})
									}else {
										bootbox.alert(msg)
									}
								}
							})//end ajax
						}//end if
					})
				});
			})
		});


	</script>

</html>