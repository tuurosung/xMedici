<?php

  /**
   * Doctor
   */
  class Pharmacist{

    Public $pharm_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function PharmIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM pharmacists WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'PH'.prefix($count).''.$count;
    }

    function GetPharm(){
      $query=mysqli_query($this->db,"SELECT * FROM pharmacists WHERE subscriber_id='".$this->active_subscriber."' AND status='active'") or die(msyqli_error($this->db));
      return $query;
    }//end function

    function DeleteNurse(){
      $query=mysqli_query($this->db,"UPDATE pharmacists SET status='deleted' WHERE pharm_id='".$this->PharmIdGen."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete pharmacit';
      }
    }//end function

    function PharmInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM pharmacists WHERE pharm_id='".$this->pharm_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $pharm_info=mysqli_fetch_array($query);
      $this->title=$pharm_info['title'];
      $this->surname=$pharm_info['surname'];
      $this->othernames=$pharm_info['othernames'];
      $this->pharm_fullname=$this->title.' '.$this->surname.' '.$this->othernames;
      $this->phone_number=$pharm_info['phone_number'];
      $this->address=$pharm_info['address'];
    } //end DoctorInfo

    function UpdatePharm($pharm_id,$title,$surname,$othernames,$phone_number){
        $query=mysqli_query($this->db,"UPDATE pharmacists
                                                            SET
                                                              title='".$title."',
                                                              surname='".$surname."',
                                                              othernames='".$othernames."',
                                                              phone_number='".$phone_number."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND pharm_id='".$pharm_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to update pharmacist';
          }
    }// end update


    function CreatePharm($title,$surname,$othernames,$phone_number,$address,$username,$password,$date,$timestamp){

      $check_username=mysqli_query($this->db,"SELECT * FROM users WHERE username='".$username."'  AND status='active'")or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_username) > 0){
        return 'Username Exists';
      }else {

        $pharm_id=$this->PharmIdGen();
        $checkstring=mysqli_query($this->db,"SELECT * FROM pharmacists WHERE pharm_id='".$pharm_id."' && subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if(mysqli_num_rows($checkstring) != 0){
          return  'Pharm Already Registered';
        }
        else {

          $table='pharmacists';
          $fields=array("subscriber_id","pharm_id","title","surname","othernames","phone_number","address","date","timestamp","status");
          $values=array("$this->active_subscriber","$pharm_id","$title","$surname","$othernames","$phone_number","$address","$date","$timestamp","active");
          $query=insert_data($this->db,$table,$fields,$values);



          return $pharm_id;
        } //end else
      }

    }// end Create



  }



 ?>
