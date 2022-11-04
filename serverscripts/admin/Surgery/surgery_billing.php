<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Surgeries.php';
    // require_once '../../Classes/Billing.php';

    $surg=new Surgery();

    $surgery_id=clean_string($_GET['surgery_id']);
    $description=clean_string($_GET['description']);
    $unit_cost=clean_string($_GET['unit_cost']);
    $qty=clean_string($_GET['qty']);
    $total=clean_string($_GET['total']);



    // Create Surgery
    $query=$surg->SurgeryBilling($surgery_id,$description,$unit_cost,$qty,$total);

    echo $query;
