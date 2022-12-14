<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
  require_once '../serverscripts/Classes/Admissions.php';
 ?>
<?php
$p=new Patient();
$ward=new Ward();
$doctor=new Doctor();
$admission=new Admission();
$v=new Visit();
 ?>

		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">All Appointments</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">
            <button type="button" class="btn btn-primary btn-rounded">
              <i class="fas fa-print mr-2" aria-hidden></i>
              Print
            </button>
				  </div>
				</div>



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



        <h5 class="montserrat font-weight-bold  my-5">All Appointments</h5>


							<div class="card mb-3 grey lighten-4 font-weight-bold">
                    <div class="card-body">
                      <div class="row" style="font-size:13px">
    										<div class="col-md-2">
    											Adm Date
    										</div>
    										<div class="col-md-3">
    											Full Name
    										</div>
    										<div class="col-md-2">
    											Ward
    										</div>
    										<div class="col-md-1">
    										Bed
    										</div>
    										<div class="col-md-2 text-right">
    										Days
    										</div>
    										<div class="col-md-2 text-right">
    											Option
    										</div>
    									</div>
                    </div>
                  </div>

							<?php
					    $visits=$v->All();
							$i=1;
							foreach ($visits as $rows) {

                $visit_id=$rows['visit_id'];
								$p->patient_id=$rows['patient_id'];

                $v->VisitInfo($visit_id);

								$p->PatientInfo();
								$othernames=$p->othernames;

								$ward->ward_id=$rows['ward_id'];
								$ward->WardInfo();

								$doctor->doctor_id=$rows['admission_requested_by'];
								$doctor->DoctorInfo();

								$bed_id=$rows['bed_id'];
								$ward->BedInfo($bed_id);

                $admission->admission_id=$rows['admission_id'];
                $admission->AdmissionInfo();
								?>
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="row" style="font-size:13px">
                      <div class="col-md-2">
                        <?php echo $rows['admission_date']; ?>
                      </div>
  										<div class="col-md-3">
  											<p class="montserrat p-0" style="font-weight:600; font-size:12px"><?php echo $p->patient_fullname; ?> </p>
  											<p class="d-none" style="font-size:11px"> <span class="mr-2"><?php echo $p->age; ?> </span> | <span class="poppins ml-2" style="text-decoration:italic"><?php echo $p->sex; ?></span></p>
  										</div>
  										<div class="col-md-2">
  											<?php echo $ward->description; ?>
  										</div>
  										<div class="col-md-1">
  										<?php echo $ward->bed_description; ?>
  										</div>
  										<div class="col-md-2 text-italic text-right">
  										<?php echo $admission->days_on_admission; ?> Days
  										</div>
  										<div class="col-md-2 text-right">
  											<a  href="inpatientmanagement.php?admission_id=<?php echo $rows['admission_id']; ?>" class="btn btn-primary btn-sm">Ward Mgt.</a>
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
