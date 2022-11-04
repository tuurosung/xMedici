<?php
  // session_start();
  require_once '../dbcon.php';

  $invoice_id=clean_string($_GET['invoice_id']);
  $customer_id=clean_string($_GET['customer_id']);
  $payment_id=payment_idgen();
  $amount_paid=clean_string($_GET['amount_paid']);
  $payment_date=clean_string($_GET['payment_date']);
  $cheque_number=clean_string($_GET['cheque_number']);
  $payment_account=clean_string($_GET['payment_account']);
  $receivables_account=clean_string($_GET['receivables_account']);
  $payment_date=clean_string($_GET['payment_date']);
  $timestamp=time();

  reject_empty($invoice_id);
  reject_empty($customer_id);
  reject_empty($amount_paid);
  reject_empty($payment_account);
  reject_empty($receivables_account);
  reject_empty($payment_date);

  if($amount_paid <0){
    die("Negative Payments Cannot Be Accepted");
  }

  $check_exists=mysqli_query($db,"SELECT * FROM invoices WHERE invoice_id='".$invoice_id."'  && subscriber_id='".$active_subscriber."'") or die('failed');
  if(mysqli_num_rows($check_exists) == 0){
    die('Invoice Not Found');
  }
  else {
    $table='invoice_payments';
    $fields=array("subscriber_id","payment_id","customer_id","invoice_id","amount_paid","payment_account","timestamp","date","status");
    $values=array("$active_subscriber","$payment_id","$customer_id","$invoice_id","$amount_paid","$payment_account","$timestamp","$payment_date","active");
    $insert_query=insert_data($db,$table,$fields,$values);

    $update_query=mysqli_query($db,"UPDATE invoices SET amount_paid=amount_paid+$amount_paid,balance_remaining=balance_remaining-$amount_paid WHERE invoice_id='".$invoice_id."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));

    $invoice_info=invoice_info($invoice_id);

    if($invoice_info['balance_remaining']==0){
      $update_query=mysqli_query($db,"UPDATE invoices SET payment_status='paid' WHERE invoice_id='".$invoice_id."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    }

    $credit_customer=mysqli_query($db,"UPDATE customers SET credit=credit+$amount_paid,balance=balance+$amount_paid WHERE customer_id='".$customer_id."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));



    $receivables_account_info=account_info($receivables_account);
    $receivables_account_balance=$receivables_account_info['balance']-$amount_paid;

    $payment_account_info=account_info($account_number);
    $payment_account_balance=$payment_account_info['balance']+$amount_paid;

    // Debit Payment Account*************************************
    $table='ledger_transactions';
    $fields=array("subscriber_id","transaction_id","account_number","type","date","debit","balance","description","reference");
    $values=array("$active_subscriber","$payment_id","$payment_account","withdrawal","$today","$amount_paid","$payment_account_balance","Invoice Payment","$payment_id");
    $query=insert_data($db,$table,$fields,$values);
    debit_account($amount_paid,$payment_account,'increasebalance');

    // Credit Receivables*************************************
    $table='ledger_transactions';
    $fields=array("subscriber_id","transaction_id","account_number","type","date","credit","balance","description","reference");
    $values=array("$active_subscriber","$payment_id","$receivables_account","withdrawal","$today","$amount_paid","$receivables_account_balance","Invoice Payment","$payment_id");
    $query=insert_data($db,$table,$fields,$values);
    credit_account($amount_paid,$receivables_account,'decreasebalance');

    echo 'save_successful';
  }
