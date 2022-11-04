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
