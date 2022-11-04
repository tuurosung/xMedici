<?php
require_once '../../dbcon.php';
require_once '../../Classes/Drugs.php';

$drug_id=clean_string($_GET['drug_id']);

reject_empty($active_subscriber);
reject_empty($drug_id);

$d=new Drug();
$d->drug_id=$drug_id;

$query=$d->DeleteDrug();

echo $query

?>
