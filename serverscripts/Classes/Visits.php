<?php
  require_once 'Services.php';
  require_once 'Billing.php';
  require_once 'Patients.php';


  /**
   * Expenditure
   */
  class Visit{

    Public $visit_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->mysqli=new mysqli('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici');

      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->active_user=$_SESSION['active_user'];

      $this->today=date('Y-m-d');
      $this->timenow=date('Y-m-d H:i:s');
    }


    function VisitIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM visits WHERE subscriber_id='".$this->active_subscriber."' AND visit_date='".$this->today."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'OPD'.prefix($count).''.$count;
    }


    function CreateVisit($patient_id,$service_id,$visit_type){
      $visit_id=$this->VisitIdGen();
      $checkstring=mysqli_query($this->db,"SELECT * FROM visits WHERE subscriber_id='".$this->active_subscriber."' AND visit_id='".$visit_id."'") or die(msyqli_error($this->db));

      if(mysqli_num_rows($checkstring)>0){
        // if visit already exists
        return 'Visit Exists';
      }else {

        if($this->CheckForVisit($patient_id)=='no_visit'){
          // create visit
          $table='visits';
          $fields=array("subscriber_id","visit_id","visit_type","patient_id","status","visit_date","department_id");
          $values=array("$this->active_subscriber","$visit_id","$visit_type","$patient_id","active","$this->today","001");
          $query=insert_data($this->db,$table,$fields,$values);

          $this->VisitLog($patient_id,$visit_id,'Visit Created');

          $billing=new Billing();
          $billing->BillPatient($patient_id,$visit_id,$service_id,'1',$this->today);

          // if visit is created, proceed to create vitals and consultation pages
          if($query=='save_successful'){
            // create vitals
            $table='vitals';
            $fields=array("subscriber_id","visit_id","patient_id","status");
            $values=array("$this->active_subscriber","$visit_id","$patient_id","active");
            $query=insert_data($this->db,$table,$fields,$values);

            return 'Visit created, proceed to nurses desk';
          }else {
            return 'Unable to create visit';
          }
        }else {
          // if patient already has an active visit
          return 'Patient already has an active visit';
        }//end if
      }//end if
    }


    function CheckForVisit($patient_id){
      $checkstring=mysqli_query($this->db,"SELECT * FROM visits WHERE subscriber_id='".$this->active_subscriber."' AND  patient_id='".$patient_id."' AND (status='active' OR status='admitted')") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring)==0){
        return 'no_visit';
      }else {
        return 'active_visit';
      }
    }



    function VisitLog($patient_id,$visit_id,$notes){
      $time=date('H:i:s');
      $table='visit_log';
      $fields=array("subscriber_id","patient_id","visit_id","notes","user_id","date","time","status");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$notes","$this->active_user","$this->today","$time","logged");
      $query=insert_data($this->db,$table,$fields,$values);
    }

    function Department($department_id){
      $query=mysqli_query($this->db,"SELECT * FROM departments WHERE department_id='".$department_id."'") or die(mysqli_error($this->db));
      $department_info=mysqli_fetch_array($query);
      return $department_info['department_name'];
    }

    function VisitInfo($visit_id){
      $query=mysqli_query($this->db,"SELECT * FROM visits WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $visit_info=mysqli_fetch_array($query);
      $this->patient_id=$visit_info['patient_id'];
      $this->visit_type=$visit_info['visit_type'];
      $this->visit_date=$visit_info['visit_date'];
      $this->current_folder_location=$visit_info['department_id'];
      $this->doctor_id=$visit_info['doctor_id'];
    }


    function RecordVitals($visit_id,$patient_id,$systolic,$diastolic,$pulse,$weight,$temperature,$doctor_id){
      $update_visit=mysqli_query($this->db,"UPDATE visits SET doctor_id='".$doctor_id."' WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $query=mysqli_query($this->db,"UPDATE vitals
                                                         SET
                                                          systolic='".$systolic."',
                                                          diastolic='".$diastolic."',
                                                          pulse='".$pulse."',
                                                          weight='".$weight."',
                                                          temperature='".$temperature."'
                                                        WHERE
                                                          patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."'
                                      ") or die(mysqli_error($this->db));
        if(mysqli_affected_rows($this->db)==1){
          return 'save_successful';
        }else {
          return 'Patient vitals not updated';
        }
    }


    function RecordComplain($patient_id,$visit_id,$complain_id){
      $checkstring=mysqli_query($this->db,"SELECT *
                                                                    FROM patient_complains
                                                                    WHERE
                                                                    patient_id='".$patient_id."' AND
                                                                    visit_id='".$visit_id."' AND
                                                                    complain_id='".$complain_id."' AND
                                                                    subscriber_id='".$this->active_subscriber."'
                                                                    AND status='active'
                                          ") or die(mysqli_error($this->db));

      if(mysqli_num_rows($checkstring)>0){
        return 'Complain already recorded';
      }
      else {
        $table='patient_complains';
        $fields=array("subscriber_id","patient_id","visit_id","complain_id","status");
        $values=array("$this->active_subscriber","$patient_id","$visit_id","$complain_id","active");
        $query=insert_data($this->db,$table,$fields,$values);
        return $query;
      }
    }

    function Complain($complain_id){
      $query=mysqli_query($this->db,"SELECT * FROM complains WHERE complain_id='".$complain_id."'") or die(mysqli_error($this->db));
      $complain_info=mysqli_fetch_array($query);
      return $complain_info['description'];
    }

    function RecordDiagnosis($patient_id,$visit_id,$diagnosis_id){
      $checkstring=mysqli_query($this->db,"SELECT *
                                                                    FROM patient_diagnosis
                                                                    WHERE
                                                                    patient_id='".$patient_id."' AND
                                                                    visit_id='".$visit_id."' AND
                                                                    diagnosis_id='".$diagnosis_id."' AND
                                                                    subscriber_id='".$this->active_subscriber."'
                                                                    AND status='active'
                                          ") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring)>0){
        return 'Diagnosis already recorded';
      }
      else {
        $table='patient_diagnosis';
        $fields=array("subscriber_id","patient_id","visit_id","diagnosis_id","status");
        $values=array("$this->active_subscriber","$patient_id","$visit_id","$diagnosis_id","active");
        $query=insert_data($this->db,$table,$fields,$values);
        return $query;
      }
    }

    function Diagnosis($diagnosis_id){
      $query=mysqli_query($this->db,"SELECT * FROM diagnosis WHERE diagnosis_id='".$diagnosis_id."'") or die(mysqli_error($this->db));
      $diagnosis_info=mysqli_fetch_array($query);
      return $diagnosis_info['description'];
    }






  }



 ?>
