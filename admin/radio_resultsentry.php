<?php require_once '../navigation/header_lite.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php

	require_once '../serverscripts/Classes/Radiology.php';
	require_once '../serverscripts/Classes/Patient.php';
	require_once '../serverscripts/Classes/Services.php';

	$request_id=clean_string($_GET['request_id']);

	$radio=new Radiology();
	$radio->request_id=$request_id;
	$radio->Info();

	$patient=new Patient();
	$service=new Service();

	$service->service_id=$radio->service_id;
	$service->ServiceInfo();

	// if($request_id==""){
	// 	header('location: queued_tests.php');
	// }
	//
	// if($test_id==''){
	// 	header('location: queued_tests.php');
	// }



	$patient->patient_id=$radio->patient_id;
	$patient->PatientInfo();

	if($radio->patient_type=='walkin_patient'){
		$fullname=$radio->surname.' '.$radio->othernames;
		$age=$radio->age;
		$sex=$radio->sex;
	}else {
		$fullname=$patient->patient_fullname;
		$age=$patient->age;
		$sex=$patient->sex_description;
	}


 ?>


		<main class="py-3 ml-lg-5 main" style="background-color:#fff; min-height:100vh">
			<div class="container-fluid mt-2">



				<div class="row">
				  <div class="col-md-5">
						<h4 class="titles montserrat mb-5">Radiology Results Upload</h4>
				  </div>
				  <div class="col-md-7 text-right mb-5">


						<button type="button" name="button" class="btn btn-primary m-0 btn-rounded mr-3" data-toggle="modal" data-target="#new_comments_modal">
							<i class="far fa-comments mr-3"></i>
							Add Comments
						</button>

						<button id="complete" data-request_id="<?php echo $request_id; ?>" type="button" class="btn btn-primary m-0  btn-rounded mr-3"><i class="fas fa-check mr-3" aria-hidden></i> Mark As Complete</button>


				  </div>
				</div>

				<div class="py-4" style="margin-left:-40px; margin-right:-20px; margin-bottom:50px; background-color:#eee; padding-left:40px; padding-right:20px">

					<div class="row">
					  <div class="col-3">
							Request ID
							<p class="font-weight-bold montserrat" style="font-size:16px"><?php echo $request_id; ?></p>
					  </div>
					  <div class="col-3">
							Date
							<p class="font-weight-bold poppins" style="font-size:16px"><?php echo $radio->date; ?></p>
					  </div>
					  <div class="col-3">
							Time
							<p class="font-weight-bold poppins" style="font-size:16px"><?php // ?></p>
					  </div>
					  <div class="col-3">
							Doctor
							<p class="font-weight-bold poppins" style="font-size:16px"><?php echo $radio->doctor; ?></p>
					  </div>
					</div>

					<div class="row mt-4">
					  <div class="col-3">
							Patient Name
							<p class="font-weight-bold text-uppercase montserrat" style="font-size:16px"><?php echo $fullname; ?></p>
					  </div>
					  <div class="col-3">
							Age
							<p class="font-weight-bold poppins" style="font-size:16px"><?php echo $age; ?></p>
					  </div>
					  <div class="col-3">
							Sex
							<p class="font-weight-bold poppins" style="font-size:16px"><?php echo $sex; ?></p>
					  </div>
					</div>
				</div>

				<div class="card mb-3">
					<div class="card-body">
						<h4 class="m-0 montserrat font-weight-bold"><?php echo $service->description; ?></h4>
					</div>
				</div>

				<section>
					<div class="card mb-3">
						<div class="card-body px-1">
							<p class="montserrat font-weight-bold mx-3 mb-3"><i class="far fa-file-alt mr-2" aria-hidden></i> Uploaded Files</p>
							<div class="px-3 py-3 grey lighten-4">

								<form action="../serverscripts/admin/OPD/Radiology/file_upload.php?visit_id=<?php echo $request_id; ?>"
								class="dropzone" id="my-awesome-dropzone"></form>

							</div>
						</div>
					</div>
				</section>

				<div class="card mt-5">
					<div class="card-body">
						<h6 class="montserrat font-weight-bold mb-3">Uploaded Files</h6>

						<div class="row">
							<?php
									$get_uploads=mysqli_query($db,"SELECT * FROM file_uploads WHERE visit_id='".$request_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
									while ($rows=mysqli_fetch_array($get_uploads)) {
										?>
										<div class="col-md-3">
											<img src="../FileUploads/<?php echo $rows['file_name']; ?>" alt="" class="img-thumbnail">
											<p class="mt-3">Uploaded On <?php echo $rows['date']; ?></p>
										</div>
										<?php
									}
							 ?>
						</div>

					</div>
				</div>

			</div>
		</main>


		<div id="results_entry_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
					<form id="results_entry_frm">
		      <div class="modal-body px-4">
		        <h6 class="montserrat font-weight-bold">Results Entry</h6>
						<hr class="hr">

						<div class="row d-none">
							<div class="col-md-6">
								<input type="text" class="form-control" name="" value="<?php echo $request_id; ?>" id="request_id">
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control" name="" value="<?php echo $test_id; ?>" id="test_id">
							</div>
						</div>


						<div class="row mt-3">

							<?php
								$load_parameters=mysqli_query($db,"SELECT * FROM lab_requests_results WHERE request_id='".$request_id."' AND test_id='".$test_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
								while ($rows=mysqli_fetch_array($load_parameters)) {
									$parameter_id=$rows['parameter_id'];
									$test->ParameterInfo($parameter_id);
									?>
									<div class="col-6">
										<div class="form-group">
											<label for=""><?php echo $test->parameter_name; ?></label>
											<input type="text" name="" value="<?php echo $rows['result']; ?>" class="form-control result_value" data-unique_id="<?php echo $rows['sn']; ?>">
										</div>
									</div>
									<?php
								}
							 ?>
						</div>
		      </div>
		      <div class="modal-footer">
		       	<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
		       	<button type="submit" class="btn btn-primary" id="complete_test_btn"> <i class="fas fa-check mr-2"></i> Save Results</button>
		      </div>
					</form>
		    </div>
		  </div>
		</div>



		<div id="new_comments_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
					<form id="new_comments_frm" autocomplete="off">
		      <div class="modal-body">
		        <h4 class="montserrat font-weight-bold">Add New Comments</h4>
						<hr class="hr">

						<div class="row d-none">
							<div class="col-md-6">
								<input type="text" class="form-control" name="request_id" value="<?php echo $request_id; ?>">
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control" name="test_id" value="<?php echo $test_id; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="">Scientist Comments</label>
							<textarea name="comment" id="comment" class="form-control" required></textarea>
						</div>

						<div class="form-group">
							<label for="">Name Of Scientist</label>
							<input type="text" name="scientist" class="form-control" required>
						</div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-primary"> <i class="fas fa-check mr-2"></i> Save Comments</button>
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
		$('#radio_nav').addClass('active')
		$('#radio_submenu').addClass('show')
		$('#radiology_requests_li').addClass('font-weight-bold')

		tinymce.init({
			selector: '#comment',
			force_br_newlines : true,
			force_p_newlines : false,
			forced_root_block : '', // Needed for 3.x
				setup: function (editor) {
					editor.on('change', function () {
							editor.save();
					});
			}
		});





		$('#complete').on('click', function(event) {
			event.preventDefault();
			let request_id=$(this).data('request_id');

			bootbox.confirm("Confirm. Mark this request as complete?",function(r){
				if(r===true){

					$.get('../serverscripts/admin/OPD/Radiology/complete.php?request_id='+request_id,function(msg){
						if(msg==='update_successful'){
							bootbox.alert("Request Complete",function(){
								window.location.reload();
							})
						}else {
							bootbox.alert(msg,function(){
								window.location.reload()
							})
						}
					})

				}
			})

		});//end submit


		$('#new_comments_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm('Confirm. Add new comments to results?',function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Laboratory/new_comments_frm.php',
						type: 'GET',
						data:$('#new_comments_frm').serialize(),
						success:function(msg){
							if(msg==='save_successful'){
								bootbox.alert("Success. Comments Added",function(){
									window.location.reload()
								})
							}else {
								bootbox.alert(msg)
							}
						}
					})
				}
			})
		});





		$('.edit_test_btn').on('click', function(event) {
			event.preventDefault();
			var test_id=$(this).attr('ID')
			$.get('../serverscripts/admin/Laboratory/edit_test_modal.php?test_id='+test_id,function(msg){
				$("#modal_holder").html(msg)
				$('#edit_test_modal').modal('show')

				$('#edit_test_frm').on('submit', function(event) {
					event.preventDefault();
					bootbox.confirm('Update test info?',function(r){
						if(r===true){
							$.ajax({
								url: '../serverscripts/admin/Laboratory/edit_test_frm.php',
								type: 'GET',
								data:$('#edit_test_frm').serialize(),
								success:function(msg){
									if(msg==='update_successful'){
										bootbox.alert("Test info updated successfully",function(){
											window.location.reload()
										})
									}else {
										bootbox.alert(msg)
									}
								}
							})
						}
					})
				});
			})
		});


		$('.delete_test_btn').on('click', function(event) {
			event.preventDefault();
			var test_id=$(this).attr('ID')
			bootbox.confirm("Permanently delete this test?",function(r){
				if(r===true){
					$.get('../serverscripts/admin/Laboratory/delete_test.php?test_id='+test_id,function(msg){
						if(msg==='delete_successful'){
							bootbox.alert('Test deleted successfully',function(){
								window.location.reload()
							})
						}else {
							bootbox.alert(msg)
						}
					})
				}
			})
		});





    	$(document).ready(function(){

					$('#print').on('click', function(event) {
						event.preventDefault();
						print_popup('print_inventory.php');
					});


					$('#new_drug_modal').on('shown.bs.modal', function(event) {
						event.preventDefault();
						$('#drug_name').focus()
					});//end modal shown




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
