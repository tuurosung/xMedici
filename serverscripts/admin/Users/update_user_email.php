<?php
require_once '../../dbcon.php';
require_once '../../Classes/Users.php';

$user=new User();

$user_id=$_SESSION['active_user'];
$user_email=clean_string($_GET['email_address']);


reject_empty($user_id);
reject_empty($user_email);

$user->user_id=$user_id;
$user->UserInfo();
if(!empty($user->email)){
  echo 'Link already sent';
}else {
  $query=$user->UpdateUserEmail($user_email);
}


echo $query;

 ?>
