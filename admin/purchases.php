<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

		<main class="py-3 mx-lg-5">
			<div class="container-fluid mt-2">

				<div class="row mb-5">
				  <div class="col-md-6">
				    	<h4 class="titles">Purchase Invoices</h4>
				  </div>
				  <div class="col-md-6 text-right">
						<button type="button" class="btn btn-primary">Create New</button>
				  </div>
				</div>

        <div class="content">
            <div class="container-fluid">

							<div class="row">
							  <div class="col-md-8">
									<div class="card" >
										<div class="" id="data_grid">

										</div>
									</div>
							  </div>
							  <div class="col-md-4">
									<div class="card">
										<div class="content">

											<button type="button" name="button" class="btn btn-primary  custom_button_purple" id="view_invoices" style="width:70%">
												<i class="fa fa-binoculars"></i>
												Purchases
											</button>
											<button type="button" name="button" class="btn btn-primary  custom_button_purple" id="new_invoice_btn" >
												New
											</button>
											<br><br>
											<button type="button" name="button" class="btn btn-primary  custom_button_orange" id="view_suppliers" style="width:70%">
												<i class="fa fa-truck"></i>
												Suppliers
											</button>
											<button type="button" name="button" class="btn btn-primary  custom_button_orange" id="new_supplier_btn">
												New
											</button>
											<br><br>

											<div class="panel panel-primary">
												<div class="panel-heading">
													Monthly Purchase
												</div>
												<div class="panel-body" >
													<h3 id="purchase_holder"></h3>
												</div>
											</div>



										</div>
									</div>
							  </div>
							</div>

            </div>   <!-- container ends here -->
        </div>


        <footer class="footer">
            <div class="container-fluid">

                <p class="copyright pull-right">
                    &copy; 2016 <a href="#">Powered by Kindred GH. Technologies
                </p>
            </div>
        </footer>

    </div>
</div>


<div class="hidden" id="new_supplier_div">
	<div class="header">
		<h4 class="title">New Supplier Registration</h4>
	</div>
	<hr>
	<div class="content">
		<form id="new_supplier_frm">
			<div class="row" style="margin-bottom:10px">
			  <div class="col-md-6 col-xs-6">
					<label>Supplier ID</label>
					<input type="text" class="form-control" id="supplier_id" name="supplier_id" readonly>
			  </div>
			</div>
			<div class="row" style="margin-bottom:10px" >
			  <div class="col-md-12">
					<label>Supplier Name</label>
			    <input type="text" class="form-control" id="supplier_name" name="supplier_name">
			  </div>
			</div>
			<div class="row" style="margin-bottom:30px">
			  <div class="col-md-6 col-xs-6">
					<label>Phone Number</label>
			    <input type="text" class="form-control" id="phone_number" name="phone_number">
			  </div>
			  <div class="col-md-6 col-xs-6">
					<label>Location / Address</label>
			    <input type="text" class="form-control" id="location" name="location">
			  </div>
			</div>



			<div class="row" style="margin-bottom:10px">
			  <div class="col-md-6 col-xs-6">
					<button type="submit" class="btn btn-success wide">Submit</button>
			  </div>
			  <div class="col-md-6 col-xs-6">
					<button type="button" class="btn btn-warning wide">
						<i class="fa fa-undo"></i>
						Reset Form
					</button>
			  </div>
			</div>


		</form>
	</div>

</div>


