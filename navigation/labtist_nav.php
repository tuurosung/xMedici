

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

        <a href="#lab_submenu" class="list-group-item list-group-item-action active waves-effect " id="lab_nav" data-toggle="collapse">
          <i class="fas fa-medkit mr-3"></i>Laboratory</a>

          <div class="collapse pl-3" id="lab_submenu" >
            <a href="queued_tests.php" class="list-group-item list-group-item-action waves-effect " id="queued_tests_li">
              <i class="fas fa-chevron-right mr-3"></i>
              Queued Requests</a>
            <a href="completed_tests.php" class="list-group-item list-group-item-action waves-effect " id="completed_tests_li">
              <i class="fas fa-chevron-right mr-3"></i>
              Completed Requests</a>
            <a href="tests.php" class="list-group-item list-group-item-action waves-effect " id="tests_li">
              <i class="fas fa-chevron-right mr-3"></i>
              Test Catalogue</a>
            <a href="testResultsUnits.php" class="list-group-item list-group-item-action waves-effect " id="units_li">
              <i class="fas fa-chevron-right mr-3"></i>
              Measurement Units</a>
          </div>





      </div>
      <hr class="mt-5" style="border-top:solid 1px #fff;">
      <hr class="mt-5" style="border-top:solid 1px #fff;">
      <a href="../index.php" class="list-group-item list-group-item-action waves-effect danger-color-dark white-text" style="position:fixed; bottom:0px; right:0px">
        <i class="fas fa-lock mr-3"></i>Log Out</a>
    </div>
    <!-- Sidebar -->

  </header>
  <!--Main Navigation-->
