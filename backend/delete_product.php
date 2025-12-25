<?php
session_start();
include '../database/dbconnection.php';
include 'session_check.php';
// Check if id is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // First, get the image filename to delete the file
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = "../assets/images/" . $row['image'];

        // Delete the product from the database
        $stmt_del = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt_del->bind_param("i", $id);

        if ($stmt_del->execute()) {
            // Delete the image file if exists
            if (file_exists($image_path)) {
                unlink($image_path);
            }
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