<div class="hidden" id="new_invoice_div">
	<div class="card">
		<div class="header">
			<h4 class="title">New Purchase Invoice</h4>
		</div>
		<hr>
		<div class="content">
			<form id="new_invoice_frm">
				<div class="row" style="margin-bottom:10px">
				  <div class="col-md-6 col-xs-6">
						<label>Invoice ID</label>
						<input type="text" class="form-control" id="invoice_id" name="invoice_id" readonly>
				  </div>

				  <div class="col-md-6 col-xs-6">
						<label>Supplier Invoice ID</label>
						<input type="text" class="form-control" id="supplier_invoice_id" name="supplier_invoice_id" required>
				  </div>
				</div>

				<div class="row" style="margin-bottom:15px">
				  <div class="col-md-6">
						<label>Supplier Name</label>
						<select class="form-control" name="supplier_id" id="supplier_id" required>
							<option value=""></option>


				    <?php
							require_once '../serverscripts/dbcon.php';
							$get_suppliers=mysqli_query($db,"SELECT * FROM suppliers") or die('failed');
							while ($rows=mysqli_fetch_array($get_suppliers)) {
								?>
								<option value="<?php echo $rows['supplier_id']; ?>"> <?php echo $rows['supplier_name']; ?></option>
								<?php
							}

						 ?>
						 </select>
				  </div>
					<div class="col-md-6">
						<label for="">Purchase Date</label>
						<input type="text" class="form-control" name="purchase_date" id="purchase_date">
					</div>
				</div>


				<div class="row" style="margin-top:30px">
				  <div class="col-md-6 col-xs-6">
						<label>Purchase Amount</label>
				    <input type="text" class="form-control" id="purchase_amount" name="purchase_amount"  required/>
				  </div>
				  <div class="col-md-6 col-xs-6">
						<label>Amount Paid</label>
				    <input type="text" class="form-control" id="amount_paid" name="amount_paid" required>
				  </div>
				</div>

				<div class="row" style="margin-bottom:30px">
				  <div class="col-md-6 col-xs-6">
						<label>Balance Remaining</label>
				    <input type="text" class="form-control" id="balance_remaining" name="balance_remaining" required>
				  </div>
				  <div class="col-md-6 col-xs-6">
						<label>Purchase Account</label>
				    <select class="form-control" id="purchase_account" name="purchase_account">
							<option value=""></option>
							<?php
								//require_once '../serverscripts/dbcon.php';
								$get_accounts=mysqli_query($db,"SELECT * FROM accounts") or die('failed');
								while ($rows=mysqli_fetch_array($get_accounts)) {
									?>
									<option value="<?php echo $rows['account_id']; ?>"> <?php echo $rows['account_name']; ?></option>
									<?php
								}

							 ?>
					  </select>
				  </div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<select class="form-control" name="payment_method" id="payment_method">
							<option value="CASH">CASH</option>
							<option value="CHEQUE">CHEQUE</option>
						</select>
					</div>
				</div>



				<div class="row" style="margin-bottom:10px">
				  <div class="col-md-6 col-xs-6">
						<button type="submit" class="btn btn-success wide">Submit</button>
				  </div>
				  <div class="col-md-6 col-xs-6">
						<button type="button" class="btn btn-warning wide">
							<i class="fa fa-undo"></i>
							Reset Form
						</button>
				  </div>
				</div>


			</form>
		</div>
	</div>

</div>




