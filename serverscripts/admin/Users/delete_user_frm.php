<?php
require_once '../../dbcon.php';
require_once '../../Classes/Users.php';

$u=new User();

$user_id=clean_string($_GET['user_id']);

$u->user_id=$user_id;
$query=$u->DeleteUser();

echo $query;
 ?>
