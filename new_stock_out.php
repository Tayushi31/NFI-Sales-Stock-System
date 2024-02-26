<?php
include "header.php";

if (isset($_POST['export'])) {
   header("Location: stock_out_export.php");
   exit();
}

if (isset($_POST['edit'])) {
   $id = $_POST['edit'];
   header("Location: stock_out_edit.php?id=$id");
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
   $pid = validate($_POST['products_name']);
   $quantity = validate($_POST['quantity']);
   $remarks = validate($_POST['remarks']);

   $sql1 = "SELECT * FROM stock WHERE id=$pid";
   $result = mysqli_query($conn, $sql1);
   $row = mysqli_fetch_array($result);
   $stock_out = $row['stock_out'];
   $products_name = $row['particulars'];
   $total_quantity = ($stock_out + $quantity);

   $sql2 = "UPDATE stock SET stock_out=$total_quantity WHERE id=$pid";
   $result = mysqli_query($conn, $sql2);

   $sql3 = "INSERT INTO stock_out(name,date,products_name,quantity,remarks,stock_id) VALUE('$name', '$date', '$products_name', '$quantity', '$remarks', '$pid')";
   //$result = mysqli_query($conn, $sql);

   // Insert the data with error handling
   if ($conn->query($sql3) === TRUE) {
      echo "<script>alert('Record added successfully.')</script>";
   } else {
      echo "<script>alert(''Error: ' . $sql3 . '<br>' . $conn -> error')</script>";
   }
}

