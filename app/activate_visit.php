<?php
session_start();

require_once ('../serverscripts/Classes/SeaGate.php');

$seagate=new SeaGate();

$patient_id = $seagate->Clean($_GET['patient_id']);
$visit_id = $seagate->Clean($_GET['visit_id']);

unset($_SESSION['activePatientID']);
unset($_SESSION['activeVisitID']);

$_SESSION['activePatientID']=$patient_id;
$_SESSION['activeVisitID']=$visit_id;

header('location:singlevisit.php');



?>