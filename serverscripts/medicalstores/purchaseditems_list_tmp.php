<?php
require_once '../dbcon.php';

session_start();

$purchase_id=$_SESSION['active_purchase_id'];

?>

<table class="table">
  <thead>
    <tr>
      <th>#</th>
      <th>Item Name</th>
      <th>Cost</th>
      <th>Qty</th>
      <th>Total</th>
      <th>

      </th>
    </tr>
  </thead>
  <tbody>
    <?php
    $select=mysqli_query($db,"SELECT * FROM stores_purchaseditems WHERE purchase_id='".$purchase_id."'") or die('failed');
    $i=1;
    while ($rows=mysqli_fetch_array($select)) {
      ?>
      <tr>
        <td><?php echo $i++ ; ?></td>
        <td><?php echo $rows['drug_name']; ?></td>
        <td><?php echo $rows['supply_price']; ?></td>
        <td><?php echo $rows['qty'] ?></td>
        <td class="text-right"><?php echo $rows['cost']; ?></td>
        <td class="text-right">
          <i class="fa fa-trash-o"></i>
        </td>
      </tr>

      <?php
      $total_cost+=$rows['cost'];
    }

    ?>
    <td> </td>
    <td> </td>
    <td> </td>
    <td class="text-right" style="font-weight:bold; font-size:13px">Total </td>
    <td class="text-right" style="font-weight:bold; font-size:13px"><?php echo number_format($total_cost,2); ?></td>
    <td> </td>

  </tbody>
</table>
