<?php

  /**
   * Class for sales
   */
  class Sale {

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_dartah','@supremo3635040','shaabd_dartah') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function SalePeriod($start,$end){

      $query=mysqli_query($this->db,"
      SELECT SUM(total) as total_sales FROM cart
      LEFT JOIN sales on sales.transaction_id=cart.transaction_id
      WHERE sales.date BETWEEN '".$start."' AND '".$end."' && cart.subscriber_id='".$this->active_subscriber."' && sales.subscriber_id='".$this->active_subscriber."' && cart.status='active'
      ") or die(mysqli_error($this->db));

      $total_sales=mysqli_fetch_assoc($query);
      $total_sales=$total_sales['total_sales'];
      return $total_sales;
    }
  }


 ?>
