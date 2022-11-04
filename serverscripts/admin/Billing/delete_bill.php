<?php

session_start();
require_once '../../dbcon.php';
require_once '../../Classes/Payments.php';
require_once '../../Classes/OPD.php';


$bill_id=clean_string($_GET['bill_id']);

$billing=new Billing();


$query=$billing->DeleteBill($bill_id);
echo $query;
 ?>
