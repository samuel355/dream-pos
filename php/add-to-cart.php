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
$query = "SELECT price FROM products WHERE id = '$product_id'";
  // $query = "SELECT 
  //           pp.id AS product_pricing_id,
  //           pp.price,
  //           pp.size_name,
  //           pp.category_id AS product_pricing_catId,
  //           p.id AS product_id,
  //           p.category_id AS product_catId
  //         FROM product_pricing pp
  //         JOIN products p ON pp.category_id = p.category_id  -- both tables link by category_id
  //         WHERE p.id = '$product_id'";

  $result = mysqli_query($conn, $query);
  $product = mysqli_fetch_assoc($result);


  if (!$product) {
    sendResponse('error', 'Product not found');
  }

  // Check if product already in cart
  $check_query = "SELECT id, quantity FROM cart_items 
                 WHERE session_id = '$session_id' AND product_id = '$product_id'";
  $check_result = mysqli_query($conn, $check_query);

  if (mysqli_num_rows($check_result) > 0) {
    // Update quantity
    $cart_item = mysqli_fetch_assoc($check_result);
    $new_quantity = $cart_item['quantity'] + $quantity;
    $update_query = "UPDATE cart_items 
                      SET quantity = '$new_quantity' 
                      WHERE id = '{$cart_item['id']}'";
    mysqli_query($conn, $update_query);
  } else {
    // Insert new item
    $insert_query = "INSERT INTO cart_items (session_id, product_id, quantity, price, size) 
                      VALUES ('$session_id', '$product_id', '$quantity', '{$product['price']}', '{$product['size_name']}')";
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
