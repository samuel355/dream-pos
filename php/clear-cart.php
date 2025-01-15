<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function clearCart($conn)
{
  $session_id = session_id();

  $query = "DELETE FROM cart_items WHERE session_id = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "s", $session_id);

  if (mysqli_stmt_execute($stmt)) {
    return [
      'status' => 'success',
      'message' => 'Cart cleared successfully'
    ];
  } else {
    return [
      'status' => 'error',
      'message' => 'Failed to clear cart'
    ];
  }
}

echo json_encode(clearCart($conn));
