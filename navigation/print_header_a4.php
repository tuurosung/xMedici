<?php
session_start();
require_once '../serverscripts/dbcon.php';
require_once '../serverscripts/Classes/Payments.php';
require_once '../serverscripts/Classes/Billing.php';
require_once '../serverscripts/Classes/Patient.php';
require_once '../serverscripts/Classes/Subscribers.php';

$hospital=new Subscriber();
$hospital->HospitalInfo();

 ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>xMEDICI - The Paperless Clinic </title>

<link href="../mdb/css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="../datatables/datatables.css" rel="stylesheet" media="all">
<link href="../mdb/css/style.css" rel="stylesheet" media="all">
<link href="../mdb/css/style.min.css" rel="stylesheet" media="all">
<link href="../mdb/css/xmedici.css" rel="stylesheet" media="all">
<link href="../mdb/css/dropzone.css" rel="stylesheet" media="all">

<style media="all">
/** Setting margins */
@page { margin: 2cm }

/* Or: */
@page :left {
margin: 1cm;
}

@page :right {
margin: 1cm;
}
/* The first page of a print can be manipulated as well */
@page :first {
  margin: 1cm 2cm;
}

@import url('https://fonts.googleapis.com/css?family=Montserrat:400,500,600|Open+Sans:400,700|Roboto:300,400,500,700&display=swap');


.montserrat{
  font-family: 'Montserrat',sans-serif;
  font-weight:400;
}

.montserrat-600{
  font-family: 'Montserrat',sans-serif;
  font-weight:600;
}
</style>

<style media="all">
  body{
    font-family: "Poppins",sans-serif;
    font-size: 16px !important;
  }
  .mb-1{
    margin-bottom: 7px;
  }
  .mb-2{
    margin-bottom: 12px;
  }
  .my-2{
    margin-top: 15px;
    margin-bottom: 15px;
  }
  .d-flex{
    display:flex;
  }
  table th, table td {
  font-size: 16px !important;
}
.table td, .table th {
  border-top: 1px solid #c6c6c6;
}
</style>

</head>
<body class="">
  <div class="container">
    <div class=" mb-5" >
      <p class="montserrat" style="font-size:30px; font-weight:800"><?php echo $hospital->hospital_name; ?></p>
      <p class="poppins" style="font-size:18px"><?php echo $hospital->hospital_address; ?></p>
      <p class="poppins" style="font-size:18px"><?php echo $hospital->hospital_phone; ?></p>
      <hr style="border-top:1px dashed #000">
    </div>
