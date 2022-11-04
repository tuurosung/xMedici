<?php
require_once '../dbcon.php';
$invoice_id=$_GET['invoice_id'];

$get_invoice_info=mysqli_query($db,"SELECT * FROM invoices WHERE invoice_id='".$invoice_id."'") or die('failed');
$invoice_info=mysqli_fetch_array($get_invoice_info);

$supplier_id=$invoice_info['supplier_id'];

$supplier_info=mysqli_query($db,"SELECT * FROM suppliers WHERE supplier_id='".$supplier_id."'") or die('failed');
$supplier_info=mysqli_fetch_row($supplier_info);


?>

<div class="container-fluid" style="">
  <div class="row">
    <div class="col-md-6">
        <h4 style="font-size:18px" class="page-header">Issued By:</h4>
        <span style="margin-left:20px; font-size:16px"><?php echo $supplier_info[2]; ?></span><br>
        <span style="margin-left:20px; font-size:14px"><i class="fa fa-phone"></i>: <?php echo $supplier_info[3]; ?></span><br>
        <span style="margin-left:20px; font-size:14px"><i class="fa fa-map-marker"></i>: <?php echo $supplier_info[4]; ?></span>
    </div>
    
  </div>

  <div class="row" style="margin-top:20px; margin-bottom:20px">
    <div class="col-md-12">
      <div style="padding:5px; border-style:solid; border-width:1px; width:130px; font-size:16px">
        INVOICE
      </div>
      <br>
      <table class="table">
        <tbody>
          <tr style="background-color:#000; color:#fff">
            <td>sn</td>
            <td>Description</td>
            <td class="text-right">Purchase Cost</td>
          </tr>
          <tr>
            <td>1.</td>
            <td>Supply Of Goods</td>
            <td class="text-right"><?php echo $invoice_info['purchase_cost']; ?></td>
          </tr>
          <tr style="text-decoration:bold">
            <td></td>
            <td class="text-right">Total</td>
            <td class="text-right"><?php echo $invoice_info['purchase_cost']; ?></td>
          </tr>

          <tr style="text-decoration:bold">
            <td></td>
            <td class="text-right">Amount Paid</td>
            <td class="text-right"><?php echo $invoice_info['amount_paid']; ?></td>
          </tr>

          <tr style="text-decoration:bold">
            <td></td>
            <td class="text-right">Balance Remaining</td>
            <td class="text-right"><?php echo $invoice_info['balance_remaining']; ?></td>
          </tr>
        </tbody>
      </table>
      <hr>
      <div class="row">
        <div class="col-md-4 col-md-offset-8 text-center" style="margin-right:5px">
          .................................................<br>
          ( Distributor )
        </div>
      </div>
    </div>
  </div>
</div>
