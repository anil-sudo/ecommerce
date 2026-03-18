<?php
include "../database/dbconnection.php";

// Add phone column if it doesn't exist
$check_column = $conn->query("SHOW COLUMNS FROM register_user LIKE 'phone'");

if ($check_column->num_rows === 0) {
    $alter_sql = "ALTER TABLE register_user ADD COLUMN phone VARCHAR(20) DEFAULT NULL AFTER email";
    if ($conn->query($alter_sql)) {
        echo "Phone column added successfully!";
    } else {
        echo "Error adding phone column: " . $conn->error;
    }
} else {
    echo "Phone column already exists!";
}

$conn->close();
?>
