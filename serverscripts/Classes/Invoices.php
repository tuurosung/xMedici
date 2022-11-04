<?php
  /**
   *
   */
  class Invoice {
    Public $invoice_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");

      if(!isset($_SESSION['active_subscriber']) || !isset($_SESSION['active_user']) || $_SESSION['active_subscriber']=='' || $_SESSION['active_user']==''){
        die('session_expired');
      }else {
        $this->active_subscriber=$_SESSION['active_subscriber'];
        $this->active_user=$_SESSION['active_user'];
      }

      $this->suffix=date('y');
      $this->today=date('Y-m-d');
    }

    function InvoiceIdGen(){
      $query=mysqli_query($this->db,"SELECT count(*) as count FROM invoices WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      $len=strlen($count);
      $prefix=prefix($len);
      return 'INV'.$prefix.''.$count;
    }

    function InvoiceSales($start,$end){
      $query=mysqli_query($this->db,"SELECT SUM(total) as total_invoice_values FROM invoices WHERE subscriber_id='".$this->active_subscriber."' AND status='active' AND date_created BETWEEN '".$start."' AND '".$end."'") or die(mysqli_error($this->db));
      $total_invoice_values=mysqli_fetch_array($query);
      return $total_invoice_values['total_invoice_values'];
    }

    function InvoicePayments($start,$end){
      $query=mysqli_query($this->db,"SELECT SUM(amount_paid) as total_invoice_sales FROM invoices WHERE subscriber_id='".$this->active_subscriber."' AND status='active' AND date_issued BETWEEN '".$start."' AND '".$end."'") or die(mysqli_error($this->db));
      $total_invoice_sales=mysqli_fetch_array($query);
      return $total_invoice_sales['total_invoice_sales'];
    }

    function CreateInvoice($invoice_type,$patient_id,$ref,$due_date,$created_by,$footer_notes){
      $invoice_id=$this->InvoiceIdGen();
      $check_exists=mysqli_query($this->db,"SELECT *
                                                                      FROM invoices
                                                                      WHERE
                                                                          invoice_id='".$invoice_id."' AND subscriber_id='".$this->active_subscriber."'
                                                    ") or die(mysqli_error($this->db));

      if(mysqli_num_rows($check_exists) ==0){
        $table='invoices';
        $fields=array("subscriber_id","invoice_type","invoice_id","patient_id","ref","date_created","due_date","status","created_by","footer_notes");
        $values=array("$this->active_subscriber","$invoice_type","$invoice_id","$patient_id","$ref","$this->today","$due_date","active","$this->active_user","$footer_notes");
        $insert_query=insert_data($this->db,$table,$fields,$values);

          if($insert_query){
            $_SESSION['active_invoice']=$invoice_id;
            return 'save_successful';
          }else {
            return 'Unable to save invoice';
          }

      }else {
        return 'Sorry, Invoice already exists';
      }
    }//end function

    function InvoiceInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM invoices WHERE invoice_id='".$this->invoice_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $invoice_info=mysqli_fetch_array($query);

      $this->invoice_type=$invoice_info['invoice_type'];
      $this->patient_id=$invoice_info['patient_id'];
      $this->visit_id=$invoice_info['visit_id'];
      $this->ref=$invoice_info['ref'];
      $this->sub_total=$invoice_info['sub_total'];
      $this->vat_percent=$invoice_info['vat_percent'];
      $this->vat_amount=$invoice_info['vat_amount'];
      $this->nhil_percent=$invoice_info['nhil_percent'];
      $this->nhil_amount=$invoice_info['nhil_amount'];
      $this->getfund_percent=$invoice_info['getfund_percent'];
      $this->getfund_amount=$invoice_info['getfund_amount'];
      $this->total=$invoice_info['total'];
      $this->date_created=$invoice_info['date_created'];
      $this->due_date=$invoice_info['due_date'];
      $this->created_by=$invoice_info['created_by'];

    }

    function DeleteInvoiceItem($sn){
      $query=mysqli_query($this->db,"UPDATE invoice_items SET status='deleted' WHERE sn='".$sn."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        $this->UpdateInvoiceSubTotal();
        return 'delete_successful';
      }else {
        return 'Unable to remove invoice item';
      }
    }

    function UpdateInvoiceSubTotal(){
      $invoice_items_sum=mysqli_query($this->db,"SELECT
                                                                                  SUM(total) as invoice_items_sum
                                                                              FROM invoice_items
                                                                              WHERE
                                                                                  invoice_id='".$this->invoice_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                                          ") or die(mysqli_error($this->db));
        $info=mysqli_fetch_array($invoice_items_sum);
        $this->invoice_items_sum=$info['invoice_items_sum'];

        $update_query=mysqli_query($this->db,"UPDATE invoices
                                                                        SET
                                                                          sub_total='$this->invoice_items_sum',
                                                                          vat_amount=((vat_percent/100)*sub_total),
                                                                          getfund_amount=((getfund_percent/100)*sub_total),
                                                                          nhil_amount=((nhil_percent/100)*sub_total),
                                                                          total=(vat_amount+getfund_amount+nhil_amount)+sub_total
                                                                        WHERE
                                                                          invoice_id='".$this->invoice_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'
                                                      ") or die(mysqli_error($this->db));

    }


    function InvoiceConfigData($tin_number,$vat_percent,$nhil_percent,$getfund_percent,$currency,$tagline){
        $check_exists=mysqli_query($this->db,"SELECT * FROM invoice_config WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if(mysqli_num_rows($check_exists)==0){
          $table='invoice_config';
          $fields=array("subscriber_id","tin_number","vat_percent","nhil_percent","getfund_percent","currency","invoice_tagline");
          $values=array("$this->active_subscriber","$tin_number","$vat_percent","$nhil_percent","$getfund_percent","$currency","$tagline");
          $insert_query=insert_data($this->db,$table,$fields,$values);

          if(mysqli_affected_rows($this->db)==1){
            $this->InvoiceTerms('Goods sold are not returnable.');
            $this->InvoiceTerms('This invoice is valid for 30 days after issuance.');
            $this->InvoiceTerms('All cheques must be written in the company name');
            $this->InvoiceTerms('Remember to quote invoice number on cheque payments');
            return 'save_successful';
          }else {
            return 'unable to create config data';
          }
        }
    }//end config

    function InvoiceConfigInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM invoice_config WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $invoice_config_info=mysqli_fetch_array($query);

      $this->tin_number=$invoice_config_info['tin_number'];
      $this->config_vat_percent=$invoice_config_info['vat_percent'];
      $this->config_nhil_percent=$invoice_config_info['nhil_percent'];
      $this->config_getfund_percent=$invoice_config_info['getfund_percent'];
      $this->currency=$invoice_config_info['currency'];
      $this->tagline=$invoice_config_info['invoice_tagline'];
    }


    function DeleteInvoice(){
      if(empty($this->invoice_id)){
        echo "No invoice selected";
      }else {
        $delete_invoice_items = mysqli_query($this->db,"UPDATE invoice_items SET status='deleted' WHERE invoice_id='".$this->invoice_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        if($delete_invoice_items){
          $delete_invoice=mysqli_query($this->db,"UPDATE invoices SET status='deleted' WHERE invoice_id='".$this->invoice_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
          if($delete_invoice){
            return 'delete_successful';
          }else {
            return 'Unable to delete invoice';
          }//end if
        }//end if
      }//end if
    }//end function

  }

 ?>
