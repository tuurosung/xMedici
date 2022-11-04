<?php
require_once '../dbcon.php';
$invoice_id=clean_string($_GET['invoice_id']);
$customer_id=clean_string($_GET['customer_id']);
$purchase_order_number=clean_string($_GET['purchase_order_number']);
$date_issued=clean_string($_GET['date_issued']);
$due_date=clean_string($_GET['due_date']);
$vat_status=clean_string($_GET['vat_status']);
$vat_percent=clean_string($_GET['vat_percent']);
// $account_id=clean_string($_GET['account_id']);

$check_exists=mysqli_query($db,"SELECT * FROM invoices WHERE invoice_id='".$invoice_id."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
if(mysqli_num_rows($check_exists)!=0){
  die("Invoice Exists");
}
else {
  $insert_string=mysqli_query($db,"INSERT INTO invoices
                                    (subscriber_id,invoice_id,customer_id,purchase_order_number,date_issued,
                                      due_date,vat_status,vat_percent,status)
                                    VALUES
                                    ('$active_subscriber','$invoice_id','$customer_id','$purchase_order_number','$date_issued',
                                      '$due_date','$vat_status','$vat_percent','active')
                                  ") or die(mysqli_error($db));


                if($insert_string){
                  echo 'save_successful';
                }
                else {
                  echo 'failed';
                }
}


?>
