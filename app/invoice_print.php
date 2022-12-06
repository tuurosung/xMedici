<?php
session_start();
ob_start();
require_once '../serverscripts/dbcon.php';

require_once '../serverscripts/Classes/Patient.php';
require_once '../serverscripts/Classes/OPD.php';
require_once '../serverscripts/Classes/Services.php';
require_once '../serverscripts/Classes/Admins.php';
require_once '../serverscripts/Classes/AdminsHr.php';
require_once '../serverscripts/Classes/Doctors.php';
require_once '../serverscripts/Classes/Nurses.php';
require_once '../serverscripts/Classes/Pharmacists.php';
require_once '../serverscripts/Classes/Labtists.php';
require_once '../serverscripts/Classes/Accountants.php';
require_once '../serverscripts/Classes/Tests.php';
require_once '../serverscripts/Classes/Payments.php';
require_once '../serverscripts/Classes/Surgeries.php';
require_once '../serverscripts/Classes/Wards.php';

require_once '../serverscripts/Classes/Invoices.php';
require_once '../serverscripts/Classes/Drugs.php';
require_once '../serverscripts/Classes/Pharmacy.php';
require_once '../serverscripts/Classes/Reports.php';
require_once '../serverscripts/Classes/Expenditure.php';
require_once '../serverscripts/Classes/Accounts.php';
require_once '../serverscripts/Classes/Suppliers.php';
require_once '../serverscripts/Classes/Subscribers.php';

$user_id=$_SESSION['active_user'];
$access_level=$_SESSION['access_level'];
$active_subscriber=$_SESSION['active_subscriber'];

$user_prefix=substr($user_id,0,2);
switch ($user_prefix) {
  case 'NR':
    $nurse=new Nurse();
    $nurse->nurse_id=$user_id;
    $nurse->NurseInfo();
    $user_fullname=$nurse->nurse_fullname;
    break;

  case 'DR':
    $nurse=new Nurse();
    $doctor=new Doctor();
    $doctor->doctor_id=$user_id;
    $doctor->DoctorInfo();
    $user_fullname=$doctor->doctor_fullname;
    break;

  case 'AC':
    $accountant=new Accountant();
    $accountant->accountant_id=$user_id;
    $accountant->AccountantInfo();
    $user_fullname=$accountant->accountant_fullname;
    break;
  case 'HR':
    $hr=new Hr();
    $hr->hr_id=$user_id;
    $hr->HrInfo();
    $user_fullname=$hr->hr_fullname;
    break;

  default:
    // code...
    break;
}

// $user_info=mysqli_query($db,"SELECT * FROM users WHERE user_id='".$user_id."' AND subscriber_id='".$active_subscriber."'") or die(msyqli_error($db));
// $user_info=mysqli_fetch_array($user_info);

$hospital=new Subscriber();
$hospital->HospitalInfo();

 ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>xMedici - The Paperless Clinic </title>

<link href="../mdb/css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="../datatables/datatables.css" rel="stylesheet" media="all">
<link href="../mdb/css/style.css" rel="stylesheet" media="all">
<link href="../mdb/css/style.min.css" rel="stylesheet" media="all">
<link href="../mdb/css/xmedici.css" rel="stylesheet" media="all">
<link href="../mdb/css/dropzone.css" rel="stylesheet" media="all">




<link href="../mdb/css/mdb.css" rel="stylesheet"  media="all">
<link href="../mdb/css/datepicker.css" rel="stylesheet"  media="all">
<link href="../fontawesome/css/all.css" rel="stylesheet"  media="all">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"   media="all"/>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"  media="all">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet"  media="all">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet"  media="all">

<!--Icons-->

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>
<body class="">
  <style media="all">
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

  .montserrat{
    font-family: 'Montserrat', sans-serif;
  }

  .poppins{
    font-family: 'Poppins',sans-serif;
  }
  .lato{
    font-family: 'Lato',sans-serif;
  }

  body{
    font-family: 'Poppins',sans-serif;
  }

  @media (min-width: 1200px) {
        .collapse.dont-collapse-sm {
          display: block;
          height: auto !important;
          visibility: visible;
          height: 100vh !important;
        }
        #topnav{
          visibility: hidden;
        }
    }
  @media (min-width: 1200px) {
        .navbar-toggler {
          visibility: hidden;
        }

    }
    @media (max-width:1200px) {
      .mobileadjust{
        margin-bottom:30px !important;
      }
      .main{
        padding-top: 90px !important;
      }
      .titles{
        font-size:20px;
      }
    }
    label {
        font-family: 'Poppins',sans-serif !important;
        font-weight: 400;
    }
    .list-group-item {
        padding: .5rem 1.0rem;
    }
    .nav-pills .list-group-item.active {
      font-weight: bold;
      color: #fff;
      background-color: #0d47a1;
      border:none;
      border-left:none;
      border-right: none;
    }

    #sidebar .list-group-item.active {
      font-weight: bold;
      color: #fff;
      background-color: #0d47a1;
      border:none;
      border-left:none;
      border-right: none;
    }
  </style>

