<?php
    require_once '../dbcon.php';

    $transaction_id=clean_string($_GET['transaction_id']);
    $_SESSION['active_transaction_id']=$transaction_id;
    $drug_id=clean_string($_GET['drug_id']);
    $batch_code=clean_string($_GET['batch_code']);
    $selling_price=clean_string($_GET['selling_price']);
    $qty=clean_string($_GET['qty']);
    $total=clean_string($_GET['total']);

    reject_empty($transaction_id);
    reject_empty($active_subscriber);
    reject_empty($drug_id);
    reject_empty($batch_code);
    reject_empty($selling_price);
    reject_empty($qty);
    reject_empty($total);

    $check_exists=mysqli_query($db,"SELECT * FROM cart WHERE transaction_id='".$transaction_id."' AND drug_id='".$drug_id."' AND batch_code='".$batch_code."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(msyqli_error($db));
    if(mysqli_num_rows($check_exists) ==1){
      $update_string=mysqli_query($db,"UPDATE cart
                                                              SET qty=qty+$qty, total=total+$total
                                                              WHERE transaction_id='".$transaction_id."'
                                                                            AND
                                                                            drug_id='".$drug_id."'
                                                                            AND
                                                                            batch_code='".$batch_code."'
                                                                            AND
                                                                            subscriber_id='".$active_subscriber."'
                                                                            AND
                                                                            status='active'
                                                    ") or die(mysqli_error($db));
          echo 'save_successful';
    }
    else {
      $table='cart';
      $fields=array("subscriber_id","transaction_id","drug_id","batch_code","cost","qty","total","status");
      $values=array("$active_subscriber","$transaction_id","$drug_id","$batch_code","$selling_price","$qty","$total","active");
      $query=insert_data($db,$table,$fields,$values);

      echo $query;
    }





 ?>
