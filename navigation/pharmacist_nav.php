

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

      


        <a href="#pharmacy_submenu" class="list-group-item list-group-item-action waves-effect " id="hr_nav" data-toggle="collapse">
          <i class="fas fa-first-aid mr-3"></i>Pharmacy
          <i class="fas fa-chevron-right float-right" aria-hidden></i>
        </a>

          <div class="collapse pl-3" id="pharmacy_submenu" >
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












          <!-- Menu Item for My Account -->
          <div class="mt-5">
          <a href="#myaccount_submenu" class="list-group-item list-group-item-action waves-effect " id="myaccount_nav" data-toggle="collapse">
            <i class="far fa-user-circle mr-3"></i>My Account <i class="fas fa-chevron-right float-right"></i></a>

            <div class="collapse pl-3" id="myaccount_submenu" >
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
