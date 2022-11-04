<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Services.php';
    require_once '../../Classes/Patient.php';

    $admission_id=clean_string($_GET['admission_id']);
    $discharge_date=date('Y-m-d');

    $get_admission_info=mysqli_query($db,"SELECT * FROM admissions WHERE admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
    $admission_info=mysqli_fetch_array($get_admission_info);

    $patient_id=$admission_info['patient_id'];
    $visit_id=$admission_info['visit_id'];
    $ward_id=$admission_info['ward_id'];
    $bed_id=$admission_info['bed_id'];

    // $p=new Patient();
    // $p->patient_id=$patient_id;
    // $p->PatientInfo();

    $accept_discharge=mysqli_query($db,"UPDATE admissions
                                                                          SET
                                                                            discharge_date='".$discharge_date."',
                                                                            admission_status='discharged'
                                                                          WHERE
                                                                            admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."'
                                                        ")  or die(mysqli_error($db));

      if($accept_discharge){

          $update_visit=mysqli_query($db,"UPDATE visits SET admission_status='discharged',status='discharged' WHERE visit_id='".$visit_id."' AND patient_id='".$patient_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
          if($update_visit){
            $update_bed_status=mysqli_query($db,"UPDATE beds SET bed_status='EMPTY' WHERE bed_id='".$bed_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
            if($update_bed_status){
              $update_discharge=mysqli_query($db,"UPDATE admission_discharges SET discharge_status='discharged',discharging_nurse='".$active_user."' WHERE admission_id='".$admission_id."' AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
              echo 'discharge_successful';
            }else {
              echo 'unable to update bed status';
            }
          }else {
            echo "unable to update visit";
          }
        }else {
          echo 'unable to accept discharge';
      }
?>
