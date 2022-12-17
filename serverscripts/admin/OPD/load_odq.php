<?php
session_start();
require_once '../../dbcon.php';
require_once '../../Classes/OPD.php';

$opd = new Visit();

$patient_id = clean_string($_GET['patient_id']);
$visit_id = clean_string($_GET['visit_id']);


?>
<table class="table table-condensed">

  <tbody>
    <?php

    $sql = "SELECT * FROM patient_odq WHERE subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "'";
    $r = $mysqli->query($sql);

    if ($r->num_rows  == 0) {
      echo 'No ODQ';
    } else {
      // $hpc_count=1;
      while ($odq = $r->fetch_assoc()) {
    ?>

        <tr>
          <td><?php echo $odq['question']; ?> <?php echo $odq['response']; ?></td>
          <td class="text-right"><button type="button" class="btn btn-danger btn-sm remove_odq" id="<?php echo $odq['sn']; ?>">Remove</button></td>
        </tr>

    <?php
      }
    }

    ?>
    <tr>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table>