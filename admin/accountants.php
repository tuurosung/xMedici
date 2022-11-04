<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
		$accountant=new Accountant();
 ?>

<main class="py-3 mx-lg-5">
	<div class="container-fluid mt-2">

		<div class="row mb-5">
		  <div class="col-md-6">
		    <h4 class="titles montserrat">Accountants / Cashiers</h4>
		  </div>
		  <div class="col-md-6 text-right">
				<button type="button" name="button" class="btn btn-primary btn-rounded" id="" data-toggle="modal" data-target="#new_account_modal">
					<i class="fas fa-plus mr-3"></i>
					New Cashier
				</button>
		  </div>
		</div>

				<div class="card">
					<div class="card-body">
						<h6 class="montserrat font-weight-bold">List Of Accountants</h6>
						<hr class="hr">

						<table class="table datatables table-condensed">
						  <thead class="grey lighten-4">
						    <tr>
						      <th>#</th>
						      <th>Full Name</th>
						      <th>Accountant Rank</th>
						      <th>Phone Number</th>
						      <th></th>
						    </tr>
						  </thead>
						  <tbody>
								<?php
								// require_once '../dbcon.php';
								$get_items=mysqli_query($db,"SELECT * FROM accountants WHERE subscriber_id='".$active_subscriber."' AND status='active'")  or die(mysqli_error($this->db));
								$i=1;
								while ($rows=mysqli_fetch_array($get_items)) {
									$accountant->accountant_id=$rows['accountant_id'];
									$accountant->AccountantInfo();
									?>
									<tr>
							      <td><?php echo $i++; ?></td>
							      <td><?php echo $accountant->accountant_fullname; ?></td>
							      <td><?php echo $accountant->accountant_rank; ?></td>
										<td><?php echo $accountant->phone_number; ?></td>
							      <td>
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
										</td>
							    </tr>

									<?php
								}
								?>

						  </tbody>
						</table>





			</div>
		</div>

<div class="modal fade" id="new_account_modal">
  <div class="modal-dialog modal-side modal-top-right" role="document">
    <div class="modal-content">
			<form id="new_accountant_frm" autocomplete="off">
      <div class="modal-body">
					<h6 class="montserrat font-weight-bold">New Cashier Registration</h6>
					<hr class="hr">

					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
								<label>Rank</label>
								<select class="custom-select browser-default" id="accountant_rank" name="accountant_rank" required>
									<option value="Junior Accountant">Junior Accountant</option>
									<option value="Senior Accountant">Senior Accountant</option>
									<option value="Chartered Accountant">Chartered Accountant</option>
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
