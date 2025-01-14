<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function getProductsByCategory($conn, $category_id = null) {
  $query = "SELECT p.*, category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id";
  
  if ($category_id) {
      $category_id = mysqli_real_escape_string($conn, $category_id);
      $query .= " AND p.category_id = '$category_id'";
  }
  
  $result = mysqli_query($conn, $query);
  $products = [];
  
  while ($row = mysqli_fetch_assoc($result)) {
      $products[] = [
          'id' => $row['id'],
          'name' => $row['name'],
          'price' => $row['price'],
          'image' => $row['image'],
          'category_name' => $row['category_name'],
      ];
  }
  
  return $products;
}

$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$products = getProductsByCategory($conn, $category_id);

echo json_encode(['status' => 'success', 'data' => $products]);
?>
