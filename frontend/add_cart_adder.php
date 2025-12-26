
<?php

// Handle Add to Cart
if (isset($_POST['cart'])) {
    if(!isset($_SESSION['username'])) {
        header("location:../frontend/login.php");
    }

    $name  = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $image = $_POST['image'] ?? '';
    $quantity = $_POST['quantity'] ?? 1;

    if ($name && $price > 0 && $quantity > 0 && $image) {
        $checkStmt = $conn->prepare("SELECT id, quantity FROM cart WHERE name = ?");
        $checkStmt->bind_param("s", $name);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult && $checkResult->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            $newQuantity = $row['quantity'] + $quantity;
            $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $updateStmt->bind_param("ii", $newQuantity, $row['id']);
            $updateStmt->execute();
        } else {
            $quantity = $quantity;
            $stmt = $conn->prepare("INSERT INTO cart(name, image, price, quantity) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssdi", $name, $image, $price, $quantity);
            $stmt->execute();
        }
    }
}
?>