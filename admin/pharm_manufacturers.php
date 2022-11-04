<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>


		<main class="py-3 mx-lg-5">
			<div class="container-fluid mt-2">

				<div class="row mb-5">
				  <div class="col-md-6">
						<h4 class="titles montserrat">Drug Manufacturers</h4>
				  </div>
				  <div class="col-md-6 text-right">
						<button type="button" name="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#new_manufacturer_modal">
							<i class="fa fa-plus mr-3"></i>
							Add New
						</button>
						<!-- <div class="btn-group">
						  <button type="button" class="btn btn-warning"><i class="fas fa-plus mr-3"></i> Create New</button>
						  <button type="button" class="btn btn-warning dropdown-toggle px-3" data-toggle="dropdown" aria-haspopup="true"
						    aria-expanded="false">
						    <span class="sr-only">Toggle Dropdown</span>
						  </button>
						  <div class="dropdown-menu">
						    <a class="dropdown-item" href="#">Category</a>
						    <a class="dropdown-item" href="#">Shelf</a>
						    <div class="dropdown-divider"></div>
						    <a class="dropdown-item" href="#">Print List</a>
						  </div>
						</div> -->
				  </div>
				</div>



				<table class="table datatables table-condensed">
					<thead class="">
						<tr>
							<th>#</th>
							<th>Manufacturer ID</th>
							<th>Manufacturer Name</th>
							<th class="">Address</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php

						$get_manufacturers=mysqli_query($db,"SELECT * FROM pharm_manufacturers WHERE status='active' && subscriber_id='".$active_subscriber."' ORDER BY name")  or die(mysqli_error($db));
						$i=1;
						while ($rows=mysqli_fetch_array($get_manufacturers)) {
							?>

							<tr class="text-uppercase">
								<td><?php echo $i++; ?></td>
								<td><?php echo $rows['manufacturer_id']; ?></td>
								<td class=""><?php echo $rows['name']; ?></td>
								<td class=""><?php echo $rows['address']; ?></td>
								<td class="text-right">
									<div class="dropdown open">
									  <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									    Action
									  </button>
									  <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu1">
											<ul class="list-group">
												<li class="list-group-item edit" id="<?php echo $rows['drug_id']; ?>">Edit</li>
												<li class="list-group-item delete" id="<?php echo $rows['drug_id']; ?>">Delete</li>
												<a href="drug_matrix.php?drug_id=<?php echo $rows['drug_id']; ?>"><li class="list-group-item ">Matrix</li></a>
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
		</main>




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
	<script type="text/javascript">
		$('.sidebar-fixed .list-group-item').removeClass('active')
		$('#pharmacy_nav').addClass('active')
		$('#pharmacy_submenu').addClass('show')
		$('#manufacturers_li').addClass('font-weight-bold')
	</script>


</html>
