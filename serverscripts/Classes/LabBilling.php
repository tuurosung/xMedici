<?php
  require_once 'Services.php';


  /**
   * Expenditure
   */
  class Billing{

    Public $bill_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");

      if(!isset($_SESSION['active_subscriber']) || !isset($_SESSION['active_user']) || $_SESSION['active_subscriber']=='' || $_SESSION['active_user']==''){
        die('session_expired');
      }else {
        $this->active_subscriber=$_SESSION['active_subscriber'];
        $this->active_user=$_SESSION['active_user'];
      }

      $this->suffix=date('y');
      $this->today=date('Y-m-d');
    }

    function BillIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM service_billing WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'Bill-'.prefix($count).''.$count;
    }



    function BillInfo(){


      $query=mysqli_query($this->db,"SELECT *
                                                          FROM service_billing
                                                          WHERE
                                                            bill_id='".$this->bill_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                        ") or die(mysqli_error($this->db));

      $bill_info=mysqli_fetch_array($query);
      $this->patient_id=$bill_info['patient_id'];
      $this->visit_id=$bill_info['visit_id'];
      $this->bill_id=$bill_info['bill_id'];
      $this->service_id=$bill_info['service_id'];
      $this->service_cost=$bill_info['service_cost'];
      $this->cycle=$bill_info['cycle'];
      $this->total=$bill_info['total'];
      $this->date=$bill_info['date'];
      // $this->payment_status=$bill_info['payment_status'];
      $this->status=$bill_info['status'];

      $check_payment_status=mysqli_query($this->db,"SELECT SUM(amount_paid-balance) as paid FROM payments WHERE bill_id='".$this->bill_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $paid=mysqli_fetch_array($check_payment_status);
      $this->total_bill_payment=$paid['paid'];

      if($this->total_bill_payment == $this->service_cost){
        $this->payment_status='PAID';
      }elseif ($this->total_bill_payment==0) {
        $this->payment_status='PENDING';
      }elseif ($this->total_bill_payment >0 AND $this->total_bill_payment < $this->service_cost) {
        $this->payment_status='PART-PAID';
      }

    } //end BillInfo


    function LabBillInfo(){


      $query=mysqli_query($this->db,"SELECT *
                                                          FROM labs_billing
                                                          WHERE
                                                            request_id='".$this->bill_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                        ") or die(mysqli_error($this->db));

      $bill_info=mysqli_fetch_array($query);
      $this->patient_id=$bill_info['patient_id'];
      $this->visit_id=$bill_info['visit_id'];
      $this->request_id=$bill_info['request_id'];
      $this->bill=$bill_info['bill'];
      $this->cycle=$bill_info['cycle'];
      $this->total=$bill_info['total'];
      $this->date=$bill_info['date'];
      // $this->payment_status=$bill_info['payment_status'];
      $this->status=$bill_info['status'];

      $check_payment_status=mysqli_query($this->db,"SELECT SUM(amount_paid-balance) as paid FROM payments WHERE bill_id='".$this->bill_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $paid=mysqli_fetch_array($check_payment_status);
      $this->total_bill_payment=$paid['paid'];

      if($this->total_bill_payment == $this->service_cost){
        $this->payment_status='PAID';
      }elseif ($this->total_bill_payment==0) {
        $this->payment_status='PENDING';
      }elseif ($this->total_bill_payment >0 AND $this->total_bill_payment < $this->service_cost) {
        $this->payment_status='PART-PAID';
      }

    } //end BillInfo



    function BillPatient($patient_id,$visit_id,$service_id,$cycle,$date){
      $checkstring=mysqli_query($this->db,"SELECT * FROM service_billing WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring)>0){
        return 'Service Already Billed to Patient';
      } else {
        $s=new Service();
        $s->service_id=$service_id;
        $s->ServiceInfo();
        $service_cost=$s->service_cost;

        $bill_id=$this->BillIdGen();

        $total=(float) $service_cost * (int) $cycle;

        $table='service_billing';
        $fields=array("subscriber_id", "patient_id", "visit_id", "bill_id", "service_id", "service_cost", "cycle", "total", "status", "payment_status", "date");
        $values=array("$this->active_subscriber", "$patient_id", "$visit_id", "$bill_id", "$service_id","$service_cost","$cycle", "$total","active","pending","$date");
        $query=insert_data($this->db,$table,$fields,$values);

        if(mysqli_affected_rows($this->db)==1){
          return 'billing_successful';
        }else {
          return 'Unable to bill patient';
        }
      }
    }//End Bill Patient


  }



 ?>
