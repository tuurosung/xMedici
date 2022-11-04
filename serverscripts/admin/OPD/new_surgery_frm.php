<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Surgeries.php';
    // require_once '../../Classes/Billing.php';

    $surg=new Surgery();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);

    $surgical_procedure=clean_string($_GET['surgical_procedure']);
    $procedure_type=clean_string($_GET['procedure_type']);
    $date=clean_string($_GET['date']);

    // Create Surgery
    $query=$surg->CreateSurgery($patient_id,$visit_id,$surgical_procedure,$procedure_type,$date);

    echo $query;
