<?php

  require_once '../../dbcon.php';
  require_once '../../Classes/OPD.php';

  $visit_id=clean_string($_GET['visit_id']);
  $patient_id=clean_string($_GET['patient_id']);
  $death_notes=clean_string($_GET['death_notes']);
  $doctor=clean_string($_GET['doctor']);

  $opd=new Visit();

  echo $opd->DeceasedPatient($patient_id,$visit_id,$death_notes,$doctor);



 ?>
