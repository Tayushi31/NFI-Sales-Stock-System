<?php
include "header.php";

$pid = $_GET['pid'];

if (isset($_POST['back'])) {
   header("Location: stock_list.php");
   exit();
}

if (isset($_POST['export'])) {
   header("Location: stock_in_export.php?pid=$pid");
   exit();
}

if (isset($_POST['add_in'])) {
   header("Location: stock_in_add.php?pid=$pid");
   exit();
}

if (isset($_POST['edit'])) {
   $id = $_POST['edit'];
   header("Location: stock_in_edit.php?id=$id&pid=$pid");
   exit();
}

if (isset($_POST['delete'])) {
   $id = $_POST['delete'];

   // Fetch stock information
   $sql = "SELECT stock_in, stock_out FROM stock WHERE id = ?";
   $stmt = mysqli_prepare($conn, $sql);
   mysqli_stmt_bind_param($stmt, "i", $pid);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);

   if ($row = mysqli_fetch_assoc($result)) {
       $stock_in = $row['stock_in'];
       $stock_out = $row['stock_out'];

       // Fetch quantity from stock_in
       $sql2 = "SELECT quantity FROM stock_in WHERE id = ?";
       $stmt2 = mysqli_prepare($conn, $sql2);
       mysqli_stmt_bind_param($stmt2, "i", $id);
       mysqli_stmt_execute($stmt2);
       $result2 = mysqli_stmt_get_result($stmt2);

       if ($row2 = mysqli_fetch_assoc($result2)) {
           $quantity = $row2['quantity'];

           // Calculate new values
           $total_quantity = $stock_in - $quantity;
           $balance = $total_quantity - $stock_out;

           // Update stock table
           $sql3 = "UPDATE stock SET stock_in = ?, balance = ? WHERE id = ?";
           $stmt3 = mysqli_prepare($conn, $sql3);
           mysqli_stmt_bind_param($stmt3, "iii", $total_quantity, $balance, $pid);
           $result3 = mysqli_stmt_execute($stmt3);

           if ($result3) {
               // Delete from stock_in table
               $sql4 = "DELETE FROM stock_in WHERE id = ?";
               $stmt4 = mysqli_prepare($conn, $sql4);
               mysqli_stmt_bind_param($stmt4, "i", $id);
               $result4 = mysqli_stmt_execute($stmt4);

               if ($result4) {
                   echo "<script>alert('Delete Success!')</script>";
               } else {
                   echo "<script>alert('Delete failed: " . mysqli_error($conn) . "')</script>";
               }
           } else {
               echo "<script>alert('Update failed: " . mysqli_error($conn) . "')</script>";
           }
       } else {
           echo "<script>alert('Error fetching quantity: " . mysqli_error($conn) . "')</script>";
       }
   } else {
       echo "<script>alert('Error fetching stock information: " . mysqli_error($conn) . "')</script>";
   }

   // Close prepared statements
   mysqli_stmt_close($stmt);
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
                              <h2 style="float: left;">Stock In List</h2>
                              <button class="btn cur-p btn-secondary" style="float: right; margin: -7px;" name="back">
                                 < Back</button>
                           </form>
                        </div>
                        <form method="post">
                           <div class="white_shd full margin_bottom_30">
                              <div class="table_section padding_infor_info">
                                 <?php
                                 $sql = "SELECT * FROM stock WHERE id = '$pid'";
                                 $result = mysqli_query($conn, $sql);
                                 $row = mysqli_fetch_array($result);
                                 $name = $row['particulars'];
                                 $date = $row['date'];
                                 $stock_in = $row['stock_in'];
                                 ?>
                                 <div class="list_title">
                                    <h1>Name : <?php echo $name; ?></h1>
                                    <h1>Date : <?php echo $date; ?></h1>
                                    <h1>Stock In : <?php echo $stock_in; ?></h1>
                                 </div>
                                 <center><button class="btn cur-p btn-success" style="width:50%; height:40px; margin:20px;" name="add_in">Add New Stock In</button></center>
                                 <center><button class="btn cur-p btn-info" style="width:50%; height:40px; margin-bottom:20px;" name="export">Export</button></center>
                                 <div class="table-responsive-sm">
                                    <table class="table" id="datatable">
                                       <thead class="thead-dark">
                                          <tr>
                                             <th>Name</th>
                                             <th>Date</th>
                                             <th>Quantity</th>
                                             <th>Remarks</th>
                                             <th>Action</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <?php
                                          $sql = "SELECT * FROM stock_in WHERE stock_id='$pid' ORDER BY date ";
                                          $result = mysqli_query($conn, $sql);

                                          if ($result) {
                                             while ($row = mysqli_fetch_array($result)) {
                                                $id = $row['id'];
                                                $name = $row['name'];
                                                $date = $row['date'];
                                                $quantity = $row['quantity'];
                                                $remarks = $row['remarks'];

                                                echo "
                                                      <tr>
                                                         <td>$name</td>
                                                         <td>$date</td>
                                                         <td>$quantity</td>
                                                         <td>$remarks</td>
                                                         <td>
                                                            <button class='btn cur-p btn-primary' name='edit' value='$id'>Edit</button>
                                                            <button class='btn cur-p btn-danger' name='delete' value='$id' onclick='return confirm(`Delete this stock?`)'>Delete</button>
                                                         </td>
                                                      </tr>
                                                   ";
                                             }
                                          }
                                          ?>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </form>
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

   <!-- data table for file exports -->
   <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
   <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
   <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>


   <script>
      $(document).ready(function() {
         $('#datatable').DataTable({
            searching: true,
            info: true,
            paging: true,
            dom: 'Bfrtip',
            buttons: []
         });
      });
   </script>
</body>

</html>