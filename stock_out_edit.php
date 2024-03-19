<?php
include "header.php";

$id = $_GET['id'];

if (isset($_POST['back'])) {
   header("Location: new_stock_out.php");
   exit();
}

if (isset($_POST['submit'])) {
   // Function to validate and sanitize input data
   function validate($data)
   {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
   }

   // Validate and sanitize form input data
   $name = validate($_POST['name']);
   $date = validate($_POST['date']);
   $pid = validate($_POST['products_name']);
   $quantity = validate($_POST['quantity']);
   $remarks = validate($_POST['remarks']);

   // Fetch the previous quantity from stock_out table
   $sql1 = "SELECT quantity FROM stock_out WHERE id = ?";
   $stmt1 = mysqli_prepare($conn, $sql1);
   mysqli_stmt_bind_param($stmt1, "i", $id);
   mysqli_stmt_execute($stmt1);
   $result1 = mysqli_stmt_get_result($stmt1);

   if ($row1 = mysqli_fetch_assoc($result1)) {
      $quantityprev = $row1['quantity'];

      // Fetch the current stock details for the specified product ID
      $sql2 = "SELECT particulars, stock_in, stock_out FROM stock WHERE id = ?";
      $stmt2 = mysqli_prepare($conn, $sql2);
      mysqli_stmt_bind_param($stmt2, "i", $pid);
      mysqli_stmt_execute($stmt2);
      $result2 = mysqli_stmt_get_result($stmt2);

      // Check if the query executed successfully
      if ($row2 = mysqli_fetch_assoc($result2)) {
         $products_name = $row2['particulars'];
         $stock_in = $row2['stock_in'];
         $stock_out = $row2['stock_out'];

         // Calculate the updated quantities and balance
         $total_quantity = $stock_out + ($quantity - $quantityprev);
         $balance = $stock_in - $total_quantity;

         // Update the stock table with the new quantities
         $sql3 = "UPDATE stock SET stock_out = ?, balance = ? WHERE id = ?";
         $stmt3 = mysqli_prepare($conn, $sql3);
         mysqli_stmt_bind_param($stmt3, "iii", $total_quantity, $balance, $pid);
         

         // Check if the update query executed successfully
         if ($result3 = mysqli_stmt_execute($stmt3)) {
            // Insert into the stock_out table with prepared statement
            $sql4 = "UPDATE stock_out SET name = ?, date = ?, products_name = ?, quantity = ?, remarks = ?, stock_id = ? WHERE id = ?";
            $stmt4 = mysqli_prepare($conn, $sql4);
            mysqli_stmt_bind_param($stmt4, "sssisii", $name, $date, $products_name, $quantity, $remarks, $pid, $id);

            // Check if the insert query executed successfully
            if ($result4 = mysqli_stmt_execute($stmt4)) {
               echo "<script>alert('Record edited successfully.')</script>";
            } else {
               // Log the error and provide a user-friendly message
               echo "<script>alert('Error updating record: " . mysqli_error($conn) . "')</script>";
            }
         } else {
            // Log the error and provide a user-friendly message
            echo "<script>alert('Error updating stock table: " . mysqli_error($conn) . "')</script>";
         }
      } else {
         // Log the error and provide a user-friendly message
         echo "<script>alert('Error fetching stock details: " . mysqli_error($conn) . "')</script>";
      }
   } else {
      // Log the error and provide a user-friendly message
      echo "<script>alert('Error fetching stock out details: " . mysqli_error($conn) . "')</script>";
   }

   // Close prepared statements
   mysqli_stmt_close($stmt1);
   mysqli_stmt_close($stmt2);
   mysqli_stmt_close($stmt3);
   mysqli_stmt_close($stmt4);

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
                              <h2 style="float: left;">New Stock Out</h2>
                              <button class="btn cur-p btn-secondary" style="float: right; margin: -7px;" name="back">
                                 < Back</button>
                           </form>
                        </div>
                        <div class="white_shd full margin_bottom_30">
                           <div class="full graph_head">
                              <div class="heading1 margin_0">
                                 <h2>Edit Stock Out List</h2>
                              </div>
                              <div class="table_section padding_infor_info">
                                 <div class="table-responsive-sm">
                                    <table width=100%>
                                       <tbody>
                                          <div class="form-group">
                                             <form method="post">
                                                <?php
                                                $sql = "SELECT * FROM stock_out WHERE id='$id'";
                                                $result = mysqli_query($conn, $sql);
                                                $row = mysqli_fetch_assoc($result);
                                                $name = $row['name'];
                                                $products_name = $row['products_name'];
                                                $date = $row['date'];
                                                $quantity = $row['quantity'];
                                                $remarks = $row['remarks'];
                                                $stock_id = $row['stock_id'];
                                                ?>
                                                <tr style="height:50px;">
                                                   <td>Name</td>
                                                   <td><input type="text" name="name" class="form-field" value="<?php echo $name; ?>" required></td>
                                                </tr>
                                                <tr style="height:50px; width:100%;">
                                                   <td>Date</td>
                                                   <td><input type="date" name="date" class="form-field" value="<?php echo $date; ?>" required></td>
                                                </tr>
                                                <tr>
                                                   <td colspan=2>
                                                      <h4 style="text-align:center; margin:10px; margin-top:20px;">Products Chosen</h4>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   <td colspan=2>
                                                      <div id="dynamic_field">
                                                         <table>
                                                            <tr style="height:50px;">
                                                               <td style="padding:10px">
                                                                  <select class="form-field" name="products_name" required>
                                                                     <!-- <option value="<?php echo $stock_id; ?>" disabled hidden selected><?php echo $products_name; ?></option> -->
                                                                     <!-- <option disabled hidden selected>&lt;Please select a value&gt;</option> -->
                                                                     <?php
                                                                     $sql = "SELECT * FROM stock";
                                                                     $result = mysqli_query($conn, $sql);

                                                                     if ($result) {
                                                                        while ($row = mysqli_fetch_array($result)) {
                                                                           $oid = $row['id'];
                                                                           $particulars = $row['particulars'];

                                                                           echo "
                                                                                 <option value='$oid'>$particulars</option>
                                                                              ";
                                                                        }
                                                                     }
                                                                     ?>
                                                                  </select>
                                                               </td>
                                                               <td style="padding:10px; width:30%"><input type="number" name="quantity" class="form-field" value="<?php echo $quantity; ?>" required></td>
                                                            </tr>
                                                         </table>
                                                      </div>

                                                   </td>
                                                </tr>
                                                <tr style="height:50px;">
                                                   <td>Remarks</td>
                                                   <td><input type="text" name="remarks" class="form-field" value="<?php echo $remarks; ?>" required></td>
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