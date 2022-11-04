<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';


    $name=clean_string($_GET['UnitName']);
    $unit=clean_string($_GET['unit']);


    $test=new Test();

    reject_empty($name);
    reject_empty($unit);

    $query=$test->CreateUnit($name,$unit);
    echo $query;

 ?>
