<?php
require_once '../dbcon.php';
session_start();
$purchase_id=$_SESSION['active_purchase_id'];
$cost=$_GET['cost'];
$amount_paid=$_GET['amount_paid'];
$balance=$_GET['balance'];


$save_string=mysqli_query($db,"UPDATE stores_purchases SET total_cost='".$cost."',amount_paid='".$amount_paid."',balance='".$balance."' WHERE
                               purchase_id='".$purchase_id."'") or die('failed');


if($save_string){
  session_start();
  unset($_SESSION['active_purchase_id']);
  echo 'save_successful';
}
else {
  echo 'failed';
}

?>
