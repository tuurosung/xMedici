<?php

  // include 'Subscribers.php';


  /**
   * Banking
   */
  class Banking{

    Public $deposit_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
      $this->timestamp=time();

    }

    function DepositIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM banking WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'DEP'.prefix($count).''.$count;
    }


    function DeleteDeposit(){
      if($this->deposit_id==''){
        return 'Deposit not found';
      }else {
        $query=mysqli_query($this->db,"UPDATE banking SET status='deleted' WHERE deposit_id='".$this->deposit_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if(mysqli_affected_rows($this->db)==1){
          return 'delete_successful';
        }else {
          return 'Unable to delete deposit';
        }
      }
    }//end function

    function BankingInfo(){
      $query=mysqli_query($this->db,"SELECT *
                                                          FROM banking
                                                          WHERE
                                                            deposit_id='".$this->deposit_id."' AND
                                                            subscriber_id='".$this->active_subscriber."' AND status='active'
                                      ") or die(mysqli_error($this->db));

      $deposit_info=mysqli_fetch_array($query);
      $this->amount=$deposit_info['amount'];
      $this->deposit_account=$deposit_info['deposit_account'];
      $this->source_account=$deposit_info['source_account'];
      $this->narration=$deposit_info['narration'];
      $this->date=$deposit_info['date'];
    } //end BankingInfo

    function UpdateDeposit($deposit_id,$narration,$amount,$deposit_account,$source_account,$date){
        $query=mysqli_query($this->db,"UPDATE banking
                                                            SET
                                                              narration='".$narration."',
                                                              amount='".$amount."',
                                                              deposit_account='".$deposit_account."',
                                                              source_account='".$source_account."',
                                                              date='".$date."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND deposit_id='".$deposit_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to updated banking';
          }
    }// end update


    function CreateDeposit($amount,$deposit_account,$source_account,$narration,$date){
      $deposit_id=$this->DepositIdGen();
      $checkstring=mysqli_query($this->db,"SELECT * FROM banking WHERE deposit_id='".$deposit_id."' && subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) != 0){
        return  'An deposit with these details exists';
      }
      else {

        $table='banking';
        $fields=array("subscriber_id","deposit_id","amount","deposit_account","source_account","narration","date","timestamp","user_id");
        $values=array("$this->active_subscriber","$deposit_id","$amount","$deposit_account","$source_account","$narration","$date","$this->timestamp","$this->user_id");
        $query=insert_data($this->db,$table,$fields,$values);

        return 'save_successful';
      }
    }// end CreateDeposit


    function BankingPeriod($start,$end){
      $query=mysqli_query($this->db,"SELECT SUM(amount) as total_deposit
                                                          FROM banking
                                                          WHERE
                                                            date BETWEEN '".$start."' AND '".$end."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                        ") or die(msyqli_error($this->db));
      $total_deposit=mysqli_fetch_array($query);
      return $total_deposit['total_deposit'];
    }




  }



 ?>
