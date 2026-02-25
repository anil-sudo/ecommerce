<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../includes/header.php'; ?>
<?php
include '../database/dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$register_user_id = $_SESSION['user_id'];
$totalAmount = 0;
$cartItems = [];

/* 🔹 Get User Cart */
$cartStmt = $conn->prepare("SELECT id FROM carts WHERE register_user_id = ?");
$cartStmt->bind_param("i", $register_user_id);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();

if ($cartResult->num_rows > 0) {
    $cart = $cartResult->fetch_assoc();
    $cart_id = $cart['id'];

    /* 🔹 Handle Quantity Update */
    if (isset($_POST['update'])) {
        $item_id = intval($_POST['item_id']);
        $quantity = max(1, intval($_POST['quantity']));

        $updateStmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $updateStmt->bind_param("ii", $quantity, $item_id);
        $updateStmt->execute();

        header("Location: cart.php");
        exit;
    }

    /* 🔹 Handle Remove */
    if (isset($_POST['remove'])) {
        $item_id = intval($_POST['item_id']);

        $deleteStmt = $conn->prepare("DELETE FROM cart_items WHERE id = ?");
        $deleteStmt->bind_param("i", $item_id);
        $deleteStmt->execute();

        header("Location: cart.php");
        exit;
    }

    /* 🔹 Get Cart Items */
    $itemsStmt = $conn->prepare("
        SELECT ci.id, ci.quantity, ci.price,
               p.name, p.image
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.cart_id = ?
    ");
    $itemsStmt->bind_param("i", $cart_id);
    $itemsStmt->execute();
    $itemsResult = $itemsStmt->get_result();

    while ($row = $itemsResult->fetch_assoc()) {
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $totalAmount += $row['subtotal'];
        $cartItems[] = $row;
    }
}

function generateUUIDv4() {
    $data = random_bytes(16);

    // Set version to 0100
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$uuid = generateUUIDv4();

$message = "total_amount=". $totalAmount .",transaction_uuid=". $uuid .",product_code=EPAYTEST";
$signature = hash_hmac('sha256', $message, '8gBm/:&EnhH.1/q', true);

?>
<section class="cart-section" style="padding:40px;">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>

            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td>
                        <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" width="60">
                        <?php echo htmlspecialchars($item['name']); ?>
                    </td>

                    <td>Rs. <?php echo number_format($item['price'], 2); ?></td>

                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width:60px;">
                            <button type="submit" name="update">Update</button>
                        </form>
                    </td>

                    <td>Rs. <?php echo number_format($item['subtotal'], 2); ?></td>

                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="remove" style="color:red;">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3 style="margin-top:20px;">
            Total: Rs. <?php echo number_format($totalAmount, 2); ?>
        </h3>

        <div style="display: flex;flex-direction: row; gap: .5rem;">
            <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                <input type="hidden" id="amount" name="amount" value="<?php echo ($totalAmount-(13/100)*$totalAmount); ?>" required>
                <input type="hidden" id="tax_amount" name="tax_amount" value ="<?php echo ((13/100)*$totalAmount); ?>" required>
                <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $totalAmount; ?>" required>
                <input type="hidden" id="transaction_uuid" name="transaction_uuid" value="<?php echo $uuid; ?>" required>
                <input type="hidden" id="product_code" name="product_code" value ="EPAYTEST" required>
                <input type="hidden" id="product_service_charge" name="product_service_charge" value="0" required>
                <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
                <input type="hidden" id="success_url" name="success_url" value="http://localhost/e-commerce/frontend/order-callback.php" required>
                <input type="hidden" id="failure_url" name="failure_url" value="http://localhost/e-commerce/frontend/order-failed.php" required>
                <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required>
                <input type="hidden" id="signature" name="signature" value="<?php echo base64_encode($signature); ?>" required>
                <button type="submit" class="btn" style="margin-top:20px; background-color: #377948;">Pay with eSewa</button>
            </form>    
            <a href="http://localhost/e-commerce/frontend/order-callback.php" class="btn" style="margin-top:20px;">COD</a>   
        </div>

    <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>

</body>
</html>