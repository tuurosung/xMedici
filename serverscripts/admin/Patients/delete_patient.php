<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Patient.php';

    $p=new Patient();

    $patient_id=clean_string($_GET['patient_id']);


    reject_empty($patient_id);

    $p->patient_id=$patient_id;

    $query=$p->DeletePatient();

    echo $query;

 ?>
