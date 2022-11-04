<?php
require_once '../dbcon.php';
session_start();
$purchase_id=$_SESSION['active_purchase_id'];
$drug_id=$_GET['drug_id'];
$drug_name=$_GET['drug_name'];
$supply_price=$_GET['supply_price'];
$qty=$_GET['qty'];
$cost=$_GET['cost'];


$save_string=mysqli_query($db,"INSERT INTO stores_purchaseditems
                              (purchase_id,drug_id,drug_name,supply_price,qty,cost)
                              VALUES
                              ('$purchase_id','$drug_id','$drug_name','$supply_price','$qty','$cost')
                          ") or die('failed');


if($save_string){
  session_start();
  //$_SESSION['active_purchase_id']=$purchase_id;
  echo 'save_successful';
}
else {
  echo 'failed';
}

?>
