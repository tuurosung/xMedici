<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Patients.php';

    $p=new Patient();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $diagnosis_id=clean_string($_GET['diagnosis_id']);

    $query=$p-> RecordDiagnosis($patient_id,$visit_id,$diagnosis_id);

    echo $query;
