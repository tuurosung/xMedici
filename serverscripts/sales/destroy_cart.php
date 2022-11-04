<?php
require_once '../dbcon.php';

session_start();

$tracksales_transid=$_SESSION['tracksales_transid'];

$destroy_cart=mysqli_query($db,"DELETE FROM sales WHERE status='PENDING' &&  transid='".$tracksales_transid."'") or die('failed');

if($destroy_cart){
  $tracksales_transid='';
  echo 'delete_successful';
}
else {
  echo 'failed';
}






?>
