<?php
require 'database.php';
// list orders with customer name and total items
$sql = "SELECT o.id, o.order_date, c.name AS customer_name,
        COALESCE(SUM(oi.quantity),0) AS total_items
        FROM orders o
        JOIN customers c ON o.customer_id = c.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        GROUP BY o.id
        ORDER BY o.id DESC";
$res = $mysqli->query($sql);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Orders</h1>
    <div class="nav"><a class="button" href="index.php">Back to Products</a> <a class="button" href="create_order.php">Create Order</a></div>

    <table class="table">
        <thead><tr><th>#</th><th>Customer</th><th>Items</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if ($res && $res->num_rows): while($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo e($row['id']); ?></td>
                <td><?php echo e($row['customer_name']); ?></td>
                <td><?php echo e($row['total_items']); ?></td>
                <td><?php echo e($row['order_date']); ?></td>
                <td><a class="button" href="order_details.php?id=<?php echo $row['id']; ?>">View</a></td>
            </tr>
        <?php endwhile; else: ?>
            <tr><td colspan="5">No orders yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
