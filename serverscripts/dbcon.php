<?php
session_start();
if(!isset($_SESSION['active_subscriber'])){
  session_start();
}

$db=mysqli_connect('localhost','root','@Tsung3#','xMedici') or die("Check Connection");
$mysqli=new mysqli('localhost','root','@Tsung3#','xMedici');

$active_subscriber=$_SESSION['active_subscriber'];
$user_id=$_SESSION['active_user'];

function is_admin(){
  global $access_level;
  if($access_level !='administrator'){
    echo 'd-none';
  }
}

function is_pharm(){
  global $access_level;
  if($access_level !='pharmacist' || $access_level!='administrator'){
    echo 'd-none';
  }
}
function is_nurse(){
  global $access_level;
  if($access_level !='nurse' || $access_level!='administrator'){
    echo 'd-none';
  }
}
function is_doctor(){
  global $access_level;
  if($access_level !='doctor' || $access_level!='administrator'){
    echo 'd-none';
  }
}



function prefix($str){
  $len=strlen($str);
  switch ($len) {
    case '1':
      $prefix = '0000';
      break;
    case '2':
      $prefix = '000';
      break;
    case '3':
      $prefix = '00';
    case '4':
      $prefix = '0';
      break;
    case '5':
      $prefix = '';
      break;

    default:
      $prefix = '';
      break;
  }
  return $prefix;
}


//time and date functions +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$today=date('Y-m-d');
$week_start=date('Y-m-d', strtotime('last monday'));
$week_end=date('Y-m-d', strtotime('next sunday'));
$month_start=date('Y-m-d',strtotime('first day of this month'));
$month_end=date('Y-m-d',strtotime('last day of this month'));
$year_start=date('Y-m-d',strtotime('first day of January this year'));
$year_end=date('Y-m-d',strtotime('last day of December this year'));





function clean_string($string){
  $string=trim($string);
  $string=htmlspecialchars($string);
  // $string=mysqli_real_escape_string($string);
  // $string=filter_var($string,FILTER_SANITIZE_STRING);
  return $string;
}

function reject_empty($value){
  if(empty($value)){
    die('Mandatory Fields Empty');
  }
}//end function

// function validate_phonenumber($phone_number,$response){
//   if(mb_strlen($phone_number) != 10 ){
//     die($response);
//   }else {
//     $number_prefix=substr($phone_number,0,2);
//     if($number_prefix !="02" ||
//         $number_prefix !="05"
//       ){
//           die($number_prefix);
//       }
//   }
// }

function check_exists($table,$key){
  global $db;
  $query=mysqli_query($db,"SELECT * FROM $table WHERE $key") or die(mysqli_error($db));
  if(mysqli_num_rows($query) >0){
    $status='exists';
  }
  else {
    $status='not_exists';
  }
  return $status;
}//end function

function single_select($db,$table){
  $query=mysqli_query($db,"SELECT * FROM $table") or die('failed');
  return $query;
}

function single_select_count($db,$table){
  $query=mysqli_query($db,"SELECT COUNT(*) AS count FROM $table") or die('failed');
  $query=mysqli_fetch_assoc($query);
  $query=$query['count'];
  return $query;
}

function single_select_count_condition($db,$table,$condition){
  $query=mysqli_query($db,"SELECT COUNT(*) AS count FROM $table WHERE $condition") or die('failed');
  $query=mysqli_fetch_assoc($query);
  $query=$query['count'];
  return $query;
}

function single_select_condition($db,$table,$condition){
  $query=mysqli_query($db,"SELECT * FROM $table WHERE $condition") or die('failed');
  return $query;
}

function single_delete_condition($db,$table,$condition){
  $query=mysqli_query($db,"DELETE FROM $table WHERE $condition") or die('failed');
  if($query){
    return 'delete_successful';
  }
  else {
    return 'failed';
  }
}

function update_condition($db,$table,$values,$condition){
  $query=mysqli_query($db,"UPDATE $table SET $values WHERE $condition") or die('update_failed');
  if($query){
    return 'update_successful';
  }
  else {
    return 'failed';
  }
}


