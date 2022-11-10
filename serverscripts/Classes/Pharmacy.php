<?php

  /**
   * Class for handling pharmacy
   */
  class Pharmacy{

    Public $pharm_id='';

    function __construct(){
    $this->db=mysqli_connect('localhost','root','@Tsung3#','xMedici') or die("Check Connection");
    $this->mysqli = mysqli_connect('localhost', 'root', '@Tsung3#', 'xMedici');

      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];

      $this->today=date('Y-m-d');
    }

    function WalkInTransactionIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM pharm_walkin_transactions WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'PHWKIN'.prefix($count).''.$count;
    }

    function AllPrescriptions(){
      $sql="SELECT * FROM prescriptions WHERE subscriber_id='".$this->active_subscriber."'";
      $r=$this->mysqli->query($sql);
      if($r->num_rows >0){
        while ($rows=$r->fetch_assoc()) {
          $data[]=$rows;
        }
        return $data;
      }
    }

    function AddToWalkInCart($drug_id,$retail_price,$qty,$total){

      if(!isset($_SESSION['pharm_walkin_transaction_id'])){
        $transaction_id=$this->WalkInTransactionIdGen();
        $_SESSION['pharm_walkin_transaction_id']=$transaction_id;
      }else {
        $transaction_id=$_SESSION['pharm_walkin_transaction_id'];
      }

      $check_exists=mysqli_query($this->db,"SELECT * FROM pharm_walkin_cart WHERE transaction_id='".$transaction_id."' AND drug_id='".$drug_id."'  AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($check_exists) ==1){
        $update_string=mysqli_query($this->db,"UPDATE cart
                                                                SET qty=qty+$qty, total=total+$total
                                                                WHERE transaction_id='".$transaction_id."'  AND
                                                                              drug_id='".$drug_id."'   AND
                                                                              batch_code='".$batch_code."' AND
                                                                              subscriber_id='".$active_subscriber."' AND
                                                                              status='active'
                                                      ") or die(mysqli_error($this->db));
            return  'save_successful';
      }
      else {
        $table='pharm_walkin_cart';
        $fields=array("subscriber_id","transaction_id","drug_id","retail_price","qty","total","status","date");
        $values=array("$this->active_subscriber","$transaction_id","$drug_id","$retail_price","$qty","$total","active","$this->today");
        $query=insert_data($this->db,$table,$fields,$values);

        echo $query;
      }

    }


    function CartSum($transaction_id){
      if(!empty($transaction_id)){
        $query=mysqli_query($this->db,"SELECT SUM(total) as cart_sum FROM pharm_walkin_cart WHERE transaction_id='".$transaction_id."' AND status='active' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $cart_info=mysqli_fetch_array($query);
        return $cart_info['cart_sum'];
      }
    }//end function

    function CheckoutCart($transaction_id,$total_cost,$timestamp){
      $checkstring=mysqli_query($this->db,"SELECT * FROM pharm_walkin_transactions WHERE transaction_id='".$transaction_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) ==0){
        $table='pharm_walkin_transactions';
        $fields=array("subscriber_id","transaction_id","total_cost","timestamp","status");
        $values=array("$this->active_subscriber","$transaction_id","$total_cost","$timestamp","active");
        $query=insert_data($this->db,$table,$fields,$values);
        return $query;
      }
    }//end function


    function PharmacySales($start,$end){
      $query=mysqli_query($this->db,"SELECT SUM(total) AS total_sales
                                                          FROM pharm_cart
                                                          WHERE
                                                            status='CHECKOUT' AND
                                                            subscriber_id='".$this->active_subscriber."' AND
                                                            date BETWEEN '".$start."' AND '".$end."'
                                    ") or die(mysqli_error($this->db));
      $total_sales=mysqli_fetch_array($query);
      return $total_sales['total_sales'];
    }


    function PharmacyWalkInSales($start,$end){
      $query=mysqli_query($this->db,"SELECT SUM(total) AS total_sales
                                                          FROM pharm_walkin_cart
                                                          WHERE
                                                            status='CHECKOUT' AND
                                                            subscriber_id='".$this->active_subscriber."' AND
                                                            date BETWEEN '".$start."' AND '".$end."'
                                    ") or die(mysqli_error($this->db));
      $total_sales=mysqli_fetch_array($query);
      return $total_sales['total_sales'];
    }

  }//end class





 ?>
