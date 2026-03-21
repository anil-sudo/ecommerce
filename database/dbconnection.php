<?php

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-commerce";

// Secure Database Connection (Hide warnings in production)
ini_set('display_errors', 0);
mysqli_report(MYSQLI_REPORT_OFF); // Prevent exception leakage of sensitive data

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: Server error. Please try again later.");
}
?>
