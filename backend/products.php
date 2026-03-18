<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products | Admin Panel</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family: 'Arial', sans-serif; }
    body { background: #f4f6f8; }

    /* Sidebar */
    .sidebar {
        width: 250px; height: 100vh; background: #1f2937; color: #fff;
        position: fixed; top: 0; left: 0; display: flex; flex-direction: column;
    }
    .sidebar h2 { text-align:center; margin:20px 0; font-size:1.5em; }
    .sidebar a {
        color:#fff; padding:15px 20px; text-decoration:none; display:block;
        transition:0.3s; border-left:4px solid transparent;
    }
    .sidebar a:hover { background:#111827; border-left:4px solid #3b82f6; }

    /* Main Content */
    .main { margin-left:250px; padding:20px; }
    .main h1 { margin-bottom:20px; color:#111827; }

    /* Top controls */
    .top-controls { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; }
    .top-controls input[type="text"] {
        padding:8px 12px; width:250px; border-radius:5px; border:1px solid #ccc;
    }
    .btn { padding:8px 12px; border:none; border-radius:5px; cursor:pointer; color:#fff; transition:0.3s; }
    .btn-add { background:#10b981; }
    .btn-add:hover { background:#059669; }
    .btn-edit { background:#3b82f6; }
    .btn-edit:hover { background:#2563eb; }
    .btn-delete { background:#ef4444; }
    .btn-delete:hover { background:#b91c1c; }

    /* Products Table */
    table { width:100%; border-collapse: collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 4px 6px rgba(0,0,0,0.1); }
    th, td { padding:15px; text-align:left; vertical-align: middle; }
    th { background:#3b82f6; color:#fff; }
    tr:nth-child(even) { background:#f9fafb; }
    tr:hover { background:#e0f2fe; }
    img { width:60px; height:60px; object-fit:cover; border-radius:5px; }

    @media(max-width:768px){
        .sidebar { width:100%; height:auto; position:relative; }
        .main { margin-left:0; }
        .top-controls { flex-direction:column; gap:10px; }
    }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="#">Dashboard</a>
    <a href="#">Products</a>
    <a href="#">Orders</a>
    <a href="#">Users</a>
    <a href="#">Settings</a>
</div>

<div class="main">
    <h1>Products</h1>

    <div class="top-controls">
        <input type="text" placeholder="Search products...">
        <button class="btn btn-add">+ Add Product</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><img src="https://via.placeholder.com/60" alt="Product Image"></td>
                <td>Smart Watch</td>
                <td>$99.99</td>
                <td>50</td>
                <td>
                    <button class="btn btn-edit">Edit</button>
                    <button class="btn btn-delete">Delete</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td><img src="https://via.placeholder.com/60" alt="Product Image"></td>
                <td>Bluetooth Headphones</td>
                <td>$59.99</td>
                <td>100</td>
                <td>
                    <button class="btn btn-edit">Edit</button>
                    <button class="btn btn-delete">Delete</button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td><img src="https://via.placeholder.com/60" alt="Product Image"></td>
                <td>Laptop Bag</td>
                <td>$39.99</td>
                <td>30</td>
                <td>
                    <button class="btn btn-edit">Edit</button>
                    <button class="btn btn-delete">Delete</button>
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td><img src="https://via.placeholder.com/60" alt="Product Image"></td>
                <td>Gaming Mouse</td>
                <td>$49.99</td>
                <td>75</td>
                <td>
                    <button class="btn btn-edit">Edit</button>
                    <button class="btn btn-delete">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>
