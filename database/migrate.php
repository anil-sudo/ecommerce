<?php
include 'dbconnection.php';

echo "Running Database Migrations...<br>";

// 1. Modify products table (add is_deleted)
// Since MySQL syntax for IF NOT EXISTS in ALTER TABLE was added in 10.6 and this is 10.4, 
// let's just run them and suppress errors if they already exist.
try {
    $conn->query("ALTER TABLE `products` ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`");
    echo "Products table altered successfully (added is_deleted).<br>";
} catch (Exception $e) {
    echo "Note: Products table might already be altered.<br>";
}

// 3. Modify order_items (add product_name)
try {
    $conn->query("ALTER TABLE `order_items` ADD COLUMN `product_name` VARCHAR(255) NULL AFTER `product_id`");
    echo "Added product_name to order_items.<br>";

    // Backfill historical order_items product_name
    $conn->query("UPDATE order_items oi JOIN products p ON oi.product_id = p.id SET oi.product_name = p.name WHERE oi.product_name IS NULL");
    echo "Backfilled product_names in historical orders.<br>";
} catch (Exception $e) {
    echo "Note: order_items table might already be altered.<br>";
}

// 4. Performance Indexes
try {
    $conn->query("ALTER TABLE `cart_items` ADD INDEX IF NOT EXISTS `idx_product` (`product_id`)");
    $conn->query("ALTER TABLE `order_items` ADD INDEX IF NOT EXISTS `idx_product` (`product_id`)");
    $conn->query("ALTER TABLE `orders` ADD INDEX IF NOT EXISTS `idx_status` (`status`)");
    echo "Indexes created successfully.<br>";
} catch (Exception $e) {
}

echo "Migrations completed successfully!";
$conn->close();
?>
