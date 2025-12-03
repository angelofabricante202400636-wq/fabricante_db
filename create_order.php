<?php
require 'database.php';
$msg = '';
$customers = $mysqli->query("SELECT * FROM customers ORDER BY name");
$products = $mysqli->query("SELECT * FROM products ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = (int)($_POST['customer_id'] ?? 0);
    $items = $_POST['items'] ?? []; // items[product_id] => qty

    if ($customer_id && count($items)) {
        // create order
        $stmt = $mysqli->prepare("INSERT INTO orders (customer_id) VALUES (?)");
        $stmt->bind_param('i', $customer_id);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // insert items
        $stmt2 = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        foreach ($items as $product_id => $qty) {
            $product_id = (int)$product_id;
            $qty = (int)$qty;
            if ($product_id && $qty > 0) {
                $stmt2->bind_param('iii', $order_id, $product_id, $qty);
                $stmt2->execute();
            }
        }
        $stmt2->close();

        $msg = 'Order created. Order ID: ' . $order_id;
    } else {
        $msg = 'Choose a customer and at least one product quantity.';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Create Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Create Order</h1>
    <div class="nav"><a class="button" href="index.php">Back to Products</a> <a class="button" href="view_orders.php">View Orders</a></div>
    <?php if ($msg): ?><div class="notice"><?php echo e($msg); ?></div><?php endif; ?>

    <form method="post">
        <div class="form-row">
            <label>Customer</label>
            <select name="customer_id" required>
                <option value="">-- select customer --</option>
                <?php if ($customers && $customers->num_rows): while($c = $customers->fetch_assoc()): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo e($c['name']); ?> (<?php echo e($c['email']); ?>)</option>
                <?php endwhile; endif; ?>
            </select>
        </div>

        <h3>Products</h3>
        <table class="table">
            <thead><tr><th>Product</th><th>Price</th><th>Quantity</th></tr></thead>
            <tbody>
            <?php if ($products && $products->num_rows): while($p = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo e($p['name']); ?></td>
                    <td><?php echo number_format($p['price'],2); ?></td>
                    <td><input type="number" min="0" name="items[<?php echo $p['id']; ?>]" value="0" style="width:80px;"></td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="3">No products available.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <button class="button" type="submit">Create Order</button>
    </form>
</div>
</body>
</html>
