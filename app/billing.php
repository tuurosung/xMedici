<?php require_once '../navigation/header_lite.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
require_once '../serverscripts/Classes/Billing.php';
require_once '../serverscripts/Classes/Patient.php';

$billing = new Billing();
$p = new Patient();

// print_r($billing->PendingBills());
?>

<main class="py-3 mx-lg-5 main">
	<div class="container-fluid mt-2">

		<div class="row">
			<div class="col-md-6 ">
				<h4 class="titles montserrat mb-5">Pending Bills</h4>
			</div>
			<div class="col-md-6 text-right mb-5">

			</div>
		</div>




		<div class="card">
			<div class="card-body" style="min-height:500px">

				<h6 class="montserrat font-weight-bold">Unpaid Invoices</h6>
				<hr class="hr">

				<table class="table table-condensed datatables">
					<thead class="grey lighten-3 font-weight-bold">
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Bill #</th>
							<th>Patient ID</th>
							<th>Name</th>
							<th>Narration</th>
							<th class="text-right">Bill Amount</th>
							<th class="text-right">Paid</th>
							<th class="text-right">Balance</th>
							<th></th>
						</tr>
					</thead>
					<tbody>

						<ul class="list-group">


							<?php
							$i = 1;
							$result = $billing->PendingBills();

							foreach ($result as $bills) {
								$p->patient_id = $bills['patient_id'];
								$p->PatientInfo();
								$othernames = $p->othernames;

								// $billing=new Billing();
								$billing->bill_id = $bills['bill_id'];
								$billing->BillInfo();

								// if($billing->payment_status=='PAID'){
								// 	continue;
								// }
							?>
								<tr class="" style="font-size:11px">
									<td><?php echo $i++; ?></td>
									<td><?php echo $bills['date']; ?></td>
									<td><?php echo $bills['bill_id']; ?></td>
									<td><?php echo $p->patient_id; ?></td>
									<td><?php echo ucfirst(mb_strtolower($p->patient_fullname)); ?></td>
									<td><?php echo $bills['narration']; ?></td>
									<td class="text-right"><?php echo $bills['bill_amount']; ?></td>
									<td class="text-right"><?php echo $billing->total_bill_payment; ?></td>
									<td class="text-right"><?php echo number_format($billing->balance_remaining, 2); ?></td>
									<td class="text-right">
										<div class="dropdown open">
											<button type="button" class="btn btn-primary btn-sm" aria-hidden id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Options</button>
											<div class="dropdown-menu p-0 b-0" aria-labelledby="dropdownMenu1">
												<ul class="list-group minioptions">
													<li class="list-group-item payment_btn" id="<?php echo $bills['bill_id']; ?>"> <i class="far fa-credit-card mr-2"></i> Payment</li>

													<li class="list-group-item modify_billing" id="<?php echo $bills['bill_id']; ?>" i><i class="fas fa-pencil-alt mr-2" aria-hidden></i> Modify Bill</li>
													<li class="list-group-item delete_bill" id="<?php echo $bills['bill_id']; ?>" i><i class="fas fa-trash-alt mr-2" aria-hidden></i> Delete Bill</li>
												</ul>
											</div>
										</div>
									</td>
								</tr>

							<?php
							}
							?>


						</ul>
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
	$('#accounting_nav').addClass('active')
	$('#accounting_submenu').addClass('show')
	$('#billing_li').addClass('font-weight-bold')

	$('.bill_info').on('click', function(event) {
		event.preventDefault();
		var bill_id = $(this).attr('ID')
		$.get('../serverscripts/admin/Cashier/bill_info.php?bill_id=' + bill_id, function(msg) {
			$('#modal_holder').html(msg)
			$('#bill_info_modal').modal('show')
		})
	});

	$('.table tbody').on('click', '.delete_bill', function(event) {
		event.preventDefault();
		var bill_id = $(this).attr('ID')
		bootbox.confirm("Delete this invoice?", function(r) {
			if (r === true) {
				$.get('../serverscripts/admin/Billing/delete_bill.php?bill_id=' + bill_id, function(msg) {
					if (msg === 'delete_successful') {
						bootbox.alert("Bill Invoice Deleted Successfully", function() {
							window.location.reload()
						})
					} else {
						bootbox.alert(msg)
					}
				})
			}
		})

	});

	$('.payment_btn').on('click', function(event) {
		event.preventDefault();
		var bill_id = $(this).attr('ID')
		var patient_id = $(this).data('patient_id');
		var visit_id = $(this).data('visit_id');
		$.get('../serverscripts/admin/Patients/payment_modal.php?patient_id=' + patient_id + '&visit_id=' + visit_id + '&bill_id=' + bill_id, function(msg) {
			$('#modal_holder').html(msg)
			$('#new_payment_modal').modal('show')

			$('#amount_paid').on('keyup', function(event) {
				event.preventDefault();
				var amount_payable = $('#amount_payable').val()
				var amount_paid = $('#amount_paid').val()
				$('#balance').val((parseFloat(amount_payable) - parseFloat(amount_paid)).toFixed(2))
			});
			// End keyup

			$('#payment_frm').on('submit', function(event) {
				event.preventDefault();
				$.ajax({
					url: '../serverscripts/admin/Patients/payment_frm.php',
					type: 'GET',
					data: $('#payment_frm').serialize(),
					success: function(msg) {
						if (msg === 'save_successful') {
							bootbox.alert('Payment successful', function() {
								window.location = 'payments.php'
							})
						} else {
							bootbox.alert(msg)
						}
					}
				})
			}); //end submit
		})
	}); //end click


	$('.modify_billing').on('click', function(event) {
		event.preventDefault();
		var bill_id = $(this).attr('ID')
		var patient_id = $(this).data('patient_id');
		var visit_id = $(this).data('visit_id');

		$.get('../serverscripts/admin/Billing/modify_billing.php?patient_id=' + patient_id + '&visit_id=' + visit_id + '&bill_id=' + bill_id, function(msg) {
			$("#modal_holder").html(msg)
			$('#modify_billing_modal').modal('show')
			$('#modify_billing_frm').on('submit', function(event) {
				event.preventDefault();
				$.ajax({
					url: '../serverscripts/admin/Billing/modify_billing_frm.php',
					type: 'GET',
					data: $('#modify_billing_frm').serialize(),
					success: function(msg) {
						if (msg === 'update_successful') {
							bootbox.alert('Bill Amount Modified', function() {
								window.location.reload()
							})
						} else {
							bootbox.alert(msg)
						}
					}
				})
			});
		})
	});


	$(document).ready(function() {


		$('#new_patient_frm').on('submit', function(event) {
			event.preventDefault();
			bootbox.confirm("Create new folder?", function(r) {
				if (r === true) {
					$.ajax({
						url: '../serverscripts/admin/Patients/new_patient_frm.php',
						type: 'GET',
						data: $('#new_patient_frm').serialize(),
						success: function(msg) {
							if (msg === 'save_successful') {
								bootbox.alert("Folder Created Successfully", function() {
									window.location = 'patient_folder.php'
								})
							} else {
								bootbox.alert(msg)
							}
						}
					})
				}
			})

		}); //end submit


		$('.table tbody').on('click', '.edit', function(event) {
			event.preventDefault();
			var drug_id = $(this).attr('ID')

			$.ajax({
				url: '../serverscripts/admin/inventory_edit.php?drug_id=' + drug_id,
				type: 'GET',
				success: function(msg) {
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
							success: function(msg) {
								if (msg === 'save_successful') {
									bootbox.alert('Drug Information Updated Successfully', function() {
										window.location.reload()
									})
								} else {
									bootbox.alert(msg)
								}
							}
						}) //end ajax
					}); //end edit form
				}
			})

		}); //end edit function


		$('.table tbody').on('click', '.delete', function(event) {
			event.preventDefault();

			var drug_id = $(this).attr('ID')
			bootbox.confirm("Are you sure you want to delete this item?", function(r) {
				if (r === true) {
					$.ajax({
						url: '../serverscripts/admin/inventory_delete.php?drug_id=' + drug_id,
						type: 'GET',
						success: function(msg) {
							if (msg === 'delete_successful') {
								bootbox.alert("Drug deleted successfully", function() {
									window.location.reload()
								})
							} else {
								bootbox.alert(msg)
							}
						}
					})
				}
			})
		}); //end delete



	});
</script>

</html>