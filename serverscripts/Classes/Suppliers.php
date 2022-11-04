<?php

  /**
   * Suppliers
   */
  class Supplier{

    public $supplier_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_dartah','@supremo3635040','shaabd_dartah') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function SupplierIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM suppliers WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'SPL'.prefix($count).''.$count;
    }

    function CreateSupplier($supplier_name,$phone_number,$location,$date){
        $supplier_id=$this->SupplierIdGen();

        $check_exists=mysqli_query($this->db,"SELECT * FROM suppliers WHERE (supplier_name='".$supplier_name."' OR supplier_id='".$supplier_id."') AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));

        if(mysqli_num_rows($check_exists) > 0){
          return 'Supplier already exists';
        }
        else {
          $insert_string=mysqli_query($this->db,"INSERT INTO suppliers
                                          (supplier_id,supplier_name,phone_number,location,date)
                                          VALUES
                                          ('$supplier_id','$supplier_name','$phone_number','$location','$date')
                                          ") or die(mysqli_error($this->db));
            if(mysql_affected_rows($this->db)==1){
              return 'save_successful';
            }
            else {
              return 'Unable to save supplier';
            }
        }//end else

    }//end function


    function SupplierInfo(){
      $query=mysqli_query($this->db,"SELECT *
                                                          FROM suppliers
                                                          WHERE supplier_id='".$this->supplier_id."' AND subscriber_id='".$this->active_subscriber."'
                                      ") or die(mysqli_error($this->db));

      $supplier_info=mysqli_fetch_array($query);
      $this->supplier_name=$supplier_info['supplier_name'];
      $this->phone_number=$supplier_info['phone_number'];
      $this->location=$supplier_info['location'];
      
    }

  }




 ?>
