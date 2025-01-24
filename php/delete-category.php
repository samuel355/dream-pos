<?php
include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

if(!isAdmin() || !isSysAdmin()){
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}

function deleteCategory($conn, $category_id)
{
  try {
    $query = "DELETE FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $category_id);

    if (mysqli_stmt_execute($stmt)) {
      sendResponse('success', 'Category deleted Successfully');
    } else {
      sendResponse('error', 'Failed to delete category');
    }

    //Todo Delete associated products

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
    'message' => 'Invalid cart item ID'
  ]);
  exit;
}

echo json_encode(deleteCategory($conn, $category_id));
