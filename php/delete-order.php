<?php
session_start();
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

  // Check if order exists before deletion
  $checkStmt = mysqli_prepare($conn, "SELECT id FROM orders WHERE id = ?");
  mysqli_stmt_bind_param($checkStmt, "i", $orderId);
  mysqli_stmt_execute($checkStmt);
  mysqli_stmt_store_result($checkStmt);

  if (mysqli_stmt_num_rows($checkStmt) === 0) {
    throw new Exception('Order not found');
  }
  mysqli_stmt_close($checkStmt);

  // Delete in the correct order to respect foreign key constraints

  // 1. First delete from order_history
  $stmt = mysqli_prepare($conn, "DELETE FROM order_history WHERE order_id = ?");
  mysqli_stmt_bind_param($stmt, "i", $orderId);
  if (!mysqli_stmt_execute($stmt)) {
    throw new Exception('Error deleting order history: ' . mysqli_error($conn));
  }
  mysqli_stmt_close($stmt);

  // 2. Delete order items
  $stmt = mysqli_prepare($conn, "DELETE FROM order_items WHERE order_id = ?");
  mysqli_stmt_bind_param($stmt, "i", $orderId);
  if (!mysqli_stmt_execute($stmt)) {
    throw new Exception('Error deleting order items: ' . mysqli_error($conn));
  }
  mysqli_stmt_close($stmt);

  // 3. Finally delete the main order
  $stmt = mysqli_prepare($conn, "DELETE FROM orders WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $orderId);
  if (!mysqli_stmt_execute($stmt)) {
    throw new Exception('Error deleting order: ' . mysqli_error($conn));
  }
  mysqli_stmt_close($stmt);

  // Commit transaction
  mysqli_commit($conn);

  // Log the deletion if needed
  error_log("Order #$orderId deleted successfully");

  sendResponse('success', 'Order deleted successfully');
} catch (Exception $e) {
  // Rollback transaction on error
  if (mysqli_connect_errno() === 0) {
    mysqli_rollback($conn);
  }

  error_log("Order deletion error: " . $e->getMessage());
  sendResponse('error', 'Error deleting order: ' . $e->getMessage());
} finally {
  if (isset($stmt)) {
    mysqli_stmt_close($stmt);
  }
  mysqli_close($conn);
}
