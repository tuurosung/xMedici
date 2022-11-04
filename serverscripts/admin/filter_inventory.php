<?php
require_once '../dbcon.php';
$search_term=$_GET['search_term'];
$get_items=mysqli_query($db,"SELECT * FROM inventory WHERE drug_name LIKE '%".$search_term."%' && status='active' && subscriber_id='".$active_subscriber."'  LIMIT 50")  or die('failed');
 ?>



<table class="table datatable table-condensed" data-search='true'>
  <thead class="elegant-color-dark white-text">
    <tr>
      <th>#</th>
      <th>Description</th>
      <th>Manufacturer</th>
      <th>Shelf</th>
      <th class="text-center">Qty</th>
      <th class="text-right">Price</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    // require_once '../dbcon.php';
    // $get_items=mysqli_query($db,"SELECT * FROM inventory WHERE status='active' && subscriber_id='".$active_subscriber."' ORDER BY drug_name LIMIT 50")  or die('failed');
    $i=1;
    while ($rows=mysqli_fetch_array($get_items)) {

      $manufacturer_info=manufacturer_info($rows['manufacturer']);

      ?>

      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $rows['drug_name']; ?></td>
        <td><?php echo $manufacturer_info['name']; ?></td>
        <td><?php echo $rows['shelf']; ?></td>
        <td class="text-center"><?php  echo $rows['remaining_stock']; ?></td>
        <td class="text-right"><?php  echo $rows['retail_price'] ; ?></td>
        <td class="text-right">
          <button type="button" class="btn btn-info btn-sm sell" id="<?php echo $rows['drug_id']; ?>">Add To Cart</button>

        </td>

      </tr>

      <?php
    }
    ?>

  </tbody>
</table>
