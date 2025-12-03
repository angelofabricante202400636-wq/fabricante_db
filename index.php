<?php
require 'database.php';
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Products - Index</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h1>Products</h1>
<div class="nav">
<a class="button" href="manage_categories.php">Manage Categories</a>
<a class="button" href="add_product.php">Add Product</a>
<a class="button" href="add_customer.php">Add Customer</a>
<a class="button" href="create_order.php">Create Order</a>
<a class="button" href="view_orders.php">View Orders</a>
</div>


<?php

$sql = "SELECT p.id, p.name AS product_name, p.price, c.name AS category_name
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
ORDER BY p.id DESC";
$res = $mysqli->query($sql);
?>


<table class="table">
<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>Category</th>
<th>Price</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php if ($res && $res->num_rows): ?>
<?php while ($row = $res->fetch_assoc()): ?>
<tr>
<td><?php echo e($row['id']); ?></td>
<td><?php echo e($row['product_name']); ?></td>
<td><?php echo e($row['category_name'] ?? '—'); ?></td>
<td><?php echo number_format($row['price'],2); ?></td>
<td class="actions">
<a class="button" href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a>
<a class="button danger" href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?');">Delete</a>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="5">No products yet.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</body>
</html>