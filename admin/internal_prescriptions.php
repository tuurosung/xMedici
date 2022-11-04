<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	unset($_SESSION['active_drug']);
	$drug=new Drug();
 ?>


		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Internal Prescriptions</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">


				  </div>
				</div>

				<div class="row mb-3 d-none">
					<div class="col-md-3">

						<div class="card">
							<div class="card-body pt-3 pb-1">
								<p>
									<?php
									echo $d->total_drugs;
								 ?>
								 Drugs Added So Far</p>
							</div>
						</div>

					</div>
					<div class="col-md-3">

						<div class="card">
							<div class="card-body pt-3 pb-1">
								<p>
									<?php
									echo $d->total_active;
								 ?>
								 Active Drugs</p>
							</div>
						</div>

					</div>
					<div class="col-md-3">
							<div class="card">
								<div class="card-body pt-3 pb-1">
									<p>
										<?php
										echo $d->total_deleted;
									 ?>
									 Deleted Drugs</p>
								</div>
						</div>
					</div>
				</div>


				<div class="card mb-3">
					<div class="card-body">
						<div class="row">
						  <div class="col-md-3">
								<input type="text" class="form-control" value="" placeholder="search drug">
						  </div>
							<div class="col-md-3">
								<select class="custom-select browser-default" name="" id="drug_category_filter">
									<option value="all_categories">All Categories</option>
									<?php
										$get_categories=mysqli_query($db,"SELECT * FROM pharm_drug_category") or die(mysqli_error($db));
										while ($rows=mysqli_fetch_array($get_categories)) {
											?>
											<option value="<?php echo $rows['category_id']; ?>"><?php echo $rows['category_name']; ?></option>
											<?php
										}
									 ?>
								</select>
						  </div>
							<div class="col-md-3">
								<select class="custom-select browser-default" name="" id="drug_category_filter">
									<option value="all_manufacturers">All Manufacturers</option>
									<?php
										$get_manufacturers=mysqli_query($db,"SELECT * FROM pharm_manufacturers WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
										while ($rows=mysqli_fetch_array($get_manufacturers)) {
											?>
											<option value="<?php echo $rows['manufacturer_id']; ?>"><?php echo $rows['name']; ?></option>
											<?php
										}
									 ?>
								</select>
						  </div>
						</div>

						</div>
				</div>

				<div class="card">
					<div class="card-body p-0">
						<li class="list-group-item custom-list-item">
							<div class="row">
							  <div class="col-md-4">
									Drug Name
							  </div>
							  <div class="col-md-1">
									Disp. Unit
							  </div>
							  <div class="col-md-2">
									Category
							  </div>
							  <div class="col-md-1 text-right">
									Cost
							  </div>
							  <div class="col-md-1 text-right">
									Retail
							  </div>
							  <div class="col-md-1 text-center">
									Qty
							  </div>
							  <div class="col-md-1 text-right">
									Value
							  </div>
								<div class="col-md-1">

								</div>
							</div>
						</li>
						<?php

						$get_items=mysqli_query($db,"SELECT * FROM pharm_inventory WHERE status='active' && subscriber_id='".$active_subscriber."' ORDER BY drug_name asc")  or die('failed');
						$i=1;
						while ($rows=mysqli_fetch_array($get_items)) {

								$category_id=$rows['category'];

								$drug->drug_id=$rows['drug_id'];
								$drug->DrugInfo();

							?>
							<li class="list-group-item hover drugs" id="<?php echo $rows['drug_id']; ?>">
								<div class="row">
									<div class="col-md-4">
										<?php echo trim($rows['drug_name']); ?>
									</div>
									<div class="col-md-1">
										<?php echo $rows['unit']; ?>
									</div>
									<div class="col-md-2">
										<?php echo $drug->CategoryName($rows['category']); ?>
									</div>
									<div class="col-md-1 text-right">
										<?php echo $rows['cost_price']; ?>
									</div>
									<div class="col-md-1 text-right">
										<?php echo $rows['retail_price']; ?>
									</div>
									<div class="col-md-1 text-center">
										<?php echo $drug->qty_rem; ?>
									</div>
									<div class="col-md-1 text-right">
										<?php echo number_format($drug->actual_stock_value,2); ?>
									</div>
									<div class="col-md-1 text-right">
										<i class="fas fa-ellipsis-v" aria-hidden></i>
									</div>
								</div>
							</li>
							<?php
						}
						?>
					</div>
				</div>





		</main>




<div id="new_drug_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right" style="width:400px">
    <div class="modal-content">
			<form id="new_drug_frm">
      <div class="modal-body">
					<h6>Add New Drug</h6>
					<hr class="hr">
					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Unit</label>
								<select class="browser-default custom-select" name="unit">
									<option value="Tab">Tablets</option>
									<option value="Cap">Capsules</option>
									<option value="Blstr">Blister</option>
									<option value="Case">Case</option>
									<option value="Pill">Pill</option>
									<option value="Piece">Piece</option>
									<option value="Vile">Vile</option>
									<option value="Amp">Ampule</option>
									<option value="Pck">Pack</option>
									<option value="Scht">Sachet</option>
									<option value="Box">Box</option>
									<option value="Btl">Bottle</option>
									<option value="Roll">Roll</option>
									<option value="Tube">Tube</option>
					    	</select>
							</div>
					  </div>
					</div>



					<div class="form-group">
					  <label for="">Description</label>
					  <input type="text" class="form-control" id="drug_name" name="drug_name" required="required" autocomplete="off">
					</div>

					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
							  <label for="">Manufacturer</label>
							  <select class="browser-default custom-select" id="manufacturer" name="manufacturer">
									<?php
										// require
										$query=mysqli_query($db,"SELECT * FROM pharm_manufacturers WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
										while ($manufacturers=mysqli_fetch_array($query)) {
											?>
											<option value="<?php echo $manufacturers['manufacturer_id']; ?>"><?php echo $manufacturers['name']; ?></option>
											<?php
										}
									 ?>

								</select>
							</div>
					  </div>
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Category</label>
								<select class="browser-default custom-select" id="category" name="drug_category">
									<?php
										// require
										$query=mysqli_query($db,"SELECT * FROM pharm_drug_category ORDER BY category_name") or die(mysqli_error($db));
										while ($rows=mysqli_fetch_array($query)) {
											?>
											<option value="<?php echo $rows['category_id']; ?>"><?php echo $rows['category_name']; ?></option>
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
							  <label for="">Shelf</label>
							  <input type="text" class="form-control" id="shelf" name="shelf" >
							</div>
					  </div>
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Restock-Limit</label>
								<input type="text" class="form-control" id="restock_level" name="restock_level" autocomplete="off" value="10">
							</div>
					  </div>
					</div>


					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Cost Price</label>
								<input type="text" class="form-control" id="cost_price" name="cost_price" required="required" autocomplete="off">
							</div>
					  </div>
					  <div class="col-md-6">
							<div class="form-group">
							  <label for="">Retail Price</label>
							  <input type="text" class="form-control" id="retail_price" name="retail_price" required="required" autocomplete="off">
							</div>
					  </div>
					</div>


      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn btn-black">Add Drug</button>
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
		$('#inventory_nav').addClass('active')
		$('#inventory_submenu').addClass('show')
		$('#drugs_li').addClass('font-weight-bold')

		$('.drugs').on('click', function(event) {
			event.preventDefault();
			var drug_id=$(this).attr('ID');
			window.location='drug_matrix.php?drug_id='+drug_id
		});

		$('#drug_category_filter').select2();

    	$(document).ready(function(){

					$('#print').on('click', function(event) {
						event.preventDefault();
						print_popup('print_inventory.php');
					});


					$('#new_drug_modal').on('shown.bs.modal', function(event) {
						event.preventDefault();
						$('#drug_name').focus()
					});//end modal shown

					$('#new_drug_frm').on('submit', function(event) {
						event.preventDefault();
						bootbox.confirm("Add this drug to inventory",function(r){
							if(r===true){
								$.ajax({
									url: '../serverscripts/admin/Drugs/new_drug_frm.php',
									type: 'GET',
									data: $('#new_drug_frm').serialize(),
									success: function(msg){
										if(msg==='save_successful'){
											bootbox.alert("Drug added successfully",function(){
												window.location='drug_matrix.php'
											})
										}
										else {
											bootbox.alert(msg)
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
