<?php
include '../database/dbconnection.php';
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

    <style>
        .product-detail {
            max-width: 1100px;
            margin: 50px auto;
            display: flex;
            gap: 40px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
        }

        .product-image {
            flex: 1;
        }

        .product-image img {
            width: 100%;
            border-radius: 10px;
            object-fit: cover;
        }

        .product-info {
            flex: 1;
        }

        .product-info h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .rating {
            color: #FFD700;
            margin-bottom: 15px;
        }

        .price {
            font-size: 24px;
            font-weight: bold;
            color: orange;
            margin-bottom: 15px;
        }

        .description {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 25px;
            color: #555;
        }

        .quantity {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .quantity input {
            width: 60px;
            padding: 6px;
            text-align: center;
        }

        .btn-cart {
            background: orange;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-cart:hover {
            background: darkorange;
        }

        @media (max-width: 768px) {
            .product-detail {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

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

            <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
            <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">

            <button type="submit" name="cart" class="btn-cart">
                Add to Cart
            </button>
        </form>
    </div>

</section>
<?php else: ?>
    <p style="text-align:center; margin:50px;">Product not found.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

</body>
</html>
