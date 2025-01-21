<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function getCategories($conn)
{
  //$query = "SELECT * FROM products ORDER BY name";
  // $query = "SELECT p.*, c.name as category_name, pp.size, pp.price 
  //           FROM products p
  //           LEFT JOIN categories c ON p.category_id = c.id
  //           LEFT JOIN product_pricing pp ON p.id = pp.product_id
  //           ORDER BY p.name";

  $query = "SELECT p.*, c.name as category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.id";
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
