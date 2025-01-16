<?php

include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

try {
  // Get POST data
  $data = json_decode(file_get_contents('php://input'), true);
  $orderId = isset($data['order_id']) ? intval($data['order_id']) : 0;

  if ($orderId <= 0) {
    sendResponse('error', 'Invalid order ID');
    exit;
  }

  // Start transaction
  mysqli_begin_transaction($conn);

  // Delete in the correct order to respect foreign key constraints

  // 1. First delete from order_history
  $stmt = mysqli_prepare($conn, "DELETE FROM order_history WHERE order_id = ?");
  mysqli_stmt_bind_param($stmt, "i", $orderId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  // 2. Delete order items
  $stmt = mysqli_prepare($conn, "DELETE FROM order_items WHERE order_id = ?");
  mysqli_stmt_bind_param($stmt, "i", $orderId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  // 3. Finally delete the main order
  $stmt = mysqli_prepare($conn, "DELETE FROM orders WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $orderId);
  mysqli_stmt_execute($stmt);

  // Check if order was actually deleted
  if (mysqli_stmt_affected_rows($stmt) === 0) {
    throw new Exception('Order not found');
  }
  mysqli_stmt_close($stmt);

  // Commit transaction
  mysqli_commit($conn);

  sendResponse('success', 'Order deleted successfully');
} catch (Exception $e) {
  // Rollback transaction on error
  mysqli_rollback($conn);
  sendResponse('error', 'Error deleting order: ' . $e->getMessage());
} finally {
  // Close connection
  mysqli_close($conn);
}
