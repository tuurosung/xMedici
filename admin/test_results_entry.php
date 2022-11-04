<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$request_id=clean_string($_GET['request_id']);
	$test_id=clean_string($_GET['test_id']);

	if($request_id==""){
		header('location: queued_tests.php');
	}

	if($test_id==''){
		header('location: queued_tests.php');
	}

	$get_test_parameters=mysqli_query($db,"SELECT * FROM lab_tests_parameters WHERE test_id='".$test_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
	while ($test_parameters=mysqli_fetch_array($get_test_parameters)) {
		$parameter_id=$test_parameters['parameter_id'];
		$check_if_results_row_was_created=mysqli_query($db,"SELECT *
																																															FROM
																																																lab_requests_results
																																															WHERE
																																																request_id='".$request_id."' AND test_id='".$test_id."' AND parameter_id='".$parameter_id."' AND subscriber_id='".$active_subscriber."'
																																										") or die(mysqli_error($db));
			if(mysqli_num_rows($check_if_results_row_was_created)==0){
				$table='lab_requests_results';
				$fields=array("subscriber_id","request_id","test_id","parameter_id","result");
				$values=array("$active_subscriber","$request_id","$test_id","$parameter_id","");
				$query=insert_data($db,$table,$fields,$values);
			}
	}


	$test=new Test();
	$test->test_id=$test_id;
	$test->TestInfo();

	$opd=new Visit();
	$opd->TestRequestInfo($request_id);
	$patient_id=$opd->requested_test_patient_id;

	$patient=new Patient();
	$patient->patient_id=$patient_id;
	$patient->PatientInfo();

	$test->LabOrderInfo($request_id,$test_id);


	if($patient_id==''){
		$getinfo=mysqli_query($db,"SELECT * FROM lab_requests WHERE request_id='".$request_id."' AND subscriber_id='".$active_subscriber."'") or die(msyqli_error($db));
		$info=mysqli_fetch_array($getinfo);
		$fullname=$info['patient_name'];
		$age=$info['age'].'years';
		$sex=$info['sex'];
	}else {
		$fullname=$patient->patient_fullname;
		$age=$patient->age;
		$sex=$patient->sex_description;
	}


 ?>


		<main class="py-3 ml-lg-5 main" style="background-color:#fff; min-height:100vh">
			<div class="container-fluid mt-2">



				<div class="row">
				  <div class="col-md-3">
						<h4 class="titles montserrat mb-5">Results Entry</h4>
				  </div>
				  <div class="col-md-9 text-right mb-5">

						<button type="button" name="button" class="btn btn-primary m-0 btn-rounded mr-3" data-toggle="modal" data-target="#results_entry_modal">
							<i class="fas fa-plus mr-3"></i>
							Input Results
						</button>
						<button type="button" name="button" class="btn btn-primary m-0 btn-rounded mr-3" data-toggle="modal" data-target="#new_comments_modal">
							<i class="far fa-comments mr-3"></i>
							Add Comments
						</button>
						<button type="button" name="button" class="btn btn-primary m-0 btn-rounded mr-3" data-toggle="modal" data-target="#validate_modal">
							<i class="fas fa-check mr-3"></i>
							Validate Results
						</button>

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
							<p class="font-weight-bold poppins" style="font-size:16px"><?php echo $opd->requested_test_date; ?></p>
					  </div>
					  <div class="col-3">
							Time
							<p class="font-weight-bold poppins" style="font-size:16px"><?php echo date('H:i:s',$test->order_timestamp); ?></p>
					  </div>
					  <div class="col-3">
							Doctor
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
						<h4 class="m-0 montserrat font-weight-bold"><?php echo $test->description; ?></h4>
					</div>
				</div>

				<div class="row mb-3">
				  <div class="col-md-4 d-none">
						<div class="card">
							<div class="card-body">
								<h6><?php echo $test->description; ?> - Results</h6>
								<hr class="hr">
								<div class="spacer"></div>

								<div class="row d-none">
								  <div class="col-md-6">
								    <input type="text" class="form-control" name="" value="<?php echo $request_id; ?>" id="request_id">
								  </div>
								  <div class="col-md-6">
								    <input type="text" class="form-control" name="" value="<?php echo $test_id; ?>" id="test_id">
								  </div>
								</div>

								<?php
									$load_parameters=mysqli_query($db,"SELECT * FROM lab_requests_results WHERE request_id='".$request_id."' AND test_id='".$test_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
									while ($rows=mysqli_fetch_array($load_parameters)) {
										$parameter_id=$rows['parameter_id'];
										$test->ParameterInfo($parameter_id);
										?>
										<div class="form-group">
											<label for=""><?php echo $test->parameter_name; ?></label>
											<input type="text" name="" value="<?php echo $rows['result']; ?>" class="form-control result_value" data-unique_id="<?php echo $rows['sn']; ?>">
										</div>
										<?php
									}
								 ?>

								 <button type="button" class="btn btn-primary m-0 wide" id=""> <i class="fas fa-check mr-2" aria-hidden></i> Complete Test</button>
							</div>
						</div>
				  </div>
					<div class="col-12">
						<div class="card">
							<div class="card-body">

								<table class="table table-condensed">
							    <thead>
							      <tr>
							        <th>Parameter</th>
							        <th>Value</th>
							        <th>Normal Range</th>
							        <th>Flag</th>
							        <th>Unit</th>
							      </tr>
							    </thead>
							    <tbody>
							      <?php
							        $load_parameters=mysqli_query($db,"SELECT * FROM lab_requests_results WHERE request_id='".$request_id."' AND test_id='".$test_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
							        while ($rows=mysqli_fetch_array($load_parameters)) {
							          $parameter_id=$rows['parameter_id'];
							          $test->ParameterInfo($parameter_id);
							          ?>
							          <tr>
							            <td><?php echo $test->parameter_name; ?></td>
							            <td><?php echo $rows['result']; ?></td>
							            <td>

														<?php
															if($test->parameter_type=='variable'){
																if($sex=='M'){
																	echo $test->parameter_male_min. ' - '.$test->parameter_male_max;
																}elseif ($sex=='F') {
																	echo $test->parameter_female_min. ' - '.$test->parameter_female_max;
																}
															}else {
																echo $test->parameter_general_min . ' - '.$test->parameter_general_max;
															}
														 ?>

													</td>
							            <td>
							              <?php
							              if($rows['result'] > $test->parameter_general_max){
							                ?>
							                <p class="m-0 font-weight-bold">H</p>
							                <?php
							              }elseif ($rows['result'] < $test->parameter_general_min) {
							                ?>
							                <p class="m-0 font-weight-bold">L</p>
							                <?php
							              }
							             ?>
							           </td>

							            <td><?php echo $test->parameter_unit; ?></td>
							          </tr>
							          <?php
							        }
							       ?>

							    </tbody>
							  </table>

							</div>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-body">
						<?php
							$getcomments=mysqli_query($db,"SELECT * FROM lab_test_results_comments WHERE test_id='".$test_id."' AND request_id='".$request_id."' AND status='active'") or die(mysqli_error($db));
							while ($comments=mysqli_fetch_array($getcomments)) {
								?>
								<p><?php echo $comments['scientist']; ?></p>
								<p><?php echo $comments['comments']; ?></p>
								<div class="spacer">

								</div>
								<?php
							}
						 ?>
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
		$('#lab_nav').addClass('active')
		$('#lab_submenu').addClass('show')
		$('#tests_li').addClass('font-weight-bold')

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


		$('.result_value').on('blur', function(event) {
			event.preventDefault();
			var result_value=$(this).val();
			var identity=$(this).data('unique_id')
			if(result_value===''){

			}else {
				$.get('../serverscripts/admin/Laboratory/results_entry.php?result_value='+result_value+'&unique_id='+identity,function(msg){
					if(msg==='save_successful'){

					}else {
						bootbox.alert(msg)
					}
				})//end get
			}
		});


		$('#complete_test_btn').on('click', function(event) {
			event.preventDefault();
			bootbox.confirm("Confirm. Is this test complete?",function(r){
				if(r===true){

					var request_id=$('#request_id').val()
					var test_id=$('#test_id').val()

					$.ajax({
						url: '../serverscripts/admin/Laboratory/complete_test.php?request_id='+request_id+'&test_id='+test_id,
						type: 'GET',
						success: function(msg){
							if(msg==='save_successful'){
								bootbox.alert("Test Complete",function(){
									window.location.reload();
								})
							}else {
								bootbox.alert(msg,function(){
									window.location.reload()
								})
							}
						}
					})//end ajax
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
