<?php
  require_once ('Database.php');

  /**
   * Expenditure
   */
  class Service{

    Public $service_id='';

    function __construct(){
      $q=new DataBase();
      $this->db=$q->db;
      $this->mysqli=$q->mysqli;

      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function ServiceIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM services WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'S'.prefix($count).''.$count;
    }



    function ServiceInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM services WHERE service_id='".$this->service_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $service_info=mysqli_fetch_array($query);
      $this->description=$service_info['description'];
      $this->billing_type=$service_info['billing_type'];
      $this->billing_cycle=$service_info['billing_cycle'];
      $this->billing_point=$service_info['billing_point'];
      $this->service_cost=$service_info['service_cost'];
      $this->status=$service_info['status'];
    } //end ServiceInfo

    function UpdateService($service_id,$description,$billing_cycle,$billing_point,$billing_type,$service_cost){
        $query=mysqli_query($this->db,"UPDATE services
                                                            SET
                                                              description='".$description."',
                                                              billing_cycle='".$billing_cycle."',
                                                              billing_type='".$billing_type."',
                                                              billing_point='".$billing_point."',
                                                              service_cost='".$service_cost."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND service_id='".$service_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to update service';
          }
    }// end update


    function CreateService($description,$billing_cycle,$billing_point,$billing_type,$service_cost,$date){
      $service_id=$this->ServiceIdGen();
      $checkstring=mysqli_query($this->db,"SELECT * FROM services WHERE service_id='".$service_id."' && subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) != 0){
        return  'A service with the same details exists';
      }
      else {

        $table='services';
        $fields=array("subscriber_id","service_id","description","billing_cycle","billing_point","billing_type","service_cost","status","date");
        $values=array("$this->active_subscriber","$service_id","$description","$billing_cycle","$billing_point","$billing_type","$service_cost","active","$date");
        $query=insert_data($this->db,$table,$fields,$values);

        return 'save_successful';
      }
    }// end Create 



    function BillingPoint($billing_point){
      $query=mysqli_query($this->db,"SELECT * FROM billing_points WHERE billing_point='".$billing_point."'") or die(mysqli_error($this->db));
      $point_info=mysqli_fetch_array($query);
      return $point_info['point_name'];
    } //end ServiceInfo


    function servicesFilter($billing_point){
      $sql="SELECT * FROM services WHERE billing_point ='".$billing_point."'";
      $result=$this->mysqli->query($sql);
      while ($rows=$result->fetch_assoc()) {
        $data[]=$rows;
      }
      return $data;
    }

    function AllServices(){
      $sql="SELECT * FROM services WHERE subscriber_id='".$this->active_subscriber."'";
      $result=$this->mysqli->query($sql);
      while ($rows=$result->fetch_assoc()) {
        $data[]=$rows;
      }
      return $data;
    }




  }



 ?>
