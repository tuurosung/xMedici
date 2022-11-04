<?php
require_once '../dbcon.php';

$item_code=$_GET['item_code'];
$batch_code=$_GET['batch_code'];
$stockout_date=$_GET['stockout_date'];
$stockout_purpose=$_GET['stockout_purpose'];
$stockout_qty=$_GET['stockout_qty'];
// $qty_pcs=$_GET['qty_pcs'];
//$date=date("Y-m-d");

$insert_string=mysqli_query($db,"INSERT INTO stockout
                                (item_code,batch_code,stockout_purpose,stockout_qty,stockout_date)
                                VALUES
                                ('$item_code','$batch_code','$stockout_purpose','$stockout_qty','$stockout_date')
                                ") or die('failed');
if($insert_string){
  mysqli_query($db,"UPDATE stock SET qty_rem=qty_rem-$stockout_qty, qty_stockout=qty_stockout+$stockout_qty WHERE drug_id='".$item_code."' && batch_code='".$batch_code."'") or die('failed');
  echo 'save_successful';
}
else {
  echo 'failed';
}





?>
