<?php
require_once '../dbcon.php';
$sn=$_GET['sn'];

$get_sale_info=mysqli_query($db,"SELECT * FROM sales WHERE sn='".$sn."'") or die('failed');
$get_sale_info=mysqli_fetch_array($get_sale_info);

$drug_id=$get_sale_info['item_code'];
$batch_code=$get_sale_info['batch_code'];
$qty=$get_sale_info['qty'];

$reverse=mysqli_query($db,"UPDATE stock SET qty_rem=qty_rem + $qty, qty_sold=qty_sold - $qty WHERE drug_id='".$drug_id."'") or die('failed');

if($reverse){
  $delete=mysqli_query($db,"DELETE FROM sales WHERE sn='".$sn."'") or die('failed');
  echo 'delete_successful';
}
else {
  echo 'failed';
}


?>
