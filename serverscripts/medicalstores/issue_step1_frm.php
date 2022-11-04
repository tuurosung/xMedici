<?php
require_once '../dbcon.php';

$issue_id=$_GET['issue_id'];
$unit=$_GET['unit'];
$issue_date=$_GET['issue_date'];
$receiver=$_GET['receiver'];


$save_string=mysqli_query($db,"INSERT INTO stores_issues
                                (issue_id,unit,date,receiver)
                                VALUES
                                ('$issue_id','$unit','$issue_date','$receiver')
                          ") or die('failed');


if($save_string){
  session_start();
  $_SESSION['active_issue_id']=$issue_id;
  echo 'save_successful';
}
else {
  echo 'failed';
}

?>
