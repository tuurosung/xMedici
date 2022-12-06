<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
		$staff_id=$_SESSION['active_user'];
		$staff=new Staff();
		$staff->staff_id=$staff_id;
		$staff->StaffInfo();
 ?>
	<style media="screen">
		.profile-pic{
			width: 130px;
			border:solid 3px rgb(210, 214, 222);
			border-radius: 50% 50% 50% 50%;
			padding: 3px;
			text-align: center;
			display:block;
		}
	</style>
<main class="py-3 mx-lg-5">
	<div class="container-fluid mt-2">

		<div class="row mb-5">
			<div class="col-8">
				<a href="index.php" type="button" class="btn btn-primary btn-rounded">
					<i class="fas fa-arrow-left mr-3" aria-hidden></i> Return</a>
			</div>
		</div>


		<div class="d-flex justify-content-center align-items-center" style="height:80vh">
				<div class="card">
					<div class="card-body">
						<form id="update_profile" >
							<h5>My Profile</h5>
							<hr class="hr">

							<div class="spacer"></div>
							<div class="spacer"></div>

							<div class="row">
							  <div class="col-md-6">
									<div class="form-group">
										<label for="">Surname</label>
										<input type="text" name="surname" class="form-control" value="<?php echo $staff->surname; ?>">
									</div>
							  </div>
							  <div class="col-md-6">
									<div class="form-group">
										<label for="">Othernames</label>
										<input type="text" name="othernames" class="form-control" value="<?php echo $staff->othernames; ?>">
									</div>
							  </div>
							</div>

							<div class="row">
							  <div class="col-md-6">
									<div class="form-group">
										<label for="">Phone Number</label>
										<input type="text" name="surname" class="form-control" value="<?php echo $staff->phone_number; ?>">
									</div>
							  </div>
							  <div class="col-md-6">
									<div class="form-group">
										<label for="">Email</label>
										<input type="text" name="othernames" class="form-control" value="<?php echo $staff->email; ?>">
									</div>
							  </div>
							</div>

							<div class="form-group">
								<label for="">Address</label>
								<textarea name="address" class="form-control"><?php echo $staff->address; ?></textarea>
							</div>

							<button type="submit" class="btn btn-primary m-0 wide">
									<i class="fas fa-check mr-3" aria-hidden></i> Update Profile
							</button>

						</form>


					</div>
				</div>
		</div>




<div id="modal_holder"></div>


</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">

	$('#update_profile').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/Staff/update_profile.php',
			type: 'GET',
			data: $('#update_profile').serialize(),
			success: function(msg){
				if(msg==='save_successful'){
					bootbox.alert("Success. Profile updated successfully",function(){
						window.location.reload()
					})
				}
				else {
					bootbox.alert(msg)
				}
			}
		})//end ajax
	});// end profile submit


	</script>

</html>
