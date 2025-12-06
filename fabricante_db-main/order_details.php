<?php
require 'database.php';

if (!isset($_GET['id'])) {
    die("Order ID is required.");
}

$order_id = intval($_GET['id']);

// Fetch order + customer info
$orderSql = "SELECT o.*, c.name AS customer_name, c.email, c.phone
             FROM orders o
             JOIN customers c ON o.customer_id = c.id
             WHERE o.id = $order_id";

$order = $mysqli->query($orderSql)->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// Fetch order items
$itemsSql = "SELECT 
                oi.quantity,
                p.name AS product_name,
                p.price
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = $order_id";

$items = $mysqli->query($itemsSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .page {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .btn {
            background: #2979ff;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
        }

        .btn:hover {
            background: #1f5ec8;
        }

        .summary {
            background: #f7f9fc;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid #e3e7ef;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background: #eef2f7;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-top: 1px solid #eee;
        }

        .total-box {
            margin-top: 20px;
            padding: 15px;
            font-size: 20px;
            background: #f1f5ff;
            border-radius: 10px;
            border: 1px solid #dce6ff;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="page">

    <h2 class="title">Order Details</h2>

    <a href="view_orders.php" class="btn">← Back to Orders</a>

    <div class="summary">
        <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
        <p><strong>Customer:</strong> <?php echo $order['customer_name']; ?></p>
        <p><strong>Email:</str
