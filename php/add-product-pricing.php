<?php
include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

if(!isAdmin() || !isSysAdmin()){
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}

try {

  $size_name = mysqli_real_escape_string($conn, trim($_POST['size-name']));
  $category_id = mysqli_real_escape_string($conn, trim($_POST['category-id']));
  $price = mysqli_real_escape_string($conn, trim($_POST['price']));
  
  if($category_id === '' || $category_id === 'Choose Category'){
    sendResponse('error', 'Select Product Category');
    exit;
  }

  if($size_name === '' || $size_name === 'Select Size'){
    sendResponse('error', 'Select Size');
    exit;
  }

  if(empty($price)){
    sendResponse('error', 'Enter the amount');
    exit;
  }

  $check = mysqli_query($conn, "SELECT size_name, category_id FROM product_pricing WHERE size_name = '$size_name' AND category_id = '$category_id' ");
  if (mysqli_num_rows($check) > 0) {
    sendResponse('error', 'Sorry the price of the category name and it corresponding size exist already');
  }


  //insert data
  $query = "INSERT INTO product_pricing(size_name, category_id, price) 
            VALUES(?, ?, ?)";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "sss", $size_name, $category_id, $price);

  if(!mysqli_stmt_execute($stmt)){
    throw new Exception('Database error occurred');
  }

  sendResponse('success', 'Product pricing created successfully');

} catch (Exception $e) {
  echo json_encode([
    'status' => 'error',
    'message' => $e->getMessage()
  ]);
}

mysqli_close($conn);