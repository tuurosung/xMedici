<?php

session_start();
require_once '../../dbcon.php';
require_once '../../Classes/Payments.php';
require_once '../../Classes/OPD.php';

$patient_id=clean_string($_GET['patient_id']);
$visit_id=clean_string($_GET['visit_id']);
$bill_id=clean_string($_GET['bill_id']);
$bill_amount=clean_string($_GET['bill_amount']);
$narration=clean_string($_GET['narration']);

reject_empty($patient_id);
reject_empty($visit_id);
reject_empty($bill_id);
reject_empty($bill_amount);
reject_empty($narration);

$billing=new Billing();

// $billing->bill_id=$bill_id;
$query=$billing->ModifyBilling($patient_id,$visit_id,$bill_id,$bill_amount,$narration);

 echo $query;




 ?>
