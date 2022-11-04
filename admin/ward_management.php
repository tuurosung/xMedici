<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	unset($_SESSION['active_drug']);
	$ward_id=clean_string($_GET['ward_id']);
 ?>


		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Ward Management</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">
						<div class="btn-group">

							<button type="button" name="button" class="btn btn-primary m-0 btn-rounded mr-3" data-toggle="modal" data-target="#new_ward_modal">
								<i class="fas fa-plus mr-3"></i>
								New Ward
							</button>



						</div>


				  </div>
				</div>

				<div class="row">
				  <div class="col-md-4">
						<div class="card">
								<div class="card-body">
									<form id="new_bed_frm" autocomplete="off">
									<h6 class="montserrat font-weight-bold">Add New Bed</h6>
									<hr class="hr">

									<div class="d-none">
										<input type="text" class="form-control" name="ward_id" value="<?php echo $ward_id; ?>">
									</div>

									<div class="form-group">
										<label for="">Bed Type</label>
										<select class="custom-select browser-default" name="bed_type">
												<option value="regular">Regular</option>
												<option value="vip">VIP</option>
										</select>
									</div>

									<div class="form-group">
										<label for="">Description</label>
										<input type="text" class="form-control" name="description" value="" placeholder="eg. Bed 9">
									</div>

									<button type="submit" class="btn btn-primary wide">Add Bed</button>
									</form>
								</div>
						</div>
				  </div>
				  <div class="col-md-8">
						<div class="card">
							<div class="card-body">
								<h6 class="montserrat font-weight-bold">List Of Beds</h6>
								<hr class="hr">

								<?php
										$query=mysqli_query($db,"SELECT * FROM beds WHERE ward_id='".$ward_id."' AND status='active' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
										while ($rows=mysqli_fetch_array($query)) {
											?>
											<li class="list-group-item pl-0" style="border-left:none; border-right:none">
												<div class="row">
													<div class="col-md-4">
														<?php echo $rows['description'] ?>
													</div>
													<div class="col-md-4">
														<?php echo $rows['bed_status'] ?>
													</div>
													<div class="col-md-4">
														<button type="button" class="btn btn-primary btn-sm edit" id="<?php echo $rows['bed_id']; ?>">Edit</button>
														<button type="button" class="btn btn-danger btn-sm delete">Delete</button>
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





		</main>




<div id="new_ward_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:900px">
    <div class="modal-content">
			<form id="new_ward_frm">
      <div class="modal-body">
					<h6 class="montserrat font-weight-bold">Add New Ward</h6>
					<hr class="hr">

					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Ward Type</label>
								<select class="browser-default custom-select" name="ward_type">
								<option value="general">General Ward</option>
								<option value="male">Male Ward</option>
								<option value="female">Female Ward</option>
					    	</select>
							</div>
					  </div>
					</div>

					<div class="form-group">
						<label for="">Description</label>
						<input type="text" name="description" class="form-control" value="">
					</div>


      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn btn-black"><i class="fas fa-plus-circle mr-2" aria-hidden></i> Add Ward</button>
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
	<script type="text/javascript">
		$('.sidebar-fixed .list-group-item').removeClass('active')
		$('#config_nav').addClass('active')
		$('#config_submenu').addClass('show')
		$('#wards_li').addClass('font-weight-bold')

		$('#new_bed_frm').on('submit', function(event) {
			event.preventDefault();
			/* Act on the event */
			$.ajax({
				url: '../serverscripts/admin/Wards/new_bed_frm.php',
				type: 'GET',
				data:$('#new_bed_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						bootbox.alert('Bed Added successfully',function(){
							window.location.reload()
						})
					}else {
						bootbox.alert(msg)
					}
				}
			})
		});

	</script>

</html>
