<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
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
						<h4 class="titles montserrat mb-5">Customers</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">
						<button type="button" name="button" class="btn btn-primary m-0" data-toggle="modal" data-target="#new_customer_modal">
							New Customer
						</button>

				  </div>
				</div>


				<div class="table-responsive">
					<table class="table datatables table-condensed">
						<thead class="">
							<tr>
								<th>#</th>
								<th>Customer Name</th>
								<th class="">Address</th>
								<th class="">Phone</th>
								<th class="text-right">Debit</th>
								<th class="text-right">Credit</th>
								<th class="text-right">Balance</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=1;
							$get_customers=mysqli_query($db,"SELECT * FROM customers WHERE status='active' && subscriber_id='".$active_subscriber."' ORDER BY customer_name asc")  or die('failed');
							while ($customers=mysqli_fetch_array($get_customers)) {

								?>

								<tr class="text-uppercase">
									<td><?php echo $i++; ?></td>
									<td><?php echo $customers['customer_name']; ?></td>
									<td class=""><?php echo $customers['address']; ?></td>
									<td class=""><?php echo $customers['phone_number']; ?></td>
									<td class="text-right"><?php echo $customers['debit']; ?></td>
									<td class="text-right"><?php echo $customers['credit']; ?></td>
									<td class="text-right"><?php echo $customers['balance']; ?></td>
									<td class="text-right">
										<div class="dropdown open">
										  <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										    Action
										  </button>
										  <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu1">
												<ul class="list-group">
													<li class="list-group-item edit" id="<?php echo $customers['customer_id']; ?>">Edit</li>
													<li class="list-group-item delete" id="<?php echo $customers['customer_id']; ?>">Delete</li>
													<a href="customer_matrix.php?customer_id=<?php echo $customers['customer_id']; ?>"><li class="list-group-item ">Matrix</li></a>
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
					  <input type="text" class="form-control" id="customer_id" name="customer_id" value="<?php echo customer_idgen(); ?>" readonly>
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
		$('#customers_li').addClass('font-weight-bold')


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



    	$(document).ready(function(){

					$('#print').on('click', function(event) {
						event.preventDefault();
						print_popup('print_inventory.php');
					});













    	});


	</script>

</html>
