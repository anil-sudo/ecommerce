<?php
session_start();
include '../database/dbconnection.php';

$totalItems = 0;

// Get current page for nav highlighting
$currentPage = basename($_SERVER['PHP_SELF']);

if (isset($_SESSION['user_id'])) {
    $register_user_id = $_SESSION['user_id'];

    // Get user's cart
    $cartStmt = $conn->prepare("SELECT id FROM carts WHERE register_user_id = ?");
    $cartStmt->bind_param("i", $register_user_id);
    $cartStmt->execute();
    $cartResult = $cartStmt->get_result();

    if ($cartResult->num_rows > 0) {
        $cart = $cartResult->fetch_assoc();
        $cart_id = $cart['id'];

        // Get total quantity
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
            <a href="../frontend/index.php" class="<?= ($currentPage == 'index.php') ? 'active' : '' ?>">Home</a>
            <a href="../frontend/product.php" class="<?= ($currentPage == 'product.php') ? 'active' : '' ?>">Products</a>
            <a href="../frontend/about.php" class="<?= ($currentPage == 'about.php') ? 'active' : '' ?>">About Us</a>
        </nav>

        <!-- Search -->
        <div class="search-box">
            <form action="../frontend/search-items.php" method="GET">
                <input type="text" name="search" placeholder="Search products..." autocomplete="off" required>
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Right actions -->
        <div class="nav-actions">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="../frontend/cart.php" class="cart">
                    🛒 Cart
                    <?php if ($totalItems > 0): ?>
                        <span class="cart-count"><?= $totalItems ?></span>
                    <?php endif; ?>
                </a>

                <a href="../frontend/profile.php" class="profile"><?= htmlspecialchars($_SESSION['username']) ?></a>
                <a href="../database/logout.php" class="profile">Logout</a>
            <?php else: ?>
                <a href="../frontend/login.php" class="sign">Sign in</a>
            <?php endif; ?>
        </div>

    </div>
</header>

<!-- CSS for nav highlighting -->
<style>
.nav-links {
    display: flex;
    gap: 22px;
}

.nav-links a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    padding: 6px 0;
    position: relative;
    transition: color 0.3s ease;
}

.nav-links a::after {
    content: "";
    position: absolute;
    width: 0%;
    height: 2px;
    left: 0;
    bottom: -4px;
    background-color: #ff7a00;
    transition: width 0.3s ease;
}

.nav-links a:hover {
    color: #ff7a00;
}

.nav-links a:hover::after {
    width: 100%;
}

/* Active page highlight */
.nav-links a.active {
    color: #ff7a00;
    font-weight: 600;
}

.nav-links a.active::after {
    width: 100%;
}f
</style>