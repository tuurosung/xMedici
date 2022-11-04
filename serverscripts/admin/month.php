<?php
require_once '../dbcon.php';
$month=date('m');

$sales_sum=mysqli_query($db,"SELECT SUM(total_cost) as total_sales FROM sales WHERE MONTH(date)='".$month."'") or die('failed');
$sales_sum=mysqli_fetch_assoc($sales_sum);
$sales_sum=number_format($sales_sum['total_sales'],2);


echo $sales_sum;
?>
