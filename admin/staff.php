<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
$staff = new Staff();
?>

<main class="py-3 mx-lg-5">
	<div class="container-fluid mt-2">

		<div class="row mb-5">
			<div class="col-8">
				<h4 class="titles montserrat">Human Resource Manager</h4>
				<p>Manage Teams and Payroll Information</p>
			</div>
			<div class="col-2 text-right mb-5">
				<div class="d-flex align-items-center" style="width:100%">
					<input type="text" name="search_term" class="form-control" placeholder="search for staff" value="">
				</div>
			</div>
			<div class="col-2 text-right">
				<button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#new_staff_modal"><i class="fas fa-plus mr-3"></i> New Staff</button>
			</div>
		</div>
	</div>

	<div class="card mb-5">
		<div class="card-body">
			<div class="row">
				<div class="col-2">
					<label for="">Designation</label>
					<select class="browser-default custom-select" name="filter_category">
						<?php
						$get_cats = $staff->HrCategories();
						foreach ($get_cats as $cats) {
						?>
							<option value="<?php echo $cats['alias']; ?>"><?php echo $cats['description']; ?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</div>


	<div class="card">
		<div class="card-body" id="data_holder">
			<h6 class="montserrat font-weight-bold mb-5">All Staff</h6>
			<!-- <hr class="hr"> -->

			<table class="table datatables table-condensed">
				<thead class="grey lighten-4">
					<tr>
						<th>#</th>
						<th>Surname</th>
						<th>Othernames</th>
						<th>Designation / Rank</th>
						<th>Phone Number</th>
						<th>Role</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$allstaff = $staff->AllStaff();
					foreach ($allstaff as $rows) {
						switch ($rows['category']) {
							case 'admin_hr':
								$category = 'Human Resource';
								$rank = 'Senior HR';
								break;
							case 'doctor':
								$category = 'Doctor';
								$rank = $rows['specialisation'];
								break;
							case 'nurse':
								$category = 'Nurse';
								$rank = $rows['nurse_rank'];
								break;
							case 'laboratory':
								$category = 'Lab. Scientist';
								break;
							case 'accountant':
								$category = 'Accountant';
								$rank = $rows['accountant_rank'];
								break;
							case 'pharmacy':
								$category = 'Pharmacist';
								break;

							default:
								$category = '';
								break;
						}
						$staff_id = $rows['staff_id'];
						$get_details = mysqli_query($db, "SELECT * FROM staff WHERE staff_id='" . $staff_id . "' AND subscriber_id='" . $active_subscriber . "'");
						$info = mysqli_fetch_array($get_details);

						$username = $info['username'];
						$password = $info['password'];
						$email = $info['email'];
						$role = $info['role'];
						$auth = $info['auth'];

						// $update=mysqli_query($db,"UPDATE staff SET email='".$email."', role='".$role."',auth='".$auth."',username='".$username."',password='".$password."' WHERE staff_id='".$staff_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
					?>



						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $rows['surname']; ?></td>
							<td><?php echo $rows['othernames']; ?></td>
							<td><?php echo $rank; ?></td>
							<td><?php echo $rows['phone_number']; ?></td>
							<td><?php echo $category; ?></td>

							<td class="text-right">
								<div class="dropdown open">
									<button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Options
									</button>
									<div class="dropdown-menu p-0 b-0  minioptions" aria-labelledby="dropdownMenu1">
										<ul class="list-group">
											<li class="list-group-item edit" data-staff_id="<?php echo $rows['staff_id']; ?>"><i class="fas fa-pencil-alt mr-3" aria-hidden></i> Edit Info</li>
											<li class="list-group-item delete" data-staff_id="<?php echo $rows['staff_id']; ?>"><i class="fas fa-trash-alt mr-3" aria-hidden></i> Delete User</li>
											<li class="list-group-item"><i class="fas fa-arrow-right mr-3" aria-hidden></i> Staff Portal</li>
										</ul>
									</div>
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




	<!-- Modal -->
	<div class="modal fade" id="new_staff_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">New Staff Registration</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="new_staff_frm">
					<div class="modal-body">


						<div class="row">
							<div class="col-md-6">
								<label for="">Title</label>
								<div class="form-group">
									<select class="custom-select browser-default" name="title">
										<option value="Dr.">Dr.</option>
										<option value="Mr.">Mr.</option>
										<option value="Mrs.">Mrs.</option>
										<option value="Miss.">Miss.</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<label for="">Role / Department</label>
								<div class="form-group">
									<select class="browser-default custom-select" name="role">
										<?php
										$get_cats = $staff->HrCategories();
										foreach ($get_cats as $cats) {
										?>
											<option value="<?php echo $cats['alias']; ?>"><?php echo $cats['description']; ?></option>
										<?php
										}
										?>
									</select>
								</div>
							</div>
						</div>

						<div class="row">

							<div class="col-md-12">
								<div class="form-group">
									<label>Specialisation</label>
									<select class="custom-select browser-default" id="rank" name="rank" required>
										<option value="General Physician">General Physician</option>
										<option value="Physiotherapy">Physiotherapy</option>
										<option value="Dermatology">Dermatology</option>
										<option value="Gynecology">Gynecology</option>
										<option value="Oncology">Oncology</option>
										<option value="Neurology">Neurology</option>
										<option value="Physician Assistant">Physician Assistant</option>
										<option value="Nurse Consultant">Nurse Consultant</option>
										<option value="Staff Nurse">Staff Nurse</option>
										<option value="Senior Staff Nurse">Senior Staff Nurse</option>
										<option value="Nursing Officer">Nursing Officer</option>
										<option value="DDNS">DDNS</option>
									</select>
								</div>
							</div>

						</div>

						<hr>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Surname</label>
									<input type="text" class="form-control" id="surname" name="surname" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Othernames</label>
									<input type="text" class="form-control" id="othernames" name="othernames" required>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Phone Number</label>
									<input type="text" class="form-control" id="phone_number" name="phone_number" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Address</label>
									<input type="text" class="form-control" id="address" name="address" required>
								</div>
							</div>
						</div>


						<div class="row gx-lg-5">

							<div class="col-md-6 mb-5 mb-lg-0">
								<div class="form-group">
									<label for="">Emergency Contact Person</label>
									<input type="text" name="emergency_contact_person" id="" class="form-control" placeholder="">
								</div>
							</div>

							<div class="col-md-6 mb-5 mb-lg-0">
								<div class="form-group">
									<label for="">Emergency Contact</label>
									<input type="text" name="emergency_contact" id="" class="form-control" placeholder="">
								</div>
							</div>

						</div>



						<hr>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Username</label>
									<input type="text" class="form-control" id="username" name="username" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Password</label>
									<input type="password" class="form-control" id="password" name="password" required>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary"><i class="fas fa-check mr-3" aria-hidden="true"></i> Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>






	<div id="modal_holder"></div>


	</body>

	<!--   Core JS Files   -->
	<?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">
		$('#new_staff_frm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/Staff/new.php',
				type: 'GET',
				data: $('#new_staff_frm').serialize(),
				success: function(msg) {
					if (msg === 'save_successful') {
						bootbox.alert("New Staff Added Successfully", function() {
							window.location.reload()
						})
					} else {
						bootbox.alert(msg)
					}
				}
			}) //end ajax
		}); // new staff frm on submit end



		$('.table tbody').on('click', '.edit', function(event) {
			event.preventDefault();
			var user_id = $(this).attr('ID')
			$.get('../serverscripts/admin/Users/edit_user_modal.php?user_id=' + user_id, function(msg) {
				$('#modal_holder').html(msg)
				$('#edit_user_modal').modal('show')

				$('#edit_user_frm').on('submit', function(event) {
					event.preventDefault();
					$.ajax({
						url: '../serverscripts/admin/Users/edit_user_frm.php',
						type: 'GET',
						data: $('#edit_user_frm').serialize(),
						success: function(msg) {
							if (msg === 'update_successful') {
								bootbox.alert("User information updated successfully", function() {
									window.location.reload()
								})
							} else {
								bootbox.alert(msg)
							}
						}
					}) //end ajax
				});
			})
		});


		$('table tbody').on('click', '.delete', function(event) {
			event.preventDefault();
			var staff_id = $(this).data('staff_id')
			bootbox.confirm("Do you want to delete this account?", function(r) {
				if (r === true) {
					$.get('../serverscripts/admin/Staff/delete.php?staff_id=' + staff_id, function(msg) {
						if (msg === 'delete_successful') {
							bootbox.alert("Success. Account deleted", function() {
								window.location.reload()
							})
						} else {
							bootbox.alert(msg)
						}
					}) //end get
				} //end if
			}) //end confirm
		});
	</script>

	</html>