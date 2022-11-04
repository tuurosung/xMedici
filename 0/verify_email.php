<?php

  require_once '../serverscripts/dbcon.php';

  $email=clean_string($_GET['email']);
  $auth=clean_string($_GET['auth']);

  $check_match=mysqli_query($db,"SELECT * FROM users WHERE email='".$email."' AND auth='".$auth."'") or die(mysqli_error($db));
  $details=mysqli_fetch_array($check_match);
  if(mysqli_num_rows($check_match)==1){
    if($details['mail_verify']==1){
      echo 'Email already verified';
      echo '<p>Click <a href="../index.php">here</a> to proceed to login</p>';
    }else {
      mysqli_query($db,"UPDATE users SET mail_verify=1 WHERE email='".$email."' AND auth='".$auth."'") or die(mysqli_error($db));
      echo '<h3>Awesome, email verification successful</h3>';
      echo '<p>Click <a href="../index.php">here</a> to proceed to login</p>';
    }

  }else {
    echo 'Invalid link';
  }


 ?>
