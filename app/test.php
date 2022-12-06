<!DOCTYPE html>
<html lang="en" dir="ltr">
  <!-- <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/datepicker.css">
  </head> -->
  <head>
  	<meta charset="utf-8" />

  <title>PharmCare</title>

  <meta name="viewport" content="width=device-width" />



    <!-- Bootstrap core CSS     -->
  	<link rel="stylesheet" href="../assets/css/bootstrap.css">
  	<link rel="stylesheet" href="../assets/css/datepicker.css">
  	<link href="../assets/css/datepicker3.css" rel="stylesheet"/>
    <link href="../assets/css/bootstrap-table.css" rel="stylesheet" />


    <!-- Animation library for notifications   -->
    <link href="../assets/animate.css/animate.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <!-- <link href="../assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/> -->



    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <!-- <link href="../assets/css/demo.css" rel="stylesheet" /> -->


    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.css" media="screen" title="no title">
  </head>
  <body>
    <div class="form-group">
      <label for="">Date Picker Test</label>
      <input type="text" class="form-control" id="start_date" name="start_date" placeholder="">
    </div>
  </body>

  <script type="text/javascript" src="../js/jquery.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="../js/bootstrap-table.js"></script>


  <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
  <script src="../assets/js/light-bootstrap-dashboard.js"></script>



  <script type="text/javascript">
    $('.logout').on('click', function(event) {
      event.preventDefault();

      var con=confirm('Are you sure you want to end this session?')
      if(con===true){
        window.location='../index.php'
      }
      else {

      }
    });

    var height = $(window).height();
    var required_height = height - 250;
    thirtypc = parseInt(required_height) + 'px';
    $(".fit-height").css('height',thirtypc);
  </script>
  <script type="text/javascript">
    $('#start_date').datepicker()
  </script>



  <!-- <script type="text/javascript" src="../js/jquery.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="../js/bootstrap-table.js"></script>
  <script type="text/javascript">
    $('#start_date').datepicker()
  </script> -->
</html>
