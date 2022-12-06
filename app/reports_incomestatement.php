<?php require_once ('../navigation/header.php'); ?>
<?php require_once ('../navigation/admin_nav.php'); ?>

<?php
  $year_start=date('Y-m-d',strtotime('first day of January this year'));
  $year_end=date('Y-m-d',strtotime('last day of December this year'));

  $r=new Report();
  $r->start=$year_start;
  $r->end=$year_end;

  $a=new Account();
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
            <h4 class="titles montserrat">Profit And Loss </h4>
            <p class="m-0">From :<?php echo $year_start. ' To:  '.$year_end; ?></p>
        </div>
        <div class="col-md-6 text-right">
          <button type="button" class="btn btn-primary btn-rounded" id="print_btn">
            <i class="fas fa-print mr-3"></i>
            Print
          </button>
        </div>
      </div>

      <?php
        $r->Income();
       ?>


      <div class="card mb-3">
        <div class="card-body pb-3">
          <h6 class="font-weight-bold montserrat">Profit And Loss Account Statement</h6>
          <hr class="hr">

          <!-- income +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
          <div class="row font-weight-bold text-uppercase">
            <div class="col-md-6">
              Income
            </div>
            <div class="col-md-3 text-right">
              Debit
            </div>
            <div class="col-md-3 text-right">
              Credit
            </div>
          </div>

          <hr class="my-1" style="border-top:solid 1px #000">

          <div class="row mb-1">
            <div class="col-md-6">
              Retail Sales
            </div>
            <div class="col-md-3 text-right">

            </div>
            <div class="col-md-3 text-right">
              <?php echo number_format($r->total_retail_sale,2); ?>
            </div>
          </div>


          <div class="row mb-1">
            <div class="col-md-6">
              Wholesale Sales
            </div>
            <div class="col-md-3">

            </div>
            <div class="col-md-3 text-right">
              <?php echo number_format($r->total_invoice_payments,2); ?>
            </div>
          </div>

          <div class="row mb-1">
            <div class="col-md-6">

            </div>
            <div class="col-md-3">

            </div>
            <div class="col-md-3 text-right">
              <div class="" style="border-top:3px double">

                  <?php echo number_format($r->total_income,2); ?>
              </div>
            </div>
          </div>




          <!-- purchases +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
          <div class="row mt-3 font-weight-bold">
            <div class="col-md-6">
              Cost Of Goods Sold
            </div>
            <div class="col-md-3">

            </div>
            <div class="col-md-3 text-right">

            </div>
          </div>
          <hr class="mt-1" style="border-top:solid 1px #000">

          <div class="row mt-3 mb-1">
            <div class="col-md-6">
              Opening Stock
            </div>
            <div class="col-md-3 text-right">
              0.00
            </div>
            <div class="col-md-3 text-right">

            </div>
          </div>
          <div class="row mb-1">
            <div class="col-md-6">
              Add Purchases
            </div>
            <div class="col-md-3 text-right">
              <?php //echo number_format(purchases_period($year_start,$year_end),2); ?>
            </div>
            <div class="col-md-3 text-right">

            </div>
          </div>
          <div class="row">
            <div class="col-md-6">

            </div>
            <div class="col-md-3 text-right">
              <div class="" style="border-top:3px double; ">
                <?php //echo number_format(purchases_period($year_start,$year_end),2); ?>
              </div>
            </div>
            <div class="col-md-3">

            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              Less Closing Stock
            </div>
            <div class="col-md-3 text-right">
              <?php //echo number_format(purchases_period($year_start,$year_end),2); ?>
            </div>
            <div class="col-md-3 text-right">

            </div>
          </div>

          <div class="row">
            <div class="col-md-6">

            </div>
            <div class="col-md-3 text-right">
              <div class="" style="border-top:3px double; ">
                  <?php
                      //$total_purchases=purchases_period($year_start,$year_end);
                   ?>
                <?php echo number_format($total_purchases,2); ?>
              </div>
            </div>
            <div class="col-md-3 text-right">
              <div class="" style="border-top:3px double; ">
                  <?php //echo number_format($total_income-$total_purchases,2); ?>
              </div>
            </div>
          </div>

          <div class="row mt-2">
            <div class="col-md-6  font-weight-bold">
              GROSS PROFIT
            </div>
            <div class="col-md-3 text-right pt-1">
              ---------------------->
            </div>
            <div class="col-md-3 text-right font-weight-bold">
              <div class="" style="border-bottom:3px double;border-top:double 3px ">
                  <?php
                    //$gross_profit=$total_income - $total_purchases;
                    ?>
                  <?php //echo number_format($gross_profit,2); ?>
              </div>
            </div>
          </div>


            <div class="row mt-5 font-weight-bold">
            <div class="col-md-6">
              LESS EXPENSES
            </div>
            <div class="col-3">

            </div>
            <div class="col-3">

            </div>
          </div>

          <?php
              $r->Expenditure();
           ?>


          <hr class="mt-1" style="border-top:solid 1px #000">

          <?php
          $total_expenses=0;

           $get_expenses=mysqli_query($db,"SELECT  expenditure_account,SUM(amount) as amount
                                                                  FROM expenditure
                                                                  WHERE date BETWEEN '".$year_start."' AND '".$year_end."' && subscriber_id='".$active_subscriber."' AND status='active'
                                                                  GROUP BY
                                                                    expenditure_account
                                                          ") or die(mysqli_error($db));

           while ($expenses=mysqli_fetch_array($get_expenses)) {
             $a->account_number=$expenses['expenditure_account'];
             $a->AccountInfo();

             ?>
             <div class="row mb-1 text-uppercase">
               <div class="col-md-6">
                 <?php echo $a->account_name; ?>
               </div>
               <div class="col-md-3 text-right">
                 <?php echo number_format($expenses['amount'],2); ?>
               </div>
               <div class="col-md-3 text-right">

               </div>
             </div>
             <?php
             $total_expenses+=$expenses['total_expenditure'];
           }
           ?>

           <div class="row">
             <div class="col-md-6">

             </div>
             <div class="col-md-3 text-right">
               <div class="" style="border-top:3px double; ">
                 <?php echo number_format($r->total_expenditure,2); ?>
               </div>
             </div>
             <div class="col-md-3 text-right">
               <div class="" style="border-top:3px double; ">
                   <?php echo number_format($gross_profit,2); ?>
               </div>
             </div>
           </div>

           <div class="row mt-4">
             <div class="col-md-6 font-weight-bold">
               NET PROFIT
             </div>
             <div class="col-md-3 text-right">
               ---------------------->
             </div>
             <div class="col-md-3 text-right font-weight-bold">
               <div class="" style="border-top:3px double; border-bottom:3px double">
                   <?php echo number_format($r->net_profit,2); ?>
               </div>
             </div>
           </div>

        </div>
      </div>



    <?php require_once ('../navigation/footer.php'); ?>
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
