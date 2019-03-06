<?php
include 'base_url.php';
echo $protocol=url();
?>
<!DOCTYPE html>
<html>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- Latest compiled and minified CSS -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
   <!-- jQuery library -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <!-- Latest compiled JavaScript -->
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
   <style>
      .stellar-login img{
      width: 40%;
      display:block;
      margin:auto;
      }
      .stellar-login li{
      display:inline-block;
      }
      .stellar-login li:nth-child(2) button{
      outline:none;
      color:#fff;
      padding: 6px 12px;
      font-size: 17px;
      font-weight:bold;
      cursor: pointer;
      background-color:#bb181b;
      }
      .stellar-login li:first-child button{
      outline:none;
      padding: 6px 12px;
      font-size: 17px;
      font-weight:bold;
      cursor: pointer;
      }
      .login {
      display: flex;
      justify-content: center;
      align-items: center;
      justify-content: center;
      height: 100vh;
      width: 100%;
      text-align: center;
      }
   </style>
   <body>
      <div class="container">
         <div class="row">
            <div class="login">
               <div class="stellar-login">
                   <img  id="logo" src="" width="130" src="" alt="">
                  <h3>Sorry, you can't sign up yet</h3>
                  <p>You're not old enough to use onstellar yet</p>
                  <ul>
                     <li><button>Get help</button></li>
                     <li><button class="log-in">Log in</button></li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </body>
</html>