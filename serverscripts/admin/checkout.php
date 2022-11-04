<?php
  require_once '../dbcon.php';
  // session_start();

  $transaction_id=transaction_idgen();

  $_SESSION['print_this_transaction']=$transaction_id;

  $cart_total=clean_string($_GET['cart_total']);
  $amount_paid=clean_string($_GET['amount_paid']);
  $balance=clean_string($_GET['balance']);
  $timestamp=time();
  $cart_sum=cart_sum($transaction_id);
  $account_id='0001';
  $asset_account=clean_string($_GET['asset_account']);
  $income_account=clean_string($_GET['income_account']);

  reject_empty($transaction_id);
  reject_empty($cart_total);
  reject_empty($amount_paid);
  reject_empty($balance);
  reject_empty($cart_sum);
  reject_empty($asset_account);
  reject_empty($income_account);

  if($amount_paid > $cart_sum){
    $amount_paid=$cart_sum;
    $balance=0;
    $payment_status='PAID';
  }
  elseif ($amount_paid==$cart_sum) {
    $payment_status='PAID';
  }
  elseif ($amount_paid < $cart_sum) {
    $payment_status='PENDING';
  }

  $checkstring=mysqli_query($db,"SELECT * FROM sales WHERE transaction_id='".$transaction_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  if(mysqli_num_rows($checkstring) >0){
    die("Transaction Recorded Already");
  }
  else {
    $table='sales';
    $fields=array("subscriber_id","transaction_id","cart_sum","amount_paid","balance","date","timestamp","account_id","status","attendant");
    $values=array("$active_subscriber","$transaction_id","$cart_sum","$amount_paid","$balance","$today","$timestamp","$income_account","active","$user_id");
    $query=insert_data($db,$table,$fields,$values);

    if($query){
      $cash_info=account_info($asset_account);
      $cash_balance=$cash_info['balance']+$cart_sum;

      $sales_info=account_info($income_account);
      $sales_balance=$sales_info['balance']+$cart_sum;

      // Debit Cash*************************************
      $table='ledger_transactions';
      $fields=array("subscriber_id","transaction_id","account_number","type","date","debit","balance","description","reference");
      $values=array("$active_subscriber","$order_id","$asset_account","deposit","$today","$amount_paid","$cash_balance","Retail Cash Sale","$transaction_id");
      $query=insert_data($db,$table,$fields,$values);
      debit_account($cart_sum,$asset_account,'increasebalance');

      // Credit Sales*************************************
      $table='ledger_transactions';
      $fields=array("subscriber_id","transaction_id","account_number","type","date","credit","balance","description","reference");
      $values=array("$active_subscriber","$order_id","$income_account","deposit","$today","$amount_paid","$sales_balance","Cash Sale","$transaction_id");
      $query=insert_data($db,$table,$fields,$values);
      credit_account($cart_sum,$income_account,'increasebalance');

      $get_cart=mysqli_query($db,"SELECT * FROM cart WHERE transaction_id='".$transaction_id."' && subscriber_id='".$active_subscriber."' && status='active'") or die(mysqli_error($db));
      while ($cart=mysqli_fetch_array($get_cart)) {
        $qty=$cart['qty'];
        $drug_id=$cart['drug_id'];
        $batch_code=$cart['batch_code'];
        $update_drug=mysqli_query($db,"UPDATE inventory SET
              stock_sold=stock_sold+$qty,
              remaining_stock=remaining_stock-$qty
              WHERE
              drug_id='".$drug_id."' && subscriber_id='".$active_subscriber."'
        ") or die(mysqli_error($db));
        $deduct_qty=mysqli_query($db,"UPDATE stock SET qty_sold=qty_sold+$qty,qty_rem=qty_rem-$qty WHERE drug_id='".$drug_id."' && batch_code='".$batch_code."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
      }

      activity_log("Cash Sale of $cart_sum, Transaction $transaction_id");
      echo 'checkout_successful';
    }

  }








 ?>
