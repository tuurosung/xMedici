<?php
require_once '../../dbcon.php';
require_once '../../Classes/Drugs.php';


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
$query=$drug->CreateDrug($unit,$generic_name,$trade_name,$drug_category,$manufacturer,$shelf,$restock_level,$cost_price,$retail_price,$date);
echo $query;

// check_exists('inventory','drug_name="'.$drug_name.'" && subscriber_id="'.$active_subscriber.'"');

// $check_exists=mysqli_query($db,"SELECT * FROM inventory WHERE (drug_id='".$drug_id."' AND subscriber_id='".$active_subscriber."') OR (drug_name='".$drug_name."' AND subscriber_id='".$active_subscriber."')") or die(msyqli_error($db));
// if(mysqli_num_rows($check_exists) > 0){
//   die("Similar Information Exists");
// }
// else {
//   $table='inventory';
//   $fields=array("subscriber_id","drug_id","unit","drug_name","category","manufacturer","shelf","restock_level","cost_price","wholesale_price","retail_price","date","status");
//   $values=array("$active_subscriber","$drug_id","$unit","$drug_name","$drug_category","$manufacturer","$shelf","$restock_level","$cost_price","$wholesale_price","$retail_price","$date","active");
//   $query=insert_data($db,$table,$fields,$values);
//
//   activity_log("New Drug Added - $drug_name");
//
//   echo $query;
// }



?>
