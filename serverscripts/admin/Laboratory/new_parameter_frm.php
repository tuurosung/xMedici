<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';


    $test_id=clean_string($_GET['test_id']);
    $description=clean_string($_GET['description']);
    $unit=clean_string($_GET['unit']);
    $general_min=clean_string($_GET['general_min']);
    $general_max=clean_string($_GET['general_max']);
    $male_min=clean_string($_GET['male_min']);
    $male_max=clean_string($_GET['male_max']);
    $female_min=clean_string($_GET['female_min']);
    $female_max=clean_string($_GET['female_max']);
    $child_min=clean_string($_GET['child_min']);
    $child_max=clean_string($_GET['child_max']);


    $test=new Test();

    $query=$test->AddParameter($test_id,$description,$unit,$general_min,$general_max,$male_min,$male_max,$female_min,$female_max,$child_min,$child_max);
    echo $query;

 ?>
