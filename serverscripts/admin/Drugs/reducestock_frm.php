<?php
require_once '../../dbcon.php';
require_once '../../Classes/Drugs.php';

$drug_id=$_GET['drug_id'];
$date=$_GET['date'];
$qty=$_GET['qty'];
$narration=$_GET['narration'];
$timestamp=time();

reject_empty($drug_id);
reject_empty($date);
reject_empty($qty);

$drug=new Drug();
$query=$drug->ReduceStock($drug_id,$qty,$narration,$date,$timestamp);
echo $query;
?>
