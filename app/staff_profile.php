<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<style type="text/css">
	.noborders td,
	.table th {
		border-top: none;
	}
</style>

<!-- Initialize Classes -->
<?php
$staff = new Staff();

$staff_id = clean_string($_GET['staff_id']);
$staff->staff_id = $staff_id;
$staff->StaffInfo();
?>


<div class="row mb-5">
	<div class="col-4">
		<h4 class="titles montserrat">Staff Profile</h4>
	</div>
	<div class="col-8 text-right">
		<div class="dropdown">
			<button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-plus mr-3  "></i>
				New
			</button>
			<div class="dropdown-menu b-0 p-0" aria-labelledby="triggerId">				
				<ul class="list-group">
					<li class="list-group-item">Leave Request</li>
					<li class="list-group-item">Query</li>
					<li class="list-group-item">Create Salary</li>
				</ul>				
			</div>
		</div>
	</div>
</div>


<div class="card mb-5">
	<div class="card-body">
		<div class="row">
			<div class="col-md-4 mb-4 mb-md-0 text-center" style="border-right:dashed 1px #000">
				<img src="../images/stethoscope.png" class="" style="width: 150px" alt=""> <br><br><br>
				<button type="button" class="btn btn-primary btn-sm mt-5"><i class="fas fa-pen mr-3  "></i> Edit</button>
			</div>
			<div class="col-md-4 mb-4 mb-md-0">
				<h6>Personal Information</h6>
				<hr style="border-top:solid 2px #000; width: 30%;" align="left">
				<table class="table noborders">
					<tbody>
						<tr>
							<td style="font-size: 15px !important;">Fullname : </td>
							<td style="font-size: 15px !important;  font-weight:600;"> <?php echo $staff->full_name; ?> </td>
						</tr>
						<tr>
							<td>Address : </td>
							<td> <?php echo $staff->address; ?> </td>
						</tr>
						<tr>
							<td>Phone # : </td>
							<td> <?php echo $staff->phone; ?> </td>
						</tr>
						<tr>
							<td>Email : </td>
							<td> <?php echo $staff->email; ?> </td>
						</tr>
						<tr>
							<td>Role : </td>
							<td> <?php echo $staff->hr_categories[$staff->role]; ?> </td>
						</tr>
						<tr>
							<td style="width: 140px;">Specialisation : </td>
							<td> <?php echo $staff->rank; ?> </td>
						</tr>
					</tbody>
				</table>

			</div>
			<div class="col-md-4 mb-4 mb-md-0">

				<h6>Emergency Contact</h6>
				<hr style="border-top:solid 2px #000; width: 30%;" align="left">
				<table class="table noborders">
					<tbody>
						<tr>
							<td>Contact Person : </td>
							<td> <?php echo $staff->emergency_contact_person; ?> </td>
						</tr>
						<tr>
							<td style="width: 140px;">Phone # : </td>
							<td> <?php echo $staff->emergency_contact; ?> </td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>







<!-- Main Body -->

<!-- Pills navs -->
<ul class="nav nav-pills xmedici_pills mb-5" id="ex1" role="tablist">
	<li class="nav-item" role="presentation">
		<a class="nav-link active" id="ex3-tab-1" data-toggle="pill" href="#ex3-pills-1" role="tab" aria-controls="ex3-pills-1" aria-selected="true">Dashboard</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link" id="ex3-tab-2" data-toggle="pill" href="#ex3-pills-2" role="tab" aria-controls="ex3-pills-2" aria-selected="false">Attendance</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link" id="ex3-tab-3" data-toggle="pill" href="#ex3-pills-3" role="tab" aria-controls="ex3-pills-3" aria-selected="false">Payslips</a>
	</li>
</ul>
<!-- Pills navs -->

<!-- Pills content -->
<div class="tab-content" id="ex2-content">
	<div class="tab-pane fade show active" id="ex3-pills-1" role="tabpanel" aria-labelledby="ex3-tab-1">

		<div class="card">
			<div class="card-body" id="data_holder">
				<h6 class="montserrat font-weight-bold mb-5"> Dashboard </h6>





			</div>
		</div>

	</div>
	<div class="tab-pane fade" id="ex3-pills-2" role="tabpanel" aria-labelledby="ex3-tab-2">

		<div class="card">
			<div class="card-body" id="data_holder">
				<h6 class="montserrat font-weight-bold mb-5">Attendance</h6>
				<!-- <hr class="hr"> -->




			</div>
		</div>

	</div>
	<div class="tab-pane fade" id="ex3-pills-3" role="tabpanel" aria-labelledby="ex3-tab-3">

		<div class="card">
			<div class="card-body" id="data_holder">
				<h6 class="montserrat font-weight-bold mb-5">Payslip</h6>
				<!-- <hr class="hr"> -->




			</div>
		</div>

	</div>
</div>
<!-- Pills content -->




<!-- Main Body -->



<div id="modal_holder"></div>


</body>

<!--   Core JS Files   -->
<?php require_once '../navigation/footer.php'; ?>



</html>