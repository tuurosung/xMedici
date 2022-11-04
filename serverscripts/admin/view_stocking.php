<?php
require_once '../dbcon.php';

$item_code=$_GET['item_code'];

$get_stocking=mysqli_query($db,"SELECT * FROM stock WHERE drug_id='".$item_code."' ORDER BY stock_date DESC") or die('failed');


$get_item_info=mysqli_query($db,"SELECT * FROM inventory WHERE drug_id='".$item_code."'") or die('failed');
$get_item_info=mysqli_fetch_array($get_item_info);

$item_code=$get_item_info['drug_id'];
$item_name=$get_item_info['drug_name'];

?>


<div class="header">
    <h4 class="title">Stocking Information of <?php echo $item_name; ?></h4>
    <p class="category">Add a new drug that already doesnt exist in inventory</p>
</div>
<hr>
<div class="content">
  <div class="">
    <span class="pull-right">
      <button type="button" class="btn btn-primary btn-sm return">
        <i class="fa fa-print"></i>
        Print History
      </button>
      <button type="button" class="btn btn-default btn-sm return">
        <i class="fa fa-undo"></i>
        Return To Items
      </button>
    </span>
    <br><br>
    <table class="table datatables" style="font-size:12px">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Batch Code</th>
          <th>Qty Stocked</th>
          <th>Qty Sold</th>
          <th>Qty Remaining</th>
          <th>Damaged</th>
          <th>Out</th>
          <th>

          </th>

        </tr>
      </thead>
      <tbody>
        <?php
        $i=1;
        while ($rows=mysqli_fetch_array($get_stocking)) {
          ?>
            <tr>
              <td><?php  echo $i++; ?></td>
              <td><?php echo $rows['stock_date']; ?></td>
              <td><?php echo $rows['batch_code']; ?></td>
              <td><?php echo $rows['qty_stocked']; ?></td>
              <td><?php echo $rows['qty_sold']; ?></td>
              <td><?php echo $rows['qty_rem']; ?></td>
              <td><?php echo $rows['qty_dmg']; ?></td>
              <td><?php echo $rows['qty_stockout']; ?></td>
              <td>
                <i class="fa fa-trash-o action_buttons delete_stocking" id="<?php echo $rows['sn']; ?>"></i>
              </td>
            </tr>
          <?php
        }

         ?>

      </tbody>
    </table>
  </div>

</div>
