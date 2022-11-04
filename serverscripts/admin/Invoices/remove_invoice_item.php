<?php
    require_once '../../dbcon.php';
    require_once '../../Classes/Invoices.php';

    $sn=clean_string($_GET['sn']);
    $invoice_id=clean_string($_GET['invoice_id']);

    $invoice=new Invoice();
    $invoice->invoice_id=$invoice_id;

    echo $invoice->DeleteInvoiceItem($sn);
 ?>
