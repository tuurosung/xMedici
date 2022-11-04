<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $opd=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $question=clean_string($_GET['question']);
    $response=clean_string($_GET['response']);

    $query=$opd-> RecordODQ($patient_id,$visit_id,$question,$response);

    echo $query;
