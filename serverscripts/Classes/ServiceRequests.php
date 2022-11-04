<?php
  // require_once 'Billing.php';
  /**
   * Service Requests
   */
  class ServiceRequest{

    Public $request_id='';

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



    function RequestIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM service_requests WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'SRQ'.prefix($count).''.$count;
    }



    function RequestService($patient_id,$visit_id,$service_id,$unit_cost,$qty,$total){
      $request_id=$this->RequestIdGen();
      // $checkstring=mysqli_query($this->db,"SELECT * FROM service WHERE subscriber_id='".$this->active_subscriber."' AND visit_id='".$visit_id."'") or die(msyqli_error($this->db));

      $table='service_requests';
      $fields=array("subscriber_id","patient_id","visit_id","request_id","service_id","service_cost","qty","total","date");
      $values=array("$this->active_subscriber","$patient_id","$visit_id","$request_id","$service_id","$unit_cost","$qty","$total","$this->today");
      $query=insert_data($this->db,$table,$fields,$values);

      return $request_id;
    }

  }



 ?>
