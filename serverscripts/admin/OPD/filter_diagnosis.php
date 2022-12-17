<?php
require_once '../../dbcon.php';
require_once '../../Classes/Patient.php';

$search_term = clean_string($_GET['search_term']);
$patient_id = clean_string($_GET['patient_id']);
$visit_id = clean_string($_GET['visit_id']);

?>

<div class="mt-5">
  <table class="table table-hover">
    <tbody>

      <?php
      session_start();

      $sql = "SELECT * FROM sys_diagnosis";
      $sql .= " WHERE diagnosis_id LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'";
      $sql .= " LIMIT 20";
      $r = $mysqli->query($sql);
      while ($diagnosis = $r->fetch_assoc()) {
      ?>
        <tr>
          <td><?php echo $diagnosis['diagnosis_id']; ?></td>
          <td><?php echo $diagnosis['description']; ?></td>
          <td class="text-right">
            <button type="button" class="btn btn-primary btn-sm diagnose_btn" data-diagnosis_id="<?php echo $diagnosis['diagnosis_id']; ?>" data-patient_id="<?php echo $patient_id; ?>" data-visit_id="<?php echo $visit_id; ?>">Diagnose</button>
          </td>
        </tr>
      <?php
      }
      ?>
      <tr>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  </table>
</div>