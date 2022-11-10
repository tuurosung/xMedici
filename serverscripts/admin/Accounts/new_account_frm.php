<?php
  require_once '../../dbcon.php';
  require_once '../../Classes/Accounts.php';

  $acc=new Account();

  $account_header=clean_string($_GET['account_header']);
  $account_name=clean_string($_GET['account_name']);
  $description=clean_string($_GET['description']);

  echo $acc->NewAccount($account_name,$account_header,$description);
  

 ?>
