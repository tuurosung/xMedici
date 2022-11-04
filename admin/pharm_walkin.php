<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	// unset($_SESSION['active_drug']);
	$drug=new Drug();
	$pharmacy=new Pharmacy();

	if(isset($_SESSION['pharm_walkin_transaction_id'])){
		$transaction_id=$_SESSION['pharm_walkin_transaction_id'];
	}else {
		$transaction_id=$pharmacy->WalkInTransactionIdGen();
	}
 ?>


		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-8 ">
						<h4 class="titles montserrat mb-5">Walk-In Dispensary</h4>
				  </div>

				  <div class="col-md-4 text-right mb-5">

						<!-- <div class="dropdown open">
						  <button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
								<ul class="list-group">
								  <li class="list-group-item" data-toggle="modal" data-target="#new_drug_modal"><i class="fas fa-plus mr-3" aria-hidden></i> New Drug</li>
								  <li class="list-group-item" data-toggle="modal" data-target="#new_manufacturer_modal"><i class="fas fa-plus mr-3" aria-hidden></i> Add Manufacturer</li>
								</ul>
						  </div>
						</div> -->

				  </div>
				</div>

				<form id="cart_frm" autocomplete="off">
				<div class="card mb-5">
					<div class="card-body">
						<div class="row">
						  <div class="col-md-4">
								<label for="">Select Drug</label>
								<select class="custom-select browser-default" name="drug_id" id="drug_id">

								</select>
						  </div>
							<div class="col-md-2">
								<label for="">Unit Cost</label>
								<input type="text" class="form-control" name="retail_price" id="retail_price" value="" required readonly>
							</div>
							<div class="col-md-1">
								<label for="">Qty</label>
								<input type="text" class="form-control" name="qty" id="qty" value="" required>
							</div>
							<div class="col-md-2">
								<label for="">Total</label>
								<input type="text" class="form-control" name="total" id="total" value="" required readonly>
							</div>
							<div class="col-md-3">
								<button type="submit" class="btn btn-primary wide" style="margin-top:23px"><i class="fas fa-cart-plus mr-2" aria-hidden></i> Add To Cart </button>
							</div>
						</div>
					</div>
				</div>
				</form>


				<div class="row">
				  <div class="col-md-8">
						<div class="card">
							<div class="card-body" id="data_holder">
								<h6 class="montserrat font-weight-bold">Cart</h6>
								<hr class="hr">



								<table class="table table-condensed datatable">
								  <thead class="grey lighten-4">
								    <tr>
								      <th>#</th>
								      <th>Drug Name</th>
								      <th>Retail Price</th>
								      <th class="text-center">Qty</th>
								      <th class="text-right">Total</th>
											<th>Options</th>
								    </tr>
								  </thead>
								  <tbody>




								<?php

								$get_items=mysqli_query($db,"SELECT *
																																	FROM
																																		pharm_walkin_cart
																																	WHERE
																																		transaction_id='".$transaction_id."' AND status='active' AND subscriber_id='".$active_subscriber."'


																												")  or die(mysqli_error($db));
								$i=1;
								while ($rows=mysqli_fetch_array($get_items)) {

										$drug->drug_id=$rows['drug_id'];
										$drug->DrugInfo();

									?>
									<tr class="drugs" id="<?php echo $rows['drug_id']; ?>">
										<td><?php echo $i++; ?></td>
										<td><?php echo $drug->drug_name; ?></td>
										<td><?php echo $rows['retail_price']; ?></td>
										<td class="text-right"><?php echo (int) $drug->qty_rem; ?></td>
										<td class="text-right"><?php echo $rows['total']; ?></td>
										<td class="text-right">
											<button type="button" class="btn btn-white btn-sm remove_drug" id="<?php echo $rows['drug_id']; ?>">
												<i class="fas fa-trash mr-2" aria-hidden></i>
												Remove
											</button>
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
				  <div class="col-md-4">
						<div class="card">
							<div class="card-body">
								<h6 class="montserrat font-weight-bold"> <i class="fas fa-credit-card mr-2" aria-hidden></i> Checkout</h6>
								<hr class="hr">

								<form id="checkout_frm" autocomplete="off">

									<div class="form-group d-none">
										<label for="">Transaction ID</label>
										<input type="text" name="transaction_id" class="form-control" value="<?php echo $transaction_id; ?>" readonly>
									</div>

									<div class="form-group">
										<label for="">Total Amount</label>
										<input type="text"  name="total_amount" class="form-control" value="<?php echo $pharmacy->CartSum($transaction_id); ?>" readonly>
									</div>

									<button type="submit" class="btn btn-primary m-0 wide">
										<i class="fas fa-credit-card mr-2" aria-hidden></i>
										Checkout
									</button>



									<button type="button" class="btn btn-white wide m-0 mt-5" id="destroy_cart">
										<i class="fas fa-trash mr-2" aria-hidden></i> Destroy Cart
									</button>

								</form>
							</div>
						</div>
				  </div>
				</div>







		</main>





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
		$('#walkin_li').addClass('font-weight-bold')

		$('#drug_id').select2({
					placeholder: 'Select a drug',
					ajax: {
							url: '../serverscripts/admin/Drugs/filter_drugs_select.php',
							dataType: 'json',
							delay: 100,
							data: function (data) {
									return {
											searchTerm: data.term // search term
									};
							},
							processResults: function (response) {
									return {
											results:response
									};
							},
							cache: true
					}
			});




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

		$('#drug_id').on('change', function(event) {
			event.preventDefault();
			var drug_id=$(this).val();
			$.get('../serverscripts/admin/Drugs/get_drug_price.php?drug_id='+drug_id,function(msg){
				$('#retail_price').val(msg)
			})
		});


		$('#qty').on('keyup', function(event) {
			event.preventDefault();
			var qty=parseInt($('#qty').val())
			var retail_price=parseFloat($('#retail_price').val())
			$('#total').val((qty* retail_price).toFixed(2))
			$('#qty').focus();
		});


		$('#cart_frm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/Pharmacy/add_to_walkin_cart.php',
				type: 'GET',
				data:$('#cart_frm').serialize(),
				success:function(msg){
						if(msg==='save_successful'){
							bootbox.alert("Drug added to cart",function(){
								window.location.reload();
							})
						}else {
							bootbox.alert(msg)
						}
				}
			})
		});


		$('#checkout_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm("Confirm checkout and forward this invoice to accounts?",function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Pharmacy/walkin_cart_checkout.php',
						type: 'GET',
						data:$('#checkout_frm').serialize(),
						success:function(msg){
							if(msg==='save_successful'){
								bootbox.alert('Bill sent for payment. Pay and take your drugs',function(){
									window.location.reload();
								})
							}else {
								bootbox.alert(msg)
							}
						}
					})//end ajax
				}
			})
		});

		$('#destroy_cart').on('click', function(event) {
			event.preventDefault();
			bootbox.confirm("Proceed to destroy this cart?",function(r){
				if(r===true){
					window.location='pharm_destroy_walkin_cart.php'
				}
			})
		});

	</script>

</html>
