<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Tests.php';


  $sn=clean_string($_GET['unique_id']);
  $result_value=clean_string($_GET['result_value']);


    $test=new Test();

    $query=$test->ResultsEntry($sn,$result_value);
    echo $query;

 ?>
