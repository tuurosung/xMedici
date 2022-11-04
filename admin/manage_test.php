<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$test_id=clean_string($_GET['test_id']);
	$test=new Test();
	$test->test_id=$test_id;
	$test->TestInfo();
 ?>


		<main class="py-3 ml-lg-5 main" style="background-color:; min-height:100vh">
			<div class="container-fluid mt-2">



				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Manage Test</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">

						<button type="button" name="button" class="btn btn-primary m-0 btn-rounded mr-3" data-toggle="modal" data-target="#new_service_modal">
							<i class="fas fa-plus mr-3"></i>
							Add Parameter
						</button>

				  </div>
				</div>


				<div class="card mb-3">
					<div class="card-body">
						<h6 class="montserrat font-weight-bold"><?php echo $test->description; ?></h6>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="card" style="border-radius:10px">
							<div class="card-body">
								<form id="new_parameter_frm" autocomplete="off">
								<h6 class="montserrat font-weight-bold">Add Parameter</h6>

								<div class="spacer"></div>

								<input type="text" name="test_id" value="<?php echo $test_id; ?>" class="d-none">
								<!-- Pills navs -->
									<ul class="nav nav-pills mb-3 xmedici_pills" id="ex1" role="tablist">
									  <li class="nav-item" role="presentation">
									    <a class="nav-link active py-2 " id="info_tab" data-toggle="pill" href="#info" role="tab" aria-controls="info" aria-selected="true" >
												Parameter Info
											</a>
									  </li>
									  <li class="nav-item" role="presentation">
										  <a class="nav-link py-2 px-3" id="sex_tab" data-toggle="pill" href="#sex"  role="tab"  aria-controls="sex" aria-selected="false">
												Variable Ranges
											</a>
									  </li>

									</ul>
									<!-- Pills navs -->

									<!-- Pills content -->
									<div class="tab-content" id="ex1-content">
									  <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info_tab">
											<div class="spacer"></div>

											<div class="row">
											  <div class="col-md-6">
													<div class="form-group">
														<label for="">Description</label>
														<input class="form-control" type="text"  name="description" value="" required>
													</div>
											  </div>
											  <div class="col-md-6">
													<div class="form-group">
														<label for="">Unit</label>
														<select class="custom-select browser-default" name="unit">
																<option value="N/A">Not Applicable</option>
															<?php
																	$get_units=mysqli_query($db,"SELECT * FROM measurement_units") or die(mysqli_error($db));
																	while ($units=mysqli_fetch_array($get_units)) {
																		?>
																		<option value="<?php echo $units['unit']; ?>"><?php echo $units['unit']; ?></option>
																		<?php
																	}
															 	?>

														</select>
													</div>
											  </div>
											</div>


											<div class="row">
											  <div class="col-md-6">
													<div class="form-group">
														<label for="">General Minimum Value</label>
														<input type="text" name="general_min" class="form-control" value="">
													</div>
											  </div>
											  <div class="col-md-6">
													<div class="form-group">
														<label for="">General Max Value</label>
														<input type="text" name="general_max" class="form-control" value="">
													</div>
											  </div>
											</div>

										</div>

									  <div class="tab-pane fade" id="sex" role="tabpanel" aria-labelledby="sex_tab">
											<div class="spacer"></div>

											<div class="row">
											  <div class="col-md-6">
													<div class="form-group">
														<label for="">Male Min</label>
														<input type="text" name="male_min" class="form-control" value="">
													</div>
											  </div>
											  <div class="col-md-6">
													<div class="form-group">
														<label for="">Male Max</label>
														<input type="text" name="male_max" class="form-control" value="">
													</div>
											  </div>
											</div>

											<div class="row">
											  <div class="col-md-6">
													<div class="form-group">
														<label for="">Female Min</label>
														<input type="text" name="female_min" class="form-control" value="">
													</div>
											  </div>
											  <div class="col-md-6">
													<div class="form-group">
														<label for="">Female Max</label>
														<input type="text" name="female_max" class="form-control" value="">
													</div>
											  </div>
											</div>

											<div class="row">
											  <div class="col-md-6">
													<div class="form-group">
														<label for="">Neonates Min</label>
														<input type="text" name="child_min" class="form-control" value="">
													</div>
											  </div>
											  <div class="col-md-6">
													<div class="form-group">
														<label for="">Neonates Max</label>
														<input type="text" name="child_max" class="form-control" value="">
													</div>
											  </div>
											</div>

									  </div>


									</div>
									<!-- Pills content -->


									<button type="submit" class="btn btn-primary wide m-0"><i class="fas fa-check mr-2" aria-hidden></i> Add Parameter</button>

								</form>
							</div>
						</div>
					</div>

					<div class="col-md-8">
						<div class="card" style="border-radius:10px">
							<div class="card-body">
								<h6 class="montserrat font-weight-bold mb-5">Test Parameters</h6>

								<table class="table table">
								  <thead class="grey lighten-4">
								    <tr>
								      <th>Description</th>
								      <th>Unit</th>
								      <th>Normal Range</th>
								      <th></th>
								    </tr>
								  </thead>
								  <tbody>
										<?php
										$get_parameters=mysqli_query($db,"SELECT * FROM lab_tests_parameters	WHERE test_id='".$test_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
										while ($parameters=mysqli_fetch_array($get_parameters)) {
											?>
											<tr>
									      <td><?php echo $parameters['description']; ?></td>
									      <td><?php echo $parameters['unit']; ?></td>
									      <td><?php echo $parameters['general_min']; ?> - <?php echo $parameters['general_max']; ?></td>
									      <td class="text-right">
													<button type="button" class="btn btn-primary btn-sm edit_parameter" id="<?php echo $parameters['parameter_id']; ?>"><i class="fas fa-pencil-alt mr-2" aria-hidden></i>Edit</button>
													<button type="button" class="btn btn-danger btn-sm delete"  id="<?php echo $parameters['parameter_id']; ?>"><i class="fas fa-trash-alt mr-2" aria-hidden></i> Delete</button>
												</td>
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


				<div class="table-responsive mt-5">

				</div>

			</div>
		</main>




<div id="new_service_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog " style="">
    <div class="modal-content">
			<form id="new_test_frm" autocomplete="off">
      <div class="modal-body">
					<h5 class="font-weight-bold montserrat">New Lab Test</h5>
					<hr class="hr mb-3">


						<div class="row poppins">
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Category</label>
									<select class="custom-select browser-default" name="test_category">
										<?php
												$get_categories=mysqli_query($db,"SELECT * FROM lab_test_categories") or die(mysqli_error($db));
												while ($rows=mysqli_fetch_array($get_categories)) {
													?>
													<option value="<?php echo $rows['category_id']; ?>"><?php echo $rows['description']; ?></option>
													<?php
												}
										 ?>

									</select>
						    </div>
						  </div>
						  <div class="col-md-6">

						  </div>
						</div>

						<div class="row poppins">
						  <div class="col-md-6">
						    <div class="form-group">
									<label for="">Description</label>
									<input type="text" class="form-control" name="description" id="description" placeholder="Enter the name of the test">
						    </div>
						  </div>
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Cost</label>
									<input type="text" class="form-control" name="test_cost" id="test_cost"  placeholder="Enter the cost of the test">
						    </div>
						  </div>
						</div>


						<div class="form-group">
							<label for="">Specimen</label>
							<select class="custom-select browser-default" name="specimen">
								<option value="Blood">Blood</option>
								<option value="Urine">Urine</option>
								<option value="Stool">Stool</option>
								<option value="Seminal Fluid">Seminal Fluid</option>
							</select>
						</div>


      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn primary-color-dark">
					<i class="fas fa-check mr-3"></i>
					Create Test</button>
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


		$('#new_parameter_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm("Add new parameter?",function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Laboratory/new_parameter_frm.php',
						type: 'GET',
						data: $('#new_parameter_frm').serialize(),
						success: function(msg){
							if(msg==='save_successful'){
								bootbox.alert("Parameter Added Successfully",function(){
									window.location.reload()
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


		$('.table tbody').on('click','.edit_parameter', function(event) {
			event.preventDefault();

			var parameter_id=$(this).attr('ID')
			$.get('../serverscripts/admin/Laboratory/edit_parameter_modal.php?parameter_id='+parameter_id,function(msg){
				$('#modal_holder').html(msg)
				$('#edit_parameter_modal').modal('show')

				$('#edit_parameter_frm').on('submit', function(event) {
					event.preventDefault();
					bootbox.confirm('Confirm. Update Parameter info?',function(r){
						if(r===true){
							$.ajax({
								url: '../serverscripts/admin/Laboratory/edit_parameter_frm.php',
								type: 'GET',
								data:$('#edit_parameter_frm').serialize(),
								success:function(msg){
									if(msg==='update_successful'){
										bootbox.alert("Parameter info updated successfully",function(){
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


		});//end delete




		$('.table tbody').on('click','.delete', function(event) {
			event.preventDefault();

			var parameter_id=$(this).attr('ID')
			bootbox.confirm("Confirm. Do you want to delete this parameter?",function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Laboratory/delete_parameter.php?parameter_id='+parameter_id,
						type: 'GET',
						success: function(msg){
							if(msg==='delete_successful'){
								bootbox.alert("Parameter deleted successfully",function(){
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






    	});


	</script>

</html>
