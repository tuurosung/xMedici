<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$p=new Patient();
 ?>


		<main class="py-3 ml-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Services</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">

						<button type="button" name="button" class="btn btn-primary m-0 btn-rounded mr-3" data-toggle="modal" data-target="#new_service_modal">
							<i class="fas fa-plus mr-3"></i>
							New Service
						</button>

				  </div>
				</div>

					<div class="card mb-4">
							<div class="card-body p-0">
								<!-- Pills navs -->
								<ul class="nav nav-pills xmedici_pills poppins" id="ex1" role="tablist">
								  <li class="nav-item" role="presentation">
								    <a
								      class="nav-link active"
								      id="ex1-tab-1"
								      data-toggle="pill"
								      href="#ex1-pills-1"
								      role="tab"
								      aria-controls="ex1-pills-1"
								      aria-selected="true"
								      >Registration Services</a
								    >
								  </li>
								  <li class="nav-item" role="presentation">
								    <a
								      class="nav-link"
								      id="ex1-tab-2"
								      data-toggle="pill"
								      href="#ex1-pills-2"
								      role="tab"
								      aria-controls="ex1-pills-2"
								      aria-selected="false"
								      >Admission Services</a
								    >
								  </li>
								  <li class="nav-item" role="presentation">
								    <a
								      class="nav-link"
								      id="ex1-tab-3"
								      data-toggle="pill"
								      href="#ex1-pills-3"
								      role="tab"
								      aria-controls="ex1-pills-3"
								      aria-selected="false"
								      >Consultancy Services</a
								    >
								  </li>
								  <li class="nav-item" role="presentation">
								    <a
								      class="nav-link"
								      id="ex1-tab-4"
								      data-toggle="pill"
								      href="#ex1-pills-4"
								      role="tab"
								      aria-controls="ex1-pills-4"
								      aria-selected="false"
								      >Anaesthesia Services</a
								    >
								  </li>
								  <li class="nav-item" role="presentation">
								    <a
								      class="nav-link"
								      id="ex1-tab-5"
								      data-toggle="pill"
								      href="#ex1-pills-5"
								      role="tab"
								      aria-controls="ex1-pills-5"
								      aria-selected="false"
								      >Surgical Services</a
								    >
								  </li>
								  <li class="nav-item" role="presentation">
								    <a
								      class="nav-link"
								      id="ex1-tab-6"
								      data-toggle="pill"
								      href="#ex1-pills-6"
								      role="tab"
								      aria-controls="ex1-pills-6"
								      aria-selected="false"
								      >Radiology</a
								    >
								  </li>
								</ul>
								<!-- Pills navs -->
							</div>
					</div>


					<!-- Pills content -->
					<div class="tab-content" id="ex1-content">
					  <div class="tab-pane fade show active" id="ex1-pills-1"  role="tabpanel" aria-labelledby="ex1-tab-1" >

							<div class="card">
								<div class="card-body">

									<h6 class="montserrat font-weight-bold mb-3">Registration Services</h6>
									<hr class="hr">

									<table class="table table-condensed datatables">
									  <thead class="grey lighten-4">
									    <tr>
									      <th>#</th>
									      <th>Description</th>
									      <th>Service Type</th>
									      <th>Cycle</th>
									      <th class="text-right">Cost</th>
									      <th class="text-right">Options</th>
									    </tr>
									  </thead>
									  <tbody>


											<?php
											$s=new Service();
											$get_services=mysqli_query($db,"SELECT *
																																						 FROM services
																																						 WHERE status='active' && subscriber_id='".$active_subscriber."' AND billing_point='registration'
																																")  or die(mysqli_error($db));
											$i=1;
											while ($rows=mysqli_fetch_array($get_services)) {
													switch ($rows['billing_cycle']) {
														case 'one_time':
															$billing_cycle='One Time';
															break;
														case 'daily':
															$billing_cycle='Daily';
															break;

														default:
															// code...
															break;
													}

													switch ($rows['billing_type']) {
														case 'mandatory':
															$billing_type='Mandatory';
															break;
														case 'optional':
															$billing_type='Optional';
															break;

														default:
															// code...
															break;
													}
												?>
												<tr>
												 <td><?php echo $i++; ?></td>
												 <td><?php echo $rows['description']; ?></td>
												 <td><?php echo $billing_type; ?></td>
												 <td><?php echo $billing_cycle; ?></td>
												 <td class="text-right"><?php echo $rows['service_cost']; ?></td>
												 <td class="text-right">
													 <button type="button" class="btn btn-primary btn-sm edit" style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-pencil-alt mr-2" aria-hidden></i>Edit</button>
													 <button type="button" class="btn danger-color-dark btn-sm delete"  style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-trash-alt mr-1" aria-hidden></i>Delete</button>
												 </td>
											 </tr>

												<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<!-- End Card -->
					  </div>
					  <div class="tab-pane fade" id="ex1-pills-2" role="tabpanel" aria-labelledby="ex1-tab-2">

							<div class="card">
								<div class="card-body">

									<h6 class="montserrat font-weight-bold mb-3">Admission Services</h6>
									<hr class="hr">

									<table class="table table-condensed datatables">
									  <thead class="grey lighten-4">
									    <tr>
									      <th>#</th>
									      <th>Description</th>
									      <th>Service Type</th>
									      <th>Cycle</th>
									      <th class="text-right">Cost</th>
									      <th class="text-right">Options</th>
									    </tr>
									  </thead>
									  <tbody>

											<?php
											$s=new Service();
											$get_services=mysqli_query($db,"SELECT *
																																						 FROM services
																																						 WHERE status='active' && subscriber_id='".$active_subscriber."' AND billing_point='admission'
																																")  or die(mysqli_error($db));
											$i=1;
											while ($rows=mysqli_fetch_array($get_services)) {
													switch ($rows['billing_cycle']) {
														case 'one_time':
															$billing_cycle='One Time';
															break;
														case 'daily':
															$billing_cycle='Daily';
															break;

														default:
															// code...
															break;
													}

													switch ($rows['billing_type']) {
														case 'mandatory':
															$billing_type='Mandatory';
															break;
														case 'optional':
															$billing_type='Optional';
															break;

														default:
															// code...
															break;
													}
												?>

												<tr>
												 <td><?php echo $i++; ?></td>
												 <td><?php echo $rows['description']; ?></td>
												 <td><?php echo $billing_type; ?></td>
												 <td><?php echo $billing_cycle; ?></td>
												 <td class="text-right"><?php echo $rows['service_cost']; ?></td>
												 <td class="text-right">
													 <button type="button" class="btn btn-primary btn-sm edit" style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-pencil-alt mr-2" aria-hidden></i>Edit</button>
													 <button type="button" class="btn danger-color-dark btn-sm delete"  style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-trash-alt mr-1" aria-hidden></i>Delete</button>
												 </td>
											 </tr>


												<?php
											}
											?>
										</tbody>
									</table>

								</div>
							</div>
							<!-- End Card -->
					  </div>

					  <div class="tab-pane fade" id="ex1-pills-3" role="tabpanel" aria-labelledby="ex1-tab-3">

							<div class="card">
								<div class="card-body">

									<h6 class="montserrat font-weight-bold mb-3">Consultancy Services</h6>
									<hr class="hr">

									<table class="table table-condensed datatables">
									  <thead class="grey lighten-4">
									    <tr>
									      <th>#</th>
									      <th>Description</th>
									      <th>Service Type</th>
									      <th>Cycle</th>
									      <th class="text-right">Cost</th>
									      <th class="text-right">Options</th>
									    </tr>
									  </thead>
									  <tbody>


											<?php
											$s=new Service();
											$get_services=mysqli_query($db,"SELECT *
																																						 FROM services
																																						 WHERE status='active' && subscriber_id='".$active_subscriber."' AND billing_point='appointment'
																																")  or die(mysqli_error($db));
											$i=1;
											while ($rows=mysqli_fetch_array($get_services)) {
													switch ($rows['billing_cycle']) {
														case 'one_time':
															$billing_cycle='One Time';
															break;
														case 'daily':
															$billing_cycle='Daily';
															break;

														default:
															// code...
															break;
													}

													switch ($rows['billing_type']) {
														case 'mandatory':
															$billing_type='Mandatory';
															break;
														case 'optional':
															$billing_type='Optional';
															break;

														default:
															// code...
															break;
													}
												?>
												<tr>
												 <td><?php echo $i++; ?></td>
												 <td><?php echo $rows['description']; ?></td>
												 <td><?php echo $billing_type; ?></td>
												 <td><?php echo $billing_cycle; ?></td>
												 <td class="text-right"><?php echo $rows['service_cost']; ?></td>
												 <td class="text-right">
													 <button type="button" class="btn btn-primary btn-sm edit" style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-pencil-alt mr-2" aria-hidden></i>Edit</button>
													 <button type="button" class="btn danger-color-dark btn-sm delete"  style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-trash-alt mr-1" aria-hidden></i>Delete</button>
												 </td>
											 </tr>



												<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<!-- End Card -->
					  </div>

					  <div class="tab-pane fade" id="ex1-pills-4" role="tabpanel" aria-labelledby="ex1-tab-4">

							<div class="card">
								<div class="card-body">

									<h6 class="montserrat font-weight-bold mb-3">Anaesthesia Services</h6>
									<hr class="hr">


										<table class="table table-condensed datatables">
											<thead class="grey lighten-4">
												<tr>
													<th>#</th>
													<th>Description</th>
													<th>Service Type</th>
													<th>Cycle</th>
													<th class="text-right">Cost</th>
													<th class="text-right">Options</th>
												</tr>
											</thead>
											<tbody>


											<?php
											$s=new Service();
											$get_services=mysqli_query($db,"SELECT *
																																						 FROM services
																																						 WHERE status='active' && subscriber_id='".$active_subscriber."' AND billing_point='anaesthesia'
																																")  or die(mysqli_error($db));
											$i=1;
											while ($rows=mysqli_fetch_array($get_services)) {
													switch ($rows['billing_cycle']) {
														case 'one_time':
															$billing_cycle='One Time';
															break;
														case 'daily':
															$billing_cycle='Daily';
															break;

														default:
															// code...
															break;
													}

													switch ($rows['billing_type']) {
														case 'mandatory':
															$billing_type='Mandatory';
															break;
														case 'optional':
															$billing_type='Optional';
															break;

														default:
															// code...
															break;
													}
												?>
												<tr>
												 <td><?php echo $i++; ?></td>
												 <td><?php echo $rows['description']; ?></td>
												 <td><?php echo $billing_type; ?></td>
												 <td><?php echo $billing_cycle; ?></td>
												 <td class="text-right"><?php echo $rows['service_cost']; ?></td>
												 <td class="text-right">
													 <button type="button" class="btn btn-primary btn-sm edit" style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-pencil-alt mr-2" aria-hidden></i>Edit</button>
													 <button type="button" class="btn danger-color-dark btn-sm delete"  style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-trash-alt mr-1" aria-hidden></i>Delete</button>
												 </td>
											 </tr>

												<?php
											}
											?>
										</tbody>
									</table>

								</div>
							</div>
							<!-- End Card -->
					  </div>
					  <div class="tab-pane fade" id="ex1-pills-5" role="tabpanel" aria-labelledby="ex1-tab-5">

							<div class="card">
								<div class="card-body">

									<h6 class="montserrat font-weight-bold mb-3">Surgical Services</h6>
									<hr class="hr">

									<table class="table table-condensed datatables">
										<thead class="grey lighten-4">
											<tr>
												<th>#</th>
												<th>Description</th>
												<th>Service Type</th>
												<th>Cycle</th>
												<th class="text-right">Cost</th>
												<th class="text-right">Options</th>
											</tr>
										</thead>
										<tbody>


											<?php
											$s=new Service();
											$get_services=mysqli_query($db,"SELECT *
																																						 FROM services
																																						 WHERE status='active' && subscriber_id='".$active_subscriber."' AND billing_point='surgery'
																																")  or die(mysqli_error($db));
											$i=1;
											while ($rows=mysqli_fetch_array($get_services)) {
													switch ($rows['billing_cycle']) {
														case 'one_time':
															$billing_cycle='One Time';
															break;
														case 'daily':
															$billing_cycle='Daily';
															break;

														default:
															// code...
															break;
													}

													switch ($rows['billing_type']) {
														case 'mandatory':
															$billing_type='Mandatory';
															break;
														case 'optional':
															$billing_type='Optional';
															break;

														default:
															// code...
															break;
													}
												?>
												<tr>
												 <td><?php echo $i++; ?></td>
												 <td><?php echo $rows['description']; ?></td>
												 <td><?php echo $billing_type; ?></td>
												 <td><?php echo $billing_cycle; ?></td>
												 <td class="text-right"><?php echo $rows['service_cost']; ?></td>
												 <td class="text-right">
													 <button type="button" class="btn btn-primary btn-sm edit" style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-pencil-alt mr-2" aria-hidden></i>Edit</button>
													 <button type="button" class="btn danger-color-dark btn-sm delete"  style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-trash-alt mr-1" aria-hidden></i>Delete</button>
												 </td>
											 </tr>


												<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<!-- End Card -->
					  </div>
					  <div class="tab-pane fade" id="ex1-pills-6" role="tabpanel" aria-labelledby="ex1-tab-6">

							<div class="card">
								<div class="card-body">

									<h6 class="montserrat font-weight-bold mb-3">Radiology</h6>
									<hr class="hr">

									<table class="table table-condensed datatables">
										<thead class="grey lighten-4">
											<tr>
												<th>#</th>
												<th>Description</th>
												<th>Service Type</th>
												<th>Cycle</th>
												<th class="text-right">Cost</th>
												<th class="text-right">Options</th>
											</tr>
										</thead>
										<tbody>


											<?php
											$s=new Service();
											$get_services=mysqli_query($db,"SELECT *
																																						 FROM services
																																						 WHERE status='active' AND subscriber_id='".$active_subscriber."' AND billing_point='radiology'
																																")  or die(mysqli_error($db));
											$i=1;
											while ($rows=mysqli_fetch_array($get_services)) {
													switch ($rows['billing_cycle']) {
														case 'one_time':
															$billing_cycle='One Time';
															break;
														case 'daily':
															$billing_cycle='Daily';
															break;

														default:
															// code...
															break;
													}

													switch ($rows['billing_type']) {
														case 'mandatory':
															$billing_type='Mandatory';
															break;
														case 'optional':
															$billing_type='Optional';
															break;

														default:
															// code...
															break;
													}
												?>
												<tr>
												 <td><?php echo $i++; ?></td>
												 <td><?php echo $rows['description']; ?></td>
												 <td><?php echo $billing_type; ?></td>
												 <td><?php echo $billing_cycle; ?></td>
												 <td class="text-right"><?php echo $rows['service_cost']; ?></td>
												 <td class="text-right">
													 <button type="button" class="btn btn-primary btn-sm edit" style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-pencil-alt mr-2" aria-hidden></i>Edit</button>
													 <button type="button" class="btn danger-color-dark btn-sm delete"  style="border-radius:10px" id="<?php echo $rows['service_id']; ?>"><i class="fas fa-trash-alt mr-1" aria-hidden></i>Delete</button>
												 </td>
											 </tr>


												<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<!-- End Card -->
					  </div>
					</div>
					<!-- Pills content -->




				<div class="table-responsive mt-5">

				</div>

			</div>
		</main>




<div id="new_service_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog " style="">
    <div class="modal-content">
			<form id="new_service_frm" autocomplete="off">
      <div class="modal-body">
					<h5 class="font-weight-bold montserrat">New Service</h5>
					<hr class="hr mb-3">


						<div class="row poppins">
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Billing Type</label>
									<select class="custom-select browser-default" name="billing_type">
											<option value="mandatory">Mandatory</option>
											<option value="optional">Optional</option>
									</select>
						    </div>
						  </div>
						  <div class="col-md-6">

						  </div>
						</div>

						<div class="row poppins">
						  <div class="col-md-6">
						    <div class="form-group">
									<label for="">Description</label>
									<input type="text" class="form-control" name="description" id="description" placeholder="Enter the name of the service">
						    </div>
						  </div>
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Billing Point</label>
									<select class="custom-select browser-default" name="billing_point">
											<?php
												$get_billing_points=mysqli_query($db,"SELECT * FROM billing_points") or die(mysqli_error($db));
												while ($rows=mysqli_fetch_array($get_billing_points)) {
													?>
													<option value="<?php echo $rows['billing_point']; ?>"><?php echo $rows['point_name']; ?></option>
													<?php
												}
											 ?>
									</select>
						    </div>
						  </div>
						</div>


						<div class="row poppins mt-2">
						  <div class="col-md-6">
						    <div class="form-group">
									<label for="">Cost</label>
									<input type="text" class="form-control" name="service_cost" id="service_cost"  placeholder="Enter the cost of the service">
						    </div>
						  </div>
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Billing Cycle</label>
									<select class="custom-select browser-default" name="billing_cycle">
											<option value="one_time">One - Time</option>
											<option value="daily">Daily</option>
									</select>
						    </div>
						  </div>
						</div>





      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn primary-color-dark">
					<i class="fas fa-file-alt mr-3"></i>
					Create Service</button>
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
		$('#patients_nav').addClass('active')
		$('#patients_submenu').addClass('show')
		$('#patients_li').addClass('font-weight-bold')


		$('#new_service_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm("Create new service?",function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Billing/create_service.php',
						type: 'GET',
						data: $('#new_service_frm').serialize(),
						success: function(msg){
							if(msg==='save_successful'){
								bootbox.alert("Service Created Successfully",function(){
									window.location.reload()
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


		$('.table tbody').on('click', '.edit', function(event) {
			event.preventDefault();
			var service_id=$(this).attr('ID')
			$.get('../serverscripts/admin/Services/edit_service_modal.php?service_id='+service_id,function(msg){
				$('#modal_holder').html(msg)
				$('#edit_service_modal').modal('show')

				$('#edit_service_frm').on('submit', function(event) {
					event.preventDefault();
					$.ajax({
						url: '../serverscripts/admin/Services/edit_service_frm.php',
						type: 'GET',
						data: $('#edit_service_frm').serialize(),
						success:function(resp){
							if(resp==='update_successful'){
								bootbox.alert('Update Successful',function(){
									window.location.reload()
								})
							}else {
								bootbox.alert(resp)
							}
						}
					})

				});
			})
		});










	</script>

</html>
