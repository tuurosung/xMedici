<?php

  /**
   * Payments
   */
  class Subscriber{

    Public $active_subscriber='';

    function __construct(){
      $this->db=mysqli_connect('localhost','root','@Tsung3#','xMedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
    }

    function HospitalInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM subscriptions WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $subscriber_info=mysqli_fetch_array($query);

      $this->hospital_name=$subscriber_info['hospital_name'];
      $this->hospital_phone=$subscriber_info['phone_numbers'];
      $this->hospital_address=$subscriber_info['address'];
      $this->hospital_email=$subscriber_info['email'];
      // $this->subscription_plan=$subscriber_info['subscription_plan'];

      // $this->last_payment_date=$subscriber_info['last_payment_date'];
      // $this->next_payment_date=$subscriber_info['next_payment_date'];

      // $today=date('Y-m-d');
      // $today=new DateTime($today);
      // $expiry=new DateTime($this->next_payment_date);
      // $differece=$today->diff($expiry);
      // $this->days_remaining=$differece->format('%a');
      // $this->days_remaining_negpo=$differece->format('%R%a');
      //
      // $last_payment_date=new DateTime($this->last_payment_date);
      // $total_days=$last_payment_date->diff($expiry);
      // $this->total_subscription_days=$total_days->format('%a');
      //
      // $this->percentage=number_format(($this->days_remaining/$this->total_subscription_days)*100,2);

    }//end SubscriberInfo();



  }

 ?>
