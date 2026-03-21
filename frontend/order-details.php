<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php 
include '../includes/header.php';
include '../database/dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if (!isset($_GET['order_id'])) {
    echo "<p>Order ID not specified.</p>";
    exit;
}

$orderId = intval($_GET['order_id']);

// Fetch order info
$stmtOrder = $conn->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE id = ? AND register_user_id = ?");
$stmtOrder->bind_param("ii", $orderId, $userId);
$stmtOrder->execute();
$orderResult = $stmtOrder->get_result();

if ($orderResult->num_rows == 0) {
    echo "<p>Order not found.</p>";
    exit;
}

$order = $orderResult->fetch_assoc();

// Fetch order items (historical decoupling)
$stmtItems = $conn->prepare("
    SELECT oi.quantity, oi.price, oi.product_name, p.image, p.is_deleted 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$itemsResult = $stmtItems->get_result();

$totalAmount = 0;
?>

<section style="padding:40px;" class="order-items-section">
    <h2>Order #<?php echo $order['id']; ?></h2>
    <p>Status: <strong><?php echo ucfirst($order['status']); ?></strong></p>
    <p>Placed on: <?php echo $order['created_at']; ?></p>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($item = $itemsResult->fetch_assoc()): 
            $subtotal = $item['price'] * $item['quantity'];
            $totalAmount += $subtotal;
        ?>
            <tr>
                <td>
                    <?php 
                        $imgSrc = !empty($item['image']) ? "../assets/images/" . htmlspecialchars($item['image']) : "../assets/images/placeholder.jpg";
                        $displayName = htmlspecialchars($item['product_name']);
                        if ($item['image'] === null || $item['is_deleted'] == 1) {
                            $displayName .= " <span style='color:red; font-size:12px;'>(Unav.)</span>";
                        }
                    ?>
                    <img src="<?php echo $imgSrc; ?>" width="60" alt="Product Image" style="vertical-align: middle; margin-right: 10px;">
                    <?php echo $displayName; ?>
                </td>
                <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>Rs. <?php echo number_format($subtotal, 2); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Total Amount: Rs. <?php echo number_format($totalAmount, 2); ?></h3>
</section>

<?php include '../includes/footer.php'; ?>
</body>
</html>