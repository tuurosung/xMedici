<?php
require_once '../../dbcon.php';
require_once '../../Classes/Nurses.php';
require_once '../../Classes/Users.php';

$nurse=new Nurse();
$user=new User();


$title=clean_string($_GET['title']);
$surname=clean_string($_GET['surname']);
$othernames=clean_string($_GET['othernames']);
$phone_number=clean_string($_GET['phone_number']);
$nurse_rank=clean_string($_GET['nurse_rank']);
$username=clean_string($_GET['username']);
$password=clean_string($_GET['password']);
$date=date('Y-m-d');
$timestamp=time();


reject_empty($surname);
reject_empty($othernames);
reject_empty($phone_number);
reject_empty($nurse_rank);
reject_empty($username);
reject_empty($password);

$nurse_id=$nurse->CreateNurse($title,$surname,$othernames,$nurse_rank,$phone_number,$address,$username,$password,$date,$timestamp);
$create_user=$user->CreateNewUser($nurse_id,$phone_number,'nurse',$username,$password);
echo $create_user;

 ?>
