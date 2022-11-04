<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Patients.php';

    $p=new Patient();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $department_id=clean_string($_GET['department_id']);

    $query=$p->PatientTransfer($patient_id,$visit_id,$department_id);

    echo $query;
