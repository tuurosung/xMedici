<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php

	$department_id='002';
	$p=new Patient();
 ?>
		<style media="screen">
		.dropdown-menu:before {
				position: absolute;
				top: -7px;
				left: 40%;
				display: inline-block;
				border-right: 7px solid transparent;
				border-bottom: 7px solid #ccc;
				border-left: 7px solid transparent;
				border-bottom-color: rgba(0, 0, 0, 0.2);
				content: '';
				}
				.dropdown-menu:after {
				position: absolute;
				top: -6px;
				left: 40%;
				margin-top:10px;
				display: inline-block;
				border-right: 6px solid transparent;
				border-bottom: 6px solid #ffffff;
				border-left: 6px solid transparent;
				content: '';
				}
		</style>

		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Nurses Desk</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">
						<div class="btn-group">

							<button type="button" name="button" class="btn btn-primary m-0 btn-rounded mr-3" data-toggle="modal" data-target="#new_patient_modal">
								<i class="fas fa-plus mr-3"></i>
								New Patient
							</button>

							<div class="dropdown open">
							  <button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							    Dropdown
							  </button>
							  <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
									<ul class="list-group">
									  <li class="list-group-item" id="print">Print List</li>
									</ul>
							  </div>
							</div>

						</div>


				  </div>
				</div>

				<div class="row mb-3">
				  <div class="col-md-6">
						<i class="fas fa-th-large mr-2" aria-hidden style="font-size:16px"></i>
						<i class="fas fa-bars" aria-hidden style="font-size:16px"></i>
				  </div>
				  <div class="col-md-6 text-right font-weight-bold" style="font-size:16px">
						<?php echo $today; ?> <i class="far fa-calendar-alt" aria-hidden></i>
				  </div>
				</div>

				<div class="row">
				  <div class="col-md-8">
						<div class="row">

							<?php

								$get_pending_transfers=mysqli_query($db,"SELECT * FROM patient_transfer WHERE transferred_to='002' AND transfer_status='pending'")  or die(mysqli_error($db));
								while ($patients=mysqli_fetch_array($get_pending_transfers)) {
									$p->patient_id=$patients['patient_id'];
									$p->PatientInfo();
									?>
									<div class="col-md-4">
									 <div class="card">
										 <div class="card-body pt-2">
											 <div class="text-right">
											 <i class="far fa-clock" aria-hidden></i>	<?php echo  $patients['time_transferred']; ?>
											 </div>
											 <p class="montserrat text-uppercase primary-text font-weight-bold m-0" style="font-weight:400"><?php echo $p->patient_fullname; ?></p>
											 <?php echo $p->sex; ?>, <?php echo $p->age; ?>

											 <div class="my-2 font-itali" style="font-size:10px">
												 Transferred From <strong><?php echo $p->Department($patients['transferred_from']); ?></strong>
											 </div>

											 <button type="button" class="btn btn-sm primary-color-dark accept_transfer_btn" id=""
											 						data-patient_id="<?php echo $patients['patient_id']; ?>"
																	data-visit_id="<?php echo $patients['visit_id']; ?>"
																	data-transferred_to="<?php echo $patients['transferred_to']; ?>"
																	data-serial="<?php echo $patients['sn']; ?>"
																	>
												 				<i class="fas fa-check mr-2" aria-hidden></i>
																Accept & Open Folder
											 </button>
										 </div>
									 </div>
								 </div>
									<?php
								}

							 ?>


						</div>
				  </div>
				  <div class="col-md-4">
						<h6>Active Folders</h6>
						<?php

							$folders=mysqli_query($db,"SELECT * FROM visits WHERE department_id='002' AND status='active'")  or die(mysqli_error($db));
							while ($patients=mysqli_fetch_array($folders)) {
								$p->patient_id=$patients['patient_id'];
								$p->PatientInfo();
								?>
								<div class="card">
								 <div class="card-body pt-2">
									 <p class="montserrat text-uppercase primary-text font-weight-bold m-0" style="font-weight:400"><?php echo $p->patient_fullname; ?></p>
									 <?php echo $p->sex; ?>, <?php echo $p->age; ?>


									<div class="mt-2">
										<a href="singlevisit.php?visit_id=<?php echo $patients['visit_id']; ?>" type="button" class="btn btn-sm primary-color-dark">
 														Open Folder
 															<i class="fas fa-angle-double-right ml-2" aria-hidden></i>
 									 </a>
									</div>

								 </div>
							 </div>
								<?php
							}

						 ?>
				  </div>
				</div>









			</div>
		</main>




