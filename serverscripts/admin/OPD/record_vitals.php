<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $opd=new Visit();


    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $systolic=clean_string($_GET['systolic']);
    $diastolic=clean_string($_GET['diastolic']);
    $pulse=clean_string($_GET['pulse']);
    $temperature=clean_string($_GET['temperature']);
    $weight=clean_string($_GET['weight']);
    $doctor_id=clean_string($_GET['doctor_id']);

    reject_empty($systolic);
    reject_empty($diastolic);
    reject_empty($pulse);
    reject_empty($temperature);
    reject_empty($weight);
    reject_empty($doctor_id);
    reject_empty($patient_id);
    reject_empty($visit_id);

    $query=$opd->RecordVitals($visit_id,$patient_id,$systolic,$diastolic,$pulse,$weight,$temperature,$doctor_id);

    echo $query;

 ?>
