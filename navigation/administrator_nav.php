<!-- Sidebar -->
<div class="sidebar-fixed position-fixed collapse dont-collapse-sm animated slideInLeft" id="sidebar">

  <a class="logo-wrapper waves-effect">
    <div class="montserrat primary-text" style="font-size:20px; font-weight:bold; letter-spacing:3px">
      xMEDICI
    </div>
    <div class="" style="font-size:10px; font-weight:bold; letter-spacing:1px;">
      THE PAPERLESS CLINIC<br>
      <hr class="hr">
      <p class="text-primary"><?php echo $hospital->hospital_name; ?></p>
    </div>

  </a>



  <div class="list-group list-group-flush sidebar">
    <p class="montserrat font-weight-bold mb-4">ADMINISTRATOR</p>
    <a href="index.php" class="list-group-item list-group-item-action active waves-effect">
      <i class="fas fa-paper-plane mr-3"></i>Dashboard
    </a>

    <a href="#patients_submenu" class="list-group-item list-group-item-action waves-effect " id="patients_nav" data-toggle="collapse">
      <i class="fas fa-users mr-3"></i>Patients
      <i class="fas fa-chevron-right float-right" aria-hidden></i>
    </a>

    <div class="collapse pl-3" id="patients_submenu">
      <a href="findpatient.php" class="list-group-item list-group-item-action waves-effect cursor " id="patients_li">
        <i class="fas fa-chevron-right mr-3"></i>All Patients</a>

      <a href="opd.php" class="list-group-item list-group-item-action waves-effect " id="opd_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Out-Patients</a>

      <a href="inpatients.php" class="list-group-item list-group-item-action waves-effect " id="inpatients_li">
        <i class="fas fa-chevron-right mr-3"></i>
        In-Patients</a>

      <a href="discharges.php" class="list-group-item list-group-item-action waves-effect " id="discharged_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Discharged Patients</a>

      <a href="appointments.php" class="list-group-item list-group-item-action waves-effect " id="appointments_li">
        <i class="fas fa-chevron-right mr-3"></i>
        All Appointments</a>

      <a href="reports_patients.php" class="list-group-item list-group-item-action waves-effect " id="reports_patients_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Reports</a>
    </div>




    <a href="#pharmacy_submenu" class="list-group-item list-group-item-action waves-effect " id="hr_nav" data-toggle="collapse">
      <i class="fas fa-first-aid mr-3"></i>Pharmacy
      <i class="fas fa-chevron-right float-right" aria-hidden></i>
    </a>

    <div class="collapse pl-3" id="pharmacy_submenu">
      <a href="inventory.php" class="list-group-item list-group-item-action waves-effect " id="doctors_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Inventory</a>
      <a href="prescriptions.php" class="list-group-item list-group-item-action waves-effect " id="nurses_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Dispensary</a>
      <!-- <a href="prescriptions.php" class="list-group-item list-group-item-action waves-effect " id="labtists_li">
              <i class="fas fa-chevron-right mr-3"></i>
              Pending Prescriptions</a> -->
      <a href="pharm_sales.php" class="list-group-item list-group-item-action waves-effect " id="lab_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Dispensed Drugs</a>
      <a href="pharmacy_analytics.php" class="list-group-item list-group-item-action waves-effect " id="tests_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Analytics</a>
    </div>


    <!-- Menu Item for Laboratory -->
    <a href="#lab_submenu" class="list-group-item list-group-item-action waves-effect " id="hr_nav" data-toggle="collapse">
      <i class="fas fa-first-aid mr-3"></i>Laboratory <i class="fas fa-chevron-right float-right"></i></a>

    <div class="collapse pl-3" id="lab_submenu">
      <a href="laboratorydashboard.php" class="list-group-item list-group-item-action waves-effect " id="labdashboard_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Dashboard</a>
      <a href="queued_tests.php" class="list-group-item list-group-item-action waves-effect " id="lab_request_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Lab Requests</a>
      <a href="samplecollection.php" class="list-group-item list-group-item-action waves-effect " id="lab_request_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Sample Collection Point</a>
      <a href="tests.php" class="list-group-item list-group-item-action waves-effect " id="doctors_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Test Catalogue</a>
      <a href="labinventory.php" class="list-group-item list-group-item-action waves-effect " id="doctors_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Inventory</a>
    </div>


    <!-- Menu Item for Radiology -->
    <a href="#radio_submenu" class="list-group-item list-group-item-action waves-effect " id="radio_nav" data-toggle="collapse">
      <i class="fas fa-search mr-3"></i>Radiology <i class="fas fa-chevron-right float-right"></i></a>

    <div class="collapse pl-3" id="radio_submenu">
      <a href="radiologydashboard.php" class="list-group-item list-group-item-action waves-effect " id="radiologydashboard_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Dashboard</a>
      <a href="radiology_requests.php" class="list-group-item list-group-item-action waves-effect " id="radiology_requests_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Radiology Requests</a>
    </div>


    <!-- Menu Item for invoices -->
    <a href="#invoices_submenu" class="list-group-item list-group-item-action waves-effect " id="invoices_nav" data-toggle="collapse">
      <i class="far fa-file-alt mr-3"></i>Invoices <i class="fas fa-chevron-right float-right"></i></a>

    <div class="collapse pl-3" id="invoices_submenu">
      <a href="all_invoices.php" class="list-group-item list-group-item-action waves-effect " id="all_invoices_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Invoices</a>
      <a href="invoices.php" class="list-group-item list-group-item-action waves-effect " id="nurses_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Sales Invoices</a>
      <a href="invoice_config.php" class="list-group-item list-group-item-action waves-effect " id="nurses_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Invoice Config.</a>
    </div>



    <!-- Menu Item for accounting -->
    <a href="#accounting_submenu" class="list-group-item list-group-item-action waves-effect " id="accounting_nav" data-toggle="collapse">
      <i class="fas fa-chart-line mr-3"></i>Accounting <i class="fas fa-chevron-right float-right"></i> </a>

    <div class="collapse pl-3" id="accounting_submenu">
      <a href="billing.php" class="list-group-item list-group-item-action waves-effect" id="billing_li">
        <i class="fas fa-chevron-right mr-3"></i>Billing</a>
      <a href="payments.php" class="list-group-item list-group-item-action waves-effect" id="payments_li">
        <i class="fas fa-chevron-right mr-3"></i>Payments</a>
      <a href="expenditure.php" class="list-group-item list-group-item-action waves-effect" id="expenses_li">
        <i class="fas fa-cog mr-3"></i>Expenses</a>
      <a href="banking.php" class="list-group-item list-group-item-action waves-effect" id="deposit_li">
        <i class="fas fa-credit-card mr-3"></i>Bank Deposits</a>
      <a href="chart_of_accounts.php" class="list-group-item list-group-item-action waves-effect" id="accounts_li">
        <i class="fas fa-briefcase mr-3"></i>Chart Of Accounts</a>
    </div>


    <!-- Menu Item for Human Resource -->
    <a href="#hr_submenu" class="list-group-item list-group-item-action waves-effect " id="hr_nav" data-toggle="collapse">
      <i class="fas fa-users mr-3"></i>HR Manager <i class="fas fa-chevron-right float-right"></i></a>

    <div class="collapse pl-3" id="hr_submenu">

      <a href="shift_manager.php" class="list-group-item list-group-item-action waves-effect " id="shifts_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Shift Manager</a>

      <a href="staff.php" class="list-group-item list-group-item-action waves-effect " id="doctors_li">
        <i class="fas fa-chevron-right mr-3"></i>
        All Staff</a>

    </div>


    <!-- Menu Item for Configuration -->
    <a href="#config_submenu" class="list-group-item list-group-item-action waves-effect " id="config_nav" data-toggle="collapse">
      <i class="fas fa-wrench mr-3"></i>Configuration <i class="fas fa-chevron-right float-right"></i></a>

    <div class="collapse pl-3" id="config_submenu">
      <a href="wards.php" class="list-group-item list-group-item-action waves-effect " id="wards_li">
        <i class="fas fa-chevron-right mr-3"></i>
        Wards</a>
    </div>



    <a href="services.php" class="list-group-item list-group-item-action waves-effect">
      <i class="fas fa-cogs mr-3"></i>Services <i class="fas fa-chevron-right float-right"></i></a>


    <!-- Menu Item for My Account -->
    <div class="mt-5">
      <a href="#myaccount_submenu" class="list-group-item list-group-item-action waves-effect " id="myaccount_nav" data-toggle="collapse">
        <i class="far fa-user-circle mr-3"></i>My Account <i class="fas fa-chevron-right float-right"></i></a>

      <div class="collapse pl-3" id="myaccount_submenu">
        <a href="profile.php" class="list-group-item list-group-item-action waves-effect " id="profile_li">
          <i class="fas fa-chevron-right mr-3"></i>
          Profile</a>
        <a href="reset_password.php" class="list-group-item list-group-item-action waves-effect " id="reset_li">
          <i class="fas fa-chevron-right mr-3"></i>
          Reset Password</a>

      </div>

    </div>





  </div>
  <hr class="mt-5" style="border-top:solid 1px #fff;">
  <a href="../index.php" class="list-group-item list-group-item-action waves-effect danger-color-dark white-text" style="position:fixed; bottom:0px; right:0px">
    <i class="fas fa-lock mr-3"></i>Log Out</a>
</div>
<!-- Sidebar -->

</header>
<!--Main Navigation-->