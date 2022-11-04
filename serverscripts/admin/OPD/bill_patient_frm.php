<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Billing.php';

    $opd=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $reference =$_GET['service_id'];
    $bill_amount =$_GET['billing_service_cost'];
    $narration =$_GET['narration'];



    // Bill Patient
    $billing=new Billing();
    $query=$billing->BillPatient($patient_id,$visit_id,$reference,$bill_amount,$narration);

    echo $query;
