<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Payments.php';
    require_once '../../Classes/OPD.php';

    $pmnt=new Payment();
    $visit=new Visit();

    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);
    $bill_id=clean_string($_GET['bill_id']);
    $amount_payable=clean_string($_GET['amount_payable']);
    $amount_paid=clean_string($_GET['amount_paid']);
    $balance=clean_string($_GET['balance']);
    $income_header=clean_string($_GET['income_account']);
    $payment_account=clean_string($_GET['payment_account']);


    reject_empty($patient_id);
    reject_empty($visit_id);
    reject_empty($bill_id);
    reject_empty($amount_payable);
    reject_empty($amount_paid);
    reject_empty($balance);

    if($amount_paid > $amount_payable){
      echo 'The amount you entered is more than the bill';
    }elseif ($amount_paid==0) {
      echo 'Invalid amount entered';
    }else {
      $query=$pmnt->NewPayment($patient_id,$visit_id,$bill_id,$amount_payable,$amount_paid,$balance,$date,$income_header,$payment_account);
      $visit->VisitLog($patient_id,$visit_id,'Payment of "'.$amount_paid.'" made to Cashier');
      echo $query;
    }



 ?>
