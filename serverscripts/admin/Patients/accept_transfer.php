<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Patients.php';

    $p=new Patient();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $serial=clean_string($_GET['serial']);
    $transferred_to=clean_string($_GET['transferred_to']);

    $query=$p->AcceptTransfer($patient_id,$visit_id,$serial,$transferred_to);

    echo $query;
