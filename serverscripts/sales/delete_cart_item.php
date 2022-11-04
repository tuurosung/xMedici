<?php
require '../dbcon.php';

$sn=$_GET['sn'];

$delete=mysqli_query($db,"DELETE FROM sales WHERE sn='".$sn."'") or die('failed');

if($delete){
  echo 'success';
}
else {
  echo 'failed';
}



?>
