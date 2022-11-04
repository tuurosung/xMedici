<?php
  session_start();

  if(!isset($_SESSION['active_user']) || $_SESSION['user_level'] !== 'pharmacist'){
    header('Location: ../index.php');
  }
  else {
    # code...
  }

?>



<div class="sidebar" data-color="red" data-image="../images/cart.jpg">

<!--

    Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
    Tip 2: you can also add an image using data-image tag

-->

  <div class="sidebar-wrapper">
        <div class="logo" style="background-color:#fff; height:69px">
            <div class="" style="font-size:40px; font-family:baloo">
            <a href="index.php" style="text-decoration:none;font-family:baloo">OpenArms</a>
            </div>

        </div>

        <ul class="nav">
            <li class="" id="sales_panel_li">
                <a href="sales_panel.php">
                    <i class="fa fa-shopping-cart"></i>
                    <p> Sales Panel</p>
                </a>
            </li>
              <li class="" id="sales_history_li">
                <a href="sales_history.php">
                    <i class="fa fa-line-chart"></i>
                    <p> Sales History</p>
                </a>
            </li>
            <li id="stocksummary_li">
                <a href="stock_summary.php">
                    <i class="fa fa-bar-chart-o"></i>
                    <p>Stock Summary</p>
                </a>
            </li>
            <li id="expenditure_li">
                <a href="expenditure.php">
                    <i class="fa fa-exchange"></i>
                    <p>Expenditure</p>
                </a>
            </li>
            <li id="banking_li">
                <a href="banking.php">
                    <i class="fa fa-bank"></i>
                    <p>Banking</p>
                </a>
            </li>


            <li class="active-pro">
                <a href="upgrade.html">
                    <i class="pe-7s-rocket"></i>
                    <p>Enter Medical Stores</p>
                </a>
            </li>
        </ul>
  </div>
</div>
