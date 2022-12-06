<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<style media="screen">
  .card{
    border-radius: .9rem;
  }
</style>


<?php
  $visit_id=clean_string($_GET['visit_id']);

  $opd=new Visit();
  $opd->VisitInfo($visit_id);

  $patient=new Patient();
  $patient->patient_id=$opd->patient_id;
  $patient->PatientInfo();

  $doc=new Doctor();
  $drug=new Drug();


  $patient_id=$opd->patient_id;
 ?>

 <main class="py-3 mx-lg-5 main">
   <div class="container-fluid mt-2">

     <div class="row">
       <div class="col-md-8 offset-md-2" id="prescription_holder">
         <div class="card">
           <div class="card-body" style="min-height:400px">
             <div class="row">
               <div class="col-md-7">
                 <h3 class="montserrat font-weight-bold mb-1">Cart</h3>
                 <div class="mb-3">#000000</div>
                 <span class="primary-color-dark px-3 py-2 montserrat white-text font-weight-bold" style="border-radius:15px; letter-spacing:2px">
                    - PENDING
                 </span>
               </div>
               <div class="col-md-5">
                 <div class="shadow1 p-3">
                   <div class="mb-2" style="color:#babbbd;">Prescribed For</div>
                   <p class="montserrat font-weight-bold" style="color:#0d47a1"><?php echo $patient->patient_fullname; ?></p>
                   <p><?php echo $patient->hse_address ?></p>
                   <p><?php echo $patient->phone_number; ?></p>
                 </div>

               </div>
             </div>
             <!-- End Row -->

             <div class="row mt-4">
               <div class="col-md-4">
                 <div class="d-flex">
                   <div class="mr-3">
                      <i class="far fa-file-alt fa-2x grey-text" aria-hidden></i>
                   </div>
                   <div class="">
                     <p class="montserrat font-weight-bold grey-text" style="font-size:10px">VISIT DATE</p>
                     <p class="montserrat font-weight-bold"><?php echo date('d - M - Y',strtotime($opd->visit_date)) ?></p>
                   </div>
                 </div>
               </div>
               <div class="col-md-6">
                 <div class="d-flex">
                   <div class="mr-3">
                      <i class="fas fa-user-md fa-2x grey-text" aria-hidden></i>
                   </div>
                   <div class="">
                     <?php
                       $doc->doctor_id=$opd->doctor_id;
                       $doc->DoctorInfo();
                      ?>
                     <p class="montserrat font-weight-bold grey-text" style="font-size:10px">PRESCRIBER</p>
                     <p class="montserrat font-weight-bold"><?php echo $doc->doctor_fullname; ?></p>
                   </div>
                 </div>
               </div>
             </div>

             <section class="my-5">

               <div class="row">
                 <div class="col-md-6">
                   Drug Name
                 </div>
                 <div class="col-md-2">
                   Cost
                 </div>
                 <div class="col-md-2">
                   Qty
                 </div>
                 <div class="col-md-2  text-right">
                   Total
                 </div>
               </div>
               <hr class="mt-2">
               <?php
                  $total=0;
                  $get_cart=mysqli_query($db,"SELECT * FROM pharm_cart WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$active_subscriber."' AND status='active'") or die(mysqli_error($db));
                  while ($cart=mysqli_fetch_array($get_cart)) {
                    $drug->drug_id=$cart['drug_id'];
                    $drug->DrugInfo();
                    ?>
                    <div class="row">
                      <div class="col-md-6">
                        <?php echo $drug->drug_name; ?>
                      </div>
                      <div class="col-md-2">
                        <?php echo $cart['retail_price']; ?>
                      </div>
                      <div class="col-md-2">
                        <?php echo $cart['qty']; ?>
                      </div>
                      <div class="col-md-2 text-right">
                        <?php echo $cart['total']; ?>
                      </div>
                    </div>
                    <hr>
                    <?php
                    $total +=$cart['total'];
                  }
              ?>
              <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-2">

                </div>
                <div class="col-md-4 font-weight-bold text-right montserrat" style="font-size:17px">
                  GHS <?php echo number_format($total,2); ?>
                </div>
              </div>





             </section>

             <form  id="checkout_frm">

               <div class="row d-none">
                 <div class="col-md-4">
                   <div class="form-group">
                     <label for="">Patient ID</label>
                     <input type="text" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
                   </div>
                 </div>
                 <div class="col-md-4">
                   <div class="form-group">
                     <label for="">Visit ID</label>
                     <input type="text" name="visit_id" value="<?php echo $visit_id; ?>" readonly>
                   </div>
                 </div>
                 <div class="col-md-4">
                   <div class="form-group">
                     <label for="">Total Bill</label>
                     <input type="text" name="total_bill" value="<?php echo $total; ?>" readonly>
                   </div>
                 </div>
               </div>

             <div class="text-right mt-3">
               <button type="submit" class="btn primary-color-dark white-text">Checkout <i class="fa fa-arrow-right ml-2"></i></button>
             </div>

           </form>
           </div>
         </div>
       </div>
     </div>









</div>
<div id="modal_holder"></div>

</div>
</main>










</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/footer.php'; ?>

	<script type="text/javascript">

      $('#checkout_frm').on('submit', function(event) {
        event.preventDefault();
        bootbox.confirm("Do you want to addd this bill to patients bill?",function(r){
          if(r===true){
            $.ajax({
              url: '../serverscripts/admin/Dispensary/checkout_frm.php',
              type: 'GET',
              data:$('#checkout_frm').serialize(),
              success:function(msg){
                if(msg==='checkout_successful'){
                  bootbox.alert("Checkout successful. Patient can proceed to make payment at accounts department",function(){
                    window.location='dispensary.php';
                  })
                }else {
                  bootbox.alert(msg)
                }
              }
            })
          }
        })
      });

	</script>

</html>
