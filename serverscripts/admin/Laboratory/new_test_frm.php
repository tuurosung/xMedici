<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';


    $description=clean_string($_GET['description']);
    $test_category=clean_string($_GET['test_category']);
    $test_cost=clean_string($_GET['test_cost']);
    $specimen=clean_string($_GET['specimen']);
    $comment=clean_string($_GET['comment']);
    $date=date('Y-m-d');


    $test=new Test();

    $query=$test->CreateTest($description,$test_category,$test_cost,$specimen,$comment,$date);
    echo $query;

 ?>
