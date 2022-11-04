<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    // require_once '../../Classes/Billing.php';

    $opd=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $drug_id=clean_string($_GET['drug_id']);
    $dosage=clean_string($_GET['dosage']);
    $duration=clean_string($_GET['duration']);
    $route=clean_string($_GET['route']);
    $frequency=clean_string($_GET['frequency']);
    $notes=clean_string($_GET['doctors_notes']);

    // Add Prescription
    $query=$opd->AddPrescription($patient_id,$visit_id,$drug_id,$dosage,$duration,$route,$frequency,$notes);

    echo $query;
