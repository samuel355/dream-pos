<?php
include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

if(!isSysAdmin()){
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}
function deleteCategory($conn, $price_id)
{
  try {
    $query = "DELETE FROM product_pricing WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $price_id);

    if (mysqli_stmt_execute($stmt)) {
      sendResponse('success', 'Price deleted Successfully');
    } else {
      sendResponse('error', 'Failed to delete price');
    }

  } catch (Exception $e) {
    sendResponse('error', $e->getMessage());
  } finally {
    mysqli_close($conn);
  }
} 

$price_id = isset($_POST['price_id']) ? (int)$_POST['price_id'] : null;

if (!$price_id) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Invalid price item ID'
  ]);
  exit;
}

echo json_encode(deleteCategory($conn, $price_id));
