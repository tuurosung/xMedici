<?php
  require_once 'Billing.php';
  /**
   * OPD Visit
   */
  class Visit{

    Public $patient_id='';
    Public $visit_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','root','@Tsung3#','xMedici') or die("Check Connection");
      $this->mysqli=new mysqli('localhost','root','@Tsung3#','xMedici');

      $sql="CREATE TABLE IF NOT EXISTS visits (
        sn int NOT NULL AUTO_INCREMENT,
        subscriber_id varchar(32) COLLATE utf8_unicode_ci NOT NULL,
        patient_id varchar(32) COLLATE utf8_unicode_ci NOT NULL,
        visit_id varchar(32) COLLATE utf8_unicode_ci NOT NULL,
        visit_type varchar(45) COLLATE utf8_unicode_ci NOT NULL,
        visit_date date NOT NULL,
        doctor_id varchar(45) COLLATE utf8_unicode_ci NOT NULL,
        major_complaint text COLLATE utf8_unicode_ci NOT NULL,
        primary_diagnosis varchar(32) COLLATE utf8_unicode_ci NOT NULL,
        secondary_diagnosis varchar(32) COLLATE utf8_unicode_ci NOT NULL,
        status varchar(32) COLLATE utf8_unicode_ci NOT NULL,
        department_id varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        admission_status text COLLATE utf8_unicode_ci NOT NULL,
        timestamp varchar(45) COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (sn)
      )";

      $this->mysqli->query($sql);

      if(!isset($_SESSION['active_subscriber']) || !isset($_SESSION['active_user']) || $_SESSION['active_subscriber']=='' || $_SESSION['active_user']==''){
        die('session_expired');
      }else {
        $this->active_subscriber=$_SESSION['active_subscriber'];
        $this->active_user=$_SESSION['active_user'];
      }

      $this->suffix=date('y');
      $this->today=date('Y-m-d');
      $this->timestamp=time();

      $count_visits=mysqli_query($this->db,"SELECT COUNT(*) as total_visit_count FROM visits WHERE status!='deleted' AND subscriber_id='".$this->active_subscriber."'") or die(msyqli_error($this->db));
      $count_visits=mysqli_fetch_array($count_visits);
      $this->total_visit_count=$count_visits['total_visit_count'];


      $count_active_opd=mysqli_query($this->db,"SELECT COUNT(*) as active_opd_cases FROM visits WHERE status='active' AND subscriber_id='".$this->active_subscriber."'") or die(msyqli_error($this->db));
      $count_active_opd=mysqli_fetch_array($count_active_opd);
      $this->active_opd_cases=$count_active_opd['active_opd_cases'];

      $count_discharged=mysqli_query($this->db,"SELECT COUNT(*) as discharged FROM visits WHERE status='discharged' AND subscriber_id='".$this->active_subscriber."'") or die(msyqli_error($this->db));
      $count_discharged=mysqli_fetch_array($count_discharged);
      $this->discharged=$count_discharged['discharged'];

      $count_active_admissions=mysqli_query($this->db,"SELECT COUNT(*) as active_admissions FROM admissions WHERE admission_status='admitted' AND subscriber_id='".$this->active_subscriber."'") or die(msyqli_error($this->db));
      $count_active_admissions=mysqli_fetch_array($count_active_admissions);
      $this->active_admissions=$count_active_admissions['active_admissions'];


       $overstay_patients=mysqli_query($this->db,"SELECT * FROM visits WHERE (visit_date <= now() - INTERVAL 15 DAY) AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
       $this->overstay_patients=mysqli_num_rows($overstay_patients);
       while ($rows=mysqli_fetch_array($overstay_patients)) {
         $patient_id=$rows['patient_id'];
         $visit_id=$rows['visit_id'];
         $visit_bill=$this->VisitBalance($patient_id,$visit_id);
         if($visit_bill<=0){
           $discharge_query=mysqli_query($this->db,"UPDATE visits
                                                                       SET status='discharged'
                                                                       WHERE
                                                                         visit_id='".$visit_id."' AND patient_id='".$patient_id."' AND subscriber_id='".$this->active_subscriber."'
                                                             ") or die(mysqli_error($this->active_subscriber));
         }
       }
    }


    // Create id for visits
    function VisitIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM visits WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'OPD'.prefix($count).''.$count;
    }


    function LabRequestIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM lab_requests WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'LAB'.prefix($count).''.$count;
    }

    function AdmissionIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM admissions WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'ADM'.prefix($count).''.$count;
    }


    function AllVisits(){
      $sql="SELECT * FROM visits WHERE status='active' AND subscriber_id='".$this->active_subscriber."'  ORDER BY visit_date desc";
      $result=$this->mysqli->query($sql);
      while ($rows=$result->fetch_assoc()) {
        $data[]=$rows;
      }
      return $data;
    }

    function PatientVisits(){
      $sql="SELECT * FROM visits WHERE patient_id='".$this->patient_id."' AND  status='active' AND subscriber_id='".$this->active_subscriber."'  ORDER BY visit_date desc";
      $result=$this->mysqli->query($sql);
      if($result->num_rows > 0){
        while ($rows=$result->fetch_assoc()) {
          $data[]=$rows;
        }
        return $data;
      }
      
    }


    function CreateVisit($patient_id,$visit_type,$major_complaint){
      $visit_id=$this->VisitIdGen();
      // $checkstring=mysqli_query($this->db,"SELECT * FROM visits WHERE subscriber_id='".$this->active_subscriber."' AND visit_id='".$visit_id."'") or die(msyqli_error($this->db));
      // create visit
      $table='visits';
      $fields=array("subscriber_id","visit_id","visit_type","patient_id","major_complaint","status","visit_date","timestamp");
      $values=array("$this->active_subscriber","$visit_id","$visit_type","$patient_id","$major_complaint","active","$this->today","$this->timestamp");
      $query=insert_data($this->db,$table,$fields,$values);

      $this->VisitLog($patient_id,$visit_id,'New Visit Created In Patient Folder');

      // if visit is created, proceed to create vitals and consultation pages
      if($query=='save_successful'){
        // create vitals
        $table='vitals';
        $fields=array("subscriber_id","visit_id","patient_id","status");
        $values=array("$this->active_subscriber","$visit_id","$patient_id","active");
        $query=insert_data($this->db,$table,$fields,$values);

        return $visit_id;
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


    function PatientTransfer($patient_id,$visit_id,$transferred_to){

      $time_transferred=date('H:i:s');
      $this->VisitInfo($visit_id);
      $transferred_from=$this->current_folder_location;

      if($transferred_from==$transferred_to){
        return 'You cannot transfer patient into the same department';
      }else {

              $table='patient_transfer';
              $fields=array("subscriber_id","patient_id","visit_id","transferred_from","transferred_to","transferred_by","date_transferred","time_transferred","transfer_status");
              $values=array("$this->active_subscriber","$patient_id","$visit_id","$transferred_from","$transferred_to","$this->active_user","$this->today","$time_transferred","pending");
              $query=insert_data($this->db,$table,$fields,$values);

              if($query=='save_successful'){
                $this->VisitLog($patient_id,$visit_id,'Patient Transferred');
                return 'save_successful';
              }else {
                return 'Unable to transfer patient';
              }
      }

    }

    function AcceptTransfer($patient_id,$visit_id,$serial,$transferred_to){
      $time_accepted=date('H:i:s');
      $query=mysqli_query($this->db,"UPDATE patient_transfer
                                                          SET
                                                            accepted_by='".$this->active_user."',
                                                            date_accepted='".$this->today."',
                                                            time_accepted='".$time_accepted."',
                                                            transfer_status='accepted'
                                                          WHERE
                                                            patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND sn='".$serial."'
                                      ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            $update_visit=mysqli_query($this->db,"UPDATE visits SET department_id='".$transferred_to."' WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
            return 'transfer_successful';
          }else {
            return 'Unable to accept transfer';
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
      $this->major_complaint=$visit_info['major_complaint'];
      $this->admission_status=$visit_info['admission_status'];
      $this->visit_status=$visit_info['status'];

      $query_vitals="SELECT * FROM vitals WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'";
      $r=$this->mysqli->query($query_vitals);
      $vitals=$r->fetch_assoc();
      $this->systolic=$vitals['systolic'];
      $this->diastolic=$vitals['diastolic'];
      $this->weight=$vitals['weight'];
      $this->temperature=$vitals['temperature'];
      $this->pulse=$vitals['pulse'];

      $get_diagnosis=mysqli_query($this->db,"SELECT * FROM patient_diagnosis WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $diagnosis_info=mysqli_fetch_array($get_diagnosis);
      $this->primary_diagnosis=$diagnosis_info['diagnosis_id'];
      $update_primary="UPDATE visits SET primary_diagnosis='".$this->primary_diagnosis."' WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."'";
      $this->mysqli->query($update_primary);

      $get_diagnosis=mysqli_query($this->db,"SELECT * FROM patient_secondary_diagnosis WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $diagnosis_info=mysqli_fetch_array($get_diagnosis);
      $this->secondary_diagnosis=$diagnosis_info['diagnosis'];
      $update_secondary="UPDATE visits SET secondary_diagnosis='".$this->secondary_diagnosis."' WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."'";
      $this->mysqli->query($update_secondary);
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


    function RecordComplain($patient_id,$visit_id,$complain,$complain_duration,$complain_status){
      $table='patient_complains';
      $fields=array("subscriber_id","patient_id","visit_id","complain","complain_duration","complain_status","status");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$complain","$complain_duration","$complain_status","active");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }

    function RecordHPC($patient_id,$visit_id,$hpc){
      $table='patient_hpc';
      $fields=array("subscriber_id","patient_id","visit_id","history","status");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$hpc","active");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }

    function RecordCE($patient_id,$visit_id,$clinical_examination){
      $table='patient_examination';
      $fields=array("subscriber_id","patient_id","visit_id","observation","status");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$clinical_examination","active");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }


    function RecordODQ($patient_id,$visit_id,$question,$response){
      $table='patient_odq';
      $fields=array("subscriber_id","patient_id","visit_id","question","response","status");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$question","$response","active");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }


    function Complain($complain_id){
      $query=mysqli_query($this->db,"SELECT * FROM syst_complains WHERE complain_id='".$complain_id."'") or die(mysqli_error($this->db));
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

    function RecordSecondaryDiagnosis($patient_id,$visit_id,$secondary_diagnosis){
      $table='patient_secondary_diagnosis';
      $fields=array("subscriber_id","patient_id","visit_id","diagnosis","status");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$secondary_diagnosis","active");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }

    function RecordDoctorsNotes($patient_id,$visit_id,$doctors_notes){
      $table='patient_doctors_notes';
      $fields=array("subscriber_id","patient_id","visit_id","doctors_notes","status");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$doctors_notes","active");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }


    function QueueTest($patient_id,$visit_id,$request_id,$test_id){
      $test=new Test();
      $test->test_id=$test_id;
      $test->TestInfo();
      $test_cost=$test->test_cost;

      $table='lab_requests_tests';
      $fields=array("subscriber_id","request_id","patient_id","visit_id","test_id","test_cost","status","results_status","date","timestamp");
      $values=array("$this->active_subscriber","$request_id","$patient_id","$visit_id","$test_id","$test_cost","active","pending","$this->today","$this->timestamp");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }


    function RequestTests($patient_id,$visit_id,$request_id,$total_cost,$patient_name,$request_type,$age,$sex,$requested_by,$doctors_name){
      $table='lab_requests';
      $fields=array("subscriber_id","patient_id","visit_id","request_id","total_cost","payment_status","status","date","timestamp","patient_name","request_type","age","sex","requested_by","doctors_name");

      $values=array("$this->active_subscriber","$patient_id","$visit_id","$request_id","$total_cost","pending","active","$this->today","$this->timestamp","$patient_name","$request_type","$age","$sex","$requested_by","$doctors_name");
      $query=insert_data($this->db,$table,$fields,$values);

      return $query;
    }


    function TestRequestInfo($request_id){
      $query=mysqli_query($this->db,"SELECT * FROM lab_requests_tests WHERE request_id='".$request_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $info=mysqli_fetch_array($query);
      $this->requested_test_patient_id=$info['patient_id'];
      $this->requested_test_visit_id=$info['visit_id'];
      $this->requested_test_date=$info['date'];
    }

    function RequestCost($request_id,$patient_id,$visit_id){
      $get_total_bill=mysqli_query($this->db,"SELECT SUM(test_cost) as labs_bill FROM lab_requests_tests WHERE request_id='".$request_id."' AND status='active' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $total_bill=mysqli_fetch_array($get_total_bill);
      $total_bill=$total_bill['labs_bill'];

      return $total_bill;
    }



    function Diagnosis($diagnosis_id){
      $query=mysqli_query($this->db,"SELECT * FROM sys_diagnosis WHERE diagnosis_id='".$diagnosis_id."'") or die(mysqli_error($this->db));
      $diagnosis_info=mysqli_fetch_array($query);
      return $diagnosis_info['description'];
    }


    function VisitBilling($patient_id,$visit_id){
      $get_bill=mysqli_query($this->db,"SELECT SUM(bill_amount) as total_bill FROM billing WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'") or die(mysqli_error($this->db));
      $bill=mysqli_fetch_array($get_bill);
      return $bill['total_bill'];
    }

    function VisitPayment($patient_id,$visit_id){
      $get_visit_payments=mysqli_query($this->db,"SELECT SUM(amount_paid) as paid
                                                                                FROM payments
                                                                                WHERE
                                                                                  patient_id='".$patient_id."' AND
                                                                                  visit_id='".$visit_id."' AND
                                                                                  status='active'
                                                          ") or die(mysqli_error($this->db));
      $get_payments=mysqli_fetch_array($get_visit_payments);
      return $get_payments['paid'];
    }

    function VisitBalance($patient_id,$visit_id){
      $bill=$this->VisitBilling($patient_id,$visit_id);
      $paid=$this->VisitPayment($patient_id,$visit_id);
      $visit_balance= $bill-$paid;
      return $visit_balance;
    }


    function AddPrescription($patient_id,$visit_id,$drug_id,$dosage,$duration,$route,$frequency,$notes){
      $table='prescriptions';
      $fields=array("subscriber_id","patient_id","visit_id","drug_id","dosage","duration","route","frequency","status","notes","date");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$drug_id","$dosage","$duration","$route","$frequency","active","$notes","$this->today");
      $query=insert_data($this->db,$table,$fields,$values);

      return $query;

    }

    function RequestAdmission($patient_id,$visit_id,$ward_id,$admission_notes,$doctor_id){
      $admission_id=$this->AdmissionIdGen();
      $request_timestamp=time();
      $checkstring=mysqli_query($this->db,"SELECT * FROM admissions WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) >0){
        return 'Patient Already Admitted';
      }else {
        $table='admissions';
        $fields=array("subscriber_id","patient_id","visit_id","admission_id","ward_id","admission_notes","admission_date","admission_requested_by","admission_request_status","request_timestamp","status");
        $values=array("$this->active_subscriber","$patient_id","$visit_id","$admission_id","$ward_id","$admission_notes","$this->today","$doctor_id","PENDING","$request_timestamp","active");
        $query=insert_data($this->db,$table,$fields,$values);

        // mysqli_query($this->db,"UPDATE visits SET admission_status='admitted' WHERE visit_id='".$visit_id."' AND patient_id='".$patient_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        return 'save_successful';
      }
    }

    function AcceptAdmission($admission_id,$bed_id,$admission_notes,$nurse_id){
      $accept_timestamp=time();
      $accept_admission=mysqli_query($this->db,"UPDATE admissions
                                                                            SET
                                                                              bed_id='".$bed_id."',
                                                                              admission_accepted_by='".$nurse_id."',
                                                                              accept_timestamp='".$accept_timestamp."',
                                                                              admission_request_status='ACCEPTED'
                                                                            WHERE
                                                                              admission_id='".$admission_id."' AND subscriber_id='".$this->active_subscriber."'
                                                          ")  or die(mysqli_error($this->db));
        if(mysqli_affected_rows($this->db)==1){
          $update_bed_status=mysqli_query($this->db,"UPDATE beds SET bed_status='FULL' WHERE bed_id='".$bed_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
          if($update_bed_status){
            return 'save_successful';
          }
        }else {
          return 'admission_failed';
        }
    }


    function DeceasedPatient($patient_id,$visit_id,$death_notes,$doctor){
      mysqli_query($this->db,"UPDATE patients SET  status='deceased'   WHERE  patient_id='".$patient_id."' AND subscriber_id='".$this->active_subscriber."'")  or die(mysqli_error($this->db));
      mysqli_query($this->db,"UPDATE visits SET  status='deceased'  WHERE  visit_id='".$visit_id."' AND patient_id='".$patient_id."' AND subscriber_id='".$this->active_subscriber."'")  or die(mysqli_error($this->db));
      mysqli_query($this->db,"UPDATE admissions SET  admission_status='deceased'  WHERE  visit_id='".$visit_id."' AND patient_id='".$patient_id."' AND subscriber_id='".$this->active_subscriber."'")  or die(mysqli_error($this->db));

      $table='patients_deceased';
      $fields=array("subscriber_id","patient_id","visit_id","death_date","time_of_death","death_notes","doctor","timestamp");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$death_date","$time_of_death","$death_notes","$doctor","$this->timestamp");
      $query=insert_data($this->db,$table,$fields,$values);

      return $query;

    }


    function All(){
      $sql="SELECT * FROM visits WHERE subscriber_id='".$this->active_subscriber."'";
      $r=$this->mysqli->query($sql);
      while ($rows=$r->fetch_assoc()) {
        $data[]=$rows;
      }
      return $data;
    }

  }



 ?>
