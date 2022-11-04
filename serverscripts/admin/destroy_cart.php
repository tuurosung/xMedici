<?php
  require_once '../dbcon.php';
  session_start();

  if(!isset($_SESSION['active_transaction_id'])){
    die('Cart empty');
  }

  $transaction_id=$_SESSION['active_transaction_id'];

  $delete_string=mysqli_query($db,"UPDATE cart SET status='deleted' WHERE transaction_id='".$transaction_id."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));

  if($delete_string){
    echo 'delete_successful';
  }
  else {
    echo 'failed';
  }


 ?>
