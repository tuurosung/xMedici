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
    font-size:11px !important;
  }
  </style>
<body>
  <main class="pt-3 mx-lg-5 pb-5">
    <div class="container-fluid mt-2" id="data_holder">

      <div class="row mb-5">
        <div class="col-md-6">
          <h4 class="titles montserrat">Reports</h4>
        </div>
        <div class="col-md-6">

        </div>
      </div>

      <div class="card my-5">
        <div class="card-body py-5">
          <div class="row">
            <div class="col-6">
              <div class="pr-4">
                <h5 class="montserrat font-weight-bold">Your Performance Metrics</h5>
                <p>How much profit are you making? Are your assets growing faster than your liabilities? Is cash flowing, or getting stuck?</p>
              </div>

            </div>
            <div class="col-6">
              <ul class="list-group">
                <li class="list-group-item b-0 py-4" style="border-top:none; border-left:none; border-right:none; font-size:16px !important;"><a href="reports_incomestatement.php"> Income Statement <i class="fa fa-chevron-right float-right"></i></a></li>
                <li class="list-group-item b-0 px-4" style="border-left:none; border-right:none; font-size:16px !important;"><a href="balance_sheet.php">Balance Sheet <i class="fa fa-chevron-right float-right"></i> </a></li>
                <li class="list-group-item b-0 px-4" style="border-left:none; border-right:none; font-size:16px !important;">Cash Flow <i class="fa fa-chevron-right float-right"></i></li>
              </ul>
            </div>
          </div>
        </div>
      </div>


      <div class="card my-5">
        <div class="card-body py-5">
          <div class="row">
            <div class="col-6">
              <div class="pr-4">
                <h5  class="montserrat font-weight-bold">Customer Metrics</h5>
                <p>Get insights as to customer contribution to revenue. Also keep track of overdue balances.</p>
              </div>

            </div>
            <div class="col-6">
              <ul class="list-group">
                <li class="list-group-item b-0 py-4" style="border-top:none; border-left:none; border-right:none; font-size:16px !important;">Income By Customer   <i class="fa fa-chevron-right float-right"></i></li>
                <li class="list-group-item b-0 py-4" style="border-left:none; border-right:none;font-size:16px !important;">Overdue Invoices <i class="fa fa-chevron-right float-right"></i></li>
              </ul>
            </div>
          </div>
        </div>
      </div>


      <div class="card my-5">
        <div class="card-body py-5">
          <div class="row">
            <div class="col-6">
              <div class="pr-4">
                <h5  class="montserrat font-weight-bold">Vendor Metrics</h5>
                <p>Get insights as to customer contribution to revenue. Also keep track of overdue balances.</p>
              </div>

            </div>
            <div class="col-6">
              <ul class="list-group">
                <li class="list-group-item b-0 py-4" style="border-top:none; border-left:none; border-right:none; font-size:16px !important;">Purchases By Vendor   <i class="fa fa-chevron-right float-right"></i></li>
                <li class="list-group-item b-0 py-4" style="border-left:none; border-right:none;font-size:16px !important;">Aged Payables <i class="fa fa-chevron-right float-right"></i></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="card my-5">
        <div class="card-body py-5">
          <div class="row">
            <div class="col-6">
              <div class="pr-4">
                <h5 class="montserrat font-weight-bold">Beyond the surface</h5>
                <p>How much profit are you making? Are your assets growing faster than your liabilities? Is cash flowing, or getting stuck?</p>
              </div>

            </div>
            <div class="col-6">
              <ul class="list-group">
                <li class="list-group-item b-0 py-4"  style="border-top:none; border-left:none; border-right:none; font-size:16px !important;"> <a href="account_balances.php">Account Balances <i class="fa fa-chevron-right float-right"></i></a></li>
                <li class="list-group-item b-0 px-4" style="border-left:none; border-right:none; font-size:16px !important;"><a href="trial_balance.php"> Trial Balance <i class="fa fa-chevron-right float-right"></i></a></li>
                <li class="list-group-item b-0 px-4" style="border-left:none; border-right:none; font-size:16px !important;">General Ledger <i class="fa fa-chevron-right float-right"></i></li>
              </ul>
            </div>
          </div>
        </div>
      </div>










      <!-- <div class="card mt-5">
        <div class="card-body">
          <h6>Expenditure Report</h6>
          <table class="table table-condensed">
            <thead class="">
              <tr>
                <th>#</th>
                <th>Expenditure Header</th>
                <th class="text-right">Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php

                  // $query=mysqli_query($db,"
                  // SELECT e.header_id,h.header_name, sum(e.amount) as total_expenditure FROM expenditure e
                  // LEFT JOIN expenditure_headers h on e.header_id=h.header_id
                  // WHERE e.date BETWEEN '".$year_start."' AND  '".$year_end."'
                  // GROUP BY e.header_id,h.header_name ORDER BY total_expenditure desc
                  // ") or die(mysqli_error($db));
                  // $i=1;
                  // while ($rows=mysqli_fetch_array($query)) {
                    ?>
                    <tr class="small-text">
                      <td><?php //echo $i++; ?></td>
                      <td><?php //echo $rows['header_name']; ?></td>
                      <td class="text-right"><?php //echo $rows['total_expenditure'] ?></td>
                    </tr>
                    <?php
                  //}
               ?>

            </tbody>
          </table>
        </div>
      </div> -->


    <?php require_once ('../navigation/admin_footer.php'); ?>
    <script type="text/javascript">

    $('.list-group-item').removeClass('active')
    $('#reports_nav').addClass('active')

    $('.tab-item').on('click', function(event) {
      event.preventDefault();
      $('#tab-items > .list-group-item.active').removeClass('active')
      // $(this).addClass('active')
    });

    $('#print_trial_balance').on('click', function(event) {
      event.preventDefault();
      print_popup('print_trial_balance.php')
    });
    $('#print_pl').on('click', function(event) {
      event.preventDefault();
      print_popup('print_pandl.php')
    });


      $('#search_frm').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
          url: '../serverscripts/restadmin/report_search_frm.php',
          type: 'GET',
          data:$('#search_frm').serialize(),
          success:function(msg){
            $('#data_holder').html(msg)
          }
        })
      });
    </script>
</body>
</html>
