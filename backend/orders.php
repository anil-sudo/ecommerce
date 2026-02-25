<?php
session_start();
include '../database/dbconnection.php';
include 'session_check.php';

// Handle search/filter
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

// Prepare base SQL
$sql = "SELECT o.id, o.total_amount, o.status AS order_status, 
               t.status AS transaction_status, u.username, u.email
        FROM orders o
        LEFT JOIN transactions t ON o.id = t.order_id
        LEFT JOIN register_user u ON o.register_user_id = u.id
        WHERE 1"; // base condition for easy appending

$params = [];
$types = "";

// Filter by status
if (!empty($statusFilter)) {
    $sql .= " AND o.status = ?";
    $params[] = $statusFilter;
    $types .= "s";
}

// Search by Order ID
if (!empty($search)) {
    $sql .= " AND o.id LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

$sql .= " ORDER BY o.id ASC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
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
    <h1>Orders</h1>

    <!-- Filters & Search --> 
    <form method="GET" class="top-controls" style="margin-bottom: 1rem;">
        <input type="text" name="search" placeholder="Search by Order ID..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="status">
            <option value="">Filter by Status</option>
            <option value="pending" <?php if($statusFilter=='pending') echo 'selected'; ?>>Pending</option>
            <option value="confirmed" <?php if($statusFilter=='confirmed') echo 'selected'; ?>>Confirmed</option>
            <option value="delivered" <?php if($statusFilter=='delivered') echo 'selected'; ?>>Delivered</option>
            <option value="cancelled" <?php if($statusFilter=='cancelled') echo 'selected'; ?>>Cancelled</option>
        </select>
        <button type="submit" class="btn btn-edit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Order Status</th>
                <th>Transaction Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>#" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>Rs." . number_format($row['total_amount'], 2) . "</td>";
                        echo "<td style='text-transform: capitalize;'>" . ucfirst($row['order_status']) . "</td>";
                        echo "<td style='text-transform: capitalize;'>" . ($row['transaction_status'] ? ucfirst($row['transaction_status']) : 'Pending') . "</td>";
                        echo "<td>
                                <a href='order_detail.php?id=" . $row['id'] . "'>
                                    <button class='btn btn-edit'>View</button>
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>No orders found.</td></tr>";
                }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>