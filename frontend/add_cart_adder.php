<?php
include '../database/dbconnection.php';

if (isset($_POST['cart'])) {

    if (!isset($_SESSION['user_id'])) {
        header("location:../frontend/login.php");
        exit;
    }

    $register_user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);

    if ($product_id > 0 && $quantity > 0) {

        // 🔹 1. Get or Create Cart
        $cartQuery = $conn->prepare("SELECT id FROM carts WHERE register_user_id = ?");
        $cartQuery->bind_param("i", $register_user_id);
        $cartQuery->execute();
        $cartResult = $cartQuery->get_result();

        if ($cartResult->num_rows > 0) {
            $cart = $cartResult->fetch_assoc();
            $cart_id = $cart['id'];
        } else {
            $insertCart = $conn->prepare("INSERT INTO carts (register_user_id) VALUES (?)");
            $insertCart->bind_param("i", $register_user_id);
            $insertCart->execute();
            $cart_id = $insertCart->insert_id;
        }

        // 🔹 2. Get Product Price From DB (secure)
        $productQuery = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $productQuery->bind_param("i", $product_id);
        $productQuery->execute();
        $productResult = $productQuery->get_result();

        if ($productResult->num_rows == 0) {
            die("Invalid product");
        }

        $product = $productResult->fetch_assoc();
        $price = $product['price'];

        // 🔹 3. Check if product already in cart
        $checkStmt = $conn->prepare(
            "SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?"
        );
        $checkStmt->bind_param("ii", $cart_id, $product_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {

            $row = $checkResult->fetch_assoc();
            $newQuantity = $row['quantity'] + $quantity;

            $updateStmt = $conn->prepare(
                "UPDATE cart_items SET quantity = ? WHERE id = ?"
            );
            $updateStmt->bind_param("ii", $newQuantity, $row['id']);
            $updateStmt->execute();

        } else {

            $insertItem = $conn->prepare(
                "INSERT INTO cart_items (cart_id, product_id, quantity, price)
                 VALUES (?, ?, ?, ?)"
            );
            $insertItem->bind_param("iiid", $cart_id, $product_id, $quantity, $price);
            $insertItem->execute();
        }
    }

    if(isset($_POST['source_page']) && $_POST['source_page'] === 'product_detail'){
        header("Location: ../frontend/product-detail.php?id=" . $product_id . "&added=1");
    } else {
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    exit;
}
?>