<div id="new_patient_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="">
    <div class="modal-content">
			<form id="new_patient_frm">
      <div class="modal-body">
					<h5 class="font-weight-bold montserrat">New Patient Registration</h5>
					<hr class="hr">

						<h6 class="primary-text montserrat font-weight-bold mb-3">Basic Information</h6>

						<div class="row poppins">
						  <div class="col-md-6">
						    <div class="form-group">
									<label for="">Surname</label>
									<input type="text" class="form-control" name="surname" id="surname" value="">
						    </div>
						  </div>
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Othernames</label>
									<input type="text" class="form-control" name="othernames" id="othernames" value="">
						    </div>
						  </div>
						</div>

						<div class="row">
						  <div class="col-md-6">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Date Of Birth</label>
											<input type="text" class="form-control" name="date_of_birth" id="date_of_birth" value="">
								    </div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Sex</label>
											<select class="custom-select browser-default" name="sex">
													<option value="male">Male</option>
													<option value="female">Female</option>
											</select>
								    </div>
									</div>
								</div>

						  </div>
						  <div class="col-md-6">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Religion</label>
											<select class="custom-select browser-default" name="religion" id="religion">
												<option value="Christian">Christian</option>
												<option value="Muslim">Muslim</option>
												<option value="Jew">Jew</option>
												<option value="Atheist">Atheist</option>
												<option value="Other">Other</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Marital Status</label>
											<select class="custom-select browser-default" name="marital_status" id="marital_status">
												<option value="single">Single</option>
												<option value="married">Married</option>
												<option value="divorced">Divorced</option>
												<option value="widowed">Widowed</option>
											</select>
										</div>
									</div>
								</div>
						  </div>
						</div>

						<h6 class="primary-text montserrat font-weight-bold mb-3 mt-4">Contact Information</h6>

						<div class="row poppins">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">House Address</label>
									<input type="text" class="form-control" name="hse_address" id="hse_address" value="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label for="">Town</label>
											<input type="text" class="form-control" name="town" id="town" value="">
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label for="">Region</label>
											<input type="text" class="form-control" name="region" id="region" value="">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row poppins">
							<div class="col-md-6">
								<div class="row">
								  <div class="col-6">
										<div class="form-group">
											<label for="">Primary Phone Number</label>
											<input type="text" class="form-control" name="phone_number" id="phone_number" value="">
										</div>
								  </div>
								  <div class="col-6">
										<div class="form-group">
											<label for="">Secondary Phone Number</label>
											<input type="text" class="form-control" name="phone_number2" id="phone_number2" value="">
										</div>
								  </div>
								</div>

							</div>
							<div class="col-md-6">
								<div class="row poppins">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Hometown</label>
											<input type="text" class="form-control" name="hometown" id="hometown" value="">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Ethnicity</label>
											<input type="text" class="form-control" name="ethnicity" id="ethnicity" value="">
										</div>
									</div>
								</div>
							</div>
						</div>



						<h6 class="primary-text montserrat font-weight-bold mb-3 mt-4">Relative Information</h6>

						<div class="row poppins">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Name Of Nearest Relative</label>
									<input type="text" class="form-control" name="nearest_relative" id="nearest_relative" value="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Relative's Phone</label>
									<input type="text" class="form-control" name="relative_phone" id="relative_phone" value="">
								</div>
							</div>
						</div>


						<h6 class="primary-text montserrat font-weight-bold mb-3 mt-4">NHIS Info</h6>

						<div class="row poppins">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Payment Mode</label>
									<select class="custom-select browser-default" name="payment_mode" id="payment_mode" value="">
										<option value="cash">Cash</option>
										<option value="nhis">NHIS</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">NHIS Number</label>
									<input type="text" class="form-control" name="nhis_number" id="nhis_number" value="">
								</div>
							</div>
						</div>

      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn primary-color-dark">
					<i class="fas fa-file-alt mr-3"></i>
					Create Folder</button>
      </div>
			</form>
    </div>
  </div>
</div>


<div id="modal_holder">

</div>

