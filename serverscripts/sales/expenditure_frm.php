<?php
require_once('../dbcon.php');


$expenditure_id=$_GET['expenditure_id'];
$expenditure_header=$_GET['expenditure_header'];
$expenditure_purpose=$_GET['expenditure_purpose'];
$expenditure_amount=$_GET['expenditure_amount'];
$expenditure_account=$_GET['expenditure_account'];
$payee=$_GET['payee'];
$date=date("Y-m-d");

session_start();



$insert_string=mysqli_query($db,"INSERT INTO expenditure
                                    (expenditure_id,expenditure_header,expenditure_purpose,expenditure_amount,expenditure_account,
                                      payee,date)
                                    VALUES
                                    ('$expenditure_id','$expenditure_header','$expenditure_purpose','$expenditure_amount',
                                      '$expenditure_account','$payee','$date')
                            ") OR die('failed');

if($insert_string){

    $get_account_balance=mysqli_query($db,"SELECT * FROM accounts WHERE account_id='".$expenditure_account."'") OR die('failed');
    $get_account_balance=mysqli_fetch_row($get_account_balance);
    $account_balance=$get_account_balance[7];
    $account_balance=$account_balance - $expenditure_amount;

    $save_transaction=mysqli_query($db,"INSERT INTO transactions
                                        (transaction_id,transaction_account,transaction_type,transaction_amount,account_balance,transaction_date)
                                        VALUES
                                        ('$expenditure_id','$expenditure_account','EXPENDITURE','$expenditure_amount','$account_balance','$date')
                                        ") or die('failed');

    $update_accounts=mysqli_query($db,"UPDATE accounts SET actual_balance=actual_balance-$expenditure_amount,total_expenditure=total_expenditure+$expenditure_amount WHERE account_id='".$expenditure_account."'") OR die('failed');


}
else {
  echo 'failed';
}



if($update_accounts){
  echo 'save_successful';
}
else {
  echo 'failed';
}




?>
