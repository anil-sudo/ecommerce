<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "e-commerce");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch registered users
$sql = "SELECT username, email, created_at 
        FROM register_user 
        ORDER BY created_at DESC";
$result = $conn->query($sql);
if(!$result) die("Query Failed: " . $conn->error);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Users</title>
    
    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', sans-serif; }
        body{ display:flex; min-height:100vh; background:#f4f7fc; overflow-x:hidden; }

        /* ---------------- Sidebar ---------------- */
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
        .sidebar nav { flex:1; display:flex; flex-direction:column; }
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

        /* ---------------- Main Content ---------------- */
        .main-content{
            margin-left:250px; 
            padding:30px;
            flex:1;
            transition: margin-left 0.3s;
        }
        .sidebar.collapsed ~ .main-content { margin-left:70px; }

        h2{ margin-bottom:20px; color:#111827; }

        .card{
            background:white;
            padding:20px;
            border-radius:12px;
            box-shadow:0 4px 15px rgba(0,0,0,0.1);
            width:100%;
            overflow-x:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
            min-width:600px;
        }

        th{
            background:#3b82f6;
            color:white;
            padding:12px;
        }

        td{
            padding:12px;
            text-align:center;
            border-bottom:1px solid #eee;
        }

        tr:hover{
            background:#f1f5f9;
            transition:0.3s;
        }

        .badge{
            background:#10b981;
            color:white;
            padding:5px 10px;
            border-radius:20px;
            font-size:12px;
        }

        /* Responsive */
        @media(max-width:768px){
            .main-content{ margin-left:70px; padding:20px; }
            .sidebar{ width:70px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include 'aside.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Registered Users</h2>
        <div class="card">
            <table>
                <tr>
                    <th>S.N</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Joined On</th>
                    <th>Status</th>
                </tr>

                <?php 
                $sn = 1;
                while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                        <td><span class="badge">Active</span></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <script>
        // Toggle sidebar collapse
        const sidebar = document.getElementById('sidebar');
        const collapseBtn = sidebar.querySelector('.collapse-btn');
        collapseBtn.addEventListener('click', ()=>{
            sidebar.classList.toggle('collapsed');
        });
    </script>

</body>
</html>