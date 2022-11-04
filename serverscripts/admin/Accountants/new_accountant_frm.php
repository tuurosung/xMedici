<?php
require_once '../../dbcon.php';
require_once '../../Classes/Accountants.php';
require_once '../../Classes/Users.php';

$accountant=new Accountant();
$user=new User();


$title=clean_string($_GET['title']);
$surname=clean_string($_GET['surname']);
$othernames=clean_string($_GET['othernames']);
$phone_number=clean_string($_GET['phone_number']);
$rank=clean_string($_GET['accountant_rank']);
$username=clean_string($_GET['username']);
$password=clean_string($_GET['password']);
$date=date('Y-m-d');
$timestamp=time();


reject_empty($surname);
reject_empty($othernames);
reject_empty($phone_number);
reject_empty($rank);
reject_empty($username);
reject_empty($password);

$accountant_id=$accountant->CreateAccountant($title,$surname,$othernames,$rank,$phone_number,$address,$username,$password,$date,$timestamp);
$create_user=$user->CreateNewUser($accountant_id,$phone_number,'accountant',$username,$password);
echo $create_user;

 ?>
