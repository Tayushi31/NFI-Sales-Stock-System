<?php
session_start();
include "db_conn.php";

if (isset($_POST["user_name"]) && isset($_POST["password"])) {

   function validate($data)
   {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
   }

   $uname = mysqli_real_escape_string($conn, validate($_POST['user_name']));
   $pass = mysqli_real_escape_string($conn, validate($_POST['password']));

   $pass = hash("sha256", $pass);

   $sql = "SELECT * FROM user WHERE user_name = '$uname' AND password = '$pass'";

   $result = mysqli_query($conn, $sql);

   if (mysqli_num_rows($result) === 1) {
      $row = mysqli_fetch_assoc($result);

      if ($row['user_name'] === $uname && $row['password'] === $pass && $row['role'] === "sales") {
         $_SESSION['user_name'] = $row['user_name'];
         $_SESSION['role'] = $row['role'];

         header("Location: stock_list.php");
         exit();
      }
   } else {
      echo "<script>alert('The username or password entered is wrong.')</script>";
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <!-- basic -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- mobile metas -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="viewport" content="initial-scale=1, maximum-scale=1">
   <!-- site metas -->
   <title>Login - NFI Sales Stock System</title>
   <link rel="icon" href="images/logo/NFI-02.png">
   </link>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">
   <!-- site icon -->
   <link rel="icon" href="images/fevicon.png" type="image/png" />
   <!-- bootstrap css -->
   <link rel="stylesheet" href="css/bootstrap.min.css" />
   <!-- site css -->
   <link rel="stylesheet" href="style.css" />
   <!-- responsive css -->
   <link rel="stylesheet" href="css/responsive.css" />
   <!-- color css -->
   <link rel="stylesheet" href="css/colors.css" />
   <!-- select bootstrap -->
   <link rel="stylesheet" href="css/bootstrap-select.css" />
   <!-- scrollbar css -->
   <link rel="stylesheet" href="css/perfect-scrollbar.css" />
   <!-- custom css -->
   <link rel="stylesheet" href="css/custom.css" />
   <!-- calendar file css -->
   <link rel="stylesheet" href="js/semantic.min.css" />
   <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
</head>

<body class="inner_page login">
   <div class="full_container">
      <div class="container">
         <div class="center verticle_center full_height">
            <div class="login_section">
               <div class="logo_login">
                  <div class="center">
                     <img width="150" src="images/logo/NFISSS.png" alt="#" />
                  </div>
               </div>
               <div class="login_form">
                  <form method="post">
                     <!--
                        <script>
                           function check(){
                              if(document.getElementById("error"))
                                 return true;
                              else 
                                 document.getElementById("error").innerHTML = "Wrong keyword entry."
                                 return false;
                           }
                        </script>
                        -->
                     <fieldset>
                        <div class="field">
                           <label class="label_field">User Name</label>
                           <input type="text" name="user_name" id="user_name" placeholder="User Name" required />
                        </div>
                        <div class="field">
                           <label class="label_field">Password</label>
                           <input type="password" name="password" id="password" placeholder="Password" required />
                        </div>
                        <!-- <div class="field">
                           <label class="label_field hidden">hidden label</label>
                           <label class="form-check-label"><input type="checkbox" class="form-check-input" name="chkbx"> Remember Me</label>
                           <a class="forgot" href="">Forgotten Password?</a>
                        </div> -->
                        <div class="field margin_0">
                           <label class="label_field hidden">hidden label</label>
                           <button class="main_bt">Log In</button>
                        </div>
                     </fieldset>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- jQuery -->
   <script src="js/jquery.min.js"></script>
   <script src="js/popper.min.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <!-- wow animation -->
   <script src="js/animate.js"></script>
   <!-- select country -->
   <script src="js/bootstrap-select.js"></script>
   <!-- nice scrollbar -->
   <script src="js/perfect-scrollbar.min.js"></script>
   <script>
      var ps = new PerfectScrollbar('#sidebar');
   </script>
   <!-- custom js -->
   <script src="js/custom.js"></script>
   <script>
      $(function() {
         if (localStorage.chkbx && localStorage.chkbx != '') {
            $('#chkbx').attr('checked', 'checked');
            $('#user_name').val(localStorage.user_name);
            $('#password').val(localStorage.password);
         } else {
            $('#chkbx').removeAttr('checkedd');
            $('#user_name').val('');
            $('#password').val('');
         }

         $('#chkbx').click(function() {
            if ($('#chkbx').is(':checked')) {
               localStorage.user_name = $('#user_name').val();
               localStorage.password = $('#password').val();
               localStorage.chkbx = $('#chkbx').val();
            } else {
               localStorage.user_name = '';
               localStorage.password = '';
               localStorage.chkbx = '';
            }
         });
      });
   </script>
</body>

</html>