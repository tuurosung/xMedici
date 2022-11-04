<?php
require_once '../../dbcon.php';
require_once '../../Classes/Drugs.php';

$drug_id=$_GET['drug_id'];
$batch_code=$_GET['batch_code'];
$stocking_date=$_GET['stocking_date'];
$qty_stocked=$_GET['qty_stocked'];
$expiry_date=$_GET['expiry_date'];
$timestamp=time();

reject_empty($drug_id);
reject_empty($batch_code);
reject_empty($stocking_date);
reject_empty($qty_stocked);
// reject_empty($expiry_date);

$drug=new Drug();
$query=$drug->AddStock($drug_id,$batch_code,$qty_stocked,$stocking_date,$qty_sold,$qty_rem,$expiry_date,$timestamp);
echo $query;
?>
