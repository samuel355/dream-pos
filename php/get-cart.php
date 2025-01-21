<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function getCartItems($conn)
{
  $session_id = session_id();

  $query = "SELECT 
              ci.id as cart_id,
              ci.quantity,
              ci.size,
              ci.price as item_price,
              p.id as product_id,
              p.name,
              p.image,
              p.category_id
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.session_id = ?";

  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "s", $session_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $items = [];
  $total_items = 0;
  $subtotal = 0;

  while ($row = mysqli_fetch_assoc($result)) {
    $item_total = $row['quantity'] * $row['item_price'];
    $subtotal += $item_total;
    $total_items += $row['quantity'];

    $items[] = [
      'cart_id' => $row['cart_id'],
      'product_id' => $row['product_id'],
      'category_id' => $row['category_id'],
      'name' => $row['name'],
      'price' => $row['item_price'],
      'size' => $row['size'],
      'quantity' => $row['quantity'],
      'image' => $row['image'],
      'item_total' => $item_total
    ];
  }

  //$tax = $subtotal * 0.1; // 10% tax
  //$total = $subtotal + $tax;
  $total = $subtotal;

  return [
    'status' => 'success',
    'data' => [
      'items' => $items,
      'total_items' => $total_items,
      'subtotal' => $subtotal,
      //'tax' => $tax,
      'total' => $total
    ]
  ];
}

echo json_encode(getCartItems($conn));
