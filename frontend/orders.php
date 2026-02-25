<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
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

// Fetch orders
$stmt = $conn->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE register_user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$orders = $stmt->get_result();
?>

<section style="padding:40px;" class="orders-section">
    <h2>My Orders</h2>

    <?php if ($orders->num_rows == 0): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr>
                <th>Order ID</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Placed On</th>
                <th>Action</th>
            </tr>
            <?php while ($order = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td>Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><?php echo ucfirst($order['status']); ?></td>
                    <td><?php echo $order['created_at']; ?></td>
                    <td>
                        <a href="order-details.php?order_id=<?php echo $order['id']; ?>">View Details</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>
</body>
</html>