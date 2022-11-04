

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
        <p class="montserrat font-weight-bold mb-4">Accountant</p>
        <a href="index.php" class="list-group-item list-group-item-action active waves-effect">
          <i class="fas fa-paper-plane mr-3"></i>Dashboard
        </a>



            <a href="#accounting_submenu" class="list-group-item list-group-item-action waves-effect " id="accounting_nav" data-toggle="collapse">
              <i class="fas fa-chart-line mr-3"></i>Accounting</a>

              <div class="collapse pl-3" id="accounting_submenu" >
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




      </div>
      <hr class="mt-5" style="border-top:solid 1px #fff;">
      <a href="../index.php" class="list-group-item list-group-item-action waves-effect danger-color-dark white-text" style="position:fixed; bottom:0px; right:0px">
        <i class="fas fa-lock mr-3"></i>Log Out</a>
    </div>
    <!-- Sidebar -->

  </header>
  <!--Main Navigation-->
