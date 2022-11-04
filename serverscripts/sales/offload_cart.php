<?php
require_once('../dbcon.php');

$amount_payable=$_GET['amount_payable'];
$amount_paid=$_GET['amount_paid'];
$balance_remaining=$_GET['balance_remaining'];
// $customer_name=$_GET['customer_name'];
// $phone_number=$_GET['phone_number'];

$date=date("Y-m-d");

session_start();
$attendant=$_SESSION['active_user'];

if(!isset($_SESSION['tracksales_transid'])){
  $_SESSION['tracksales_transid']=time();
  $tracksales_transid=$_SESSION['tracksales_transid'];
}
else {
  $tracksales_transid=$_SESSION['tracksales_transid'];
}




$save_cart=mysqli_query($db,"INSERT INTO cart_transactions
                             (transaction_id,transaction_cost,amount_paid,balance_remaining,date,attendant)
                             VALUES
                             ('$tracksales_transid','$amount_payable','$amount_paid','$balance_remaining','$date','$attendant')
                             ") OR die('failed');

if($save_cart){
  if($amount_paid > $amount_payable || $amount_paid==$amount_payable){
    $transaction_amount=$amount_payable;
  }
  else
  if($amount_paid < $amount_payable)
  {
    $transaction_amount=$amount_paid;
  }


  $get_account_balance=mysqli_query($db,"SELECT * FROM accounts WHERE account_id='CASHACC'") OR die('failed1');
  $get_account_balance=mysqli_fetch_row($get_account_balance);
  $account_balance=$get_account_balance[7];
  $account_balance=$account_balance + $transaction_amount;

  $save_transaction=mysqli_query($db,"INSERT INTO transactions
                                      (transaction_id,transaction_account,transaction_type,transaction_amount,account_balance,transaction_date)
                                      VALUES
                                      ('$tracksales_transid','CASHACC','INCOME','$transaction_amount','$account_balance','$date')
                                      ") or die('failed');

  $update_accounts=mysqli_query($db,"UPDATE accounts SET actual_balance=actual_balance+$transaction_amount,total_income=total_income+$transaction_amount WHERE account_id='CASHACC'") OR die('failed1');

  $cart_items=mysqli_query($db,"SELECT * FROM sales WHERE transid='".$tracksales_transid."' AND status='PENDING'") OR die('failed');
  while ($cart_rows=mysqli_fetch_array($cart_items)) {
    $item_code=$cart_rows['item_code'];
    $batch_code=$cart_rows['batch_code'];
    $qty=$cart_rows['qty'];

    $deduct=mysqli_query($db,"UPDATE stock SET qty_sold=qty_sold+$qty,qty_rem=qty_rem-$qty WHERE drug_id='".$item_code."' && batch_code='".$batch_code."'") or die('failed1');
    if($deduct){
      $update_string=mysqli_query($db,"UPDATE sales SET status='SOLD' WHERE transid='".$tracksales_transid."' && item_code='".$item_code."' && batch_code='".$batch_code."'") or die('failed');
    }
  }//end while

  if($save_transaction){

    if($update_string){
      $_SESSION['print_id']=$_SESSION['tracksales_transid'];
      unset($_SESSION['tracksales_transid']);
      echo 'cart_offload_successful';
    }
    else {
      echo 'failed';
    }
  }
  else {
    echo 'failed';
  }

}


?>
