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



		<div class="d-flex justify-content-center align-items-center" style="height:80vh">
			<div class="card" style="width:350px">
				<div class="card-body">
					<h6>Password Reset</h6>
					<hr class="hr">

					<div class="form-group">
						<label for="">Old Password</label>
						<input type="password" name="old_password" class="form-control"  value="">
					</div>

					<div class="form-group">
						<label for="">New Password</label>
						<input type="password" name="new_password" class="form-control"  value="">
					</div>

					<div class="form-group">
						<label for="">Retype Password</label>
						<input type="password" name="retype_password" class="form-control"  value="">
					</div>

					<div class="d-flex flex-row-reverse">
						<button type="submit" class="btn btn-primary m-0"><i class="fas fa-check mr-3" aria-hidden></i> Update Password</button>
					</div>
					<div class="mt-5 text-center">
						<a href="#"><i class="fas fa-arrow-left mr-3" aria-hidden></i> Return to profile</a>
					</div>
				</div>
			</div>
		</div>



	</div>



</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">

	$('#new_admin_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/Administrators/new_admin_frm.php',
			type: 'GET',
			data: $('#new_admin_frm').serialize(),
			success: function(msg){
				if(msg==='save_successful'){
					bootbox.alert("New Admin Added Successfully",function(){
						window.location.reload()
					})
				}
				else {
					bootbox.alert(msg)
				}
			}
		})//end ajax
	});// new user frm on submit end


	$('#new_hr_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/Administrators/new_hr_frm.php',
			type: 'GET',
			data: $('#new_hr_frm').serialize(),
			success: function(msg){
				if(msg==='save_successful'){
					bootbox.alert("New Hr Added Successfully",function(){
						window.location.reload()
					})
				}
				else {
					bootbox.alert(msg)
				}
			}
		})//end ajax
	});// new user frm on submit end

	$('#new_accountant_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/Accountants/new_accountant_frm.php',
			type: 'GET',
			data: $('#new_accountant_frm').serialize(),
			success: function(msg){
				if(msg==='save_successful'){
					bootbox.alert("New Accountant Added Successfully",function(){
						window.location.reload()
					})
				}
				else {
					bootbox.alert(msg)
				}
			}
		})//end ajax
	});// new user frm on submit end



	// New Doctor
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


	// New Nurse Form

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


	// New Pharm
	$('#new_pharm_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/Pharmacists/new_pharm_frm.php',
			type: 'GET',
			data: $(this).serialize(),
			success: function(msg){
				if(msg==='save_successful'){
					bootbox.alert("New Pharm Added Successfully",function(){
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


	$('table tbody').on('click', '.delete',function(event) {
		event.preventDefault();
		var staff_id=$(this).data('staff_id')
		bootbox.confirm("Do you want to delete this account?",function(r){
			if(r===true){
				$.get('../serverscripts/admin/Staff/delete.php?staff_id='+staff_id,function(msg){
					if(msg==='delete_successful'){
						bootbox.alert("Success. Account deleted",function(){
							window.location.reload()
						})
					}
					else {
						bootbox.alert(msg)
					}
				})//end get
			}//end if
		})//end confirm
	});

	</script>

</html>
