<?php
require_once '../../dbcon.php';
require_once '../../Classes/Pharmacists.php';
require_once '../../Classes/Users.php';

$pharm=new Pharmacist();
$user=new User();


$title=clean_string($_GET['title']);
$surname=clean_string($_GET['surname']);
$othernames=clean_string($_GET['othernames']);
$phone_number=clean_string($_GET['phone_number']);
$username=clean_string($_GET['username']);
$password=clean_string($_GET['password']);
$date=date('Y-m-d');
$timestamp=time();


reject_empty($surname);
reject_empty($othernames);
reject_empty($phone_number);
reject_empty($username);
reject_empty($password);

$pharm_id=$pharm->CreatePharm($title,$surname,$othernames,$phone_number,$address,$username,$password,$date,$timestamp);
$create_user=$user->CreateNewUser($pharm_id,$phone_number,'pharmacist',$username,$password);
echo $create_user;

 ?>
