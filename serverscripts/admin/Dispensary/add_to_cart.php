<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Drugs.php';

    $drug_id=clean_string($_GET['drug_id']);
    $patient_id=clean_string($_GET['patient_id']);
    $visit_id=clean_string($_GET['visit_id']);

    $retail_price=clean_string($_GET['retail_price']);
    $qty=clean_string($_GET['qty']);
    $total=clean_string($_GET['total']);

    $pharm_id=$_SESSION['active_user'];

    reject_empty($drug_id);
    reject_empty($patient_id);
    reject_empty($visit_id);
    reject_empty($retail_price);
    reject_empty($qty);
    reject_empty($total);

    $drug=new Drug();
    $query=$drug->AddToCart($patient_id,$visit_id,$drug_id,$retail_price,$qty,$total,$pharm_id);

    echo $query;

 ?>
