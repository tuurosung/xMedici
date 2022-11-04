<?php

  /**
   * Expenditure
   */
  class Test{

    Public $test_id='';

    function __construct(){
      $this->db=mysqli_connect('localhost','shaabd_xmedici','@Tsung3#','shaabd_xmedici') or die("Check Connection");
      $this->active_subscriber=$_SESSION['active_subscriber'];
      $this->user_id=$_SESSION['active_user'];
      $this->today=date('Y-m-d');
      $this->timestamp=time();


      $all_tests=mysqli_query($this->db,"SELECT COUNT(*)  as all_tests FROM lab_requests_tests WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $all=mysqli_fetch_array($all_tests);
      $this->count_all_tests=$all['all_tests'];

      $complete_tests=mysqli_query($this->db,"SELECT COUNT(*)  as complete_tests FROM lab_requests_tests WHERE subscriber_id='".$this->active_subscriber."' AND status='active' AND results_status='complete'") or die(mysqli_error($this->db));
      $complete_tests=mysqli_fetch_array($complete_tests);
      $this->count_complete_tests=$complete_tests['complete_tests'];


      $pending_tests=mysqli_query($this->db,"SELECT COUNT(*)  as pending_tests FROM lab_requests_tests WHERE subscriber_id='".$this->active_subscriber."' AND status='active' AND results_status='pending'") or die(mysqli_error($this->db));
      $pending_tests=mysqli_fetch_array($pending_tests);
      $this->count_pending_tests=$pending_tests['pending_tests'];

      $cancelled_tests=mysqli_query($this->db,"SELECT COUNT(*)  as cancelled_tests FROM lab_requests_tests WHERE subscriber_id='".$this->active_subscriber."' AND status='cancelled' AND results_status='cancelled'") or die(mysqli_error($this->db));
      $cancelled_tests=mysqli_fetch_array($cancelled_tests);
      $this->count_cancelled_tests=$cancelled_tests['cancelled_tests'];

    }

    function TestIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM lab_tests WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'TEST'.prefix($count).''.$count;
    }

    function ParameterIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM lab_tests_parameters WHERE subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'PARA'.prefix($count).''.$count;
    }

    function CategoryIdGen(){
      $query=mysqli_query($this->db,"SELECT COUNT(*) as count FROM lab_test_categories") or die(mysqli_error($this->db));
      $count=mysqli_fetch_assoc($query);
      $count=++$count['count'];
      return 'TCAT'.prefix($count).''.$count;
    }



    function TestInfo(){
      $query=mysqli_query($this->db,"SELECT * FROM lab_tests WHERE test_id='".$this->test_id."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      $test_info=mysqli_fetch_array($query);
      $this->description=$test_info['description'];
      $this->test_category=$test_info['test_category'];
      $this->test_cost=$test_info['test_cost'];
      $this->specimen=$test_info['specimen'];
      $this->comment=$test_info['comment'];
      $this->status=$test_info['status'];
    } //end ServiceInfo


    function CreateTest($description,$test_category,$test_cost,$specimen,$comment,$date){
      $test_id=$this->TestIdGen();
      $checkstring=mysqli_query($this->db,"SELECT * FROM lab_tests WHERE (test_id='".$test_id."' OR description='".$description."')  AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) != 0){
        return  'A test with the same details exists';
      }
      else {

        $table='lab_tests';
        $fields=array("subscriber_id","test_id","description","test_category","test_cost","specimen","comment","status","date");
        $values=array("$this->active_subscriber","$test_id","$description","$test_category","$test_cost","$specimen","$comment","active","$date");
        $query=insert_data($this->db,$table,$fields,$values);

        return 'save_successful';
      }
    }// end Create

    function UpdateTest($test_id,$description,$test_category,$test_cost,$specimen,$comment){
        $query=mysqli_query($this->db,"UPDATE lab_tests
                                                            SET
                                                              description='".$description."',
                                                              test_category='".$test_category."',
                                                              test_cost='".$test_cost."',
                                                              specimen='".$specimen."',
                                                              comment='".$comment."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND test_id='".$test_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to update test';
          }
    }// end update




    function TestCategory($category_id){
      $query=mysqli_query($this->db,"SELECT * FROM lab_test_categories WHERE category_id='".$category_id."'") or die(mysqli_error($this->db));
      $category_info=mysqli_fetch_array($query);
      return $category_info['description'];
    } //end CategoryInfo


    function DeleteTest($test_id){
      $query=mysqli_query($this->db,"UPDATE lab_tests SET status='deleted' WHERE test_id='".$test_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete test';
      }
    }



    function AddParameter($test_id,$description,$unit,$general_min,$general_max,$male_min,$male_max,$female_min,$female_max,$child_min,$child_max){
      $checkstring=mysqli_query($this->db,"SELECT * FROM lab_tests_parameters WHERE test_id='".$test_id."' AND description='".$description."' AND subscriber_id='".$this->active_subscriber."' AND status='active'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) > 0){
        return 'Parameter Exists';
      }else {
        $parameter_id=$this->ParameterIdGen();
        $table='lab_tests_parameters';
        $fields=array("subscriber_id","test_id","parameter_id","description","unit","general_min","general_max","male_min","male_max","female_min","female_max","child_min","child_max","status");
        $values=array("$this->active_subscriber","$test_id","$parameter_id","$description","$unit","$general_min","$general_max","$male_min","$male_max","$female_min","$female_max","$child_min","$child_max","active");
        $query=insert_data($db,$table,$fields,$values);
        return $query;
      }
    }

    function UpdateParameter($parameter_id,$description,$unit,$general_min,$general_max,$male_min,$male_max,$female_min,$female_max,$child_min,$child_max){
        $query=mysqli_query($this->db,"UPDATE lab_tests_parameters
                                                            SET
                                                              description='".$description."',
                                                              unit='".$unit."',
                                                              general_min='".$general_min."',
                                                              general_max='".$general_max."',
                                                              male_min='".$male_min."',
                                                              male_max='".$male_max."',
                                                              female_min='".$female_min."',
                                                              female_max='".$female_max."',
                                                              child_min='".$child_min."',
                                                              child_max='".$child_max."'
                                                            WHERE
                                                              subscriber_id='".$this->active_subscriber."' AND parameter_id='".$parameter_id."'
                                        ") or die(mysqli_error($this->db));
          if(mysqli_affected_rows($this->db)==1){
            return 'update_successful';
          }
          else {
            return 'Unable to update parameter';
          }
    }// end update

    function DeleteParameter($parameter_id){
      $query=mysqli_query($this->db,"UPDATE lab_tests_parameters SET status='deleted' WHERE parameter_id='".$parameter_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete parameter';
      }
    }


    function ParameterInfo($parameter_id){
      $query=mysqli_query($this->db,"SELECT * FROM lab_tests_parameters WHERE parameter_id='".$parameter_id."' AND status='active' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $parameter_info=mysqli_fetch_array($query);

      if($parameter_info['male_min'] !=''){
        $this->parameter_type='variable';
      }else {
        $this->parameter_type='generic';
      }

      $this->parameter_name=$parameter_info['description'];
      $this->parameter_unit=$parameter_info['unit'];

      $this->parameter_general_min=$parameter_info['general_min'];
      $this->parameter_general_max=$parameter_info['general_max'];
      $this->parameter_male_min=$parameter_info['male_min'];
      $this->parameter_male_max=$parameter_info['male_max'];
      $this->parameter_female_min=$parameter_info['female_min'];
      $this->parameter_female_max=$parameter_info['female_max'];
    }


    function ResultsEntry($sn,$result_value){
      $query=mysqli_query($this->db,"UPDATE lab_requests_results SET result='".$result_value."' WHERE sn='".$sn."'") or die(mysqli_error($this->db));
      if($query){
        return 'save_successful';
      }else {
        return 'Unable to save test result';
      }
    }


    function CompleteTest($request_id,$test_id){

      $complete_request=mysqli_query($this->db,"UPDATE  lab_requests_tests
                                                                            SET
                                                                              results_status='complete'
                                                                            WHERE
                                                                              request_id='".$request_id."' AND test_id='".$test_id."' AND subscriber_id='".$this->active_subscriber."'
                                                          ") or die(mysqli_error($this->db));

      $confirm_results=mysqli_query($this->db,"UPDATE lab_requests_results
                                                                          SET
                                                                            status='confirmed'
                                                                          WHERE
                                                                            request_id='".$request_id."' AND test_id='".$test_id."' AND subscriber_id='".$this->active_subscriber."'
                                                        ") or die(mysqli_error($this->db));
      return 'save_successful';
    }



    // Test Categories

    function CreateCategory($category_name){
      $category_id=$this->CategoryIdGen();
      $checkstring=mysqli_query($this->db,"SELECT * FROM lab_test_categories WHERE (category_id='".$category_id."' OR description='".$category_name."')  AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) != 0){
        return  'Category exists';
      }
      else {

        $table='lab_test_categories';
        $fields=array("subscriber_id","category_id","description","status","date");
        $values=array("$this->active_subscriber","$category_id","$category_name","active","$this->today");
        $query=insert_data($this->db,$table,$fields,$values);

        return 'save_successful';
      }
    }// end Create

    function DeleteCategory($category_id){
      $query=mysqli_query($this->db,"UPDATE lab_test_categories SET status='deleted' WHERE category_id='".$category_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      if(mysqli_affected_rows($this->db)==1){
        return 'delete_successful';
      }else {
        return 'Unable to delete test';
      }
    }//end delete


    function LabOrderInfo($request_id,$test_id){
      $query=mysqli_query($this->db,"SELECT * FROM lab_requests_tests WHERE request_id='".$request_id."' AND test_id='".$test_id."' AND subscriber_id='".$this->active_subscriber."'") or die(mysqli_error($this->db));
      $info=mysqli_fetch_array($query);
      $this->order_patient_id=$info['patient_id'];
      $this->order_visit_id=$info['visit_id'];
      $this->order_test_cost=$info['test_cost'];
      $this->order_results_status=$info['results_status'];
      $this->order_status=$info['status'];
      $this->order_date=$info['date'];
      $this->order_timestamp=$info['timestamp'];
    }

    function AddTestComments($test_id,$request_id,$comment,$scientist){
      $table='lab_test_results_comments';
      $fields=array("subscriber_id","request_id","test_id","comments","scientist","date","timestamp");
      $values=array("$this->active_subscriber","$request_id","$test_id","$comment","$scientist","$this->today","$this->timestamp");
      $query=insert_data($this->db,$table,$fields,$values);
      echo $query;
    }


    function CreateUnit($name,$unit){
      $checkstring=mysqli_query($this->db,"SELECT * FROM measurement_units WHERE unit='".$unit."'") or die(mysqli_error($this->db));
      if(mysqli_num_rows($checkstring) != 0){
        return  'Unit Already Exists';
      }
      else {
        $table='measurement_units';
        $fields=array("unit","name");
        $values=array("$unit","$name");
        $query=insert_data($this->db,$table,$fields,$values);
        return 'save_successful';
      }//end if
    }// end Create


  }



 ?>
