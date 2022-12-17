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

    $sql = "SELECT * FROM patient_diagnosis";
    $sql .= " WHERE  subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND status='active'";

    $r = $mysqli->query($sql);
    if ($r->num_rows == 0) {
      echo 'No Diagnosis';
    } else {
      while ($diagnosis = $r->fetch_assoc()) {
    ?>
        <tr>

          <td><?php echo $diagnosis['diagnosis_id']; ?></td>
          <td><?php echo $opd->Diagnosis($diagnosis['diagnosis_id']); ?></td>
          <td class="text-right"><a href="" class="text-danger remove_diagnosis" id="<?php echo $diagnosis['sn']; ?>">Click to Remove</a></td>

        </tr>
    <?php

      }
    }

    ?>
  </tbody>
</table>