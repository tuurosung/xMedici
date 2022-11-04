<?php
require_once '../../dbcon.php';
require_once '../../Classes/Drugs.php';
require_once '../../Classes/Pharmacy.php';

$drug_id=clean_string($_GET['drug_id']);
$retail_price=clean_string($_GET['retail_price']);
$qty=clean_string($_GET['qty']);
$total=clean_string($_GET['total']);
$timestamp=time();

reject_empty($drug_id);
reject_empty($retail_price);
reject_empty($qty);
reject_empty($total);

$drug=new Drug();
$drug->drug_id=$drug_id;
$drug->DrugInfo();

if($drug->qty_rem < $qty){
  echo "Selected drug has insufficient quantity";
}else {
  $pharmacy=new Pharmacy();
  $query=$pharmacy->AddToWalkInCart($drug_id,$retail_price,$qty,$total);
  echo $query;
}


?>
