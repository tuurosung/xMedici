<?php
require_once '../../dbcon.php';
require_once '../../Classes/Drugs.php';

$drug_id=clean_string($_GET['drug_id']);
$batch_code=clean_string($_GET['batch_code']);

$drug=new Drug();


echo $drug->DeleteStock($drug_id,$batch_code);





?>
