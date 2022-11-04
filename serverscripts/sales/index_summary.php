<?php
require_once('../dbcon.php');

$date=date('Y-m-d');
$sales_sum=mysqli_query($db,"SELECT SUM(amount_paid) as total_sales FROM cart_transactions WHERE date='".$date."'") or die('failed');
$sales_sum=mysqli_fetch_assoc($sales_sum);
$sales_sum=number_format($sales_sum['total_sales'],2);


$expenditure_sum=mysqli_query($db,"SELECT SUM(expenditure_amount) as total_expenditure FROM expenditure WHERE date='".$date."'") or die('failed');
$expenditure_sum=mysqli_fetch_assoc($expenditure_sum);
$expenditure_sum=number_format($expenditure_sum['total_expenditure'],2);


$banking_sum=mysqli_query($db,"SELECT SUM(deposit_amount) as total_banking FROM banking WHERE date='".$date."'") or die('failed');
$banking_sum=mysqli_fetch_assoc($banking_sum);
$banking_sum=number_format($banking_sum['total_banking'],2);





$array=array('total_sales'=>$sales_sum,'total_expenditure' =>$expenditure_sum,'total_banking' => $banking_sum);
echo json_encode($array);

?>
