<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Admissions.php';

    $admission=new Admission();


    $admission_id=clean_string($_GET['admission_id']);
    $nurses_notes=$_GET['nurses_notes'];
    $time=date('Y-m-d H:i:s');

    reject_empty($nurses_notes);
    reject_empty($admission_id);

    $query=$admission->SaveNursesNotes($admission_id,$nurses_notes,$time);

    echo $query;

 ?>
