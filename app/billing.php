<?php require_once '../navigation/header_lite.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<?php

require_once '../serverscripts/Classes/Billing.php';
require_once '../serverscripts/Classes/Patient.php';

$billing = new Billing();
$p = new Patient();

// print_r($billing->PendingBills());

?>


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

<script type="text/javascript" src="../Includes/js/billing/billing.js"></script>

</html>