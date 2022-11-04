<?php

  /**
   * Payments
   */
  class Subscriber{

    Public $active_subscriber='';

    function __construct(){
      $this->db=mysqli_connect('localhost','root','@Tsung3#','xMedici') or die("Check Connection");
      $this->mysqli=new mysqli('localhost','root','@Tsung3#','xMedici');

      $sql="CREATE TABLE IF NOT EXISTS subscriptions (
        sn int NOT NULL,
        subscriber_id varchar(32) COLLATE utf8_unicode_ci NOT NULL,
        hospital_name text COLLATE utf8_unicode_ci NOT NULL,
        address text COLLATE utf8_unicode_ci NOT NULL,
        phone_numbers text COLLATE utf8_unicode_ci NOT NULL,
        email text COLLATE utf8_unicode_ci NOT NULL,
        status text COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (sn)
      )";

      $this->mysqli->query($sql);

      $sql="CREATE TABLE IF NOT EXISTS syst_departments (
        sn int NOT NULL AUTO_INCREMENT,
        department_id varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        department_name text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (sn)
      )";

      $this->mysqli->query($sql);

      $sql="INSERT IGNORE INTO syst_departments (sn, department_id, department_name) VALUES
      (1, '001', 'Registration / Information'),
      (2, '002', 'Nurses Station'),
      (3, '003', 'Consulting Room'),
      (4, '004', 'Accident And Emergency'),
      (5, '005', 'Laboratory'),
      (6, '006', 'Blood Bank'),
      (7, '007', 'Pharmacy'),
      (8, '008', 'Operating Theatre'),
      (9, '009', 'Medical Imaging / Radiology'),
      (10, '010', 'Physiotherapy'),
      (11, '011', 'Obstetrics / Gyenaecology'),
      (12, '012', 'Orthopaedic'),
      (13, '013', 'Accounts'),
      (14, '014', 'Urology')";

      $this->mysqli->query($sql);



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
