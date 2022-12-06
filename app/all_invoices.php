<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
	// unset($_SESSION['active_drug']);
	$patient=new Patient();
 ?>


		<main class="py-3 mx-lg-5 main">
			<div class="container-fluid mt-2">

				<div class="row">
				  <div class="col-md-8 ">
						<h4 class="titles montserrat mb-5">All Invoices</h4>
				  </div>

				  <div class="col-md-4 text-right mb-5">

						<div class="dropdown open">
						  <button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
								<ul class="list-group">
								  <li class="list-group-item" data-toggle="modal" data-target="#new_invoice_modal"><i class="fas fa-plus mr-3" aria-hidden></i> Create New Invoice</li>
								</ul>
						  </div>
						</div>

				  </div>
				</div>




				<div class="card">
					<div class="card-body" id="data_holder">
						<h6 class="montserrat font-weight-bold">List Of Invoices Created</h6>
						<hr class="hr">

						<table class="table table-condensed datatables">
						  <thead class="grey lighten-4">
						    <tr>
						      <th>#</th>
						      <th>Invoice ID</th>
						      <th>Patient Name</th>
						      <th class="text-right">Invoice Value</th>
						      <th class="text-right">Due Date</th>
						      <th class="text-right"> Status</th>
									<th>Options</th>
						    </tr>
						  </thead>
						  <tbody>




						<?php

						$query=mysqli_query($db,"SELECT *
																															FROM
																																invoices
																															WHERE
																																status='active' && subscriber_id='".$active_subscriber."'
																															ORDER BY
																																date_created asc
																										")  or die(mysqli_error($db));
						$i=1;
						while ($rows=mysqli_fetch_array($query)) {

							$patient->patient_id=$rows['patient_id'];
							$patient->PatientInfo();

							?>
							<tr class="drugs" id="<?php echo $rows['invoice_id']; ?>">
								<td><?php echo $i++; ?></td>
								<td><?php echo $rows['invoice_id']; ?></td>
								<td><?php echo $patient->patient_fullname; ?></td>
								<td class="text-right"><?php echo $rows['total']; ?></td>
								<td class="text-right"><?php echo $rows['due_date']; ?></td>
								<td class="text-right"><?php echo $rows['status']; ?></td>
								<td class="text-right">
									<a href="invoice_prepare.php?invoice_id=<?php echo $rows['invoice_id']; ?>" type="button" class="btn btn-primary btn-sm">Manage</a>
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




<div id="new_invoice_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:900px">
    <div class="modal-content">
			<form id="new_invoice_frm" autocomplete="off">
      <div class="modal-body">
					<h6 class="montserrat font-weight-bold">Create New Invoice</h6>
					<hr class="hr">

					<div class="form-group">
						<label for="">Invoice Type</label>
						<select class="custom-select browser-default" name="invoice_type">
								<option value="pro_forma">Pro-Forma</option>
								<option value="sales_invoice">Sales Invoice</option>
						</select>
					</div>


					<div class="form-group">
						<label for="">Patient</label>
						<select class="browser-default custom-select" name="patient_id" id="patient_id">

						</select>
					</div>

					<div class="row">
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Reference #</label>
								<input type="text" class="form-control" id="ref" name="ref" required="required">
							</div>
					  </div>
					  <div class="col-md-6">
							<div class="form-group">
								<label for="">Due Date</label>
								<input type="text" class="form-control" id="due_date" name="due_date" required="required" autocomplete="off">
							</div>
					  </div>
					</div>

					<div class="form-group">
						<label for="">Footer Notes</label>
						<textarea name="footer_notes" class="form-control"></textarea>
					</div>







      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-white">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2" aria-hidden></i> Create Invoice</button>
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
		$('#invoices_nav').addClass('active')
		$('#invoices_submenu').addClass('show')
		$('#all_invoices_li').addClass('font-weight-bold')

		$('#new_invoice_modal').on('shown.bs.modal',  function(event) {
			event.preventDefault();
			$('#due_date').datepicker();

			$('#due_date').on('change', function(event) {
				event.preventDefault();
				$(this).datepicker('hide')
			});

			$('#patient_id').select2({
            placeholder: 'Select a Patient',
						dropdownParent: $('#new_invoice_modal'),
            ajax: {
                url: '../serverscripts/admin/Patients/filter_patients_select.php',
                dataType: 'json',
                delay: 100,
                data: function (data) {
                    return {
                        searchTerm: data.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results:response
                    };
                },
                cache: true
            }
        });
		});

		$('#new_invoice_frm').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: '../serverscripts/admin/Invoices/new_invoice_frm.php',
				type: 'GET',
				data: $('#new_invoice_frm').serialize(),
				success:function(msg){
					if(msg==='save_successful'){
						bootbox.alert('Invoice created successfully',function(){
							window.location='invoice_prepare.php';
						})
					}else {
						bootbox.alert(msg)
					}
				}
			})
		});

	</script>

</html>
