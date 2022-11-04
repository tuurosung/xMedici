<?php
  require_once 'Services.php';
  require_once 'Billing.php';
  require_once 'Tests.php';


  /**
   * Expenditure
   */
  class Patient{

    Public $patient_id='';

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
    }

    function PatientIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM patients WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return prefix($count).''.$count.'/'.$this->suffix;
    }

    function VisitIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM visits WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'OPD'.prefix($count).''.$count;
    }

    function RequestIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM lab_requests WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'LAB'.prefix($count).''.$count;
    }


    function PatientInfo(){
      $query=mysqli_query($this->db,"SELECT *
                                                          FROM patients
                                                          WHERE
                                                            patient_id='".$this->patient_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                        ") or die(mysqli_error($this->db));

      $patient_info=mysqli_fetch_array($query);
      $this->surname=ucfirst(strtolower($patient_info['surname']));
      $this->othernames=$patient_info['othernames'];
      $this->patient_fullname=ucfirst(strtolower($this->surname)) .' '.ucfirst(strtolower($this->othernames));
      $this->date_of_birth=$patient_info['date_of_birth'];
      $this->sex=$patient_info['sex'];
      $this->phone_number=$patient_info['phone_number'];
      $this->hse_address=$patient_info['hse_address'];
      $this->town=$patient_info['town'];
      $this->region=$patient_info['region'];
      $this->hometown=$patient_info['hometown'];
      $this->ethnicity=$patient_info['ethnicity'];
      $this->religion=$patient_info['religion'];
      $this->marital_status=$patient_info['marital_status'];
      $this->nearest_relative=$patient_info['nearest_relative'];
      $this->relative_phone=$patient_info['relative_phone'];
      $this->nhis_status=$patient_info['nhis_status'];
      $this->nhis_number=$patient_info['nhis_number'];

      $dob=new DateTime($this->date_of_birth);
      $today=new DateTime($this->today);

      $interval=$dob->diff($today);
      $this->age=$interval->format('%Y y , %M m');

      $check_last_visit=mysqli_query($this->db,"SELECT * FROM visits WHERE subscriber_id='".$this->active_subscriber."' AND patient_id='".$this->patient_id."' AND status='active' ORDER BY visit_date DESC limit 1") or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_last_visit) >0){
        $visit_history=mysqli_fetch_array($check_last_visit);
        $this->last_visit=$visit_history['visit_date'];
      }else {
        $this->last_visit='N/A';
      }


    } //end PatientInfo


    function CreatePatient($service_id,$surname,$othernames,$date_of_birth,$sex,$phone_number,$hse_address,$town,$region,$hometown,$ethnicity,$religion,$marital_status,$nearest_relative,$relative_phone,$payment_mode,$nhis_number){
      $patient_id=$this->PatientIdGen();
      $checkstring=mysqli_query($this->db,"SELECT * FROM patients WHERE
                                                  (patient_id='".$patient_id."' OR phone_number='".$phone_number."') AND subscriber_id='".$this->active_subscriber."'
                                                ") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) > 0){
        return  'A similar patient already exists';
      }
      else {

        $table='patients';
        $fields=array("subscriber_id","patient_id","surname","othernames","date_of_birth","sex","phone_number","hse_address","town","region","hometown","ethnicity","religion","marital_status","nearest_relative","relative_phone","payment_mode","nhis_number","status","date");
        $values=array("$this->active_subscriber","$patient_id","$surname","$othernames","$date_of_birth","$sex","$phone_number","$hse_address","$town","$region","$hometown","$ethnicity","$religion","$marital_status","$nearest_relative","$relative_phone","$payment_mode","$nhis_number","active","$this->today");
        $query=insert_data($this->db,$table,$fields,$values);

        $_SESSION['new_patient_id']=$patient_id;

        // $billing=new Billing();
        // $billing->BillPatient($patient_id,'ignition',$service_id,'1',$this->today);

        return 'save_successful';
      }
    }// end Create



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

          // $billing=new Billing();
          // $billing->BillPatient($patient_id,$visit_id,$service_id,'1',$this->today);

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


    function RequestTest($patient_id,$visit_id,$request_id,$test_id){
      $checkstring=mysqli_query($this->db,"SELECT *
                                                                    FROM lab_requests
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

        $table='lab_requests';
        $fields=array("subscriber_id","request_id","patient_id","visit_id","test_id","test_cost","status","date");
        $values=array("$this->active_subscriber","$request_id","$patient_id","$visit_id","$test_id","$test_cost","active","$this->today");
        $query=insert_data($this->db,$table,$fields,$values);
        return $query;



      }
    }

    function BillLabs($request_id,$patient_id,$visit_id){
      $get_total_bill=mysqli_query($this->db,"SELECT SUM(test_cost) as labs_bill FROM lab_requests WHERE request_id='".$request_id."' AND status='active' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $total_bill=mysqli_fetch_array($get_total_bill);
      $total_bill=$total_bill['labs_bill'];

      $table='labs_billing';
      $fields=array("subscriber_id","patient_id","visit_id","request_id","bill","payment_status","date");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$request_id","$total_bill","pending","$this->today");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }

    function Diagnosis($diagnosis_id){
      $query=mysqli_query($this->db,"SELECT * FROM diagnosis WHERE diagnosis_id='".$diagnosis_id."'") or die(mysqli_error($this->db));
      $diagnosis_info=mysqli_fetch_array($query);
      return $diagnosis_info['description'];
    }


    function VisitBilling($patient_id,$visit_id){
      $get_service_billing=mysqli_query($this->db,"SELECT SUM(total) as service_bill FROM service_billing WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'") or die(mysqli_error($this->db));
      $service_bill=mysqli_fetch_array($get_service_billing);
      $service_bill=$service_bill['service_bill'];

      $get_lab_billing=mysqli_query($this->db,"SELECT SUM(test_cost) as lab_bill FROM lab_requests WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'") or die(mysqli_error($this->db));
      $lab_bill=mysqli_fetch_array($get_lab_billing);
      $lab_bill=$lab_bill['lab_bill'];


      $this->visit_total_bill=$service_bill+$lab_bill;
    }
    function VisitPayment($patient_id,$visit_id){
      $get_visit_payments=mysqli_query($this->db,"SELECT SUM(amount_paid-balance) as paid FROM payments WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND status='active'") or die(mysqli_error($this->db));
      $get_payments=mysqli_fetch_array($get_visit_payments);
      $this->visit_payment=$get_payments['paid'];
    }
    function VisitBalance($patient_id,$visit_id){
      $this->visit_balance=$this->VisitBilling($patient_id,$visit_id) - $this->VisitPayment($patient_id,$visit_id);
    }


  }



 ?>
