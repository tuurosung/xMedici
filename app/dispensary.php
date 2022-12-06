<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<style media="screen">
  .card{
    border-radius: .9rem;
  }
</style>


<?php

  $patient=new Patient();
  $opd=new Visit();
  $drug=new Drug();
 ?>

 <main class="py-3 mx-lg-5">
   <div class="container-fluid mt-2">

     <div class="row mb-5">
       <div class="col-6">
         <h4 class="titles montserrat">Drug Matrix </h4>
       </div>
     </div>



    <div class="row">


     <?php
      $get_prescriptions=mysqli_query($db,"SELECT patient_id,visit_id,COUNT(*) as count FROM  prescriptions  WHERE subscriber_id='".$active_subscriber."' AND status='active'  AND dispensary_status='PENDING'  GROUP BY visit_id") or die(mysqli_error($db));

      if(mysqli_num_rows($get_prescriptions) >0){

      while ($rows=mysqli_fetch_array($get_prescriptions)) {
        $visit_id=$rows['visit_id'];
        $patient_id=$rows['patient_id'];

        $opd->VisitInfo($visit_id);
        $patient->patient_id=$opd->patient_id;
        $patient->PatientInfo();

        if($opd->visit_status=='discharged'){
          continue;
        }
        ?>
        <div class="col-3">
          <div class="card mb-4" style="min-height:300px">
            <div class="card-body">

              <div class="row">
                <div class="col-6"></div>
                <div class="col-6"><?php echo $rows['date']; ?></div>
              </div>

              <h6 class="montserrat " style="font-weight:600"><?php echo $patient->patient_fullname; ?></h6>
              <hr>

              <div class="px-3" style="overflow-y:auto; height:200px">

                <ol class="list-group">


                <?php

                 $load_prescriptions=mysqli_query($db,"SELECT * FROM prescriptions WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$active_subscriber."' AND status='active' AND dispensary_status='PENDING'") or die(mysqli_error($db));

                         while ($list=mysqli_fetch_array($load_prescriptions)) {
                           $drug->drug_id=$list['drug_id'];
                           $drug->DrugInfo();
                           ?>
                           <li class="">
                             <p class="montserrat " style="font-size:12px"><span class="font-weight-bold mr-3"><?php echo $drug->drug_name; ?></span> <span class="poppins" style="font-style:italic"><?php echo $list["qty"] . ' '.$list["frequency"].' '.$list["duration"] .' '.$list["route"].' '.$list["notes"]; ?> </span> </p>
                           </li>
                           <hr style="border-top:dashed 1px #000">
                           <?php
                         }
                 ?>

               </ol>
              </div>
              <hr>
              <button type="button" class="btn btn-primary float-right  pending_prescriptions my-1" id="<?php echo $rows['visit_id']; ?>">
                <i class="fas fa-signature mr-2" aria-hidden></i>
                Dispense</button>
            </div>
          </div>
        </div>


        <?php
          }
        }else {
          ?>
          <p>No Pending Prescriptions</p>
          <?php
        }
      ?>


</div>
     <div class="row d-none">
       <div class="col-md-4">
         <div class="card mb-3">
           <div class="card-body">
             <h6 class="montserrat font-weight-bold">Pending Prescriptions</h6>
           </div>
         </div>

         <?php
          $get_prescriptions=mysqli_query($db,"SELECT *,count(*) as count
                                                                        FROM
                                                                          prescriptions
                                                                        WHERE
                                                                          subscriber_id='".$active_subscriber."' AND status='active'  AND dispensary_status='PENDING'
                                                                        GROUP BY visit_id
                                                            ") or die(mysqli_error($db));
          if(mysqli_num_rows($get_prescriptions) >0){


          while ($rows=mysqli_fetch_array($get_prescriptions)) {
            $visit_id=$rows['visit_id'];
            $opd->VisitInfo($visit_id);
            $patient->patient_id=$opd->patient_id;
            $patient->PatientInfo();
            $fullname=$patient->othernames;
            ?>
            <div class="card mb-3 cursor pending_prescriptions" id="<?php echo $rows['visit_id']; ?>">
              <div class="card-body holder-cards">
                <div class="row">
                  <div class="col-2">
                    <div class="avatar d-flex justify-content-center align-items-center font-weight-bold white-text montserrat font-weight-bold">
                      <?php echo $fullname[0]; ?>

                    </div>
                  </div>
                  <div class="col-8 montserrat" >
                    <p class="montserrat m-0" style="font-weight:600; font-size:12px"><?php echo $patient->patient_fullname; ?></p>
                    <span class="text-primary" style="font-weight:bold; font-size:10px">- PENDING</span>
                  </div>
                  <div class="col-2 d-flex justify-content-center align-items-center">
                    <i class="fas fa-ellipsis-v" aria-hidden style="color:#f4f5f7"></i>
                  </div>
                </div>
                <!-- End Row -->
              </div>
            </div>
            <?php
              }
            }else {
              ?>
              <p>No Pending Prescriptions</p>
              <?php
            }
          ?>
       </div>

       <div class="col-md-8" id="">
         <div class="card">
           <div class="card-body" style="min-height:400px">
             <div class="row">
               <div class="col-md-7">
                 <h3 class="montserrat font-weight-bold mb-1">Prescriptions</h3>
                 <div class="mb-3">#000000</div>
                 <span class="primary-color-dark px-3 py-2 montserrat white-text font-weight-bold" style="border-radius:15px; letter-spacing:2px">
                    - PENDING
                 </span>
               </div>
               <div class="col-md-5">
                 <div class="shadow1 p-3">
                   <div class="mb-2" style="color:#babbbd;">Prescribed For</div>
                   <p class="montserrat font-weight-bold" style="color:#0d47a1">Abdallah Kofi Nyaaba</p>
                   <p>Jisonayili , Hse # JY20</p>
                   <p>024 000 0000</p>
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
                     <p class="montserrat font-weight-bold grey-text" style="font-size:10px">PRESCIPTION DATE</p>
                     <p class="montserrat font-weight-bold">15 - May - 2021</p>
                   </div>
                 </div>
               </div>
               <div class="col-md-4">
                 <div class="d-flex">
                   <div class="mr-3">
                      <i class="fas fa-user-md fa-2x grey-text" aria-hidden></i>
                   </div>
                   <div class="">
                     <p class="montserrat font-weight-bold grey-text" style="font-size:10px">PRESCRIBER</p>
                     <p class="montserrat font-weight-bold">Dr. Abdulai Joseph</p>
                   </div>
                 </div>
               </div>
             </div>

             <section class="mt-3 p-3 grey lighten-5">
               <div class="card shadow2 mb-3">
                 <div class="card-body ">
                   <div class="row">
                     <div class="col-md-12">
                       <p class="montserrat "><span class="font-weight-bold">TAB PARACETAMOL 500MG</span> [ 2 Tabs QID  4Days,Oral ]</p>
                       <div class="row mt-2">
                         <div class="col-md-2">
                           <input type="text" name="" class="form-control" value="" placeholder="Unit">
                         </div>
                         <div class="col-md-2">
                           <input type="text" name="" class="form-control" value="" placeholder="Qty">
                         </div>
                         <div class="col-md-2">
                           <input type="text" name="" class="form-control" value="" placeholder="Total">
                         </div>
                         <div class="col-md-6 text-right">
                           <button type="button" class="btn btn-primary" style="padding: 0.4rem 2.14rem;">Add to cart</button>
                         </div>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
               <div class="card shadow2">
                 <div class="card-body ">
                   <div class="row">
                     <div class="col-md-12">
                       <p class="montserrat "><span class="font-weight-bold">IV METRONIDAZOLE 300MG</span> [ 100ML QID  4Days,Oral ]</p>
                       <div class="row mt-2">
                         <div class="col-md-2">
                           <input type="text" name="" class="form-control" value="" placeholder="Unit">
                         </div>
                         <div class="col-md-2">
                           <input type="text" name="" class="form-control" value="" placeholder="Qty">
                         </div>
                         <div class="col-md-2">
                           <input type="text" name="" class="form-control" value="" placeholder="Total">
                         </div>
                         <div class="col-md-6 text-right">
                           <button type="button" class="btn btn-primary" style="padding: 0.4rem 2.14rem;">Add to cart</button>
                         </div>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>



             </section>
             <div class="text-right mt-3">
               <button type="button" class="btn primary-color-dark white-text"><i class="fas fa-shopping-basket mr-2" aria-hidden></i> Go To Cart</button>
             </div>

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

  $('.sidebar-fixed .list-group-item').removeClass('active')
  $('#pharmacy_nav').addClass('active')
  $('#pharmacy_submenu').addClass('show')
  $('#dispensary_li').addClass('font-weight-bold')

    $('.pending_prescriptions').on('click', function(event) {
      event.preventDefault();
      var visit_id=$(this).attr('ID')
      GetPrescription(visit_id)
    });

    function GetPrescription(visit_id){
      $.get('../serverscripts/admin/Dispensary/load_prescription.php?visit_id='+visit_id,function(msg){
        $('#modal_holder').html(msg)
        $('#prescription_modal').modal('show')
        $('.qty').on('keyup', function(event) {
          event.preventDefault();
          var idnumber=$(this).data('idnumber');
          var qty=$(this).val()
          var retail_price=$('#retail_price_'+idnumber).val()
          $('#total_'+idnumber).val((parseInt(qty)*parseFloat(retail_price)).toFixed(2))
        });//end keyup

        $('.add_to_cart_frm').on('submit', function(event) {
          event.preventDefault();
          $.ajax({
            url: '../serverscripts/admin/Dispensary/add_to_cart.php',
            type: 'GET',
            data:$(this).serialize(),
            success:function(msg){
              if(msg=='save_successful'){
                bootbox.alert('Drugs added to cart',function(){
                  GetPrescription(visit_id)
                })
              }else {
                bootbox.alert(msg)
              }
            }
          })
        });
      })
    }

	</script>

</html>
