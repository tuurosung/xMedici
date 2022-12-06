<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$p=new Patient();
	$ward=new Ward();
	$doctor=new Doctor();
	$visit=new Visit();
	// $s=new Service();
 ?>

		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Discharged Patients</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">
						<div class="btn-group">



						</div>


				  </div>
				</div>




					<div class="card mb-3">
						<div class="card-body">
							<h6 class="font-weight-bold montserrat">List Of Discharged Patients</h6>
							<hr class="hr">

							<table class="table table-condensed datatables">
							  <thead class="grey lighten-4">
							    <tr>
							      <th>#</th>
							      <th>Date</th>
							      <th>Patient ID</th>
							      <th>Name</th>
							      <th>Doctor</th>
							      <th>Notes</th>
							    </tr>
							  </thead>
							  <tbody>


							<?php
							$get_patients=mysqli_query($db,"SELECT *
																																		FROM 	admission_discharges
																																		WHERE
																																					discharge_status='discharged' AND
																																					status='active' AND
																																					subscriber_id='".$active_subscriber."'
																																		ORDER BY
																																			discharge_date
																													")  or die(mysqli_error($db));
							$i=1;
							while ($rows=mysqli_fetch_array($get_patients)) {
								$p->patient_id=$rows['patient_id'];
								$p->PatientInfo();
								$othernames=$p->othernames;

								$ward->ward_id=$rows['ward_id'];
								$ward->WardInfo();

								$doctor->doctor_id=$rows['discharging_doctor'];
								$doctor->DoctorInfo();

								$patient_id=$rows['patient_id'];
								$visit_id=$rows['visit_id'];

								$bill_to_pay=$visit->VisitBalance($patient_id,$visit_id);
								?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo $rows['discharge_date']; ?></td>
									<td><?php echo $p->patient_id; ?></td>
									<td><?php echo $p->patient_fullname; ?></td>
									<td><?php echo $doctor->doctor_fullname; ?></td>
									<td><?php echo $rows['discharge_notes']; ?></td>
								</tr>

									<?php
								}
								?>

							</tbody>
						</table>
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

		$('.accept_discharge').on('click', function(event) {
			event.preventDefault();
			var admission_id=$(this).data('admission_id');
			$.get('../serverscripts/admin/Wards/accept_discharge.php?admission_id='+admission_id,function(msg){
				if(msg==='discharge_successful'){
					bootbox.alert("Discharge Successful",function(){
						window.location.reload();
					})
				}else {
					bootbox.alert(msg)
				}

			})
		});


	</script>

</html>
