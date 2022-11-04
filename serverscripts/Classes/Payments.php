<?php
  // require_once 'Billing.php';


  /**
   * Expenditure
   */
  class Payment{

    Public $bill_id='';
    Public $payment_id='';
    Public $visit_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->mysqli=new mysqli('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici');

      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->active_user=$_SESSION['active_user'];

      $this->suffix=date('y');
      $this->today=date('Y-m-d');
      $this->this_month=date('m');
      $this->current_year=date('Y');
      $this->current_month=date('m');
      $this->timestamp=time();

      $monthly_revenue=mysqli_query($this->db,"SELECT SUM(amount_paid) as monthly_revenue FROM payments WHERE status='active' AND  YEAR(date)='".$this->current_year."' AND MONTH(date)='".$this->current_month."' AND subscriber_id='".$this->active_subscriber."'
      ") or die(mysqli_error($this->db));
      $monthly_revenue=mysqli_fetch_array($monthly_revenue);
      $this->monthly_revenue=$monthly_revenue['monthly_revenue'];


      $todays_revenue=mysqli_query($this->db,"SELECT SUM(amount_paid) as todays_revenue FROM payments WHERE status='active' AND subscriber_id='".$this->active_subscriber."' AND date='".$this->today."'") or die(mysqli_error($this->db));
      $todays_revenue=mysqli_fetch_array($todays_revenue);
      $this->todays_revenue=$todays_revenue['todays_revenue'];
    }

    function PaymentIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM payments WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'PMT'.prefix($count).''.$count;
    }



    function PaymentInfo(){
      $query=mysqli_query($this->db,"SELECT *
                                                          FROM payments
                                                          WHERE
                                                            payment_id='".$this->payment_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                        ") or die(mysqli_error($this->db));

      $payment_info=mysqli_fetch_array($query);
      $this->patient_id=$payment_info['patient_id'];
      $this->visit_id=$payment_info['visit_id'];
      $this->bill_id=$payment_info['bill_id'];
      $this->amount_payable=$payment_info['amount_payable'];
      $this->amount_paid=$payment_info['amount_paid'];
      $this->balance=$payment_info['balance'];
      $this->date=$payment_info['date'];
      $this->status=$payment_info['status'];
      $this->income_account=$payment_info['income_account'];
      $this->payment_account=$payment_info['payment_account'];

    } //end BillInfo



    function NewPayment($patient_id,$visit_id,$bill_id,$amount_payable,$amount_paid,$balance,$date,$income_header,$payment_account){
      $payment_id=$this->PaymentIdGen();
      $checkstring=mysqli_query($this->db,"SELECT * FROM payments WHERE payment_id='".$payment_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring)>0){
        return 'Payment cannot be duplicated';
      } else {


        $table='payments';
        $fields=array("subscriber_id", "patient_id", "visit_id", "bill_id", "payment_id", "amount_payable", "amount_paid", "balance", "status","date","income_account","payment_account","timestamp","cashier");
        $values=array("$this->active_subscriber", "$patient_id", "$visit_id","$bill_id", "$payment_id", "$amount_payable","$amount_paid","$balance","active","$this->today","$income_header","$payment_account","$this->timestamp","$this->active_user");
        $query=insert_data($this->db,$table,$fields,$values);

        return $query;
      }
    }//End NewPayment

    function DeletePayment($payment_id){
      $delete_query=mysqli_query($this->db,"UPDATE payments SET status='deleted' WHERE payment_id='".$payment_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($db));
      if($delete_query){
        return 'delete_successful';
      }else {
        return 'Unable to delete payment';
      }
    }


    function VisitPayments(){
        $sql="SELECT * FROM payments WHERE subscriber_id='".$this->active_subscriber."'  AND visit_id='".$this->visit_id."'  AND status='active'";
        $r=$this->mysqli->query($sql);
        while ($rows=$r->fetch_assoc()) {
          $data[]=$rows;
        }
        return $data;
    }

    function AllPayments($start_date,$end_date,$cashier,$payment_account){

      $sql="SELECT * FROM payments WHERE status='active' AND subscriber_id='".$this->active_subscriber."' ";

      if(isset($start_date) AND isset($end_date)){
        $sql.="AND  date BETWEEN '".$start_date."' AND '".$end_date."'";
      }

      if($cashier!=''){
        $sql.="AND cashier='".$cashier."'";
      }

      if($payment_account!=''){
        $sql.=" AND payment_account='".$payment_account."'";
      }

      $r=$this->mysqli->query($sql);
      while ($rows=$r->fetch_assoc()) {
        $data[]=$rows;
      }
      return $data;
    }


  }



 ?>
