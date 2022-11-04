<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
		$drug=new Drug();

 ?>
<main class="py-3 mx-lg-5">
	<div class="container-fluid mt-2">

		<div class="row mb-5">
		  <div class="col-md-6">
		    <h4 class="titles montserrat">Dispensed Drugs</h4>
		  </div>
		  <div class="col-md-6 text-right">
				<button type="button" class="btn btn-primary btn-rounded"><i class="fas fa-print mr-2"></i> Print Report</button>
		  </div>
		</div>

		<div class="card mb-5">
			<div class="card-body">
				<h5 class="mb-4">Filter Sales Report</h5>

				<form  id="filter_frm">
					<div class="row">
				  <div class="col-2">
							Start Date
							<input type="text" class="form-control" name="start_date" id="start_date" placeholder="Start Date" value="<?php echo $today; ?>">
				  </div>
					<div class="col-2">
						End Date
							<input type="text" class="form-control" name="end_date" id="end_date"  placeholder="End Date" value="<?php echo $today; ?>">
					</div>
					<div class="col-2">
						Pharmacists
						<select class="custom-select browser-default" name="pharm_id" id="pharm_id">
							<?php
										$get_pharmacists=mysqli_query($db,"SELECT * FROM pharmacists WHERE subscriber_id='".$active_subscriber."' AND status='active'") or die(msyqli_error($db));
										while ($rows=mysqli_fetch_array($get_pharmacists)) {
												?>
												<option value="<?php echo $rows['pharm_id']; ?>"><?php echo $rows['surname'] .' '. $rows['othernames']; ?></option>
												<?php
										}
							 ?>
						</select>
					</div>
				</div>
				</form>
			</div>
		</div>

		<div class="card">
			<div class="card-body">

				<h6 class="mb-5">Report Of Drugs Dispensed</h6>


				<div class="" id="data_holder">
					<table class="table table-condensed">
					  <thead>
					    <tr>
					      <th>#</th>
					      <th>Description</th>
					      <th>Unit Cost</th>
					      <th>Qty</th>
					      <th class="text-right">Total</th>
					      <th class="text-right">Profit</th>
					      <th class="text-right">Option</th>
					    </tr>
					  </thead>
					  <tbody>
							<?php
								$i=1;
								$total=0;

								$get_sales=mysqli_query($db,"SELECT * FROM pharm_cart WHERE date='".$today."' && status='CHECKOUT' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
								while ($cart_items=mysqli_fetch_array($get_sales)) {
										$drug->drug_id=$cart_items['drug_id'];
										$drug->DrugInfo();
										?>
										<tr>
								      <td><?php echo $i++; ?></td>
								      <td><?php echo $drug->drug_name; ?></td>
								      <td><?php echo $cart_items['retail_price']; ?></td>
								      <td><?php echo $cart_items['qty']; ?></td>
								      <td class="text-right"><?php echo $cart_items['total']; ?></td>
											<td class="text-right">
												<?php echo number_format($drug->profit * $cart_items['qty'],2); ?>
											</td>
								      <td class="text-right">
												<div class="dropdown open">
												  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												    Actions
												  </button>
												  <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
														<ul class="list-group">
														  <li class="list-group-item"><i class="fas fa-print mr-2" aria-hidden></i> Print Receipt</li>
														</ul>
												  </div>
												</div>
											</td>
								    </tr>


										<?php
										$total+=$cart_items['total'];
										$overall_profit+=$drug->profit * $cart_items['qty'];
									}

							 ?>
							 <tr>
							 	<td></td>
							 	<td></td>
							 	<td></td>
							 	<td></td>
							 	<td class="text-right" style="font-size:16px !important"><?php echo number_format($total,2); ?></td>
							 	<td class="text-right" style="font-size:16px !important"><?php echo number_format($overall_profit,2); ?></td>
								<td></td>
							 </tr>
					  </tbody>
					</table>
				</div>


			</div>
		</div>








  </div>
</main>


</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">

	$('.sidebar-fixed .list-group-item').removeClass('active')
	$('#pharmacy_nav').addClass('active')
	$('#pharmacy_submenu').addClass('show')
	$('#sales_li').addClass('font-weight-bold')

			$('#start_date, #end_date').datepicker()
			$('#start_date, #end_date').on('changeDate', function(event) {
				event.preventDefault();
				$(this).datepicker('hide')

				var start_date=$('#start_date').val()
				var end_date=$('#end_date').val()
				var pharm_id=$('#pharm_id').val()



				if(start_date !='' && end_date !='' && pharm_id !=''){
					$.get('../serverscripts/admin/filter_sales_history.php?start_date='+start_date+'&end_date='+end_date+'&pharm_id='+pharm_id,function(msg){
						$('#data_holder').html(msg)
					})
				}
			});

			$('#filter_frm').on('submit',function(event) {
				event.preventDefault();
				// alert('Hi')
				$.ajax({
					url: '../serverscripts/admin/filter_sales_history.php',
					type: 'GET',
					data: $("#filter_frm").serialize(),
					success: function(msg){

						$('.table').addClass('datatables')

						$('.table tbody').on('click', '.delete', function(event) {
							event.preventDefault();
							var sn=$(this).attr('ID')
							bootbox.confirm("Delete this item from sales?",function(r){
								if(r===true){
									$.get("../serverscripts/admin/delete_sale_item.php?sn="+sn,function(msg){
										if(msg==='delete_successful'){
											bootbox.alert("Delete successfull",function(msg){
												window.location.reload()
											})
										}
										else {
											bootbox.alert(msg)
										}
									})
								}
							})
						});// delete click
					}
				})
			});

			$('.table tbody').on('click', '.print_receipt', function(event) {
				event.preventDefault();
				var transaction_id=$(this).attr('ID')
				print_popup('print_voucher.php?transaction_id='+transaction_id)
			});


			$('.table tbody').on('click', '.delete', function(event) {
				event.preventDefault();
				var sn=$(this).attr('ID')
				bootbox.confirm("Delete this item from sales?",function(r){
					if(r===true){
						$.get("../serverscripts/admin/delete_sale_item.php?sn="+sn,function(msg){
							if(msg==='delete_successful'){
								bootbox.alert("Delete successfull",function(msg){
									window.location.reload()
								})
							}
							else {
								bootbox.alert(msg)
							}
						})
					}
				})
			});


			$('#print').on('click', function(event) {
					var con=confirm('Do you want to print out this sales report?')
					var start_date=$('#start_date').val()
					var end_date=$('#end_date').val()
					var attendant=$('#attendant').val()
					if(con==true){
						window.open("../serverscripts/admin/find_sales_history_print.php?start_date="+start_date+"&end_date="+end_date+"&attendant="+attendant,"_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=656,height=400")
					}

			})


	</script>

</html>
