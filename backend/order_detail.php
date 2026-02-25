<?php
session_start();
include '../database/dbconnection.php';
include 'session_check.php';

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$orderId = intval($_GET['id']);

$stmtOrder = $conn->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE id = ?");
$stmtOrder->bind_param("i", $orderId);
$stmtOrder->execute();
$orderResult = $stmtOrder->get_result();


if ($orderResult->num_rows == 0) {
    echo "<p>Order not found.</p>";
    exit;
}

$stmtOrder = $conn->prepare("
    SELECT o.id, o.total_amount, o.status AS order_status, o.created_at,
           t.status AS transaction_status, t.identifier AS transaction_id, t.note AS transaction_note,
           u.username, u.email, u.phone
    FROM orders o
    LEFT JOIN transactions t ON o.id = t.order_id
    LEFT JOIN register_user u ON o.register_user_id = u.id
    WHERE o.id = ?
");
$stmtOrder->bind_param("i", $orderId);
$stmtOrder->execute();
$orderResult = $stmtOrder->get_result();

if ($orderResult->num_rows == 0) {
    echo "<p>Order not found.</p>";
    exit;
}

$order = $orderResult->fetch_assoc();

// Fetch order items
$stmtItems = $conn->prepare("
    SELECT oi.quantity, oi.price, p.name, p.image 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$itemsResult = $stmtItems->get_result();

$totalAmount = 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | Admin Panel</title>
    <link rel="stylesheet" href="css/admin.css">

</head>
<body>

<?php include 'aside.php'; ?>


<div class="main">
    <div class="admin-order-wrapper">
    <style>
        /* ================= ADMIN ORDER LAYOUT ================= */

.admin-order-wrapper {
    max-width: 1200px;
    margin: 60px auto;
    padding: 0 20px;
}

.admin-order-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
    margin-top: 25px;
}

/* ================= ADMIN CARDS ================= */

.admin-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    border: 1px solid #f1f1f1;
}

.admin-card h3 {
    margin-bottom: 20px;
    font-size: 18px;
    font-weight: 600;
    color: #222;
}

/* ================= HEADER & BADGES ================= */

.admin-order-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.admin-order-title h2 {
    font-size: 24px;
    font-weight: 600;
}

.admin-order-date {
    color: #777;
    font-size: 14px;
}

.admin-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

/* Order Status Colors */
.admin-status-pending { background: #fff3cd; color: #856404; }
.admin-status-completed { background: #d4edda; color: #155724; }
.admin-status-cancelled { background: #f8d7da; color: #721c24; }

/* Payment Status */
.admin-payment-pending { background: #fff3cd; color: #856404; }
.admin-payment-paid { background: #d4edda; color: #155724; }
.admin-payment-failed { background: #f8d7da; color: #721c24; }

/* ================= INFO ROWS ================= */

.admin-info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f1f1;
}

.admin-info-row span {
    color: #777;
    font-size: 14px;
}

.admin-info-row strong {
    font-size: 14px;
    color: #222;
}

/* ================= TABLE ================= */

.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.admin-table thead {
    background: #f9fafb;
}

.admin-table th {
    text-align: left;
    padding: 14px;
    font-size: 14px;
    color: #666;
    font-weight: 600;
    border-bottom: 1px solid #eee;
}

.admin-table td {
    padding: 16px 14px;
    border-bottom: 1px solid #f1f1f1;
    font-size: 14px;
}

.admin-product-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-product-img {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
}

.admin-subtotal {
    font-weight: 600;
}

.admin-total-box {
    text-align: right;
    margin-top: 25px;
    font-size: 18px;
}
</style>
    <!-- Page Header Card -->
    <div class="admin-card admin-order-header">
        <div class="admin-order-title">
            <h2>Order #<?php echo $order['id']; ?></h2>
            <span class="admin-badge admin-status-<?php echo strtolower($order['order_status']); ?>">
                <?php echo ucfirst($order['order_status']); ?>
            </span>
        </div>
        <p class="admin-order-date">
            Placed on <?php echo date("F d, Y - h:i A", strtotime($order['created_at'])); ?>
        </p>
    </div>

    <!-- Customer + Payment Info -->
    <div class="admin-order-grid">

        <div class="admin-card admin-info-card">
            <h3>Customer Information</h3>
            <div class="admin-info-row">
                <span>Name</span>
                <strong><?php echo htmlspecialchars($order['username']); ?></strong>
            </div>
            <div class="admin-info-row">
                <span>Email</span>
                <strong><?php echo htmlspecialchars($order['email']); ?></strong>
            </div>
            <div class="admin-info-row">
                <span>Phone</span>
                <strong><?php echo htmlspecialchars($order['phone']); ?></strong>
            </div>
        </div>

        <div class="admin-card admin-info-card">
            <h3>Payment Details</h3>
            <div class="admin-info-row">
                <span>Payment Status</span>
                <strong class="admin-badge admin-payment-<?php echo strtolower($order['transaction_status'] ?? 'pending'); ?>">
                    <?php echo ucfirst($order['transaction_status'] ?? 'pending'); ?>
                </strong>
            </div>
            <div class="admin-info-row">
                <span>Method</span>
                <strong>
                    <?php echo ucfirst($order['transaction_note'] === "COD order" ? 'Cash on Delivery' : 'Esewa'); ?>
                </strong>
            </div>
            <div class="admin-info-row">
                <span>Transaction ID</span>
                <strong><?php echo $order['transaction_id'] ?? '-'; ?></strong>
            </div>
        </div>

    </div>

    <!-- Order Items -->
    <div class="admin-card admin-items-card">
        <h3>Order Items</h3>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $itemsResult->fetch_assoc()): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $totalAmount += $subtotal;
                ?>
                <tr>
                    <td class="admin-product-cell">
                        <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" class="admin-product-img">
                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                    </td>
                    <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td class="admin-subtotal">Rs. <?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="admin-total-box">
            Total Amount: <strong>Rs. <?php echo number_format($totalAmount, 2); ?></strong>
        </div>
    </div>

</div>
</div>

</body>
</html>
