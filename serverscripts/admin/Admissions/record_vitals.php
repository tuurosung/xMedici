<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Admissions.php';

    $admission=new Admission();


    $admission_id=clean_string($_GET['admission_id']);
    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $systolic=clean_string($_GET['systolic']);
    $diastolic=clean_string($_GET['diastolic']);
    $pulse=clean_string($_GET['pulse']);
    $temperature=clean_string($_GET['temperature']);
    $weight=clean_string($_GET['weight']);
    $time=date('Y-m-d H:i:s');

    reject_empty($systolic);
    reject_empty($diastolic);
    reject_empty($pulse);
    reject_empty($temperature);
    reject_empty($weight);
    reject_empty($admission_id);

    $query=$admission->RecordVitals($admission_id,$systolic,$diastolic,$pulse,$weight,$temperature,$time);

    echo $query;

 ?>
