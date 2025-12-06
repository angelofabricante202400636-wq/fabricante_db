<?php
require 'database.php';
$msg = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header('Location: index.php'); exit; }

// fetch product
$stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();
$stmt->close();

// categories
$cats = $mysqli->query("SELECT * FROM categories ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $stmt = $mysqli->prepare("UPDATE products SET name = ?, price = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param('sdii', $name, $price, $category_id, $id);
    $stmt->execute();
    $stmt->close();
    $msg = 'Product updated.';
    // refresh product
    $stmt2 = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $product = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Product</h1>
    <div class="nav"><a class="button" href="index.php">Back to Products</a></div>
    <?php if ($msg): ?><div class="notice"><?php echo e($msg); ?></div><?php endif; ?>

    <form method="post">
        <div class="form-row">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo e($product['name']); ?>" required>
        </div>
        <div class="form-row">
            <label>Price</label>
            <input type="text" name="price" value="<?php echo e($product['price']); ?>" required>
        </div>
        <div class="form-row">
            <label>Category</label>
            <select name="category_id" required>
                <option value="">-- select --</option>
                <?php if ($cats && $cats->num_rows): while($c = $cats->fetch_assoc()): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo ($product['category_id']==$c['id'])?'selected':''; ?>><?php echo e($c['name']); ?></option>
                <?php endwhile; endif; ?>
            </select>
        </div>
        <button class="button" type="submit">Save Changes</button>
    </form>
</div>
</body>
</html>
