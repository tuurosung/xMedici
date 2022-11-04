<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';


  $request_id=clean_string($_GET['request_id']);
  $test_id=clean_string($_GET['test_id']);


    $test=new Test();

    $query=$test->CompleteTest($request_id,$test_id);
    echo $query;

 ?>
