<?php
require_once '../dbcon.php';
$purchase_id=$_GET['purchase_id'];
$purchase_id=mysqli_query($db,"DELETE FROM stores_purchases WHERE purchase_id='".$purchase_id."'") or die('failed');

if($purchase_id){
  echo 'delete_successful';
}
else {
  echo 'failed';
}

?>
