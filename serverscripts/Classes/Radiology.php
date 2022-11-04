<?php

  /**
   * Radiology Class
   */
  class Radiology{

    public $request_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->mysqli=new mysqli("localhost","shaabd_xmedici","@Tsung3#","shaabd_xmedici");

      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
      $this->date=date('Y-m-d');
    }

    function RadiologyIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM radiology_requests WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'R'.prefix($count).''.$count;
    }

    function Info(){
      $sql="SELECT * FROM radiology_requests WHERE request_id='".$this->request_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'";
      $result=$this->mysqli->query($sql);
      $info=$result->fetch_assoc();

      if($info['patient_type']=='walkin_patient'){
        $this->surname=$info['surname'];
        $this->othernames=$info['othernames'];
        $this->age=$info['age'];
        $this->sex=$info['sex'];
        $this->address=$info['address'];
      }else {
        $this->patient_id=$info['patient_id'];
      }

      $this->patient_type=$info['patient_type'];

      $this->service_id=$info['service_id'];
      $this->service_cost=$info['service_cost'];

      $this->clinical_history=$info['clinical_history'];
      $this->doctor=$info['doctor'];
      $this->station_address=$info['station_address'];
      $this->serial_number=$info['serial_number'];
      $this->status=$service_info['status'];
    } //end


    function NewRequest($patient_type,$surname,$othernames,$age,$sex,$address,$clinical_history,$service_id,$service_cost,$doctor,$station_address){
      $request_id=$this->RadiologyIdGen();
      $table='radiology_requests';
      $fields=array("subscriber_id","request_id","patient_type","surname","othernames","age","sex","address","history","service_id","service_cost","doctor","station_address","date","created_by");
      $values=array("$this->active_subscriber","$request_id","$patient_type","$surname","$othernames","$age","$sex","$address","$clinical_history","$service_id","$service_cost","$doctor","$station_address","$this->date","$this->user_id");
      $query=insert_data($this->db,$table,$fields,$values);
      if($query){
        return $request_id;
      }else {
        return 'false';
      }
    }//end function


    function All(){
      $sql="SELECT * FROM radiology_requests WHERE status='active' AND subscriber_id='".$this->active_subscriber."'";
      $result=$this->mysqli->query($sql);
      if($result->num_rows >0){
        while ($rows=$result->fetch_assoc()) {
          $data[]=$rows;
        }//end while
        return $data;
      }else {
        return "No data to display";
      }
    } //end all

    function Delete(){
      $sql="UPDATE radiology_requests SET status='deleted' WHERE request_id='".$this->request_id."' AND subscriber_id='".$this->active_subscriber."'";
      if($this->mysqli->query($sql)){
        return 'delete_successful';
      }else {
        return 'Unable to delete request';
      }
    } //end Delete()


    function Complete(){
      $sql="UPDATE radiology_requests SET request_status='Complete' WHERE request_id='".$this->request_id."' AND subscriber_id='".$this->active_subscriber."'";
      if($this->mysqli->query($sql)){
        return 'update_successful';
      }else {
        return 'Unable to complete request';
      }
    } //end Delete()



  }



 ?>
