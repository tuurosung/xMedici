<?php
    session_start();
    require_once '../../dbcon.php';
    require_once '../../Classes/Patient.php';

    $p=new Patient();

    $patient_id=clean_string($_GET['patient_id']);
    $surname=clean_string($_GET['surname']);
    $othernames=clean_string($_GET['othernames']);
    $date_of_birth=clean_string($_GET['date_of_birth']);
    $sex=clean_string($_GET['sex']);
    $phone_number=clean_string($_GET['phone_number']);
    $hse_address=clean_string($_GET['hse_address']);
    $town=clean_string($_GET['town']);
    $region=clean_string($_GET['region']);
    $hometown=clean_string($_GET['hometown']);
    $ethnicity=clean_string($_GET['ethnicity']);
    $religion=clean_string($_GET['religion']);
    $marital_status=clean_string($_GET['marital_status']);
    $nearest_relative=clean_string($_GET['nearest_relative']);
    $relative_phone=clean_string($_GET['relative_phone']);
    $payment_mode=clean_string($_GET['payment_mode']);
    $nhis_number=clean_string($_GET['nhis_number']);

    reject_empty($surname);
    reject_empty($othernames);
    reject_empty($date_of_birth);
    reject_empty($sex);
    reject_empty($phone_number);
    reject_empty($nearest_relative);
    reject_empty($relative_phone);
    reject_empty($payment_mode);
    reject_empty($nhis_number);
    $p->patient_id=$patient_id;

    $query=$p->EditPatient($surname,$othernames,$date_of_birth,$sex,$phone_number,$hse_address,$town,$region,$hometown,$ethnicity,$religion,$marital_status,$nearest_relative,$relative_phone,$payment_mode,$nhis_number);

    echo $query;

 ?>
