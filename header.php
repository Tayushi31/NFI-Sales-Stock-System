<?php
session_start();
include "db_conn.php";

$user_name = $_SESSION['user_name'];

if ($user_name == null) {
   header("Location: index.php");
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
   // last request was more than 30 minutes ago
   session_unset();     // unset $_SESSION variable for the run-time 
   session_destroy();   // destroy session data in storage
   header('Location: login.php');
   exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
