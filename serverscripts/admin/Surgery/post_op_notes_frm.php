<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Surgeries.php';
    // require_once '../../Classes/Billing.php';

    $surg=new Surgery();

    $surgery_id=clean_string($_GET['surgery_id']);
    $post_op_notes=$_GET['post_op_notes'];



    // Create Surgery
    $query=$surg->PostOpNotes($surgery_id,$post_op_notes);

    echo $query;
