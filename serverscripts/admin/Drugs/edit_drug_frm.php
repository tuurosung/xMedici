<?php
require_once '../../dbcon.php';
require_once '../../Classes/Drugs.php';


$drug_id=clean_string($_GET['drug_id']);
$unit=clean_string($_GET['unit']);
$generic_name=clean_string($_GET['generic_name']);
$trade_name=clean_string($_GET['trade_name']);
$drug_category=$_GET['drug_category'];
$manufacturer=$_GET['manufacturer'];
$restock_level=$_GET['restock_level'];
$cost_price=$_GET['cost_price'];
$retail_price=$_GET['retail_price'];
$shelf=$_GET['shelf'];
$date=date('Y-m-d');

reject_empty($active_subscriber);
reject_empty($generic_name);
reject_empty($trade_name);
reject_empty($manufacturer);
reject_empty($restock_level);
reject_empty($cost_price);
reject_empty($retail_price);

$drug=new Drug();
$query=$drug->EditDrug($drug_id,$unit,$generic_name,$trade_name,$drug_category,$manufacturer,$shelf,$restock_level,$cost_price,$retail_price,$date);
echo $query;
 ?>
