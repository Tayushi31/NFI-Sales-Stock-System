<?php
include "header.php";

$pid = $_GET['pid'];

if (isset($_POST['back'])) {
   header("Location: stock_in_view.php?pid=$pid");
   exit();
}

if (isset($_POST['submit'])) {
   function validate($data)
   {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
   }

   $name = validate($_POST['name']);
   $date = validate($_POST['date']);
   $quantity = validate($_POST['quantity']);
   $remarks = validate($_POST['remarks']);

   $sql1 = "SELECT * FROM stock WHERE id=$pid";
   $result = mysqli_query($conn, $sql1);
   $row = mysqli_fetch_array($result);
   $stock_in = $row['stock_in'];
   $stock_out = $row['stock_out'];
   $total_quantity = ($stock_in + $quantity);
   $balance = $total_quantity - $stock_out;

   $sql2 = "UPDATE stock SET stock_in=$total_quantity, balance=$balance WHERE id=$pid";
   $result = mysqli_query($conn, $sql2);

   $sql3 = "INSERT INTO stock_in(name,date,quantity,remarks,stock_id) VALUE('$name', '$date', '$quantity', '$remarks', '$pid')";
   //$result = mysqli_query($conn, $sql);

   // Insert the data with error handling
   if ($conn->query($sql3) === TRUE) {
      echo "<script>alert('Record added successfully.')</script>";
   } else {
      echo "<script>alert(''Error: ' . $sql3 . '<br>' . $conn -> error')</script>";
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
   <title>NFI Sales Stock System</title>
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
   <link rel="stylesheet" href="css/style.css" />
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
   <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
</head>

<body class="dashboard dashboard_1">
   <div class="full_container">
      <div class="inner_container">
         <!-- Sidebar  -->
         <nav id="sidebar">
            <div class="sidebar_blog_1">
               <div class="sidebar-header">
                  <div class="logo_section">
                     <a href="index.html"><img class="logo_icon img-responsive" src="images/logo/logo_icon.png" alt="#" /></a>
                  </div>
               </div>
            </div>
            <div class="sidebar_blog_2">
               <h4>General</h4>
               <ul class="list-unstyled components">
                  <li class="active">
                     <a href="stock_list.php">
                        <i class="fa fa-table purple_color"></i>
                        <span>Stocks Listing</span>
                     </a>
                  </li>
                  <li>
                     <a href="new_stock.php">
                        <i class="fa fa-plus green_color"></i>
                        <span>Create New Stock</span>
                     </a>
                  </li>
                  <li>
                     <a href="new_stock_out.php">
                        <i class="fa fa-share red_color"></i>
                        <span>New Stock Out</span>
                     </a>
                  </li>
                  <li>
                     <a href="logout.php" return>
                        <i class="fa fa-sign-out"></i>
                        <span>Logout</span>
                     </a>
                  </li>
               </ul>
            </div>
         </nav>
         <!-- end sidebar -->
         <!-- right content -->
         <div id="content">
            <!-- topbar -->
            <div class="topbar">
               <nav class="navbar navbar-expand-lg navbar-light">
                  <div class="full">
                     <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i class="fa fa-bars"></i></button>
                     <div class="logo_section">
                        <a href="stock_list.php">
                           <h1 style="color: white; font-size: 33px; padding-left: 30px; padding-top: 5px">NFI SSS</h1>
                        </a>
                     </div>
                     <div class="right_topbar">
                        <div class="icon_info">
                           <ul class="user_profile_dd">
                              <li>
                                 <span class="name_user"><?php echo "$user_name"; ?></span>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </nav>
            </div>
            <!-- end topbar -->
            <!-- dashboard inner -->
            <div class="midde_cont">
               <div class="container-fluid">
                  <div class="row column_title">
                     <div class="col-md-12">
                        <div class="page_title">
                           <form method="post">
                              <h2 style="float: left;">Stock In List</h2>
                              <button class="btn cur-p btn-secondary" style="float: right; margin: -7px;" name="back">
                                 < Back</button>
                           </form>
                        </div>
                        <div class="white_shd full margin_bottom_30">
                           <div class="full graph_head">
                              <div class="heading1 margin_0">
                                 <h2>Add New Stock In</h2>
                              </div>
                              <div class="table_section padding_infor_info">
                                 <div class="table-responsive-sm">
                                    <table width=100%>
                                       <tbody>
                                          <div class="form-group">
                                             <form method="post">
                                                <tr style="height:50px;">
                                                   <td>Name</td>
                                                   <td><input type="text" name="name" class="form-field" required></td>
                                                </tr>
                                                <tr style="height:50px; width:100%;">
                                                   <td>Date</td>
                                                   <td><input type="date" name="date" class="form-field" required></td>
                                                </tr>
                                                <tr style="height:50px;">
                                                   <td>Quantity</td>
                                                   <td><input type="number" name="quantity" class="form-field" required></td>
                                                </tr>
                                                <tr style="height:50px;">
                                                   <td>Remarks</td>
                                                   <td><input type="text" name="remarks" class="form-field" required></td>
                                                </tr>
                                                <tr style="height:50px;">
                                                   <td colspan="2">
                                                      <center><button class="btn cur-p btn-primary" name="submit">Submit</button></center>
                                                   </td>
                                                </tr>
                                             </form>
                                          </div>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- footer -->
               <div class="container-fluid">
                  <div class="footer">
                  <p>Copyright © 2024 Made by Tayushi<br><br>
                        GitHub: <a href="https://github.com/Tayushi31/NFI-Sales-Stock-System">NFI Sales Stock System</a>
                     </p>
                  </div>
               </div>
            </div>
            <!-- end dashboard inner -->
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
   <!-- owl carousel -->
   <script src="js/owl.carousel.js"></script>
   <!-- chart js -->
   <script src="js/Chart.min.js"></script>
   <script src="js/Chart.bundle.min.js"></script>
   <script src="js/utils.js"></script>
   <script src="js/analyser.js"></script>
   <!-- nice scrollbar -->
   <script src="js/perfect-scrollbar.min.js"></script>
   <script>
      var ps = new PerfectScrollbar('#sidebar');
   </script>
   <!-- custom js -->
   <script src="js/chart_custom_style1.js"></script>
   <script src="js/custom.js"></script>
</body>

</html>