</body>


    <!--   Core JS Files   -->
    <?php require_once '../navigation/admin_footer.php'; ?>


	<script type="text/javascript">
    	$(document).ready(function(){

				$('#purchases_li').addClass('active')


				all_suppliers()




					$('#new_supplier_btn').on('click', function(event) {
						event.preventDefault();

						$('#data_grid').html($('#new_supplier_div').html())
						supplier_idgen()

						$('#new_supplier_frm').on('submit', function(event) {
							event.preventDefault();

							var data=$(this).serialize()
							$.ajax({
								url: '../serverscripts/admin/new_supplier_frm.php',
								type: 'GET',
								data: data,
								success: function(msg){
									if(msg==='save_successful'){
										alert('Supplier saved successfully')
										$('#new_supplier_frm')[0].reset()
										supplier_idgen()
									}
									else {
										alert(msg)
									}
								}
							})

						});

					}); //end click event


					$('#view_suppliers').on('click', function(event) {
						event.preventDefault();
						all_suppliers()
					}) //end click


					$('#new_invoice_btn').on('click', function(event) {
						event.preventDefault();
						$('#data_grid').html($('#new_invoice_div').html())

						invoice_id_gen();

						$('#purchase_date').datepicker({
							dateFormat:'yy-mm-dd'
						})

						$('#amount_paid').on('keyup', function(event) {
							event.preventDefault();
							var purchase_amount=$('#purchase_amount').val()
							var amount_paid=$(this).val()
							var balance_remaining=(parseFloat(purchase_amount) - parseFloat(amount_paid)).toFixed(2)
							$('#balance_remaining').val(balance_remaining)
						});

						$('#new_invoice_frm').on('submit', function(event) {
							event.preventDefault();
							var con=confirm('Are you sure you want to file this invoice?')
							if(con===true){
								var data=$(this).serialize()
								$.ajax({
									url: '../serverscripts/admin/new_invoice_frm.php',
									type: 'GET',
									data: data,
									success: function(msg){
										if(msg==='save_successful'){
											alert('Invoice saved successfully')
											$('#new_invoice_frm')[0].reset()
											all_invoices()
											month_purchase()
										}
										else {
											alert(msg)
										}
									}
								})
							}
						});
					}); //end click event

					$('#view_invoices').on('click', function(event) {
						event.preventDefault();
						all_invoices()

					})


					function supplier_idgen(){
						var text = "";
            var possible = "123456789";
            for( var i=0; i < 15; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            $('#supplier_id').val(text)
					}

					function invoice_id_gen(){
						var text = "";
            var possible = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            for( var i=0; i < 15; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            $('#invoice_id').val(text)
					}


					function all_suppliers(){
						$.ajax({
							url: '../serverscripts/admin/all_suppliers.php',
							type: 'GET',
							success: function(msg){
								$('#data_grid').html(msg)

								$('.datatables').DataTable({
									'sort':false
								})

								$('.datatables tbody').on('click', '.view', function(event) {
									event.preventDefault();
									 var supplier_id=$(this).attr('ID')
									 $.ajax({
	 										url: '../serverscripts/admin/supplier_card.php?supplier_id='+supplier_id,
	 										type: 'GET',
	 										success	: function(msg){
												$('#data_grid').html(msg)

												$('.return').on('click', function(event) {
													event.preventDefault();
													all_items();
												});

											}
	 									})
								});


							}
						})

					}


					function all_invoices(){
						$.ajax({
							url: '../serverscripts/admin/all_invoices.php',
							type: 'GET',
							success: function(msg){
								$('#data_grid').html(msg)

								$('.datatables').DataTable({
									'sort':false
								})

								$('.datatables tbody').on('click', '.view', function(event) {
									event.preventDefault();
									 var invoice_id=$(this).attr('ID')
									 $.ajax({
	 										url: '../serverscripts/admin/invoice_mime.php?invoice_id='+invoice_id,
	 										type: 'GET',
	 										success	: function(msg){
												$('#data_grid').html(msg)

											}
	 									})
								});

								$('.datatables tbody').on('click', '.delete', function(event) {
									event.preventDefault();

									var con=confirm('Do you want to delete this invoice?')
									if(con===true){
										var sn=$(this).attr('ID')
										$.ajax({
 	 										url: '../serverscripts/admin/delete_invoice.php?sn='+sn,
 	 										type: 'GET',
 	 										success	: function(msg){
 												if(msg==='delete_successful'){
 													alert('Invoice Deleted Successfully')
													all_invoices()
													month_purchase()
 												}
												else {
													alert(msg)
												}

 											}
 	 									})
									}



								});




							}
						})

					}

					month_purchase()

					function month_purchase(){
						$.ajax({
							url: '../serverscripts/admin/total_purchases.php',
							type: 'GET',
							success:function(msg){
								$('#purchase_holder').html(msg)
							}
						})
					}

    	});
	</script>

</html>
