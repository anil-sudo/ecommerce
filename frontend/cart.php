<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include '../database/dbconnection.php';
include '../includes/header.php';

/* ===============================
   HANDLE UPDATE
================================ */
if (isset($_POST['update'])) {
    $id  = (int)$_POST['cart_id'];
    $qty = (int)$_POST['quantity'];
    if ($qty < 1) $qty = 1;

    $conn->query("UPDATE cart SET quantity=$qty WHERE id=$id");
    header("Location: cart.php");
    exit;
}

/* ===============================
   HANDLE REMOVE
================================ */
if (isset($_POST['remove'])) {
    $id = (int)$_POST['cart_id'];
    $conn->query("DELETE FROM cart WHERE id=$id");
    header("Location: cart.php");
    exit;
}

/* ===============================
   FETCH CART DATA
================================ */
$sql = "SELECT id, name, image, price, quantity FROM cart";
$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cart</title>

<style>
body{font-family:Arial;background:#f3f4f6;margin:0}
.container{max-width:900px;margin:40px auto;background:#fff;padding:25px;border-radius:10px}
table{width:100%;border-collapse:collapse}
th,td{padding:12px;text-align:center;border-bottom:1px solid #ddd}
th{background:#2563eb;color:#fff}
img{width:60px;height:60px;object-fit:cover}
input{width:60px;padding:5px}
button{padding:6px 12px;border:none;border-radius:4px;cursor:pointer}
.update{background:#3b82f6;color:#fff}
.remove{background:#ef4444;color:#fff}
.total{text-align:right;margin-top:20px;font-weight:bold;font-size:18px}

</style>
<link rel = "stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="container">
<h2 align="center">My Cart</h2>

<?php if ($result->num_rows > 0): ?>
<table>
<tr>
    <th>Image</th>
    <th>Name</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Subtotal</th>
    <th>Action</th>
</tr>

<?php
$grand = 0;
while ($row = $result->fetch_assoc()):
    $sub = $row['price'] * $row['quantity'];
    $grand += $sub;
?>
<tr>
    <td>
        <img src="../assets/images/<?php echo $row['image']; ?>">
    </td>
    <td><?php echo $row['name']; ?></td>
    <td>Rs. <?php echo $row['price']; ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
            <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
            <button name="update" class="update">Update</button>
        </form>
    </td>
    <td>Rs. <?php echo $sub; ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
            <button name="remove" class="remove">Remove</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>

<div class="total">
Grand Total: Rs.<?php echo $grand; ?>
</div>

<?php else: ?>
<p align="center">Cart is empty</p>
<?php endif; ?>

</div>
</body>
</html>