function insert_data($db,$table,$fields,$values){
  global $db;
  global $active_subscriber;
  $str=implode(",",$fields);
  $val=implode("','",$values);
  //echo $val;
  $query="insert into $table ($str) values ('$val')";
  mysqli_query($db,$query) or die(mysqli_error($db));
  $status='save_successful';
  return $status;
}


function single_sum_condition($db,$table,$field,$condition){
	$query=mysqli_query($db,"SELECT SUM($field) as sum FROM $table WHERE $condition") or die('failed');
	$sum=mysqli_fetch_assoc($query);
	return $sum['sum'];
}




// Inventory Functions
function drug_idgen(){
    global $db;
    global $active_subscriber;
    $query=mysqli_query($db,"SELECT COUNT(*) as count FROM pharm_inventory WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    $count=mysqli_fetch_assoc($query);
    $count=++$count['count'];
    return prefix($count).''.$count;
}



// Stock Functions
function stock_value(){
  global $db;
  global $active_subscriber;
  $stock_value=mysqli_query($db,"SELECT SUM(cost_price*remaining_stock) as stock_value FROM inventory WHERE subscriber_id='".$active_subscriber."' && status='active'") or die(mysqli_error($db));
  $stock_value=mysqli_fetch_assoc($stock_value);
  $stock_value=$stock_value['stock_value'];
  return $stock_value;
}


function expected_stock_value(){
  global $db;
  global $active_subscriber;
  $stock_value=mysqli_query($db,"SELECT SUM(retail_price*remaining_stock) as stock_value FROM inventory WHERE subscriber_id='".$active_subscriber."' && status='active'") or die(mysqli_error($db));
  $stock_value=mysqli_fetch_assoc($stock_value);
  $stock_value=$stock_value['stock_value'];
  return $stock_value;
}


function profit_margin(){
  $profit=expected_stock_value()-stock_value();
  return $profit;
}

function drug_info($drug_id){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT * FROM inventory WHERE drug_id='".$drug_id."' && status='active' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $drug_info=mysqli_fetch_array($query);
  return $drug_info;
}

function  drug_qty_rem($drug_id){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,  "SELECT SUM(qty_rem) as total_qty FROM stock WHERE drug_id='".$drug_id."' && subscriber_id='".$active_subscriber."'") or die('failed');
  $qty_rem=mysqli_fetch_assoc($query);
  $qty_rem=$qty_rem['total_qty'];
  return $qty_rem;
}

function  drug_stock_value($drug_id){
  global $db;
  global $active_subscriber;
  $stock_value=mysqli_query($db,"SELECT * FROM inventory WHERE subscriber_id='".$active_subscriber."' && drug_id='".$drug_id."' && status='active'") or die('failed');
  $total_value=0;
  while ($rows=mysqli_fetch_array($stock_value)) {
    $get_value=mysqli_query($db,"SELECT SUM(qty_rem) as qty_rem FROM stock WHERE drug_id='".$rows['drug_id']."' && subscriber_id='".$active_subscriber."'") or die('failed');
    $get_value=mysqli_fetch_assoc($get_value);

    $total_value += $get_value['qty_rem'] * $rows['cost_price'];
  }
  return $total_value;
}
function  drug_expected_value($drug_id){
  global $db;
  global $active_subscriber;
  $stock_value=mysqli_query($db,"SELECT * FROM inventory WHERE subscriber_id='".$active_subscriber."' && drug_id='".$drug_id."' && status='active'") or die('failed');
  $total_value=0;
  while ($rows=mysqli_fetch_array($stock_value)) {
    $get_value=mysqli_query($db,"SELECT SUM(qty_rem) as qty_rem FROM stock WHERE drug_id='".$rows['drug_id']."' &&subscriber_id='".$active_subscriber."'") or die('failed');
    $get_value=mysqli_fetch_assoc($get_value);

    $total_value += $get_value['qty_rem'] * $rows['retail_price'];
  }
  return $total_value;
}

function transaction_idgen(){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT count(*) as count FROM sales WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $count=mysqli_fetch_assoc($query);
  $count=++$count['count'];
  return 'T'.prefix($count).''.$count;
}

