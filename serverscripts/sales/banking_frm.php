<?php
require_once('../dbcon.php');


$voucher_id=$_GET['voucher_id'];
$source_account=$_GET['source_account'];
$deposit_account=$_GET['deposit_account'];
$deposit_amount=$_GET['deposit_amount'];
$depositor_name=$_GET['depositor_name'];
$date=date("Y-m-d");


$insert_string=mysqli_query($db,"INSERT INTO banking
                                  (voucher_id,source_account,deposit_account,deposit_amount,depositor_name,date)
                                  VALUES
                                  ('$voucher_id','$source_account','$deposit_account','$deposit_amount','$depositor_name','$date')
                            ") OR die('failed11');

if($insert_string){

    $get_account_balance=mysqli_query($db,"SELECT * FROM Accounts WHERE account_id='".$deposit_account."'") OR die('failed1');
    $get_account_balance=mysqli_fetch_row($get_account_balance);
    $account_balance=$get_account_balance[7];
    $account_balance=$account_balance + $deposit_amount;

    $save_transaction=mysqli_query($db,"INSERT INTO transactions
                                        (transaction_id,transaction_account,transaction_type,transaction_amount,account_balance,transaction_date)
                                        VALUES
                                        ('$voucher_id','$deposit_account','DEPOSIT','$deposit_amount','$account_balance','$date')
                                        ") or die('failed2');

    $update_accounts=mysqli_query($db,"UPDATE Accounts SET actual_balance=actual_balance + $deposit_amount WHERE account_id='".$deposit_account."'") OR die('failed');



    $get_account_balance=mysqli_query($db,"SELECT * FROM Accounts WHERE account_id='".$source_account."'") OR die('failed3');
    $get_account_balance=mysqli_fetch_row($get_account_balance);
    $account_balance=$get_account_balance[7];
    $account_balance=$account_balance - $deposit_amount;


    $save_transaction=mysqli_query($db,"INSERT INTO transactions
                                        (transaction_id,transaction_account,transaction_type,transaction_amount,account_balance,transaction_date)
                                        VALUES
                                        ('$voucher_id','$source_account','TRANSFER','$deposit_amount','$account_balance','$date')
                                        ") or die('failed4');

    $update_accounts=mysqli_query($db,"UPDATE Accounts SET actual_balance=actual_balance - $deposit_amount WHERE account_id='".$source_account."'") OR die('failed');


}
else {
  echo 'failed5';
}



if($update_accounts){
  echo 'save_successful';
}
else {
  echo 'failed6';
}




?>
