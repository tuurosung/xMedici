<?php
require_once '../dbcon.php';
$issue_id=$_GET['issue_id'];
$issue_id=mysqli_query($db,"DELETE FROM stores_issues WHERE issue_id='".$issue_id."'") or die('failed');

if($issue_id){
  echo 'delete_successful';
}
else {
  echo 'failed';
}

?>
