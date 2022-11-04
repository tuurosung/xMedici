<?php
  require_once '../dbcon.php';
  session_start();
  $sn=clean_string($_GET['sn']);

  reject_empty($sn);
  reject_empty($active_subscriber);

  $get_sale_info=mysqli_query($db,"SELECT * FROM cart WHERE sn='".$sn."' && status='active'") or die(mysqli_error($db));
  if(mysqli_num_rows($get_sale_info) ==1){
    $cart=mysqli_fetch_array($get_sale_info);

    $qty=$cart['qty'];
    $drug_id=$cart['drug_id'];
    $batch_code=$cart['batch_code'];
    $transaction_id=$cart['transaction_id'];

    $update_drug=mysqli_query($db,"UPDATE inventory SET
          stock_sold=stock_sold-$qty,
          remaining_stock=remaining_stock+$qty
          WHERE
          drug_id='".$drug_id."' && subscriber_id='".$active_subscriber."'
    ") or die(mysqli_error($db));

    $deduct_qty=mysqli_query($db,"UPDATE stock SET qty_sold=qty_sold-$qty,qty_rem=qty_rem+$qty WHERE drug_id='".$drug_id."' && batch_code='".$batch_code."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));

    $delete_string=mysqli_query($db,"UPDATE cart SET status='deleted' WHERE sn='".$sn."' && transaction_id='".$transaction_id."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));

    if($delete_string){
      echo 'delete_successful';
    }
    else {
      echo 'failed';
    }

  }





 ?>
