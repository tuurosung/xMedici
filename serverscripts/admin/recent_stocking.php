<table class="table datatables">
  <thead>
    <tr>
      <th>#</th>
      <th>Item Name</th>
      <th>Model Number</th>
      <th>Status</th>
      <th>Avail. Qty</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    require_once '../dbcon.php';
    $get_items=mysqli_query($db,"SELECT * FROM inventory")  or die('failed');
    $i=1;
    while ($rows=mysqli_fetch_array($get_items)) {

      $get_qty=mysqli_query($db,  "SELECT SUM(qty_stocked) as total_qty FROM stock WHERE item_code='".$rows['item_code']."'") or die('failed');
      $get_qty=mysqli_fetch_assoc($get_qty);

      $total_qty=$get_qty['total_qty'];
      ?>

      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['item_name']; ?></td>
        <td><?php echo $rows['model_number']; ?></td>
        <td>In-Stock</td>
        <td><?php  echo $total_qty; ?></td>
        <td class="text-right">
          <i class="fa fa-plus-square action_buttons add_stock" id="<?php echo $rows['item_code']; ?>" data-toggle="tooltip" title="add stock"></i>
          <i class="fa fa-binoculars action_buttons view" id="<?php echo $rows['item_code']; ?>"></i>
        </td>

      </tr>

      <?php
    }
    ?>

  </tbody>
</table>
