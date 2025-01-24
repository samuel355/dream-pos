<?php

include '../includes/db_connection.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

try {
  $query = "SELECT n.*, o.customer_name, o.total_amount, GROUP_CONCAT(oi.quantity, 'x ',  p.name SEPARATOR ', ') as items 
  FROM notifications n
  LEFT JOIN orders o ON n.order_id = o.id
  LEFT JOIN order_items oi ON n.order_id = oi.order_id
  LEFT JOIN products p ON oi.product_id = p.id
  WHERE n.is_read = 0
  GROUP BY o.id
  ORDER BY n.created_at DESC";


  $result = mysqli_query($conn, $query);

  if (!$result) {
    throw new Exception("Error fetching notifications: " . mysqli_error($conn));
  }

  $notifications = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
  }

  sendResponse('success', 'Notifications fetched successfully', ['notifications' => $notifications]);
} catch (Exception $e) {
  sendResponse('error', $e->getMessage());
} finally {
  mysqli_close($conn);
}
