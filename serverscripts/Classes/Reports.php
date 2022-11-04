<?php

  /**
   * Class for sales
   */
  class Report {

    Public $start='';
    Public $end='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->mysqli=new mysqli('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici');

      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->active_user=$_SESSION['active_user'];

      $this->today=date('Y-m-d');
      $this->timenow=date('Y-m-d H:i:s');

    }

    function DiagnosisReport(){
      $sql="SELECT subscriber_id,primary_diagnosis,COUNT(*) AS count FROM visits WHERE primary_diagnosis!='' AND subscriber_id='".$this->active_subscriber."' GROUP BY primary_diagnosis ORDER BY count desc";
      $r=$this->mysqli->query($sql);
      while ($rows=$r->fetch_assoc()) {
        $data[]=$rows;
      }
      return $data;
    }

    // function Income(){
    //
    //   $query=mysqli_query($this->db,"
    //   SELECT SUM(total) as total_sales FROM cart
    //   LEFT JOIN sales on sales.transaction_id=cart.transaction_id
    //   WHERE sales.date BETWEEN '".$this->start."' AND '".$this->end."' && cart.subscriber_id='".$this->active_subscriber."' && sales.subscriber_id='".$this->active_subscriber."' && cart.status='active'
    //   ") or die(mysqli_error($this->db));
    //
    //   $total_sales=mysqli_fetch_assoc($query);
    //   $total_sales=$total_sales['total_sales'];
    //   $this->total_retail_sale=$total_sales;
    //
    //   $query=mysqli_query($this->db,"SELECT SUM(amount_paid) as total_invoice_payments FROM invoice_payments WHERE subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
    //   $total_invoice_payments=mysqli_fetch_array($query);
    //   $this->total_invoice_payments=$total_invoice_payments['total_invoice_payments'];
    //
    //   $this->total_income=($this->total_retail_sale)+($this->total_invoice_payments);
    // }
    //
    // function Expenditure(){
    //   $query=mysqli_query($this->db,"SELECT SUM(amount) as total_expenditure
    //                                                       FROM expenditure
    //                                                       WHERE
    //                                                         date BETWEEN '".$this->start."' AND '".$this->end."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
    //                                     ") or die(mysqli_error($this->db));
    //
    //       $total_expenditure=mysqli_fetch_array($query);
    //       $this->total_expenditure=$total_expenditure[total_expenditure];
    //       $this->net_profit=($this->total_income)- ($this->total_expenditure);
    // }//end Expenditure




  }


 ?>
