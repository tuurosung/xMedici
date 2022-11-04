<?php
require_once '../dbcon.php';
$user_id=$_GET['user_id'];
$full_name=$_GET['full_name'];
$phone_number=$_GET['phone_number'];
$access_level=$_GET['access_level'];
$username=$_GET['username'];
$password=$_GET['password'];
$date=date('Y-m-d');


reject_empty($active_subscriber);
reject_empty($user_id);
reject_empty($full_name);
reject_empty($phone_number);
reject_empty($access_level);
reject_empty($username);
reject_empty($password);

$check_exists=mysqli_query($db,"
                                                      SELECT user_id FROM users
                                                      WHERE
                                                          (user_id='".$user_id."' AND subscriber_id='".$active_subscriber."')
                                                          OR
                                                          (subscriber_id='".$active_subscriber."' AND username='".$username."')
                                              ") or die(mysqli_error($db));
if(mysqli_num_rows($check_exists) >0){
  die("Similar Information Exists");
}
else {
    $table='users';
    $fields=array("subscriber_id","user_id","full_name","phone","access_level","username","password","date");
    $values=array("$active_subscriber","$user_id","$full_name","$phone_number","$access_level","$username","$password","$date");
    $query=insert_data($db,$table,$fields,$values);

    if($query){
      echo 'save_successful';
    }
    else {
      echo 'failed';
    }
}

?>
