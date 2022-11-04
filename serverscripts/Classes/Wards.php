<?php

  /**
   * Wards
   */
  class Ward{

    Public $ward_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function WardIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM wards WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'WD'.prefix($count).''.$count;
    }
    function BedIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM beds WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'BED'.prefix($count).''.$count;
    }

    // function GetDoctors(){
    //   $query=mysqli_query($this->db,"SELECT * FROM doctors WHERE subscriber_id='".$this->active_subscriber."' AND status='active'") or die(msyqli_error($this->db));
    //   return $query;
    // }//end function
    //
    // function DeleteDoctor(){
    //   $query=mysqli_query($this->db,"UPDATE doctors SET status='deleted' WHERE doctors_id='".$this->doctors_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
    //   if(mysqli_affected_rows($this->db)==1){
    //     return 'delete_successful';
    //   }else {
    //     return 'Unable to delete doctor';
    //   }
    // }//end function

    function WardInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM wards WHERE ward_id='".$this->ward_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $ward_info=mysqli_fetch_array($query);
      $this->ward_type=$ward_info['ward_type'];
      $this->description=$ward_info['description'];
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


    function CreateWard($ward_type,$description){

      $checkstring=mysqli_query($this->db,"SELECT * FROM wards WHERE description='".$description."' AND subscriber_id='".$this->active_subscriber."' AND status='active'")or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_username) > 0){
        return 'Ward Exists';
      }else {

        $ward_id=$this->WardIdGen();
        $table='wards';
        $fields=array("subscriber_id","ward_id","ward_type","description","status");
        $values=array("$this->active_subscriber","$ward_id","$ward_type","$description","active");
        $query=insert_data($this->db,$table,$fields,$values);

        return $query;
      }

    }// end Create


    function CreateBed($ward_id,$bed_type,$description){
      $bed_id=$this->BedIdGen();
      $checkstring=mysqli_query($this->db,"SELECT * FROM beds WHERE (bed_id='".$bed_id."' OR description='".$description."') AND ward_id='".$ward_id."' AND  subscriber_id='".$this->active_subscriber."' AND status='active'")or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_username) > 0){
        return 'Bed Exists';
      }else {

        $table='beds';
        $fields=array("subscriber_id","ward_id","bed_type","bed_id","description","status");
        $values=array("$this->active_subscriber","$ward_id","$bed_type","$bed_id","$description","active");
        $query=insert_data($this->db,$table,$fields,$values);

        return $query;
      }

    }// end Create


    function CheckBeds($ward_id){
      $query=mysqli_query($this->db,"SELECT COUNT(*) AS empty_beds FROM beds WHERE bed_status='EMPTY' AND ward_id='".$ward_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die (msyqli_error($this->db));
      $info=mysqli_fetch_array($query);
      return $info['empty_beds'];
    }

    function BedInfo($bed_id){
      $query=mysqli_query($this->db,"SELECT * FROM beds WHERE bed_id='".$bed_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $bed_info=mysqli_fetch_array($query);
      $this->bed_description=$bed_info['description'];
      $this->bed_status=$bed_info['bed_status'];
    }



  }



 ?>
