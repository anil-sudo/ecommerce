<?php
session_start();
include '../database/dbconnection.php';

// Redirect URLs
$successPage = '../frontend/order-success.php';
$failedPage  = '../frontend/order-failed.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $failedPage);
    exit;
}

$userId = $_SESSION['user_id'];

// Function to generate UUID v4
function generateUUIDv4() {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// Determine source based on presence of `data` parameter
if (isset($_GET['data']) && !empty($_GET['data'])) {
    $source = 'esewa';
} else {
    $source = 'cod';
}

if ($source === 'esewa') {
    // eSewa flow
    $encodedData = $_GET['data'];
    $jsonData = base64_decode($encodedData);
    if ($jsonData === false) {
        header("Location: " . $failedPage);
        exit;
    }

    $data = json_decode($jsonData, true);
    if ($data === null || !isset($data['status']) || $data['status'] !== 'COMPLETE') {
        header("Location: " . $failedPage);
        exit;
    }

    $totalAmount       = $data['total_amount'] ?? 0;
    $transactionUUID   = !empty($data['transaction_uuid']) ? $data['transaction_uuid'] : generateUUIDv4();
    $orderStatus       = 'confirmed';   // order delivery status
    $transactionStatus = 'success';        // payment status
    $transactionNote   = json_encode($data);

} else {
    // COD flow
    $transactionUUID   = generateUUIDv4();
    $orderStatus       = 'pending';     // order delivery status
    $transactionStatus = 'pending';     // payment status
    $transactionNote   = 'COD order';

    // Calculate total from cart
    $stmtCart = $conn->prepare("SELECT ci.quantity, ci.price 
                                FROM carts c 
                                JOIN cart_items ci ON c.id = ci.cart_id 
                                WHERE c.register_user_id = ?");
    $stmtCart->bind_param("i", $userId);
    $stmtCart->execute();
    $cartResult = $stmtCart->get_result();

    $totalAmount = 0;
    while ($item = $cartResult->fetch_assoc()) {
        $totalAmount += $item['quantity'] * $item['price'];
    }
}

// Process order
$conn->begin_transaction();

try {
    // 1️⃣ Insert order
    $stmtOrder = $conn->prepare("INSERT INTO orders (register_user_id, total_amount, status) VALUES (?, ?, ?)");
    $stmtOrder->bind_param("ids", $userId, $totalAmount, $orderStatus);
    $stmtOrder->execute();
    $orderId = $stmtOrder->insert_id;

    // 2️⃣ Copy cart items to order_items
    $stmtCartItems = $conn->prepare("SELECT ci.product_id, ci.quantity, ci.price 
                                     FROM carts c 
                                     JOIN cart_items ci ON c.id = ci.cart_id 
                                     WHERE c.register_user_id = ?");
    $stmtCartItems->bind_param("i", $userId);
    $stmtCartItems->execute();
    $cartItems = $stmtCartItems->get_result();

    $stmtInsertItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    while ($item = $cartItems->fetch_assoc()) {
        $stmtInsertItem->bind_param("iiid", $orderId, $item['product_id'], $item['quantity'], $item['price']);
        $stmtInsertItem->execute();
    }

    // 3️⃣ Record transaction
    $stmtTrans = $conn->prepare("INSERT INTO transactions (order_id, identifier, status, note) VALUES (?, ?, ?, ?)");
    $stmtTrans->bind_param("isss", $orderId, $transactionUUID, $transactionStatus, $transactionNote);
    $stmtTrans->execute();

    // 4️⃣ Clear cart
    $stmtClearCart = $conn->prepare("DELETE ci FROM cart_items ci 
                                     JOIN carts c ON ci.cart_id = c.id 
                                     WHERE c.register_user_id = ?");
    $stmtClearCart->bind_param("i", $userId);
    $stmtClearCart->execute();

    $conn->commit();

    // Redirect to success page
    header("Location: " . $successPage . "?order_id=" . $orderId);
    exit;

} catch (Exception $e) {
    $conn->rollback();
    header("Location: " . $failedPage);
    exit;
}
?>