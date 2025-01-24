<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');


if(!isAdmin() || !isSysAdmin()){
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}

$session_id = session_id();

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  exit;
}

// Check if action is set
if (!isset($_POST['action']) || $_POST['action'] !== 'update_size') {
  echo json_encode(['success' => false, 'message' => 'Invalid action']);
  exit;
}

// Get and validate inputs
$cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
$new_size = isset($_POST['new_size']) ? mysqli_real_escape_string($conn, $_POST['new_size']) : '';

// Validate inputs
if ($cart_id <= 0 || empty($new_size)) {
  echo json_encode(['success' => false, 'message' => 'Invalid input parameters']);
  sendResponse('error', 'Something went wrong updating id, size');
}

// Valid sizes
$valid_sizes = ['Medium', 'Large'];
if (!in_array($new_size, $valid_sizes)) {
  sendResponse('error', 'Invalid Size Selected');
  exit;
}

// Fetch the price for the selected size
$stmt_price = $conn->prepare("SELECT price FROM product_pricing WHERE size_name = ?");
$stmt_price->bind_param("s", $new_size);
if (!$stmt_price->execute()) {
  sendResponse('error', 'Invalid Price in db');
  exit;
}

$result_price = $stmt_price->get_result();
if ($result_price->num_rows > 0) {
  $new_price = number_format((float)$result_price->fetch_assoc()['price'], 2, '.', '');

  // Update cart item with the new size and price
  $stmt_update = $conn->prepare("UPDATE cart_items SET size = ?, price = ? WHERE id = ? AND session_id = ?");
  $stmt_update->bind_param("ssis", $new_size, $new_price, $cart_id, $session_id);

  if ($stmt_update->execute()) {
    sendResponse('success', 'Size and price updated.');
  } else {
    sendResponse('error', 'Error changing size');
  }
} else {
  sendResponse('error', 'Price not found for selected size.');
}

$conn->close();
