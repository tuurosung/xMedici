<?php
session_start();
require_once('dbcon.php');
require_once 'Classes/Subscribers.php';
require_once 'Classes/Staff.php';
require_once 'Classes/Doctors.php';
require_once 'Classes/Nurses.php';
require_once 'Classes/Pharmacists.php';
require_once 'Classes/Labtists.php';

// require_once '../hubtel/vendor/autoload.php';

$hospital=new Subscriber();
$date=date('Y-m-d');
$time=date('H:i:s');

$hospital->HospitalInfo();
$hospital_name=$hospital->hospital_name;
$hospital_email=$hospital->hospital_email;
$browserAgent = $_SERVER['HTTP_USER_AGENT'];


$username=clean_string($_GET['username']);
$password=clean_string($_GET['password']);

$query=mysqli_query($db,"SELECT * FROM staff WHERE username='".$username."' AND  password='".$password."' AND status='active'") or die(mysqli_error($db));

if(mysqli_num_rows($query)!=1){
	echo "Incorrect Username or Password";
}else {
	
	$user_info=mysqli_fetch_array($query);

	$role=$user_info['role'];
	$user_fullname=$user_info['full_name'];
	$user_id=$user_info['staff_id'];
	$user_email=$user_info['email'];
	$active_subscriber=$user_info['subscriber_id'];
	$account_status=$user_info['status'];
	$permission=$user_info['permission'];

	$_SESSION['username']=$username;
	$_SESSION['access_level']=$role;
	$_SESSION['active_user']=$user_id;
	$_SESSION['active_subscriber']=$active_subscriber;

				if($role!='administrator' && $permission=='granted'){
					$login_status=true;
				}elseif ($role=='administrator' || $role=='administrator_hr') {
					$login_status=true;
				}else {
					$login_status=false;
				}

				if($login_status==true){

					$staff_id=$user_id;
					$staff=new Staff();
					$staff->staff_id=$staff_id;
					$staff->StaffInfo();

					$user_prefix=substr($user_id,0,2);
					$user_fullname=$staff->full_name;


					$login_success="$user_fullname just signed in at $time";
					$phone_number=$hospital->hospital_phone;
					$phone_number=substr($phone_number,1);
					$phone_number='233'.$phone_number;
					ob_clean();
					//send_message($phone_number,$login_success);

$_SESSION['user_fullname']=$user_fullname;

$message="

Hi $hospital_name,

$user_fullname just signed into your
Hospital Management Software
at $time .

---------
The Medici Team
NT-0098-8712 -Education Ridge Road,
Tamale - Northern Region.
0246173282 | 0372020168

";


// $message='asdfad';
$headers = 'From: xMedici Hospital Manager <info@medicipos.com>' . "\r\n" .
	'Reply-To: info@medicipos.com' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();

mail($hospital_email, 'Sign In Notification | xMedici Hospital Manager', $message,$headers);

$message="

Hi $user_fullname,

Someone just signed into your xMedici Account at $time .

If this was you, then no further action is
required. Otherwise, report to your administrator
immediately.

---------
The Medici Team
NT-0098-8712 -Education Ridge Road,
Tamale - Northern Region.
0246173282 | 0372020168

";


// $message='asdfad';
$headers = 'From: xMedici Hospital Manager <info@medicipos.com>' . "\r\n" .
	'Reply-To: info@medicipos.com' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();

	if(!empty($user_email)){
		mail($user_email, 'Sign In Notification | xMedici Hospital Manager', $message,$headers);
	}

					echo 'login_successful';
				}else {
					echo "You are not authorized to access this system. Please contact administrator.";
				}
}




?>
