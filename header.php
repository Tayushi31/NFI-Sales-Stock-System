<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable error display on the live site
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error/log/file.log');

session_start();
include "db_conn.php";

// Include guard to prevent direct access
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    exit("Access denied");
}

$user_name = $_SESSION['user_name'];

if ($user_name == null) {
    header('Location: login.php');
    exit();
}

// Regenerate session ID after successful login
session_regenerate_id(true);

// Constants
define('SESSION_TIMEOUT', 1800); // 30 minutes

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location: login.php');
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

