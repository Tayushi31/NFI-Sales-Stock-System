<?php
include "header.php";

if (isset($_POST['export'])) {
   header("Location: stock_list_export.php");
   exit();
}

if (isset($_POST['stock_in'])) {
   $pid = $_POST['stock_in'];
   header("Location: stock_in_view.php?id=$pid");
   exit();
}

if (isset($_POST['edit'])) {
   $_SESSION['id'] = $_POST['edit'];
   header("Location: stock_list_edit.php");
   exit();
}

if (isset($_POST['delete'])) {
   $id = $_POST['delete'];
   $sql = "DELETE FROM stock WHERE id='$id' ";

   if ($conn->query($sql)) {
      echo "<script>alert('Delete Success!')</script>";
   } else {
      echo "<script>alert('Delete failed. " . $conn->error . "')</script>";
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
                  <li class="logout">
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
                           <h2 style="float: left;">Stock List</h2>
                        </div>
                        <div class="white_shd full margin_bottom_30">
                           <div class="table_section padding_infor_info">
                              <div class="table-responsive-sm">
                                 <div class="heading1 margin_0">
                                    <form method="post">
                                       <h2 style="float:left;">Stock List</h2>
                                       <button class="btn cur-p btn-info" style="float: right;" name="export">Export</button>
                                    </form>
                                 </div>
                                 <table class="table" id="datatable">
                                    <thead class="thead-dark">
                                       <tr>
                                          <th>Date</th>
                                          <th>Particulars</th>
                                          <th>In</th>
                                          <th>Out</th>
                                          <th>Balance</th>
                                          <th>Remarks</th>
                                          <th>Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                          <?php
                                          $sql = "SELECT * FROM stock ORDER BY particulars";
                                          $result = mysqli_query($conn, $sql);

                                          if ($result) {
                                             while ($row = mysqli_fetch_array($result)) {
                                                $id = $row['id'];
                                                $date = $row['date'];
                                                $particulars = $row['particulars'];
                                                $stock_in = $row['stock_in'];
                                                $stock_out = $row['stock_out'];
                                                $balance = $row['balance'];
                                                $remarks = $row['remarks'];

                                                echo "
                                                      <tr>
                                                         <td style='width:10%;'>$date</td>
                                                         <td>$particulars</td>
                                                         <td><a href='stock_in_view.php?pid=$id' data-toggle='tooltip' data-placement='bottom' title='View Stock In List'><i class='fa fa-search blue2_color' style='margin-right:10px;'></i></a>$stock_in</td>
                                                         <td>$stock_out</td>
                                                         <td>$balance</td>
                                                         <td>$remarks</td>
                                                         <form method='post'>
                                                         <td>
                                                            <button class='btn cur-p btn-primary' name='edit' value='$id'>Edit</button>
                                                            <button class='btn cur-p btn-danger' name='delete' value='$id' onclick='return confirm(`Delete this stock?`)'>Delete</button>
                                                         </td>
                                                         </form>
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
                     </div>
                  </div>
               </div>
               <!-- FOOTER -->
               <div class="container-fluid">
                  <div class="footer">
                     <p>Copyright © 2024 Made by Tayushi<br><br>
                        GitHub: <a href="https://github.com/Tayushi31/NFI-Sales-Stock-System">NFI Sales Stock System</a>
                     </p>
                  </div>
               </div>
               <!-- END FOOTER -->
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