</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">
		$('.sidebar-fixed .list-group-item').removeClass('active')
		$('#nurses_desk_nav').addClass('active')
		$('#nurses_desk_submenu').addClass('show')
		$('#nurses_desk_pending_patients_li').addClass('font-weight-bold')


		$('.accept_transfer_btn').on('click', function(event) {
			event.preventDefault();
			var patient_id=$(this).data('patient_id');
			var visit_id=$(this).data('visit_id');
			var serial=$(this).data('serial');
			var transferred_to=$(this).data('transferred_to');
			bootbox.confirm('Accept folder transfer',function(r){
				if(r===true){
					$.get('../serverscripts/admin/Patients/accept_transfer.php?patient_id='+patient_id+'&visit_id='+visit_id+'&serial='+serial+'&transferred_to='+transferred_to,function(msg){
						if(msg=='transfer_successful'){
							window.location="singlevisit.php?visit_id="+visit_id;
						}else {
							bootbox.alert(msg)
						}
					})
				}//end if
			})//end bootbox
		});











		$('#new_patient_modal').on('shown.bs.modal', function(event) {
			event.preventDefault();
			if($(this).val()=='cash'){
				$('#nhis_number').val('N/A')
				$('#nhis_number').attr('readonly','readonly')
			}else {
				$('#nhis_number').val('')
				$('#nhis_number').attr('readonly',false)
			}
		});

		$('#date_of_birth').datepicker()
		$('#date_of_birth').on('changeDate', function(event) {
			event.preventDefault();
			$(this).datepicker('hide')
		});

		$('#payment_mode').on('change',  function(event) {
			event.preventDefault();
			if($(this).val()=='cash'){
				$('#nhis_number').val('N/A')
				$('#nhis_number').attr('readonly','readonly')
			}else {
				$('#nhis_number').val('')
				$('#nhis_number').attr('readonly',false)
			}
		});

		// $(document).on('keyup',function(e){
		// 	if(e.keyCode=='78'){
		// 		$('#new_drug_modal').modal('show')
		// 	}
		// })


    	$(document).ready(function(){

					$('#print').on('click', function(event) {
						event.preventDefault();
						print_popup('print_inventory.php');
					});


					$('#new_drug_modal').on('shown.bs.modal', function(event) {
						event.preventDefault();
						$('#drug_name').focus()
					});//end modal shown

					$('#new_patient_frm').on('submit', function(event) {
						event.preventDefault();
						bootbox.confirm("Create new folder?",function(r){
							if(r===true){
								$.ajax({
									url: '../serverscripts/admin/Patients/new_patient_frm.php',
									type: 'GET',
									data: $('#new_patient_frm').serialize(),
									success: function(msg){
										if(msg==='save_successful'){
											bootbox.alert("Folder Created Successfully",function(){
												window.location='patient_folder.php'
											})
										}
										else {
											bootbox.alert(msg,function(){
												window.location.reload()
											})
										}
									}
								})
							}
						})

					});//end submit


					$('.table tbody').on('click','.edit', function(event) {
						event.preventDefault();
						 var drug_id=$(this).attr('ID')

						 $.ajax({
							url: '../serverscripts/admin/inventory_edit.php?drug_id='+drug_id,
							type: 'GET',
							success: function(msg){
								$('#modal_holder').html(msg)

								$('body').removeClass('modal-open');
								$('.modal-backdrop').remove()

								$('#edit_drug_modal').modal('show')

								$('#edit_drug_frm').on('submit', function(event) {
									event.preventDefault();

									$.ajax({
										url: '../serverscripts/admin/edit_item_frm.php',
										type: 'GET',
										data: $(this).serialize(),
										success: function(msg){
											if(msg==='save_successful'){
												bootbox.alert('Drug Information Updated Successfully',function(){
													window.location.reload()
												})
											}
											else {
												bootbox.alert(msg)
											}
										}
									})//end ajax
								});//end edit form
							}
						 })

					});//end edit function


					$('.table tbody').on('click','.delete', function(event) {
						event.preventDefault();

						var drug_id=$(this).attr('ID')
						bootbox.confirm("Are you sure you want to delete this item?",function(r){
							if(r===true){
								$.ajax({
									url: '../serverscripts/admin/inventory_delete.php?drug_id='+drug_id,
									type: 'GET',
									success: function(msg){
										if(msg==='delete_successful'){
											bootbox.alert("Drug deleted successfully",function(){
												window.location.reload()
											})
										}
										else {
											bootbox.alert(msg)
										}
									}
								})
							}
						})
					});//end delete



    	});


	</script>

</html>
