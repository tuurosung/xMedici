<?php require_once '../navigation/header.php'; ?>
<?php require_once '../navigation/admin_nav.php'; ?>

<main class="py-3 mx-lg-5">
	<div class="container-fluid mt-2">
		<h5>Account Information</h5>
		<hr class="hr">

    <div class="row">
      <?php
          $get_accounts=mysqli_query($db,"SELECT * FROM accounts WHERE subscriber_id='".$active_subscriber."'") or die(mysqli_error($db));
          while ($accounts=mysqli_fetch_array($get_accounts)) {
            ?>
            <div class="col-md-3">
              <div class="card mb-3">
                <div class="card-body pt-3 pb-3">
                  <?php echo $accounts['account_name']; ?>
                  <p class="m-0 montserrat font-weight-bold" style="font-size:18px">GHS <?php echo number_format($accounts['balance'],2); ?></p>
                </div>
              </div>
            </div>
            <?php
          }
       ?>
    </div>

  </div>

</main>

</body>
<?php require_once '../navigation/admin_footer.php'; ?>
