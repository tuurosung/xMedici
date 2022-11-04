<?php
  // require_once 'Billing.php';
  /**
   * Expenditure
   */
  class Patient{

    Public $patient_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->mysqli=new mysqli('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici');

      if(!isset($_SESSION['active_subscriber']) || !isset($_SESSION['active_user']) || $_SESSION['active_subscriber']=='' || $_SESSION['active_user']==''){
        die('session_expired');
      }else {
        $this->active_subscriber=$_SESSION['active_subscriber'];
        $this->active_user=$_SESSION['active_user'];
      }

      $this->suffix=date('y');
      $this->today=date('Y-m-d');
      $this->month=date('m');
      $this->year=date('Y');

      $count_patients=mysqli_query($this->db,"SELECT COUNT(*) total_patient_count FROM patients WHERE status='active' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count_patients=mysqli_fetch_array($count_patients);
      $this->total_patient_count=$count_patients['total_patient_count'];

      $new_patients=mysqli_query($this->db,"SELECT COUNT(*) new_patient_count FROM patients WHERE status='active' AND subscriber_id='".$this->active_subscriber."' AND MONTH(date)='".$this->month."' AND YEAR(date) ='".$this->year."'") or die(mysqli_error($this->db));
      $new_patients=mysqli_fetch_array($new_patients);
      $this->new_patient_count=$new_patients['new_patient_count'];
    }

    function PatientIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM patients WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return prefix($count).''.$count.'/'.$this->suffix;
    }

    function PatientInfo(){
      $query=mysqli_query($this->db,"SELECT *
                                                          FROM patients
                                                          WHERE
                                                            patient_id='".$this->patient_id."' AND subscriber_id='".$this->active_subscriber."' AND status!='deleted'
                                        ") or die(mysqli_error($this->db));

      $patient_info=mysqli_fetch_array($query);
      $this->surname=$patient_info['surname'];
      $this->othernames=$patient_info['othernames'];
      $this->patient_fullname=$this->surname .' '.$this->othernames;
      $this->date_of_birth=$patient_info['date_of_birth'];
      $this->sex=$patient_info['sex'];
      $this->phone_number=$patient_info['phone_number'];
      $this->hse_address=$patient_info['hse_address'];
      $this->town=$patient_info['town'];
      $this->region=$patient_info['region'];
      $this->hometown=$patient_info['hometown'];
      $this->occupation=$patient_info['occupation'];
      $this->religion=$patient_info['religion'];
      $this->marital_status=$patient_info['marital_status'];
      $this->nearest_relative=$patient_info['nearest_relative'];
      $this->relative_phone=$patient_info['relative_phone'];
      $this->nhis_status=$patient_info['nhis_status'];
      $this->nhis_number=$patient_info['nhis_number'];
      $this->status=$patient_info['status'];

      if($this->sex=='male'){
        $this->sex_description='M';
      }elseif ($this->sex=='female') {
        $this->sex_description='F';
      }

      $dob=new DateTime($this->date_of_birth);
      $today=new DateTime($this->today);

      $interval=$dob->diff($today);
      $this->age=$interval->format('%Y years, %M months');

      $check_last_visit=mysqli_query($this->db,"SELECT * FROM visits WHERE subscriber_id='".$this->active_subscriber."' AND patient_id='".$this->patient_id."'  ORDER BY visit_date DESC limit 1") or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_last_visit) >0){
        $visit_history=mysqli_fetch_array($check_last_visit);
        $this->last_visit=$visit_history['visit_date'];
      }else {
        $this->last_visit='N/A';
      }

      $count_visits=mysqli_query($this->db,"SELECT COUNT(*) as total_visit_count FROM visits WHERE patient_id='".$this->patient_id."' AND subscriber_id='".$this->active_subscriber."'") or die(msyqli_error($this->db));
      $count_visits=mysqli_fetch_array($count_visits);
      $this->patient_visit_count=$count_visits['total_visit_count'];


    } //end PatientInfo


    function CreatePatient($service_id,$surname,$othernames,$date_of_birth,$sex,$phone_number,$hse_address,$town,$region,$hometown,$occupation,$religion,$marital_status,$nearest_relative,$relative_phone,$payment_mode,$nhis_number){
      $patient_id=$this->PatientIdGen();
      $checkstring=mysqli_query($this->db,"SELECT *
                                                                    FROM patients
                                                                    WHERE
                                                                    patient_id='".$patient_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) > 0){
        return  'A similar patient already exists';
      }
      else {

        $table='patients';
        $fields=array("subscriber_id","patient_id","surname","othernames","date_of_birth","sex","phone_number","hse_address","town","region","hometown","occupation","religion","marital_status","nearest_relative","relative_phone","payment_mode","nhis_number","status","date","user_id");
        $values=array("$this->active_subscriber","$patient_id","$surname","$othernames","$date_of_birth","$sex","$phone_number","$hse_address","$town","$region","$hometown","$occupation","$religion","$marital_status","$nearest_relative","$relative_phone","$nhis_status","$nhis_number","active","$this->today","$this->active_user");
        $query=insert_data($this->db,$table,$fields,$values);

        $_SESSION['new_patient_id']=$patient_id;

        // $billing=new Billing();
        // $billing->BillPatient($patient_id,'ignition',$service_id,'1',$this->today);

        return 'save_successful';
      }
    }// end Create


    function EditPatient($surname,$othernames,$date_of_birth,$sex,$phone_number,$hse_address,$town,$region,$hometown,$occupation,$religion,$marital_status,$nearest_relative,$relative_phone,$payment_mode,$nhis_number){
        $update_query=mysqli_query($this->db,"UPDATE patients
                                                                        SET
                                                                          surname='".$surname."',
                                                                          othernames='".$othernames."',
                                                                          date_of_birth='".$date_of_birth."',
                                                                          sex='".$sex."',
                                                                          phone_number='".$phone_number."',
                                                                          hse_address='".$hse_address."',
                                                                          town='".$town."',
                                                                          religion='".$religion."',
                                                                          hometown='".$hometown."',
                                                                          occupation='".$occupation."',
                                                                          region='".$region."',
                                                                          marital_status='".$marital_status."',
                                                                          nearest_relative='".$nearest_relative."',
                                                                          relative_phone='".$relative_phone."',
                                                                          payment_mode='".$payment_mode."',
                                                                          nhis_number='".$nhis_number."'
                                                                        WHERE
                                                                          subscriber_id='".$this->active_subscriber."' AND patient_id='".$this->patient_id."' AND status='active'
        ") or die(mysqli_error($this->db));
        if(mysqli_affected_rows($this->db)==1){
          return 'update_successful';
        }else {
          return 'Unable to update patient';
        }
    }


    function DeletePatient(){
      $delete_query=mysqli_query($this->db,"UPDATE patients
                                                                      SET
                                                                        status='deleted'
                                                                      WHERE
                                                                        subscriber_id='".$this->active_subscriber."' AND patient_id='".$this->patient_id."' AND status='active'
                                                    ") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete patient';
      }
    }


    function PatientStatus(){
      $query=mysqli_query($this->db,"SELECT *
                                                          FROM patients
                                                          WHERE
                                                            patient_id='".$this->patient_id."' AND subscriber_id='".$this->active_subscriber."'
                                        ") or die(mysqli_error($this->db));
        $status_info=mysqli_fetch_array($query);
        return $status_info['status'];
    }

    function All(){
      $sql="SELECT * FROM patients WHERE status='active' AND subscriber_id='".$this->active_subscriber."'";
      $result=$this->mysqli->query($sql);
      while ($rows=$result->fetch_assoc()) {
        $data[]=$rows;
      }
      return $data;
    }

    function Find($term){
      $sql="SELECT * FROM patients WHERE (surname='".$term."' || othernames='".$term."' || patient_id='".$term."' || phone_number='".$term."') AND subscriber_id='".$this->active_subscriber."'";
      $result=$this->mysqli->query($sql);
      return $result->fetch_assoc();
    }



  }



 ?>
