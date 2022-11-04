<?php
      require_once '../../dbcon.php';
      require_once '../../Classes/Wards.php';

      $ward_type=clean_string($_GET['ward_type']);
      $description=clean_string($_GET['description']);

      reject_empty($ward_type);
      reject_empty($description);

      $ward=new Ward();

      $query=$ward->CreateWard($ward_type,$description);
      echo $query;

 ?>
