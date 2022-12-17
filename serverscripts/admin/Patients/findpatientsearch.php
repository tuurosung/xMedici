<ul class="list-group">
<?php
      session_start();
      require_once '../../dbcon.php';
      require_once '../../Classes/Patient.php';
      
      $term=clean_string($_GET['search_term']);

      $patient=new Patient();
      $list=$patient->Find($term);      
      $i=1;

      ?>

      <table class="table table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th>Folder #</th>
						<th>Patient Name</th>
						<th>Age</th>
						<th>Sex</th>
						<th>Phone Number</th>
						<th>Last Visit</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$patients = $patient->Find($term);

					$i = 1;
					foreach ($patients as $rows) {
						$patient->patient_id = $rows['patient_id'];
						$patient->PatientInfo();
						$othernames = $rows['othernames'];
					?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td>
								<a href="patient_folder.php?patient_id=<?php echo $patient->patient_id; ?>" class="font-weight-bold">
									<?php echo $patient->patient_id; ?></a>
							</td>
							<td><?php echo $patient->patient_fullname; ?></td>
							<td><?php echo $patient->age; ?></td>
							<td><?php echo ucfirst($patient->sex); ?></td>
							<td><?php echo $patient->phone_number; ?></td>
							<td><?php echo $patient->last_visit; ?></td>
							<td class="text-right">
								<a class="" href="patient_folder.php?patient_id=<?php echo $patient->patient_id; ?>">
									Open Folder
									<i class="fas fa-arrow-right ml-2" aria-hidden></i>
								</a>
							</td>
						</tr>


					<?php
					}
					?>
		</div>

		</tbody>
		</table>
     