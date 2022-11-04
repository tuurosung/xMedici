<?php
require_once '../dbcon.php';
$month=date('m');
$select=mysqli_query($db,"SELECT SUM(purchase_cost) as purchase_amount FROM invoices WHERE MONTH(purchase_date)='".$month."'") or die('failed');

$purchase_amount=mysqli_fetch_assoc($select);

echo 'GH&cent '. number_format($purchase_amount['purchase_amount'],2);


?>
