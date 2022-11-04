<?php
session_start();
$issue_id=$_SESSION['active_issue_id'];
require_once '../dbcon.php';

$select=mysqli_query($db,"SELECT * FROM stores_issues WHERE issue_id='".$issue_id."'") or die('failed');

$issue_info=mysqli_fetch_array($select);

$unit=$issue_info['unit'];
//echo $unit;

if($unit=='retail'){
  $select_items=mysqli_query($db,"SELECT * FROM stores_issueditems WHERE issue_id='".$issue_id."'") or die('failed');
  while ($items=mysqli_fetch_array($select_items)) {
    $min=111111;
    $max=999999;
    $batch_code=rand($min,$max);
    $drug_id=$items['drug_id'];
    $qty_stocked=$items['qty'];
    $stocking_date=date('Y-m-d');


    $insert_string=mysqli_query($db,"INSERT INTO stock
                                    (drug_id,batch_code,qty_stocked,stock_date,qty_sold,qty_rem)
                                    VALUES
                                    ('$drug_id','$batch_code','$qty_stocked','$stocking_date',0,'$qty_stocked')
                                    ") or die('failed');
  }
  echo 'update_complete';
}
else
if($unit=='wholesale') {
  $select_items=mysqli_query($db,"SELECT * FROM stores_issueditems WHERE issue_id='".$issue_id."'") or die('failed');
  while ($items=mysqli_fetch_array($select_items)) {
    $min=111111;
    $max=999999;
    $batch_code=rand($min,$max);
    $drug_id=$items['drug_id'];
    $qty_stocked=$items['qty'];
    $stocking_date=date('Y-m-d');


    $insert_string=mysqli_query($db2,"INSERT INTO wholesale.stock
                                    (drug_id,batch_code,qty_stocked,stock_date,qty_sold,qty_rem)
                                    VALUES
                                    ('$drug_id','$batch_code','$qty_stocked','$stocking_date',0,'$qty_stocked')
                                    ") or die('failed');
  }
  echo 'update_complete';
}
?>
