-- Migration to add admin relationship with orders
-- This establishes which admin processed/confirmed each order

-- Add admin_id column to orders table
ALTER TABLE `orders`
ADD COLUMN `admin_id` INT(11) NULL AFTER `register_user_id`,
ADD CONSTRAINT `fk_orders_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

-- Add index for better query performance
ALTER TABLE `orders`
ADD INDEX `idx_orders_admin_id` (`admin_id`);

-- Optional: Update existing orders to assign to a default admin (first admin)
-- Uncomment the line below if you want to assign existing orders to the first admin
-- UPDATE `orders` SET `admin_id` = (SELECT `id` FROM `admins` ORDER BY `id` LIMIT 1) WHERE `admin_id` IS NULL;