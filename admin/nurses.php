<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
		$nurse=new Nurse();
 ?>

<main class="py-3 mx-lg-5">
	<div class="container-fluid mt-2">

		<div class="row mb-5">
		  <div class="col-md-6">
		    <h4 class="titles montserrat">Nurses</h4>
		  </div>
		  <div class="col-md-6 text-right">
				<button type="button" name="button" class="btn btn-primary btn-rounded" id="" data-toggle="modal" data-target="#new_account_modal">
					<i class="fas fa-plus mr-3"></i>
					New Nurse
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
							Nurse Rank
					  </div>
					  <div class="col-md-3">
							Phone Number
					  </div>
					</div>
				</li>

		    <?php
		    // require_once '../dbcon.php';
		    $get_items=mysqli_query($db,"SELECT * FROM nurses WHERE subscriber_id='".$active_subscriber."' AND status='active'")  or die(mysqli_error($this->db));
		    $i=1;
		    while ($rows=mysqli_fetch_array($get_items)) {
					$nurse->nurse_id=$rows['nurse_id'];
					$nurse->NurseInfo();
		      ?>
					<li class="list-group-item hover nurses" id="<?php echo $rows['nurse_id']; ?>">
						<div class="row poppins">
						  <div class="col-md-1">
								<?php echo $i++; ?>
						  </div>
						  <div class="col-md-4">
								<?php echo $nurse->nurse_fullname; ?>
						  </div>
						  <div class="col-md-3">
								<?php echo $nurse->nurse_rank; ?>
						  </div>
						  <div class="col-md-2">
								<?php echo $nurse->phone_number; ?>
						  </div>
							<div class="col-md-2 text-right">
								<div class="dropdown open">
								  <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    Options
								  </button>
								  <div class="dropdown-menu p-0 b-0  minioptions" aria-labelledby="dropdownMenu1">
								    <ul class="list-group">
								      <li class="list-group-item edit" data-nurse_id="<?php echo $rows['nurse_id']; ?>">Edit Info</li>
								      <li class="list-group-item suspend_account"  data-nurse_id="<?php echo $rows['nurse_id']; ?>">Suspend Account</li>
								      <li class="list-group-item">Staff Portal</li>
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

<div class="modal fade" id="new_account_modal">
  <div class="modal-dialog modal-side modal-top-right" role="document">
    <div class="modal-content">
			<form id="new_nurse_frm">
      <div class="modal-body">
					<h6>New Nurse Registration</h6>
					<hr class="hr">

					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
								<label>Nurse Rank</label>
								<select class="custom-select browser-default" id="nurse_rank" name="nurse_rank" required>
									<option value="Staff Nurse">Staff Nurse</option>
									<option value="Senior Staff Nurse">Senior Staff Nurse</option>
									<option value="Nursing Officer">Nursing Officer</option>
									<option value="DDNS">DDNS</option>
								</select>
							</div>
					  </div>
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Title</label>
								<select class="custom-select browser-default" name="title">
									<!-- <option value="Dr.">Dr.</option> -->
									<option value="Mr.">Mr.</option>
									<option value="Mrs.">Mrs.</option>
									<option value="Miss.">Miss.</option>
								</select>
							</div>
					  </div>
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

	$('#new_nurse_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/Nurses/new_nurse_frm.php',
			type: 'GET',
			data: $(this).serialize(),
			success: function(msg){
				if(msg==='save_successful'){
					bootbox.alert("New Nurse Added Successfully",function(){
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


	$('.suspend_account').on('click', function(event) {
		event.preventDefault();
		var nurse_id=$(this).data('nurse_id')
		bootbox.confirm("Do you want to suspend this account?",function(r){
			if(r===true){
				$.get('../serverscripts/admin/Nurses/suspend_account.php?nurse_id='+nurse_id,function(msg){
					if(msg==='account_suspended'){
						bootbox.alert("Nurse suspended",function(){
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
