<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function getCategories($conn)
{
  $query = "SELECT * FROM products ORDER BY name";
  $result = mysqli_query($conn, $query);

  if (!$result) {
    return ['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn)];
  }

  $products = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $products[] = [
      'id' => $row['id'],
      'name' => $row['name'],
      'created_by' => $row['created_by'],
      'image' => 'php/' . $row['image'],
      'category_name' => $row['category_name'],
      'category_id' => $row['category_id'],
      'size' => $row['size'],
      'price' => $row['price']
    ];
  }

  return ['status' => 'success', 'products' => $products];
}

echo json_encode(getCategories($conn));
