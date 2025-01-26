<?php
session_start();
include '../includes/db_connection.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

try {
  // Validate request method
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Invalid request method');
  }

  // Validate and sanitize input
  if (
    !isset($_POST['action']) || $_POST['action'] !== 'update_size' ||
    !isset($_POST['cart_id']) || !is_numeric($_POST['cart_id']) ||
    !isset($_POST['new_size']) || empty($_POST['new_size'])
  ) {
    throw new Exception('Invalid input parameters');
  }

  $cartId = intval($_POST['cart_id']);
  $newSize = mysqli_real_escape_string($conn, trim($_POST['new_size']));
  $session_id = session_id();

  // Validate sizes against db values
  $validSizes = [];
  $size_query = "SELECT size_name FROM product_pricing GROUP BY size_name";
  $size_result = mysqli_query($conn, $size_query);

  while ($row = mysqli_fetch_assoc($size_result)) {
    $validSizes[] = $row['size_name'];
  }

  if (!in_array($newSize, $validSizes)) {
    throw new Exception('Invalid size selected');
  }

  // Fetch the price for the selected size
  $priceQuery = "SELECT price FROM product_pricing WHERE size_name = ?";
  $stmtPrice = mysqli_prepare($conn, $priceQuery);
  if (!$stmtPrice) {
    throw new Exception('Failed to prepare price query');
  }
  mysqli_stmt_bind_param($stmtPrice, "s", $newSize);
  if (!mysqli_stmt_execute($stmtPrice)) {
    throw new Exception('Failed to execute price query');
  }
  $priceResult = mysqli_stmt_get_result($stmtPrice);
  $priceData = mysqli_fetch_assoc($priceResult);

  if (!$priceData) {
    throw new Exception('Price not found for the selected size');
  }

  $newPrice = (float)$priceData['price'];

  // Update cart item with new size and price
  $updateQuery = "UPDATE cart_items SET size = ?, price = ? WHERE id = ? AND session_id = ?";
  $stmtUpdate = mysqli_prepare($conn, $updateQuery);
  if (!$stmtUpdate) {
    throw new Exception('Failed to prepare update query: ' . mysqli_error($conn));
  }

  mysqli_stmt_bind_param($stmtUpdate, "sdis", $newSize, $newPrice, $cartId, $session_id);

  if (!mysqli_stmt_execute($stmtUpdate)) {
    throw new Exception('Failed to update cart item: ' . mysqli_stmt_error($stmtUpdate));
  }

  sendResponse('success', 'Size and price updated successfully');
} catch (Exception $e) {
  sendResponse('error', $e->getMessage());
} finally {
  if (isset($stmtPrice)) mysqli_stmt_close($stmtPrice);
  if (isset($stmtUpdate)) mysqli_stmt_close($stmtUpdate);
  mysqli_close($conn);
}
