<?php

  /**
   * Admin
   */
  class Hr{

    Public $hr_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function HrIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM admin_hr WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'HR'.prefix($count).''.$count;
    }


    function DeleteAdmin(){
      $query=mysqli_query($this->db,"UPDATE admin_hr SET status='deleted' WHERE admin_id='".$this->admin_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete admin';
      }
    }//end function

    function HrInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM admin_hr WHERE hr_id='".$this->hr_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $admin_info=mysqli_fetch_array($query);
      $this->title=$admin_info['title'];
      $this->surname=$admin_info['surname'];
      $this->othernames=$admin_info['othernames'];
      $this->hr_fullname=$this->title.' '.$this->surname.' '.$this->othernames;
      $this->phone_number=$admin_info['phone_number'];
      $this->address=$admin_info['address'];
    } //end DoctorInfo

    function UpdateAdmin($hr_id,$title,$surname,$othernames,$phone_number){
        $query=mysqli_query($this->db,"UPDATE admin_hr
                                                            SET
                                                              title='".$title."',
                                                              surname='".$surname."',
                                                              othernames='".$othernames."',
                                                              phone_number='".$phone_number."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND admins_id='".$hr_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to updated admin';
          }
    }// end update


    function CreateHr($title,$surname,$othernames,$phone_number,$address,$username,$password,$date,$timestamp){

      $check_username=mysqli_query($this->db,"SELECT * FROM users WHERE username='".$username."' AND subscriber_id='".$this->active_subscriber."' AND status='active'")or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_username) > 0){
        return 'Username Exists';
      }else {

        $hr_id=$this->HrIdGen();
        $checkstring=mysqli_query($this->db,"SELECT * FROM admin_hr WHERE hr_id='".$hr_id."' && subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if(mysqli_num_rows($checkstring) != 0){
          return  'HR Already Registered';
        }
        else {

          $table='admin_hr';
          $fields=array("subscriber_id","hr_id","title","surname","othernames","phone_number","address","date","timestamp","status");
          $values=array("$this->active_subscriber","$hr_id","$title","$surname","$othernames","$phone_number","$address","$date","$timestamp","active");
          $query=insert_data($this->db,$table,$fields,$values);

          return $hr_id;
        } //end else
      }

    }// end Create



  }



 ?>
