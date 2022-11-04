<?php
require_once '../../dbcon.php';
require_once '../../Classes/Drugs.php';


$manufacturer_name=$_GET['manufacturer_name'];
$address=$_GET['address'];


clean_string($manufacturer_name);
clean_string($address);

$drug=new Drug();
$query=$drug->CreateManufacturer($manufacturer_name,$address);

echo $query;

?>
