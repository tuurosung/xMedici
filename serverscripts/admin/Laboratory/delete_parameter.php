<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';

    $parameter_id=clean_string($_GET['parameter_id']);


    $test=new Test();

    $query=$test->DeleteParameter($parameter_id);
    echo $query;

 ?>
