<?php
// Migration script to add admin-orders relationship
include '../database/dbconnection.php';

try {
    // Add admin_id column to orders table
    $sql1 = "ALTER TABLE `orders`
             ADD COLUMN `admin_id` INT(11) NULL AFTER `register_user_id`,
             ADD CONSTRAINT `fk_orders_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL";

    if ($conn->query($sql1) === TRUE) {
        echo "✓ Successfully added admin_id column to orders table\n";
    } else {
        throw new Exception("Error adding admin_id column: " . $conn->error);
    }

    // Add index for better query performance
    $sql2 = "ALTER TABLE `orders` ADD INDEX `idx_orders_admin_id` (`admin_id`)";

    if ($conn->query($sql2) === TRUE) {
        echo "✓ Successfully added index on admin_id\n";
    } else {
        throw new Exception("Error adding index: " . $conn->error);
    }

    // Optional: Assign existing orders to first admin
    $sql3 = "UPDATE `orders` SET `admin_id` = (SELECT `id` FROM `admins` ORDER BY `id` LIMIT 1) WHERE `admin_id` IS NULL";

    if ($conn->query($sql3) === TRUE) {
        echo "✓ Successfully assigned existing orders to default admin\n";
    } else {
        echo "Note: Could not assign existing orders to admin (this is optional): " . $conn->error . "\n";
    }

    echo "\n🎉 Migration completed successfully!\n";
    echo "The admins table now has a relationship with the orders table.\n";

} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
}

$conn->close();
?>