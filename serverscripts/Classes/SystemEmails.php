<?php
  /**
   *Class for handling Mails
   */
  class SystemMail {
    // Public $user_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      if(!isset($_SESSION['active_subscriber'])){
        die("NoSession");
      }
      else {
        $this->active_subscriber=$_SESSION['active_subscriber'];
        $this->active_user=$_SESSION['active_user'];

        $this->today=date('Y-m-d');
        $this->timenow=date('Y-m-d H:i:s');
      }
    }//end construct

    // $body = '
    //   Thank you for updating your email.
    //
    //   One more step to secure your account.
    //   Click on the link below to verify your email address.
    //
    //   https://www.x.medicipos.com/0/verify_email.php?email='.$user_email.'&auth='.$auth.'
    //
    //   ---------
    //   The Medici Team
    //   NT-0098-8712 -Education Ridge Road,
    //   Tamale - Northern Region.
    //   0246173282 | 0372020168
    //
    // ';


    function SendMail($email,$subject,$body){

      // $message='asdfad';
      $headers = 'From: xMedici Hospital Manager <info@medicipos.com>' . "\r\n" .
        'Reply-To: info@medicipos.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

      mail($email, $subject, $body,$headers);
      // mail($email, 'xMedici | Verify your email address', $body,$headers);


    }



  }

 ?>
