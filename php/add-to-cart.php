<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function addToCart($conn, $product_id, $quantity = 1)
{
  $session_id = session_id();
  $product_id = mysqli_real_escape_string($conn, $product_id);
  $quantity = (int)$quantity;

  // Get product price
  $query = "SELECT price, size, category_id FROM products WHERE id = '$product_id'";

  $result = mysqli_query($conn, $query);
  $product = mysqli_fetch_assoc($result);

  if (!$product) {
    sendResponse('error', 'Product not found');
  }

  if (in_array($product['category_id'], [1, 3, 5])) {
    // Check if any product from these categories already exists in cart
    $check_category_query = "SELECT ci.*, p.category_id, p.name as product_name 
                           FROM cart_items ci 
                           JOIN products p ON ci.product_id = p.id 
                           WHERE ci.session_id = '$session_id' 
                           AND p.category_id IN (1, 3, 5)";

    $category_result = mysqli_query($conn, $check_category_query);

    if (mysqli_num_rows($category_result) > 0) {
      sendResponse('info', 'Main flavors can only be one in cart, remove the existing one in cart to add different flavor');
      $existing_item = mysqli_fetch_assoc($category_result);

      // If the existing item is not the same as the one being added
      if ($existing_item['product_id'] !== $product_id) {
        $message = "You already have a main flavor (" . $existing_item['product_name'] . ") in your cart. " .
          "Please remove it first before adding a new one.";
        sendResponse('info', $message);
        exit;
      }
    }
  }

  // Check if product already in cart
  $check_query = "SELECT id, quantity FROM cart_items 
                 WHERE session_id = '$session_id' AND product_id = '$product_id'";
  $check_result = mysqli_query($conn, $check_query);

  if (mysqli_num_rows($check_result) > 0) {
    // Update quantity
    // $cart_item = mysqli_fetch_assoc($check_result);
    // $new_quantity = $cart_item['quantity'] + $quantity;
    // $update_query = "UPDATE cart_items 
    //                   SET quantity = '$new_quantity' 
    //                   WHERE id = '{$cart_item['id']}'";
    // mysqli_query($conn, $update_query);
    sendResponse('info', 'Finish with this order and create different');
  } else {
    // Insert new item
    $insert_query = "INSERT INTO cart_items (session_id, product_id, quantity, price, size, category_id) 
                      VALUES ('$session_id', '$product_id', '$quantity', '{$product['price']}', '{$product['size']}','{$product['category_id']}' )";
    mysqli_query($conn, $insert_query);
  }

  sendResponse('success', 'Product added to cart');
}

$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

if (!$product_id) {
  sendResponse('error', 'Product ID is required');
  exit;
}

$result = addToCart($conn, $product_id, $quantity);
echo json_encode($result);
