<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';

    $test_id=clean_string($_GET['test_id']);


    $test=new Test();

    $query=$test->DeleteTest($test_id);
    echo $query;

 ?>
