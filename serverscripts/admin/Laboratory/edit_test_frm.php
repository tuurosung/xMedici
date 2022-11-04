<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';

    $test_id=clean_string($_GET['test_id']);
    $description=clean_string($_GET['description']);
    $test_category=clean_string($_GET['test_category']);
    $test_cost=clean_string($_GET['test_cost']);
    $specimen=clean_string($_GET['specimen']);
    $comment=clean_string($_GET['comment']);
    $date=date('Y-m-d');

    reject_empty($description);
    reject_empty($test_category);
    reject_empty($test_cost);
    reject_empty($specimen);

    $test=new Test();

    $query=$test->UpdateTest($test_id,$description,$test_category,$test_cost,$specimen,$comment);
    echo $query;

 ?>
