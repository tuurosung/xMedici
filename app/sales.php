<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<main class="py-3 mx-lg-5">
	<div class="container-fluid mt-2">

		<div class="row mb-5">
		  <div class="col-md-6">
		    <h4 class="titles montserrat">Cash Sales Report</h4>
		  </div>
		  <div class="col-md-6 text-right">
				<a href="sales_portal.php"><button type="button" class="btn btn-primary">Dispensary</button> </a>
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


<div class="" id="data_holder">

	<div class="infoboxes px-3 py-3 mb-5">
		<div class="row">
			<div class="col-md-3">
				Today's Sales
				<h5 class="big-text">GHS <?php echo number_format(sales_period($today,$today),2) ?></h5>
			</div>
			<div class="col-md-3">
				Weekly Sales
				<h5 class="big-text">GHS <?php echo number_format(sales_period($week_start,$week_end),2) ?></h5>
			</div>
			<div class="col-md-3">
				Monthly Sales
				<h5 class="big-text">GHS <?php echo number_format(sales_period($month_start,$month_end),2) ?></h5>
			</div>
		</div>
	</div>

	<table class="table datatables table-condensed">
		<thead class="">
			<tr>
				<th>#</th>
				<th>Trans ID</th>
				<th>Description</th>
				<th>Cost</th>
				<th>Qty</th>
				<th>Total</th>
				<th>Time</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i=1;
				$total=0;
				$get_sales=mysqli_query($db,"SELECT * FROM sales WHERE date='".$today."' && status='active' && subscriber_id='".$active_subscriber."' ORDER BY transaction_id desc") or die(mysqli_error($db));
				while ($sales=mysqli_fetch_array($get_sales)) {
					$get_cart=mysqli_query($db,"SELECT * FROM cart WHERE transaction_id='".$sales['transaction_id']."' && subscriber_id='".$active_subscriber."' && status='active'") or die(mysqli_error($db));
					while ($cart_items=mysqli_fetch_array($get_cart)) {
						$drug_info=drug_info($cart_items['drug_id']);
						?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $sales['transaction_id']; ?></td>
							<td><?php echo $drug_info['drug_name']; ?></td>
							<td><?php echo $cart_items['cost']; ?></td>
							<td><?php echo $cart_items['qty']; ?></td>
							<td class="text-right"><?php echo $cart_items['total']; ?></td>
							<td><?php echo date('H:i:s',$sales['timestamp']); ?></td>
							<td class="text-right">
								<div class="dropdown open">
								  <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    Option
								  </button>
								  <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu1">
										<ul class="list-group">
											<li class="list-group-item print_receipt" id="<?php echo $sales['transaction_id']; ?>">Receipt</li>
											<li class="list-group-item delete" id="<?php echo $cart_items['sn']; ?>">Delete</li>
										</ul>
								  </div>
								</div>
							</td>
						</tr>
						<?php
						$total+=$cart_items['total'];
					}
				}
			 ?>
			 	<tr>
			 		<td colspan="5"></td>
			 		<td class="font-weight-bold text-right"><?php echo number_format($total,2); ?></td>
			 	</tr>
		</tbody>
	</table>
</div>

  </div>
</main>


</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">

			$('.sidebar-fixed .list-group-item').removeClass('active')
			$('#retail_nav').addClass('active')
			$('#sales_submenu').addClass('show')
			$('#sales_li').addClass('font-weight-bold')

			$('#start_date, #end_date').datepicker()
			$('#start_date, #end_date').on('change', function(event) {
				event.preventDefault();
				$(this).datepicker('hide')
			});

			$('#filter_frm').on('submit',function(event) {
				event.preventDefault();
				// alert('Hi')
				$.ajax({
					url: '../serverscripts/admin/filter_sales_history.php',
					type: 'GET',
					data: $("#filter_frm").serialize(),
					success: function(msg){
						$('#data_holder').html(msg)
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
