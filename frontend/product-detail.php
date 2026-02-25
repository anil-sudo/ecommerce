<?php
    include '../database/dbconnection.php';
    include '../includes/header.php';
    include 'add_cart_adder.php';

    $product = null;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $productId = (int) $_GET['id'];

        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
        }
        $stmt->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product ? htmlspecialchars($product['name']) : 'Product'; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php if ($product): ?>
<section class="product-detail">

    <!-- Product Image -->
    <div class="product-image">
        <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>"
             alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>

    <!-- Product Info -->
    <div class="product-info">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>

        <div class="rating">★★★★★</div>

        <div class="price">
            Rs. <?php echo number_format($product['price'], 2); ?>
        </div>

        <form method="post">
            <div class="quantity">
                <label>Qty:</label>
                <input type="number" name="quantity" value="1" min="1">
            </div>

            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="source_page" value="product_detail">
            
            <button type="submit" name="cart" class="btn-cart">Add to Cart</button>
        </form>
    </div>

</section>
<?php else: ?>
    <p style="text-align:center; margin:50px;">Product not found.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

</body>
</html>