<?php
$patient=new Patient();
$invoice=new Invoice();

$invoice_id=clean_string($_GET['invoice_id']);
$invoice->invoice_id=$invoice_id;
$invoice->InvoiceInfo();



  // $invoice_info=invoice_info($invoice_id);
  $patient->patient_id=$invoice->patient_id;
  $patient->PatientInfo();
 ?>

<main class="">
  <div class="container-fluid p-0">



    <?php
        if($invoice_info['payment_status']=='paid'){
          ?>
          <span class="badge badge-success px-3 montserrat mb-4" style="font-size:20px">PAID <i class="fas fa-check ml-3"></i></span>
          <?php
        }
     ?>


    <?php
        if($invoice_info['lockstatus']=='locked'){
          ?>
          <div class="card mb-5">
            <div class="card-body">
              <h6 class="font-weight-bold mb-3">Payment</h6>

              <div class="row">
                <div class="col-4 pt-3" style="font-size:16px;">
                  Expected Amount : GHS <?php echo $invoice_info['total']; ?>
                </div>
                <div class="col-4">

                </div>
                <div class="col-4 text-right">
                  <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#new_payment_modal"><i class="fas fa-credit-card mr-3"></i>Record Payment</button>
                </div>
              </div>
            </div>
          </div>
          <?php
        }
     ?>


    <div class="card" style="min-height:297mm">
      <div class="card-body">
          <div class="row mt-3">
            <div class="col-8">
              <h1 class="montserrat font-weight-bold"><?php echo $hospital->hospital_name; ?></h1>
              <h3 class="montserrat font-weight-bold" style="font-size:16px"><?php echo $hospital->hospital_address; ?></h3>
              <h5 class="montserrat font-weight-bold" style="font-size:14px"><?php echo $hospital->phone_numbers; ?></h5 >
            </div>
            <div class="col-4 text-right">
              <h1 class="montserrat mb-3">INVOICE</h1>
            </div>
          </div>
          <hr class="hr">

          <div class="row my-5">
            <div class="col-6">
              <p>Bill To</p>
              <h6 class="montserrat font-weight-bold" style="font-size:16px"><?php echo $patient->patient_fullname; ?></h6>
              <h6 class="" style="font-size:14px"><?php echo $patient->address; ?></h6>
              <h6 class="" style="font-size:14px"><?php echo $patient->phone_number; ?></h6>
            </div>
            <div class="col-6 ">
              <div class="row">
                <div class="col-8 text-right">
                    Invoice #
                </div>
                <div class="col-4 font-weight-bold">
                  <?php echo $invoice->invoice_id ?>
                </div>
              </div>
              <div class="row">
                <div class="col-8 text-right">
                    Invoice Date
                </div>
                <div class="col-4 font-weight-bold">
                  <?php echo $invoice->date_created; ?>
                </div>
              </div>
              <div class="row">
                <div class="col-8 text-right">
                    Due Date
                </div>
                <div class="col-4 font-weight-bold">
                  <?php echo $invoice->due_date; ?>
                </div>
              </div>
            </div>
          </div>

          <table class="table table-condensed">
            <thead>
              <tr>
                <th>#</th>
                <th>Description</th>
                <th>Unit Cost</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i=1;
                $get_invoice_items=mysqli_query($db,"SELECT * FROM invoice_items WHERE subscriber_id='".$active_subscriber."' && invoice_id='".$invoice_id."' && status='active'") or die(mysqli_error($db));
                while ($invoice_items=mysqli_fetch_array($get_invoice_items)) {
                  // $drug_info=drug_info($invoice_items['drug_id']);
                  ?>
                  <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $invoice_items['description']; ?></td>
                    <td><?php echo $invoice_items['unit_cost'] ?></td>
                    <td class="text-center"><?php echo $invoice_items['qty'] ?></td>
                    <td class="text-right"><?php echo $invoice_items['total'] ?></td>
                  </tr>
                  <?php
                }
               ?>

            </tbody>
          </table>

          <div class="row" style="margin-bottom:70px">
            <div class="col-7">
              <div class="p-2" style="border:1px solid #000; min-height:150px">
                <h6 class="font-weight-bold montserrat m-0" style="font-size:16px">Terms & Conditions</h6>
                <hr class="hr">
                <?php
                    $j=1;
                    $get_terms=mysqli_query($db,"SELECT * FROM invoice_terms WHERE subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
                    while ($terms=mysqli_fetch_array($get_terms)) {
                      ?>
                        <p style="font-size:15px"><?php echo $j++. '. '. $terms['description']; ?></p>
                      <?php
                    }
                 ?>
              </div>
            </div>
            <div class="col-5">
              <!-- Subtotal -->
              <div class="row" style="font-size:16px">
                <div class="col-6 text-right">
                    Sub - Total (GHS)
                </div>
                <div class="col-6 font-weight-bold text-right">
                  <?php echo number_format($invoice->sub_total,2); ?>
                </div>
              </div>

              <!-- Taxes -->
              <div class="row">
                <div class="col-6 text-right">
                  VAT(GHS)
                </div>
                <div class="col-6 text-right">
                  <?php echo number_format($invoice->vat_amount,2); ?>
                </div>
              </div>

              <!-- Taxes -->
              <div class="row">
                <div class="col-6 text-right">
                  GetFund(GHS)
                </div>
                <div class="col-6 text-right">
                  <?php echo number_format($invoice->getfund_amount,2); ?>
                </div>
              </div>
              <!-- Taxes -->
              <div class="row">
                <div class="col-6 text-right">
                  NHIL(GHS)
                </div>
                <div class="col-6 text-right">
                  <?php echo number_format($invoice->nhil_amount,2); ?>
                </div>
              </div>

              <!-- Total -->
              <div class="row" style="font-size:16px">
                <div class="col-6 font-weight-bold text-right">
                  TOTAL (GHS)
                </div>
                <div class="col-6 text-right font-weight-bold">
                  <?php echo number_format($invoice->total,2); ?>
                </div>
              </div>

              <section class="" style="margin-top:7rem">
                <hr class="mt-5" style="border-top:dashed 1px #000; width:50%">
                <p class="m-0 font-weight-bold text-uppercase text-center" style="font-size:16px"><?php echo $inv->created_by; ?></p>
              </section>
            </div>
          </div>


          <p class="text-italic text-center font-weight-bold montserrat" style="font-size:16px"><?php echo $inv->tagline; ?></p>

          <hr style="border-top:dashed 1px #000; width:50%" class="mt-4" >

      </div>
    </div>





</div>
<div id="modal_holder"></div>

</div>
</main>



<div id="new_payment_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-side modal-top-right">
    <div class="modal-content">
      <form id="invoice_payment_frm">
      <div class="modal-body">

        <h6 class="font-weight-bold">Record Payment For This Invoice</h6>
        <hr class="hr">

        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="">Customer ID</label>
              <input type="text" class="form-control input-sm" name="customer_id" value="<?php echo $invoice_info['customer_id']; ?>" readonly>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Invoice ID</label>
              <input type="text" class="form-control input-sm" name="invoice_id" value="<?php echo $invoice_id; ?>" readonly>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="">Amount Paid</label>
          <input type="text" class="form-control input-sm" name="amount_paid" id="amount_paid" value="<?php echo $invoice_info['balance_remaining']; ?>" required>
        </div>

        <div class="form-group">
          <label for="">Payment Date</label>
          <input type="text" class="form-control input-sm" name="payment_date" id="payment_date" value="<?php echo $today; ?>" required>
        </div>


        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="">Payment Account</label>
              <select class="custom-select browser-default" name="payment_account" id="payment_account" required>
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
          <div class="col-6">
            <div class="form-group">
              <label for="">Receivables Account</label>
              <select class="custom-select browser-default" name="receivables_account" id="receivables_account" required>
                <?php
                    $get_accounts=mysqli_query($db,"
                    SELECT subscriber_id,account_header,account_number,account_name,h.type,h.sn FROM all_accounts a
                    LEFT JOIN account_headers h on h.sn=a.account_header
                    WHERE h.sn=3 && subscriber_id='".$active_subscriber."';
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



      </div>
      <div class="modal-footer">

        <button  type="button" class="btn btn-white" data-dismiss="modal">
          Close
        </button>

        <button type="submit" class="btn btn-black ">
          Record Payment
        </button>
      </div>
      </form>
    </div>
  </div>
</div>









</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">

      $('#payment_date').datepicker()







      $('#invoice_payment_frm').one('submit', function(event) {
        event.preventDefault();
        bootbox.confirm("Proceed with payment?",function(r){
          if(r===true){

            $.ajax({
              url: '../serverscripts/admin/invoice_payment_frm.php',
              type: 'GET',
              data:$('#invoice_payment_frm').serialize(),
              success: function(msg){
                if(msg==='save_successful'){
                  bootbox.alert("Payment Successful",function(){
                    window.location.reload()
                  })
                }
                else {
                  bootbox.alert(msg)
                }//end if
              }//end success
            }) //end ajax
          }
        })

      }); //end submit




	</script>

</html>
