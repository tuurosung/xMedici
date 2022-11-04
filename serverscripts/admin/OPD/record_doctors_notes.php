<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';

    $visit=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $doctors_notes=$_GET['doctors_notes'];

    $query=$visit-> RecordDoctorsNotes($patient_id,$visit_id,$doctors_notes);

    echo $query;
