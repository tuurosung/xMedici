<table class="table" data-toggle="table" data-search="true">
  <thead>
    <tr>
      <th>#</th>
      <th>Drug ID</th>
      <th>Drug Name</th>
      <th>Avail. Qty</th>
      <th>Unit Price</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    require_once '../dbcon.php';
    $get_items=mysqli_query($db,"SELECT * FROM inventory ORDER BY drug_name ASC")  or die('failed');
    $i=1;
    while ($rows=mysqli_fetch_array($get_items)) {

      $get_total=mysqli_query($db,"SELECT SUM(qty_rem) as total_qty FROM stock WHERE drug_id='".$rows['drug_id']."'") or die('failed');
      $get_total=mysqli_fetch_assoc($get_total);
      ?>

      <tr id="<?php echo $rows['drug_id']; ?>">
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['drug_id']; ?></td>
        <td><?php echo $rows['drug_name']; ?></td>
        <td><?php echo (int)$get_total['total_qty']; ?></td>
        <td><?php echo $rows['selling_price']; ?></td>

        <td class="text-right">


          <div class="dropdown">
          <button class="btn btn-primary btn-sm add_to_cart" type="button" id="<?php echo $rows['drug_id']; ?>">
            Load Drug
          </button>
        </div>
        </td>

      </tr>

      <?php
    }
    ?>

  </tbody>
</table>
