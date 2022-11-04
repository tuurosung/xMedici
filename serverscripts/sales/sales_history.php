<?php
require_once '../dbcon.php';
$today=date('Y-m-d');

$sales_sum=mysqli_query($db,"SELECT SUM(total_cost) as total_sales FROM sales WHERE date='".$today."'") or die('failed');
$sales_sum=mysqli_fetch_assoc($sales_sum);
$sales_sum=number_format($sales_sum['total_sales'],2);



$i=1;
$get_transactions=mysqli_query($db,"SELECT * FROM cart_transactions WHERE date='".$today."'") or die('failed');

?>

<div class="row">
  <div class="col-md-3">

    <div class="panel panel-green">
      <div class="panel-heading">
        Total Sales
      </div>
      <div class="panel-body" style="font-size:20px">
       GH&cent;  <?php echo number_format($sales_sum,2); ?>
      </div>
    </div>

  </div>
  <div class="col-md-4">

  </div>
</div>




<table class="table datatables">
  <thead>
    <tr>
      <th>#</th>
      <th>Item Code</th>
      <th>Item Name</th>
      <th>Unit Price</th>
      <th>Qty</th>
      <th>Total</th>
      <th>Attendant</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php


    while ($transaction_rows=mysqli_fetch_array($get_transactions)) {
      $get_carts=mysqli_query($db,  "SELECT * FROM sales WHERE transid='".$transaction_rows['transaction_id']."'  && status='SOLD'") or die('failed');
      while ($cart_rows=mysqli_fetch_array($get_carts)) {
        $get_item_info=mysqli_query($db,"SELECT * FROM inventory WHERE drug_id='".$cart_rows['item_code']."'") or die('failed');
        $get_item_info=mysqli_fetch_array($get_item_info);

        $get_attendant=mysqli_query($db,"SELECT * FROM users WHERE user_id='".$cart_rows['attendant']."'") or die('failed');
        $get_attendant=mysqli_fetch_row($get_attendant);


        ?>

        <tr id="">
          <td><?php echo $i++; ?></td>
          <td><?php echo $cart_rows['item_code']; ?></td>
          <td><?php echo $get_item_info['drug_name']; ?></td>
          <td><?php echo $cart_rows['selling_price']; ?></td>
          <td><?php echo $cart_rows['qty']; ?></td>
          <td><?php echo $cart_rows['total_cost']; ?></td>
          <td><?php echo $get_attendant[2]; ?></td>



        </tr>


        <?php

      }
    }

    ?>

  </tbody>
</table>
