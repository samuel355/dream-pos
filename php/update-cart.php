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

    // First, get the category_id of the product in cart
    $check_category_query = "SELECT p.category_id 
                           FROM cart_items ci 
                           JOIN products p ON ci.product_id = p.id 
                           WHERE ci.id = ? AND ci.session_id = ?";
    
    $stmt = mysqli_prepare($conn, $check_category_query);
    mysqli_stmt_bind_param($stmt, "is", $cart_id, $session_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Check if product is from restricted categories (1, 3, or 5)
        if (in_array($row['category_id'], [1, 3, 5])) {
            return [
                'status' => 'info',
                'message' => 'Please finish with this order and make another one'
            ];
        }
    }

    // If not a restricted category, proceed with update
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
