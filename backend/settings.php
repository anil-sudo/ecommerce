<?php
session_start();
include 'session_check.php';
include '../database/dbconnection.php';

/* ===============================
   VERIFY LOGIN SESSION
================================ */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/* ===============================
   FETCH ADMIN INFO
================================ */
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$admin = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings | Admin Panel</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif}
body{background:#f4f6f8;color:#111827;transition:.3s}
body.dark-mode{background:#111827;color:#f4f6f8}
body.dark-mode .sidebar{background:#111827}
body.dark-mode .form-section{background:#1f2937}
body.dark-mode input{background:#1f2937;color:#f4f6f8;border:1px solid #444}

.main{margin-left:250px;padding:20px}
h1{margin-bottom:20px}

.form-section{
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 6px rgba(0,0,0,.1);
    margin-bottom:20px;
}
.form-section h2{color:#3b82f6;margin-bottom:15px}

.form-group{margin-bottom:15px}
.form-group label{display:block;font-weight:bold;margin-bottom:5px}
.form-group input{
    width:100%;
    padding:10px;
    border-radius:5px;
    border:1px solid #ccc;
}

.btn-logout{
    background:#ef4444;
    padding:10px 15px;
    border:none;
    border-radius:5px;
    color:#fff;
    cursor:pointer;
}
.btn-logout:hover{background:#b91c1c}
</style>
</head>

<body>

<?php include 'aside.php'; ?>

<div class="main">
<h1>Settings</h1>

<!-- Profile -->
<div class="form-section">
    <h2>Profile Settings</h2>

    <div class="form-group">
        <label>Admin Name</label>
        <input type="text" value="<?= htmlspecialchars($admin['username'] ?? '') ?>" disabled>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" disabled>
    </div>
</div>

<!-- Theme -->
<div class="form-section">
    <h2>Theme Preferences</h2>
    <label>
        <input type="checkbox" id="darkModeToggle">
        Enable Dark Mode
    </label>
</div>

<!-- Logout -->
<div class="form-section">
    <a href="logout.php">
        <button class="btn-logout">Logout</button>
    </a>
</div>

</div>

<script>
const toggle = document.getElementById('darkModeToggle');

if (localStorage.getItem('darkMode') === 'enabled') {
    document.body.classList.add('dark-mode');
    toggle.checked = true;
}

toggle.addEventListener('change', () => {
    if (toggle.checked) {
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkMode','enabled');
    } else {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode','disabled');
    }
});
</script>

</body>
</html>
