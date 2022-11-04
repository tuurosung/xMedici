<?php
require_once '../dbcon.php';
$start_date=$_GET['start_date'];
$end_date=$_GET['end_date'];

$get_total=mysqli_query($db,"SELECT SUM(transaction_cost) AS total_transaction_cost FROM cart_transactions WHERE date='2016-12-28' ") or die('failed');
$total_transaction_cost=mysqli_fetch_assoc($get_total);
$total_transaction_cost=$total_transaction_cost['total_transaction_cost'];

$get_total_items=mysqli_query($db,"SELECT COUNT(*) as total_items FROM sales WHERE date='2016-12-28' AND status='SOLD'") or die('failed');
$get_total_items=mysqli_fetch_assoc($get_total_items);
$total_items=$get_total_items['total_items'];

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="../../assets/css/bootstrap.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.css" media="print" title="no title">

    <style media="print">
     .table > tbody{
       font-family: roboto;
     }

    body {
         column-count: 3;
         column-gap: 2em;
         column-rule: thin solid black;
         -moz-column-count:3; /* Firefox */
          -webkit-column-count:3; /* Safari and Chrome */
          font-family: roboto;
     }

     /* Default left, right, top, bottom margin is 2cm */
    @page {
      margin: 5px;
      -moz-margin:5px;
    }

    /* First page, 10 cm margin on top */
    @page :first {
      margin-top: 10px;
      -moz-margin-top:10px;
    }

    /* Left pages, a wider margin on the left */
    @page :left {
      margin-left: 5px;
      margin-right: 5px;
      -moz-margin-left: 5px;
      -moz-margin-right: 5px;
    }

    @page :right {
      margin-left: 5px;
      margin-right: 5px;
      -moz-margin-left: 5px;
      -moz-margin-right: 5px;
    }

    section {
      page-break-before: always;
      -moz-page-break-before:always;
    }
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
      padding: 2px;
      line-height: 1.0;
      vertical-align: top;
      border-top: 1px solid #ddd;
    }
    .column-center-outer {float:left}

    @media print {
      body {
           column-count: 3;
           column-gap: 2em;
           column-rule: thin solid black;
           -moz-column-count:3; /* Firefox */
            -webkit-column-count:3; /* Safari and Chrome */
            font-family: roboto;
       }
       section {
         page-break-before: always;
         -moz-page-break-before:always;
       }
    }

    </style>
  </head>


  <body>


<div class="row" style="margin-top:0px">
  <div class="col-md-4">
    <div class="panel panel-green">
      <div class="panel-heading">
        <i class="fa fa-line-chart"></i>
        Total Sales
      </div>
      <div class="panel-body" style="font-size:25px">
          <span>GH&cent; <?php  echo number_format($total_transaction_cost,2); ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel panel-green">
      <div class="panel-heading">
        <i class="fa fa-line-chart"></i>
        Number Of Items Sold
      </div>
      <div class="panel-body" style="font-size:25px">
          <span><?php echo $total_items; ?></span>
      </div>
    </div>
  </div>

</div>

<hr>
Date : 2016-12-27

<table class="table datatables">
  <thead>
    <tr>
      <th>#</th>
      <th>Item Name</th>
      <th>Unit Price</th>
      <th>Qty</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php

    $i=1;
    $get_transactions=mysqli_query($db,"SELECT * FROM cart_transactions WHERE  date='2016-12-28'") or die('failed');

    while ($transaction_rows=mysqli_fetch_array($get_transactions)) {
      $get_carts=mysqli_query($db,  "SELECT * FROM sales WHERE transid='".$transaction_rows['transaction_id']."'  && status='SOLD'") or die('failed');
      while ($cart_rows=mysqli_fetch_array($get_carts)) {
        $get_item_info=mysqli_query($db,"SELECT * FROM inventory WHERE item_code='".$cart_rows['item_code']."'") or die('failed');
        $get_item_info=mysqli_fetch_array($get_item_info);

        ?>

        <tr id="">
          <td><?php echo $i++; ?></td>
          <td><?php echo $get_item_info['item_name']; ?></td>
          <td><?php echo $cart_rows['selling_price']; ?></td>
          <td><?php echo $cart_rows['qty']; ?></td>
          <td><?php echo $cart_rows['total_cost']; ?></td>


        </tr>


        <?php

      }
    }

    $i=1;
    while ($rows=mysqli_fetch_array($get_stock)) {

      $get_item_info=mysqli_query($db,"SELECT * FROM inventory WHERE item_code='".$rows['item_code']."'") or die('failed');
      $get_item_info=mysqli_fetch_array($get_item_info);

      // $get_qty=mysqli_query($db,  "SELECT SUM(qty_pkg) as total_pkg, SUM(qty_pcs) as total_pcs FROM stock WHERE item_code='".$rows['item_code']."'") or die('failed');
      // $get_qty=mysqli_fetch_assoc($get_qty);
      //
      // $total_pkg=$get_qty['total_pkg'];
      // $total_pcs=$get_qty['total_pcs'];
      ?>



      <?php
    }
    ?>

  </tbody>
</table>

</body>
</html>
