<?php
session_start();
include '../database/dbconnection.php';
include 'session_check.php';

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$orderId = intval($_GET['id']);

// Single optimized query — fetch order + customer + transaction + address
$stmtOrder = $conn->prepare("
    SELECT o.id, o.total_amount, o.status AS order_status, o.created_at,
           o.street, o.city, o.state, o.zip_code, o.country,
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

// Build address string
$addressParts = array_filter([
    $order['street'],
    $order['city'],
    $order['state'],
    $order['zip_code'],
    $order['country']
]);
$fullAddress = !empty($addressParts) ? implode(', ', $addressParts) : 'Not provided';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo $order['id']; ?> | Admin Panel</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .admin-order-wrapper {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .admin-order-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .admin-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid #f1f1f1;
        }

        .admin-card h3 {
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 600;
            color: #222;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f1f1;
        }

        .admin-order-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .admin-order-title h2 {
            font-size: 24px;
            font-weight: 600;
            color: #111;
        }

        .admin-order-date {
            color: #777;
            font-size: 14px;
            margin-top: 6px;
        }

        /* Badges */
        .admin-badge {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .admin-status-pending    { background: #fff3cd; color: #856404; }
        .admin-status-confirmed  { background: #d4edda; color: #155724; }
        .admin-status-delivered  { background: #cce5ff; color: #004085; }
        .admin-status-cancelled  { background: #f8d7da; color: #721c24; }
        .admin-payment-pending   { background: #fff3cd; color: #856404; }
        .admin-payment-success   { background: #d4edda; color: #155724; }
        .admin-payment-failed    { background: #f8d7da; color: #721c24; }

        /* Info rows */
        .admin-info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f5f5f5;
            gap: 12px;
        }

        .admin-info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .admin-info-row span {
            color: #888;
            font-size: 13px;
            white-space: nowrap;
            padding-top: 2px;
        }

        .admin-info-row strong {
            font-size: 14px;
            color: #222;
            text-align: right;
        }

        /* Address card */
        .address-block {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-top: 4px;
        }

        .address-icon {
            width: 36px;
            height: 36px;
            background: #f0f4ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .address-lines {
            font-size: 14px;
            color: #333;
            line-height: 1.7;
        }

        .address-lines .addr-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            color: #aaa;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        /* Table */
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
            padding: 13px 16px;
            font-size: 13px;
            color: #666;
            font-weight: 600;
            border-bottom: 1px solid #eee;
        }

        .admin-table td {
            padding: 16px;
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
            color: #333;
            vertical-align: middle;
        }

        .admin-table tbody tr:last-child td {
            border-bottom: none;
        }

        .admin-table tbody tr:hover {
            background: #fafafa;
        }

        .admin-product-cell {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .admin-product-img {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #eee;
            flex-shrink: 0;
        }

        .admin-subtotal {
            font-weight: 600;
            color: #111;
        }

        .admin-total-box {
            text-align: right;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 2px solid #f1f1f1;
            font-size: 17px;
            color: #333;
        }

        .admin-total-box strong {
            font-size: 20px;
            color: #111;
        }

        /* Back button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #555;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #e5e5e5;
            background: #fff;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: #f5f5f5;
            color: #111;
        }
    </style>
</head>
<body>

<?php include 'aside.php'; ?>

<div class="main">
    <div class="admin-order-wrapper">

        <a href="orders.php" class="back-btn">← Back to Orders</a>

        <!-- Header Card -->
        <div class="admin-card">
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

        <!-- Customer + Payment + Address grid -->
        <div class="admin-order-grid">

            <!-- Customer Info -->
            <div class="admin-card">
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
                    <strong><?php echo htmlspecialchars($order['phone'] ?? '—'); ?></strong>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="admin-card">
                <h3>Payment Details</h3>
                <div class="admin-info-row">
                    <span>Payment Status</span>
                    <strong>
                        <span class="admin-badge admin-payment-<?php echo strtolower($order['transaction_status'] ?? 'pending'); ?>">
                            <?php echo ucfirst($order['transaction_status'] ?? 'Pending'); ?>
                        </span>
                    </strong>
                </div>
                <div class="admin-info-row">
                    <span>Method</span>
                    <strong>
                        <?php echo ($order['transaction_note'] === 'COD order') ? 'Cash on Delivery' : 'eSewa'; ?>
                    </strong>
                </div>
                <div class="admin-info-row">
                    <span>Transaction ID</span>
                    <strong style="font-size:12px; word-break:break-all;">
                        <?php echo htmlspecialchars($order['transaction_id'] ?? '—'); ?>
                    </strong>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="admin-card">
                <h3>Delivery Address</h3>
                <div class="address-block">
                    <div class="address-icon">📍</div>
                    <div class="address-lines">
                        <?php if (!empty($order['street']) || !empty($order['city'])): ?>
                            <?php if (!empty($order['street'])): ?>
                                <div><?php echo htmlspecialchars($order['street']); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($order['city']) || !empty($order['state'])): ?>
                                <div>
                                    <?php echo htmlspecialchars(implode(', ', array_filter([$order['city'], $order['state']]))); ?>
                                    <?php if (!empty($order['zip_code'])): ?>
                                        &nbsp;<?php echo htmlspecialchars($order['zip_code']); ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($order['country'])): ?>
                                <div><?php echo htmlspecialchars($order['country']); ?></div>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color:#aaa;">No address provided</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Order Items -->
        <div class="admin-card" style="margin-top: 25px;">
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
                        <td>
                            <div class="admin-product-cell">
                                <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>"
                                     class="admin-product-img"
                                     alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <span><?php echo htmlspecialchars($item['name']); ?></span>
                            </div>
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