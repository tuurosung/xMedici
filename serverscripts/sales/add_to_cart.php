<?php
require_once('../dbcon.php');
session_start();

$item_code=$_GET['item_code'];
// $batch_code=$_GET['batch_code'];
$selling_price=$_GET['selling_price'];
$qty=$_GET['qty'];
$total_cost=$_GET['total_cost'];
$date=date("Y-m-d");
$attendant=$_SESSION['active_user'];


if(!isset($_SESSION['tracksales_transid'])){
  $_SESSION['tracksales_transid']=time();
  $tracksales_transid=$_SESSION['tracksales_transid'];
}
else {
  $tracksales_transid=$_SESSION['tracksales_transid'];
}

$get_stock_info=mysqli_query($db,"SELECT * FROM stock WHERE drug_id='".$item_code."' && qty_rem >= '".$qty."' ORDER BY  sn DESC LIMIT 1") or die('failed');
if(mysqli_num_rows($get_stock_info) ==0){


        echo 'less_qty';


}
else {


        $get_stock_info=mysqli_fetch_array($get_stock_info);
        $batch_code=$get_stock_info['batch_code'];

        $check_exists=mysqli_query($db,"SELECT COUNT(*) AS num_cart FROM sales WHERE transid='".$tracksales_transid."' && item_code='".$item_code."' && batch_code='".$batch_code."' && status='PENDING'") or die('failed1');
        $num_cart=mysqli_fetch_assoc($check_exists);
        $num_cart=$num_cart['num_cart'];


        if($num_cart > 0){

          $update_string=mysqli_query($db,"UPDATE sales SET qty=qty+$qty,total_cost=total_cost+$total_cost WHERE transid='".$tracksales_transid."' AND drug_id='".$item_code."' AND batch_code='".$batch_code."'  && status='PENDING'") or die('failed');
          if($update_string){
            echo 'cart_successful';
          }
          else {
            echo 'failed';
          }

        }
        else {

          $insert_string=mysqli_query($db,"INSERT INTO sales
                                            (transid,item_code,batch_code,selling_price,qty,total_cost,status,date,attendant)
                                           VALUES
                                           ('$tracksales_transid','$item_code','$batch_code','$selling_price','$qty','$total_cost','PENDING','$date','$attendant')
                                           ") OR die('failed');

          if($insert_string){
            echo 'cart_successful';
          }
          else {
            echo 'failed';
          }

        }
}


//verify qty-remaining
// $check_qty=mysqli_query($db,"SELECT qty_rem AS qty_rem FROM stock WHERE batch_code='".$batch_code."' AND drug_id='".$item_code."'") or die('failed');
// $qty_rem=mysqli_fetch_assoc($check_qty);
// $qty_rem=$qty_rem['qty_rem'];
//
// if($qty_rem < $qty){
//   echo 'less_qty';
// }
// else{
//
//   //check whether item exists in cart
//   $check_exists=mysqli_query($db,"SELECT COUNT(*) AS num_cart FROM sales WHERE transid='".$tracksales_transid."' && item_code='".$item_code."' && batch_code='".$batch_code."' && status='PENDING'") or die('failed1');
//   $num_cart=mysqli_fetch_assoc($check_exists);
//   $num_cart=$num_cart['num_cart'];
//
//
//   if($num_cart > 0){
//
//     $update_string=mysqli_query($db,"UPDATE sales SET qty=qty+$qty,total_cost=total_cost+$total_cost WHERE transid='".$tracksales_transid."' AND drug_id='".$item_code."' AND batch_code='".$batch_code."'  && status='PENDING'") or die('failed');
//     if($update_string){
//       echo 'cart_successful';
//     }
//     else {
//       echo 'failed';
//     }
//
//   }
//   else {
//
//     $insert_string=mysqli_query($db,"INSERT INTO sales
//                                       (transid,item_code,batch_code,selling_price,qty,total_cost,status,date,attendant)
//                                      VALUES
//                                      ('$tracksales_transid','$item_code','$batch_code','$selling_price','$qty','$total_cost','PENDING','$date','$attendant')
//                                      ") OR die('failed');
//
//     if($insert_string){
//       echo 'cart_successful';
//     }
//     else {
//       echo 'failed';
//     }
//
//   }
//
//
// }
?>
