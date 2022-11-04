<?php

  /**
   * Doctor
   */
  class Accountant{

    Public $accountant_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','root','@Tsung3#','xMedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function AccountantIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM accountants WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'ACC'.prefix($count).''.$count;
    }

    function GetAccountants(){
      $query=mysqli_query($this->db,"SELECT * FROM accountants WHERE subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      return $query;
    }//end function

    function DeleteAccountant(){
      $query=mysqli_query($this->db,"UPDATE nurse SET status='deleted' WHERE accountant_id='".$this->accountant_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete nurse';
      }
    }//end function

    function AccountantInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM accountants WHERE accountant_id='".$this->accountant_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $accountants_info=mysqli_fetch_array($query);
      $this->title=$accountants_info['title'];
      $this->surname=$accountants_info['surname'];
      $this->othernames=$accountants_info['othernames'];
      $this->accountant_fullname=$this->title.' '.$this->surname.' '.$this->othernames;
      $this->accountant_rank=$accountants_info['accountant_rank'];
      $this->phone_number=$accountants_info['phone_number'];
      $this->address=$accountants_info['address'];
    } //end DoctorInfo

    function UpdateDoctor($accountant_id,$title,$surname,$othernames,$nurse_rank,$phone_number){
        $query=mysqli_query($this->db,"UPDATE doctors
                                                            SET
                                                              title='".$title."',
                                                              surname='".$surname."',
                                                              othernames='".$othernames."',
                                                              nurse_rank='".$nurse_rank."',
                                                              phone_number='".$phone_number."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND accountant_id='".$accountant_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to updated doctors';
          }
    }// end update


    function CreateAccountant($title,$surname,$othernames,$rank,$phone_number,$address,$username,$password,$date,$timestamp){

      $check_username=mysqli_query($this->db,"SELECT * FROM users WHERE username='".$username."' AND subscriber_id='".$this->active_subscriber."' AND status='active'")or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_username) > 0){
        return 'Username Exists';
      }else {

        $accountant_id=$this->AccountantIdGen();
        $checkstring=mysqli_query($this->db,"SELECT * FROM accountants WHERE accountant_id='".$accountant_id."' && subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if(mysqli_num_rows($checkstring) != 0){
          return  'Accountant Already Registered';
        }
        else {

          $table='accountants';
          $fields=array("subscriber_id","accountant_id","title","surname","othernames","accountant_rank","phone_number","address","date","timestamp","status");
          $values=array("$this->active_subscriber","$accountant_id","$title","$surname","$othernames","$rank","$phone_number","$address","$date","$timestamp","active");
          $query=insert_data($this->db,$table,$fields,$values);



          return $accountant_id;
        } //end else
      }

    }// end Create

    function SuspendAccount($accountant_id){
      $suspend_query=mysqli_query($this->db,"UPDATE accountants SET status='suspended' WHERE accountant_id='".$accountant_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if($suspend_query){
        $lock_access=mysqli_query($this->db,"UPDATE users SET status='locked' WHERE user_id='".$accountant_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
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
