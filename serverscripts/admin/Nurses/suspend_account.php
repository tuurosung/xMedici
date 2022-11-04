<?php
require_once '../../dbcon.php';
require_once '../../Classes/Nurses.php';
require_once '../../Classes/Users.php';

$nurse=new Nurse();
$user=new User();

$nurse_id=clean_string($_GET['nurse_id']);


$query=$nurse->SuspendAccount($nurse_id);

echo $query;
