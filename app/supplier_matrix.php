<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php
		$supplier_id=clean_string($_GET['supplier_id']);

		$s=new Supplier();
		$s->supplier_id=$supplier_id;
		$s->SupplierInfo();
 ?>

		<main class="py-3 mx-lg-5">
			<div class="container-fluid mt-2">

				<div class="row mb-5">
				  <div class="col-md-6">
				    	<h4 class="titles montserrat mb-3">Supplier Matrix</h4>
							<h6 class="m-0 montserrat font-weight-bold"><?php echo $s->supplier_name; ?></h6>
							<p><?php echo $s->phone_number; ?></p>
							<p><?php echo $s->location; ?></p>
				  </div>
				  <div class="col-md-6 text-right">
						<button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#new_supplier_modal">Create New</button>
				  </div>
				</div>

				<div class="row mb-5">
				  <div class="col-md-3">
						<div class="card">
							<div class="card-body pt-2 pb-2">
								<p class="m-0">Total Supply Cost</p>
								<h6 class="montserrat font-weight-bold">GHS </h6>
							</div>
						</div>
				  </div>
				  <div class="col-md-3">
						<div class="card">
							<div class="card-body pt-2 pb-2">
								<p class="m-0">Total Payments</p>
								<h6 class="montserrat font-weight-bold">GHS </h6>
							</div>
						</div>
				  </div>
				  <div class="col-md-3">
						<div class="card">
							<div class="card-body pt-2 pb-2">
								<p class="m-0">Outstanding Balance</p>
								<h6 class="montserrat font-weight-bold">GHS </h6>
							</div>
						</div>
				  </div>
				</div>

				<div class="row">
				  <div class="col-md-6">
						<!-- Pills navs -->
							<ul class="nav nav-pills mb-3" id="ex1" role="tablist">
							  <li class="nav-item" role="presentation">
							    <a class="nav-link active"  id="ex1-tab-1"   data-toggle="pill"  href="#ex1-pills-1"  role="tab" aria-controls="ex1-pills-1"   aria-selected="true">
										Supply Invoices
									</a>
							  </li>
							  <li class="nav-item" role="presentation">
							    <a  class="nav-link"   id="ex1-tab-2"   data-toggle="pill"   href="#ex1-pills-2"    role="tab"   aria-controls="ex1-pills-2"   aria-selected="false">
										Payments Made
									</a>
							  </li>
							  <!-- <li class="nav-item" role="presentation">
							    <a  class="nav-link"  id="ex1-tab-3"  data-toggle="pill"   href="#ex1-pills-3"  role="tab"  aria-controls="ex1-pills-3" aria-selected="false">
										Tab 3
									</a>
							  </li> -->
							</ul>
							<!-- Pills navs -->

						</div>
</div>

							<!-- Pills content -->
							<div class="tab-content" id="ex1-content">

								<!-- Supply Invoices -->
							  <div   class="tab-pane fade show active"   id="ex1-pills-1"   role="tabpanel"    aria-labelledby="ex1-tab-1">
									<table class="table datatables table-condensed">
							      <thead>
							        <tr>
							          <th>#</th>
							          <th>Supplier ID</th>
							          <th>Supplier Name</th>
							          <th>Phone Number</th>
							          <th>Location</th>
							          <th></th>
							        </tr>
							      </thead>
							      <tbody>
							        <?php
							        // require_once '../dbcon.php';
							        $get_items=mysqli_query($db,"SELECT * FROM suppliers")  or die('failed');
							        $i=1;
							        while ($rows=mysqli_fetch_array($get_items)) {
							          ?>

							          <tr>
							            <td><?php echo $i++; ?></td>
							            <td><?php echo $rows['supplier_id']; ?></td>
							            <td><?php echo $rows['supplier_name']; ?></td>
							            <td><?php echo $rows['phone_number']; ?></td>
							            <td><?php echo $rows['location']; ?></td>

							            <td class="text-right">
														<div class="dropdown open">
														  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														    Options
														  </button>
														  <div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
														    <ul class="list-group">
														    	<li class="list-group-item edit">Edit</li>
														    	<a href="supplier_matrix.php?supplier_id=<?php echo $rows['supplier_id']; ?>"<li class="list-group-item">Matrix</li></a>
														    	<li class="list-group-item">Delete</li>
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

								<!-- Payments Made -->
							  <div class="tab-pane fade" id="ex1-pills-2" role="tabpanel" aria-labelledby="ex1-tab-2">
							    Tab 2 content
							  </div>
							  <!-- <div class="tab-pane fade" id="ex1-pills-3" role="tabpanel" aria-labelledby="ex1-tab-3">
							    Tab 3 content
							  </div> -->
							</div>
							<!-- Pills content -->
				  </div>
				</div>







<div class="modal fade" id="new_supplier_modal">
  <div class="modal-dialog modal-side modal-top-right" role="document">
    <div class="modal-content">
			<form id="new_supplier_frm">
      <div class="modal-body">
				<h6>New Supplier Registration</h6>
				<hr class="hr">

				<div class="form-group">
					<label>Supplier ID</label>
					<input type="text" class="form-control" id="supplier_id" name="supplier_id" value="<?php echo supplier_idgen(); ?>" readonly>
				</div>
				<div class="form-group">
					<label>Supplier Name</label>
			    <input type="text" class="form-control" id="supplier_name" name="supplier_name">
				</div>
				<div class="form-group">
					<label>Phone Number</label>
			    <input type="text" class="form-control" id="phone_number" name="phone_number">
				</div>
				<div class="form-group">
					<label>Location / Address</label>
			    <input type="text" class="form-control" id="location" name="location">
				</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
			</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->






</body>


    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>


	<script type="text/javascript">
    	$(document).ready(function(){

				// $('.sidebar')
				$('#purchases_li').addClass('active')


				$('#new_supplier_frm').on('submit', function(event) {
					event.preventDefault();
					$.ajax({
						url: '../serverscripts/admin/new_supplier_frm.php',
						type: 'GET',
						data: $("#new_supplier_frm").serialize(),
						success: function(msg){
							if(msg==='save_successful'){
								bootbox.alert('Supplier saved successfully',function(){
									window.location.reload()
								})
							}
							else {
								bootbox.alert(msg)
							}
						}
					})//end ajax

				});//end submit


				$('.datatables tbody').on('click', '.delete', function(event) {
					event.preventDefault();
					bootbox.confirm('Do you want to delete this invoice?',function(r){
						if(r===true){
							$.ajax({
								url: '../serverscripts/admin/delete_supplier.php?sn='+sn,
								type: 'GET',
								success	: function(msg){
									if(msg==='delete_successful'){
										alert('Invoice Deleted Successfully')
										all_invoices()
										month_purchase()
									}
									else {
										alert(msg)
									}//end if
								}
							})//end ajax
						}//end if
					})
				});//end delete








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






							}
						})

					}

    	});
	</script>

</html>
