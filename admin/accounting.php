<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<?php require_once '../navigation/admin_header.php' ?>

</head>
<body>

<div class="wrapper">

    <?php require_once '../navigation/admin_nav.php'; ?>
    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                </div>

                <div class="collapse navbar-collapse">


                    <ul class="nav navbar-nav navbar-right">


                        <li class="logout">
                            <a href="#">
															<i class="fa fa-lock"></i>
                                Log out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Financial Accounts</h4>
                                <!-- <p class="category">sales summary for today</p> -->
                            </div>
                            <hr>
                            <div class="content">

															<div class="text-right" style="margin-bottom:10px">
																<button type="button" id="new_account_btn" class="btn btn-primary text" style="right:5px">
																	<i class="fa fa-plus-circle"></i>
																</button>
															</div>




															<div id="accounts_list_holder" style="font-size:11px">

															</div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Display Area</h4>
                                <!-- <p class="category">24 Hours performance</p> -->
                            </div>
                            <hr>
                            <div class="content" id="account_history_holder">



                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </div>


        <footer class="footer">
            <div class="container-fluid">

                <p class="copyright pull-right">
                    &copy; 2016 <a href="#">Powered by Kindred GH. Technologies</a>
                </p>
            </div>
        </footer>

    </div>
</div>




<div id="new_account_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content" style="width:600px">
      <div class="modal-header" style="background-color:#0cb93c; font-family:rancho; font-size:30px">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="myModalLabel">Let's create a new account together</h2>
      </div>
      <div class="modal-body">
				<form id="new_account_frm">

        <div class="row" style="margin-bottom:30px">
          <div class="col-xs-6">
            <label for="">Account ID</label>
            <input type="text" name="account_id" id="account_id" class="form-control input-sm" readonly="readonly">
          </div>

        </div>
        <div class="row" style="margin-bottom:30px">
          <div class="col-xs-6">
            <label for="">Account Name</label>
            <input type="text" name="account_name" id="account_name" class="form-control input-sm" required>
          </div>
          <div class="col-xs-6">
            <label for="">Opening Balance</label>
            <input type="text" name="opening_balance" id="opening_balance" class="form-control input-sm" required>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-danger">
          <i class="fa fa-close"></i>
          Cancel
        </button>
        <button type="submit" class="btn btn-primary">
          Create Account
					<i class="fa fa-check"></i>
        </button>
      </div>
		</form>
    </div>
  </div>
</div>


<div id="success_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content" style="width:600px">
      <div class="modal-header" style="background-color:#0cb93c; font-family:rancho; font-size:30px">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="myModalLabel">Bingo!</h2>
      </div>
      <div class="modal-body">
				<div class="row">
				  <div class="col-md-4">
						<i class="fa fa-smile-o" style="font-size:150px; color:#1c79b2"></i>
				  </div>
				  <div class="col-md-8">
						<div class="" style="font-family:acme; font-size:20px;" >
              Success!!!
            </div>
            <div class="" style="font-family:rancho; font-size:30px">
              Your account has been created successfully, Now lets do some transactions with your account.
            </div>
				  </div>
				</div>

      </div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-primary">
					<i class="fa fa-thumbs-o-up"></i>
					Okay
        </button>
      </div>
		</form>
    </div>
  </div>
</div>

</body>

    <!--   Core JS Files   -->
    <?php require_once '../navigation/admin_footer.php'; ?>

	<script type="text/javascript">
    	$(document).ready(function(){
				list_accounts()

				$('#new_account_btn').on('click', function(event) {
					var con=confirm('Do you want to create a new account?')
					if(con===true){
						$('#new_account_modal').modal('show')
						account_idgen()

						$('#new_account_frm').on('submit', function(event) {
		           event.preventDefault();
		           $.ajax({
		             url: '../serverscripts/admin/create_account.php',
		             type: 'GET',
		             data: $(this).serialize(),
		             success: function(msg){
		                if(msg=='success'){
		                  $('#new_account_modal').modal('hide')
		                  $('#success_modal').modal('show')
		                }
		                else {
		                  alert(msg)
		                }
		             }
		           })
		         });
					}
				})


				function account_idgen(){
					var text = "";
					var possible = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
					for( var i=0; i < 15; i++ )
							text += possible.charAt(Math.floor(Math.random() * possible.length));

					$('#account_id').val(text)
				}

				function list_accounts(){
            $.ajax({
              url: '../serverscripts/admin/list_accounts.php',
              type: 'GET',
              success: function(msg){
                $('#accounts_list_holder').html(msg)

                $('#new_account_btn').on('click', function(event) {
                  event.preventDefault();
                  $('#new_account_modal').modal('show')
                  account_id_gen()
                });

                $('.accounts').on('click', function(event) {
                  event.preventDefault();
                  var id=$(this).attr('ID')
                  $.ajax({
                    url: '../../serverscripts/admin/account_history.php?id='+id,
                    type: 'GET',
                    success: function(msg){
                      $('#account_history_holder').html(msg)
                    }
                  })

                });

								$('.del').on('click', function(event) {
									var account_id=$(this).attr('ID');

									var con=confirm('Deleting this account will delete all its records.')

									if(con===true){
										$.ajax({
											url: '../serverscripts/admin/delete_account.php?account_id='+account_id,
											type: 'GET',
											success: function(msg){
												if(msg==='delete_successful'){
													list_accounts();
													alert('Account deleted successfully')
												}
												else {
													alert(msg)
												}
											}
										})
									}
									else {

									}
								})
              }
            })

        }





    	});
	</script>

</html>
