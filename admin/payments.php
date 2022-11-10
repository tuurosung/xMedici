<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php
$p = new Patient();
$pmt = new Payment();


switch ($access_level) {
	case 'doctor':
		echo "<script>window.location='doctorsdashboard.php'</script>";
		break;
	case 'pharmacist':
		echo "<script>window.location='prescriptions.php'</script>";
		break;
	case 'nurse':
		echo "<script>window.location='nursesdashboard.php'</script>";
		break;

	default:
		// code...
		break;
}
?>

<main class="py-3 mx-lg-5 main">
	<div class="container-fluid mt-2">

		<div class="row">
			<div class="col-md-6 ">
				<h4 class="titles montserrat">Payments</h4>
			</div>
			<div class="col-md-6 text-right">
				<div class="btn-group">


					<div class="dropdown open">
						<button class="btn btn-primary btn-rounded dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Dropdown
						</button>
						<div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
							<ul class="list-group">
								<li class="list-group-item" id="print">Print List</li>
							</ul>
						</div>
					</div>

				</div>


			</div>
		</div>


		<div class="row my-5">
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-3">
								<div class="icon-box primary-color d-flex justify-content-center align-items-center">
									<i class="fas fa-credit-card" aria-hidden></i>
								</div>
							</div>
							<div class="col-9">
								<p class="big-text montserrat">GHS <?php echo number_format($pmt->todays_revenue, 2) ?></p>
								Today's Payments
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-3">
								<div class="icon-box primary-color d-flex justify-content-center align-items-center">
									<i class="far fa-calendar-alt" aria-hidden></i>
								</div>
							</div>
							<div class="col-9">
								<p class="big-text montserrat">GHS <?php echo number_format($pmt->monthly_revenue, 2) ?></p>
								Monthly Revenue
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="card mb-5">
			<div class="card-body">
				<h6 class="montserrat font-weight-bold mb-3">Filter Payments</h6>
				<form id="filter_payments_frm">
					<div class="row">
						<div class="col-md-3">
							<label for="">Start Date</label>
							<input type="text" name="start_date" id="start_date" class="form-control" placeholder value="<?php echo $today; ?>">
						</div>
						<div class="col-md-3">
							<label for="">End Date</label>
							<input type="text" name="end_date" id="end_date" class="form-control" placeholder value="<?php echo $today; ?>">
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Payment Account</label>
								<select class="custom-select browser-default" name="payment_account" id="payment_account" required>
									<option value="all">All</option>
									<?php
									$get_accounts = mysqli_query($db, "
													SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
													LEFT JOIN account_headers h on h.sn=a.account_header
													WHERE h.type=4 AND subscriber_id='" . $active_subscriber . "';
													") or die('failed');
									while ($accounts = mysqli_fetch_array($get_accounts)) {
									?>
										<option value="<?php echo $accounts['account_number']; ?>"><?php echo $accounts['account_name']; ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<button type="submit" class="btn btn-primary" style="margin-top:27px; padding-top:0.7rem; padding-bottom:0.7rem"><i class="fas fa-file-alt mr-2" aria-hidden></i> Generate Report</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="card">
			<div class="card-body" id="data_holder">
				<h6 class="montserrat font-weight-bold">Payments Received</h6>
				<hr class="hr ">

				<div class="spacer"></div>

				<table class="table table-condensed datatables">
					<thead class="grey lighten-3 font-weight-bold">
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Bill#</th>
							<th>Patient ID</th>
							<th>Name</th>
							<th>Narration</th>
							<th class="text-right">Amount Paid</th>
							<th class="text-right">Balance</th>
							<th></th>
						</tr>
					</thead>
					<tbody>

						<?php
						$i = 1;

						$payments = $pmt->AllPayments($today, $today, '', '');

						foreach ($payments as $rows) {
							$p->patient_id = $rows['patient_id'];
							$p->PatientInfo();
							$othernames = $p->othernames;

							$billing = new Billing();
							$billing->bill_id = $rows['bill_id'];
							$billing->BillInfo();

							// if($billing->payment_status=='PAID'){
							// 	continue;
							// }
						?>
							<tr class="py-2">
								<td class="py-2"><?php echo $i++; ?></td>
								<td><?php echo $rows['date']; ?></td>
								<td><?php echo $rows['bill_id']; ?></td>
								<td><?php echo $p->patient_id; ?></td>
								<td class="text-capitalize"><?php echo ucfirst(mb_strtolower($p->patient_fullname)); ?></td>
								<td><?php echo substr($billing->narration, 0, 80); ?></td>
								<td class="text-right"><?php echo $rows['amount_paid']; ?></td>
								<td class="text-right"><?php echo $rows['balance']; ?></td>
								<td class="text-right">

									<div class="dropdown open">
										<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Options
										</button>
										<div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
											<ul class="list-group">

												<li class="list-group-item print_btn" id="<?php echo $rows['payment_id']; ?>"><i class="fas fa-print mr-2" aria-hidden></i> Print Receipt</li>

												<li class="list-group-item edit_payment" id="<?php echo $rows['payment_id']; ?>"><i class="fas fa-pencil-alt mr-2" aria-hidden></i> Edit Payment</li>
												<li class="list-group-item delete_payment" id="<?php echo $rows['payment_id']; ?>"><i class="fas fa-trash-alt mr-2" aria-hidden></i> Delete Payment</li>
											</ul>
										</div>
									</div>

								</td>
							</tr>

						<?php
							$amount_paid += $rows['amount_paid'];
							$balance += $rows['balance'];
						}
						?>


					</tbody>
				</table>
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
	$('#payments_li').addClass('font-weight-bold')



	var start_date = $('#start_date').val()
	var end_date = $('#end_date').val()
	FilterPayments(start_date, end_date)

	$('.table tbody').on('click', '.print_btn', function(event) {
		event.preventDefault();
		alert('HI')
	})

	$('.table tbody').on('click', '.print_receipt', function(event) {
		event.preventDefault();
		alert('hi')
		var payment_id = $(this).attr('ID')
		window.open('payment_receipt.php?payment_id=' + payment_id, '_blank', 'width=200', 'height=150');
	});



	$('.payment_btn').on('click', function(event) {
		event.preventDefault();
		var bill_id = $(this).attr('ID')
		var patient_id = $(this).data('patient_id');
		var visit_id = $(this).data('visit_id');
		bootbox.confirm('Accept payment?', function(r) {
			if (r === true) {
				$.get('../../serverscripts/admin/Patients/payment_modal.php?patient_id=' + patient_id + '&visit_id=' + visit_id + '&bill_id=' + bill_id, function(msg) {
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
										window.location.reload()
									})
								} else {
									bootbox.alert(msg)
								}
							}
						})
					}); //end submit
				})
			}
		})
	}); //end click


	$('.table tbody').on('click', '.edit_payment', function(event) {
		event.preventDefault();
		var payment_id = $(this).attr('ID')
		EditPayment(payment_id)
	})

	$('.table tbody').on('click', '.delete_payment', function(event) {
		event.preventDefault();
		var payment_id = $(this).attr('ID')
		DeletePayment(payment_id)
	})

	$('#filter_payments_frm').on('submit', function(event) {
		event.preventDefault();
		var start_date = $('#start_date').val()
		var end_date = $('#end_date').val()
		var payment_account = $('#payment_account').val()
		FilterPayments(start_date, end_date, payment_account)
	});


	function FilterPayments(start_date, end_date, payment_account) {
		$.get('../serverscripts/admin/Payments/filter_payments.php?start_date=' + start_date + '&end_date=' + end_date + '&payment_account=' + payment_account, function(msg) {
			$('#data_holder').html(msg)
			// $('table').DataTable();

			$('.table tbody').on('click', '.print_btn', function(event) {
				event.preventDefault();
				var payment_id = $(this).attr('ID')
				window.open('payment_receipt.php?payment_id=' + payment_id, '_blank', 'width=200', 'height=150');
			});


			$('.table tbody').on('click', '.edit_payment', function(event) {
				event.preventDefault();
				var payment_id = $(this).attr('ID')
				EditPayment(payment_id)
			})

			$('.table tbody').on('click', '.delete_payment', function(event) {
				event.preventDefault();
				var payment_id = $(this).attr('ID')
				DeletePayment(payment_id)
			})
		})
	}

	function EditPayment(payment_id) {
		$.get('../serverscripts/admin/Billing/edit_payment_modal.php?payment_id=' + payment_id, function(msg) {
			$('#modal_holder').html(msg);
			$('#edit_payment_modal').modal('show')
		})
	}

	function DeletePayment(payment_id) {
		bootbox.confirm("Delete this payment permanently?", function(r) {
			if (r === true) {
				$.get('../serverscripts/admin/Billing/delete_payment.php?payment_id=' + payment_id, function(msg) {
					if (msg === 'delete_successful') {
						bootbox.alert('Payment Deleted Successfully', function() {
							window.location.reload();
						})
					}
				}) //end get
			} //end if
		}) //end confirm
	}
</script>

</html>