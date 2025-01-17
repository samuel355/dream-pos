<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function getProductSizes($conn)
{
  $query = "SELECT * FROM product_pricing pc
  JOIN categories c ON c.id = pc.category_id
  ORDER BY pc.size_name";
  $result = mysqli_query($conn, $query);

  if (!$result) {
    return ['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn)];
  }


  $product_pricing = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $product_pricing[] = [
      'id' => $row['id'],
      'category_name' => $row['name'],
      'size_name' => $row['size_name'],
      'price' => $row['price'],
      'image' => 'php/' . $row['image']
    ];
  }

  return ['status' => 'success', 'product_pricing' => $product_pricing];
}

echo json_encode(getProductSizes($conn));
