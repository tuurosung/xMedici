<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $opd=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $diagnosis_id=clean_string($_GET['diagnosis_id']);

    $query=$opd-> RecordDiagnosis($patient_id,$visit_id,$diagnosis_id);

    echo $query;
