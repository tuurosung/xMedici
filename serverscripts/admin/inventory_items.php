<div class="header">
    <h4 class="title">All Drugs</h4>
    <p class="category">Displays a list of all drugs in inventory records</p>
</div>
<hr>
<div class="content">
  <table class="table datatables" >
    <thead>
      <tr>
        <th>#</th>
        <th>Item Name</th>
        <th>Restock</th>
        <th>Cost</th>
        <th>Selling</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php
      require_once '../dbcon.php';
      $get_items=mysqli_query($db,"SELECT sn,drug_id,drug_name,restock_level,cost_price,selling_price FROM inventory ORDER BY drug_name ASC")  or die('failed');
      $i=1;
      while ($rows=mysqli_fetch_array($get_items)) {
        ?>

        <tr class="text-uppercase">
          <td><?php echo $i++; ?></td>
          <td><?php echo $rows['drug_name']; ?></td>
          <td><?php echo $rows['restock_level']; ?></td>
          <td><?php echo $rows['cost_price']; ?></td>
          <td><?php echo $rows['selling_price']; ?></td>
          <td class="text-right">
            <i class="fa fa-pencil action_buttons edit" id="<?php echo $rows['drug_id']; ?>"></i>
            <i class="fa fa-trash action_buttons delete" id="<?php echo $rows['sn']; ?>"></i>
          </td>

        </tr>

        <?php
      }
      ?>

    </tbody>
  </table>

</div>
