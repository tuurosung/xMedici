<?php require_once ('../navigation/header.php'); ?>
    <?php require_once ('../navigation/admin_nav.php'); ?>

    <?php
      $account_number=clean_string($_GET['account_number']);
      $acc=new Account();
      $acc->account_number=$account_number;
      $acc->AccountInfo();

      $patient=new Patient();
      $billing=new Billing();
     ?>

    <main class="pt-3 pb-5 mx-lg-5">
      <div class="container-fluid mt-2">

        <div class="row mb-3">
          <div class="col-md-4">
              <h4 class="titles montserrat">Account Transactions  <?php echo $account_number; ?></h4>
              <p><?php echo $acc->account_name; ?> - <?php echo $account_number; ?></p>
          </div>
          <div class="col-6">
            <form class="form-inline" id="account_statement_frm">
              <div class="form-group mr-3">
                <input type="text" class="form-control" style="background-color:#e1e1e1 !important" id="start_date" placeholder="<?php echo $month_start; ?>">
              </div>
              <div class="form-group mr-3">
                <input type="text" class="form-control" id="end_date" placeholder="<?php echo $month_end; ?>">
              </div>
              <!-- <button type="submit" class="btn btn-primary btn-rounded">Generate Report</button> -->
            </form>
          </div>

          <div class="col-2 text-right">
            <!-- <button type="button" class="btn btn-primary m-0 mr-3 btn-rounded">
              <i class="fas fa-plus mr-2"></i>
              New Transaction
            </button> -->
            <button type="button" class="btn btn-primary m-0 btn-rounded">
              <i class="fas fa-plus mr-2"></i>
              Print Statement
            </button>
          </div>
        </div>

        <div class="info-boxes mb-4 px-4 py-3 mb-5">
          <div class="row">
            <div class="col-2">
              Total Revenue
              <h4 class="big-text m-0 montserrat" style="font-size:20px">GHS <?php echo number_format($acc->total_inflow,2); ?></h4>
            </div>
            <div class="col-2">
              Total Inbound Transfers
              <h4 class="big-text m-0 montserrat" style="font-size:20px">GHS <?php echo number_format($acc->total_transfers_received,2); ?></h4>
            </div>
            <div class="col-2">
              Total Expenditure
              <h4 class="big-text m-0 montserrat" style="font-size:20px">GHS <?php echo number_format($acc->total_expenditure,2); ?></h4>
            </div>
            <div class="col-2">
              Total Outbound
              <h4 class="big-text m-0 montserrat" style="font-size:20px">GHS <?php echo number_format($acc->total_transfers,2); ?></h4>
            </div>
            <div class="col-2">
              Balance
              <h4 class="big-text m-0 montserrat" style="font-size:20px">GHS <?php echo number_format($acc->account_balance,2); ?></h4>
            </div>

          </div>
        </div>

        <div class="card mb-5">
          <div class="card-body p-0">
            <!-- Pills navs -->
              <ul class="nav nav-pills xmedici_pills" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="ex1-tab-1" data-toggle="pill" href="#ex1-pills-1" role="tab" aria-controls="ex1-pills-1" aria-selected="true">
                    Payments Received</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="ex1-tab-2" data-toggle="pill" href="#ex1-pills-2" role="tab" aria-controls="ex1-pills-2" aria-selected="false">
                    Inbound Transfers</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="ex1-tab-3" data-toggle="pill" href="#ex1-pills-3" role="tab" aria-controls="ex1-pills-3" aria-selected="false" >
                    Expenditure History</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="ex1-tab-4" data-toggle="pill" href="#ex1-pills-4" role="tab" aria-controls="ex1-pills-4" aria-selected="false" >
                    Outbound Transfers</a>
                </li>
              </ul>
              <!-- Pills navs -->
          </div>
        </div>


          <!-- Pills content -->
          <div class="tab-content" id="ex1-content">
            <div class="tab-pane fade show active" id="ex1-pills-1" role="tabpanel" aria-labelledby="ex1-tab-1">
              <div class="card mb-5">
                <div class="card-body">
                  <h6 class="mb-5" style="">Payments Received</h5>

                    <table class="table table-condensed">
                      <thead>
                        <tr>
                          <th>Date / Time</th>
                          <th>Description</th>
                          <th class="text-right">Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          (float) $total_paid=0;
                          $get_payments=mysqli_query($db,"SELECT * FROM payments WHERE payment_account='".$account_number."' AND ( date BETWEEN '".$month_start."' AND '".$month_end."') AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));

                          while ($rows=mysqli_fetch_array($get_payments)) {
                            if($rows['patient_id']!='Walkin'){
                              $patient->patient_id=$rows['patient_id'];
                              $patient->PatientInfo();
                              $fullname=$patient->patient_fullname;
                            }elseif ($rows['patient_id']=='Walkin') {
                              $fullname='Walkin';
                            }

                            $billing->bill_id=$rows['bill_id'];
                            $billing->BillInfo();

                            ?>
                            <tr>
                              <td><?php echo $rows['date']; ?> - <?php echo date('H:i:s',$rows['timestamp']); ?></td>
                              <td>
                                <?php echo $fullname .' - '. $billing->narration; ?>
                              </td>
                              <td class="text-right"><?php echo number_format($rows['amount_paid'],2); ?></td>
                            </tr>
                            <?php
                            $total_paid+=$rows['amount_paid'];
                            // $total_balance+=$rows['balance'];
                            // $update_balance=mysqli_query($db,"UPDATE ledger_transactions SET balance=balance+$debit WHERE sn='".$sn."'") or die(mysqli_error($db));
                            // $update_balance=mysqli_query($db,"UPDATE ledger_transactions SET balance=balance+$credit WHERE sn='".$sn."'") or die(mysqli_error($db));
                          }
                         ?>
                         <tr class="" >
                           <td colspan="2"></td>
                           <td class="text-right" style="font-size:16px !important; font-weight:500"><?php echo number_format($total_paid,2); ?></td>
                         </tr>
                      </tbody>
                    </table>

                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="ex1-pills-2" role="tabpanel" aria-labelledby="ex1-tab-2">
              <div class="card mb-5">
                <div class="card-body">
                  <h6 class="mb-5">Inbound Transfers</h5>

                    <table class="table table-condensed">
                      <thead>
                        <tr>
                          <th>Date / Time</th>
                          <th>Description</th>
                          <th>Deposit Account</th>
                          <th class="text-right">Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        (float) $total_deposits=0;
                          $get_deposits=mysqli_query($db,"SELECT * FROM banking WHERE deposit_account='".$account_number."' AND ( date BETWEEN '".$month_start."' AND '".$month_end."') AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
                          while ($rows=mysqli_fetch_array($get_deposits)) {
                            $acc->account_number=$rows['deposit_account'];
                            $acc->AccountInfo();
                            ?>
                            <tr>
                              <td><?php echo $rows['date']; ?> - <?php echo date('H:i:s',$rows['timestamp']); ?></td>
                              <td><?php echo $rows['narration']; ?></td>
                              <td><?php echo $acc->account_name; ?></td>
                              <td class="text-right"><?php echo number_format($rows['amount'],2); ?></td>
                            </tr>
                            <?php
                            $total_deposits+=$rows['amount'];
                            // $total_balance+=$rows['balance'];
                            // $update_balance=mysqli_query($db,"UPDATE ledger_transactions SET balance=balance+$debit WHERE sn='".$sn."'") or die(mysqli_error($db));
                            // $update_balance=mysqli_query($db,"UPDATE ledger_transactions SET balance=balance+$credit WHERE sn='".$sn."'") or die(mysqli_error($db));
                          }
                         ?>
                         <tr class="">
                           <td colspan="3"></td>
                            <td class="text-right" style="font-size:16px !important; font-weight:500"><?php echo number_format($total_deposits,2); ?></td>
                         </tr>
                      </tbody>
                    </table>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="ex1-pills-3" role="tabpanel" aria-labelledby="ex1-tab-3">
              <div class="card mb-5">
                <div class="card-body">
                  <h6 class="mb-5">Expenditure History</h6>
                    <table class="table table-condensed">
                      <thead>
                        <tr>
                          <th>Date / Time</th>
                          <th>Description</th>
                          <th class="text-right">Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          (float) $total_expenditure=0;
                          $get_payments=mysqli_query($db,"SELECT * FROM expenditure WHERE payment_account='".$account_number."' AND ( date BETWEEN '".$month_start."' AND '".$month_end."') AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
                          while ($rows=mysqli_fetch_array($get_payments)) {

                            ?>
                            <tr>
                              <td><?php echo $rows['date']; ?> - <?php echo date('H:i:s',$rows['timestamp']); ?></td>
                              <td><?php echo $rows['description']; ?></td>
                              <td class="text-right"><?php echo $rows['amount']; ?></td>
                            </tr>
                            <?php
                            $total_expenditure+=$rows['amount'];
                            // $total_balance+=$rows['balance'];
                            // $update_balance=mysqli_query($db,"UPDATE ledger_transactions SET balance=balance+$debit WHERE sn='".$sn."'") or die(mysqli_error($db));
                            // $update_balance=mysqli_query($db,"UPDATE ledger_transactions SET balance=balance+$credit WHERE sn='".$sn."'") or die(mysqli_error($db));
                          }
                         ?>
                         <tr class="">
                           <td><?php echo $rows['date']; ?></td>
                           <td><?php echo $rows['description']; ?></td>
                            <td class="text-right" style="font-size:16px !important; font-weight:500"><?php echo number_format($total_expenditure,2); ?></td>
                         </tr>
                      </tbody>
                    </table>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="ex1-pills-4" role="tabpanel" aria-labelledby="ex1-tab-4">
              <div class="card mb-5">
                <div class="card-body">
                  <h6 class="mb-5">Outbound Transfers</h6>

                    <table class="table table-condensed">
                      <thead>
                        <tr>
                          <th>Date / Time</th>
                          <th>Description</th>
                          <th>Deposit Account</th>
                          <th class="text-right">Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        (float) $total_deposits=0;
                          $get_deposits=mysqli_query($db,"SELECT * FROM banking WHERE source_account='".$account_number."' AND ( date BETWEEN '".$month_start."' AND '".$month_end."') AND subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
                          while ($rows=mysqli_fetch_array($get_deposits)) {
                            $acc->account_number=$rows['deposit_account'];
                            $acc->AccountInfo();
                            ?>
                            <tr>
                              <td><?php echo $rows['date']; ?> - <?php echo date('H:i:s',$rows['timestamp']); ?></td>
                              <td><?php echo $rows['narration']; ?></td>
                              <td><?php echo $acc->account_name; ?></td>
                              <td class="text-right"><?php echo number_format($rows['amount'],2); ?></td>
                            </tr>
                            <?php
                            $total_deposits+=$rows['amount'];
                            // $total_balance+=$rows['balance'];
                            // $update_balance=mysqli_query($db,"UPDATE ledger_transactions SET balance=balance+$debit WHERE sn='".$sn."'") or die(mysqli_error($db));
                            // $update_balance=mysqli_query($db,"UPDATE ledger_transactions SET balance=balance+$credit WHERE sn='".$sn."'") or die(mysqli_error($db));
                          }
                         ?>
                         <tr class="font-weight-bold">
                           <td colspan="3"></td>
                            <td class="text-right" style="font-size:16px !important; font-weight:500"><?php echo number_format($total_deposits,2); ?></td>
                         </tr>
                      </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
          <!-- Pills content -->




    </div>



    <div id="new_category_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-side modal-top-right" style="width:300px">
        <div class="modal-content">
          <div class="modal-header info-color-dark">
            <h4 class="modal-title white-text" id="myModalLabel">New Category</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="new_expenditure_header_frm">
          <div class="modal-body">

            <!-- <div class="form-group">
              <label for="" class="label">Header ID</label>
              <input type="text" class="form-control" name="header_id" id="header_id">
            </div> -->

            <div class="form-group">
              <label for="" class="label">Header Name</label>
              <input type="text" class="form-control" name="header_name" id="header_name">
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary"> Create Category </button>
          </div>
          </form>
        </div>
      </div>
    </div>









    <div id="modal_holder">

    </div>
    <?php require_once ('../navigation/footer.php'); ?>


    <script type="text/javascript">

      $('#date').datepicker()
      $('.list-group-item').removeClass('active')
      $('#reports_nav').addClass('active')

      $('#date').on('change', function(event) {
        event.preventDefault();
        $(this).datepicker('hide')
      });



      $('.accounts').on('click', function(event) {
        event.preventDefault();
        var account_number=$(this).attr('ID')
        $.ajax({
          url: '../serverscripts/restadmin/account_history.php?account_number='+account_number,
          type: 'GET',
          success:function(msg){
            $('#data_holder').html(msg)
            $('html, body').animate({
                scrollTop: $("#data_holder").offset().top
            }, 2000);

          }
        })
      });



      $('.transfer_funds').on('click', function(event) {
        event.preventDefault();
        var account_number=$(this).attr("ID")
        $.ajax({
          url: '../serverscripts/restadmin/transfer_funds_modal.php?account_number='+account_number,
          type: 'GET',
          success:function(msg){
              $('#modal_holder').html(msg)
              $('#transfer_funds_modal').modal('show')

              $('#transfer_funds_frm').on('submit', function(event) {
                event.preventDefault();
                bootbox.confirm('Do you want to proceed with this transaction?',function(r){
                  if(r===true){
                    $.ajax({
                      url: '../serverscripts/restadmin/transfer_funds_frm.php',
                      type: 'GET',
                      data:$('#transfer_funds_frm').serialize(),
                      success:function(msg){
                        if(msg==='save_successful'){
                          bootbox.alert('Transfer Successful',function(){
                            window.location.reload()
                          })
                        }
                        else {
                          bootbox.alert(msg)
                        }
                      }
                    })
                  }
                })
              });
          }
        })
      });

    </script>
</body>
</html>
