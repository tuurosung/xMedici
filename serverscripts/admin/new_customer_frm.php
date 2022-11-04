<?php
require_once '../dbcon.php';
$customer_id=clean_string($_GET['customer_id']);
$customer_name=clean_string($_GET['customer_name']);
$address=clean_string($_GET['address']);
$phone_number=$_GET['phone_number'];
$date=date('Y-m-d');

reject_empty($customer_id);
reject_empty($customer_name);
reject_empty($address);
reject_empty($phone_number);

check_exists('customers','customer_id="'.$customer_id.'" && subscriber_id="'.$active_subscriber.'"');


$table='customers';
$fields=array("subscriber_id","customer_id","customer_name","address","phone_number","date","status");
$values=array("$active_subscriber","$customer_id","$customer_name","$address","$phone_number","$date","active");
$query=insert_data($db,$table,$fields,$values);

echo $query;

?>
