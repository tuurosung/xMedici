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

    $query = mysqli_query($db, "SELECT * FROM patient_complains WHERE subscriber_id='" . $active_subscriber . "'AND patient_id='" . $patient_id . "' AND visit_id='" . $visit_id . "' AND status='active'") or die(mysqli_error($db));

    if (mysqli_num_rows($query) == 0) {
      echo 'No Complains';
    } else {
      while ($complains = mysqli_fetch_array($query)) {
    ?>

        <tr>
          <td><?php echo $complains['complain']; ?></td>
          <td><?php echo $complains['complain_duration']; ?></td>
          <td class="text-right">
            <a href="#" class="btn btn-danger btn-sm remove_complain" id="<?php echo $complains['sn']; ?>">
              <i class="fas fa-trash-alt mr-2" aria-hidden></i> Remove
            </a>
          </td>
        </tr>

    <?php
      }
    }

    ?>
    <tr>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table>