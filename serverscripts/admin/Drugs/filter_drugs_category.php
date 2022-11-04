<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Drugs.php';
    $category_id=clean_string($_GET['category_id']);

    $drug=new Drug();


 ?>

 <?php
  if($category_id=='all' || $category_id=='deleted'){
    ?>
    <h6 class="montserrat font-weight-bold">
        <?php
          if($category_id=='all'){
            ?>
            All Drugs
            <?php
          }else {
            ?>
            Deleted Drugs
            <?php
          }
         ?>
    </h6>
    <?php
  }else {
    ?>
    <h6 class="montserrat font-weight-bold"><?php echo $drug->CategoryName($category_id); ?></h6>
    <?php
  }
  ?>


<div class="spacer">

</div>
 <?php

 if($category_id=='all'){
   $get_items=mysqli_query($db,"SELECT * FROM  pharm_inventory  WHERE  status='active' AND subscriber_id='".$active_subscriber."'  ORDER BY  generic_name asc")  or die(mysqli_error($db));
 }elseif ($category_id=='deleted') {
   $get_items=mysqli_query($db,"SELECT * FROM  pharm_inventory  WHERE  status='deleted' AND subscriber_id='".$active_subscriber."'  ORDER BY  generic_name asc")  or die(mysqli_error($db));
 }else {
  $get_items=mysqli_query($db,"SELECT * FROM  pharm_inventory  WHERE  status='active' && subscriber_id='".$active_subscriber."' AND category='".$category_id."'  ORDER BY  generic_name asc")  or die(mysqli_error($db));
 }


 $i=1;
 while ($rows=mysqli_fetch_array($get_items)) {

     $category_id=$rows['category'];

     $drug->drug_id=$rows['drug_id'];
     $drug->DrugInfo();

   ?>
   <div class="card mb-3">
     <div class="card-body">
       <div class="row">
         <div class="col-6 text-capitalize">
           <?php echo $drug->drug_name; ?>
         </div>
         <div class="col-1">
           <?php echo $rows['unit']; ?>
         </div>
         <div class="col-1 text-center">
           <?php echo (int) $drug->qty_rem; ?>
         </div>
         <div class="col-1 text-right">
           <?php echo $drug->cost_price; ?>
         </div>
         <div class="col-1 text-right">
           <?php echo $drug->retail_price; ?>
         </div>
         <div class="col-1 text-right">
           <?php echo $drug->profit_margin; ?>
         </div>
         <div class="col-1 text-right">
           <a href="drug_matrix.php?drug_id=<?php echo $drug->drug_id; ?>" type="button" class="btn btn-primary btn-sm">Matrix</a>
         </div>
       </div>
     </div>
   </div>


       <?php
     }
     ?>
