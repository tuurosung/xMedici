<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	$surgery_id=clean_string($_GET['surgery_id']);
	$surg=new Surgery();
	$surg->surgery_id=$surgery_id;
	$surg->SurgeryInfo();

	$visit=new Visit();
	$visit->VisitInfo($surg->visit_id);


	$patient_id=$visit->patient_id;

	$p=new Patient();
	$p->patient_id=$patient_id;
	$p->PatientInfo();

	$visit->VisitBilling($patient_id,$visit_id);
	$visit->VisitPayment($patient_id,$visit_id);
	$visit->VisitBalance($patient_id,$visit_id);
 ?>


		<main class="py-3 ml-lg-5 main" style="background-color:; min-height:100vh">
			<div class="container-fluid mt-2">



				<div class="row">
				  <div class="col-md-6 ">
						<h4 class="titles montserrat mb-5">Surgery Portal</h4>
				  </div>
				  <div class="col-md-6 text-right mb-5">

						<div class="dropdown open">
						  <button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
								<ul class="list-group">
								  <li class="list-group-item" data-toggle="modal" data-target="#new_surgery_billing_modal"><i class="fas fa-shopping-basket mr-2" aria-hidden></i> Add Bill</li>
								  <li class="list-group-item" id="print_invoice_btn"><i class="fas fa-print mr-2" aria-hidden></i> Print Invoice</li>
								</ul>
						  </div>
						</div>



				  </div>
				</div>



				<ul class="nav nav-pills mb-3 xmedici_pills" id="ex1" role="tablist">
					<li class="nav-item" role="presentation">
						<a class="nav-link active py-3 " id="info_tab" data-toggle="pill" href="#info" role="tab" aria-controls="info" aria-selected="true" >
							<i class="fas fa-paper-plane mr-2" aria-hidden></i>
							Summary
						</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link py-3 px-3" id="anaesthesia_tab" data-toggle="pill" href="#anaesthesia"  role="tab"  aria-controls="report" aria-selected="false">
							<i class="fas fa-ambulance mr-2" aria-hidden></i>
							Anaesthesia
						</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link py-3 px-3" id="report_tab" data-toggle="pill" href="#report"  role="tab"  aria-controls="report" aria-selected="false">
							<i class="far fa-file-alt mr-2" aria-hidden></i>
							Report Of Operation
						</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link py-3 px-3" id="notes_tab" data-toggle="pill" href="#notes"  role="tab"  aria-controls="notes" aria-selected="false">
							<i class="fas fa-file-alt mr-2" aria-hidden></i>
							Post-Op Notes
						</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link py-3 px-3" id="billing_tab" data-toggle="pill" href="#billing"  role="tab"  aria-controls="notes" aria-selected="false">
							<i class="fas fa-credit-card mr-2" aria-hidden></i>
							Billing
						</a>
					</li>

				</ul>
				<!-- Pills navs -->




				<!-- Pills content -->
				<div class="tab-content" id="ex1-content">
					<div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info_tab">
						<div class="spacer"></div>

						<div class="row">
						  <div class="col-md-8">
								<section>

									<div class="card mb-3">
										<div class="card-body">

											<div class="row">
												<div class="col-md-4">

													<?php
														// if($visit->admission_status=='admitted'){
														// 	?>
														<!-- // 	<p class="text-center mb-3 montserrat font-weight-bold">ADMITTED</p>
														// 	<hr> -->
														<?php
														// }
													 ?>

													<div class="text-center mb-3">
														<?php
															switch ($p->sex) {
																case 'male':
																	echo '<img src="../images/dummy_male.png" alt="" class="img-fluid" style="width:100px">';
																	break;
																case 'female':
																	echo '<img src="../images/dummy_female.png" alt="" class="img-fluid mx-auto" style="width:100px">';
																	break;

																default:
																	// code...
																	break;
															}
														 ?>
													</div>



												</div>
												<div class="col-md-8">

													<p class="font-weight-bold montserrat text-uppercase primary-text" style="font-size:16px"><?php echo $p->patient_fullname; ?></p>

													<div class="row">
														<div class="col-6">
																<p class="font-weight-bold text-uppercase montserrat"><?php echo $p->sex; ?> | <?php echo $p->age; ?></p>
														</div>
														<div class="col-6">
															<p class="font-weight-bold text-uppercase montserrat"></p>
														</div>
													</div>

													<div class="row mt-3">
													  <div class="col-md-12">
															<p class="font-weight-bold">Procedure</p>
															<p class="" style=""><?php echo $surg->surgical_procedure; ?></p>
													  </div>
													</div>


												</div>
											</div>


										</div>
									</div>
									<!-- end card -->

								</section>
						  </div>
						</div>



					</div>

					<div class="tab-pane fade" id="anaesthesia" role="tabpanel" aria-labelledby="anaesthesia_tab">
						<div class="spacer"></div>
						<section>

							<div class="card">
								<div class="card-body p-0" style="min-height:80vh">
										<div class="row">
											<div class="col-md-4">
												<ul class=" list-group xmedici_pills2 nav nav-pills" id="ex1" role="tablist" style="">
													<li class="xmedici-lists nav-item p-0 b-0" role"presentation">
														<a class="nav-link active" id="graphs_tab" data-toggle="pill"  href="#graphs_content"  role="tab"  aria-controls="graphs_content"  aria-selected="true">

															<p class="m-0  montserrat ">Graphs / Monitors</p>
														</a>
													</li>
													<li class="xmedici-lists montserrat  nav-item p-0 b-0" role"presentation">
														<a class="nav-link" id="odq_tab" data-toggle="pill"  href="#odq_content"  role="tab"  aria-controls="odq_content"  aria-selected="true">

															<p class="m-0  montserrat">Pre-Op</p>

														</a>
													</li>
													<li class="xmedici-lists montserrat  nav-item p-0 b-0" role"presentation">
														<a class="nav-link" id="hpc_tab" data-toggle="pill"  href="#hpc_content"  role="tab"  aria-controls="hpc_content"  aria-selected="true">

															<p class="m-0  montserrat ">Surgery</p>

														</a>
													</li>
													<li class="xmedici-lists montserrat  nav-item p-0 b-0" role"presentation">
														<a class="nav-link" id="examination_tab" data-toggle="pill"  href="#examination_content"  role="tab"  aria-controls="examination_content"  aria-selected="true">
															<p class="m-0  montserrat">Post Op</p>
														</a>
													</li>
													<li class="xmedici-lists montserrat font-weight-bold nav-item p-0 b-0" role"presentation">
														<a class="nav-link" id="diagnosis_tab" data-toggle="pill"  href="#diagnosis_content"  role="tab"  aria-controls="diagnosis_content"  aria-selected="true">
															4. Diagnosis
														</a>
													</li>
													<li class="xmedici-lists montserrat font-weight-bold nav-item p-0 b-0" role"presentation">
														<a class="nav-link" id="notes_tab" data-toggle="pill"  href="#notes_content"  role="tab"  aria-controls="notes_content"  aria-selected="true">
															5. Notes
														</a>
													</li>
												</ul>
											</div>
											<div class="col-md-8">
												<div class="tab-content">
													<div class="tab-pane fade show active pt-3"  id="graphs_content" role="tabpanel"  aria-labelledby="graphs_tab">

														<h6 class="montserrat font-weight-bold mb-3">Graphs & Monitors</h6>
														<hr class="hr" style="width:30%; margin-left:0px">


													</div>
													<div class="tab-pane fade pt-3"  id="odq_content" role="tabpanel"  aria-labelledby="odq_tab">
														<h6 class="montserrat font-weight-bold">Out - Direct Questioning</h6>
														<hr class="hr" style="width:30%; margin-left:0px">




													</div>
													<div class="tab-pane fade pt-3"  id="hpc_content" role="tabpanel"  aria-labelledby="hpc_tab">







													</div>
													<div class="tab-pane fade pt-3"  id="examination_content" role="tabpanel"  aria-labelledby="examination_tab">

														<h6 class="montserrat font-weight-bold mb-3">Clinical Examination</h6>
														<hr class="hr" style="width:30%; margin-left:0px">




													</div>
													<div class="tab-pane fade pt-3"  id="diagnosis_content" role="tabpanel"  aria-labelledby="diagnosis_tab">



																	<h6 class="montserrat font-weight-bold mb-3">Diagnosis</h6>
																	<hr class="hr" style="width:30%; margin-left:0px">

																	<div class="row">
																		<div class="col-md-8">
																			<div class="form-group">
																				<label for="">Diagnosis</label>
																				<input type="text" name="diagnosis" id="diagnosis_search_term"  class="form-control" value="" placeholder="Type Diagnosis or ICD10 Code">
																			</div>
																		</div>
																	</div>

																	<div class="" id="diagnosis_holder">

																	</div>

																	<?php

																	$query=mysqli_query($db,"SELECT *
																																							FROM patient_diagnosis
																																							WHERE
																																								subscriber_id='".$active_subscriber."'AND patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'
																																		") or die(mysqli_error($db));

																	if(mysqli_num_rows($query)==0){
																		echo 'No Diagnosis';
																	}else {
																		while ($diagnosis=mysqli_fetch_array($query)) {
																			?>
																			<p class="montserrat font-weight-bold m-0">	<?php echo $diagnosis['diagnosis_id']; ?></p>
																			<p class="m-0 montserrat"><?php echo $opd->Diagnosis($diagnosis['diagnosis_id']); ?></p>
																			<a href="" class="text-danger remove_diagnosis" id="<?php echo $diagnosis['sn']; ?>">Click to Remove</a>
																			<div class="mb-4">

																			</div>
																			<?php

																		}
																	}

																	 ?>

													</div>
													<div class="tab-pane fade pt-3"  id="notes_content" role="tabpanel"  aria-labelledby="notes_tab">

														<section class="p-4">
															<h6 class="font-weight-bold montserrat">Doctor's Notes</h6>
															<textarea name="doctors_notes" id="" class="form-control"></textarea>
															<button type="submit" class="btn btn-primary">Add Notes</button>
														</section>



													</div>

												</div>

											</div>
										</div>
								</div>
							</div>

						</section>

						<div class="row">
						  <div class="col-md-3">
								<div class="card">
									<div class="card-body p-0">
										<ul class="list-group">

										  <li class="list-group-item">Graphs /Monitors</li>
										  <li class="list-group-item">Pre-Op</li>
										</ul>
									</div>
								</div>
						  </div>
						  <div class="col-md-8">

						  </div>
						</div>


					</div>

					<div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="report_tab">
						<div class="spacer"></div>

						<div class="card">
							<div class="card-body">
								<h6 class="montserrat font-weight-bold mb-3">Surgery Report</h6>

								<form id="surgery_report_frm">

									<div class="form-group d-none">
										<label for="">Surgery ID</label>
										<input type="text" name="surgery_id" class="form-control" value="<?php echo $surgery_id; ?>" readonly>
									</div>

								<div class="form-group">
									<textarea name="surgery_report" id="surgery_report"></textarea>
								</div>

								<button type="submit" class="btn btn-primary m-0">Save Report</button>

								</form>

							</div>
						</div>

						<div class="mt-5">

							<?php
								$get_report=mysqli_query($db,"SELECT * FROM surgery_report WHERE surgery_id='".$surgery_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
								while ($rows=mysqli_fetch_array($get_report)) {
									?>
									<div class="card">
										<div class="card-body">

											<div class="text-right">
												<button type="button" class="btn btn-primary btn-sm"><i class="fa fa-print mr-2"></i> Print</button>
											</div>

											<?php echo $rows['report']; ?>


										</div>
									</div>
									<?php
								}
							 ?>

						</div>


					</div>

					<div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes_tab">



							<div class="card">
								<div class="card-body">
										<h6 class="montserrat font-weight-bold mb-4">Post Op Notes</h6>
										<form id="post_op_notes_frm">

											<div class="form-group d-none">
												<label for="">Surgery ID</label>
												<input type="text" name="surgery_id" class="form-control" value="<?php echo $surgery_id; ?>" readonly>
											</div>

										<div class="form-group">
											<textarea name="post_op_notes" id="post_op_notes" class="form-control"   style="height:600px"></textarea>
										</div>

										<button type="submit" class="btn btn-primary m-0">Save Notes</button>

									</form>
								</div>
							</div>



							<div class="mt-5">

								<?php
									$get_postop_notes=mysqli_query($db,"SELECT * FROM surgery_postop_notes WHERE surgery_id='".$surgery_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
									while ($rows=mysqli_fetch_array($get_postop_notes)) {
										?>
										<div class="card">
											<div class="card-body">

												<div class="text-right">
													<button type="button" class="btn btn-primary btn-sm"><i class="fa fa-print mr-2"></i> Print</button>
												</div>

												<?php echo $rows['notes']; ?>


											</div>
										</div>
										<?php
									}
								 ?>

							</div>

					</div>

					<div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing_tab">
						<div class="spacer"></div>



						<div class="card">
							<div class="card-body">
								<h6 class="font-weight-bold montserrat mb-3">Surgery Billing </h6>

								<table class="table table-condensed">
								  <thead class="grey lighten-3">
								    <tr>
								      <th>#</th>
								      <th>Description</th>
								      <th class="text-right">Unit Cost</th>
								      <th class="text-center">Qty</th>
								      <th class="text-right">Total</th>
								      <th></th>
								    </tr>
								  </thead>
								  <tbody>



								<?php
									$i=1;
									$get_surgery_bill=mysqli_query($db,"SELECT * FROM surgery_billing WHERE surgery_id='".$surgery_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
									while ($rows=mysqli_fetch_array($get_surgery_bill)) {
										?>
										<tr>
								      <td><?php echo $i++; ?></td>
								      <td><?php echo $rows['description']; ?></td>
								      <td class="text-right"><?php echo $rows['unit_cost']; ?></td>
								      <td class="text-center"><?php echo $rows['qty']; ?></td>
								      <td class="text-right"><?php echo $rows['total']; ?></td>
								      <td class="text-right">
												<!-- <button type="button" name="button" class="btn btn-primary btn-sm">Edit</button> -->
												<button type="button" name="button" class="btn btn-danger btn-sm">Delete</button>
											</td>
								    </tr>
										<?php
									}
								 ?>
								 <tr>
								 	<td></td>
								 	<td></td>
								 	<td></td>
								 	<td></td>
								 	<td class="text-right font-weight-bold"><?php echo number_format($surg->surgery_cost,2); ?></td>
								 	<td></td>
								 </tr>
							 </tbody>
						 </table>



							</div>
						</div>

					</div>
				</div>
				<!-- Pills content -->



			</div>
		</main>




<div id="new_surgery_billing_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog " style="">
    <div class="modal-content">
			<form id="surgery_billing" autocomplete="off">
      <div class="modal-body">
					<h5 class="font-weight-bold montserrat">Surgery Billing</h5>
					<hr class="hr mb-3">

						<div class="form-group d-none">
							<label for="">Surgery ID</label>
							<input type="text" name="surgery_id" class="form-control" value="<?php echo $surgery_id; ?>" readonly>
						</div>



						<div class="row poppins">
						  <div class="col-md-6">
						    <div class="form-group">
									<label for="">Description</label>
									<input type="text" class="form-control" name="description" id="description" placeholder="">
						    </div>
						  </div>
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Cost</label>
									<input type="text" class="form-control" name="unit_cost" id="unit_cost"  placeholder="">
						    </div>
						  </div>
						</div>

						<div class="row poppins">
						  <div class="col-md-6">
						    <div class="form-group">
									<label for="">Qty</label>
									<input type="text" class="form-control" name="qty" id="qty" placeholder="">
						    </div>
						  </div>
						  <div class="col-md-6">
								<div class="form-group">
									<label for="">Total</label>
									<input type="text" class="form-control" name="total" id="total"  placeholder="" readonly>
						    </div>
						  </div>
						</div>





      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn primary-color-dark">
					<i class="fas fa-check mr-3"></i>
					Add To Bill</button>
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
		$('#lab_nav').addClass('active')
		$('#lab_submenu').addClass('show')
		$('#tests_li').addClass('font-weight-bold')

		tinymce.init({
      selector: '#post_op_notes,#surgery_report',
			force_br_newlines : true,
		  force_p_newlines : false,
		  forced_root_block : '', // Needed for 3.x
				setup: function (editor) {
	        editor.on('change', function () {
	            editor.save();
	        });
	    }
    });

		$('#qty').on('keyup', function(event) {
			event.preventDefault();
			var qty=$('#qty').val()
			var unit_cost=$('#unit_cost').val()
			$('#total').val((parseFloat(unit_cost) * parseInt(qty)).toFixed(2))
		});


		$('#print_invoice_btn').on('click', function(event) {
			event.preventDefault();
			var surgery_id='<?php echo $surgery_id; ?>';
			window.open('print_surgery_invoice.php?surgery_id='+surgery_id,"_blank","width=800")
		});

		$('#post_op_notes_frm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/Surgery/post_op_notes_frm.php',
				type: 'GET',
				data:$('#post_op_notes_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						bootbox.alert("Notes Saved Successfully",function(){
							window.location.reload()
						})
					}else {
						bootbox.alert(msg)
					}
				}
			})
		});


		$('#surgery_report_frm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/Surgery/surgery_report_frm.php',
				type: 'GET',
				data:$('#surgery_report_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						bootbox.alert("Surgery Report Saved Successfully",function(){
							window.location.reload()
						})
					}else {
						bootbox.alert(msg)
					}
				}
			})
		});


		$('#surgery_billing').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm("Add new bill?",function(r){
				if(r===true){
					$.ajax({
						url: '../serverscripts/admin/Surgery/surgery_billing.php',
						type: 'GET',
						data: $('#surgery_billing').serialize(),
						success: function(msg){
							if(msg==='save_successful'){
								bootbox.alert("Bill Added Successfully",function(){
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


	</script>

</html>
