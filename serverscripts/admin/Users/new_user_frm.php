<?php
require_once '../../dbcon.php';
require_once '../../Classes/Users.php';

$u=new User();

$user_id=$u->UserIdGen();
$full_name=clean_string($_GET['full_name']);
$phone_number=clean_string($_GET['phone_number']);
$access_level=clean_string($_GET['access_level']);
$username=clean_string($_GET['username']);
$password=clean_string($_GET['password']);
$date=date('Y-m-d');


reject_empty($full_name);
reject_empty($phone_number);
reject_empty($access_level);
reject_empty($username);
reject_empty($password);

$query=$u->CreateNewUser($full_name,$phone_number,$access_level,$username,$password);

echo $query;

 ?>
