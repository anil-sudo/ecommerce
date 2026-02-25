<?php
session_start();
include "../database/dbconnection.php";

/* Session Protection */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_SESSION['role'])) {
11



    $model = 'register_user';
} else {
    $model = ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'editor') ? 'register_user' : 'users';
}
    
$user_id = $_SESSION['user_id'];

/* Fetch user info */
if ($model === 'users') {
    $stmt = $conn->prepare("SELECT username, email FROM admins WHERE id = ?");
} else {
    $stmt = $conn->prepare("SELECT username, email, phone FROM register_user WHERE id = ?");
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile | E-Commerce</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:"Segoe UI",sans-serif;}
body{background:#f0f2f5;color:#111827;transition:.3s;}


.container{
    max-width:500px;
    margin:60px auto;
    background:#fff;
    border-radius:15px;
    box-shadow:0 15px 35px rgba(0,0,0,.2);
    overflow:hidden;
    text-align:center;
    padding-bottom:30px;
}


.header{
    background:#2874f0;
    color:#fff;
    padding:50px 20px 40px;
    border-bottom-left-radius:15px;
    border-bottom-right-radius:15px;
}


.avatar{
    width:120px;
    height:120px;
    background:#2874f0;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:50px;
    color:#fff;
    margin:0 auto 20px; /* Adjusted margin */
    box-shadow:0 4px 20px rgba(0,0,0,.2);
    transition:.3s;
}


.header h2{margin:10px 0 5px;}
.header p{opacity:.9;font-size:16px;}

.badge{
    display:inline-block;
    margin-top:10px;
    padding:5px 15px;
    background:#16a34a;
    color:#fff;
    border-radius:20px;
    font-size:13px;
}

.info-box{
    margin:25px 20px 0;
    text-align:left;
}
.info-box label{font-size:12px;color:#777;display:block;margin-bottom:3px;}
body.dark-mode .info-box label{color:#cbd5e1;}
.info-box p{font-size:16px;font-weight:500;}


.card{
    margin:20px;
    padding:15px;
    background:#f9fafb;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,.05);
    transition:.3s;
}


.card:hover{
    transform:translateY(-5px);
}

.actions{
    display:flex;
    justify-content:space-around;
    margin-top:30px;
}

button{
    padding:12px 25px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:500;
    transition:.2s;
}
.logout-btn{background:#ef4444;color:#fff;}
.logout-btn:hover{background:#b91c1c;}
.theme-btn{background:#2874f0;color:#fff;}
.theme-btn:hover{background:#1d4ed8;}

a.card {
    display: block; 
    color: inherit; 
    text-decoration: none;
}

</style>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <!-- Header -->
    <div class="header">
        <div class="avatar"><?= strtoupper(substr($user['username'],0,1)) ?></div>
        <h2 style="color:black;"><?= htmlspecialchars($user['username']) ?></h2>
        <p style="color:black;"><?= htmlspecialchars($user['email']) ?></p>
        <?php if (isset($user['phone'])): ?>
            <p style="color:black;"><?= htmlspecialchars($user['phone']) ?></p>
        <?php endif; ?>
        <div class="badge">Online</div>
    </div>

    <!-- Info Cards -->
    <div class="card info-box">
        <label>Last Login</label>
        <p>Today</p>
    </div>

    <div class="card info-box">
        <label>Account Status</label>
        <p>Active</p>
    </div>

    <a href="../frontend/orders.php" style="text-decoration: none;" class="card info-box">
        <label>order History</label>
        <p>View orders</p>
    </a>


    <!-- Actions -->
    <div class="actions">
     
        <a href="../database/logout.php"><button class="logout-btn">Logout</button></a>
    </div>

</div>
<?php
include '../includes/footer.php';
?>



</body>
</html>
