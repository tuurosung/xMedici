<?php

  /**
   * Admin
   */
  class Admin{

    Public $admin_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function AdminIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM admins WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'ADM'.prefix($count).''.$count;
    }

    function GetAdmins(){
      $query=mysqli_query($this->db,"SELECT * FROM admins WHERE subscriber_id='".$this->active_subscriber."' AND status='active'") or die(msyqli_error($this->db));
      return $query;
    }//end function

    function DeleteAdmin(){
      $query=mysqli_query($this->db,"UPDATE admins SET status='deleted' WHERE admin_id='".$this->admin_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete admin';
      }
    }//end function

    function AdminInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM admins WHERE admin_id='".$this->admin_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $admin_info=mysqli_fetch_array($query);
      $this->title=$admin_info['title'];
      $this->surname=$admin_info['surname'];
      $this->othernames=$admin_info['othernames'];
      $this->admin_fullname=$this->title.' '.$this->surname.' '.$this->othernames;
      $this->phone_number=$admin_info['phone_number'];
      $this->address=$admin_info['address'];
    } //end DoctorInfo

    function UpdateAdmin($admins_id,$title,$surname,$othernames,$phone_number){
        $query=mysqli_query($this->db,"UPDATE admins
                                                            SET
                                                              title='".$title."',
                                                              surname='".$surname."',
                                                              othernames='".$othernames."',
                                                              phone_number='".$phone_number."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND admins_id='".$admins_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to updated admin';
          }
    }// end update


    function CreateAdmin($title,$surname,$othernames,$phone_number,$address,$username,$password,$date,$timestamp){

      $check_username=mysqli_query($this->db,"SELECT * FROM users WHERE username='".$username."' AND subscriber_id='".$this->active_subscriber."' AND status='active'")or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_username) > 0){
        return 'Username Exists';
      }else {

        $admin_id=$this->AdminIdGen();
        $checkstring=mysqli_query($this->db,"SELECT * FROM admins WHERE admin_id='".$admins_id."' && subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if(mysqli_num_rows($checkstring) != 0){
          return  'Admin Already Registered';
        }
        else {

          $table='admins';
          $fields=array("subscriber_id","admin_id","title","surname","othernames","phone_number","address","date","timestamp","status");
          $values=array("$this->active_subscriber","$admin_id","$title","$surname","$othernames","$phone_number","$address","$date","$timestamp","active");
          $query=insert_data($this->db,$table,$fields,$values);



          return $admin_id;
        } //end else
      }

    }// end Create



  }



 ?>
