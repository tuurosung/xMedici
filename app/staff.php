<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<!-- Initialise Classes -->
<?php
$staff = new Staff();
?>


<!-- Main Body  -->
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
						<th>Full Name</th>
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
						$staff_role=$rows['role'];
						$role=$staff->hr_categories[$staff_role];
					?>



						<tr>
							<td><?php echo $i++; ?></td>
							<td>
								<a href="staff_profile.php?staff_id=<?php echo $rows['staff_id']; ?>">
									<?php echo $rows['surname'] .' '. $rows['othernames']; ?>
								</a>
							</td>
							<td><?php echo $rows['staff_rank']; ?></td>
							<td><?php echo $rows['phone_number']; ?></td>
							<td><?php echo $role; ?></td>

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

	<!-- Main Body -->



	<?php
		include_once ('../Includes/modals/staff/newStaff.php');
	?>

	<div id="modal_holder"></div>


	</body>

	<!--   Core JS Files   -->
	<?php require_once '../navigation/footer.php'; ?>
	<script type="text/javascript" src="../Includes/js/staff/staffMain.js"></script>

	</html>