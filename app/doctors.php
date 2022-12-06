<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
		$doctor=new Doctor();
 ?>

<main class="py-3 mx-lg-5">
	<div class="container-fluid mt-2">

		<div class="row mb-5">
		  <div class="col-md-6">
		    <h4 class="titles montserrat">Doctors</h4>
		  </div>
		  <div class="col-md-6 text-right">
				<button type="button" name="button" class="btn btn-primary btn-rounded" id="" data-toggle="modal" data-target="#new_account_modal">
					<i class="fas fa-plus mr-3"></i>
					New Doctor
				</button>
		  </div>
		</div>

				<div class="card">
					<div class="card-body p-0">


				<li class="list-group-item custom-list-item">
					<div class="row">
					  <div class="col-md-1">
							#
					  </div>
					  <div class="col-md-4">
							Full Name
					  </div>
					  <div class="col-md-3">
							Specialisation
					  </div>
					  <div class="col-md-3">
							Phone Number
					  </div>
					</div>
				</li>

		    <?php
		    // require_once '../dbcon.php';
		    $get_items=mysqli_query($db,"SELECT * FROM doctors WHERE subscriber_id='".$active_subscriber."' AND status='active'")  or die(mysqli_error($this->db));
		    $i=1;
		    while ($rows=mysqli_fetch_array($get_items)) {
					$doctor->doctor_id=$rows['doctor_id'];
					$doctor->DoctorInfo();
		      ?>
					<li class="list-group-item hover doctors" id="<?php echo $rows['doctor_id']; ?>">
						<div class="row">
						  <div class="col-md-1">
								<?php echo $i++; ?>
						  </div>
						  <div class="col-md-4">
								<?php echo $doctor->doctor_fullname; ?>
						  </div>
						  <div class="col-md-3">
								<?php echo $doctor->specialisation; ?>
						  </div>
						  <div class="col-md-3">
								<?php echo $doctor->phone_number; ?>
						  </div>
						</div>
					</li>

		      <?php
		    }
		    ?>
			</div>
		</div>

<div class="modal fade" id="new_account_modal">
  <div class="modal-dialog modal-side modal-top-right" role="document">
    <div class="modal-content">
			<form id="new_doctor_frm">
      <div class="modal-body">
					<h6>New Doctor Registration</h6>
					<hr class="hr">

					<div class="form-group">
						<label>Specialisation</label>
						<select class="custom-select browser-default" id="specialisation" name="specialisation" required>
							<option value="General Physician">General Physician</option>
							<option value="Physiotherapy">Physiotherapy</option>
							<option value="Dermatology">Dermatology</option>
							<option value="Gynecology">Gynecology</option>
							<option value="Oncology">Oncology</option>
							<option value="Neurology">Neurology</option>
							<option value="Physician Assistant">Physician Assistant</option>
							<option value="Nurse Consultant">Nurse Consultant</option>
						</select>
					</div>

					<div class="form-group">
						<select class="custom-select browser-default" name="title">
							<option value="Dr.">Dr.</option>
							<option value="Mr.">Mr.</option>
							<option value="Mrs.">Mrs.</option>
							<option value="Miss.">Miss.</option>
						</select>
					</div>

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


					<div class="form-group">
						<label>Phone Number</label>
						<input type="text" class="form-control" id="phone_number" name="phone_number" required>
					</div>

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
        <button type="submit" class="btn btn-primary">Create Account</button>
      </div>
			</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal_holder"></div>


</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">

	$('#new_doctor_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/Doctors/new_doctor_frm.php',
			type: 'GET',
			data: $(this).serialize(),
			success: function(msg){
				if(msg==='save_successful'){
					bootbox.alert("New Doctor Added Successfully",function(){
						window.location.reload()
					})
				}
				else {
					bootbox.alert(msg)
				}
			}
		})//end ajax
	});// new user frm on submit end

	$('.table tbody').on('click', '.edit', function(event) {
		event.preventDefault();
		var user_id=$(this).attr('ID')
		$.get('../serverscripts/admin/Users/edit_user_modal.php?user_id='+user_id,function(msg){
			$('#modal_holder').html(msg)
			$('#edit_user_modal').modal('show')

			$('#edit_user_frm').on('submit', function(event) {
				event.preventDefault();
				$.ajax({
					url: '../serverscripts/admin/Users/edit_user_frm.php',
					type: 'GET',
					data: $('#edit_user_frm').serialize(),
					success: function(msg){
						if(msg==='update_successful'){
							bootbox.alert("User information updated successfully",function(){
								window.location.reload()
							})
						}
						else {
							bootbox.alert(msg)
						}
					}
				})//end ajax
			});
		})
	});


	$('.table tbody').on('click', '.delete', function(event) {
		event.preventDefault();
		var user_id=$(this).attr('ID')
		bootbox.confirm("Do you want to delete this user?",function(r){
			if(r===true){
				$.get('../serverscripts/admin/Users/delete_user_frm.php?user_id='+user_id,function(msg){
					if(msg==='delete_successful'){
						bootbox.alert("User deleted successfully",function(){
							window.location.reload()
						})
					}
					else {
						bootbox.alert(msg)
					}
				})
			}
		})
	});

	</script>

</html>
