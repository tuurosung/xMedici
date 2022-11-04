<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$p=new Patient();
	// $s=new Service();
 ?>

		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Shift Manager</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">

				  </div>
				</div>




				<div class="card">
					<div class="card-body" style="min-height:500px">

						<h6 class="montserrat font-weight-bold">Active Shifts</h6>
						<hr class="hr">

						<table class="table table-condensed datatables">
							<thead class="grey lighten-3 font-weight-bold">
								<tr>
									<th>#</th>
									<th>Date</th>
									<th>Staff Name</th>
									<th>Shift Type</th>
									<th>Time In</th>
									<th>Time Out</th>
									<th></th>
								</tr>
							</thead>
							<tbody>

						<ul class="list-group">


							<?php
								$i=1;

								$get_active_shifts=mysqli_query($db,"SELECT * FROM staff_attendance WHERE subscriber_id='".$active_subscriber."'  AND status='active'") or die(mysqli_error($db));
								while ($shifts=mysqli_fetch_array($get_active_shifts)) {
									$user_id=$shifts['staff_id'];
									$user_prefix=substr($user_id,0,2);
									switch ($user_prefix) {
										case 'NR':
											$nurse=new Nurse();
											$nurse->nurse_id=$user_id;
											$nurse->NurseInfo();
											$user_fullname=$nurse->nurse_fullname;
											break;

										case 'DR':
											$nurse=new Nurse();
											$doctor=new Doctor();
											$doctor->doctor_id=$user_id;
											$doctor->DoctorInfo();
											$user_fullname=$doctor->doctor_fullname;
											break;

										case 'HR':
											$hr=new Hr();
											$hr->hr_id=$user_id;
											$hr->HrInfo();
											$user_fullname=$hr->hr_fullname;
											break;

										case 'AC':
											$accountant=new Accountant();
											$accountant->accountant_id=$user_id;
											$accountant->AccountantInfo();
											$user_fullname=$accountant->accountant_fullname;
											break;

										default:
											// code...
											break;
									}

									?>
									<tr class="" style="font-size:11px">
										<td><?php echo $i++; ?></td>
										<td><?php echo $shifts['log_date']; ?></td>
										<td><?php echo $user_fullname; ?></td>
										<td><?php echo $shifts['shift_type']; ?></td>
										<td><?php echo $shifts['time_in']; ?></td>
										<td><?php echo $shifts['time_out']; ?></td>
										<td class="text-right">
												<div class="dropdown open">
												<button type="button" class="btn btn-primary btn-sm" aria-hidden id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Options</button>
												<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
												 <ul class="list-group minioptions">


													 <li class="list-group-item shift_over" id="<?php echo $shifts['staff_id']; ?>" data-sn="<?php echo $shifts['sn']; ?>"> <i class="far fa-clock mr-2"></i> Shift Over</li>
												 </ul>
												</div>
											</div>
										</td>
									</tr>

									<?php
								}
							 ?>


						</ul>
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
		$('#cashier_nav').addClass('active')
		$('#cashier_submenu').addClass('show')
		$('#billing_li').addClass('font-weight-bold')

		$('.table tbody').on('click','.shift_over', function(event) {
			event.preventDefault();
			var staff_id=$(this).attr('ID')
			var sn=$(this).data('sn')
			bootbox.confirm("Do you want to end this shift?",function(r){
				if(r===true){
					$.get('../serverscripts/admin/Users/shift_over.php?staff_id='+staff_id+'&sn='+sn,function(msg){
						bootbox.alert(msg)
					})
				}//end if
			})
		});


	</script>

</html>
