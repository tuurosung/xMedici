<?php
require_once '../../dbcon.php';
require_once '../../Classes/Doctors.php';
require_once '../../Classes/Users.php';

$doctor=new Doctor();
$user=new User();


$title=clean_string($_GET['title']);
$surname=clean_string($_GET['surname']);
$othernames=clean_string($_GET['othernames']);
$phone_number=clean_string($_GET['phone_number']);
$specialiasation=clean_string($_GET['specialisation']);
$username=clean_string($_GET['username']);
$password=clean_string($_GET['password']);
$date=date('Y-m-d');
$timestamp=time();


reject_empty($surname);
reject_empty($othernames);
reject_empty($phone_number);
reject_empty($specialiasation);
reject_empty($username);
reject_empty($password);

$doctor_id=$doctor->CreateDoctor($title,$surname,$othernames,$specialiasation,$phone_number,$address,$username,$password,$date,$timestamp);
$create_user=$user->CreateNewUser($doctor_id,$phone_number,'doctor',$username,$password);
echo $create_user;

 ?>
