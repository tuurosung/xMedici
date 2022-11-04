<?php
require_once '../../dbcon.php';
require_once '../../Classes/Invoices.php';

$inv=new Invoice();

$start_date=clean_string($_GET['start_date']);
$end_date=clean_string($_GET['end_date']);
 ?>

<div class="infoboxes px-3 py-3 mb-5">
  <div class="row">
    <div class="col-md-3">
      Total Invoice Values
      <h5 class="big-text">GHS <?php echo number_format($inv->InvoiceSales($start_date,$end_date),2) ?></h5>
    </div>
  </div>
</div>

<table class="table datatables table-condensed" data-search='true'>
  <thead class="">
    <tr>
      <th>#</th>
      <th>Date</th>
      <th>Invoice ID</th>
      <th>Customer</th>
      <th>P/0 #</th>
      <th class="text-right">Cost</th>
      <th class="text-right">VAT</th>
      <th class="text-right">Total</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php

    $total=0;
    $get_items=mysqli_query($db,"
                                        SELECT
                                        *
                                        FROM invoices
                                        WHERE  subscriber_id='".$active_subscriber."' && status='active' && lockstatus='locked' AND date_issued BETWEEN '".$start_date."' AND '".$end_date."'
                        ")  or die(mysqli_error($db));
    $i=1;
    while ($rows=mysqli_fetch_array($get_items)) {
      $customer_info=customer_info($rows['customer_id']);
      ?>

      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['date_issued']; ?></td>
        <td><?php echo $rows['invoice_id']; ?></td>
        <td><?php echo $customer_info['customer_name']; ?></td>
        <td><?php echo $rows['purchase_order_number']; ?></td>
        <td class="text-right"><?php echo $rows['supply_cost']; ?></td>
        <td class="text-right"><?php echo $rows['vat_amount']; ?></td>
        <td class="text-right"><?php echo $rows['invoice_value']; ?></td>
        <td class="text-right">
          <div class="dropdown open">
            <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Options
            </button>
            <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu1">
              <ul class="list-group">
                <li class="list-group-item"><a href="invoice_preview.php?invoice_id=<?php echo $rows['invoice_id']; ?>">Preview</a></li>
                <li class="list-group-item" id="<?php echo $rows['invoice_id']; ?>">Delete</li>
                <li class="list-group-item" id="<?php echo $rows['invoice_id']; ?>">Print</li>
              </ul>
            </div>
          </div>

        </td>

      </tr>

      <?php
      $total+=$rows['invoice_value'];
    }
    ?>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td class="text-right"><?php echo number_format($total,2); ?></td>
      <td></td>
    </tr>
  </tbody>
</table>
