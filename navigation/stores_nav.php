<?php
  session_start();

  if(!isset($_SESSION['active_user']) || $_SESSION['user_level'] !== 'administrator'){
    header('Location: ../users/login_box.php');
  }
  else {
    # code...
  }

?>

<div class="sidebar" data-color="green">

<!--

    Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
    Tip 2: you can also add an image using data-image tag

-->

  <div class="sidebar-wrapper">
        <div class="logo" style="background-color:#fff; height:69px">
            <div class="" style="font-size:40px; font-family:baloo">
            <a href="index.php" style="text-decoration:none;font-family:baloo">PharmCare</a>
            </div>

        </div>

        <ul class="nav">

            <li>
                <a href="purchases.php">
                    <i class="fa fa-arrow-right"></i>
                    <p>Purchases</p>
                </a>
            </li>
            <li>
                <a href="supplies.php">
                    <i class="fa fa-arrow-left"></i>
                    <p>Drug Issues</p>
                </a>
            </li>
            <li>
                <a href="bin_cards.php">
                    <i class="fa fa-file-o"></i>
                    <p>Bin Cards</p>
                </a>
            </li>


            <li class="active-pro">
                <a href="../admin/index.php">
                    <i class="fa fa-chevron-left"></i>
                    <p>Back to admin </p>
                </a>
            </li>
        </ul>
  </div>
</div>
