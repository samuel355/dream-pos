<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function deleteCartItem($conn, $cart_id)
{
  $session_id = session_id();

  $query = "DELETE FROM cart_items 
            WHERE id = ? AND session_id = ?";

  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "is", $cart_id, $session_id);

  if (mysqli_stmt_execute($stmt)) {
    return [
      'status' => 'success',
      'message' => 'Item removed from cart'
    ];
  } else {
    return [
      'status' => 'error',
      'message' => 'Failed to remove item'
    ];
  }
}

$cart_id = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : null;

if (!$cart_id) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Invalid cart item ID'
  ]);
  exit;
}

echo json_encode(deleteCartItem($conn, $cart_id));
