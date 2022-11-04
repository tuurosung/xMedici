

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

          <a href="#pharmacy_submenu" class="list-group-item active list-group-item-action waves-effect" id="pharmacy_nav" data-toggle="collapse">
            <i class="fas fa-pills mr-3"></i>Pharmacy</a>

            <div class="collapse pl-3" id="pharmacy_submenu" >
              <a href="inventory.php" class="list-group-item list-group-item-action waves-effect " id="inventory_li">
                <i class="fas fa-chevron-right mr-3"></i>
                Drug Inventory</a>
              <a href="dispensary.php" class="list-group-item list-group-item-action waves-effect " id="dispensary_li">
                <i class="fas fa-chevron-right mr-3"></i>
                Dispensary</a>
              <a href="pharm_walkin.php" class="list-group-item list-group-item-action waves-effect " id="walkin_li">
                <i class="fas fa-chevron-right mr-3"></i>
                Walk-In</a>
              <a href="pharm_sales.php" class="list-group-item list-group-item-action waves-effect " id="sales_li">
                <i class="fas fa-chevron-right mr-3"></i>
                Sales History</a>
              <!-- <a href="walkin_dispensary.php" class="list-group-item list-group-item-action waves-effect " id="walkin_dispensary_li">
                <i class="fas fa-chevron-right mr-3"></i>
                Walk-Ins</a> -->
              <a href="pharmacy_analytics.php" class="list-group-item list-group-item-action waves-effect " id="tests_li">
                <i class="fas fa-chevron-right mr-3"></i>
                Analytics</a>
            </div>





      </div>
      <hr class="mt-5" style="border-top:solid 1px #fff;">
      <a href="../index.php" class="list-group-item list-group-item-action waves-effect <?php //isadmin(); ?>">
        <i class="fas fa-lock mr-3"></i>Log Out</a>
    </div>
    <!-- Sidebar -->

  </header>
  <!--Main Navigation-->
