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











<?php
require_once '../dbcon.php';

$start_date=$_GET['start_date'];
$end_date=$_GET['end_date'];

$total_sales=mysqli_query($db,"SELECT SUM(total_cost) as total_sales FROM sales WHERE date BETWEEN '".$start_date."' AND '".$end_date."' AND status='SOLD'") or die('failed');
$total_sales=mysqli_fetch_assoc($total_sales);

$total_expenditure=mysqli_query($db,"SELECT SUM(expenditure_amount) as total_expenditure FROM expenditure WHERE date BETWEEN '".$start_date."' AND '".$end_date."'") or die('failed');
$total_expenditure=mysqli_fetch_assoc($total_expenditure);




?>
<h4 class="page-header">Analytics Report For The Period Between <?php echo date('d-m-Y',strtotime($start_date)); ?>  And <?php echo date('d-m-Y',strtotime($end_date)); ?></h4>

<div class="row">
  <div class="col-md-12">
    <h5>Total Sales Summary</h5>
    <table class="table">
      <thead>
        <tr>
          <th>Period</th>
          <th>Total Sales</th>
          <th>Total Expenditure</th>
          <th>Damages</th>
          <th class="text-right">Profit</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo date('d-m-Y',strtotime($start_date)); ?> &nbsp;-&nbsp; <?php echo date('d-m-Y',strtotime($end_date)); ?></td>
          <td><?php echo number_format($total_sales['total_sales'],2); ?></td>
          <td><?php echo number_format($total_expenditure['total_expenditure'],2); ?></td>
          <td>0.00</td>
          <td class="text-right"><?php echo number_format((float)$total_sales['total_sales'] - (float)$total_expenditure['total_expenditure'],2); ?></td>
        </tr>
      </tbody>
    </table>

    <br><br>

    <h5>Daily Sales Summary</h5>
    <table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th class="text-right">Total Sales</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $select=mysqli_query($db,"SELECT DISTINCT(date) as days FROM sales WHERE status='SOLD' AND date BETWEEN '".$start_date."' AND '".$end_date."'") or die('failed');
          while ($days=mysqli_fetch_array($select)) {
            $days_sales=mysqli_query($db,"SELECT SUM(total_cost) as days_sales FROM sales WHERE date='".$days['days']."' AND status='SOLD'") or die('failed');
            $days_sales=mysqli_fetch_assoc($days_sales);
            ?>

            <tr>
              <td><?php echo $days['days']; ?></td>
              <td class="text-right"><?php echo number_format($days_sales['days_sales'],2); ?></td>


            </tr>


            <?php
          }
        ?>
        <tr>
          <td>
            TOTAL
          </td>
          <td class="text-right">
            <strong><?php echo number_format($total_sales['total_sales'],2); ?></strong>
          </td>
        </tr>

      </tbody>
    </table>

    <br><br>

    <h5>Items Sales Summary</h5>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Item Code</th>
          <th>Qty Sold</th>
          <th>Unit Price</th>
          <th class="text-right">Total Sales</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $select=mysqli_query($db,"SELECT DISTINCT(item_code) as item_code FROM sales WHERE status='SOLD' AND date BETWEEN '".$start_date."' AND '".$end_date."'") or die('failed');
          $i=1;
          while ($items=mysqli_fetch_array($select)) {
            $details=mysqli_query($db,"SELECT SUM(total_cost) as total_item_sale, SUM(qty) as qty_sold FROM sales WHERE item_code='".$items['item_code']."' AND status='SOLD'") or die('failed');
            $details=mysqli_fetch_assoc($details);

            $get_item_info=mysqli_query($db,"SELECT * FROM inventory WHERE item_code='".$items['item_code']."'") or die('failed');
            $get_item_info=mysqli_fetch_array($get_item_info);
            ?>

            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $get_item_info['item_name']; ?></td>
              <td><?php echo $details['qty_sold']; ?></td>
              <td><?php echo $get_item_info['selling_price']; ?></td>
              <td class="text-right"><?php echo number_format($details['total_item_sale'],2); ?></td>


            </tr>


            <?php
          }
        ?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td>
            TOTAL
          </td>
          <td class="text-right">
            <strong><?php echo number_format($total_sales['total_sales'],2); ?></strong>
          </td>
        </tr>

      </tbody>
    </table>
  </div>

</div>


</body>
</html>
