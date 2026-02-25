<?php
    session_start();
    include '../database/dbconnection.php';

    $totalItems = 0;

    if (isset($_SESSION['user_id'])) {

        $register_user_id = $_SESSION['user_id'];

        // 🔹 Get user's cart
        $cartStmt = $conn->prepare("SELECT id FROM carts WHERE register_user_id = ?");
        $cartStmt->bind_param("i", $register_user_id);
        $cartStmt->execute();
        $cartResult = $cartStmt->get_result();

        if ($cartResult->num_rows > 0) {

            $cart = $cartResult->fetch_assoc();
            $cart_id = $cart['id'];

            // 🔹 Get total quantity
            $countStmt = $conn->prepare(
                "SELECT SUM(quantity) AS total FROM cart_items WHERE cart_id = ?"
            );
            $countStmt->bind_param("i", $cart_id);
            $countStmt->execute();
            $countResult = $countStmt->get_result();

            if ($countResult->num_rows > 0) {
                $row = $countResult->fetch_assoc();
                $totalItems = $row['total'] ?? 0;
            }
        }
    }
?>

<header class="header">
    <div class="navbar">

        <!-- Logo -->
        <div class="logo">
            <a href="../frontend/index.php">ShopEase</a>
        </div>

        <!-- Navigation -->
        <nav class="nav-links">
            <a href="../frontend/index.php">Home</a>
            <a href="../frontend/product.php">Products</a>
            <a href="../frontend/about.php">About Us</a>
        </nav>

        <!-- ================= SEARCH PART START ================= -->
        <!-- This form sends the search keyword to search-items.php -->
        <div class="search-box">
            <form action="../frontend/search-items.php" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search products..." 
                    autocomplete="off"
                    required
                >
                <button type="submit">Search</button>
            </form>
        </div>
        <!-- ================== SEARCH PART END ================== -->

        <!-- Right actions -->
        <div class="nav-actions">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="../frontend/cart.php" class="cart">
                    🛒 Cart
                    <?php if ($totalItems > 0): ?>
                        <span class="cart-count"><?php echo $totalItems; ?></span>
                    <?php endif; ?>
                </a>

                <a href="../frontend/profile.php" class="profile">
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>

                <a href="../database/logout.php" class="profile">Logout</a>
            <?php else: ?>
                <a href="../frontend/login.php" class="sign">Sign in</a>
            <?php endif; ?>
        </div>

    </div>
</header>
