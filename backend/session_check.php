<?php

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}
?>
