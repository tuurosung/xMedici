<table class="table datatables" data-search='true'>
  <thead>
    <tr>
      <th>#</th>
      <th>Item Name</th>
      <th>Status</th>
      <th>Avail. Qty</th>
      <th>Stock Val</th>
      <th>Expected Val</th>
      <th>Profit</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    require_once '../dbcon.php';
    $get_items=mysqli_query($db,"SELECT drug_id,drug_name,restock_level,cost_price,selling_price FROM inventory ORDER BY drug_name ASC LIMIT 20")  or die('failed');
    $i=1;
    while ($rows=mysqli_fetch_array($get_items)) {

      $get_qty=mysqli_query($db,  "SELECT SUM(qty_rem) as total_qty FROM stock WHERE drug_id='".$rows['drug_id']."'") or die('failed');
      $get_qty=mysqli_fetch_assoc($get_qty);

      $total_qty=$get_qty['total_qty'];

      if($total_qty > $rows['restock_level']){
        $status='In-Stock';
      }
      else
      if($total_qty < $rows['restock_level'] || $total_qty !== 0){
        $status='Critical-Level';
      }
      else
      if($total_qty==0){
        $status='Out-Of-Stock';
      }

      ?>

      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['drug_name']; ?></td>
        <td><?php echo $status; ?></td>
        <td><?php  echo (int)$total_qty; ?></td>
        <td class="text-right"><?php  echo $rows['cost_price'] * $total_qty; ?></td>
        <td class="text-right"><?php  echo $rows['selling_price'] * $total_qty; ?></td>
        <td class="text-right"><?php  echo ($rows['selling_price']-$rows['cost_price']) * $total_qty; ?></td>
        <td class="text-right">
          <button type="button" class="btn btn-info btn-sm add_stock" id="<?php echo $rows['drug_id']; ?>">Add Stock</button>
          <button type="button" class="btn btn-info btn-sm matrix" id="<?php echo $rows['drug_id']; ?>">Drug Matrix</button>


          <!-- <i class="fa fa-code-fork action_buttons stockout" id="<?php //echo $rows['drug_id']; ?>" data-toggle="tooltip" title="stockout"></i> -->
          <!-- <i class="fa fa-gavel action_buttons damaged" id="<?php //echo //$rows['drug_id']; ?>" data-toggle="tooltip" title="damaged stock"></i> -->

        </td>

      </tr>

      <?php
    }
    ?>

  </tbody>
</table>
