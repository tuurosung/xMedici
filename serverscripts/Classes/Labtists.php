<?php

  /**
   * Doctor
   */
  class Labtist{

    Public $labtist_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function LabtistIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM labtists WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'LB'.prefix($count).''.$count;
    }

    function GetLabtist(){
      $query=mysqli_query($this->db,"SELECT * FROM labtists WHERE subscriber_id='".$this->active_subscriber."' AND status='active'") or die(msyqli_error($this->db));
      return $query;
    }//end function

    function DeleteNurse(){
      $query=mysqli_query($this->db,"UPDATE labtists SET status='deleted' WHERE labtist_id='".$this->PharmIdGen."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete pharmacit';
      }
    }//end function

    function LabtistInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM labtists WHERE labtist_id='".$this->labtist_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $labtist_info=mysqli_fetch_array($query);
      $this->title=$labtist_info['title'];
      $this->surname=$labtist_info['surname'];
      $this->othernames=$labtist_info['othernames'];
      $this->labtist_fullname=$this->title.' '.$this->surname.' '.$this->othernames;
      $this->phone_number=$labtist_info['phone_number'];
      $this->address=$labtist_info['address'];
    } //end DoctorInfo

    function UpdateLabtist($labtist_id,$title,$surname,$othernames,$phone_number){
        $query=mysqli_query($this->db,"UPDATE labtists
                                                            SET
                                                              title='".$title."',
                                                              surname='".$surname."',
                                                              othernames='".$othernames."',
                                                              phone_number='".$phone_number."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND labtist_id='".$labtist_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to update pharmacist';
          }
    }// end update


    function CreateLabtist($title,$surname,$othernames,$phone_number,$address,$username,$password,$date,$timestamp){

      $check_username=mysqli_query($this->db,"SELECT * FROM users WHERE username='".$username."'  AND status='active'")or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_username) > 0){
        return 'Username Exists';
      }else {

        $labtist_id=$this->LabtistIdGen();
        $checkstring=mysqli_query($this->db,"SELECT * FROM labtists WHERE labtist_id='".$labtist_id."' && subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if(mysqli_num_rows($checkstring) != 0){
          return  'Labtist Already Registered';
        }
        else {

          $table='labtists';
          $fields=array("subscriber_id","labtist_id","title","surname","othernames","phone_number","address","date","timestamp","status");
          $values=array("$this->active_subscriber","$labtist_id","$title","$surname","$othernames","$phone_number","$address","$date","$timestamp","active");
          $query=insert_data($this->db,$table,$fields,$values);



          return $labtist_id;
        } //end else
      }

    }// end Create



  }



 ?>
