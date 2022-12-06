<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	// $request_id=clean_string($_GET['request_id']);
	// $test_id=clean_string($_GET['test_id']);
	//
	// $get_test_parameters=mysqli_query($db,"SELECT * FROM lab_tests_parameters WHERE test_id='".$test_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
	// while ($test_parameters=mysqli_fetch_array($get_test_parameters)) {
	// 	$parameter_id=$test_parameters['parameter_id'];
	// 	$check_if_results_row_was_created=mysqli_query($db,"SELECT *
	// 																																														FROM
	// 																																															lab_requests_results
	// 																																														WHERE
	// 																																															request_id='".$request_id."' AND test_id='".$test_id."' AND parameter_id='".$parameter_id."' AND subscriber_id='".$active_subscriber."'
	// 																																									") or die(mysqli_error($db));
	// 		if(mysqli_num_rows($check_if_results_row_was_created)==0){
	// 			$table='lab_requests_results';
	// 			$fields=array("subscriber_id","request_id","test_id","parameter_id","result");
	// 			$values=array("$active_subscriber","$request_id","$test_id","$parameter_id","");
	// 			$query=insert_data($db,$table,$fields,$values);
	// 		}
	// }



 ?>


		<main class="py-3 ml-lg-5 main" style="background-color:; min-height:100vh">
			<div class="container-fluid mt-2">



				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Completed Tests</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">



				  </div>
				</div>

        <div class="card my-3">
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <label for="">Start Date</label>
                <input type="text" name="start_date" class="form-control" value="">
              </div>
              <div class="col-md-4">
                <label for="">End Date</label>
                <input type="text" name="start_date" class="form-control" value="">
              </div>
              <div class="col-md-4">
              <button type="submit" class="btn btn-primary wide" style="margin-top:23px"><i class="far fa-file-alt mr-2" aria-hidden></i> Generate Report</button>
              </div>
            </div>
          </div>
        </div>


				<div class="card">
					<div class="card-body p-0 xmedici_card_main">
						<ul class="list-group">
						  <li class="list-group-item custom-list-item">
								<div class="row">
								  <div class="col-md-2">
										Request ID
								  </div>
								  <div class="col-md-3">
										Description
								  </div>
								  <div class="col-md-3">
										Patient
								  </div>
								  <div class="col-md-2">
										Sample
								  </div>
								  <div class="col-md-2 text-right">
										Option
								  </div>
								</div>
							</li>


								<?php
								// $s=new Service();
								$get_tests=mysqli_query($db,"SELECT * FROM lab_requests_tests WHERE status='active' AND results_status='complete' AND subscriber_id='".$active_subscriber."'")  or die(mysqli_error($db));
								$i=1;
								while ($rows=mysqli_fetch_array($get_tests)) {
									$test=new Test();
									$test->test_id=$rows['test_id'];
									$test->TestInfo();

									$patient=new Patient();
									$patient->patient_id=$rows['patient_id'];
									$patient->PatientInfo();
									?>
									<li class="list-group-item">
									<div class="row">
										<div class="col-md-2 font-weight-bold">
											<?php echo $rows['request_id']; ?>
									  </div>
									  <div class="col-md-3">
											<?php echo $test->description; ?>
									  </div>
									  <div class="col-md-3">
											<?php echo $patient->patient_fullname; ?>
									  </div>
									  <div class="col-md-2">
											<?php echo $test->specimen; ?>
									  </div>
									  <div class="col-md-2 text-right">
											<a href="test_results_entry.php?test_id=<?php echo $rows['test_id']; ?>&request_id=<?php echo $rows['request_id']; ?>" type="button" class="btn btn-primary btn-sm"><i class="fas fa-file-alt mr-1" aria-hidden></i> Enter Results</a>
									  </div>
									</div>
									</li>

									<?php
								}
								?>

						</ul>
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
		$('#completed_tests_li').addClass('font-weight-bold')


		$('#new_test_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm("Create new test?",function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Laboratory/new_test_frm.php',
						type: 'GET',
						data: $('#new_test_frm').serialize(),
						success: function(msg){
							if(msg==='save_successful'){
								bootbox.alert("Test Created Successfully",function(){
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
