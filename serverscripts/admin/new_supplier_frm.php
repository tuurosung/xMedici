<?php
require_once '../dbcon.php';

$supplier_name=$_GET['supplier_name'];
$phone_number=$_GET['phone_number'];
$location=$_GET['location'];
$date=date('Y-m-d');

$s=new Supplier();

reject_empty($supplier_name);
reject_empty($phone_number);
reject_empty($location);
reject_empty($date);

$query=$s->CreateSupplier($supplier_name,$phone_number,$location,$date);

echo $query;
?>
