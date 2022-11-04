<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';

    $category_id=clean_string($_GET['category_id']);


    $test=new Test();

    $query=$test->DeleteCategory($category_id);
    echo $query;

 ?>