function cart_sum($transaction_id){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT SUM(total) as cart_sum FROM cart WHERE transaction_id='".$transaction_id."' && subscriber_id='".$active_subscriber."' && status='active'") or die(mysqli_error($db));
  $cart_sum=mysqli_fetch_assoc($query);
  return $cart_sum['cart_sum'];
}

function validate_amount($amount){
  if(is_numeric($amount) && $amount >0){

  }
  else {
    echo 'Please enter a valid number';
  }
}



//Account related++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function account_info($account_number){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT * FROM all_accounts WHERE account_number='".$account_number."' && subscriber_id='".$active_subscriber."'") or die('failed');
  $account_info=mysqli_fetch_array($query);
  return $account_info;
}//end function

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function credit_account($amount,$account,$type){
  global $db;
  global $active_subscriber;
    if($type=='increasebalance'){
      $credit_account=mysqli_query($db,"UPDATE all_accounts SET credit=credit+$amount, balance=balance+$amount WHERE account_number='".$account."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    }
    elseif ($type=='decreasebalance') {
      $credit_account=mysqli_query($db,"UPDATE all_accounts SET credit=credit+$amount, balance=balance-$amount WHERE account_number='".$account."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    }

   if($credit_account){
     return 'Credit Successful';
   }
   else {
     return 'Credit Unsuccessful';
   }//end if
}


function debit_account($amount,$account,$type){
  global $db;
  global $active_subscriber;
    if($type=='increasebalance'){
      $debit_account=mysqli_query($db,"UPDATE all_accounts SET debit=debit+$amount, balance=balance+$amount WHERE account_number='".$account."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    }
    elseif ($type=='decreasebalance') {
      $debit_account=mysqli_query($db,"UPDATE all_accounts SET debit=debit+$amount, balance=balance-$amount WHERE account_number='".$account."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    }

   if($debit_account){
     return 'Debit Successful';
   }
   else {
     return 'Debit Unsuccessful';
   }//end if
}


function sales_period($start,$end){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"
  SELECT SUM(total) as total_sales FROM cart
  LEFT JOIN sales on sales.transaction_id=cart.transaction_id
  WHERE sales.date BETWEEN '".$start."' AND '".$end."' && cart.subscriber_id='".$active_subscriber."' && sales.subscriber_id='".$active_subscriber."' && cart.status='active'
  ") or die(mysqli_error($db));
  $total_sales=mysqli_fetch_assoc($query);
  $total_sales=$total_sales['total_sales'];
  return $total_sales;
}


function manufacturer_idgen(){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT COUNT(*) as count FROM manufacturers WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $count=mysqli_fetch_assoc($query);
  $count=++$count['count'];
  return 'MNF'.prefix($count).''.$count;
}


function supplier_idgen(){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT COUNT(*) as count FROM suppliers WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $count=mysqli_fetch_assoc($query);
  $count=++$count['count'];
  return 'SPL'.prefix($count).''.$count;
}

function user_idgen(){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT COUNT(*) as count FROM users WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $count=mysqli_fetch_assoc($query);
  $count=++$count['count'];
  return 'USR'.prefix($count).''.$count;
}


function header_info($header_id){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT * FROM expenditure_headers WHERE header_id='".$header_id."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $header_info=mysqli_fetch_array($query);
  return $header_info;
}

function expenditure_today(){
  global $db;
  global $today;
  $query=mysqli_query($db,"SELECT SUM(amount) as total_expenditure FROM expenditure WHERE date='".$today."'  && status!='DELETED'") or die(mysqli_error($db));
  $total_expenditure=mysqli_fetch_array($query);
  $total_expenditure=$total_expenditure['total_expenditure'];
  return $total_expenditure;
}


function expenditure_period($start,$end){
  global $db;
  $query=mysqli_query($db,"SELECT SUM(amount) as total_expenditure FROM expenditure WHERE date BETWEEN '".$start."' AND '".$end."' && status!='DELETED'") or die(mysqli_error($db));
  $total_expenditure=mysqli_fetch_array($query);
  $total_expenditure=$total_expenditure['total_expenditure'];
  return $total_expenditure;
}

function expenditure_by_header($header_id){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT SUM(amount) as total_expenditure FROM expenditure WHERE header_id='".$header_id."' && subscriber_id='".$active_subscriber."'") or die('failed');
  $total_expenditure=mysqli_fetch_assoc($query);
  return $total_expenditure['total_expenditure'];
}

function expenditure_idgen(){
    global $db;
    global $active_subscriber;
    $query=mysqli_query($db,"SELECT COUNT(*) as count FROM expenditure WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    $count=mysqli_fetch_assoc($query);
    $count=++$count['count'];
    $len=strlen($count);
    $prefix=prefix($len);
    return 'EXP'.$prefix.''.$count;
}

function new_account_idgen(){
  global $db;
  global $active_subscriber;
  $acc_start=substr($parent_account,0,1);
  $query=mysqli_query($db,"SELECT COUNT(*) AS count FROM all_accounts WHERE  subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $count=mysqli_fetch_assoc($query);
  $count=++$count['count'];
  $len=strlen($count);
  if($len<10){
    $prefix='00';
  }
  else {
    $prefix='';
  }
  return $prefix.''.$count;
}


function manufacturer_info($manufacturer_id){
  global $db;
  global $active_subscriber;

  $query=mysqli_query($db,"SELECT * FROM manufacturers WHERE manufacturer_id='".$manufacturer_id."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $manufacturer_info=mysqli_fetch_array($query);
  return $manufacturer_info;
}

function  customer_idgen(){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT COUNT(*) as count FROM customers WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $count=mysqli_fetch_assoc($query);
  $count=++$count['count'];
  $len=strlen($count);
  $prefix=prefix($len);
  return 'C'.$prefix.''.$count;
}


function customer_info($customer_id){
  global $db;
  global $active_subscriber;

  $query=mysqli_query($db,"SELECT * FROM customers WHERE customer_id='".$customer_id."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $customer_info=mysqli_fetch_array($query);
  return $customer_info;
}

function invoice_idgen(){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT COUNT(*) as count FROM invoices WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $count=mysqli_fetch_assoc($query);
  $count=++$count['count'];
  $len=strlen($count);
  $prefix=prefix($len);
  return 'INV'.$prefix.''.$count;
}

function invoice_info($invoice_id){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT * FROM invoices WHERE subscriber_id='".$active_subscriber."' && invoice_id='".$invoice_id."'") or die(mysqli_error($db));
  $invoice_info=mysqli_fetch_array($query);
  return $invoice_info;
}

function payment_idgen(){
  global $db;
  global $active_subscriber;
  $query=mysqli_query($db,"SELECT COUNT(*) as count FROM invoice_payments WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
  $count=mysqli_fetch_assoc($query);
  $count=++$count['count'];
  $len=strlen($count);
  $prefix=prefix($len);
  return 'PMT'.$prefix.''.$count;
}


function activity_log($activity){
  global $db;
  global $active_subscriber;
  global $user_id;
  $timestamp=time();

  $log=mysqli_query($db,"INSERT INTO activity_log (subscriber_id,timestamp,activity,user_id) VALUES ('$active_subscriber','$timestamp','$activity','$user_id') ") or die(mysqli_error($db));
  if(!$log){
    die("Log Error");
  }
}

function send_message($phone_number,$message){

  $auth = new BasicAuth("tdwurujt", "qmbjiqtt");
    // instance of ApiHost
    $apiHost = new ApiHost($auth);
    // Let us try to send some message
    $messagingApi = new MessagingApi($apiHost);

      try {
            // Send a quick message
            $messageResponse = $messagingApi->sendQuickMessage("xMedici", $phone_number, $message);

            // if ($messageResponse instanceof MessageResponse) {
            //     // echo $messageResponse->getStatus();
            // } elseif ($messageResponse instanceof HttpResponse) {
            //     // echo "\nServer Response Status : " . $messageResponse->getStatus();
            // }
        } catch (Exception $ex) {
            // echo $ex->getTraceAsString();
        }
}


?>