if (isset($_POST['delete'])) {
   $id = $_POST['delete'];

   $sql2 = "SELECT * FROM stock_out WHERE id=$id";
   $result = mysqli_query($conn, $sql2);
   $row = mysqli_fetch_array($result);
   $quantity = $row['quantity'];
   $pid = $row['stock_id'];

   $sql = "SELECT * FROM stock WHERE id=$pid";
   $result = mysqli_query($conn, $sql);
   $row = mysqli_fetch_array($result);
   $stock_out = $row['stock_out'];

   $total_quantity = ($stock_out - $quantity);

   $sql3 = "UPDATE stock SET stock_out=$total_quantity WHERE id=$pid";
   $result = mysqli_query($conn, $sql3);

   $sql4 = "DELETE FROM stock_out WHERE id='$id'";

   if ($conn->query($sql4)) {
      echo "<script>alert('Delete Success!')</script>";
   } else {
      echo "<script>alert('Delete failed. $conn -> $error')</script>";
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
                              <li><span class="name_user">
                                       <?php echo "$user_name"; ?>
                                    </span>
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
                           </form>
                        </div>
                        <div class="white_shd full margin_bottom_30">
                           <div class="full graph_head">
                              <!-- <div class="heading1 margin_0">
                                 <h2>Add New Stock Out</h2>
                              </div> -->
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
                                                <tr>
                                                   <td colspan=2>
                                                      <h4 style="text-align:center; margin:10px; margin-top:20px;">
                                                         Products Chosen</h4>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   <td colspan=2>
                                                      <div id="dynamic_field">
                                                         <table>
                                                            <tr style="height:50px;">
                                                               <td style="padding:10px">
                                                                  <select class="form-field" name="products_name" required>
                                                                     <option selected>&lt;Please select a value&gt;
                                                                     </option>
                                                                     <?php
                                                                     $sql = "SELECT * FROM stock";
                                                                     $result = mysqli_query($conn, $sql);

                                                                     if ($result) {
                                                                        while ($row = mysqli_fetch_array($result)) {
                                                                           $id = $row['id'];
                                                                           $particulars = $row['particulars'];

                                                                           echo "
                                                                                 <option value='$id'>$particulars</option>
                                                                              ";
                                                                        }
                                                                     }
                                                                     ?>
                                                                  </select>
                                                               </td>
                                                               <td style="padding:10px; width:30%"><input type="number" name="quantity" class="form-field" required></td>
                                                               <!-- <td style="padding:10px">
                                                                     <center><button type="button" class="btn btn-success" name="add" id="add"><i class="fa fa-plus"></i></button></center>
                                                                  </td> -->
                                                            </tr>
                                                         </table>
                                                      </div>

                                                   </td>
                                                </tr>
                                                <tr style="height:50px;">
                                                   <td>Remarks</td>
                                                   <td><input type="text" name="remarks" class="form-field" required>
                                                   </td>
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
                        <div class="white_shd full margin_bottom_30">
                           <div class="table_section padding_infor_info">
                              <div class="table-responsive-sm">
                                 <div class="heading1 margin_0">
                                    <form method="post">
                                       <h2 style="float:left;">Stock Out List</h2>
                                       <button class="btn cur-p btn-info" style="float: right;" name="export">Export</button>
                                    </form>
                                 </div>
                                 <table class="table" id="datatable">
                                    <thead class="thead-dark">
                                       <tr>
                                          <th>Name</th>
                                          <th>Date</th>
                                          <th>Products Name</th>
                                          <th>Quantity</th>
                                          <th>Remarks</th>
                                          <th>Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <form method="post">
                                          <?php
                                          $sql = "SELECT * FROM stock_out ORDER BY date";
                                          $result = mysqli_query($conn, $sql);

                                          if ($result) {
                                             while ($row = mysqli_fetch_array($result)) {
                                                $id = $row['id'];
                                                $name = $row['name'];
                                                $date = $row['date'];
                                                $products_name = $row['products_name'];
                                                $quantity = $row['quantity'];
                                                $remarks = $row['remarks'];

                                                echo "
                                                      <tr>
                                                         <td>$name</td>
                                                         <td style='width:10%;'>$date</td>
                                                         <td>$products_name</td>
                                                         <td>$quantity</td>
                                                         <td>$remarks</td>
                                                         <td width=15%>
                                                            <button class='btn cur-p btn-primary' name='edit' value='$id'>Edit</button>
                                                            <button class='btn cur-p btn-danger' name='delete' value='$id' onclick='return confirm(`Delete this stock?`)'>Delete</button>
                                                         </td>
                                                      </tr>
                                                   ";
                                             }
                                          }
                                          ?>
                                       </form>
                                    </tbody>
                                 </table>
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
                        GitHub: <a href="https://themewagon.com/">NFI Sales Stock System</a>
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

   <!-- add more button -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
   <script>
      $(document).ready(function() {
         var i = 0;
         $('#add').click(function() {
            $('#dynamic_field').append('<tr id="row' + i + '"> <td style="padding:10px;"><select class="form-field" name="products_name[' + i + ']" id="products_name" required"><option selected>&lt;Please select a value&gt;</option> </select></td> <td style="padding:10px; width:30%"> <input type="number" class="form-field" name="quantity[]"> </td> <td style="padding:10px;"><button type="button" name="add" class="btn btn-danger btn_remove" id="' + i + '"><i class="fa fa-trash"></i></button></td> </tr> ');
            $.ajax({
               url: "get_productoption.php",
               type: "GET",
               success: function(data) {
                  var obj = jQuery.parseJSON(data);
                  console.log(obj);
                  select = document.getElementById('products_name[' + i + ']');
                  for (j = 0; j < obj.length; j++) {
                     var opt = document.createElement("option");
                     opt.value = obj[j][0];
                     opt.innerHTML = obj[j][1];
                     select.appendChild(opt);
                  };
               }
            });
            i++;
         });
         $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");

            $('#row' + button_id + '').remove();
         });

         $('#add2').click(function() {
            i++;
            $('#dynamic_field2').append('<div class="form-row"  id="row2' + i + '"> <div class="col"> <input type="text" class="form-control" name="mange[]"> </div> <div class="col"> <input type="text" class="form-control"  name="bezeichnung[]"> </div> <div class="col"> <input type="text" class="form-control" name="art_nr[]"> </div> <div class="col"> <td><button type="button" name="add" class="btn btn-danger btn_remove2" id="' + i + '"><i class="fa fa fa-trash"></i></button></td> </div> </div>');
         });
         $(document).on('click', '.btn_remove2', function() {
            var button_id = $(this).attr("id");

            $('#row2' + button_id + '').remove();
         });


         $('#add3').click(function() {
            i++;
            $('#dynamic_field3').append('<div class="form-row" id="row3' + i + '"> <div class="col"> <input type="text" class="form-control"  name="offene_pukte[]"> </div> <div class="col"> <input type="text" class="form-control" name="intern[]"> </div> <div class="col"> <td><button type="button" name="add"  class="btn btn-danger btn_remove3" id="' + i + '"><i class="fa fa fa-trash"></i></button></td> </div> </div>');
         });
         $(document).on('click', '.btn_remove3', function() {
            var button_id = $(this).attr("id");

            $('#row3' + button_id + '').remove();
         });



      });
   </script>

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

<?php //$sql = "SELECT * FROM stock";$result = mysqli_query($conn, $sql);if ($result) {while ($row = mysqli_fetch_array($result)) {$id = $row['id'];$particulars = $row['particulars'];echo "<option value='$id'>$particulars</option>";}} 
?>