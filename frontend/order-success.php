<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ecommerce</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php 
        include '../includes/header.php';
    ?>

    <div style="
        height:100vh;
        display:flex;
        justify-content:center;
        align-items:center;
        background:#eafaf1;
    ">
        <div style="
            padding:40px 60px;
            background:#ffffff;
            border-left:6px solid #27ae60;
            border-radius:10px;
            box-shadow:0 4px 15px rgba(0,0,0,0.08);
            text-align:center;
        ">
            <h2 style="color:#27ae60; margin-bottom:15px; font-size:28px;">
                ✔ Order Created Successfully!
            </h2>
            <p style="color:#2c3e50; margin:0; font-size:16px;">
                Thank you for your purchase. Your order has been placed successfully.
            </p>
        </div>
    </div>


    <?php include '../includes/footer.php'; ?>
</body>
</html>
