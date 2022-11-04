<?php
require_once '../dbcon.php';
$item_code=$_GET['item_code'];
$get_stocking=mysqli_query($db,"SELECT * FROM stockout") or die('failed1');
?>

<div class="header">
    <h4 class="title">Stockout History</h4>
    <p class="category">Displays a list of all items that have been stocked out</p>
</div>
<hr>
<div class="content">

<table class="table datatables" style="font-size:12px">
  <thead>
    <tr>
      <th>#</th>
      <th>Date</th>
      <th>Item Name8</th>
      <th>Batch Code</th>
      <th>Stockout Purpose</th>
      <th>Stockout Qty</th>


    </tr>
  </thead>
  <tbody>
      <?php
      $i=1;
      while ($rows=mysqli_fetch_array($get_stocking)) {
        $get_item_info=mysqli_query($db,"SELECT * FROM inventory WHERE drug_id='".$rows['item_code']."'") or die('failed');
        $get_item_info=mysqli_fetch_array($get_item_info);

        $item_code=$get_item_info['drug_id'];
        $item_name=$get_item_info['drug_name'];

        ?>
          <tr>
            <td><?php  echo $i++; ?></td>
            <td><?php echo $rows['stockout_date']; ?></td>
            <td><?php echo $item_name; ?></td>
            <td><?php echo $rows['batch_code']; ?></td>
            <td><?php echo $rows['stockout_purpose']; ?></td>
            <td><?php echo $rows['stockout_qty']; ?></td>

          </tr>
        <?php
      }

       ?>

    </tbody>
  </table>
</div>
