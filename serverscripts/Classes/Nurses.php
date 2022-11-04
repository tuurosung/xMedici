<?php

  /**
   * Doctor
   */
  class Nurse{

    Public $nurse_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function NurseIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM nurses WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'NR'.prefix($count).''.$count;
    }

    function GetNurses(){
      $query=mysqli_query($this->db,"SELECT * FROM nurses WHERE subscriber_id='".$this->active_subscriber."' AND status='active'") or die(msyqli_error($this->db));
      return $query;
    }//end function

    function DeleteNurse(){
      $query=mysqli_query($this->db,"UPDATE nurse SET status='deleted' WHERE nurse_id='".$this->nurse_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete nurse';
      }
    }//end function

    function NurseInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM nurses WHERE nurse_id='".$this->nurse_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $nurses_info=mysqli_fetch_array($query);
      $this->title=$nurses_info['title'];
      $this->surname=$nurses_info['surname'];
      $this->othernames=$nurses_info['othernames'];
      $this->nurse_fullname=$this->title.' '.$this->surname.' '.$this->othernames;
      $this->nurse_rank=$nurses_info['nurse_rank'];
      $this->phone_number=$nurses_info['phone_number'];
      $this->address=$nurses_info['address'];
    } //end DoctorInfo

    function UpdateDoctor($nurses_id,$title,$surname,$othernames,$nurse_rank,$phone_number){
        $query=mysqli_query($this->db,"UPDATE doctors
                                                            SET
                                                              title='".$title."',
                                                              surname='".$surname."',
                                                              othernames='".$othernames."',
                                                              nurse_rank='".$nurse_rank."',
                                                              phone_number='".$phone_number."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND nurse_id='".$nurses_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to updated doctors';
          }
    }// end update


    function CreateNurse($title,$surname,$othernames,$nurse_rank,$phone_number,$address,$username,$password,$date,$timestamp){

      $check_username=mysqli_query($this->db,"SELECT * FROM users WHERE username='".$username."' AND subscriber_id='".$this->active_subscriber."' AND status='active'")or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_username) > 0){
        return 'Username Exists';
      }else {

        $nurse_id=$this->NurseIdGen();
        $checkstring=mysqli_query($this->db,"SELECT * FROM nurses WHERE nurse_id='".$nurses_id."' && subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if(mysqli_num_rows($checkstring) != 0){
          return  'Nurse Already Registered';
        }
        else {

          $table='nurses';
          $fields=array("subscriber_id","nurse_id","title","surname","othernames","nurse_rank","phone_number","address","date","timestamp","status");
          $values=array("$this->active_subscriber","$nurse_id","$title","$surname","$othernames","$nurse_rank","$phone_number","$address","$date","$timestamp","active");
          $query=insert_data($this->db,$table,$fields,$values);



          return $nurse_id;
        } //end else
      }

    }// end Create

    function SuspendAccount($nurse_id){
      $suspend_query=mysqli_query($this->db,"UPDATE nurses SET status='suspended' WHERE nurse_id='".$nurse_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if($suspend_query){
        $lock_access=mysqli_query($this->db,"UPDATE users SET status='locked' WHERE user_id='".$nurse_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if($lock_access){
          return 'account_suspended';
        }else {
          return 'Unable to suspend account';
        }//end if
      }else {
        return 'Unable to suspend account';
      }
    }



  }



 ?>
