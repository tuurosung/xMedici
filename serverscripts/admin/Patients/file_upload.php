<?php
require_once '../../dbcon.php';
$patient_id=clean_string($_GET['patient_id']);
$visit_id=clean_string($_GET['visit_id']);
$date=date('Y-m-d');

$get_count=mysqli_query($db,"SELECT COUNT(*)  AS file_count FROM file_uploads WHERE subscriber_id='".$active_subscriber."' AND visit_id='".$visit_id."'") or die(mysqli_error($db));
$file_count=mysqli_fetch_array($get_count);
$file_count=++$file_count['file_count'];


$ds          = DIRECTORY_SEPARATOR;  //1

$storeFolder = '../../../FileUploads';   //2

if (!empty($_FILES)) {

    $tempFile = $_FILES['file']['tmp_name'];          //3

    $filename=$_FILES['file']['name'];

    $extension=pathinfo($filename,PATHINFO_EXTENSION);

    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

    $targetFile =  $targetPath.''.$visit_id.'_'.$file_count.'.'.$extension;  //5

    move_uploaded_file($tempFile,$targetFile); //6

    $file_name=$visit_id.'_'.$file_count.'.'.$extension;

    $table='file_uploads';
    $fields=array("subscriber_id","visit_id","file_name","date");
    $values=array("$active_subscriber","$visit_id","$file_name","$date");
    insert_data($db,$table,$fields,$values);

}
?>
