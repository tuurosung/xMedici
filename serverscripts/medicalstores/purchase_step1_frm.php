<?php
require_once '../dbcon.php';

$purchase_id=$_GET['purchase_id'];
$supplier_id=$_GET['supplier_id'];
$invoice_id=$_GET['invoice_id'];


$save_string=mysqli_query($db,"INSERT INTO stores_purchases
                                (purchase_id,supplier_id,invoice_id,status)
                                VALUES
                                ('$purchase_id','$supplier_id','$invoice_id','PENDING')
                          ") or die('failed');


if($save_string){
  session_start();
  $_SESSION['active_purchase_id']=$purchase_id;
  echo 'save_successful';
}
else {
  echo 'failed';
}

?>
