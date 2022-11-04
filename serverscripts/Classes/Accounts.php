<?php

  /**
   * Account
   */
  class Account{

    public $account_number='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
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
