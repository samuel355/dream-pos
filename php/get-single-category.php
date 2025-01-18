<?php

session_start();
include_once('../includes/db_connection.php');
header('Content-Type: application/json');
include_once('../includes/sendResponse.php');

function fetchCategory($conn, $category_id){

  try {
    $query = "SELECT * FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $category_id);

    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      $category = mysqli_fetch_assoc($result);
      
      if (!$category) {
        sendResponse('error', 'Category not found');
      }
      
      return [
        'status' => 'success',
        'category' => [
          'id' => $category['id'],
          'name' => $category['name'], 
          'created_by' => $category['created_by'],
          'image' => 'php/' . $category['image']
        ]
      ];
    } else {
      sendResponse('error', 'Failed to fetch category');
    }


  } catch (Exception $e) {
    sendResponse('error', $e->getMessage());
  } finally {
    mysqli_close($conn);
  }
}


$category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : null;

if (!$category_id) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Invalid category ID'
  ]);
  exit;
}

echo json_encode(fetchCategory($conn, $category_id));