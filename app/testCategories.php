<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$p=new Patient();
 ?>


		<main class="py-3 ml-lg-5 main" style="background-color:; min-height:100vh">
			<div class="container-fluid mt-2">



				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Laboratory Categories</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5 ">


						<div class="dropdown open">
						  <button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Options
						  </button>
						  <div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
								<ul class="list-group">
								  <li class="list-group-item" data-toggle="modal" data-target="#new_service_modal">Create New Test</li>
								  <li class="list-group-item" data-toggle="modal" data-target="#new_category_modal">Create Category</li>
								  <li class="list-group-item">Print Pricelist</li>
								</ul>
						  </div>
						</div>

				  </div>
				</div>

				<div class="card">
					<div class="card-body py-5">

						<div class="row mb-5">
						  <div class="col-4">
								<h6 class="font-weight-bold montserrat">List Of Categories</h6>
						  </div>
						  <div class="col-8 d-flex flex-row-reverse">
								
						  </div>
						</div>


						<table class="table table-condensed datatables">
							<thead class="grey lighten-4">
								<tr>
									<th>#</th>
									<th>Description</th>
									<th>Category</th>
									<th>Specimen</th>
									<th>Cost</th>
									<th>Date Added</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i=1;
								$s=new Service();
								$get_services=mysqli_query($db,"SELECT * FROM lab_tests WHERE status='active' && subscriber_id='".$active_subscriber."'")  or die(mysqli_error($db));
								$i=1;
								while ($rows=mysqli_fetch_array($get_services)) {
									$test=new Test();

									?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?php echo $rows['description']; ?></td>
										<td><?php echo $test->TestCategory($rows['test_category']); ?></td>
										<td><?php echo $rows['specimen']; ?></td>
										<td><?php echo $rows['test_cost']; ?></td>
										<td><?php echo $rows['date']; ?></td>
										<td class="text-right">
											<div class="dropdown open">
												<button type="button" class="btn btn-primary dropdown-toggle btn-sm cursor" aria-hidden id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Option</button>

												<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
													<ul class="list-group minioptions">
														<a class="list-group-item" href="manage_test.php?test_id=<?php echo $rows['test_id']; ?>">Manage</a>
														<li class="list-group-item edit_test_btn" id="<?php echo $rows['test_id']; ?>">Edit</li>
														<li class="list-group-item delete_test_btn" id="<?php echo $rows['test_id']; ?>">Delete</li>
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
												$get_categories=mysqli_query($db,"SELECT * FROM lab_test_categories WHERE status='active' AND (subscriber_id='".$active_subscriber."' OR subscriber_id='system')") or die(mysqli_error($db));
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
								<option value="N/A">N/A</option>
								<option value="Blood">Blood</option>
								<option value="Urine">Urine</option>
								<option value="Stool">Stool</option>
								<option value="Seminal Fluid">Seminal Fluid</option>
							</select>
						</div>

						<div class="form-group">
              <label for="">Comment</label>
              <textarea name="comment" class="form-control"></textarea>
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




<div id="new_category_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
			<form id="new_category_frm">
      <div class="modal-body">

					<h6 class="montserrat font-weight-bold mb-4">Create New Category</h6>
					<hr class="hr">

					<div class="form-group">
						<label for="">Category Name</label>
						<input type="text" class="form-control" name="category_name" value="">
					</div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2" aria-hidden></i> Create Category</button>
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


		$('.table tbody').on('click','.edit_test_btn', function(event) {
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


		$('.table tbody').on('click', '.delete_test_btn',function(event) {
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


		$('#new_category_frm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/Laboratory/new_category_frm.php',
				type: 'GET',
				data:$("#new_category_frm").serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						window.location.reload()
					}else {
						bootbox.alert(msg)
					}
				}//end success
			})
		});


		$('.delete_category_btn').on('click', function(event) {
			event.preventDefault();
			var category_id=$(this).attr('ID')
			bootbox.confirm('Delete this category?',function(r){
				if(r===true){
					$.get('../serverscripts/admin/Laboratory/delete_category.php?category_id='+category_id,function(msg){
						if(msg==='delete_successful'){
							bootbox.alert('Category Deleted',function(){
								window.location.reload()
							})
						}else {
							bootbox.alert(msg)
						}
					})
				}
			})
		});

	</script>

</html>
