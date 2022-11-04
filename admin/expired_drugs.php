<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$d=new Drug();
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
						<h4 class="titles montserrat mb-5">Expired Drugs</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">
						<div class="btn-group">

							<button type="button" name="button" class="btn btn-primary m-0 btn-rounded mr-3" data-toggle="modal" data-target="#new_drug_modal">
								<i class="fas fa-plus mr-3"></i>
								New Drug
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

				<div class="row">

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


				<div class="table-responsive mt-5">
					<table class="table table-condensed">
						<thead>
							<tr>
								<th>#</th>
								<th>Drug Name</th>
								<th class="text-right">Qty</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$j=1;
								$get_drugs=mysqli_query($db,"SELECT * FROM stock WHERE expiry_date < '$today' AND subscriber_id='".$active_subscriber."' AND status='active' ORDER BY expiry_date asc") or die(mysqli_error($db));
								while ($drugs=mysqli_fetch_array($get_drugs)) {
									$drug_info=drug_info($drugs['drug_id']);
									if($drug_info['drug_name']==''){
										continue;
									}

									if($drugs['qty_rem']==0){
										continue;
									}
									//
									if($drugs['expiry_date']=='0000-00-00'){
										continue;
									}

									//
									// $d1=date_create($drugs['expiry_date']);
									// $d2=date_create($today);
									// $interval=date_diff($d1,$d2);
									// $days= $interval ->format('%a days');
									?>
									<tr>
										<td><?php echo $j++; ?></td>
										<td style="font-weight:400px;">
											<a style="font-size:11px" href="drug_matrix.php?drug_id=<?php echo $drugs['drug_id']; ?>">
												<?php echo $drug_info['drug_name']; ?>
											</a>
										</td>
										<td class="text-right">
											<?php echo $drugs['qty_rem']; ?>
										</td>
									</tr>


									<?php
								}

							 ?>
						</tbody>
					</table>
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

					<div class="form-group d-none">
					  <label for="">Drug Code</label>
					  <input type="text" class="form-control" id="drug_id" name="drug_id" value="<?php echo drug_idgen(); ?>" readonly>
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
										$query=mysqli_query($db,"SELECT * FROM manufacturers WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
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
										$query=mysqli_query($db,"SELECT * FROM drug_category ORDER BY category_name") or die(mysqli_error($db));
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
					  <div class="col-md-4">
							<div class="form-group">
								<label for="">Cost Price</label>
								<input type="text" class="form-control" id="cost_price" name="cost_price" required="required" autocomplete="off">
							</div>
					  </div>
					  <div class="col-md-4">
							<div class="form-group">
							  <label for="">Wholesale Price</label>
							  <input type="text" class="form-control" id="wholesale_price" name="wholesale_price" required="required" autocomplete="off">
							</div>
					  </div>
					  <div class="col-md-4">
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

					$('#new_drug_frm').one('submit', function(event) {
						event.preventDefault();
						var drug_id=$('#drug_id').val()
						bootbox.confirm("Add this drug to inventory",function(r){
							if(r===true){
								$.ajax({
									url: '../serverscripts/admin/new_item_frm.php',
									type: 'GET',
									data: $('#new_drug_frm').serialize(),
									success: function(msg){
										if(msg==='save_successful'){
											bootbox.alert("Drug added successfully",function(){
												window.location='drug_matrix.php?drug_id='+drug_id
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
