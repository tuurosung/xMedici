<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Admissions.php';

    $admission=new Admission();


    $admission_id=clean_string($_GET['admission_id']);
    $drug_id=clean_string($_GET['serve_meds_drug_id']);
    $time=date('Y-m-d H:i:s');

    reject_empty($drug_id);
    reject_empty($admission_id);

    $query=$admission->ServeMeds($admission_id,$drug_id,$time);

    echo $query;

 ?>
