<?php
    include '../database/dbconnection.php';

    if (isset($_SESSION['role'])) {
        $isSuperAdmin = $_SESSION['role'] === 'admin' ? true : false;        
    } else {    
        header("Location: ../frontend/index.php");
    }

    // Get current page name
    $currentPage = basename($_SERVER['PHP_SELF']);
?>
<?php
include '../database/dbconnection.php';

if (isset($_SESSION['role'])) {
    $isSuperAdmin = $_SESSION['role'] === 'admin';
} else {
    header("Location: ../frontend/index.php");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar" id="sidebar">

    <h2 class="logo"><i class="fa-solid fa-cubes"></i> ShopEase</h2>
    <nav class="sidebar-nav">

        <div class="nav-main">
            <a href="admin.php" class="<?= ($currentPage == 'admin.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>

            <?php if ($isSuperAdmin): ?>
                <a href="users.php" class="<?= ($currentPage == 'users.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-user-shield"></i> Admin Users
                </a>

                <a href="customer.php" class="<?= ($currentPage == 'customer.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-user-group"></i> Customers
                </a>

                <a href="orders.php" class="<?= ($currentPage == 'orders.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-box-open"></i> Order Management
                </a>
            <?php endif; ?>
        </div>

        <a href="logout.php" class="logout-btn">
            <i class="fa-solid fa-sign-out-alt"></i> Logout
        </a>

    </nav>

</aside>
  


<style>
    .logo{
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
}
/* Sidebar */
.sidebar {
    width: 250px; 
    height: 100vh; 
    background: #111827; 
    color: #fff;
    position: fixed;
    top:0; left:0;
    display: flex;
    flex-direction: column;
    transition: width 0.3s;
    overflow: hidden;
    box-shadow: 2px 0 10px rgba(0,0,0,0.2);
}
.sidebar.collapsed { width: 70px; }
.sidebar h2 {
    text-align:center;
    margin:25px 0;
    font-size:1.8em;
    letter-spacing:1px;
}
.sidebar nav {
    flex:1;
    display:flex;
    flex-direction:column;
}
.sidebar a {
    color:#fff; 
    padding:15px 20px; 
    text-decoration:none; 
    display:flex;
    align-items:center;
    gap:15px;
    transition:0.3s; 
    border-left:4px solid transparent;
}
.sidebar a i { width:20px; text-align:center; font-size:1.1em; }
.sidebar a:hover { background:#1f2937; border-left:4px solid #3b82f6; }
.sidebar a.active { background:#3b82f6; border-left:4px solid #2563eb; }
.sidebar a.active {
    background:#3b82f6;
    border-left:4px solid #2563eb;
}
/* Sidebar container */
.sidebar-nav {
    display: flex;
    flex-direction: column;
    height: 100vh;
    padding: 20px;
    background-color: #111827;
    color: #fff;
}

/* Main nav links */
.nav-main a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    text-decoration: none;
    color: #fff;
    border-radius: 5px;
    margin-bottom: 8px;
    transition: background 0.3s;
}

.nav-main a.active, .nav-main a:hover {
    background-color: #3b82f6;
}


/* Logout fixed at bottom */
.logout-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    text-decoration: none;
    color: #fff;
    background-color: #ef4444; /* red */
    border-radius: 5px;
    font-weight: 500;
    position: absolute;
    bottom: 20px; /* distance from bottom of screen */
    width: calc(100% - 60px); /* full width minus sidebar padding */
    left: 20px;
    transition: background 0.3s;
}

.logout-btn:hover {
    background-color: #b91c1c;
}

</style>


<!-- Include FontAwesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
