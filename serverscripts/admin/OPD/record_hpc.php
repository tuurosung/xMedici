<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $opd=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $hpc=$_GET['hpc'];

    $query=$opd-> RecordHPC($patient_id,$visit_id,$hpc);

    echo $query;
