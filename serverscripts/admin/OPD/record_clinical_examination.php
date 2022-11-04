<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $opd=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $clinical_examination=$_GET['clinical_examination'];

    $query=$opd-> RecordCE($patient_id,$visit_id,$clinical_examination);

    echo $query;
