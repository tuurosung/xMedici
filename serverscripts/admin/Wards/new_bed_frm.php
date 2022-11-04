<?php
      require_once '../../dbcon.php';
      require_once '../../Classes/Wards.php';

      $ward_id=clean_string($_GET['ward_id']);
      $bed_type=clean_string($_GET['bed_type']);
      $description=clean_string($_GET['description']);

      reject_empty($ward_id);
      reject_empty($bed_type);
      reject_empty($description);

      $ward=new Ward();

      $query=$ward->CreateBed($ward_id,$bed_type,$description);
      echo $query;

 ?>
