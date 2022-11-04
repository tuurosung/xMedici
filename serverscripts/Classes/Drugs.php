<?php

  /**
   * Class for sales
   */
  class Drug {

    Public $drug_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
      $this->today=date('Y-m-d');

      // Count Drugs
      $query=mysqli_query($this->db,"SELECT COUNT(*) AS total_drugs
                                                          FROM pharm_inventory
                                                          WHERE subscriber_id='".$this->active_subscriber."'
                                        ") or die(mysqli_error($this->db));
      $total_drugs=mysqli_fetch_array($query);
      $this->total_drugs=$total_drugs['total_drugs'];


      // Count Active
      $query=mysqli_query($this->db,"SELECT COUNT(*) AS total_active
                                                          FROM pharm_inventory
                                                          WHERE subscriber_id='".$this->active_subscriber."' AND status='active'
                                        ") or die(mysqli_error($this->db));
      $total_active=mysqli_fetch_array($query);
      $this->total_active=$total_active['total_active'];

      // Count Deleted
      $query=mysqli_query($this->db,"SELECT COUNT(*) AS total_deleted
                                                          FROM pharm_inventory
                                                          WHERE subscriber_id='".$this->active_subscriber."' AND status='deleted'
                                        ") or die(mysqli_error($this->db));
      $total_deleted=mysqli_fetch_array($query);
      $this->total_deleted=$total_deleted['total_deleted'];

      $stock_report=mysqli_query($this->db,"
      SELECT SUM(actual_value) as actual_stock_value, SUM(expected_value) as expected_stock_value, SUM(profit_margin) as total_profit_margin FROM (

      SELECT 	i.drug_id,
      i.cost_price,
      i.retail_price,
      IFNULL(stocked.qty_stocked,0) as total_stocked,
      IFNULL(sold.qty_sold,0) as total_sold,
      IFNULL(stocked.qty_stocked,0) - IFNULL(sold.qty_sold,0) - IFNULL(sold_walkin.qty_sold_walkin,0) as qty_rem,
      (IFNULL(stocked.qty_stocked,0) - IFNULL(sold.qty_sold,0) - IFNULL(sold_walkin.qty_sold_walkin,0)) * i.cost_price as actual_value,
      (IFNULL(stocked.qty_stocked,0) - IFNULL(sold.qty_sold,0)  - IFNULL(sold_walkin.qty_sold_walkin,0)) * i.retail_price as expected_value,
      ((IFNULL(stocked.qty_stocked,0) - IFNULL(sold.qty_sold,0) - IFNULL(sold_walkin.qty_sold_walkin,0)) * i.retail_price) - ((IFNULL(stocked.qty_stocked,0) - IFNULL(sold.qty_sold,0) - IFNULL(sold_walkin.qty_sold_walkin,0)) * i.cost_price) as profit_margin
      FROM pharm_inventory i


      left join (
      SELECT drug_id,SUM(qty_stocked) as qty_stocked FROM stock WHERE subscriber_id='".$this->active_subscriber."' AND status='active'
      GROUP BY drug_id
      ) stocked on i.drug_id=stocked.drug_id

      left join (
      SELECT drug_id,SUM(qty) as qty_sold FROM pharm_cart WHERE subscriber_id='".$this->active_subscriber."'
      GROUP BY drug_id
      )sold ON i.drug_id=sold.drug_id

      left join (
      SELECT drug_id,SUM(qty) as qty_sold_walkin FROM pharm_walkin_cart WHERE subscriber_id='".$this->active_subscriber."'
      GROUP BY drug_id
      )sold_walkin ON i.drug_id=sold_walkin.drug_id

      WHERE i.subscriber_id='".$this->active_subscriber."'
      )stock_value


      ") or die(mysqli_error($this->db));

        $stock_report=mysqli_fetch_array($stock_report);
        $this->total_stock_value=$stock_report['actual_stock_value'];
        $this->total_expected_stock_value=$stock_report['expected_stock_value'];
        $this->total_profit_margin=$stock_report['total_profit_margin'];

    }

    function DrugIdGen(){
        $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM pharm_inventory WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
        $count=mysqli_fetch_assoc($query);
        $count=++$count['count'];
        return 'DRUG'.prefix($count).''.$count;
    }

    function ManufacturerIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM pharm_manufacturers WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'MNF'.prefix($count).''.$count;
    }

    function DrugInfo(){
      $query=mysqli_query($this->db,"SELECT *
                                                          FROM pharm_inventory
                                                          WHERE drug_id='".$this->drug_id."'  AND subscriber_id='".$this->active_subscriber."'
                                      ") or die(mysqli_error($this->db));

      $drug_info=mysqli_fetch_array($query);
      $this->generic_name=$drug_info['generic_name'];
      $this->trade_name=$drug_info['trade_name'];
      $this->drug_name=$drug_info['generic_name'].' '."(<strong>".$drug_info['trade_name']."</strong>)";
      $this->unit=$drug_info['unit'];
      $this->category=$drug_info['category'];
      $this->cost_price=$drug_info['cost_price'];
      $this->retail_price=$drug_info['retail_price'];
      $this->profit=$drug_info['retail_price'] - $drug_info['cost_price'];
      $this->manufacturer=$drug_info['manufacturer'];
      $this->shelf=$drug_info['shelf'];
      $this->status=$drug_info['status'];

      $get_stocked=mysqli_query($this->db,  "SELECT SUM(qty_stocked) as qty_stocked FROM stock WHERE drug_id='".$this->drug_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $qty_stocked=mysqli_fetch_assoc($get_stocked);
      $this->qty_stocked=$qty_stocked['qty_stocked'];

      $get_sold=mysqli_query($this->db,"SELECT SUM(qty) as qty_sold FROM pharm_cart WHERE drug_id='".$this->drug_id."' AND  subscriber_id='".$this->active_subscriber."' AND status='CHECKOUT'") or die(mysqli_error($this->db));
      $get_sold=mysqli_fetch_array($get_sold);
      $this->qty_sold=$get_sold['qty_sold'];

      $get_sold_walkin=mysqli_query($this->db,"SELECT SUM(qty) as qty_sold FROM pharm_walkin_cart WHERE drug_id='".$this->drug_id."' AND  subscriber_id='".$this->active_subscriber."' AND checkout_status='CHECKOUT'") or die(mysqli_error($this->db));
      $get_sold_walkin=mysqli_fetch_array($get_sold_walkin);
      $this->qty_sold_walkin=$get_sold_walkin['qty_sold'];

      $get_reduced=mysqli_query($this->db,"SELECT SUM(qty) as qty_reduced FROM pharm_reducestock WHERE drug_id='".$this->drug_id."' AND  subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $get_reduced=mysqli_fetch_array($get_reduced);
      $this->qty_reduced=$get_reduced['qty_reduced'];

      $this->qty_rem=(int)$this->qty_stocked - (int)$this->qty_sold - (int)$this->qty_sold_walkin - (int) $this->qty_reduced;

      $this->actual_stock_value=$this->qty_rem*$this->cost_price;
      $this->expected_stock_value=$this->qty_rem*$this->retail_price;
      $this->profit_margin=$this->expected_stock_value - $this->actual_stock_value;

    }


    function CreateDrug($unit,$generic_name,$trade_name,$category,$manufacturer,$shelf,$restock_level,$cost_price,$retail_price,$date){
      $drug_id=$this->DrugIdGen();
      $check_exists=mysqli_query($this->db,"SELECT * FROM pharm_inventory WHERE (drug_id='".$drug_id."' OR trade_name='".$trade_name."') AND  subscriber_id='".$this->active_subscriber."'") or die(msyqli_error($this->db));
      if(mysqli_num_rows($check_exists) > 0){

        return "Similar Drug Exists";

      }else {

        $table='pharm_inventory';
        $fields=array("subscriber_id","drug_id","unit","generic_name","trade_name","category","manufacturer","shelf","restock_level","cost_price","retail_price","date","status");
        $values=array("$this->active_subscriber","$drug_id","$unit","$generic_name","$trade_name","$category","$manufacturer","$shelf","$restock_level","$cost_price","$retail_price","$date","active");
        $query=insert_data($this->db,$table,$fields,$values);

        session_start();
        $_SESSION['active_drug']=$drug_id;
        return  $query;
      }//end if

    }//End CreateDrug

    function EditDrug($drug_id,$unit,$generic_name,$trade_name,$category,$manufacturer,$shelf,$restock_level,$cost_price,$retail_price,$date){



      $update_drug=mysqli_query($this->db,"UPDATE pharm_inventory SET
                                          unit='".$unit."',
                                          generic_name='".$generic_name."',
                                          trade_name='".$trade_name."',
                                          category='".$drug_category."',
                                          manufacturer='".$manufacturer."',
                                          restock_level='".$restock_level."',
                                          cost_price='".$cost_price."',
                                          retail_price='".$retail_price."',
                                           shelf='".$shelf."'
                                          WHERE drug_id='".$drug_id."' AND subscriber_id='".$this->active_subscriber."'
                                    ") or die(mysqli_error($this->db));

        if($update_drug){
          return 'update_successful';
        }else {
          return 'Unable to edit drug';
        }
    }



    function DeleteDrug(){
      if(isset($this->drug_id)){
        $delete_query=mysqli_query($this->db,"UPDATE pharm_inventory
                                                                        SET status='deleted'
                                                                        WHERE drug_id='".$this->drug_id."' AND subscriber_id='".$this->active_subscriber."'
                                                  ") or die(mysqli_error($this->db));
        if(mysqli_affected_rows($this->db)==1){
          mysqli_query($this->db,"UPDATE stock SET status='deleted' WHERE drug_id='".$this->drug_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($db));
          return 'delete_successful';
        }else {
          return 'Unable to delete drug';
        }//end if
      }//end if
    }//EndDeleteDrug


    function CreateManufacturer($manufacturer_name,$address){
      $manufacturer_id=$this->ManufacturerIdGen();

      $checkstring=mysqli_query($this->db,"SELECT * FROM pharm_manufacturers WHERE (manufacturer_id='".$manufacturer_id."' OR  name='".$manufacturer_name."') AND  subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) ==0){

        $table='pharm_manufacturers';
        $fields=array("subscriber_id","manufacturer_id","name","address","date","status");
        $values=array("$this->active_subscriber","$manufacturer_id","$manufacturer_name","$address","$this->today","active");
        $query=insert_data($this->db,$table,$fields,$values);
        return $query;

      }else {
        return 'Similar record exists';
      }
    }

    function CategoryName($category_id){
      $get_category=mysqli_query($this->db,"SELECT * FROM pharm_drug_category WHERE category_id='".$category_id."'") or die(mysqli_error($this->db));
      $category_info=mysqli_fetch_array($get_category);
      return $category_info['category_name'];
    }

    function AddStock($drug_id,$batch_code,$qty_stocked,$stocking_date,$qty_sold,$qty_rem,$expiry_date,$timestamp){
      $table='stock';
      $fields=array("subscriber_id","drug_id","batch_code","qty_stocked","stock_date","qty_rem","expiry_date","timestamp","status");
      $values=array("$this->active_subscriber","$drug_id","$batch_code","$qty_stocked","$stocking_date","$qty_stocked","$expiry_date","$timestamp","active");
      $query=insert_data($this->db,$table,$fields,$values);

      return $query;
    }


    function ReduceStock($drug_id,$qty,$narration,$date,$timestamp){
      $table='pharm_reducestock';
      $fields=array("subscriber_id","drug_id","qty","narration","date","timestamp","status","user_id");
      $values=array("$this->active_subscriber","$drug_id","$qty","$narration","$date","$timestamp","active","$this->user_id");
      $query=insert_data($this->db,$table,$fields,$values);
      return $query;
    }

    function DeleteStock($drug_id,$batch_code){
      $update_stock=mysqli_query($this->db,"UPDATE stock SET status='deleted' WHERE drug_id='".$drug_id."' AND batch_code='".$batch_code."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if($update_stock){
        return 'delete_successful';
      }else {
        return 'Unable to reduce stock';
      }
    }



    function AddToCart($patient_id,$visit_id,$drug_id,$retail_price,$qty,$total,$pharm_id){
      $checkstring=mysqli_query($this->db,"SELECT * FROM pharm_cart WHERE visit_id='".$visit_id."' AND patient_id='".$patient_id."' AND drug_id='".$drug_id."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring)>0){
        return 'Drug already in cart';
      }else {
        $table='pharm_cart';
        $fields=array("subscriber_id","patient_id","visit_id","drug_id","retail_price","qty","total","status","pharm_id","date");
        $values=array("$this->active_subscriber","$patient_id","$visit_id","$drug_id","$retail_price","$qty","$total","active","$pharm_id","$this->today");
        $query=insert_data($this->db,$table,$fields,$values);
        if($query){
          mysqli_query($this->db,"UPDATE prescriptions SET dispensary_status='CART' WHERE drug_id='".$drug_id."' AND  patient_id='".$patient_id."' AND visit_id='".$visit_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
          return $query;
        }else {
          return 'Unable to add drug to cart';
        }
      }
    }

    function Expiry($expiry_date){
      $expiry_date=strtotime($expiry_date);
      $today=strtotime($this->today);

      $interval=$expiry_date-$today;

      return abs(round($interval/86400));
    }

    function ManufacturerInfo($manufacturer_id){
      $query=mysqli_query($this->db,"SELECT * FROM manufacturers WHERE manufacturer_id='".$manufacturer_id."'") or die(mysqli_error($this->db));
      $info=mysqli_fetch_array($query);
      return $info['name'];
    }


  }//end Class





 ?>
