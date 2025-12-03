<?php
require 'database.php';
$msg = '';
// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $stmt = $mysqli->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->close();
        $msg = 'Category added.';
    } else {
        $msg = 'Name required.';
    }
}

// Handle edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    if ($id && $name !== '') {
        $stmt = $mysqli->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param('si', $name, $id);
        $stmt->execute();
        $stmt->close();
        $msg = 'Category updated.';
    }
}

// Handle delete via GET
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id) {
        $stmt = $mysqli->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        header('Location: manage_categories.php');
        exit;
    }
}

// Fetch categories
$res = $mysqli->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Manage Categories</h1>
    <div class="nav"><a class="button" href="index.php">Back to Products</a></div>
    <?php if ($msg): ?>
        <div class="notice"><?php echo e($msg); ?></div>
    <?php endif; ?>

    <h2>Add Category</h2>
    <form method="post">
        <input type="hidden" name="action" value="add">
        <div class="form-row">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>
        <button class="button" type="submit">Add</button>
    </form>

    <h2>Existing Categories</h2>
    <table class="table">
        <thead><tr><th>#</th><th>Name</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if ($res && $res->num_rows): ?>
            <?php while ($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo e($row['id']); ?></td>
                <td><?php echo e($row['name']); ?></td>
                <td>
                    <a class="button" href="#" onclick="document.getElementById('edit-<?php echo $row['id']; ?>').style.display='block';return false;">Edit</a>
                    <a class="button danger" href="manage_categories.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete category?');">Delete</a>

                    <div id="edit-<?php echo $row['id']; ?>" style="display:none; margin-top:8px;">
                        <form method="post">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <div class="form-row">
                                <label>Edit name</label>
                                <input type="text" name="name" value="<?php echo e($row['name']); ?>" required>
                            </div>
                            <button class="button" type="submit">Save</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">No categories yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>