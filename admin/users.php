<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<main class="py-3 mx-lg-5">
	<div class="container-fluid mt-2">

		<div class="row mb-5">
		  <div class="col-md-6">
		    <h4 class="titles montserrat">Users</h4>
		  </div>
		  <div class="col-md-6 text-right">
				<button type="button" name="button" class="btn btn-primary btn-rounded" id="" data-toggle="modal" data-target="#new_account_modal">
					<i class="fas fa-plus mr-3"></i>
					New User
				</button>
		  </div>
		</div>


		<table class="table datatables table-condensed">
		  <thead>
		    <tr>
		      <th>#</th>
		      <th>User ID</th>
		      <th>Full Name</th>
		      <th>Phone Number</th>
		      <th>Access Level</th>
		      <th></th>
		    </tr>
		  </thead>
		  <tbody>
		    <?php
		    // require_once '../dbcon.php';
		    $get_items=mysqli_query($db,"SELECT * FROM users WHERE subscriber_id='".$active_subscriber."' AND status='active'")  or die(mysqli_error($this->db));
		    $i=1;
		    while ($rows=mysqli_fetch_array($get_items)) {
		      ?>

		      <tr>
		        <td><?php echo $i++; ?></td>
		        <td><?php echo $rows['user_id']; ?></td>
		        <td class="text-uppercase"><?php echo $rows['full_name']; ?></td>
		        <td><?php echo $rows['phone']; ?></td>
		        <td class="text-uppercase"><?php echo $rows['access_level']; ?></td>

		        <td class="text-right">
							<div class="dropdown open">
							  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							    Option
							  </button>
							  <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
									<ul class="list-group">
									  <li class="list-group-item edit" id="<?php echo $rows['user_id']; ?>">Edit</li>
									  <li class="list-group-item delete" id="<?php echo $rows['user_id']; ?>">Delete</li>
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


<div class="modal fade" id="new_account_modal">
  <div class="modal-dialog modal-side modal-top-right" role="document">
    <div class="modal-content">
			<form id="new_user_frm">
      <div class="modal-body">
					<h6>New User Registration</h6>
					<hr class="hr">

					<div class="form-group">
						<label>Access Level</label>
						<select class="custom-select browser-default" id="access_level" name="access_level" required>
							<option value="administrator">Administrator</option>
							<option value="sales">Sales</option>
						</select>
					</div>

					<div class="form-group">
						<label>Full Name</label>
						<input type="text" class="form-control" id="full_name" name="full_name" required>
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

	$('#new_user_frm').on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: '../serverscripts/admin/Users/new_user_frm.php',
			type: 'GET',
			data: $(this).serialize(),
			success: function(msg){
				if(msg==='save_successful'){
					bootbox.alert("New User Added Successfully",function(){
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
