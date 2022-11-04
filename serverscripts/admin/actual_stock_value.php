<?php
require_once '../dbcon.php';

$stock_value=mysqli_query($db,"SELECT drug_id,cost_price FROM inventory ") or die('failed');
$total_value=0;
while ($rows=mysqli_fetch_array($stock_value)) {
  $get_value=mysqli_query($db,"SELECT SUM(qty_rem) as qty_rem FROM stock WHERE drug_id='".$rows['drug_id']."'") or die('failed');
  $get_value=mysqli_fetch_assoc($get_value);

  $total_value += $get_value['qty_rem'] * $rows['cost_price'];
}


echo 'GH&cent;&nbsp;'. number_format($total_value,2);
?>
