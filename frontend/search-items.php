<?php
include '../database/dbconnection.php';
include 'add_cart_adder.php';

$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* product grid CSS */
        .products-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            background: #fff;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .product-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }

        .rating {
            color: #FFD700;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .price-cart {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .price-cart .price {
            color: orange;
            font-weight: bold;
            font-size: 16px;
        }

        .price-cart .btn-cart {
            background-color: orange;
            color: #fff;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .price-cart .btn-cart:hover {
            background-color: darkorange;
        }

        @media (max-width: 1024px) {
            .products-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-container {
                grid-template-columns: repeat(2, 1fr);
            }
            .product-card img {
                height: 160px;
            }
        }

        @media (max-width: 480px) {
            .products-container {
                grid-template-columns: 1fr;
            }
            .product-card img {
                height: 180px;
            }
        }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="search-results">
    <h2 style="text-align:center; margin:40px;">
        Search results for "<?php echo htmlspecialchars($search); ?>"
    </h2>

    <div class="products-container">
        <?php
        if ($search != "") {
            $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
            $term = "%" . $search . "%";
            $stmt->bind_param("s", $term);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($product = $result->fetch_assoc()):
        ?>
                    <div class="product-card">
                        <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>

                        <div class="rating">★★★★★</div>

                        <div class="price-cart">
                            <p class="price">Rs.<?php echo number_format($product['price'], 2); ?></p>
                            <form method="post" action="">
                                <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                                <button type="submit" name="cart" class="btn-cart">Add to Cart</button>
                            </form>
                        </div>
                    </div>
        <?php
                endwhile;
            } else {
                echo "<p style='text-align:center;'>No products found.</p>";
            }
        } else {
            echo "<p style='text-align:center;'>Please enter a search term.</p>";
        }
        ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

</body>
</html>
