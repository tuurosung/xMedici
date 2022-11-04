<?php
  require_once '../dbcon.php';

  $account_number=new_account_idgen();
  $account_header=clean_string($_GET['account_header']);
  $account_name=clean_string($_GET['account_name']);
  $description=clean_string($_GET['description']);
  $date_created=date('Y-m-d');

  $table='all_accounts';
  $fields=array("subscriber_id","account_number","account_name","account_header","description","date_created");
  $values=array("$active_subscriber","$account_number","$account_name","$account_header","$description","$date_created");
  $query=insert_data($db,$table,$fields,$values);

  if($query){
    echo 'save_successful';
  }
  else {
    echo 'failed';
  }


 ?>
