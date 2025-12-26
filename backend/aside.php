<aside class="sidebar" id="sidebar">
    <h2><i class="fa-solid fa-cubes"></i> ShopEase</h2>
    <nav>
        <a href="admin.php" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="users.php"><i class="fa-solid fa-users"></i> Users</a>
        <a href="settings.php"><i class="fa-solid fa-gear"></i> Settings</a>
    </nav>
    <button class="collapse-btn" onclick="toggleSidebar()"><i class="fa-solid fa-angle-left"></i></button>
</aside>

<style>
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

/* Collapse Button */
.collapse-btn {
    position:absolute; bottom:20px; left:50%; transform:translateX(-50%);
    background:#2563eb; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;
    transition:0.3s;
}
.collapse-btn:hover { background:#3b82f6; }
</style>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
}
</script>

<!-- Include FontAwesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
