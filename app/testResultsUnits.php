<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$p=new Patient();
 ?>


		<main class="py-3 ml-lg-5 main" style="background-color:; min-height:100vh">
			<div class="container-fluid mt-2">



				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Test Units</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5 ">


					<button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#newUnitModal">
						<i class="fas fa-plus mr-2"></i> Add Unit</button>
				  </div>
				</div>

				<div class="card">
					<div class="card-body py-5">

						<div class="row mb-5">
						  <div class="col-4">
								<h6 class="font-weight-bold montserrat">List Of Measuring Units</h6>
						  </div>
						  <div class="col-8 d-flex flex-row-reverse">

						  </div>
						</div>


						<table class="table table-condensed datatables">
							<thead class="grey lighten-4">
								<tr>
									<th>#</th>
									<th>Description</th>
									<th>Unit</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i=1;
								$s=new Service();
								$get_units=mysqli_query($db,"SELECT * FROM measurement_units WHERE status='active'")  or die(mysqli_error($db));
								$i=1;
								while ($rows=mysqli_fetch_array($get_units)) {
									// $test=new Test();

									?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?php echo $rows['name']; ?></td>
										<td><?php echo $rows['unit']; ?></td>
										<td class="text-right">
											<div class="dropdown open">
												<button type="button" class="btn btn-primary dropdown-toggle btn-sm cursor" aria-hidden id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Option</button>

												<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
													<ul class="list-group minioptions">
														<li class="list-group-item" id="#">Edit</li>
														<li class="list-group-item" id="#">Delete</li>
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




<div id="newUnitModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog " style="">
    <div class="modal-content">
			<form id="newUnitFrm" autocomplete="off">
      <div class="modal-body">
					<h5 class="font-weight-bold montserrat">Add New Unit</h5>
					<hr class="hr mb-3">




						<div class="row poppins">
						  <div class="col-md-6">
						    <div class="form-group">
									<label for="">Description</label>
									<input type="text" class="form-control" name="UnitName" id="UnitName" placeholder="eg. Gram per Decilitre">
						    </div>
						  </div>
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Symbol</label>
									<input type="text" class="form-control" name="unit" id="unit"  placeholder="g/dL">
						    </div>
						  </div>
						</div>





      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn primary-color-dark">
					<i class="fas fa-check mr-3"></i>
					Create Unit</button>
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
		$('#units_li').addClass('font-weight-bold')


		$('#newUnitFrm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/Laboratory/new_unit_frm.php',
				type: 'GET',
				data: $('#newUnitFrm').serialize(),
				success: function(msg){
					if(msg==='save_successful'){
						bootbox.alert("Unit Added Successfully",function(){
							window.location.reload()
						})
					}else {
						bootbox.alert(msg,function(){
							window.location.reload()
						})
					}
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
