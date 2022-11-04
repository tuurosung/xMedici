<?php
  /**
   *Class for handling users
   */
  class User {
    Public $user_id='';

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

    function UserIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM users WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'USR'.prefix($count).''.$count;
    }//end function UserIdGen

    function UserInfo(){

      $query=mysqli_query($this->db,"SELECT * FROM users WHERE user_id='".$this->user_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $user_info=mysqli_fetch_array($query);

      $this->full_name=$user_info['full_name'];
      $this->phone=$user_info['phone'];
      $this->email=$user_info['email'];
      $this->mail_verify=$user_info['mail_verify'];
      $this->access_level=$user_info['access_level'];
      $this->username=$user_info['username'];
      $this->password=$user_info['password'];

    }//end function UserInfo

    function CreateNewUser($staff_id,$phone_number,$access_level,$username,$password){
      $user_id=$this->UserIdGen();
        //Check if similar details exists
      $check_exists=mysqli_query($this->db,"
                                                            SELECT user_id FROM users
                                                            WHERE
                                                                (user_id='".$user_id."' AND subscriber_id='".$this->active_subscriber."')
                                                                OR
                                                                (subscriber_id='".$this->active_subscriber."' AND username='".$username."')
                                                    ") or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_exists) >0){
        return "Similar Information Exists";
      }
      else {
          $table='users';
          $fields=array("subscriber_id","user_id","staff_id","phone_number","role","username","password","date","status");
          $values=array("$this->active_subscriber","$staff_id","$full_name","$phone_number","$access_level","$username","$password","$date","active");
          $query=insert_data($this->db,$table,$fields,$values);

          if($query){
            return "save_successful";
          }
          else {
            return "unsuccessful";
          }
      }//end else
    }//end function CreateNewUser

    function DeleteUser(){
      $query=mysqli_query($this->db,"UPDATE users SET status='deleted' WHERE user_id='".$this->user_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }
      else {
        return 'unsuccessful';
      }//end else
    }//end function DeleteUser



    function UpdateUser($user_id,$full_name,$phone_number,$access_level,$username,$password){
      $query=mysqli_query($this->db,"UPDATE users
                                                          SET
                                                            full_name='".$full_name."',
                                                            phone_number='".$phone_number."',
                                                            access_level='".$access_level."',
                                                            username='".$username."',
                                                            password='".$password."'
                                                          WHERE
                                                            user_id='".$user_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
      ") or die(mysqli_error($this->db));

      if(mysqli_affected_rows($this->db)==1){
        return 'update_successful';
      }else {
        return 'Unable to update user information';
      }
    }


    function UpdateUserEmail($user_email){
      if(!empty($user_email)){
        $auth=md5(rand(0,1000));
        $query=mysqli_query($this->db,"UPDATE users
                                                            SET
                                                              email='".$user_email."',
                                                              auth='".$auth."'
                                                            WHERE
                                                              user_id='".$this->user_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
        ") or die(mysqli_error($this->db));
        if(mysqli_affected_rows($this->db)==1){

          $message = '
            Thank you for updating your email.

            One more step to secure your account.
            Click on the link below to verify your email address.

            https://www.x.medicipos.com/0/verify_email.php?email='.$user_email.'&auth='.$auth.'

            ---------
            The Medici Team
            NT-0098-8712 -Education Ridge Road,
            Tamale - Northern Region.
            0246173282 | 0372020168

          ';

					// $message='asdfad';
					$headers = 'From: xMedici Hospital Manager <info@medicipos.com>' . "\r\n" .
						'Reply-To: info@medicipos.com' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();

					mail($user_email, 'xMedici | Verify your email address', $message,$headers);



          return 'update_successful';
        }else {
          return 'Unable to update user information';
        }
      }


    }


    function LogShift($staff_id,$shift_type,$notes){

      $check=mysqli_query($this->db,"SELECT *
                                                          FROM staff_attendance
                                                          WHERE
                                                            staff_id='".$staff_id."' AND
                                                            subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($check) > 0){
        return "Staff Already Has An Active Shift";
      }else {
        $table='staff_attendance';
        $fields=array("subscriber_id","staff_id","shift_type","time_in","log_date","status");
        $values=array("$this->active_subscriber","$staff_id","$shift_type","$this->timenow","$this->today","active");
        $query=insert_data($this->db,$table,$fields,$values);

        if($query){
          mysqli_query($this->db,"UPDATE users SET permission = 'granted' WHERE user_id='".$staff_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
          echo 'save_successful';
        }
      }
    }

    function EndShift($staff_id,$sn){

      $lock_user=mysqli_query($this->db,"UPDATE users SET permission = 'declined' WHERE user_id='".$staff_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $end_shift=mysqli_query($this->db,"UPDATE staff_attendance SET time_out='".$this->timenow."', status = 'shiftover' WHERE staff_id='".$staff_id."' AND sn='".$sn."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));

      if($end_shift){
        return "Shift Ended Successfully";
      }
    }


  }

 ?>
