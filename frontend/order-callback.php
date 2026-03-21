<?php
session_start();
include '../database/dbconnection.php';
include 'session_check.php';

// Redirect URLs
$successPage = '../frontend/order-success.php';
$failedPage  = '../frontend/order-failed.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $failedPage);
    exit;
}

$userId = $_SESSION['user_id'];

// ── STEP 1: Determine payment source ──────────────────────────────────────────
if (isset($_GET['data']) && !empty($_GET['data'])) {
    $source = 'esewa';
} else {
    $source = 'cod';
}

// ── STEP 2: Grab delivery address ─────────────────────────────────────────────
if ($source === 'esewa') {
    // Address was saved to session before eSewa redirect
    $street   = $_SESSION['delivery_street']   ?? '';
    $city     = $_SESSION['delivery_city']     ?? '';
    $state    = $_SESSION['delivery_state']    ?? '';
    $zip_code = $_SESSION['delivery_zip_code'] ?? '';
    $country  = $_SESSION['delivery_country']  ?? 'Nepal';

    // Clear from session after reading so it's not reused
    unset(
        $_SESSION['delivery_street'],
        $_SESSION['delivery_city'],
        $_SESSION['delivery_state'],
        $_SESSION['delivery_zip_code'],
        $_SESSION['delivery_country']
    );
} else {
    // COD — address comes directly via POST
    $street   = trim($_POST['street']   ?? '');
    $city     = trim($_POST['city']     ?? '');
    $state    = trim($_POST['state']    ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');
    $country  = trim($_POST['country']  ?? 'Nepal');
}

// ── STEP 3: UUID generator ─────────────────────────────────────────────────────
function generateUUIDv4() {
    $data    = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// ── STEP 4: Process payment source ────────────────────────────────────────────
if ($source === 'esewa') {

    $encodedData = $_GET['data'];
    $jsonData    = base64_decode($encodedData);

    if ($jsonData === false) {
        header("Location: " . $failedPage);
        exit;
    }

    $data = json_decode($jsonData, true);

    if ($data === null || !isset($data['status']) || $data['status'] !== 'COMPLETE') {
        header("Location: " . $failedPage);
        exit;
    }

    // --- eSewa Signature Verification ---
    $transaction_code = $data['transaction_code'] ?? '';
    $status = $data['status'] ?? '';
    $total_amount = $data['total_amount'] ?? '';
    $transaction_uuid = $data['transaction_uuid'] ?? '';
    $product_code = $data['product_code'] ?? 'EPAYTEST';
    $signed_field_names = $data['signed_field_names'] ?? 'transaction_code,status,total_amount,transaction_uuid,product_code,signed_field_names';
    
    // Construct the message string according to signed_field_names
    $fields = explode(',', $signed_field_names);
    $message_parts = [];
    foreach($fields as $field) {
        $message_parts[] = $field . '=' . ($data[$field] ?? '');
    }
    $message = implode(',', $message_parts);
    
    $secret_key = '8gBm/:&EnhH.1/q'; 
    $expected_signature = base64_encode(hash_hmac('sha256', $message, $secret_key, true));

    if (!isset($data['signature']) || $data['signature'] !== $expected_signature) {
        // Validation failed, potential tampering
        header("Location: " . $failedPage);
        exit;
    }
    // -------------------------------------

    $totalAmount       = $data['total_amount'] ?? 0;
    $transactionUUID   = !empty($data['transaction_uuid']) ? $data['transaction_uuid'] : generateUUIDv4();
    $orderStatus       = 'confirmed';
    $transactionStatus = 'success';
    $transactionNote   = json_encode($data);

} else {

    // COD — calculate total from cart
    $stmtCart = $conn->prepare("
        SELECT ci.quantity, ci.price
        FROM carts c
        JOIN cart_items ci ON c.id = ci.cart_id
        WHERE c.register_user_id = ?
    ");
    $stmtCart->bind_param("i", $userId);
    $stmtCart->execute();
    $cartResult = $stmtCart->get_result();

    if ($cartResult->num_rows === 0) {
        // Cart is empty — nothing to order
        header("Location: " . $failedPage);
        exit;
    }

    $totalAmount = 0;
    while ($item = $cartResult->fetch_assoc()) {
        $totalAmount += $item['quantity'] * $item['price'];
    }

    $transactionUUID   = generateUUIDv4();
    $orderStatus       = 'pending';
    $transactionStatus = 'pending';
    $transactionNote   = 'COD order';
}

// ── STEP 5: Save order to database ────────────────────────────────────────────
$conn->begin_transaction();

try {

    // 1. Insert order with delivery address
    $stmtOrder = $conn->prepare("
        INSERT INTO orders
            (register_user_id, total_amount, status, street, city, state, zip_code, country)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmtOrder->bind_param(
        "idssssss",
        $userId,
        $totalAmount,
        $orderStatus,
        $street,
        $city,
        $state,
        $zip_code,
        $country
    );
    $stmtOrder->execute();
    $orderId = $stmtOrder->insert_id;

    if (!$orderId) {
        throw new Exception("Failed to create order.");
    }

    // 2. Copy cart items into order_items (with historical product_name)
    $stmtCartItems = $conn->prepare("
        SELECT ci.product_id, ci.quantity, ci.price, p.name as product_name
        FROM carts c
        JOIN cart_items ci ON c.id = ci.cart_id
        JOIN products p ON ci.product_id = p.id
        WHERE c.register_user_id = ?
    ");
    $stmtCartItems->bind_param("i", $userId);
    $stmtCartItems->execute();
    $cartItems = $stmtCartItems->get_result();

    if ($cartItems->num_rows === 0) {
        throw new Exception("No cart items found.");
    }

    $stmtInsertItem = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
        VALUES (?, ?, ?, ?, ?)
    ");

    while ($item = $cartItems->fetch_assoc()) {
        $stmtInsertItem->bind_param(
            "iisid",
            $orderId,
            $item['product_id'],
            $item['product_name'],
            $item['quantity'],
            $item['price']
        );
        $stmtInsertItem->execute();
    }

    // 3. Record transaction
    $stmtTrans = $conn->prepare("
        INSERT INTO transactions (order_id, identifier, status, note)
        VALUES (?, ?, ?, ?)
    ");
    $stmtTrans->bind_param(
        "isss",
        $orderId,
        $transactionUUID,
        $transactionStatus,
        $transactionNote
    );
    $stmtTrans->execute();

    // 4. Clear the user's cart
    $stmtClearCart = $conn->prepare("
        DELETE ci FROM cart_items ci
        JOIN carts c ON ci.cart_id = c.id
        WHERE c.register_user_id = ?
    ");
    $stmtClearCart->bind_param("i", $userId);
    $stmtClearCart->execute();

    // All good — commit
    $conn->commit();

    header("Location: " . $successPage . "?order_id=" . $orderId);
    exit;

} catch (Exception $e) {
    $conn->rollback();
    header("Location: " . $failedPage . "?error=" . urlencode($e->getMessage()));
    exit;
}
?>