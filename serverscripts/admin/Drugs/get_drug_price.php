<?php
require_once '../../dbcon.php';
require_once '../../Classes/Drugs.php';


$drug_id=clean_string($_GET['drug_id']);

reject_empty($drug_id);

$drug=new Drug();
$drug->drug_id=$drug_id;
$drug->DrugInfo();
echo $drug->retail_price;
 ?>
