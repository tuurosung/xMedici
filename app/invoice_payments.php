<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
	$inv=new Invoice();
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
						<h4 class="titles montserrat mb-5">Invoice Payments</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">
						<button type="button" name="button" class="btn btn-primary m-0" data-toggle="modal" data-target="#new_customer_modal">
							<!-- New Customer -->
						</button>

				  </div>
				</div>

				<form id="filter_frm">
					<div class="row mb-3">
						<div class="col-md-3">
								<label for="">Start Date</label>
								<input type="text" class="form-control" name="start_date" id="start_date" value="<?php echo date('Y-m-d'); ?>">
						</div>
						<div class="col-md-3">
								<label for="">End Date</label>
								<input type="text" class="form-control" name="end_date" id="end_date" value="<?php echo date('Y-m-d'); ?>">
						</div>
						<div class="col-md-3">
							<button type="submit" class="btn btn-primary" style="margin-top:19px" id="generate_btn"><i class="fas fa-file mr-3"></i>Generate Report</button>
						</div>
					</div>
				</form>

				<div class="table-responsive" id="data_holder">
					<div class="infoboxes px-3 py-3 mb-5">
						<div class="row">
							<div class="col-md-3">
								Payments For Today
								<h5 class="big-text">GHS <?php echo number_format($inv->InvoicePayments($today,$today),2) ?></h5>
							</div>
							<div class="col-md-3">
								Weekly Payments
								<h5 class="big-text">GHS <?php echo number_format($inv->InvoicePayments($week_start,$week_end),2) ?></h5>
							</div>
							<div class="col-md-3">
								Monthly Payments
								<h5 class="big-text">GHS <?php echo number_format($inv->InvoicePayments($month_start,$month_end),2) ?></h5>
							</div>
						</div>
					</div>

					<table class="table datatables table-condensed" data-search='true'>
						<thead class="">
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Payment ID</th>
								<th>Invoice ID</th>
								<th>Customer</th>
								<th class="text-right">Amount Paid</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$total_payments=0;
							// require_once '../dbcon.php';
							$get_items=mysqli_query($db,"SELECT * FROM invoice_payments WHERE  subscriber_id='".$active_subscriber."' && status='active'")  or die(mysqli_error($db));
							$i=1;
							while ($rows=mysqli_fetch_array($get_items)) {
								$customer_info=customer_info($rows['customer_id']);
								?>

								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo $rows['date']; ?></td>
									<td><?php echo $rows['payment_id']; ?></td>
									<td><?php echo $rows['invoice_id']; ?></td>
									<td><?php echo $customer_info['customer_name']; ?></td>
									<td class="text-right"><?php echo $rows['amount_paid']; ?></td>
									<td class="text-right">
										<div class="dropdown open">
											<button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Options
											</button>
											<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
												<ul class="list-group">
													<li class="list-group-item"><a href="invoice_preview.php?invoice_id=<?php echo $rows['invoice_id']; ?>">Preview</a></li>
													<li class="list-group-item" id="<?php echo $rows['invoice_id']; ?>">Delete</li>
													<li class="list-group-item" id="<?php echo $rows['invoice_id']; ?>">Print</li>
												</ul>
											</div>
										</div>

									</td>

								</tr>

								<?php
								$total_payments+=$rows['amount_paid'];
							}
							?>
							<tr>
								<td ></td>
								<td ></td>
								<td ></td>
								<td ></td>
								<td ></td>
								<td class="text-right font-weight-bold"><?php echo number_format($total_payments,2); ?></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>

			</div>
		</main>




<div id="new_customer_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right" style="width:400px">
    <div class="modal-content">
			<form id="new_customer_frm">
      <div class="modal-body">
					<h6>New Customer Registration</h6>
					<hr class="hr">


					<div class="form-group d-non">
					  <label for="">Customer ID</label>
					  <input type="text" class="form-control" id="customer_id" name="customer_id" value="<?php //echo customer_idgen(); ?>" readonly>
					</div>

					<div class="form-group">
					  <label for="">Name</label>
					  <input type="text" class="form-control" id="customer_name" name="customer_name" required="required" autocomplete="off">
					</div>


					<div class="form-group">
					  <label for="">Address</label>
					  <input type="text" class="form-control" id="address" name="address" required="required" autocomplete="off">
					</div>


					<div class="form-group">
					  <label for="">Phone Number</label>
					  <input type="text" class="form-control" id="phone_number" name="phone_number" required="required" autocomplete="off">
					</div>


      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn btn-black">Register Customer</button>
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
	$('#wholesale_nav').addClass('active')
	$('#wholesale_submenu').addClass('show')
	$('#invoice_payments_li').addClass('font-weight-bold')




		$('#new_customer_modal').on('shown.bs.modal', function(event) {
			event.preventDefault();
			$('#customer_name').focus()
		});//end modal shown

		$('#filter_frm').on('submit', function(event) {
			event.preventDefault();
			var start_date=$('#start_date').val()
			var end_date=$('#end_date').val();
			// alert(start_date)
			$.get("../serverscripts/admin/Invoices/filter_payments_frm.php?start_date="+start_date+"&end_date="+end_date,function(msg){
				$('#data_holder').html(msg)
				$('.table').DataTable({
			    'sorting':false,
			    'paging':false,
			    language: { search: '', searchPlaceholder: "Search..." },
			  })
			})
		});

		$('#new_customer_frm').one('submit', function(event) {
			event.preventDefault();
			var customer_id=$('#customer_id').val()
			bootbox.confirm("Register this customer?",function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/new_customer_frm.php',
						type: 'GET',
						data: $('#new_customer_frm').serialize(),
						success: function(msg){
							if(msg==='save_successful'){
								bootbox.alert("Customer registered successfully",function(){
									window.location='customer_matrix.php?customer_id='+customer_id
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





	</script>

</html>
