<?php
session_start();
if(isset($_SESSION['role']) == "sales") {
    header("Location: stock_list.php");
}
else {
    header("Location: login.php");
}

?>