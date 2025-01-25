<?php
include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

if(!isSysAdminOrAdmin()){
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}

function deleteCategory($conn, $product_id)
{
  try {
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $product_id);

    if (mysqli_stmt_execute($stmt)) {
      sendResponse('success', 'Category deleted Successfully');
    } else {
      sendResponse('error', 'Failed to delete category');
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
    'message' => 'Invalid product item ID'
  ]);
  exit;
}

echo json_encode(deleteCategory($conn, $product_id));
