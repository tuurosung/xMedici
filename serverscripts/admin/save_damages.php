<?php
require_once '../dbcon.php';

$item_code=$_GET['item_code'];
$batch_code=$_GET['batch_code'];
$damage_date=$_GET['damage_date'];
$damage_details=$_GET['damage_details'];
$qty_damaged=$_GET['qty_damaged'];
// $qty_pcs=$_GET['qty_pcs'];
//$date=date("Y-m-d");

$insert_string=mysqli_query($db,"INSERT INTO damages
                                (item_code,batch_code,damage_details,qty_damaged,damage_date)
                                VALUES
                                ('$item_code','$batch_code','$damage_details','$qty_damaged','$damage_date')
                                ") or die('failed');
if($insert_string){
  mysqli_query($db,"UPDATE stock SET qty_rem=qty_rem-$qty_damaged, qty_dmg=qty_dmg+$qty_damaged WHERE item_code='".$item_code."' && batch_code='".$batch_code."'") or die('failed');
  echo 'save_successful';
}
else {
  echo 'failed';
}





?>
