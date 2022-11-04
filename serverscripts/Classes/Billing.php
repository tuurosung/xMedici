<?php
  require_once 'Services.php';
  require_once 'Tests.php';


  /**
   * Expenditure
   */
  class Billing{

    Public $bill_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->mysqli=new mysqli('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici');

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
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM billing WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'Bill-'.prefix($count).''.$count;
    }



    function BillInfo(){
      $sql="SELECT * FROM billing WHERE bill_id='".$this->bill_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'";
      $result=$this->mysqli->query($sql);
      $bill_info=$result->fetch_assoc();

      // $bill_info=mysqli_fetch_array($query);
      $this->patient_id=$bill_info['patient_id'];
      $this->visit_id=$bill_info['visit_id'];
      $this->bill_id=$bill_info['bill_id'];
      $this->reference=$bill_info['reference'];
      $this->bill_amount=$bill_info['bill_amount'];
      $this->date=$bill_info['date'];
      // $this->payment_status=$bill_info['payment_status'];
      $this->status=$bill_info['status'];
      $this->narration=$bill_info['narration'];

      $check_payment_status=mysqli_query($this->db,"SELECT
                                                                                    SUM(amount_paid) as paid
                                                                                    FROM payments
                                                                                    WHERE
                                                                                      bill_id='".$this->bill_id."' AND
                                                                                       subscriber_id='".$this->active_subscriber."' AND
                                                                                       status='active'
                                                              ") or die(mysqli_error($this->db));

      $paid=mysqli_fetch_array($check_payment_status);
      $this->total_bill_payment=$paid['paid'];

      $this->balance_remaining=$this->bill_amount - $this->total_bill_payment;

      if($this->total_bill_payment >= $this->bill_amount){

        $update="UPDATE billing SET payment_status='paid' WHERE bill_id='".$this->bill_id."' AND subscriber_id='".$this->active_subscriber."'";
        $this->mysqli->query($update);
        $this->payment_status='PAID';

      }elseif ($this->total_bill_payment==0) {
        $this->payment_status='PENDING';
      }elseif ($this->total_bill_payment >0 AND $this->total_bill_payment < $this->bill_amount) {
        $this->payment_status='PART-PAID';
      }
    } //end BillInfo


    function BillPatient($patient_id,$visit_id,$reference,$bill_amount,$narration){
      // $checkstring=mysqli_query($this->db,"SELECT * FROM billing WHERE patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND reference='".$reference."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      // if(mysqli_num_rows($checkstring)>0){
      //   return 'Patient already billed';
      // } else {
      // }
      $bill_id=$this->BillIdGen();


      $table='billing';
      $fields=array("subscriber_id", "patient_id", "visit_id", "bill_id", "reference","bill_amount", "status", "payment_status", "date","narration");
      $values=array("$this->active_subscriber", "$patient_id", "$visit_id", "$bill_id", "$reference","$bill_amount","active","pending","$this->today","$narration");
      $query=insert_data($this->db,$table,$fields,$values);

      if(mysqli_affected_rows($this->db)==1){
        return 'billing_successful';
      }else {
        return 'Unable to bill patient';
      }

    }//End Bill Patient

    function ModifyBilling($patient_id,$visit_id,$bill_id,$bill_amount,$narration){
      $update_billing=mysqli_query($this->db,"UPDATE billing
                                                                        SET
                                                                          bill_amount='".$bill_amount."',
                                                                          narration='".$narration."'
                                                                          WHERE
                                                                            patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND bill_id='".$bill_id."' AND subscriber_id='".$this->active_subscriber."'

                                                    ") or die(msyqli_error($this->db));
        if($update_billing){
          return 'update_successful';
        }else {
          return 'Unable to modify bill';
        }
    }


    function DeleteBill($bill_id){
      $delete_bill=mysqli_query($this->db,"UPDATE billing
                                                                  SET status='deleted'
                                                                  WHERE
                                                                      bill_id='".$bill_id."' AND subscriber_id='".$this->active_subscriber."'
                                          ") or die(mysqli_error($db));
        if(mysqli_affected_rows($this->db)==1){
          return 'delete_successful';
        }else {
          return 'Unable to delete invoice';
        }
    }

    function PendingBills(){
      $sql="SELECT * FROM billing WHERE status='active' AND payment_status='pending' AND subscriber_id='".$this->active_subscriber."' LIMIT 100 ";
      $result=$this->mysqli->query($sql);
      if($result->num_rows >0){
        while ($rows=$result->fetch_assoc()) {
          $data[]=$rows;
        }//end while
        return $data;
      }else {
        return "No data to display";
      }


    }


  }



 ?>
