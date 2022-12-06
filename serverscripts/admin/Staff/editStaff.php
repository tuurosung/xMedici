<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Staff.php';

    $staff=new Staff();

    $staff_id=clean_string($_GET['staff_id']);
    $title=clean_string($_GET['title']);
    $rank=clean_string($_GET['rank']);
    $role=clean_string($_GET['role']);
    $surname=clean_string($_GET['surname']);
    $othernames=clean_string($_GET['othernames']);
    $phone_number=clean_string($_GET['phone_number']);
    $address=clean_string($_GET['address']);
    $emergency_contact_person=clean_string($_GET['emergency_contact_person']);
    $emergency_contact=clean_string($_GET['emergency_contact']);

    echo  $staff->EditStaff($staff_id,$title, $rank, $role, $surname, $othernames, $phone_number, $address, $emergency_contact_person, $emergency_contact);
    
    
?>