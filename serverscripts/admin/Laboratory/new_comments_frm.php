<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';


    $test_id=clean_string($_GET['test_id']);
    $request_id=clean_string($_GET['request_id']);
    $comment=clean_string($_GET['comment']);
    $scientist=clean_string($_GET['scientist']);

    reject_empty($test_id);
    reject_empty($request_id);
    reject_empty($comment);
    reject_empty($scientist);


    $test=new Test();

    $query=$test->AddTestComments($test_id,$request_id,$comment,$scientist);
    echo $query;

 ?>
