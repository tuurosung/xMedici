
<table class="table datatables">
  <thead>
    <tr>
      <th>#</th>
      <th>Item Name</th>
      <th>Avail. Qty</th>
      <th>Price</th>
      <th class="text-right">Value</th>


    </tr>
  </thead>
  <tbody>
    <?php
    require_once '../dbcon.php';
    $get_items=mysqli_query($db,"SELECT drug_id,drug_name,restock_level,selling_price FROM inventory")  or die('failed');
    $i=1;
    $overall_total=0;
    while ($rows=mysqli_fetch_array($get_items)) {

      $get_qty=mysqli_query($db,  "SELECT SUM(qty_rem) as total_qty FROM stock WHERE drug_id='".$rows['drug_id']."'") or die('failed');
      $get_qty=mysqli_fetch_assoc($get_qty);

      $total_qty=$get_qty['total_qty'];



      ?>

      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['drug_name']; ?></td>
        <td><?php  echo (int)$total_qty; ?></td>
        <td><?php echo $rows['selling_price']; ?></td>
        <td class="text-right"><?php  echo number_format($rows['selling_price']*$total_qty,2); ?></td>



      </tr>
      <?php $overall_total +=$rows['selling_price']*$total_qty; ?>

      <?php
    }
    ?>


  </tbody>
</table>
