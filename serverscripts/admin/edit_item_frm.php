<?php
require_once '../dbcon.php';

$drug_id=$_GET['drug_id'];
$unit=$_GET['unit'];
$drug_name=$_GET['drug_name'];
$drug_category=$_GET['drug_category'];
$manufacturer=$_GET['manufacturer'];
$restock_level=$_GET['restock_level'];
$cost_price=$_GET['cost_price'];
$wholesale_price=$_GET['wholesale_price'];
$retail_price=$_GET['retail_price'];
$shelf=$_GET['shelf'];
$date=date('Y-m-d');


$update_item_info=mysqli_query($db,"UPDATE inventory SET
                                    unit='".$unit."',
                                    drug_name='".$drug_name."',
                                    category='".$drug_category."',
                                    manufacturer='".$manufacturer."',
                                    restock_level='".$restock_level."',
                                    cost_price='".$cost_price."',
                                    wholesale_price='".$wholesale_price."',
                                    retail_price='".$retail_price."',
                                     shelf='".$shelf."'
                                    WHERE drug_id='".$drug_id."' AND subscriber_id='".$active_subscriber."'
                              ") or die(mysqli_error($db));



if($update_item_info){
  echo 'save_successful';
}
else {
  echo 'failed';
}
?>
