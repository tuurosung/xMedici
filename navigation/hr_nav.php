

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
        <p class="montserrat font-weight-bold mb-4">Human Resource</p>
        <a href="index.php" class="list-group-item list-group-item-action active waves-effect">
          <i class="fas fa-paper-plane mr-3"></i>Dashboard
        </a>

        <a href="#patients_submenu" class="list-group-item list-group-item-action waves-effect " id="patients_nav" data-toggle="collapse">
          <i class="fas fa-users mr-3"></i>Patients</a>

          <div class="collapse pl-3" id="patients_submenu" >
          
            <a href="findpatient.php" class="list-group-item list-group-item-action waves-effect " id="nurses_li">
              <i class="fas fa-chevron-right mr-3"></i>
              Find Patient</a>
            <a href="inpatients.php" class="list-group-item list-group-item-action waves-effect " id="inpatients_li">
              <i class="fas fa-chevron-right mr-3"></i>
              In-Patients</a>
            <a href="opd.php" class="list-group-item list-group-item-action waves-effect " id="opd_li">
              <i class="fas fa-chevron-right mr-3"></i>
              Out-Patients</a>
            <a href="discharges.php" class="list-group-item list-group-item-action waves-effect " id="tests_li">
              <i class="fas fa-chevron-right mr-3"></i>
              Discharged Patients</a>
          </div>






        <a href="#invoices_submenu" class="list-group-item list-group-item-action waves-effect " id="invoices_nav" data-toggle="collapse">
          <i class="far fa-file-alt mr-3"></i>Invoices</a>

          <div class="collapse pl-3" id="invoices_submenu" >
            <a href="all_invoices.php" class="list-group-item list-group-item-action waves-effect " id="all_invoices_li">
              <i class="fas fa-chevron-right mr-3"></i>
              All Invoices</a>
            <a href="dispensary.php" class="list-group-item list-group-item-action waves-effect " id="nurses_li">
              <i class="fas fa-chevron-right mr-3"></i>
              Dispensary</a>
            <a href="prescriptions.php" class="list-group-item list-group-item-action waves-effect " id="labtists_li">
              <i class="fas fa-chevron-right mr-3"></i>
              Pending Prescriptions</a>
          </div>






          <a href="#hr_submenu" class="list-group-item list-group-item-action waves-effect " id="hr_nav" data-toggle="collapse">
            <i class="fas fa-users mr-3"></i>HR Manager</a>

            <div class="collapse pl-3" id="hr_submenu" >

              <a href="shift_manager.php" class="list-group-item list-group-item-action waves-effect " id="shifts_li">
                <i class="fas fa-chevron-right mr-3"></i>
                Shift Manager</a>

              <a href="staff.php" class="list-group-item list-group-item-action waves-effect " id="doctors_li">
                <i class="fas fa-chevron-right mr-3"></i>
                All Staff</a>

            </div>


          <a href="#config_submenu" class="list-group-item list-group-item-action waves-effect " id="config_nav" data-toggle="collapse">
            <i class="fas fa-wrench mr-3"></i>Configuration</a>

            <div class="collapse pl-3" id="config_submenu" >
              <a href="wards.php" class="list-group-item list-group-item-action waves-effect " id="wards_li">
                <i class="fas fa-chevron-right mr-3"></i>
                Wards</a>
            </div>






      </div>
      <hr class="mt-5" style="border-top:solid 1px #fff;">
      <a href="../index.php" class="list-group-item list-group-item-action waves-effect danger-color-dark white-text" style="position:fixed; bottom:0px; right:0px">
        <i class="fas fa-lock mr-3"></i>Log Out</a>
    </div>
    <!-- Sidebar -->

  </header>
  <!--Main Navigation-->
