<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/OPD.php';
    require_once '../../Classes/Drugs.php';
    require_once '../../Classes/Patient.php';
    require_once '../../Classes/Doctors.php';

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


 <div id="prescription_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-body">




     <div class="row">
       <div class="col-md-7">
         <h3 class="montserrat font-weight-bold mb-1">Prescriptions</h3>
         <div class="mb-3"><?php echo $visit_id; ?></div>
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

     <section class="mt-3 p-3 grey lighten-5">

         <?php

          $get_prescriptions=mysqli_query($db,"SELECT
                                                                              *
                                                                        FROM
                                                                          prescriptions
                                                                        WHERE
                                                                          patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$active_subscriber."' AND status='active' AND dispensary_status='PENDING'
                                                                ") or die(mysqli_error($db));
                  while ($rows=mysqli_fetch_array($get_prescriptions)) {
                    $drug->drug_id=$rows['drug_id'];
                    $drug->DrugInfo();
                    ?>
                    <form class="add_to_cart_frm" autocomplete="off">
                      <div class="form-group d-none">
                        <input type="text" name="visit_id" value="<?php echo $visit_id; ?>">
                        <input type="text" name="patient_id" value="<?php echo $patient_id; ?>">
                        <input type="text" name="drug_id" value="<?php echo $rows['drug_id']; ?>">
                      </div>

                    <p class="poppins font-weight-bold mb-3">Prescribed On <?php echo date('d-M-Y',strtotime($rows['date'])); ?></p>
                    <div class="card shadow2 mb-3">
                    <div class="card-body ">
                      <div class="row">
                        <div class="col-md-12">
                          <p class="montserrat "><span class="font-weight-bold mr-3"><?php echo $drug->drug_name; ?></span> <span class="poppins" style="font-style:italic"><?php echo $rows["qty"] . ' '.$rows["frequency"].' '.$rows["duration"] .' '.$rows["route"].' '.$rows["notes"]; ?> </span> </p>
                          <div class="row mt-2">
                            <div class="col-md-2">
                              <input type="text" name="retail_price" id="retail_price_<?php echo $rows['sn']; ?>" class="form-control" value="<?php echo $drug->retail_price; ?>" placeholder="Unit" readonly>
                            </div>
                            <div class="col-md-2">
                              <input type="text" name="qty" id="qty_<?php echo $rows['sn']; ?>" class="form-control qty" value="" placeholder="Qty" data-idnumber="<?php echo $rows['sn']; ?>" required>
                            </div>
                            <div class="col-md-2">
                              <input type="text" name="total" id="total_<?php echo $rows['sn']; ?>" class="form-control" value="" placeholder="Total">
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                              <span style="font-size:9px">
                                <?php
                                if($rows['dispensary_status']=='PENDING'){
                                  ?>
                                  <i class="far fa-clock text-primary fa-2x" aria-hidden></i>
                                  <?php
                                }elseif ($rows['dispensary_status']=='CART') {
                                  ?>
                                  <i class="fas fa-shopping-basket text-success fa-2x" aria-hidden></i>
                                  <?php
                                }
                               ?>
                              </span>

                            </div>
                            <div class="col-md-5 text-right">
                              <?php
                                  if($rows['dispensary_status']=='CART'){
                                    ?>
                                    <button type="submit" class="btn btn-success" style="padding: 0.4rem 2.14rem;">Update</button>
                                    <?php
                                  }else {
                                    ?>
                                    <button type="submit" class="btn btn-primary" style="padding: 0.4rem 2.14rem;">Add to cart</button>
                                    <?php
                                  }
                               ?>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    </div>
                  </form>
                    <?php
                  }
          ?>






     </section>
     <div class="text-right mt-3">
       <a type="button" href="pharm_cart.php?patient_id=<?php echo $patient_id; ?>&visit_id=<?php echo $visit_id; ?>" class="btn primary-color-dark white-text"><i class="fas fa-shopping-basket mr-2" aria-hidden></i> Go To Cart</a>
     </div>



</div>
</div>
</div>
</div>
