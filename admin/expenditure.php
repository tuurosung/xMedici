<?php require_once ('../navigation/header.php'); ?>
    <?php require_once ('../navigation/admin_nav.php'); ?>

    <?php
      $e=new Expenditure();
      $acc=new Account();
     ?>

    <main class="pt-3 mx-lg-5">
      <div class="container-fluid mt-2">


        <?php

        $get_accounts=mysqli_query($db,"
        SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
        LEFT JOIN account_headers h on h.sn=a.account_header
        WHERE h.type=5 && subscriber_id='".$active_subscriber."';
        ") or die(mysqli_error($db));

        if(mysqli_num_rows($get_accounts)<1){
          die('<h4>Please create accounts for your expenditure.</h4>');
        }

         ?>

        <div class="row mb-5">
          <div class="col-4">
            <h4  class="titles montserrat">Expenditure</h4>
          </div>
          <div class="col-8">
            <div class="text-right">
              <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#new_expenditure_modal">
                <i class="fas fa-plus mr-3"></i>
                Create Expenditure
              </button>
              <button type="button" class="btn warning-color-dark white-text btn-rounded" >
                <i class="fas fa-print mr-3"></i>
                Print Report
              </button>
            </div>
          </div>
        </div>


        <div class="card mb-5">
          <div class="card-body p-0">
            <!-- Pills navs -->
            <ul class="nav xmedici_pills nav-pills" id="ex1" role="tablist">
              <li class="nav-item" role="presentation">
                <a  class="nav-link active"   id="ex1-tab-1"   data-toggle="pill"   href="#ex1-pills-1"   role="tab"  aria-controls="ex1-pills-1"   aria-selected="true">
                  Expenditure Summary
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a  class="nav-link"  id="ex1-tab-2"  data-toggle="pill"  href="#ex1-pills-2"  role="tab"  aria-controls="ex1-pills-2"  aria-selected="false" >
                  Expenditure History
                </a>
              </li>
              <!-- <li class="nav-item" role="presentation">
                <a  class="nav-link"  id="ex1-tab-3"  data-toggle="pill"  href="#ex1-pills-3"  role="tab"  aria-controls="ex1-pills-3"  aria-selected="false">
                  Expenditure Headers
                </a>
              </li> -->
            </ul>
            <!-- Pills navs -->
          </div>
        </div>


        <!-- Pills content -->
        <div class="tab-content" id="ex1-content">



          <!-- Today's Expenses -->
          <div  class="tab-pane fade show active" id="ex1-pills-1"  role="tabpanel"  aria-labelledby="ex1-tab-1" >
            <div class="card">
              <div class="card-body">
                <h6 class="mb-5">Expenditure Summary</h6>

                <table class="table table-condensed">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Description</th>
                      <th class="text-right">Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i=1;
                      $get_expenditure_summary=mysqli_query($db,"SELECT expenditure_account,SUM(amount) as amount_spent FROM expenditure WHERE date BETWEEN '".$month_start."' AND '".$month_end."' AND subscriber_id='".$active_subscriber."' AND status='active' GROUP BY expenditure_account") or die(mysqli_error($db));
                      while ($rows=mysqli_fetch_array($get_expenditure_summary)) {
                        $acc->account_number=$rows['expenditure_account'];
                        $acc->AccountInfo();
                        ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo $acc->account_name; ?></td>
                          <td class="text-right"><?php echo number_format($rows['amount_spent'],2); ?></td>
                        </tr>
                        <?php
                        $total_amount_spent+=$rows['amount_spent'];
                      }
                     ?>
                     <tr>
                       <td></td>
                       <td></td>
                       <td class="text-right" style="font-size:16px !important; font-weight:500"><?php echo number_format($total_amount_spent,2); ?></td>
                     </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Expenditure Report -->
          <div class="tab-pane fade" id="ex1-pills-2" role="tabpanel" aria-labelledby="ex1-tab-2">

            <h6 class="montserrat mt-5 mb-3 font-weight-bold text-uppercase">All Expenses</h6>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Start Date</label>
                  <input type="text" class="form-control" name="start_date" id="start_date" value="<?php echo $year_start; ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">End Date</label>
                  <input type="text" class="form-control" name="end_date" id="end_date" value="<?php echo $year_end; ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Header</label>
                  <select class="custom-select browser-default" name="header_id" id="header_id">
                      <option value="all">All Headers</option>
                      <?php
                          $get_accounts=mysqli_query($db,"
                          SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
                          LEFT JOIN account_headers h on h.sn=a.account_header
                          WHERE h.type=5 && subscriber_id='".$active_subscriber."';
                          ") or die('failed');
                          while ($accounts=mysqli_fetch_array($get_accounts)) {
                            ?>
                            <option value="<?php echo $accounts['account_number']; ?>"><?php echo $accounts['account_name']; ?></option>
                            <?php
                          }
                       ?>

                  </select>
                </div>
              </div>
            </div>


            <div class="card">
              <div class="card-body">
                <div class="" id="data_holder">
                  <table class="table table-condensed datatables">
                    <thead class="">
                      <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Exp. Account</th>
                        <th>Description</th>
                        <th>Account</th>
                        <th class="text-right">Amount</th>

                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        require_once '../serverscripts/dbcon.php';

                        $query=mysqli_query($db,"SELECT  * FROM expenditure WHERE date BETWEEN '".$year_start."' AND '".$year_end."' AND subscriber_id='".$active_subscriber."' AND status='active' ") or die(mysqli_error($db));
                        $i=1;
                        $total_expenditure=0;
                        while ($rows=mysqli_fetch_array($query)) {


                          $expenditure_account_info=account_info($rows['expenditure_account']);
                          $payment_account_info=account_info($rows['payment_account']);
                            ?>
                            <tr>
                              <td class=""><?php echo $i++; ?></td>
                              <td class=""><?php echo $rows['date']; ?></td>
                              <td class=""><?php echo $expenditure_account_info['account_name']; ?></td>
                              <td class=""><?php echo $rows['description']; ?></td>

                              <td><?php echo $payment_account_info['account_name']; ?></td>
                              <td class="text-right"><?php echo number_format($rows['amount'],2); ?></td>


                              <td class="text-right">
                                <div class="dropdown open">
                                  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Option
                                  </button>
                                  <div class="dropdown-menu b-0 p-0" aria-labelledby="dropdownMenu1">
                                    <ul class="list-group">
                                      <li class="list-group-item edit edit" id="<?php echo $rows['expenditure_id']; ?>">Edit</li>
                                      <li class="list-group-item delete" id="<?php echo $rows['expenditure_id']; ?>">Delete</li>
                                    </ul>
                                  </div>
                                </div>
                              </td>
                            </tr>

                          <?php
                          $total_expenditure+=$rows['amount'];
                        }
                        ?>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="text-right font-weight-bold"><?php echo number_format($total_expenditure,2); ?></td>
                          <td></td>
                        </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>



          </div>

          <!-- Expenditure Headers -->
          <!-- <div class="tab-pane fade" id="ex1-pills-3" role="tabpanel" aria-labelledby="ex1-tab-3">
              Tab 3 content
            </div>
          </div> -->
        <!-- Pills content -->






        <div class="">

        </div>




    </div>



    <div id="new_expenditure_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-side modal-top-right">
        <div class="modal-content">

          <form id="new_expenditure_frm">
          <div class="modal-body">
            <h6 class="font-weight-bold montserrat">New Expense</h6>
            <hr class="hr">
              <div class="form-group d-none">
                <input type="text" class="form-control" id="expenditure_id" name="expenditure_id" value="<?php echo expenditure_idgen(); ?>" placeholder="Expenditure ID" readonly>
              </div>

              <div class="form-group">
                <label for="">Expenditure Header</label>
                <select class="custom-select browser-default" name="expenditure_account" required>
                  <?php
                      $get_accounts=mysqli_query($db,"
                      SELECT subscriber_id,account_header,account_number,account_name,h.type FROM all_accounts a
                      LEFT JOIN account_headers h on h.sn=a.account_header
                      WHERE h.type=5 && subscriber_id='".$active_subscriber."';
                      ") or die('failed');
                      while ($accounts=mysqli_fetch_array($get_accounts)) {
                        ?>
                        <option value="<?php echo $accounts['account_number']; ?>"><?php echo $accounts['account_name']; ?></option>
                        <?php
                      }
                   ?>
                </select>
              </div>



              <div class="form-group">
                <label for="">Expenditure Amount</label>
                <input type="text" class="form-control" id="amount" name="amount" placeholder="" required>
              </div>


              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Payment Account</label>
                    <select class="custom-select browser-default" name="payment_account" required>
                      <?php
                          $get_accounts=mysqli_query($db,"
                          SELECT subscriber_id,account_header,account_number,account_name,h.type,h.sn FROM all_accounts a
                          LEFT JOIN account_headers h on h.sn=a.account_header
                          WHERE h.sn=1 && subscriber_id='".$active_subscriber."';
                          ") or die('failed');
                          while ($accounts=mysqli_fetch_array($get_accounts)) {
                            ?>
                            <option value="<?php echo $accounts['account_number']; ?>"><?php echo $accounts['account_name']; ?></option>
                            <?php
                          }
                       ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Expenditure Date</label>
                    <input type="text" class="form-control" id="expenditure_date" name="expenditure_date" placeholder="" value="<?php echo $today; ?>" required>
                  </div>
                </div>
              </div>


              <div class="form-group">
                <label for="">Narration</label>
                <textarea  class="form-control" id="description" name="description" placeholder=""></textarea>
              </div>



          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Record Expenditure</button>
          </div>
        </form>
        </div>
      </div>
    </div>




<div id="modal_holder">

</div>

</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">
  $('.sidebar-fixed .list-group-item').removeClass('active')
  $('#accounting_nav').addClass('active')
  $('#accounting_submenu').addClass('show')
  $('#expenses_li').addClass('font-weight-bold')

    $('#header_id').select2()


    $('#new_expenditure_modal').on('shown.bs.modal', function(event) {
      event.preventDefault();
      $('#expenditure_date').datepicker()
    });

    $('#start_date,#end_date,#header_id').on('changeDate', function(event) {
      event.preventDefault();
      var start_date=$('#start_date').val()
      var end_date=$('#end_date').val()
      var header_id=$('#header_id').val()
      $.get('../serverscripts/admin/Expenditure/expenditure_report.php?start_date='+start_date+'&end_date='+end_date+'&header_id='+header_id,function(msg){
        $('#data_holder').html(msg)
        $('.report_table').DataTable({
          'sorting':false,
          'paging':false,
          'searching':false,
          language: { search: "" }
        })
      })
    });

    $('#header_id').on('change', function(event) {
      event.preventDefault();
      var start_date=$('#start_date').val()
      var end_date=$('#end_date').val()
      var header_id=$('#header_id').val()
      $.get('../serverscripts/admin/Expenditure/expenditure_report.php?start_date='+start_date+'&end_date='+end_date+'&header_id='+header_id,function(msg){
        $('#data_holder').html(msg)
        $('.report_table').DataTable({
          'sorting':false,
          'paging':false,
          'searching':false,
          language: { search: "" }
        })
      })
    });



    $('#new_expenditure_frm').on('submit', function(event) {
      event.preventDefault();
      bootbox.confirm("Record this expense?",function(r){
        if(r===true){
          $.ajax({
            url: '../serverscripts/admin/Expenditure/new_expenditure_frm.php',
            type: 'GET',
            data:$('#new_expenditure_frm').serialize(),
            success:function(msg){
              if(msg==='save_successful'){
                bootbox.alert('Expenditure Recorded Successfully',function(){
                    window.location.reload();
                })
              }
              else {
                bootbox.alert(msg)
              }
            }//end success
          })//end ajax
        }//end if
      })
    });



    $('.table tbody').on('click', '.delete', function(event) {
      event.preventDefault();
      var expenditure_id=$(this).attr('ID')
      bootbox.confirm("Do you want to delete this expenditure?",function(r){
        if(r===true){
          $.get('../serverscripts/admin/Expenditure/delete_expenditure.php?expenditure_id='+expenditure_id,function(msg){
            if(msg==='delete_successful'){
              bootbox.alert("Expenditure deleted successfully",function(){
                window.location.reload()
              })
            }else {
                bootbox.alert(msg)
            }
          })
        }
      })
    });

    $('.table tbody').on('click', '.edit', function(event) {
      event.preventDefault();
      var expenditure_id=$(this).attr('ID')
      $.get('../serverscripts/admin/Expenditure/edit_expenditure_modal.php?expenditure_id='+expenditure_id,function(msg){
        $('#modal_holder').html(msg)
        $('#edit_expenditure_modal').modal('show')

        $('#edit_expenditure_frm').on('submit', function(event) {
          event.preventDefault();
          bootbox.confirm("Update this expenditure?",function(r){
            if(r===true){
              $.ajax({
                url: '../serverscripts/admin/Expenditure/edit_expenditure_frm.php',
                type: 'GET',
                data:$('#edit_expenditure_frm').serialize(),
                success:function(msg){
                  if(msg==='update_successful'){
                    bootbox.alert("Expenditure information updated successfully",function(){
                      window.location.reload()
                    })
                  }else {
                    bootbox.alert(msg)
                  }
                }
              })//end ajax
            }//end if
          })//end confirm
        });//end submit
      })
    });

	</script>

</html>
