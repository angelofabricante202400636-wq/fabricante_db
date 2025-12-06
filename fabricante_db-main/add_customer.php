<?php
require 'database.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    if ($name && $email) {
        $stmt = $mysqli->prepare("INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $phone);
        $stmt->execute();
        $stmt->close();
        $msg = 'Customer added.';
    } else {
        $msg = 'Name and email are required.';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Customer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Add Customer</h1>
    <div class="nav"><a class="button" href="index.php">Back to Products</a> <a class="button" href="view_orders.php">View Orders</a></div>
    <?php if ($msg): ?><div class="notice"><?php echo e($msg); ?></div><?php endif; ?>
    <form method="post">
        <div class="form-row"><label>Name</label><input type="text" name="name" required></div>
        <div class="form-row"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-row"><label>Phone</label><input type="text" name="phone"></div>
        <button class="button" type="submit">Add Customer</button>
    </form>
</div>
</body>
</html>
