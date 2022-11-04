<?php

session_start();
require_once '../../dbcon.php';
require_once '../../Classes/Payments.php';


$payment_id=clean_string($_GET['payment_id']);

$payment=new Payment();


$query=$payment->DeletePayment($payment_id);
echo $query;
 ?>
