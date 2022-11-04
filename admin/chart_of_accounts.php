<?php require_once ('../navigation/header.php'); ?>
<?php require_once ('../navigation/admin_nav.php'); ?>

<?php
  $year_start=date('Y-m-d',strtotime('first day of January this year'));
  $year_end=date('Y-m-d',strtotime('last day of December this year'));

 ?>
  <style media="screen">
  .col-md-6{
    padding-left: 15px;
    padding-right: 15px;
  }
  table th, table td{
    font-size:15px !important;
  }
  </style>
<body>
  <main class="pt-3 mx-lg-5 pb-5">
    <div class="container-fluid mt-2" id="data_holder">

      <div class="row mb-5">
        <div class="col-6">
          <h4  class="titles montserrat">Chart Of Accounts</h4>
        </div>
        <div class="col-6">
          <div class="text-right">
            <button type="button" class="btn btn-primary btn-rounded m-0" data-toggle="modal" data-target="#new_account_modal"><i class="fas fa-plus mr-3"></i>New Account</button>
          </div>
        </div>
      </div>


      <div class="card mb-5">
        <div class="card-body p-0">
          <!-- Pills navs -->
            <ul class="nav xmedici_pills nav-pills" id="ex1" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="assets_tab"  data-toggle="pill" href="#asset_content" role="tab" aria-controls="asset_content" aria-selected="true">
                  Assets
                  </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="liabilities_tab"  data-toggle="pill" href="#liabilities_content" role="tab" aria-controls="liabilities_content" aria-selected="false">
                  Liabilities
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link"  id="income_tab" data-toggle="pill"  href="#income_content"  role="tab"  aria-controls="income_content"  aria-selected="false">
                  Income
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link"  id="expenses_tab" data-toggle="pill"  href="#expenses_content"  role="tab"  aria-controls="expenses_content"  aria-selected="false">
                  Expenses
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link"  id="equity_tab" data-toggle="pill"  href="#equity_content"  role="tab"  aria-controls="equity_content"  aria-selected="false">
                  Equity
                </a>
              </li>
            </ul>
            <!-- Pills navs -->
        </div>
      </div>


        <!-- Pills content -->
        <div class="tab-content" id="ex1-content">
          <div   class="tab-pane fade show active"    id="asset_content"     role="tabpanel"   aria-labelledby="assets_tab">
            <div class="card">
                <div class="card-body">

                  <h6 class="montserrat font-weight-bold">Asset Accounts</h6>

                  <?php
                      $get_asset_headers=mysqli_query($db,"SELECT * FROM account_headers WHERE type=1") or die(mysqli_error($db));
                      while ($asset_headers=mysqli_fetch_array($get_asset_headers)) {
                        ?>
                        <div class="p-2 grey lighten-4 mb-2 mt-5 font-weight-bold">
                          <?php echo $asset_headers['description']; ?>
                        </div>

                        <?php
                          $get_accounts_belongin_to_headers=mysqli_query($db,"SELECT * FROM all_accounts WHERE account_header='".$asset_headers['sn']."' && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
                          if(mysqli_num_rows($get_accounts_belongin_to_headers)==0){
                            ?>
                            <p class="my-0">You haven't created any accounts with this header</p>
                            <?php
                          }
                          else {
                            while ($accounts=mysqli_fetch_array($get_accounts_belongin_to_headers)) {
                              ?>
                              <div class="px-4">
                                <div class="row">
                                  <div class="col-2">
                                    <a href="account_transactions.php?account_number=<?php echo $accounts['account_number']; ?>">
                                      <?php echo $accounts['account_number']; ?>
                                    </a>

                                  </div>
                                  <div class="col-4">
                                      <?php echo $accounts['account_name']; ?>
                                  </div>
                                  <div class="col-5">
                                    <?php echo $accounts['description']; ?>
                                  </div>
                                  <div class="col-1 text-right">
                                    <i class="fas fa-pen"></i>
                                  </div>
                                </div>
                                <hr class="hr">
                              </div>

                              <?php
                            }
                          }
                         ?>
                        <?php
                      }
                   ?>

                </div>
            </div>
          </div>
          <div class="tab-pane fade" id="liabilities_content" role="tabpanel" aria-labelledby="liabilities_tab">
            <div class="card">
                <div class="card-body">

                  <h6 class="montserrat font-weight-bold">Liabilities Accounts</h6>

                  <?php
                      $get_liabilities_headers=mysqli_query($db,"SELECT * FROM account_headers WHERE type=3") or die(mysqli_error($db));
                      while ($liabilities_headers=mysqli_fetch_array($get_liabilities_headers)) {
                        ?>
                        <div class="p-2 grey lighten-4 mb-2 mt-5 font-weight-bold">
                          <?php echo $liabilities_headers['description']; ?>
                        </div>

                        <?php
                          $get_accounts_belongin_to_headers=mysqli_query($db,"SELECT * FROM all_accounts WHERE account_header='".$liabilities_headers['sn']."'  && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
                          if(mysqli_num_rows($get_accounts_belongin_to_headers)==0){
                            ?>
                            <p class="my-0">You haven't created any accounts with this header</p>
                            <?php
                          }
                          else {
                            while ($accounts=mysqli_fetch_array($get_accounts_belongin_to_headers)) {
                              ?>
                              <div class="px-4">
                                <div class="row">
                                  <div class="col-2">
                                    <?php echo $accounts['account_number']; ?>
                                  </div>
                                  <div class="col-4">
                                      <?php echo $accounts['account_name']; ?>
                                  </div>
                                  <div class="col-5">
                                    <?php echo $accounts['description']; ?>
                                  </div>
                                  <div class="col-1 text-right">
                                    <i class="fas fa-pen"></i>
                                  </div>
                                </div>
                                <hr class="hr">
                              </div>
                              <?php
                            }
                          }
                         ?>
                        <?php
                      }
                   ?>

                </div>
            </div>
          </div>
          <div class="tab-pane fade" id="income_content" role="tabpanel" aria-labelledby="income_tab">
            <div class="card">
                <div class="card-body">

                  <h6 class="montserrat font-weight-bold">Income Accounts</h6>
                  <?php
                      $get_income_headers=mysqli_query($db,"SELECT * FROM account_headers WHERE type=4") or die(mysqli_error($db));
                      while ($income_headers=mysqli_fetch_array($get_income_headers)) {

                        ?>
                        <div class="p-2 grey lighten-4 mb-2 mt-5 font-weight-bold">
                          <?php echo $income_headers['description']; ?>
                        </div>

                        <?php
                          $get_accounts_belongin_to_headers=mysqli_query($db,"SELECT * FROM all_accounts WHERE account_header='".$income_headers['sn']."'  && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
                          if(mysqli_num_rows($get_accounts_belongin_to_headers)==0){
                            ?>
                            <p class="my-0">You haven't created any accounts with this header</p>
                            <?php
                          }
                          else {
                            while ($accounts=mysqli_fetch_array($get_accounts_belongin_to_headers)) {
                              ?>
                              <div class="px-4">
                                <div class="row">
                                  <div class="col-2">
                                    <?php echo $accounts['account_number']; ?>
                                  </div>
                                  <div class="col-4">
                                      <?php echo $accounts['account_name']; ?>
                                  </div>
                                  <div class="col-5">
                                    <?php echo $accounts['description']; ?>
                                  </div>
                                  <div class="col-1 text-right">
                                    <i class="fas fa-pen"></i>
                                  </div>
                                </div>
                                <hr class="hr">
                              </div>
                              <?php
                            }
                          }
                         ?>
                        <?php
                      }
                   ?>
                </div>
            </div>
          </div>
          <div class="tab-pane fade" id="expenses_content" role="tabpanel" aria-labelledby="expenses_tab">
            <div class="card">
                <div class="card-body">

                  <h6 class="montserrat font-weight-bold">Expenses Accounts</h6>

                  <?php
                      $get_expenses_headers=mysqli_query($db,"SELECT * FROM account_headers WHERE type=5") or die(mysqli_error($db));
                      while ($expenses_headers=mysqli_fetch_array($get_expenses_headers)) {

                        ?>
                        <div class="p-2 grey lighten-4 mb-2 mt-5 font-weight-bold">
                          <?php echo $expenses_headers['description']; ?>
                        </div>

                        <?php
                          $get_accounts_belongin_to_headers=mysqli_query($db,"SELECT * FROM all_accounts WHERE account_header='".$expenses_headers['sn']."'  && subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
                          if(mysqli_num_rows($get_accounts_belongin_to_headers)==0){
                            ?>
                            <p class="my-0">You haven't created any accounts with this header</p>
                            <?php
                          }
                          else {
                            while ($accounts=mysqli_fetch_array($get_accounts_belongin_to_headers)) {
                              ?>
                              <div class="px-4">
                                <div class="row">
                                  <div class="col-2">
                                    <?php echo $accounts['account_number']; ?>
                                  </div>
                                  <div class="col-4">
                                      <?php echo $accounts['account_name']; ?>
                                  </div>
                                  <div class="col-5">
                                    <?php echo $accounts['description']; ?>
                                  </div>
                                  <div class="col-1 text-right">
                                    <i class="fas fa-pen"></i>
                                  </div>
                                </div>
                                <hr class="hr">
                              </div>
                              <?php
                            }
                          }
                         ?>
                        <?php
                      }
                   ?>

                </div>
            </div>
          </div>
          <div class="tab-pane fade" id="equity_content" role="tabpanel" aria-labelledby="equity_tab">
            <div class="card">
                <div class="card-body">

                  <h6 class="montserrat font-weight-bold">Equity Accounts</h6>

                </div>
            </div>
          </div>
        </div>
        <!-- Pills content -->





      <div id="new_account_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-side modal-top-right">
          <div class="modal-content">
            <form id="new_account_frm" autocomplete="off">
            <div class="modal-body">
              <h6>New Account</h6>
              <hr class="hr">


              <div class="form-group">
                <label for="">Account Type</label>
                <select class="custom-select browser-default" name="account_header" required>
                  <?php
                      $get_headers=mysqli_query($db,"SELECT * FROM account_headers") or die(mysqli_error($db));
                      while ($header=mysqli_fetch_array($get_headers)) {
                        ?>
                        <option value="<?php echo $header['sn']; ?>"><?php echo $header['description']; ?></option>
                        <?php
                      }
                   ?>

                </select>
              </div>

              <div class="form-group">
                <label for="">Account Name</label>
                <input type="text" class="form-control" name="account_name" value="" required>
              </div>

              <div class="form-group">
                  <label for="">Description</label>
                  <textarea class="form-control" name="description" rows="2" cols="80"></textarea>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
            </form>
          </div>
        </div>
      </div>



    <?php require_once ('../navigation/footer.php'); ?>
    <script type="text/javascript">
    $('.sidebar-fixed .list-group-item').removeClass('active')
    $('#accounting_nav').addClass('active')
    $('#accounting_submenu').addClass('show')
    $('#accounts_li').addClass('font-weight-bold')

    $('#print_trial_balance').on('click', function(event) {
      event.preventDefault();
      print_popup('print_trial_balance.php')
    });

    $('.nav-links').on('click', function(event) {
      $('.nav-links').removeClass('active')
      $('.nav-tabs .nav-links').removeClass('show')
      $(this).addClass('active')
      $(this).prop('aria-selected', 'false');
    });

    $('#print_pl').on('click', function(event) {
      event.preventDefault();
      print_popup('print_pandl.php')
    });

    $('#new_account_frm').on('submit', function(event) {
      event.preventDefault();
      $.ajax({
        url: '../serverscripts/admin/new_account_frm.php',
        type: 'GET',
        data:$('#new_account_frm').serialize(),
        success:function(msg){
          if(msg==='save_successful'){
            bootbox.alert("Account created successfully",function(){
              window.location.reload()
            })
          }
          else {
            bootbox.alert(msg)
          }
        }
      })
    });

    </script>
</body>
</html>
