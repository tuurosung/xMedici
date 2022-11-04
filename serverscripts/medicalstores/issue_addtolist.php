<?php
require_once '../dbcon.php';
session_start();
$issue_id=$_SESSION['active_issue_id'];
$drug_id=$_GET['drug_id'];
$drug_name=$_GET['drug_name'];
$qty=$_GET['qty'];


$save_string=mysqli_query($db,"INSERT INTO stores_issueditems
                              (issue_id,drug_id,drug_name,qty)
                              VALUES
                              ('$issue_id','$drug_id','$drug_name','$qty')
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
