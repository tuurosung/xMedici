<?php

require_once '../../dbcon.php';
require_once '../../Classes/Expenditure.php';

$expenditure_id=clean_string($_GET['expenditure_id']);

$e=new Expenditure();
$e->expenditure_id=$expenditure_id;

$query=$e->DeleteExpenditure();
echo $query;
 ?>
