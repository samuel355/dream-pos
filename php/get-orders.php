<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');

try {
  $query = "SELECT o.*, 
              GROUP_CONCAT(
                  CONCAT(oi.quantity, 'x ', p.name)
                  SEPARATOR ', '
              ) as items
              FROM orders o
              LEFT JOIN order_items oi ON o.id = oi.order_id
              LEFT JOIN products p ON oi.product_id = p.id
              GROUP BY o.id
              ORDER BY o.created_at DESC";

  $result = mysqli_query($conn, $query);

  if (!$result) {
    throw new Exception("Database error: " . mysqli_error($conn));
  }

  $orders = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
  }

  sendResponse('success', 'Orders fetched successfully', ['orders' => $orders]);
} catch (Exception $e) {
  sendResponse('error', $e->getMessage());
} finally {
  mysqli_close($conn);
}
