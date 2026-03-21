<?php
session_start();
include '../database/dbconnection.php';
include 'session_check.php';

// strictly require POST request for deletion (CSRF prevention)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Check if id is provided
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];

    // First, check if product exists
    $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Soft delete the product from the catalog (do NOT delete image)
        $stmt_del = $conn->prepare("UPDATE products SET is_deleted = 1 WHERE id = ?");
        $stmt_del->bind_param("i", $id);

        if ($stmt_del->execute()) {
            // Redirect back to admin panel
            header("Location: admin.php");
            exit();
        } else {
            echo "Error deleting product: " . $stmt_del->error;
        }
    } else {
        echo "Product not found!";
    }
} else {
    echo "Invalid product ID!";
}
?>
