<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Surgeries.php';
    // require_once '../../Classes/Billing.php';

    $surg=new Surgery();

    $surgery_id=clean_string($_GET['surgery_id']);
    $surgery_report=$_GET['surgery_report'];



    // Create Surgery
    $query=$surg->SurgeryReport($surgery_id,$surgery_report);

    echo $query;
