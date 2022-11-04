<?php
require_once('../dbcon.php');

$account_id=$_GET['account_id'];
$account_name=$_GET['account_name'];
$opening_balance=$_GET['opening_balance'];
$date_created=date('Y-m-d');

$create_query=mysqli_query($db,"INSERT INTO accounts
                                (account_id,account_name,opening_balance,actual_balance,date_created)
                                VALUES
                                ('$account_id','$account_name','$opening_balance','$opening_balance','$date_created')
                                ") or die('failed1');


if($create_query){
  echo 'success';
}
else {
  echo 'failed';
}
 ?>
