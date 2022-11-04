<?php
  require_once '../../dbcon.php';
  require_once '../../Classes/Expenditure.php';

  $e=new Expenditure();

  $expenditure_id=clean_string($_GET['expenditure_id']);
  $expenditure_account=clean_string($_GET['expenditure_account']);
  $payment_account=clean_string($_GET['payment_account']);
  $description=clean_string($_GET['description']);
  $amount=clean_string($_GET['amount']);
  $date=clean_string($_GET['expenditure_date']);
  $timestamp=time();

  reject_empty($expenditure_id);
  reject_empty($expenditure_account);
  reject_empty($description);
  reject_empty($amount);
  reject_empty($payment_account);
  reject_empty($date);


  $query=$e->CreateExpenditure($expenditure_id,$description,$amount,$expenditure_account,$payment_account,$notes,$date,$timestamp);

  echo $query;

 ?>
