<?php

require_once ('Database.php');

  /**
   *Class for handling users
   */
  class Staff {
    Public $staff_id='';
    Public $username='';
    Public $password='';

    function __construct(){

      $d=new DataBase();

      $this->db=$d->db;
      $this->mysqli=$d->mysqli;

      $this->timenow=date('H:i:s');
      $this->today=date('Y-m-d');

        if(isset($_SESSION['active_subscriber'])){
          $this->active_subscriber=$_SESSION['active_subscriber'];
          $this->active_user=$_SESSION['active_user'];

          // create automatic hr categories
          $this->hr_categories=[
            'administrator' => 'Administrator',
            'admin_hr'  =>  'Human Resource',
            'doctor'  =>  'Doctor',
            'nurse'  =>  'Nurse',
            'laboratory'  =>  'Laboratory Scientist',
            'accountant'  =>  'Accountant',
            'pharmacy'  =>  'Pharmacist'
          ];

          foreach ($this->hr_categories as $alias => $description) {
          $sql="INSERT IGNORE INTO hr_categories(alias,description) VALUES('$alias','$description')";
          $this->mysqli->query($sql);
          }//

          $this->titles=['Dr.','Mr.','Mrs','Miss'];

          $this->ranks=['General Physician', 
              'Physiotherapy', 
              'Dermatology', 
              'Gynecology',
              'Oncology', 
              'Neurology', 
              'Physician Assistant', 
              'Nurse Consultant', 
              'Staff Nurse', 
              'Senior Staff Nurse',
              'Nursing Officer',
              'DDNS',
              'Administration'
            ];
        }

    }//end construct

    function StaffIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM users") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'USR'.prefix($count).''.$count;
    }//end function UserIdGen

    function StaffLogin(){
      $sql="SELECT * FROM staff WHERE username='".$this->username."' AND password='".$this->password."'";
      $r=$this->mysqli->query($sql);
      if($r->num_rows ==1){

          $info=$r->fetch_assoc();

        	$role=$info['role'];
        	$user_id=$info['staff_id'];
        	$email=$info['email'];
        	$active_subscriber=$info['subscriber_id'];
        	$account_status=$info['status'];
        	$permission=$info['permission'];

          

          if($role=='administrator'){
              $_SESSION['access_level']=$role;
              $_SESSION['active_user']=$user_id;
              $_SESSION['active_subscriber']=$active_subscriber;
            return 'login_successful';
          }else {
            if($permission =='granted'){
              $_SESSION['access_level']=$role;
              $_SESSION['active_user']=$user_id;
              $_SESSION['active_subscriber']=$active_subscriber;
              return 'login_successful';
            }else {
              return 'Your shift has expired';
            }
          }
        	
      }else {
        return 'Wrong username or password';
      }
    }

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

      $sql="SELECT * FROM staff WHERE staff_id='".$this->staff_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'";
      $r=$this->mysqli->query($sql);

      if($r->num_rows ==1){
        
        $info=$r->fetch_assoc();

        $this->title = $info['title'];
        $this->surname = $info['surname'];
        $this->othernames = $info['othernames'];
        $this->address = $info['address'];
        $this->emergency_contact_person = $info['emergency_contact_person'];
        $this->emergency_contact = $info['emergency_contact'];
        $this->address = $info['address'];
        $this->full_name = $info['surname'] . ' ' . $info['othernames'];
        $this->phone = $info['phone_number'];
        $this->email = $info['email'];
        $this->address = $info['address'];
        $this->rank = $info['staff_rank'];
        $this->role = $info['role'];
        $this->title = $info['title'];

      }else {

        return 'Staff Not Found';

      }     

    }//end function UserInfo


    function   Create($title, $rank, $role, $surname, $othernames, $phone_number, $address, $emergency_contact_person, $emergency_contact, $username, $password){
      $staff_id=$this->StaffIdGen();
        //Check if similar details exists
      $check="SELECT staff_id FROM staff WHERE username='".$username."' AND status='active'";
      $r=$this->mysqli->query($check);
      if($r->num_rows !=0){
        return "Choose A Different Username";
      }
      else {

          $table='staff';
          $fields=array("subscriber_id","staff_id","title","staff_rank","role","surname","othernames", "phone_number","address","emergency_contact_person","emergency_contact","username","password");
          $values=array("$this->active_subscriber","$staff_id","$title","$rank","$role","$surname","$othernames", "$phone_number","$address","$emergency_contact_person","$emergency_contact","$username","$password");
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



    function EditStaff($staff_id,$title, $rank, $role, $surname, $othernames, $phone_number, $address, $emergency_contact_person, $emergency_contact){
      $sql="UPDATE staff
                SET
                  title='".$title."',
                  staff_rank='".$rank."',
                  role='".$role."',
                  surname='".$surname."',
                  othernames='".$othernames."',
                  phone_number='".$phone_number."',
                  address='".$address."',
                  emergency_contact_person='".$emergency_contact_person."',
                  emergency_contact='".$emergency_contact."'
                WHERE
                  staff_id='".$staff_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
        ";
      $r=$this->mysqli->query($sql);
      if($this->mysqli->affected_rows ==1){
        return 'update_successful';
      }else {
        return 'Unable to update staff information';
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

      $check="SELECT * FROM staff_attendance WHERE  staff_id='".$staff_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'";
      $r=$this->mysqli->query($check);
      if($r->num_rows > 0){
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
      }else {
        return 'No data found';
      }
    }


  }

 ?>
