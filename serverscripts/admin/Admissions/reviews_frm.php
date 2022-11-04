<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Admissions.php';

    $admission=new Admission();


    $admission_id=clean_string($_GET['admission_id']);
    $review_notes=$_GET['review_notes'];
    $time=date('Y-m-d H:i:s');

    reject_empty($review_notes);
    reject_empty($admission_id);

    $query=$admission->SaveReview($admission_id,$review_notes,$time);

    echo $query;

 ?>
