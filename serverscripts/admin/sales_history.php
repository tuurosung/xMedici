<table class="table " data-search='true'>
  <thead>
    <tr>
      <th>#</th>
      <th>Item Code</th>
      <th>Item Name</th>
      <th>Unit Price</th>
      <th>Qty</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php
    require_once '../dbcon.php';
    $today=date('Y-m-d');
    $i=1;
    $get_transactions=mysqli_query($db,"SELECT * FROM cart_transactions WHERE date='".$today."'") or die('failed');

    while ($transaction_rows=mysqli_fetch_array($get_transactions)) {
      $get_carts=mysqli_query($db,  "SELECT * FROM sales WHERE transid='".$transaction_rows['transaction_id']."'  && status='SOLD'") or die('failed');
      while ($cart_rows=mysqli_fetch_array($get_carts)) {
        $get_item_info=mysqli_query($db,"SELECT * FROM inventory WHERE drug_id='".$cart_rows['item_code']."'") or die('failed');
        $get_item_info=mysqli_fetch_array($get_item_info);

        ?>

        <tr id="">
          <td><?php echo $i++; ?></td>
          <td><?php echo $cart_rows['item_code']; ?></td>
          <td><?php echo $get_item_info['drug_name']; ?></td>
          <td><?php echo $cart_rows['selling_price']; ?></td>
          <td><?php echo $cart_rows['qty']; ?></td>
          <td><?php echo $cart_rows['total_cost']; ?></td>


        </tr>


        <?php

      }
    }

    ?>

  </tbody>
</table>
