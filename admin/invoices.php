<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>


<?php
		$inv=new Invoice();
		$patient=new Patient();

		$this_month=date('m');
 ?>
<?php //require_once '../serverscripts/Classes/Invoices.php'; ?>


		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Invoices</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">


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
								Today's Sales
								<h5 class="big-text">GHS <?php echo number_format($inv->InvoiceSales($today,$today),2) ?></h5>
							</div>
							<div class="col-md-3">
								Weekly Sales
								<h5 class="big-text">GHS <?php echo number_format($inv->InvoiceSales($week_start,$week_end),2) ?></h5>
							</div>
							<div class="col-md-3">
								Monthly Sales
								<h5 class="big-text">GHS <?php echo number_format($inv->InvoiceSales($month_start,$month_end),2) ?></h5>
							</div>
						</div>
					</div>
				</div>

					<div class="card">
						<div class="card-body">
							<h6 class="montserrat font-weight-bold">Generated Invoices This Month</h6>
							<hr class="hr">
							<table class="table datatables table-condensed" data-search='true'>
								<thead class="grey lighten-4">
									<tr>
										<th>#</th>
										<th>Date Issued</th>
										<th>Due Date</th>
										<th>Invoice ID</th>
										<th>Patient Name</th>
										<th class="text-right">Invoice Value</th>
										<th>Status</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
									// require_once '../dbcon.php';
									$get_items=mysqli_query($db,"SELECT * FROM invoices
																																			WHERE
																																				subscriber_id='".$active_subscriber."' AND
																																				status='active' AND
																																				MONTH(date_created)='".$this_month."'
																													")  or die(mysqli_error($db));
									$i=1;
									while ($rows=mysqli_fetch_array($get_items)) {
										$patient->patient_id=$rows['patient_id'];
										$patient->PatientInfo();
										?>

										<tr>
											<td><?php echo $i++; ?></td>
											<td><?php echo $rows['date_created']; ?></td>
											<td><?php echo $rows['due_date']; ?></td>
											<td>
												<?php echo $rows['invoice_id']; ?>
											</td>
											<td><?php echo $patient->patient_fullname; ?></td>
											<td class="text-right"><?php echo number_format($rows['total'],2); ?></td>
											<td>
												<?php

													if($rows['lockstatus']=='locked'){
														?>
														<p>Active</p>
														<?php
													}

												 ?>
											</td>
											<td class="text-right">
												<div class="dropdown open">
													<button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														Options
													</button>
													<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
														<ul class="list-group">
															<li class="list-group-item"><a href="invoice_preview.php?invoice_id=<?php echo $rows['invoice_id']; ?>">Preview</a></li>
															<li class="list-group-item" id="<?php echo $rows['invoice_id']; ?>">Delete</li>
															<li class="list-group-item print" id="<?php echo $rows['invoice_id']; ?>">Print</li>
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

			</div>
		</main>




<div id="modal_holder">

</div>

</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">
	$('.sidebar-fixed .list-group-item').removeClass('active')
	$('#wholesale_nav').addClass('active')
	$('#wholesale_submenu').addClass('show')
	$('#invoices_li').addClass('font-weight-bold')


		$('#new_customer_modal').on('shown.bs.modal', function(event) {
			event.preventDefault();
			$('#customer_name').focus()
		});//end modal shown

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


		$('#filter_frm').on('submit', function(event) {
			event.preventDefault();
			var start_date=$('#start_date').val()
			var end_date=$('#end_date').val();
			// alert(start_date)
			$.get("../serverscripts/admin/Invoices/filter_frm.php?start_date="+start_date+"&end_date="+end_date,function(msg){
				$('#data_holder').html(msg)
				$('.table').DataTable({
			    'sorting':false,
			    'paging':false,
			    language: { search: '', searchPlaceholder: "Search..." },
			  })
			})
		});


		$('.table tbody').on('click','.print', function(event) {
			event.preventDefault();
			var invoice_id=$(this).attr('ID')
			print_popup('invoice_print.php?invoice_id='+invoice_id);
		});


	</script>

</html>
