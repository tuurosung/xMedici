<?php
  /**
   *Class for handling users
   */
  class Staff {
    Public $staff_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->mysqli=new mysqli('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");


      if(!isset($_SESSION['active_subscriber'])){
        die("NoSession");
      }
      else {
        $this->active_subscriber=$_SESSION['active_subscriber'];
        $this->active_user=$_SESSION['active_user'];

        $this->today=date('Y-m-d');
        $this->timenow=date('Y-m-d H:i:s');

        // create automatic hr categories
        $hr_categories=['admin_hr'  =>  'Human Resource',
                                        'doctor'  =>  'Doctor',
                                        'nurse'  =>  'Nurse',
                                        'laboratory'  =>  'Laboratory Scientist',
                                        'accountant'  =>  'Accountant',
                                        'pharmacy'  =>  'Pharmacist'
                                      ];
          foreach ($hr_categories as $alias => $description) {
            $sql="INSERT IGNORE INTO hr_categories(alias,description) VALUES('$alias','$description')";
            $this->mysqli->query($sql);
          }//
      }
    }//end construct

    function HrCategories(){
      $sql='SELECT * FROM hr_categories';
      $result=$this->mysqli->query($sql);
      while ($rows=$result->fetch_assoc()) {
        $data[]=$rows;
      }
      return $data;
    }

    function AllStaff(){
      $sql="SELECT * FROM staff WHERE subscriber_id='".$this->active_subscriber."' AND status='active'";
      $result=$this->mysqli->query($sql);
      while ($rows=$result->fetch_assoc()) {
        $data[]=$rows;
      }
      return $data;
    }




    function StaffInfo(){

      $query=mysqli_query($this->db,"SELECT * FROM staff WHERE staff_id='".$this->staff_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $user_info=mysqli_fetch_array($query);

      $this->surname=$user_info['surname'];
      $this->othernames=$user_info['othernames'];
      $this->full_name=$user_info['surname'] .' '.$user_info['othernames'];
      $this->phone=$user_info['phone'];
      $this->email=$user_info['email'];
      $this->address=$user_info['address'];
      $this->category=$user_info['category'];
      $this->role=$user_info['role'];



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

    function DeleteStaff(){
      $sql="UPDATE staff SET status='deleted'";
      $sql.="WHERE staff_id='".$this->staff_id."' AND subscriber_id='".$this->active_subscriber."'";
      $sql;
      if($this->mysqli->query($sql)){
        return 'delete_successful';
      }else {
        return 'Unable to delete account';
      }
    }//end function DeleteUser



    function UpdateUser($staff_id,$full_name,$phone_number,$access_level,$username,$password){
      $query=mysqli_query($this->db,"UPDATE staff
                                                          SET
                                                            full_name='".$full_name."',
                                                            phone_number='".$phone_number."',
                                                            access_level='".$access_level."',
                                                            username='".$username."',
                                                            password='".$password."'
                                                          WHERE
                                                            staff_id='".$user_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
      ") or die(mysqli_error($this->db));

      if(mysqli_affected_rows($this->db)==1){
        return 'update_successful';
      }else {
        return 'Unable to update user information';
      }
    }//end function


    function UpdateUserEmail($user_email){
      if(!empty($user_email)){
        $auth=md5(rand(0,1000));
        $query=mysqli_query($this->db,"UPDATE staff
                                                            SET
                                                              email='".$user_email."',
                                                              auth='".$auth."'
                                                            WHERE
                                                              staff_id='".$this->user_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
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
          mysqli_query($this->db,"UPDATE staff SET permission = 'granted' WHERE staff_id='".$staff_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
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




    function ShiftManifest(){
      $sql="SELECT * FROM staff_attendance WHERE status='active' AND  subscriber_id ='".$this->active_subscriber."'";
      $r=$this->mysqli->query($sql);
      if($r->num_rows >0){
        while ($rows=$r->fetch_assoc()) {
          $data[]=$rows;
        }
        return $data;
      }
    }


  }

 ?>
