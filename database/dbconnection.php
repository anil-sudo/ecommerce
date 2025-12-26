<?php

$servername = "db";
$username = "ecommerce_user";
$password = "ecommerce_pass";
$dbname = "ecommerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>