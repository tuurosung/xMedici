<?php
require_once '../../dbcon.php';
require_once '../../Classes/Drugs.php';
require_once '../../Classes/Pharmacy.php';
require_once '../../Classes/Billing.php';

$transaction_id=clean_string($_GET['transaction_id']);
$total_cost=clean_string($_GET['total_amount']);
$timestamp=time();

reject_empty($transaction_id);
reject_empty($total_cost);


$pharmacy=new Pharmacy();

$query=$pharmacy->CheckoutCart($transaction_id,$total_cost,$timestamp);

if($query=='save_successful'){
  $update_cart=mysqli_query($db,"UPDATE pharm_walkin_cart SET checkout_status='CHECKOUT' WHERE  transaction_id='".$transaction_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $billing=new Billing();
  $query=$billing->BillPatient('Walkin','Walkin',$transaction_id,$total_cost,'Cost Of Drugs Sold At Walkin-Pharmcy');
  echo 'save_successful';
}


?>
