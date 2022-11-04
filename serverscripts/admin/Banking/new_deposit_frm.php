<?php

    require '../../dbcon.php';
    require '../../Classes/Accounts.php';
    require '../../Classes/Banking.php';
    require '../../Classes/Subscribers.php';
    require '../../Classes/SystemEmails.php';

    $amount=clean_string($_GET['amount']);
    $source_account=clean_string($_GET['source_account']);
    $deposit_account=clean_string($_GET['deposit_account']);
    $deposit_date=clean_string($_GET['deposit_date']);
    $narration=clean_string($_GET['narration']);


    $banking=new Banking();

    $subscriber=new Subscriber();
    $subscriber->HospitalInfo();

    $mails=new SystemMail();

    $acc=new Account();
    $acc->account_number=$source_account;
    $acc->AccountInfo();
    $source_account_name=$acc->account_name;

    $acc=new Account();
    $acc->account_number=$deposit_account;
    $acc->AccountInfo();
    $deposit_account_name=$acc->account_name;


    // // record deposit
    $query=$banking->CreateDeposit($amount,$deposit_account,$source_account,$narration,$deposit_date);

    if($query=='save_successful'){
      $body = '
        Dear '.$subscriber->hospital_name.',

        Please be informed that
        An amount of '.$amount.' has been transfered from
        Your '.$source_account_name.' account to your '.$deposit_account_name.' account.

        Thank you

        ---------
        The Medici Team
        NT-0098-8712 -Education Ridge Road,
        Tamale - Northern Region.
        0246173282 | 0372020168

      ';

      $subject= 'xMedici | New Deposit Notification';
      $mails->SendMail($subscriber->hospital_email,$subject,$body);

      echo 'save_successful';
    }


 ?>
