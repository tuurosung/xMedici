

<!-- Sidebar -->
    <div class="sidebar-fixed position-fixed collapse dont-collapse-sm animated slideInLeft" id="sidebar">

      <a class="logo-wrapper waves-effect">
        <div class="montserrat primary-text" style="font-size:20px; font-weight:bold; letter-spacing:3px">
          xMEDICI
        </div>
        <div class="" style="font-size:10px; font-weight:bold; letter-spacing:1px;">
          THE PAPERLESS CLINIC<br>
          <hr class="hr">
          <?php echo $company_info['company_name']; ?>
        </div>

      </a>

      <div class="list-group list-group-flush sidebar">

        <p class="montserrat font-weight-bold mb-4">DOCTOR / PHYSICIAN</p>

        <a href="doctorsdashboard.php" class="list-group-item list-group-item-action active waves-effect" id="">
          <i class="fas fa-paper-plane  mr-3"></i>Dashboard</a>

        <a href="consulting_room.php" class="list-group-item list-group-item-action waves-effect" id="consulting_room_li">
          <i class="fas fa-stethoscope  mr-3"></i>Consulting Room</a>




        <a href="opd.php" class="list-group-item list-group-item-action waves-effect" id="patients_nav">
          <i class="fas fa-sign-in-alt  mr-3"></i>Out-Patients</a>

        <a href="inpatients.php" class="list-group-item list-group-item-action waves-effect" id="patients_nav">
          <i class="fas fa-sign-in-alt  mr-3"></i>In-Patients</a>

        <a href="discharges.php" class="list-group-item list-group-item-action waves-effect" id="patients_nav">
          <i class="fas fa-sign-out-alt  mr-3"></i>Discharged Patients</a>

      <a href="findpatient.php" class="list-group-item list-group-item-action waves-effect" id="inpatients_nav">
        <i class="fas fa-search  mr-3"></i>Find Patient</a>


          <hr class="" style="border-top:solid 1px #fff; bottom:0px;">
          <a href="../index.php" class="list-group-item list-group-item-action waves-effect danger-color-dark white-text" style="position:fixed; bottom:0px; right:0px">
            <i class="fas fa-lock mr-3"></i>Log Out</a>
    </div>
    <!-- Sidebar -->

  </header>
  <!--Main Navigation-->
