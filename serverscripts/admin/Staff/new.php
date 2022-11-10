<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Staff.php';

    $staff=new Staff();

    $title=clean_string($_GET['title']);
    $rank=clean_string($_GET['rank']);
    $role=clean_string($_GET['role']);
    $surname=clean_string($_GET['surname']);
    $othernames=clean_string($_GET['othernames']);
    $phone_number=clean_string($_GET['phone_number']);
    $address=clean_string($_GET['address']);
    $emergency_contact_person=clean_string($_GET['emergency_contact_person']);
    $emergency_contact=clean_string($_GET['emergency_contact']);
    $username = clean_string($_GET['username']);
    $password = clean_string($_GET[ 'password']);

    echo  $staff->Create($title, $rank, $role, $surname, $othernames, $phone_number, $address, $emergency_contact_person, $emergency_contact, $username, $password);
    
    
?>