<?php

  /**
   * Surgery
   */
  class Surgery{

    Public $surgery_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->active_user=$_SESSION['active_user'];
      $now=time();
    }

    function SurgeryIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM surgeries WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'SURG'.prefix($count).''.$count;
    }

    function GetDoctors(){
      $query=mysqli_query($this->db,"SELECT * FROM doctors WHERE subscriber_id='".$this->active_subscriber."' AND status='active'") or die(msyqli_error($this->db));
      return $query;
    }//end function

    function DeleteSurgery(){
      $query=mysqli_query($this->db,"UPDATE surgeries SET status='deleted' WHERE surgery_id='".$this->surgery_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete surgery';
      }
    }//end function

    function SurgeryInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM surgeries WHERE surgery_id='".$this->surgery_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $surgery_info=mysqli_fetch_array($query);
      $this->patient_id=$surgery_info['patient_id'];
      $this->visit_id=$surgery_info['visit_id'];
      $this->surgical_procedure=$surgery_info['surgical_procedure'];
      $this->procedure_type=$surgery_info['procedure_type'];
      $this->date=$surgery_info['date'];

      $surgery_bill=mysqli_query($this->db,"SELECT SUM(total) AS surgery_cost FROM surgery_billing WHERE surgery_id='".$this->surgery_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $surgery_bill=mysqli_fetch_array($surgery_bill);
      $this->surgery_cost=$surgery_bill['surgery_cost'];
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


    function CreateSurgery($patient_id,$visit_id,$surgical_procedure,$procedure_type,$date){
      $surgery_id=$this->SurgeryIdGen();
      $checkstring=mysqli_query($this->db,"SELECT * FROM surgeries WHERE surgery_id='".$surgery_id."' AND subscriber_id='".$this->active_subscriber."'")or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) > 0){
        return 'Surgery Already Created';
      }else {
        $table='surgeries';
        $fields=array("subscriber_id","patient_id","visit_id","surgery_id","surgical_procedure","procedure_type","date","status");
        $values=array("$this->active_subscriber","$patient_id","$visit_id","$surgery_id","$surgical_procedure","$procedure_type","$date","active");
        $query=insert_data($this->db,$table,$fields,$values);
        echo $query;
      }//end else
    }// end Create


    function SurgeryBilling($surgery_id,$description,$unit_cost,$qty,$total){
      // $surgery_id=$this->SurgeryIdGen();
      $table='surgery_billing';
      $fields=array("subscriber_id","surgery_id","description","unit_cost","qty","total","status");
      $values=array("$this->active_subscriber","$surgery_id","$description","$unit_cost","$qty","$total","active");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }// end Create


    function PostOpNotes($surgery_id,$post_op_notes){
      // $surgery_id=$this->SurgeryIdGen();
      $table='surgery_postop_notes';
      $fields=array("subscriber_id","surgery_id","notes","doctor_id","status");
      $values=array("$this->active_subscriber","$surgery_id","$post_op_notes","$this->active_user","active");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }// end Create

    function SurgeryReport($surgery_id,$surgery_report){
      // $surgery_id=$this->SurgeryIdGen();
      $table='surgery_report';
      $fields=array("subscriber_id","surgery_id","report","doctor","status","timestamp");
      $values=array("$this->active_subscriber","$surgery_id","$surgery_report","$this->active_user","active","$this->now");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }// end Create



  }



 ?>
