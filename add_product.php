<?php
require 'database.php';
$msg = '';

$cats = $mysqli->query("SELECT * FROM categories ORDER BY name");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = trim($_POST['name']);
$price = (float)$_POST['price'];
$category_id = (int)$_POST['category_id'];
if ($name !== '') {
$stmt = $mysqli->prepare("INSERT INTO products (name, price, category_id) VALUES (?, ?, ?)");
$stmt->bind_param('sdi', $name, $price, $category_id);
$stmt->execute();
$stmt->close();
$msg = 'Product added.';
}
}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Product</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h1>Add Product</h1>
<div class="nav"><a class="button" href="index.php">Back to Products</a></div>
<?php if ($msg): ?><div class="notice"><?php echo e($msg); ?></div><?php endif; ?>
<form method="post">
<div class="form-row">
<label>Name</label>
<input type="text" name="name" required>
</div>
<div class="form-row">
<label>Price</label>
<input type="text" name="price" required>
</div>
<div class="form-row">
<label>Category</label>
<select name="category_id" required>
<option value="">-- select --</option>
<?php if ($cats && $cats->num_rows): while($c = $cats->fetch_assoc()): ?>
<option value="<?php echo $c['id']; ?>"><?php echo e($c['name']); ?></option>
<?php endwhile; endif; ?>
</select>
</div>
<button class="button" type="submit">Add Product</button>
</form>
</div>
</body>
</html>