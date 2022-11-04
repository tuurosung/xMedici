<?php
  require_once 'Billing.php';
  /**
   * OPD Visit
   */
  class Admission{

    Public $patient_id='';
    Public $visit_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");

      if(!isset($_SESSION['active_subscriber']) || !isset($_SESSION['active_user']) || $_SESSION['active_subscriber']=='' || $_SESSION['active_user']==''){
        die('session_expired');
      }else {
        $this->active_subscriber=$_SESSION['active_subscriber'];
        $this->active_user=$_SESSION['active_user'];
      }

      $this->suffix=date('y');
      $this->today=date('Y-m-d');


      $count_active_admissions=mysqli_query($this->db,"SELECT COUNT(*) as active_admissions FROM admissions WHERE admission_status='admitted' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($db));
      $count_active_admissions=mysqli_fetch_array($count_active_admissions);
      $this->active_admissions=$count_active_admissions['active_admissions'];

      $count_discharged_admissions=mysqli_query($this->db,"SELECT COUNT(*) as discharged_admissions FROM admissions WHERE admission_status='discharged' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($db));
      $count_discharged_admissions=mysqli_fetch_array($count_discharged_admissions);
      $this->discharged_admissions=$count_discharged_admissions['discharged_admissions'];

      $count_total_admissions=mysqli_query($this->db,"SELECT COUNT(*) as total_admissions FROM admissions WHERE  subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($db));
      $count_total_admissions=mysqli_fetch_array($count_total_admissions);
      $this->total_admissions=$count_total_admissions['total_admissions'];

      $pending_discharge=mysqli_query($this->db,"SELECT COUNT(*) as pending_discharge FROM admissions WHERE admission_status='discharge_requested' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($db));
      $pending_discharge=mysqli_fetch_array($pending_discharge);
      $this->pending_discharge=$pending_discharge['pending_discharge'];

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

    function AdmissionInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM admissions WHERE admission_id='".$this->admission_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $admission_info=mysqli_fetch_array($query);
      $this->patient_id=$admission_info['patient_id'];
      $this->visit_id=$admission_info['visit_id'];
      $this->admission_date=$admission_info['admission_date'];
      $this->admission_requested_by=$admission_info['admission_requested_by'];
      $this->admission_status=$admission_info['admission_status'];

      $admission_date=new DateTime($admission_info['admission_date']);

      if($this->admission_status=='admitted'){
        $todays_date=new DateTime(date('Y-m-d'));
        $diff=date_diff($admission_date,$todays_date);
      }else {
        $discharge_date=new DateTime($admission_info['discharge_date']);
        $diff=date_diff($admission_date,$discharge_date);
      }

      $days_on_admission=$diff->format('%d') +1;
      $this->days_on_admission=$days_on_admission;
    }


    function VisitLog($patient_id,$visit_id,$notes){
      $time=date('H:i:s');
      $table='visit_log';
      $fields=array("subscriber_id","patient_id","visit_id","notes","user_id","date","time","status");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$notes","$this->active_user","$this->today","$time","logged");
      $query=insert_data($this->db,$table,$fields,$values);
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
    }


    function RecordVitals($admission_id,$systolic,$diastolic,$pulse,$weight,$temperature,$time){
      // $update_visit=mysqli_query($this->db,"UPDATE visits SET doctor_id='".$doctor_id."' WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));

      $table='admissions_vitals';
      $fields=array("subscriber_id","admission_id","systolic","diastolic","pulse","weight","temperature","date_time","status","nurse_id");
      $values=array("$this->active_subscriber","$admission_id","$systolic","$diastolic","$pulse","$weight","$temperature","$time","active","$this->active_user");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }

    function ServeMeds($admission_id,$drug_id,$time){
      // $update_visit=mysqli_query($this->db,"UPDATE visits SET doctor_id='".$doctor_id."' WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));

      $table='admissions_serve_meds';
      $fields=array("subscriber_id","admission_id","drug_id","date_time","status","nurse_id");
      $values=array("$this->active_subscriber","$admission_id","$drug_id","$time","active","$this->active_user");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }

    function SaveReview($admission_id,$review_notes,$time){
      // $update_visit=mysqli_query($this->db,"UPDATE visits SET doctor_id='".$doctor_id."' WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));

      $table='admissions_reviews';
      $fields=array("subscriber_id","admission_id","review_notes","date_time","status","doctor_id");
      $values=array("$this->active_subscriber","$admission_id","$review_notes","$time","active","$this->active_user");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }

    function SaveNursesNotes($admission_id,$nurses_notes,$time){
      // $update_visit=mysqli_query($this->db,"UPDATE visits SET doctor_id='".$doctor_id."' WHERE visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));

      $table='admissions_nursesnotes';
      $fields=array("subscriber_id","admission_id","nurses_notes","date_time","status","doctor_id");
      $values=array("$this->active_subscriber","$admission_id","$nurses_notes","$time","active","$this->active_user");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;

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


    function QueueTest($patient_id,$visit_id,$request_id,$test_id){
      $checkstring=mysqli_query($this->db,"SELECT *
                                                                    FROM lab_requests_tests
                                                                    WHERE
                                                                    patient_id='".$patient_id."' AND
                                                                    visit_id='".$visit_id."' AND
                                                                    test_id='".$test_id."' AND
                                                                    subscriber_id='".$this->active_subscriber."'
                                                                    AND status='active'
                                          ") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring)>0){
        return 'Test already requested';
      }
      else {

        // $billing=new Billing();
        // $billing->BillPatient($patient_id,$visit_id,$test_id,'1',$this->today);

        $test=new Test();
        $test->test_id=$test_id;
        $test->TestInfo();
        $test_cost=$test->test_cost;

        // $request_id=$this->RequestIdGen();

        $table='lab_requests_tests';
        $fields=array("subscriber_id","request_id","patient_id","visit_id","test_id","test_cost","status","results_status","date");
        $values=array("$this->active_subscriber","$request_id","$patient_id","$visit_id","$test_id","$test_cost","active","pending","$this->today");
        $query=insert_data($this->db,$table,$fields,$values);
        return $query;
      }
    }

    function RequestTests($patient_id,$visit_id,$request_id,$total_cost){
      $table='lab_requests';
      $fields=array("subscriber_id","patient_id","visit_id","request_id","total_cost","payment_status","status","date");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$request_id","$total_cost","pending","active","$this->today");
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
      $get_total_bill=mysqli_query($this->db,"SELECT SUM(test_cost) as labs_bill FROM lab_requests_tests WHERE request_id='".$request_id."' AND visit_id='".$visit_id."' AND status='active' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
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
      $this->visit_total_bill=$bill['total_bill'];
    }

    function VisitPayment($patient_id,$visit_id){
      $get_visit_payments=mysqli_query($this->db,"SELECT SUM(amount_paid-balance) as paid FROM payments WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'") or die(mysqli_error($this->db));
      $get_payments=mysqli_fetch_array($get_visit_payments);
      $this->visit_payment=$get_payments['paid'];
    }

    function VisitBalance($patient_id,$visit_id){
      $bill=$this->VisitBilling($patient_id,$visit_id);
      $paid=$this->VisitPayment($patient_id,$visit_id);
      $this->visit_balance= $bill-$paid;
    }


    function AddPrescription($patient_id,$visit_id,$drug_id,$qty,$duration,$strength,$strength_unit,$route,$frequency,$notes){
      $checkstring=mysqli_query($this->db,"SELECT * FROM prescriptions WHERE drug_id='".$drug_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring)==0){

        $table='prescriptions';
        $fields=array("subscriber_id","patient_id","visit_id","drug_id","qty","duration","strength","strength_unit","route","frequency","status","notes");
        $values=array("$this->active_subscriber","$patient_id","$visit_id","$drug_id","$qty","$duration","$strength","$strength_unit","$route","$frequency","active","$notes");
        $query=insert_data($this->db,$table,$fields,$values);

        return $query;
      }else {
        return 'Medication already added';
      }

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



    function RequestDischarge($admission_id,$patient_id,$visit_id,$discharge_notes,$time){
      $check_discharges=mysqli_query($this->db,"SELECT *
                                                                            FROM admission_discharges
                                                                            WHERE
                                                                                admission_id='".$admission_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                                        ") or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_discharges) > 0){
        return 'Discharge already requested';
      }else {

        $table='admission_discharges';
        $fields=array("subscriber_id","patient_id","visit_id","admission_id","discharge_date","discharge_notes","discharging_doctor","discharge_status","request_time","status");
        $values=array("$this->active_subscriber","$patient_id","$visit_id","$admission_id","$this->today","$discharge_notes","$this->active_user","PENDING","$time","active");
        $query=insert_data($this->db,$table,$fields,$values);

        return $query;
      }//end if
    }


  }



 ?>
