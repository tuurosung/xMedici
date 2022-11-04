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

<style media="">
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

<style media="">
  body{
    font-family: "Poppins",sans-serif;
    font-size: 12px !important;
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
</style>

</head>
<body class="" >
  <div class="content" style="width:100% !important; margin-bottom:20px">

    <div class="py-3" style="text-align:center; width:80mm">
      <div class="" style="padding:10px">
        <div class="montserrat text-uppercase" style="font-size:1.2rem; font-weight:bold"><?php echo $hospital->hospital_name; ?></div>
        <div class="text-center font-weight-500 mb-1 poppins" style="font-size:12px"><?php echo $hospital->hospital_address; ?></div>
        <div class="text-center font-weight-500" style="font-size:12px"><?php echo $hospital->hospital_phone; ?></div>
      </div>
    </div>

  </div>
