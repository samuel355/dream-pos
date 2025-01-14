<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function updateCartItem($conn, $cart_id, $quantity)
{
  $session_id = session_id();

  // Validate quantity
  if ($quantity < 1) {
    sendResponse('error', 'Quantity cannot be less than 1');
  }

  $query = "UPDATE cart_items 
            SET quantity = ? 
            WHERE id = ? AND session_id = ?";

  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "iis", $quantity, $cart_id, $session_id);

  if (mysqli_stmt_execute($stmt)) {
    return [
      'status' => 'success',
      'message' => 'Cart updated successfully'
    ];
  } else {
    return [
      'status' => 'error',
      'message' => 'Failed to update cart'
    ];
  }
}

$cart_id = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : null;

if (!$cart_id || !$quantity) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Invalid parameters'
  ]);
  exit;
}

echo json_encode(updateCartItem($conn, $cart_id, $quantity));
