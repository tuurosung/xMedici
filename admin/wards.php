<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	unset($_SESSION['active_drug']);
	$drug=new Drug();
	$ward=new Ward();
 ?>


		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Wards</h4>
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

				<div class="row mb-3 d-none">
					<div class="col-md-3">

						<div class="card">
							<div class="card-body pt-3 pb-1">
								<p>
									<?php
									echo $d->total_drugs;
								 ?>
								 Drugs Added So Far</p>
							</div>
						</div>

					</div>
					<div class="col-md-3">

						<div class="card">
							<div class="card-body pt-3 pb-1">
								<p>
									<?php
									echo $d->total_active;
								 ?>
								 Active Drugs</p>
							</div>
						</div>

					</div>
					<div class="col-md-3">
							<div class="card">
								<div class="card-body pt-3 pb-1">
									<p>
										<?php
										echo $d->total_deleted;
									 ?>
									 Deleted Drugs</p>
								</div>
						</div>
					</div>
				</div>


				<div class="card mb-3">
					<div class="card-body">
						<div class="row">
						  <div class="col-md-3">
								<input type="text" class="form-control" value="" placeholder="search drug">
						  </div>
							<div class="col-md-3">
								<select class="custom-select browser-default" name="" id="drug_category_filter">
									<option value="all_manufacturers">Ward Type</option>
									<option value="general">General</option>
									<option value="male">Male</option>
									<option value="female">Female</option>
								</select>
						  </div>
						</div>

						</div>
				</div>

				<div class="card">
					<div class="card-body p-0">
						<li class="list-group-item custom-list-item">
							<div class="row">
							  <div class="col-md-7">
									Description
							  </div>
							  <div class="col-md-2">
									Type
							  </div>
								<div class="col-md-2 text-center">
									Bed Count
							  </div>
								<div class="col-md-1">

								</div>
							</div>
						</li>
						<?php

						$query=mysqli_query($db,"SELECT * FROM wards WHERE status='active' && subscriber_id='".$active_subscriber."'")  or die('failed');
						$i=1;
						while ($rows=mysqli_fetch_array($query)) {

							switch ($rows['ward_type']) {
								case 'male':
									$ward_type='Male Ward';
									break;
								case 'female':
									$ward_type='Female Ward';
									break;
								case 'general':
									$ward_type='General Ward';
									break;

								default:
									// code...
									break;
							}

							$ward_id=$rows['ward_id'];
							$beds=$ward->CheckBeds($ward_id);

							?>
							<li class="list-group-item hover wards py-3" id="<?php echo $rows['ward_id']; ?>">
								<div class="row">
									<div class="col-md-7">
										<?php echo $rows['description']; ?>
									</div>
									<div class="col-md-2">
										<?php echo $ward_type; ?>
									</div>
									<div class="col-md-2 text-center">
										<?php echo $beds; ?>
									</div>
									<div class="col-md-1 text-right">
										<?php echo $rows['retail_price']; ?>
									</div>
								</div>
							</li>
							<?php
						}
						?>
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

		$('.wards').on('click', function(event) {
			event.preventDefault();
			var ward_id=$(this).attr('ID')
			window.location='ward_management.php?ward_id='+ward_id
		});

		$('#new_ward_frm').on('submit', function(event) {
			event.preventDefault();
			/* Act on the event */
			$.ajax({
				url: '../serverscripts/admin/Wards/new_ward_frm.php',
				type: 'GET',
				data:$('#new_ward_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						bootbox.alert('Ward created successfully',function(){
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
