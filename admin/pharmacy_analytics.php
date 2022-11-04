<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	unset($_SESSION['active_drug']);
	$drug=new Drug();
 ?>


		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-5 ">
						<h4 class="titles montserrat mb-5">Pharmacy Analytics</h4>
				  </div>

				</div>

				<!-- Pills navs -->
					<ul class="nav xmedici_pills nav-pills mb-5" id="ex1" role="tablist">
					  <li class="nav-item" role="presentation">
					    <a
					      class="nav-link active poppins"
					      id="ex1-tab-1"
					      data-toggle="pill"
					      href="#ex1-pills-1"
					      role="tab"
					      aria-controls="ex1-pills-1"
					      aria-selected="true"
					      >Summary</a
					    >
					  </li>
					  <li class="nav-item" role="presentation">
					    <a
					      class="nav-link poppins"
					      id="ex1-tab-2"
					      data-toggle="pill"
					      href="#ex1-pills-2"
					      role="tab"
					      aria-controls="ex1-pills-2"
					      aria-selected="false"
					      >Stock Report</a
					    >
					  </li>
					  <li class="nav-item" role="presentation">
					    <a
					      class="nav-link poppins"
					      id="ex1-tab-3"
					      data-toggle="pill"
					      href="#ex1-pills-3"
					      role="tab"
					      aria-controls="ex1-pills-3"
					      aria-selected="false"
					      >Upcoming Expiries</a
					    >
					  </li>
					</ul>
					<!-- Pills navs -->

					<!-- Pills content -->
					<div class="tab-content" id="ex1-content">
					  <div  class="tab-pane fade show active"   id="ex1-pills-1"  role="tabpanel"  aria-labelledby="ex1-tab-1">

							<div class="row">
								<div class="col-md-8 p-4 bg-white" style="border-radius:10px">

									<div class="row">
										<div class="col-md-7">
											<div class="card bg-primary">
												<div class="card-body white-text">
													<p class="poppins">Retail Stock Value</p>
													<h6 class="montserrat font-weight-bold" style="font-size:30px">GHS <?php echo number_format($drug->total_expected_stock_value,2); ?></h6>
													<hr>
													<p>Retail stock value is calculated by the retail price</p>
													<p>Actual stock value is calculated by the wholesale price</p>
													<p>Profit Margin Represents the difference</p>
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class="card mb-3">
												<div class="card-body">
													<p>Actual Stock Value</p>
													<p class="big-text">GHS <?php echo number_format($drug->total_stock_value,2); ?></p>
												</div>
											</div>
											<div class="card">
												<div class="card-body">
													<p>Profit Margin</p>
													<p class="big-text">GHS <?php echo number_format($drug->total_profit_margin,2); ?></p>
												</div>
											</div>
										</div>
									</div>

									<div class="card mt-5">
											<div class="card-body">
												<h6 class="font-weight-bold montserrat">Sales Performance - Top 10 Drugs</h6>

												<?php
														$high_performance=mysqli_query($db,"SELECT
																																																				drug_id,SUM(qty) as qty_sold,SUM(total) as total_sale
																																																			FROM
																																																				pharm_cart
																																																			WHERE
																																																				subscriber_id='".$active_subscriber."' AND status='CHECKOUT'
																																																			GROUP BY
																																																				drug_id
																																																			ORDER BY  total_sale desc
																																														") or die(mysqli_error($db));
															while ($rows=mysqli_fetch_array($high_performance)) {
																$drug->drug_id=$rows['drug_id'];
																$drug->DrugInfo();
																?>
																<li class="list-group-item" style="font-size:11px">
																	<div class="row">
																	  <div class="col-md-6">
																			<?php echo $drug->drug_name; ?>
																	  </div>
																		<div class="col-3 text-right">
																			<?php echo $rows['qty_sold'];?>
																		</div>
																		<div class="col-3 text-right">
																			<?php echo number_format($rows['total_sale'],2);?>
																		</div>
																	</div>
																</li>
																<?php
															}
												 ?>
											</div>
									</div>

								</div>
								<div class="col-md-4">
									<div class="card">
										<div class="card-body p-3">
											<h6 class="montserrat font-weight-bold">High-End Drugs</h6>

											<?php

													$high_end=mysqli_query($db,"	SELECT *
																																						FROM
																																							pharm_inventory
																																						WHERE
																																							status='active' AND subscriber_id='".$active_subscriber."'
																																						ORDER BY retail_price DESC
																																						LIMIT 10
																																	") or die(mysqli_error($db));
														while ($rows=mysqli_fetch_array($high_end)) {
															?>
															<li class="list-group-item p-0 py-2" style="border:none !important;">
																<div class="row poppins">
																  <div class="col-8">
																		<?php echo $rows['trade_name']; ?>
																  </div>
																  <div class="col-4 text-right">
																		<?php echo $rows['retail_price']; ?>
																  </div>
																</div>

															</li>
															<?php
														}

											 ?>
										</div>
									</div>
								</div>
							</div>



					  </div>
					  <div class="tab-pane fade" id="ex1-pills-2" role="tabpanel" aria-labelledby="ex1-tab-2">

								<div class="card">
									<div class="card-body p-0" id="data_holder">
										<!-- <h6 class="font-weight-bold montserrat p-2">Stock Value</h6> -->

										<li class="list-group-item custom-list-item">
											<div class="row" style="font-size:11px">
											  <div class="col-md-6">
													Drug Name
											  </div>
											  <div class="col-md-1 text-center">
													Stocked
											  </div>
												<div class="col-md-1 text-center">
													Sold
											  </div>
												<div class="col-md-1 text-center">
													Rem
											  </div>
												<div class="col-md-1 text-right">
													Actual
											  </div>
												<div class="col-md-1 text-right">
													Exp
											  </div>
												<div class="col-md-1 text-right">
													Profit
											  </div>
											</div>
										</li>
										<?php

										$get_items=mysqli_query($db,"

										SELECT * FROM (

									SELECT 	i.drug_id,
											i.cost_price,
											i.retail_price,
											i.status,
											IFNULL(stocked.qty_stocked,0) as total_stocked,
											IFNULL(sold.qty_sold,0) as total_sold,
											IFNULL(stocked.qty_stocked,0) - IFNULL(sold.qty_sold,0) as qty_rem,
											(IFNULL(stocked.qty_stocked,0) - IFNULL(sold.qty_sold,0)) * i.cost_price as actual_value,
											(IFNULL(stocked.qty_stocked,0) - IFNULL(sold.qty_sold,0)) * i.retail_price as expected_value,
											((IFNULL(stocked.qty_stocked,0) - IFNULL(sold.qty_sold,0)) * i.retail_price) - ((IFNULL(stocked.qty_stocked,0) - IFNULL(sold.qty_sold,0)) * i.cost_price) as profit_margin
									FROM pharm_inventory i

										left join (
													SELECT drug_id,SUM(qty_stocked) as qty_stocked FROM stock WHERE subscriber_id='".$active_subscriber."'
													GROUP BY drug_id
													) stocked on i.drug_id=stocked.drug_id

										left join (
													SELECT drug_id,SUM(qty) as qty_sold FROM pharm_cart WHERE subscriber_id='".$active_subscriber."'
													GROUP BY drug_id
												)sold ON i.drug_id=sold.drug_id

										WHERE i.status='active' AND i.subscriber_id='".$active_subscriber."'
									)stock_value



																													")  or die(mysqli_error($db));
										$i=1;
										while ($rows=mysqli_fetch_array($get_items)) {

												$category_id=$rows['category'];

												$drug->drug_id=$rows['drug_id'];
												$drug->DrugInfo();

											?>
											<li class="list-group-item hover drugs py-3" id="<?php echo $rows['drug_id']; ?>">
												<div class="row" style="font-size:11px">
													<div class="col-md-6">
														<?php echo $drug->drug_name; ?>
													</div>
													<div class="col-md-1 text-center">
														<?php echo $rows['total_stocked']; ?>
													</div>
													<div class="col-md-1 text-center">
														<?php echo $rows['total_sold']; ?>
													</div>
													<div class="col-md-1 text-center">
														<?php echo $rows['qty_rem']; ?>
													</div>
													<div class="col-md-1 text-right">
														<?php echo number_format($rows['actual_value'],2); ?>
													</div>
													<div class="col-md-1 text-right">
														<?php echo number_format($rows['expected_value'],2); ?>
													</div>
													<div class="col-md-1 text-right">
														<?php echo number_format($rows['profit_margin'],2); ?>
													</div>


												</div>
											</li>
											<?php
										}
										?>
									</div>
								</div>

					  </div>
					  <div class="tab-pane fade" id="ex1-pills-3" role="tabpanel" aria-labelledby="ex1-tab-3">

							<div class="card">
								<div class="card-body p-0" id="data_holder">
									<!-- <h6 class="font-weight-bold montserrat p-2">Stock Value</h6> -->

									<li class="list-group-item custom-list-item">
										<div class="row" style="font-size:11px">
											<div class="col-md-6">
												Drug Name
											</div>
											<div class="col-md-2">
												Expiry Date
											</div>
											<div class="col-md-2">
												Qty Stocked
											</div>
											<div class="col-md-1 text-center">
												Days
											</div>
										</div>
									</li>

									<?php

											$get_stock=mysqli_query($db,"SELECT * FROM stock WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
											while ($stock=mysqli_fetch_array($get_stock)) {
												$drug->drug_id=$stock['drug_id'];
												$drug->DrugInfo();

												$days_remaining=$drug->Expiry($stock['expiry_date']);
												if($days_remaining > 90){
													continue;
												}
												?>
												<li class="list-group-item drugs" id="<?php echo $stock['drug_id']; ?>">
													<div class="row" style="font-size:11px">
														<div class="col-md-6">
															<?php echo $drug->drug_name; ?>
														</div>
														<div class="col-md-2">
															<?php echo $stock['expiry_date']; ?>
														</div>
														<div class="col-md-2">
															<?php echo $stock['qty_stocked']; ?>
														</div>
														<div class="col-md-1 text-center">
															<?php echo $days_remaining; ?>
														</div>
													</div>
												</li>

												<?php
											}

									 ?>

								 </div>
							 </div>

					  </div>
					</div>
					<!-- Pills content -->












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
			})
		});

		$('.drugs').on('click', function(event) {
			event.preventDefault();
			var drug_id=$(this).attr('ID')
			window.location='drug_matrix.php?drug_id='+drug_id
		});

	</script>

</html>
