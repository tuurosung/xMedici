<?php

  /**
   * Account
   */
  class Account{

    public $account_number='';

    function __construct(){

        $this->db=mysqli_connect('localhost','root','@Tsung3#','xMedici') or die("Check Connection");
        $this->mysqli=new mysqli('localhost','root','@Tsung3#','xMedici');

        $this->active_subscriber=$_SESSION['active_subscriber'];
        $this->user_id=$_SESSION['active_user'];

    }

  function AccountIdGen(){
        $sql = "SELECT COUNT(*) AS count FROM all_accounts WHERE  subscriber_id='" . $this->active_subscriber . "'";
        $r=$this->mysqli->query($sql);
        $count = $r->fetch_assoc();
        $count = ++$count['count'];
        $len = strlen($count);
        if ($len < 10) {
          $prefix = '00';
        } else {
          $prefix = '';
        }
        return $prefix . '' . $count;
  }


    function NewAccount($account_name,$account_header,$description){
        $check="SELECT * FROM all_accounts WHERE account_name='".$account_name."' AND subscriber_id='".$this->active_subscriber."'";
        $r=$this->mysqli->query($check);
        if($r->num_rows ==0){

          $account_number=$this->AccountIdGen();

          $table = 'all_accounts';
          $fields = array("subscriber_id", "account_number", "account_name", "account_header", "description");
          $values = array("$this->active_subscriber", "$account_number", "$account_name", "$account_header", "$description");
          $query = insert_data($this->db, $table, $fields, $values);

          if ($query) {
            echo 'save_successful';
          } else {
            echo 'failed';
          }

        }else {
          return 'Failed. Duplicate Account Exists'; 
        }
        

        

    }

    function AccountInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM all_accounts WHERE account_number='".$this->account_number."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $account_info=mysqli_fetch_array($query);
      $this->account_name=$account_info['account_name'];

      $total_inflow=mysqli_query($this->db,"SELECT SUM(amount_paid) as total_inflow FROM payments WHERE payment_account='".$this->account_number."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $total_inflow=mysqli_fetch_array($total_inflow);
      $this->total_inflow=$total_inflow['total_inflow'];

      $total_expenditure=mysqli_query($this->db,"SELECT SUM(amount) as total_expenditure FROM expenditure WHERE payment_account='".$this->account_number."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $total_expenditure=mysqli_fetch_array($total_expenditure);
      $this->total_expenditure=$total_expenditure['total_expenditure'];

      $total_transfers=mysqli_query($this->db,"SELECT SUM(amount) as total_transfers FROM banking WHERE source_account='".$this->account_number."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $total_transfers=mysqli_fetch_array($total_transfers);
      $this->total_transfers=$total_transfers['total_transfers'];


      $total_transfers_received=mysqli_query($this->db,"SELECT SUM(amount) as total_transfers_received FROM banking WHERE deposit_account='".$this->account_number."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $total_transfers_received=mysqli_fetch_array($total_transfers_received);
      $this->total_transfers_received=$total_transfers_received['total_transfers_received'];

      $this->account_balance=$this->total_inflow + $this->total_transfers_received - $this->total_expenditure - $this->total_transfers;


    }
  }



 ?>
