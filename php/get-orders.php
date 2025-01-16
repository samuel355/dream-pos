<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

try {
  $query = "SELECT o.*, 
            GROUP_CONCAT(oi.quantity, 'x ', p.name) as items
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN products p ON oi.product_id = p.id
            GROUP BY o.id
            ORDER BY o.created_at DESC";

  $result = $conn->query($query);
  $orders = [];

  while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
  }

  echo json_encode([
    'status' => 'success',
    'orders' => $orders
  ]);
} catch (Exception $e) {
  echo json_encode([
    'status' => 'error',
    'message' => $e->getMessage()
  ]);
}
