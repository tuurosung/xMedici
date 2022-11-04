<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	unset($_SESSION['active_drug']);
	$drug=new Drug();
 ?>


		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-3 ">
						<h4 class="titles montserrat mb-5">Drug Inventory</h4>
				  </div>
					<div class="col-md-3 d-none">
						<input type="text" name="search_drug" id="search_drug" class="form-control" value="" placeholder="search here">
					</div>
					<div class="col-md-3 d-none">
						<div class="form-group">
							<select class="custom-select browser-default" name="drug_category" id="filter_drugs_category">
								<option value="">Filter List</option>
								<option value="all">View All Drugs</option>
								<option value="deleted">Delete Drugs</option>
								<?php
									$query=mysqli_query($db,"SELECT * FROM pharm_drug_category ORDER BY category_name	asc") or die(mysqli_error($db));
									while ($categories=mysqli_fetch_array($query)) {
										?>
										<option value="<?php echo $categories['category_id']; ?>"><?php echo $categories['category_name']; ?></option>
										<?php
									}
								 ?>
							</select>
						</div>
					</div>

				  <div class="col-md-9 text-right mb-5">

						<div class="dropdown open">
						  <button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
								<ul class="list-group">
								  <li class="list-group-item" data-toggle="modal" data-target="#new_drug_modal"><i class="fas fa-plus mr-3" aria-hidden></i> New Drug</li>
								  <li class="list-group-item" data-toggle="modal" data-target="#new_manufacturer_modal"><i class="fas fa-plus mr-3" aria-hidden></i> Add Manufacturer</li>
								  <li class="list-group-item" id="print_report"><i class="fas fa-print mr-3" aria-hidden></i> Print Report</li>
								</ul>
						  </div>
						</div>

				  </div>
				</div>



				<div class="row" style="margin-bottom:70px">
					<div class="col-md-3">

						<div class="card">
							<div class="card-body">
								<div class="row">
								  <div class="col-8">
										<p class="big-text">
											<?php
											echo $drug->total_drugs;
										 ?>
										</p>
										<p>Drugs Added So Far</p>
								  </div>
								  <div class="col-4">
										<div class="icon-box primary-color d-flex justify-content-center align-items-center">
											<i class="fas fa-pills" aria-hidden></i>
										</div>
								  </div>
								</div>

							</div>
						</div>

					</div>
					<div class="col-md-3">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-8">
										<p class="big-text">
											<?php	echo $drug->total_active;?>
										</p>
										<p>Active Drugs</p>
									</div>
									<div class="col-4">
										<div class="icon-box primary-color d-flex justify-content-center align-items-center">
											<i class="fas fa-check" aria-hidden></i>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
					<div class="col-md-3">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col-8">
											<p class="big-text">
												<?php	echo $drug->total_deleted;?>
											</p>
											<p>Deleted Drugs</p>
										</div>
										<div class="col-4">
											<div class="icon-box primary-color d-flex justify-content-center align-items-center">
												<i class="fas fa-trash-alt" aria-hidden></i>
											</div>
										</div>
									</div>

								</div>
						</div>
					</div>
				</div>


				<div class="" id="data_holder">

					<div class="card">
						<div class="card-body py-5">
							<table class="datatables table table-condensed" style="width:100%">
							  <thead>
							    <tr>
							      <th>#</th>
							      <th>Description</th>
							      <th>Unit</th>
							      <th class="text-center">Qty</th>
							      <th class="text-right">Cost Price</th>
							      <th class="text-right">Retail Price</th>
							      <th class="text-right">Profit</th>
							      <th class="text-right">Option</th>
							    </tr>
							  </thead>
							  <tbody>
									<?php

									$get_items=mysqli_query($db,"SELECT *
																																		FROM
																																			pharm_inventory
																																		WHERE
																																			status='active' && subscriber_id='".$active_subscriber."'
																																		ORDER BY
																																			generic_name asc
																													")  or die(mysqli_error($db));
									$i=1;
									while ($rows=mysqli_fetch_array($get_items)) {

											$category_id=$rows['category'];

											$drug->drug_id=$rows['drug_id'];
											$drug->DrugInfo();

										?>


							    <tr>
							      <td><?php echo $i++; ?></td>
							      <td><?php echo $drug->drug_name; ?></td>
							      <td><?php echo $rows['unit']; ?></td>
							      <td class="text-center"><?php echo (int) $drug->qty_rem; ?></td>
							      <td class="text-right"><?php echo $drug->cost_price; ?></td>
							      <td class="text-right"><?php echo $drug->retail_price; ?></td>
							      <td class="text-right"><?php echo $drug->profit; ?></td>
							      <td class="text-right">
											<a href="drug_matrix.php?drug_id=<?php echo $drug->drug_id; ?>" type="button" class="btn btn-primary btn-sm">Matrix</a>
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




<div id="new_drug_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:900px">
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
									<?php
                    $get_units=mysqli_query($db,"SELECT * FROM drug_units") or die(mysqli_error($db));
                    while ($units=mysqli_fetch_array($get_units)) {
                      ?>
                      <option value="<?php echo $units['code'] ?>" <?php if($drug->unit==$units['code']){ echo 'selected'; } ?>><?php echo $units['description']; ?></option>
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
								<label for="">Generic Name</label>
								<input type="text" class="form-control" id="generic_name" name="generic_name" required="required">
							</div>
					  </div>
					  <div class="col-md-6">
							<div class="form-group">
							  <label for="">Brand Name (Trade Name)</label>
							  <input type="text" class="form-control" id="trade_name" name="trade_name" required="required" autocomplete="off">
							</div>
					  </div>
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

<div id="new_manufacturer_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right" style="width:400px">
    <div class="modal-content">
			<form id="new_manufacturer_frm">
      <div class="modal-body">
					<h6>New Manufacturer</h6>
					<hr class="hr">

					<div class="form-group">
					  <label for="">Manufacturer Name</label>
					  <input type="text" class="form-control" id="manufacturer_name" name="manufacturer_name" required="required" autocomplete="off">
					</div>

					<div class="form-group">
					  <label for="">Address</label>
					  <input type="text" class="form-control" id="address" name="address"  autocomplete="off">
					</div>


      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn btn-black">Add Manufacturer</button>
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
	<script type="text/javascript" src="../mdb/js/xmedici/pharm_manufacturers.js"></script>
	<script type="text/javascript" src="../mdb/js/xmedici/inventory.js"></script>
	<script type="text/javascript">
		$('.sidebar-fixed .list-group-item').removeClass('active')
		$('#pharmacy_nav').addClass('active')
		$('#pharmacy_submenu').addClass('show')
		$('#inventory_li').addClass('font-weight-bold')

		$('#search_drug').on('keyup', function(event) {
			event.preventDefault();
			var search_term=$(this).val()
			$.get('../serverscripts/admin/Drugs/filter_drugs.php?search_term='+search_term,function(msg){
				$('#data_holder').html(msg)
				$('.drugs').on('click', function(event) {
				  event.preventDefault();
				  var drug_id=$(this).attr('ID');
				  window.location='drug_matrix.php?drug_id='+drug_id
				});
			})
		});

		$('#filter_drugs_category').on('change', function(event) {
			event.preventDefault();
			var category_id=$(this).val();
			$.get('../serverscripts/admin/Drugs/filter_drugs_category.php?category_id='+category_id,function(msg){
				$('#data_holder').html(msg)
			})
		});

		$('#print_report').on('click', function(event) {
			event.preventDefault();
			window.open('print_inventory.php','_blank','width=300','height:800');
		});

	</script>

</html>
