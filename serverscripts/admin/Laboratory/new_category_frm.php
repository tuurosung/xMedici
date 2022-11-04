<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';


    $category_name=clean_string($_GET['category_name']);
    $date=date('Y-m-d');


    $test=new Test();

    reject_empty($category_name);

    $query=$test->CreateCategory($category_name);
    echo $query;

 ?>
