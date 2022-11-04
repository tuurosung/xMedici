<?php
  session_start();
  unset($_SESSION['active_user']);
  unset($_SESSION['access_level']);
  $_SESSION['noticeshown']=0;

 ?>

 <!DOCTYPE html>
 <html lang="en">

 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>MEDICI - Kindred ERP Pharmacy Manager | User Login</title>
   <!-- Font Awesome -->
   <link rel="stylesheet" href="fontawesome/css/all.css">
   <!-- Bootstrap core CSS -->
   <link href="mdb/css/bootstrap.min.css" rel="stylesheet">
   <!-- Material Design Bootstrap -->
   <link href="mdb/css/mdb.min.css" rel="stylesheet">
   <link href="mdb/css/style.min.css" rel="stylesheet">
   <link rel="preconnect" href="https://fonts.gstatic.com">
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

   <style>
     @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');
     .montserrat{
       font-family: 'Montserrat', sans-serif;
     }
     .poppins{
       font-family: 'Poppins', sans-serif;
     }
     .primary-text{
       color: #0d47a1 !important;
     }
   </style>

 </head>

 <body class="">




     <div class="" style="overflow-x:hidden; overflow-y:none">

       <div class="row" style="height:100vh;">
         <div class="col-md-8 animated fadeInLeft delay-3s d-none d-md-block" style="background-color:#006efb; padding:2em 3em;">
           <div class="d-flex justify-content-center align-items-center px-5" style="height:100vh">
             <div class="row">
               <div class="col-md-8">
                 <h1 class="montserrat font-weight-bold white-text" style="font-size:60px">Paperless Software For Smart Hospitals</h1>
                 <p class="white-text" style="font-size:16px">
                   Migrate your private hospital onto a 100% paperless platform and be a part of the digital transformation.
                   Electronic Folders, interactive consultation, digital dispensing and accurate billing and payments.
                 </p>
               </div>
               <div class="col-md-4">
                 <img src="images/stethoscope.png" alt="" class="img-fluid">
               </div>
             </div>
           </div>


         </div>
         <div class="col-md-4">

           <div class="pl-5 d-flex my-auto align-items-center" style="height:100vh">

             <div class="">
               <h4 class="montserrat font-weight-bold" style="font-size:2em !important">xMEDICI</h4>
               <p class="montserrat">Smart Hospital Manager</p>

               <div class="spacer my-5"></div>

               <form class="" id="login_form" autocomplete="off" style="width:400px">
                 <p class="primary-text montserrat mb-0" style="font-weight:500">Sign into your account</p>
                 <div class="mb-3" style="font-size:12px">
                   If you dont already have login credentials, contact your system administrator to sign you up
                 </div>
                 <div class="form-group poppins">
                   <label for="formGroupExampleInput" class="poppins">Username</label>
                   <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" autocomplete="off">
                 </div>

                 <fieldset class="form-group poppins">
                   <label for="formGroupExampleInput2">Password</label>
                   <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                 </fieldset>



                 <button type="submit" class="btn primary-color montserrat  px-5 mt-3">
                     <i class="fas fa-lock mr-3"></i>
                     Log In</button>
               </form>

               <p class="mt-5">Forgotten your password, click <a href="resetpassword.php">here</a></p>


             </div>



           </div>



         </div>
       </div>

     </div>




 </body>
 <script type="text/javascript" src="mdb/js/jquery-3.3.1.min.js"></script>
 <script type="text/javascript" src="mdb/js/bootstrap.js"></script>
 <script type="text/javascript" src="mdb/js/mdb.js"></script>
 <script type="text/javascript" src="mdb/js/bootbox.all.min.js"></script>
 <!-- <script type="text/javascript" src="js/jquery.cookie.js"></script> -->

 <script type="text/javascript">
 // $.removeCookie('dialogShownnn', { path: '/' }); // => true

 $('#login_form').on('submit', function(event) {
   event.preventDefault();

   $.ajax({
     url: 'serverscripts/login_verify.php',
     type: 'GET',
     data:$(this).serialize(),
     success: function(msg){
       if(msg==='login_failed'){
         bootbox.alert("Incorrect username or password",function(){
           $('#username').focus()
         })
       }else if (msg==='account_suspended') {
          bootbox.alert("Account suspended, contact administrator.")
       }else if (msg==='login_successful') {
         window.location='engine/engine.php';
       }
       else{
        bootbox.alert(msg)
       }
     }
   })
 })


   new WOW().init();
 </script>
 </html>
