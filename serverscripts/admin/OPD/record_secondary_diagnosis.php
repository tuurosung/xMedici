<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $visit=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $secondary_diagnosis=$_GET['secondary_diagnosis'];

    $query=$visit-> RecordSecondaryDiagnosis($patient_id,$visit_id,$secondary_diagnosis);

    echo $query;
