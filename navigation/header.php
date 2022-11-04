<?php
session_start();
if(!isset($_SESSION['active_subscriber'])){
  header('Location: ../index.php');
}
ob_start();
require_once '../serverscripts/dbcon.php';

require_once '../serverscripts/Classes/Patient.php';
require_once '../serverscripts/Classes/OPD.php';
require_once '../serverscripts/Classes/Services.php';
require_once '../serverscripts/Classes/Admins.php';
require_once '../serverscripts/Classes/AdminsHr.php';
require_once '../serverscripts/Classes/Doctors.php';
require_once '../serverscripts/Classes/Nurses.php';
require_once '../serverscripts/Classes/Pharmacists.php';
require_once '../serverscripts/Classes/Labtists.php';
require_once '../serverscripts/Classes/Accountants.php';
require_once '../serverscripts/Classes/Tests.php';
require_once '../serverscripts/Classes/Payments.php';
require_once '../serverscripts/Classes/Surgeries.php';
require_once '../serverscripts/Classes/Wards.php';

require_once '../serverscripts/Classes/Invoices.php';
require_once '../serverscripts/Classes/Drugs.php';
require_once '../serverscripts/Classes/Pharmacy.php';
require_once '../serverscripts/Classes/Reports.php';
require_once '../serverscripts/Classes/Expenditure.php';
require_once '../serverscripts/Classes/Banking.php';
require_once '../serverscripts/Classes/Accounts.php';
require_once '../serverscripts/Classes/Suppliers.php';
require_once '../serverscripts/Classes/Subscribers.php';
require_once '../serverscripts/Classes/Staff.php';
require_once '../serverscripts/Classes/Users.php';



$user_id=$_SESSION['active_user'];
$access_level=$_SESSION['access_level'];
$active_subscriber=$_SESSION['active_subscriber'];

$user=new User();
$user->user_id=$user_id;
$user->UserInfo();

$user_prefix=substr($user_id,0,2);
switch ($user_prefix) {
  case 'NR':
    $nurse=new Nurse();
    $nurse->nurse_id=$user_id;
    $nurse->NurseInfo();
    $user_fullname=$nurse->nurse_fullname;
    break;

  case 'DR':
    $nurse=new Nurse();
    $doctor=new Doctor();
    $doctor->doctor_id=$user_id;
    $doctor->DoctorInfo();
    $user_fullname=$doctor->doctor_fullname;
    break;

  case 'AC':
    $accountant=new Accountant();
    $accountant->accountant_id=$user_id;
    $accountant->AccountantInfo();
    $user_fullname=$accountant->accountant_fullname;
    break;
  case 'HR':
    $hr=new Hr();
    $hr->hr_id=$user_id;
    $hr->HrInfo();
    $user_fullname=$hr->hr_fullname;
    break;

  default:
    // code...
    break;
}

// $user_info=mysqli_query($db,"SELECT * FROM users WHERE user_id='".$user_id."' AND subscriber_id='".$active_subscriber."'") or die(msyqli_error($db));
// $user_info=mysqli_fetch_array($user_info);

$hospital=new Subscriber();
$hospital->HospitalInfo();

 ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>xMedici - The Paperless Clinic </title>

<link href="../mdb/css/bootstrap.min.css" rel="stylesheet">
<!-- <link href="../datatables/datatables.css" rel="stylesheet"> -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.5/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.11.5/af-2.3.7/b-2.2.2/cr-1.5.5/date-1.1.2/r-2.2.9/sb-1.3.2/sl-1.3.4/datatables.min.css"/>


<link href="../mdb/css/style.css" rel="stylesheet">
<link href="../mdb/css/style.min.css" rel="stylesheet">
<link href="../mdb/css/xmedici.css" rel="stylesheet">
<link href="../mdb/css/dropzone.css" rel="stylesheet">




<link href="../mdb/css/mdb.css" rel="stylesheet">
<link href="../mdb/css/datepicker.css" rel="stylesheet">
<!-- v5.6.3 -->
<link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!--Icons-->

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>
<body class="">
  <style media="screen">
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

  .montserrat{
    font-family: 'Montserrat', sans-serif;
  }

  .poppins{
    font-family: 'Poppins',sans-serif;
  }
  .OpenSans{
    font-family: 'Open Sans',sans-serif;
  }

  body{
    font-family: 'Open Sans',sans-serif;
  }

  @media (min-width: 1200px) {
        .collapse.dont-collapse-sm {
          display: block;
          height: auto !important;
          visibility: visible;
          height: 100vh !important;
        }
        #topnav{
          visibility: hidden;
        }
    }
  @media (min-width: 1200px) {
        .navbar-toggler {
          visibility: hidden;
        }

    }
    @media (max-width:1200px) {
      .mobileadjust{
        margin-bottom:30px !important;
      }
      .main{
        padding-top: 90px !important;
      }
      .titles{
        font-size:20px;
      }
    }
    label {
        font-family: 'Poppins',sans-serif !important;
        font-weight: 400;
    }
    .list-group-item {
        padding: .5rem 1.0rem;
    }
    .nav-pills .list-group-item.active {
      font-weight: bold;
      color: #fff;
      background-color: #0d47a1;
      border:none;
      border-left:none;
      border-right: none;
    }

    #sidebar .list-group-item.active {
      font-weight: bold;
      color: #fff;
      background-color: #0d47a1;
      border:none;
      border-left:none;
      border-right: none;
    }
  </style>


  <style media="screen">

  </style>

  <!--Main Navigation-->
  <header>
<!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar" id="topnav">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="navbar-brand waves-effect font-weight-bold text-primary" href="" target="_blank">
          xMEDICI - The Paperless Clinic
        </a>

        <!-- Collapse -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebar"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i>
        </button>

        <!-- Links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

          <!-- Left -->
          <!-- <ul class="navbar-nav mr-auto">
            <form class="form-inline" id="search_frm">
              <div class="form-group mr-2">
                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Start Date" required>
              </div>
              <div class="form-group mr-2">
                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="End Date" required>
              </div>
              <button type="submit" class="btn btn-primary btn-sm" style="height:34px">Generate Report</button>
            </form>
          </ul> -->

          <!-- Right -->
          <ul class="navbar-nav nav-flex-icons">
            <li class="nav-item pt-2 mr-5" >Welcome, <strong><?php //echo $user_info['full_name']; ?></strong></li>
            <li class="nav-item">
              <a href="../index.php" class="nav-link border border-light rounded waves-effect"
                target="">
                <i class="fas fa-lock mr-2"></i>Logout
              </a>
            </li>
          </ul>

        </div>

      </div>
    </nav>
    <!-- Navbar -->
