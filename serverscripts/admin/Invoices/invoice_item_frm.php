<?php
require_once '../../dbcon.php';
$invoice_id=clean_string($_GET['invoice_id']);
$description=clean_string($_GET['description']);
$unit_cost=clean_string($_GET['unit_cost']);
$qty=clean_string($_GET['qty']);
$total=clean_string($_GET['total']);

$check_exists=mysqli_query($db,"SELECT * FROM invoice_items WHERE invoice_id='".$invoice_id."' AND description='".$description."'  AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
if(mysqli_num_rows($check_exists)!=0){
  die("Item Added Already");
}
else {
  $insert_string=mysqli_query($db,"INSERT INTO invoice_items
                                    (subscriber_id,invoice_id,description,unit_cost,qty,total,status)
                                    VALUES
                                    ('$active_subscriber','$invoice_id','$description','$unit_cost','$qty','$total','active')
                                  ") or die(mysqli_error($db));


                if($insert_string){
                  $update_query=mysqli_query($db,"UPDATE invoices
                                                                          SET
                                                                          sub_total=sub_total+$total,
                                                                          vat_amount=vat_amount+((vat_percent/100)*$total),
                                                                          nhil_amount=nhil_amount+((nhil_percent/100)*$total),
                                                                          getfund_amount=getfund_amount+((getfund_amount/100)*$total),
                                                                          total=sub_total+vat_amount+nhil_amount+getfund_amount
                                                                          WHERE
                                                                          invoice_id='".$invoice_id."' && subscriber_id='".$active_subscriber."'
                                                              ") or die(msyqli_error($db));
                  echo 'save_successful';
                }
                else {
                  echo 'failed';
                }
}


?>
