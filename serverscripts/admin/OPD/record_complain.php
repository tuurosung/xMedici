<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $opd=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $complain=clean_string($_GET['complain']);
    $complain_duration=clean_string($_GET['complain_duration']);
    $complain_status=clean_string($_GET['complain_status']);

    $query=$opd-> RecordComplain($patient_id,$visit_id,$complain,$complain_duration,$complain_status);

    echo $query;
