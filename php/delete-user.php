<?php
include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

if(!isSysAdminOrAdmin()){
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}

try {
  $data = json_decode(file_get_contents('php://input'), true);
  $userId = isset($data['user_id']) ? intval($data['user_id']) : 0;

  if ($userId <= 0) {
    throw new Exception('Invalid user ID');
  }

  $query = "DELETE FROM users WHERE id = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "i", $userId);
  mysqli_stmt_execute($stmt);

  if (mysqli_stmt_affected_rows($stmt) === 0) {
    throw new Exception('User not found');
  }

  sendResponse('success', 'User deleted successfully');
} catch (Exception $e) {
  sendResponse('error', $e->getMessage());
} finally {
  mysqli_close($conn);
}
