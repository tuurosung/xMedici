<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>
<?php require_once '../serverscripts/Classes/Users.php'; ?>

<style media="screen">
  .card{
    border-radius: .9rem;
  }
</style>


<?php
  $patient=new Patient();
  $opd=new Visit();
  $drug=new Drug();
  $user=new User();
 ?>

 <main class="py-3 mx-lg-5">
   <div class="container-fluid mt-2">

     <div class="row mb-5">
       <div class="col-6">
         <h4 class="titles montserrat">Prescription</h4>
       </div>
     </div>


     <div class="card">
       <div class="card-body py-5" style="height:80vh">

         <div class="row mb-5">
           <div class="col-4">
             <h6 class="montserrat font-weight-bold">Prescription History</h6>
           </div>
           <div class="col-8 d-flex flex-row-reverse">
             <form class="form-inline">
               <div class="form-group mr-2">
                 <select class="form-control custom-select" name="">
                   <option value="pending">Pending Prescriptions</option>
                   <option value="rejected">Rejected Prescription</option>
                 </select>
               </div>
               <div class="form-group">
                 <select class="form-control custom-select" name="">
                   <option value="">Dispensed By</option>
                 </select>
               </div>
             </form>
           </div>
         </div>

         <table class="table table-condensed">
           <thead>
             <tr>
               <th>#</th>
               <th>Date</th>
               <th>Patient Name</th>
               <th>Age</th>
               <th>Sex</th>
               <th>No. Of  Drugs</th>
               <th class="text-right">Options</th>
             </tr>
           </thead>
           <tbody>
             <?php
              $i=1;
              $get_prescriptions=mysqli_query($db,"SELECT date,patient_id,visit_id,COUNT(*) as count FROM  prescriptions  WHERE subscriber_id='".$active_subscriber."' AND status='active'AND dispensary_status='PENDING'  GROUP BY visit_id") or die(mysqli_error($db));

              if(mysqli_num_rows($get_prescriptions) >0){

              while ($rows=mysqli_fetch_array($get_prescriptions)) {
                $visit_id=$rows['visit_id'];
                $patient_id=$rows['patient_id'];

                $opd->VisitInfo($visit_id);
                $patient->patient_id=$opd->patient_id;
                $patient->PatientInfo();

                $doctor_id=$opd->doctor_id;
                $user->user_id=$doctor_id;
                $user->UserInfo();

                if($opd->visit_status=='discharged'){
                  continue;
                }
                ?>

                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $rows['date']; ?></td>
                  <td><?php echo $patient->patient_fullname; ?></td>
                  <td><?php echo ucfirst($patient->sex); ?></td>
                  <td><?php echo $patient->age; ?></td>
                  <td><?php echo $rows['count']; ?></td>
                  <td class="text-right">
                    <button type="button" class="btn btn-primary btn-sm  pending_prescriptions my-1" id="<?php echo $rows['visit_id']; ?>">
                      <i class="fas fa-signature mr-2" aria-hidden></i>
                      Dispense</button>
                  </td>
                </tr>

                <?php
                  }
                }
              ?>

           </tbody>
         </table>

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
