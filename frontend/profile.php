<?php
session_start();
include "../database/dbconnection.php";

/* Session Protection */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Fetch user info */
$stmt = $conn->prepare("SELECT username, email FROM register_user WHERE id = ?");
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
body.dark-mode{background:#111827;color:#f4f4f4;}

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
body.dark-mode .container{background:#1f2937;}

.header{
    background:#2874f0;
    color:#fff;
    padding:50px 20px 40px;
    border-bottom-left-radius:15px;
    border-bottom-right-radius:15px;
}
body.dark-mode .header{background:#2563eb;}

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
body.dark-mode .avatar{background:#2563eb;}

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
body.dark-mode .info-box p{color:#f4f4f4;}

.card{
    margin:20px;
    padding:15px;
    background:#f9fafb;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,.05);
    transition:.3s;
}
body.dark-mode .card{background:#111827;}

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

.dark-toggle{
    margin-top:15px;
}
.dark-toggle input{margin-left:10px;width:18px;height:18px;cursor:pointer;}
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

    <!-- Dark Mode Toggle -->
    <div class="dark-toggle">
        <label>Dark Mode
            <input type="checkbox" id="darkToggle">
        </label>
    </div>

    <!-- Actions -->
    <div class="actions">
        <button class="theme-btn" onclick="changeColor()">Change Theme</button>
        <a href="../auth/logout.php"><button class="logout-btn">Logout</button></a>
    </div>

</div>
<?php
include '../includes/footer.php';
?>

<script>
// Dark Mode
const toggle = document.getElementById('darkToggle');
if(localStorage.getItem('darkMode') === 'enabled'){
    document.body.classList.add('dark-mode');
    toggle.checked = true;
}
toggle.addEventListener('change', ()=>{
    if(toggle.checked){
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkMode','enabled');
    } else{
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode','disabled');
    }
});

// Change Avatar Color (Frontend only)
function changeColor(){
    const colors = ['#2874f0','#16a34a','#7c3aed','#ea580c'];
    document.querySelector('.avatar').style.background = colors[Math.floor(Math.random()*colors.length)];
}
</script>

</body>
</html>
