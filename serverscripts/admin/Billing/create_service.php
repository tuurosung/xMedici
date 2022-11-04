<?php

    require_once '../../dbcon.php';
    require_once '../../Classes/Services.php';

    $description=clean_string($_GET['description']);
    $billing_type=clean_string($_GET['billing_type']);
    $billing_point=clean_string($_GET['billing_point']);
    $billing_cycle=clean_string($_GET['billing_cycle']);
    $service_cost=clean_string($_GET['service_cost']);
    $date=date('Y-m-d');

    $s=new Service();

    $query=$s->CreateService($description,$billing_cycle,$billing_point,$billing_type,$service_cost,$date);
    echo $query;

 ?>
