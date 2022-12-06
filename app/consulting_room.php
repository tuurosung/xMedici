<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php

	$department_id='002';
	$p=new Patient();
	$opd=new Visit();
 ?>

		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Consulting Room</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">
						<div class="btn-group">

							<button type="button" name="button" class="btn btn-primary m-0 btn-rounded mr-3" data-toggle="modal" data-target="#new_patient_modal">
								<i class="fas fa-plus mr-3"></i>
								New Patient
							</button>



						</div>


				  </div>
				</div>


				<div class="row">
				  <div class="col-md-4">
						<div class="card mb-2">
							<div class="card-body">
								<h6 class="montserrat font-weight-bold m-0">My Patients</h6>
							</div>
						</div>

						<?php
						$get_patients=mysqli_query($db,"SELECT *
																																	FROM
																																		visits
																																	WHERE
																																		status='active' && subscriber_id='".$active_subscriber."' AND doctor_id='".$user_id."'
																																	ORDER BY sn asc
																												")  or die(mysqli_error($db));
						$i=1;
						while ($rows=mysqli_fetch_array($get_patients)) {
							$p->patient_id=$rows['patient_id'];
							$p->PatientInfo();
							$name=$p->patient_fullname;
							$opd->VisitInfo($rows['visit_id']);
							?>
							<div class="card mb-2">
									<div class="card-body">
										<div class="row">
										  <div class="col-md-2">
												<div class="avatar d-flex justify-content-center align-items-center font-weight-bold white-text montserrat font-weight-bold">
													<?php echo $name[0]; ?>
												</div>
										  </div>
											<div class="col-md-10 montserrat font-weight-bold">
												<?php echo $p->patient_fullname; ?>
												<p><span class="badge badge-primary"><?php echo $opd->major_complaint; ?></span>  <?php echo $p->age; ?></p>
											</div>
										</div>
										<hr  class="">
										<div class="text-right">
											<a class="btn elegant-color-dark white-text btn-sm m-0" href="singlevisit.php?visit_id=<?php echo $rows['visit_id']; ?>">
												Open
												<i class="fas fa-arrow-alt-circle-right"></i>
											</a>
										</div>
									</div>
							</div>
							<?php
							}
						?>
				  </div>
					<div class="col-md-8">
						<div class="card">
								<div class="card-body">
									<h6 class="montserrat font-weight-bold"> Statistics  -  My Performance</h6>

									<div class="row mt-5">
									  <div class="col-md-4">
											<div class="card primary-color-dark white-text">
												<div class="card-body">
													<p class="montserrat font-weight-bold" style="font-size:30px">100</p>
													<p class="poppins">Patients Seen</p>
												</div>
											</div>
									  </div>
									  <div class="col-md-4">
											<div class="card warning-color-dark white-text">
												<div class="card-body">
													<p class="montserrat font-weight-bold" style="font-size:30px">100</p>
													<p class="poppins">Average Daily Patients</p>

												</div>
											</div>
									  </div>
									  <div class="col-md-4">
											<div class="card danger-color-dark white-text">
												<div class="card-body">
													<p class="montserrat font-weight-bold" style="font-size:30px">100</p>
													<p class="poppins">Total Mortality</p>

												</div>
											</div>
									  </div>
									</div>


									<div class=" mt-5">
										<h6 class="montserrat font-weight-bold mb-3">Patients On Admission</h6>

										<ul class="list-group">
										  <li class="list-group-item font-weight-bold" style="font-size:10px !important">
												<div class="row">
												  <div class="col-md-6">
														Patient Name
												  </div>
												  <div class="col-md-3">
														Ward
												  </div>
												  <div class="col-md-3">
														Bed
												  </div>
												</div>
											</li>
										  <li class="list-group-item">
												<div class="row">
												  <div class="col-md-6">

												  </div>
												  <div class="col-md-3">

												  </div>
												  <div class="col-md-3">

												  </div>
												</div>
											</li>
										</ul>
									</div>
								</div>
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
		$('#cr_nav').addClass('active')
		$('#cr_submenu').addClass('show')
		$('#nurses_desk_pending_patients_li').addClass('font-weight-bold')


		$('.accept_transfer_btn').on('click', function(event) {
			event.preventDefault();
			var patient_id=$(this).data('patient_id');
			var visit_id=$(this).data('visit_id');
			var serial=$(this).data('serial');
			var transferred_to=$(this).data('transferred_to');
			bootbox.confirm('Accept folder transfer',function(r){
				if(r===true){
					$.get('../serverscripts/admin/Patients/accept_transfer.php?patient_id='+patient_id+'&visit_id='+visit_id+'&serial='+serial+'&transferred_to='+transferred_to,function(msg){
						if(msg=='transfer_successful'){
							window.location="singlevisit.php?visit_id="+visit_id;
						}else {
							bootbox.alert(msg)
						}
					})
				}//end if
			})//end bootbox
		});











		$('#new_patient_modal').on('shown.bs.modal', function(event) {
			event.preventDefault();
			if($(this).val()=='cash'){
				$('#nhis_number').val('N/A')
				$('#nhis_number').attr('readonly','readonly')
			}else {
				$('#nhis_number').val('')
				$('#nhis_number').attr('readonly',false)
			}
		});

		$('#date_of_birth').datepicker()
		$('#date_of_birth').on('changeDate', function(event) {
			event.preventDefault();
			$(this).datepicker('hide')
		});

		$('#payment_mode').on('change',  function(event) {
			event.preventDefault();
			if($(this).val()=='cash'){
				$('#nhis_number').val('N/A')
				$('#nhis_number').attr('readonly','readonly')
			}else {
				$('#nhis_number').val('')
				$('#nhis_number').attr('readonly',false)
			}
		});

		// $(document).on('keyup',function(e){
		// 	if(e.keyCode=='78'){
		// 		$('#new_drug_modal').modal('show')
		// 	}
		// })


    	$(document).ready(function(){

					$('#print').on('click', function(event) {
						event.preventDefault();
						print_popup('print_inventory.php');
					});


					$('#new_drug_modal').on('shown.bs.modal', function(event) {
						event.preventDefault();
						$('#drug_name').focus()
					});//end modal shown

					$('#new_patient_frm').on('submit', function(event) {
						event.preventDefault();
						bootbox.confirm("Create new folder?",function(r){
							if(r===true){
								$.ajax({
									url: '../serverscripts/admin/Patients/new_patient_frm.php',
									type: 'GET',
									data: $('#new_patient_frm').serialize(),
									success: function(msg){
										if(msg==='save_successful'){
											bootbox.alert("Folder Created Successfully",function(){
												window.location='patient_folder.php'
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



    	});


	</script>

</html>
