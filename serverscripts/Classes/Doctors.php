<?php

  /**
   * Doctor
   */
  class Doctor{

    Public $doctor_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function DoctorIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM doctors WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'DR'.prefix($count).''.$count;
    }

    function GetDoctors(){
      $query=mysqli_query($this->db,"SELECT * FROM doctors WHERE subscriber_id='".$this->active_subscriber."' AND status='active'") or die(msyqli_error($this->db));
      return $query;
    }//end function

    function DeleteDoctor(){
      $query=mysqli_query($this->db,"UPDATE doctors SET status='deleted' WHERE doctors_id='".$this->doctors_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete doctor';
      }
    }//end function

    function DoctorInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM doctors WHERE doctor_id='".$this->doctor_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $doctors_info=mysqli_fetch_array($query);
      $this->title=$doctors_info['title'];
      $this->surname=$doctors_info['surname'];
      $this->othernames=$doctors_info['othernames'];
      $this->doctor_fullname=$this->title.' '.$this->surname.' '.$this->othernames;
      $this->specialisation=$doctors_info['specialisation'];
      $this->phone_number=$doctors_info['phone_number'];
      $this->address=$doctors_info['address'];
    } //end DoctorInfo

    function UpdateDoctor($doctors_id,$title,$surname,$othernames,$specialiasation,$phone_number){
        $query=mysqli_query($this->db,"UPDATE doctors
                                                            SET
                                                              title='".$title."',
                                                              surname='".$surname."',
                                                              othernames='".$othernames."',
                                                              specialisation='".$specialiasation."',
                                                              phone_number='".$phone_number."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND doctors_id='".$doctors_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to updated doctors';
          }
    }// end update


    function CreateDoctor($title,$surname,$othernames,$specialiasation,$phone_number,$address,$username,$password,$date,$timestamp){

      $check_username=mysqli_query($this->db,"SELECT * FROM users WHERE username='".$username."' AND subscriber_id='".$this->active_subscriber."' AND status='active'")or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_username) > 0){
        return 'Username Exists';
      }else {

        $doctor_id=$this->DoctorIdGen();
        $checkstring=mysqli_query($this->db,"SELECT * FROM doctors WHERE doctor_id='".$doctors_id."' && subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if(mysqli_num_rows($checkstring) != 0){
          return  'Doctor Already Registered';
        }
        else {

          $table='doctors';
          $fields=array("subscriber_id","doctor_id","title","surname","othernames","specialisation","phone_number","address","date","timestamp","status");
          $values=array("$this->active_subscriber","$doctor_id","$title","$surname","$othernames","$specialiasation","$phone_number","$address","$date","$timestamp","active");
          $query=insert_data($this->db,$table,$fields,$values);



          return $doctor_id;
        } //end else
      }

    }// end Create


    function MyAppointments($doctor_id){
      $query=mysqli_query($this->db,"SELECT COUNT(*) AS my_appointments FROM visits WHERE doctor_id='".$doctor_id."' AND status='active' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $my_appointments=mysqli_fetch_assoc($query);
      return $my_appointments['my_appointments'];
    }



  }



 ?>
