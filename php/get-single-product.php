<?php

session_start();
include_once('../includes/db_connection.php');
header('Content-Type: application/json');
include_once('../includes/sendResponse.php');

function fetchProduct($conn, $product_id){

  try {
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $product_id);

    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      $product = mysqli_fetch_assoc($result);
      
      if (!$product) {
        sendResponse('error', 'product not found');
      }
      // Get category name from categories table using category_id
      $category_query = "SELECT name FROM categories WHERE id = ?";
      $category_stmt = mysqli_prepare($conn, $category_query);
      mysqli_stmt_bind_param($category_stmt, 's', $product['category_id']);
      
      if (mysqli_stmt_execute($category_stmt)) {
        $category_result = mysqli_stmt_get_result($category_stmt);
        $category = mysqli_fetch_assoc($category_result);
        if ($category) {
          $product['category_name'] = $category['name'];
        }
      }

      return [
        'status' => 'success',
        'product' => [
          'id' => $product['id'],
          'category_id' => $product['category_id'],
          'category_name' => $product['category_name'],
          'name' => $product['name'], 
          'size' => $product['size'],
          'price' => $product['price'],
          'created_by' => $product['created_by'],
          'image' => 'php/' . $product['image']
        ]
      ];
    } else {
      sendResponse('error', 'Failed to fetch product');
    }


  } catch (Exception $e) {
    sendResponse('error', $e->getMessage());
  } finally {
    mysqli_close($conn);
  }
}


$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;

if (!$product_id) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Invalid product ID'
  ]);
  exit;
}

echo json_encode(fetchProduct($conn, $product_id));