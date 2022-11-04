<?php

  /**
   * Expenditure
   */
  class Expenditure{

    Public $expenditure_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','root','@Tsung3#','xMedici') or die("Check Connection");
      $this->mysqli=new mysqli('localhost','root','@Tsung3#','xMedici');

      $sql="CREATE TABLE IF NOT EXISTS expenditure (
        sn int NOT NULL AUTO_INCREMENT,
        subscriber_id varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        expenditure_id varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        expenditure_account varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        description text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        amount double(10,2) NOT NULL,
        payment_account varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
        notes text COLLATE utf8_unicode_ci NOT NULL,
        date date NOT NULL,
        status text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
        timestamp varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
        PRIMARY KEY (sn)
      )";

      $this->mysqli->query($sql);

      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];

    }

    function GetExpenditureHeaders(){
      $query=mysqli_query($this->db,"SELECT * FROM expenditure_headers WHERE (subscriber_id='".$this->active_subscriber."' AND status='active') OR (subscriber_id='default' AND status='active') ") or die(msyqli_error($this->db));
      return $query;
    }//end function

    function DeleteExpenditure(){
      $query=mysqli_query($this->db,"UPDATE expenditure SET status='deleted' WHERE expenditure_id='".$this->expenditure_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete expenditure';
      }
    }//end function

    function ExpenditureInfo(){
      $query=mysqli_query($this->db,"SELECT *
                                                          FROM expenditure
                                                          WHERE
                                                            expenditure_id='".$this->expenditure_id."' AND
                                                            subscriber_id='".$this->active_subscriber."' AND status='active'
                                      ") or die(mysqli_error($this->db));

      $expenditure_info=mysqli_fetch_array($query);
      $this->description=$expenditure_info['description'];
      $this->amount=$expenditure_info['amount'];
      $this->expenditure_account=$expenditure_info['expenditure_account'];
      $this->payment_account=$expenditure_info['payment_account'];
      $this->notes=$expenditure_info['notes'];
      $this->date=$expenditure_info['date'];
    } //end ExpenditureInfo

    function UpdateExpenditure($expenditure_id,$description,$amount,$expenditure_account,$payment_account,$notes,$date){
        $query=mysqli_query($this->db,"UPDATE expenditure
                                                            SET
                                                              description='".$description."',
                                                              amount='".$amount."',
                                                              expenditure_account='".$expenditure_account."',
                                                              payment_account='".$payment_account."',
                                                              notes='".$notes."',
                                                              date='".$date."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND expenditure_id='".$expenditure_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to updated expenditure';
          }
    }// end update


    function CreateExpenditure($expenditure_id,$description,$amount,$expenditure_account,$payment_account,$notes,$date,$timestamp){
      $checkstring=mysqli_query($this->db,"SELECT * FROM expenditure WHERE expenditure_id='".$expenditure_id."' && subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) != 0){
        return  'An expense with there details exists';
      }
      else {

        $table='expenditure';
        $fields=array("subscriber_id","expenditure_id","expenditure_account","description","amount","payment_account","status","notes","date","timestamp");
        $values=array("$this->active_subscriber","$expenditure_id","$expenditure_account","$description","$amount","$payment_account","active","$notes","$date","$timestamp");
        $query=insert_data($this->db,$table,$fields,$values);

        return 'save_successful';
      }
    }// end Create


    function ExpenditurePeriod($start,$end){
      $query=mysqli_query($this->db,"SELECT SUM(amount) as total_expenditure
                                                          FROM expenditure
                                                          WHERE
                                                            date BETWEEN '".$start."' AND '".$end."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                        ") or die(msyqli_error($this->db));
      $total_expenditure=mysqli_fetch_array($query);
      return $total_expenditure['total_expenditure'];
    }


    function ExpenditurePeriodQuery($start,$end,$expenditure_account){
      if($expenditure_account=='all'){
        $query=mysqli_query($this->db,"SELECT *
                                                            FROM expenditure
                                                            WHERE
                                                              date BETWEEN '".$start."' AND '".$end."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                          ") or die(msyqli_error($this->db));
      }else {
        $query=mysqli_query($this->db,"SELECT *
                                                            FROM expenditure
                                                            WHERE
                                                              date BETWEEN '".$start."' AND '".$end."' AND expenditure_account='".$expenditure_account."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                          ") or die(msyqli_error($this->db));
      }

      return $query;
    }

  }



 ?>
